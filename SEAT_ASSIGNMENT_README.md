# HR Project Seat Assignment System

## Overview

This system implements automatic seat assignment for HR projects where `project_seat_assign = true`. The system ensures that users from the same department are not seated adjacent to each other, and handles exceptions when time slots are full.

## Features

### ✅ Core Functionality
- **Automatic Seat Assignment**: Runs every minute via a background job
- **Department Separation**: Users from the same department cannot sit together
- **Exception Handling**: Creates additional seats when time slots are full
- **Persistent Assignments**: Once assigned, seats do not change
- **Real-time Processing**: Processes new registrations automatically

### ✅ Business Rules
1. **Seat Stability**: Once a seat is assigned, it remains unchanged
2. **Department Separation**: Users from the same department must have at least 1 seat gap
3. **Limit Enforcement**: 
   - When `time_limit = true`, seats beyond `time_max` are NOT created
   - When `time_limit = false`, unlimited seats can be assigned
4. **Automatic Processing**: Runs every minute to handle new registrations

## Database Schema

### Tables Used

#### `hr_seats` Table (Primary Seat Assignment Table)
- `id`: Primary key
- `time_id`: Reference to the time slot
- `user_id`: Reference to the user
- `department`: User's department
- `seat_number`: The seat number assigned
- `seat_delete`: Soft delete flag
- `created_at`, `updated_at`: Timestamps

#### `hr_attends` Table (Registration Table)
- `id`: Primary key
- `project_id`: Reference to the project
- `date_id`: Reference to the date
- `time_id`: Reference to the time slot
- `user_id`: Reference to the user
- `attend_datetime`: When user attended
- `approve_datetime`: When admin approved
- `attend_delete`: Soft delete flag
- `created_at`, `updated_at`: Timestamps

**Note**: The `hr_attends` table does NOT have a `seat_number` field. All seat assignments are stored in the `hr_seats` table.

## Implementation Details

### 1. Job System (`HrProjectSeatAssignment`)

The main job that runs every minute:

```php
// Location: app/Jobs/HrProjectSeatAssignment.php
```

**Key Methods:**
- `handle()`: Main execution method
- `processProjectSeats()`: Process all projects with seat assignment enabled
- `processTimeSlotSeats()`: Process individual time slots
- `assignSeatToUser()`: Assign seats based on department separation rules
- `createSeatAssignment()`: Create seat assignment records

### 2. Seat Assignment Logic

#### Department Separation Algorithm
1. **First Priority**: Find seats with no adjacent users from the same department
2. **Second Priority**: Find any available seat within the limit
3. **No Exceptions**: If no seat is available within the limit, no seat is assigned

#### Seat Assignment Rules
```php
// Check adjacent seats (left and right)
$leftSeat = $currentSeats->get($seatNumber - 1);
$rightSeat = $currentSeats->get($seatNumber + 1);

// If adjacent seats exist and have the same department, this seat is not suitable
if ($leftSeat && $leftSeat->department === $userDepartment) {
    return false;
}

if ($rightSeat && $rightSeat->department === $userDepartment) {
    return false;
}
```

### 3. Time Slot Configuration

Time slots can be configured in two ways:

#### Limited Capacity (`time_limit = true`)
- `time_limit = true` for limited capacity
- `time_max` set to the maximum number of seats
- `time_active = true` and `time_delete = false`

#### Unlimited Capacity (`time_limit = false`)
- `time_limit = false` for unlimited capacity
- `time_max` is ignored (unlimited seats can be assigned)
- `time_active = true` and `time_delete = false`

## Usage

### 1. Starting the System

#### Manual Start
```bash
php artisan hr:start-seat-assignment
```

#### Automatic Start (Recommended)
Add to your server startup script or use a process manager like Supervisor.

### 2. Admin Interface

#### View Seat Assignments
- Navigate to any project with `project_seat_assign = true`
- Click "ดูการจัดที่นั่ง" (View Seat Assignments) button
- View detailed seat assignment information

#### Manual Trigger
- Click "จัดที่นั่ง" (Assign Seats) button to manually trigger seat assignment
- Useful for testing or immediate processing

### 3. API Endpoints

#### Trigger Seat Assignment
```http
POST /hrd/admin/trigger-seat-assignment
```

#### Get Project Seats
```http
GET /hrd/admin/projects/{id}/seats
```

## Configuration

### 1. Project Setup

To enable seat assignment for a project:

1. Set `project_seat_assign = true` in the project settings
2. Ensure the project has time slots with `time_max` limits
3. Users must have department information in the `users` table

### 2. User Department Information

Users must have a `department` field populated in the `users` table. The system will:
- Use the department for seat assignment logic
- Log warnings for users without department information
- Assign "Unknown" department as fallback

## Data Structure

### Seat Assignment Flow
1. User registers for a project (`hr_attends` table)
2. Job checks for unassigned registrations
3. Job assigns seats based on department separation rules (`hr_seats` table)
4. Seat assignments are linked via `user_id` and `time_id`

### Relationships
```php
// HrAttend -> HrSeat relationship
public function seat()
{
    return $this->hasOne(HrSeat::class, 'user_id', 'user_id')
        ->where('time_id', $this->time_id)
        ->where('seat_delete', false);
}
```

## Monitoring and Logging

### Log Files
The system logs important events to Laravel's log files:

- **Info**: Seat assignment operations
- **Warning**: Users without department information
- **Error**: Job execution failures

### Monitoring Commands
```bash
# Check job status
php artisan queue:work

# View logs
tail -f storage/logs/laravel.log
```

## Troubleshooting

### Common Issues

#### 1. Seat Assignment Not Working
- Check if `project_seat_assign = true`
- Verify time slots have proper configuration
- Ensure users have department information

#### 2. Users Not Getting Seats Assigned
- **Limited mode** (`time_limit = true`): If time slot is full (reached `time_max`), no additional seats are created
- **Unlimited mode** (`time_limit = false`): Should always assign seats unless there's an error
- **Check time limits**: Verify `time_limit` and `time_max` settings
- **Check department data**: Ensure user department information is correct
- **Check logs**: Look for "No seat available" messages

#### 3. Job Not Running
- Check queue worker: `php artisan queue:work`
- Verify job is dispatched: Check logs for errors
- Ensure server has proper permissions

#### 4. Department Separation Not Working
- Verify user department data is correct
- Check seat assignment logic in logs
- Ensure no manual seat modifications

#### 5. Time Slot Capacity Issues
- **Limited mode**: No seats are created beyond `time_max` limit
- **Unlimited mode**: Seats are assigned without limit
- **Check configuration**: Verify `time_limit` and `time_max` settings
- **Monitor capacity**: Use admin interface to view current seat assignments

### Debug Commands
```bash
# Test seat assignment manually
php artisan tinker
>>> \App\Jobs\HrProjectSeatAssignment::dispatch();

# Check seat assignments
php artisan tinker
>>> \App\Models\HrSeat::with('user')->get();

# Check registrations without seats
>>> \App\Models\HrAttend::whereDoesntHave('seat')->get();
```

## Performance Considerations

### Optimization Tips
1. **Batch Processing**: The job processes projects in batches
2. **Database Indexing**: Ensure proper indexes on `time_id`, `user_id`, `seat_number`
3. **Memory Management**: Jobs are processed individually to prevent memory issues
4. **Error Handling**: Failed jobs are logged but don't stop the system

### Scalability
- The system can handle multiple projects simultaneously
- Each time slot is processed independently
- Unlimited mode can handle large numbers of registrations

## Security Considerations

### Access Control
- Seat assignment endpoints require admin authentication
- Job execution is restricted to authorized users
- Database operations use proper validation

### Data Integrity
- Soft deletes prevent data loss
- Foreign key constraints maintain referential integrity
- Transaction handling ensures data consistency

## Future Enhancements

### Potential Improvements
1. **Advanced Algorithms**: More sophisticated seat assignment algorithms
2. **Visual Interface**: Graphical seat map display
3. **Custom Rules**: Project-specific seating rules
4. **Analytics**: Seat assignment statistics and reports
5. **Notifications**: Email/SMS notifications for seat assignments

### Integration Possibilities
1. **Room Management**: Integration with room booking systems
2. **Attendance Tracking**: Link seat assignments to attendance
3. **Reporting**: Export seat assignments to reports
4. **Mobile App**: Mobile interface for seat viewing

## Support

For technical support or questions about the seat assignment system:

1. Check the logs for error messages
2. Verify configuration settings
3. Test with a small dataset first
4. Contact the development team for complex issues

---

**Last Updated**: January 2025
**Version**: 1.0.0