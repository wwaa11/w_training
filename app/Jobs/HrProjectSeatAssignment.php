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

    protected $projectId;

    /**
     * Create a new job instance.
     */
    public function __construct($projectId = null)
    {
        $this->projectId = $projectId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::error("Start Job");
        try {
            if ($this->projectId) {
                // Get project by ID
                $project = HrProject::with(['dates.times.attends'])->find($this->projectId);

                if ($project) {
                    $project->dates->each(function ($date) {
                        $date->times->each(function ($time) {
                            $time->attends->each(function ($attendance) {
                                if ($attendance->seat_id == null) {
                                    $this->assignSeatForAttendance($attendance->id);
                                }
                            });
                        });
                    });
                    Log::error("HR Project Seat Assignment Job completed for project: {$project->project_name}");
                } else {
                    Log::warning("Project with ID {$this->projectId} not found");
                }
            }

        } catch (\Exception $e) {
            Log::error('HR Project Seat Assignment Job Error: ' . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Assign seat for a specific attendance record
     */
    private function assignSeatForAttendance($attendId): void
    {
        try {
            $attendance = HrAttend::with(['project', 'time', 'user'])->find($attendId);

            if (! $attendance) {
                Log::warning("Attendance record not found: {$attendId}");
                return;
            }

            // Check if project has seat assignment enabled
            if (! $attendance->project || ! $attendance->project->project_seat_assign) {
                return;
            }

            // Check if attendance is not deleted
            if ($attendance->attend_delete) {
                return;
            }

            // Check if seat is already assigned
            $existingSeat = HrSeat::where('time_id', $attendance->time_id)
                ->where('user_id', $attendance->user_id)
                ->where('seat_delete', false)
                ->first();

            if ($existingSeat) {
                Log::info("Seat already assigned for user {$attendance->user_id} in time slot {$attendance->time_id}");
                return;
            }

            // Get user department
            $userDepartment = $this->getUserDepartment($attendance->user_id);
            if (! $userDepartment) {
                Log::warning("No department found for user {$attendance->user_id}");
                return;
            }

            // Assign seat
            $this->assignSeatToUser($attendance, $userDepartment);

        } catch (\Exception $e) {
            Log::error('Error assigning seat for attendance: ' . $e->getMessage(), [
                'attend_id' => $attendId,
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ]);
        }
    }

    /**
     * Get user department
     */
    private function getUserDepartment($userId): ?string
    {
        $user = User::find($userId);
        if (! $user) {
            return null;
        }

        return $user->department ?? 'Unknown';
    }

    /**
     * Assign seat to user based on department separation rules
     */
    private function assignSeatToUser(HrAttend $attendance, string $userDepartment): void
    {
        $time = $attendance->time;

        // Get current seat assignments for this time slot
        $currentSeats = HrSeat::where('time_id', $time->id)
            ->where('seat_delete', false)
            ->get()
            ->keyBy('seat_number');

        // Determine max seats
        if (! $time->time_limit) {
            $maxSeats = 999999; // Very high number for unlimited
        } else {
            $maxSeats = $time->time_max ?? 100;
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

        // Create seat assignment if found
        if ($assignedSeat) {
            $this->createSeatAssignment($time, $attendance, $userDepartment, $assignedSeat);
            Log::info("Seat {$assignedSeat} assigned to user {$attendance->user_id} in time slot {$time->id}");
        } else {
            if ($time->time_limit) {
                Log::warning("No seat available for user {$attendance->user_id} in time slot {$time->id} - time slot is full (limit: {$time->time_max})");
            } else {
                Log::warning("No seat available for user {$attendance->user_id} in time slot {$time->id} - unexpected (unlimited mode)");
            }
        }
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
    private function createSeatAssignment(HrTime $time, HrAttend $attendance, string $userDepartment, int $seatNumber): void
    {
        // Check if seat assignment already exists for this seat number
        $existingSeat = HrSeat::where('time_id', $time->id)
            ->where('seat_number', $seatNumber)
            ->where('seat_delete', false)
            ->first();

        if ($existingSeat) {
            // Log warning and skip - don't overwrite existing seat assignments
            Log::warning("Seat number {$seatNumber} is already assigned to user {$existingSeat->user_id} in time slot {$time->id}. Skipping assignment for user {$attendance->user_id}.");
            return;
        }

        // Check if user already has a seat assignment for this time slot
        $existingUserSeat = HrSeat::where('time_id', $time->id)
            ->where('user_id', $attendance->user_id)
            ->where('seat_delete', false)
            ->first();

        if ($existingUserSeat) {
            // Update existing user's seat assignment
            $existingUserSeat->update([
                'seat_number' => $seatNumber,
                'department'  => $userDepartment,
            ]);
            Log::info("Updated seat assignment for user {$attendance->user_id} from seat {$existingUserSeat->seat_number} to seat {$seatNumber} in time slot {$time->id}");
        } else {
            // Create new seat assignment
            HrSeat::create([
                'time_id'     => $time->id,
                'user_id'     => $attendance->user_id,
                'department'  => $userDepartment,
                'seat_number' => $seatNumber,
                'seat_delete' => false,
            ]);
            Log::info("Created new seat assignment: user {$attendance->user_id} assigned to seat {$seatNumber} in time slot {$time->id}");
        }
    }
}
