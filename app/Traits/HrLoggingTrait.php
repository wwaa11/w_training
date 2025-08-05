<?php
namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HrLoggingTrait
{
    /**
     * Log important HR actions with standardized format
     */
    protected function logHrAction(string $action, array $data = [], string $level = 'info', $contextUser = null): void
    {
        // Get user context safely
        $userContext = $this->getUserContext($contextUser);

        $logData = [
            'action'     => $action,
            'user_id'    => $userContext['user_id'],
            'user_name'  => $userContext['user_name'],
            'userid'     => $userContext['userid'],
            'timestamp'  => now()->toISOString(),
            'ip_address' => $userContext['ip_address'],
            'user_agent' => $userContext['user_agent'],
            'data'       => $data,
        ];

        Log::channel('hr_actions')->$level(json_encode($logData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Get user context safely, handling cases where auth is not available
     */
    private function getUserContext($contextUser = null): array
    {
        // If context user is provided, use it
        if ($contextUser && is_object($contextUser) && isset($contextUser->id)) {
            return [
                'user_id'    => $contextUser->id,
                'user_name'  => $contextUser->name ?? 'Unknown',
                'userid'     => $contextUser->userid ?? 'Unknown',
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'CLI',
            ];
        }

        try {
            // Try to get authenticated user
            if (auth()->check()) {
                $user = auth()->user();
                return [
                    'user_id'    => $user->id,
                    'user_name'  => $user->name ?? 'Unknown',
                    'userid'     => $user->userid ?? 'Unknown',
                    'ip_address' => request()->ip() ?? '127.0.0.1',
                    'user_agent' => request()->userAgent() ?? 'CLI',
                ];
            }
        } catch (\Exception $e) {
            // Auth not available (e.g., CLI context)
        }

        // Check if we're in CLI context
        if (app()->runningInConsole()) {
            return [
                'user_id'    => null,
                'user_name'  => 'System/CLI',
                'userid'     => 'CLI',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'CLI',
            ];
        }

        // Fallback for web context without auth
        return [
            'user_id'    => null,
            'user_name'  => 'Guest',
            'userid'     => 'GUEST',
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'Unknown',
        ];
    }

    /**
     * Log project creation
     */
    protected function logProjectCreated($project, array $additionalData = []): void
    {
        $this->logHrAction('PROJECT_CREATED', [
            'project_id'          => $project->id,
            'project_name'        => $project->project_name,
            'project_type'        => $project->project_type,
            'registration_period' => [
                'start' => $project->project_start_register,
                'end'   => $project->project_end_register,
            ],
            'features'            => [
                'seat_assignment'  => $project->project_seat_assign,
                'group_assignment' => $project->project_group_assign,
                'register_today'   => $project->project_register_today,
            ],
            'dates_count'         => $project->dates->count(),
            'times_count'         => $project->dates->sum(function ($date) {
                return $date->times->count();
            }),
            'links_count'         => $project->links->count(),
            'additional_data'     => $additionalData,
        ]);
    }

    /**
     * Log project update
     */
    protected function logProjectUpdated($project, array $changes = [], array $additionalData = []): void
    {
        $this->logHrAction('PROJECT_UPDATED', [
            'project_id'      => $project->id,
            'project_name'    => $project->project_name,
            'changes'         => $changes,
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Log project deletion
     */
    protected function logProjectDeleted($project, array $additionalData = []): void
    {
        // Handle both project objects and project data arrays
        if (is_array($project)) {
            $projectData = $project;
        } else {
            $projectData = [
                'project_id'       => $project->id,
                'project_name'     => $project->project_name,
                'project_type'     => $project->project_type,
                'deletion_summary' => [
                    'dates_deleted'         => $project->dates->count(),
                    'times_deleted'         => $project->dates->sum(function ($date) {
                        return $date->times->count();
                    }),
                    'registrations_deleted' => $project->attends->count(),
                    'results_deleted'       => $project->results->count(),
                    'seats_deleted'         => $project->dates->sum(function ($date) {
                        return $date->times->sum(function ($time) {
                            return $time->seats->count();
                        });
                    }),
                ],
            ];
        }

        $this->logHrAction('PROJECT_DELETED', array_merge($projectData, [
            'additional_data' => $additionalData,
        ]));
    }

    /**
     * Log user registration
     */
    protected function logUserRegistration($project, $user, $timeIds, array $additionalData = []): void
    {
        $this->logHrAction('USER_REGISTRATION', [
            'project_id'        => $project->id,
            'project_name'      => $project->project_name,
            'user_id'           => $user->id,
            'user_name'         => $user->name,
            'userid'            => $user->userid,
            'time_ids'          => $timeIds,
            'registration_type' => $project->project_type,
            'additional_data'   => $additionalData,
        ], 'info', $user); // Pass the user object for context
    }

    /**
     * Log user attendance/check-in
     */
    protected function logUserAttendance($project, $user, $time, $attendanceType = 'check_in', array $additionalData = []): void
    {
        $this->logHrAction('USER_ATTENDANCE', [
            'project_id'      => $project->id,
            'project_name'    => $project->project_name,
            'user_id'         => $user->id,
            'user_name'       => $user->name,
            'userid'          => $user->userid,
            'time_id'         => $time->id,
            'time_title'      => $time->time_title,
            'date_title'      => $time->date->date_title,
            'attendance_type' => $attendanceType,
            'check_in_time'   => now()->toISOString(),
            'additional_data' => $additionalData,
        ], 'info', $user);
    }

    /**
     * Log seat assignment
     */
    protected function logSeatAssignment($time, $user, $seatNumber, $assignmentType = 'auto', array $additionalData = []): void
    {
        $this->logHrAction('SEAT_ASSIGNMENT', [
            'time_id'         => $time->id,
            'time_title'      => $time->time_title,
            'project_id'      => $time->date->project_id,
            'project_name'    => $time->date->project->project_name,
            'user_id'         => $user->id,
            'user_name'       => $user->name,
            'userid'          => $user->userid,
            'seat_number'     => $seatNumber,
            'department'      => $user->department ?? 'Unknown',
            'assignment_type' => $assignmentType,
            'additional_data' => $additionalData,
        ], 'info', $user);
    }

    /**
     * Log seat removal
     */
    protected function logSeatRemoval($time, $user, $seatNumber, array $additionalData = []): void
    {
        $this->logHrAction('SEAT_REMOVAL', [
            'time_id'         => $time->id,
            'time_title'      => $time->time_title,
            'project_id'      => $time->date->project_id,
            'project_name'    => $time->date->project->project_name,
            'user_id'         => $user->id,
            'user_name'       => $user->name,
            'userid'          => $user->userid,
            'seat_number'     => $seatNumber,
            'additional_data' => $additionalData,
        ], 'info', $user);
    }

    /**
     * Log registration approval
     */
    protected function logRegistrationApproval($registration, $approvalType = 'approved', array $additionalData = []): void
    {
        $this->logHrAction('REGISTRATION_APPROVAL', [
            'registration_id' => $registration->id,
            'project_id'      => $registration->project_id,
            'project_name'    => $registration->project->project_name,
            'user_id'         => $registration->user_id,
            'user_name'       => $registration->user->name,
            'userid'          => $registration->user->userid,
            'time_id'         => $registration->time_id,
            'time_title'      => $registration->time->time_title,
            'approval_type'   => $approvalType,
            'approval_time'   => now()->toISOString(),
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Log bulk operations
     */
    protected function logBulkOperation($operation, $project, $count, array $details = [], array $additionalData = []): void
    {
        $this->logHrAction('BULK_OPERATION', [
            'operation'       => $operation,
            'project_id'      => $project->id,
            'project_name'    => $project->project_name,
            'affected_count'  => $count,
            'details'         => $details,
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Log export operations
     */
    protected function logExportOperation($project, $exportType, $format = 'excel', array $additionalData = []): void
    {
        $this->logHrAction('EXPORT_OPERATION', [
            'project_id'      => $project->id,
            'project_name'    => $project->project_name,
            'export_type'     => $exportType,
            'format'          => $format,
            'export_time'     => now()->toISOString(),
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Log import operations
     */
    protected function logImportOperation($project, $importType, $importedCount, $skippedCount, array $errors = [], array $additionalData = []): void
    {
        $this->logHrAction('IMPORT_OPERATION', [
            'project_id'      => $project->id,
            'project_name'    => $project->project_name,
            'import_type'     => $importType,
            'imported_count'  => $importedCount,
            'skipped_count'   => $skippedCount,
            'error_count'     => count($errors),
            'errors'          => $errors,
            'import_time'     => now()->toISOString(),
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Log group assignment
     */
    protected function logGroupAssignment($project, $user, $group, $action = 'assigned', array $additionalData = []): void
    {
        $this->logHrAction('GROUP_ASSIGNMENT', [
            'project_id'      => $project->id,
            'project_name'    => $project->project_name,
            'user_id'         => $user->id,
            'user_name'       => $user->name,
            'userid'          => $user->userid,
            'group'           => $group,
            'action'          => $action,
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Log user unregistration
     */
    protected function logUserUnregistration($project, $user, $registration, array $additionalData = []): void
    {
        $this->logHrAction('USER_UNREGISTRATION', [
            'project_id'            => $project->id,
            'project_name'          => $project->project_name,
            'user_id'               => $user->id,
            'user_name'             => $user->name,
            'userid'                => $user->userid,
            'registration_id'       => $registration->id,
            'time_id'               => $registration->time_id,
            'time_title'            => $registration->time->time_title,
            'unregistration_reason' => $additionalData['reason'] ?? 'manual',
            'additional_data'       => $additionalData,
        ]);
    }

    /**
     * Log admin actions
     */
    protected function logAdminAction($action, $targetType, $targetId, array $details = [], array $additionalData = []): void
    {
        $this->logHrAction('ADMIN_ACTION', [
            'action'          => $action,
            'target_type'     => $targetType,
            'target_id'       => $targetId,
            'details'         => $details,
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Log error with context
     */
    protected function logHrError($error, $context, array $additionalData = []): void
    {
        $this->logHrAction('ERROR', [
            'error_message'   => $error->getMessage(),
            'error_code'      => $error->getCode(),
            'error_file'      => $error->getFile(),
            'error_line'      => $error->getLine(),
            'context'         => $context,
            'additional_data' => $additionalData,
        ], 'error');
    }
}
