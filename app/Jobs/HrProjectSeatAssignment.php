<?php
namespace App\Jobs;

use App\Models\HrAttend;
use App\Models\HrProject;
use App\Models\HrSeat;
use App\Models\HrTime;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HrProjectSeatAssignment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get all active projects with seat assignment enabled
            $projects = HrProject::where('project_seat_assign', true)
                ->where('project_active', true)
                ->where('project_delete', false)
                ->with(['dates.times'])
                ->get();

            foreach ($projects as $project) {
                $this->processProjectSeats($project);
            }

            Log::info('HR Project Seat Assignment Job completed successfully');

        } catch (\Exception $e) {
            Log::error('HR Project Seat Assignment Job Error: ' . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Process seat assignment for a specific project
     */
    private function processProjectSeats(HrProject $project): void
    {
        foreach ($project->dates as $date) {
            foreach ($date->times as $time) {
                $this->processTimeSlotSeats($time);
            }
        }
    }

    /**
     * Process seat assignment for a specific time slot
     */
    private function processTimeSlotSeats(HrTime $time): void
    {
        // Skip if time slot is deleted or inactive
        if ($time->time_delete || ! $time->time_active) {
            return;
        }

        // Get all registrations for this time slot that don't have seats assigned
        $unassignedRegistrations = HrAttend::where('time_id', $time->id)
            ->where('attend_delete', false)
            ->whereDoesntHave('seat') // Check if no seat assignment exists
            ->with(['user'])
            ->orderBy('created_at', 'asc')
            ->get();

        if ($unassignedRegistrations->isEmpty()) {
            return;
        }

        // Get current seat assignments for this time slot
        $currentSeats = HrSeat::where('time_id', $time->id)
            ->where('seat_delete', false)
            ->get()
            ->keyBy('seat_number');

        // Get user department information
        $userDepartments = $this->getUserDepartments($unassignedRegistrations->pluck('user_id'));

        foreach ($unassignedRegistrations as $registration) {
            $userDepartment = $userDepartments[$registration->user_id] ?? null;

            if (! $userDepartment) {
                Log::warning("No department found for user {$registration->user_id}");
                continue;
            }

            $assignedSeat = $this->assignSeatToUser($time, $registration, $userDepartment, $currentSeats);

            if ($assignedSeat) {
                $currentSeats->put($assignedSeat, $assignedSeat);
            }
        }
    }

    /**
     * Get department information for users
     */
    private function getUserDepartments($userIds): array
    {
        $departments = [];

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            $department = $user->department ?? null;

            if ($department) {
                $departments[$user->id] = $department;
            } else {
                // If no department found, use a default or log warning
                Log::warning("No department found for user {$user->id} ({$user->userid})");
                $departments[$user->id] = 'Unknown';
            }
        }

        return $departments;
    }

    /**
     * Assign a seat to a user based on department separation rules
     */
    private function assignSeatToUser(HrTime $time, HrAttend $registration, string $userDepartment, $currentSeats): ?int
    {
        // If time_limit is false, assign without limit
        if (! $time->time_limit) {
            $maxSeats = 999999; // Very high number for unlimited
        } else {
            $maxSeats = $time->time_max ?? 100; // Use time_max if time_limit is true
        }

        $assignedSeat = null;

        // First, try to find a seat that doesn't have adjacent users from the same department
        for ($seatNumber = 1; $seatNumber <= $maxSeats; $seatNumber++) {
            if ($this->isSeatAvailable($seatNumber, $currentSeats) &&
                $this->isSeatSuitableForDepartment($seatNumber, $userDepartment, $currentSeats)) {

                $assignedSeat = $seatNumber;
                break;
            }
        }

        // If no suitable seat found, try to find any available seat within the limit
        if (! $assignedSeat) {
            for ($seatNumber = 1; $seatNumber <= $maxSeats; $seatNumber++) {
                if ($this->isSeatAvailable($seatNumber, $currentSeats)) {
                    $assignedSeat = $seatNumber;
                    break;
                }
            }
        }

        // Assign the seat if found
        if ($assignedSeat) {
            $this->createSeatAssignment($time, $registration, $userDepartment, $assignedSeat);
        } else {
            if ($time->time_limit) {
                Log::info("No seat available for user {$registration->user_id} in time slot {$time->id} - time slot is full (limit: {$time->time_max})");
            } else {
                Log::info("No seat available for user {$registration->user_id} in time slot {$time->id} - unexpected (unlimited mode)");
            }
        }

        return $assignedSeat;
    }

    /**
     * Check if a seat is available
     */
    private function isSeatAvailable(int $seatNumber, $currentSeats): bool
    {
        return ! $currentSeats->has($seatNumber);
    }

    /**
     * Check if a seat is suitable for a department (no adjacent same department)
     */
    private function isSeatSuitableForDepartment(int $seatNumber, string $userDepartment, $currentSeats): bool
    {
        // Check adjacent seats (left and right)
        $leftSeat  = $currentSeats->get($seatNumber - 1);
        $rightSeat = $currentSeats->get($seatNumber + 1);

        // If adjacent seats exist and have the same department, this seat is not suitable
        if ($leftSeat && $leftSeat->department === $userDepartment) {
            return false;
        }

        if ($rightSeat && $rightSeat->department === $userDepartment) {
            return false;
        }

        return true;
    }

    /**
     * Create a seat assignment record
     */
    private function createSeatAssignment(HrTime $time, HrAttend $registration, string $userDepartment, int $seatNumber): void
    {
        // Check if seat assignment already exists
        $existingSeat = HrSeat::where('time_id', $time->id)
            ->where('seat_number', $seatNumber)
            ->where('seat_delete', false)
            ->first();

        if ($existingSeat) {
            // Update existing seat assignment
            $existingSeat->update([
                'user_id'    => $registration->user_id,
                'department' => $userDepartment,
            ]);
        } else {
            // Create new seat assignment
            HrSeat::create([
                'time_id'     => $time->id,
                'user_id'     => $registration->user_id,
                'department'  => $userDepartment,
                'seat_number' => $seatNumber,
                'seat_delete' => false,
            ]);
        }
    }
}
