<?php
namespace App\Jobs;

use App\Models\HrAttend;
use App\Models\HrProject;
use App\Models\HrSeat;
use App\Models\HrTime;
use App\Models\User;
use App\Traits\HrLoggingTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HrProjectSeatAssignment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, HrLoggingTrait;

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
        $this->logHrAction('SEAT_ASSIGNMENT_JOB_STARTED', [
            'project_id' => $this->projectId,
            'job_type'   => 'HR_PROJECT_SEAT_ASSIGNMENT',
        ]);

        try {
            if ($this->projectId) {
                // Get project by ID
                $project = HrProject::with(['dates.times.attends'])->find($this->projectId);

                if ($project) {
                    $assignedCount = 0;
                    $skippedCount  = 0;
                    $errorCount    = 0;

                    $project->dates->each(function ($date) use (&$assignedCount, &$skippedCount, &$errorCount) {
                        $date->times->each(function ($time) use (&$assignedCount, &$skippedCount, &$errorCount) {
                            $time->attends->each(function ($attendance) use (&$assignedCount, &$skippedCount, &$errorCount) {
                                if ($attendance->seat_id == null) {
                                    $result = $this->assignSeatForAttendance($attendance->id);
                                    if ($result === 'assigned') {
                                        $assignedCount++;
                                    } elseif ($result === 'skipped') {
                                        $skippedCount++;
                                    } else {
                                        $errorCount++;
                                    }
                                }
                            });
                        });
                    });

                    $this->logHrAction('SEAT_ASSIGNMENT_JOB_COMPLETED', [
                        'project_id'      => $project->id,
                        'project_name'    => $project->project_name,
                        'assigned_count'  => $assignedCount,
                        'skipped_count'   => $skippedCount,
                        'error_count'     => $errorCount,
                        'total_processed' => $assignedCount + $skippedCount + $errorCount,
                    ]);
                } else {
                    $this->logHrAction('SEAT_ASSIGNMENT_JOB_ERROR', [
                        'project_id' => $this->projectId,
                        'error'      => 'Project not found',
                    ], 'warning');
                }
            }

        } catch (\Exception $e) {
            $this->logHrError($e, 'HR Project Seat Assignment Job', [
                'project_id' => $this->projectId,
            ]);
        }
    }

    /**
     * Assign seat for a specific attendance record
     */
    private function assignSeatForAttendance($attendId): string
    {
        try {
            $attendance = HrAttend::with(['project', 'time', 'user'])->find($attendId);

            if (! $attendance) {
                $this->logHrAction('SEAT_ASSIGNMENT_SKIPPED', [
                    'attend_id' => $attendId,
                    'reason'    => 'Attendance record not found',
                ], 'warning');
                return 'skipped';
            }

            // Check if project has seat assignment enabled
            if (! $attendance->project || ! $attendance->project->project_seat_assign) {
                return 'skipped';
            }

            // Check if attendance is not deleted
            if ($attendance->attend_delete) {
                return 'skipped';
            }

            // Check if seat is already assigned
            $existingSeat = HrSeat::where('time_id', $attendance->time_id)
                ->where('user_id', $attendance->user_id)
                ->where('seat_delete', false)
                ->first();

            if ($existingSeat) {
                $this->logHrAction('SEAT_ASSIGNMENT_SKIPPED', [
                    'attend_id' => $attendId,
                    'user_id'   => $attendance->user_id,
                    'time_id'   => $attendance->time_id,
                    'reason'    => 'Seat already assigned',
                ], 'info');
                return 'skipped';
            }

            // Get user department
            $userDepartment = $this->getUserDepartment($attendance->user_id);
            if (! $userDepartment) {
                $this->logHrAction('SEAT_ASSIGNMENT_SKIPPED', [
                    'attend_id' => $attendId,
                    'user_id'   => $attendance->user_id,
                    'reason'    => 'No department found for user',
                ], 'warning');
                return 'skipped';
            }

            // Assign seat
            $result = $this->assignSeatToUser($attendance, $userDepartment);
            return $result;

        } catch (\Exception $e) {
            $this->logHrError($e, 'Error assigning seat for attendance', [
                'attend_id' => $attendId,
            ]);
            return 'error';
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
    private function assignSeatToUser(HrAttend $attendance, string $userDepartment): string
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
            return 'assigned';
        } else {
            if ($time->time_limit) {
                $this->logHrAction('SEAT_ASSIGNMENT_FAILED', [
                    'user_id'    => $attendance->user_id,
                    'time_id'    => $time->id,
                    'time_title' => $time->time_title,
                    'reason'     => 'Time slot is full',
                    'limit'      => $time->time_max,
                ], 'warning');
            } else {
                $this->logHrAction('SEAT_ASSIGNMENT_FAILED', [
                    'user_id'    => $attendance->user_id,
                    'time_id'    => $time->id,
                    'time_title' => $time->time_title,
                    'reason'     => 'No seat available (unlimited mode)',
                ], 'warning');
            }
            return 'skipped';
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
            $this->logHrAction('SEAT_ASSIGNMENT_CONFLICT', [
                'time_id'          => $time->id,
                'time_title'       => $time->time_title,
                'seat_number'      => $seatNumber,
                'existing_user_id' => $existingSeat->user_id,
                'new_user_id'      => $attendance->user_id,
                'reason'           => 'Seat number already assigned to another user',
            ], 'warning');
            return;
        }

        // Check if user already has a seat assignment for this time slot
        $existingUserSeat = HrSeat::where('time_id', $time->id)
            ->where('user_id', $attendance->user_id)
            ->where('seat_delete', false)
            ->first();

        if ($existingUserSeat) {
            // Update existing user's seat assignment
            $oldSeatNumber = $existingUserSeat->seat_number;
            $existingUserSeat->update([
                'seat_number' => $seatNumber,
                'department'  => $userDepartment,
            ]);

            $this->logSeatAssignment($time, $attendance->user, $seatNumber, 'updated', [
                'old_seat_number' => $oldSeatNumber,
                'new_seat_number' => $seatNumber,
            ]);
        } else {
            // Create new seat assignment
            HrSeat::create([
                'time_id'     => $time->id,
                'user_id'     => $attendance->user_id,
                'department'  => $userDepartment,
                'seat_number' => $seatNumber,
                'seat_delete' => false,
            ]);

            $this->logSeatAssignment($time, $attendance->user, $seatNumber, 'auto');
        }
    }
}
