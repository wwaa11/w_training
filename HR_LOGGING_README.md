# HR Logging System

This document describes the comprehensive HR logging system implemented for tracking important actions in the HR training management system.

## Overview

The HR logging system provides detailed audit trails for all important actions performed by users and administrators in the HR training system. All logs are stored in monthly log files with standardized JSON format for easy parsing and analysis.

## Features

- **Monthly Log Files**: Logs are automatically organized by month (e.g., `HR_Log_2025-01.log`)
- **Structured JSON Format**: All log entries are in JSON format for easy parsing
- **Comprehensive Coverage**: Logs all important user and admin actions
- **User Context**: Includes user information, IP address, and user agent
- **Detailed Data**: Captures relevant data for each action type
- **Error Logging**: Tracks errors with full context
- **Command Line Interface**: Built-in command for viewing and filtering logs

## Log File Location

Log files are stored in: `storage/logs/HR_Log_YYYY-MM.log`

## Log Entry Structure

Each log entry contains the following structure:

```json
{
    "action": "ACTION_TYPE",
    "user_id": 123,
    "user_name": "John Doe",
    "userid": "JD001",
    "timestamp": "2025-01-15T10:30:00.000000Z",
    "ip_address": "192.168.1.100",
    "user_agent": "Mozilla/5.0...",
    "data": {
        // Action-specific data
    }
}
```

## Logged Actions

### User Actions
- **USER_REGISTRATION**: User registers for project sessions
- **USER_ATTENDANCE**: User checks in for attendance
- **USER_UNREGISTRATION**: User cancels registration
- **USER_RESELECTION**: User clears and re-registers

### Admin Actions
- **PROJECT_CREATED**: Admin creates new project
- **PROJECT_UPDATED**: Admin updates project details
- **PROJECT_DELETED**: Admin deletes project
- **REGISTRATION_APPROVAL**: Admin approves/unapproves registrations
- **BULK_APPROVAL**: Admin bulk approves registrations
- **PASSWORD_RESET**: Admin resets user password

### Seat Management
- **SEAT_ASSIGNMENT**: Seat assigned to user (auto/manual)
- **SEAT_REMOVAL**: Seat assignment removed
- **CLEAR_SEATS**: All seats cleared for time slot
- **SEAT_ASSIGNMENT_TRIGGER**: Bulk seat assignment triggered

### Data Operations
- **EXPORT_OPERATION**: Data exported (Excel/PDF)
- **IMPORT_OPERATION**: Data imported from file
- **CLEAR_RESULTS**: Results cleared from project
- **CLEANUP_DUPLICATE_SEATS**: Duplicate seats cleaned up

### Group Management
- **GROUP_ASSIGNMENT**: User assigned to group
- **BULK_OPERATION**: Various bulk operations

### Error Tracking
- **ERROR**: System errors with full context

## Usage Examples

### Viewing Logs via Command Line

```bash
# View current month's logs
php artisan hr:logs

# View specific month
php artisan hr:logs --month=2025-01

# Filter by action type
php artisan hr:logs --action=USER_REGISTRATION

# Filter by user
php artisan hr:logs --user="John Doe"

# Filter by project
php artisan hr:logs --project="Training Project"

# Limit results
php artisan hr:logs --limit=20

# Output in JSON format
php artisan hr:logs --json

# Combine filters
php artisan hr:logs --month=2025-01 --action=SEAT_ASSIGNMENT --limit=10
```

### Programmatic Usage

The logging system is automatically integrated into the HRController. To add logging to new methods:

```php
use App\Traits\HrLoggingTrait;

class YourController extends Controller
{
    use HrLoggingTrait;

    public function yourMethod()
    {
        try {
            // Your logic here
            
            // Log the action
            $this->logHrAction('YOUR_ACTION', [
                'key' => 'value',
                'additional_data' => 'info'
            ]);
            
        } catch (\Exception $e) {
            // Log errors
            $this->logHrError($e, 'context', [
                'additional_data' => 'error_context'
            ]);
        }
    }
}
```

## Available Logging Methods

### Basic Logging
- `logHrAction($action, $data, $level)`: Log any action with custom data

### Specific Action Logging
- `logProjectCreated($project, $additionalData)`: Log project creation
- `logProjectUpdated($project, $changes, $additionalData)`: Log project updates
- `logProjectDeleted($project, $additionalData)`: Log project deletion
- `logUserRegistration($project, $user, $timeIds, $additionalData)`: Log user registration
- `logUserAttendance($project, $user, $time, $attendanceType, $additionalData)`: Log attendance
- `logSeatAssignment($time, $user, $seatNumber, $assignmentType, $additionalData)`: Log seat assignment
- `logSeatRemoval($time, $user, $seatNumber, $additionalData)`: Log seat removal
- `logRegistrationApproval($registration, $approvalType, $additionalData)`: Log approval actions
- `logBulkOperation($operation, $project, $count, $details, $additionalData)`: Log bulk operations
- `logExportOperation($project, $exportType, $format, $additionalData)`: Log exports
- `logImportOperation($project, $importType, $importedCount, $skippedCount, $errors, $additionalData)`: Log imports
- `logGroupAssignment($project, $user, $group, $action, $additionalData)`: Log group assignments
- `logUserUnregistration($project, $user, $registration, $additionalData)`: Log unregistrations
- `logAdminAction($action, $targetType, $targetId, $details, $additionalData)`: Log admin actions
- `logHrError($error, $context, $additionalData)`: Log errors

## Configuration

The logging channel is configured in `config/logging.php`:

```php
'hr_actions' => [
    'driver' => 'daily',
    'path' => storage_path('logs/HR_Log_' . date('Y-m') . '.log'),
    'level' => env('LOG_LEVEL', 'info'),
    'days' => env('LOG_DAILY_DAYS', 365),
    'replace_placeholders' => true,
],
```

## Log Retention

- Log files are kept for 365 days by default
- Files are automatically rotated by month
- Old files are automatically cleaned up

## Security Considerations

- Logs contain sensitive user information
- Access to log files should be restricted to authorized personnel
- Log files should be backed up regularly
- Consider implementing log encryption for highly sensitive environments

## Monitoring and Analysis

### Common Use Cases

1. **Audit Trails**: Track who did what and when
2. **Troubleshooting**: Identify issues and their context
3. **Usage Analytics**: Understand system usage patterns
4. **Security Monitoring**: Detect suspicious activities
5. **Compliance**: Meet regulatory requirements for audit trails

### Sample Queries

```bash
# Find all seat assignments for a specific user
php artisan hr:logs --user="user123" --action=SEAT_ASSIGNMENT

# Check all admin actions for a project
php artisan hr:logs --project="Project Name" --action=ADMIN_ACTION

# Monitor registration patterns
php artisan hr:logs --action=USER_REGISTRATION --limit=100

# Track export activities
php artisan hr:logs --action=EXPORT_OPERATION

# Find errors
php artisan hr:logs --action=ERROR
```

## Integration Points

The logging system is integrated into:

- **HRController**: All major HR operations
- **Job Classes**: Background seat assignment jobs
- **Import/Export Classes**: Data operations
- **Admin Interfaces**: Administrative actions

## Future Enhancements

Potential improvements for the logging system:

1. **Real-time Monitoring**: Web interface for live log monitoring
2. **Alert System**: Notifications for critical actions
3. **Analytics Dashboard**: Visual representation of log data
4. **Advanced Filtering**: More sophisticated query capabilities
5. **Log Aggregation**: Centralized log management
6. **Performance Metrics**: Track system performance through logs

## Troubleshooting

### Common Issues

1. **Log files not created**: Check storage permissions
2. **Missing log entries**: Verify logging channel configuration
3. **Permission errors**: Ensure proper file permissions on storage/logs
4. **Large log files**: Consider log rotation or archiving

### Debug Commands

```bash
# Check if log file exists
ls -la storage/logs/HR_Log_*.log

# View last 50 lines of current log
tail -50 storage/logs/HR_Log_$(date +%Y-%m).log

# Check log file permissions
stat storage/logs/HR_Log_$(date +%Y-%m).log
```

## Support

For issues with the logging system:

1. Check the log files directly
2. Use the `hr:logs` command for filtering
3. Verify configuration in `config/logging.php`
4. Check Laravel's main log for system errors
5. Ensure proper permissions on storage directory 