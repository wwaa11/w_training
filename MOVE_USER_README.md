# Move Training User Functionality

## Overview
This feature allows administrators to move training users between different time slots. The system automatically handles seat management, including adding seats when necessary.

## Features

### 1. User Search
- Input user ID to search for existing training users
- Display current user information including team, current time slot, and teacher

### 2. Time Slot Filtering
- Filter available time slots by:
  - Team
  - Teacher
  - Session
- Real-time filtering with AJAX requests

### 3. Seat Management
- **Automatic Seat Addition**: If a destination time slot has 0 available seats, the system automatically adds one more seat
- **Seat Recovery**: When moving a user from their current time slot, the system increases available seats in the original slot
- **Seat Allocation**: Properly allocates seats in the destination time slot

### 4. Move Confirmation
- Confirmation dialog before executing the move
- Clear display of user and destination time information
- Success/error feedback with SweetAlert2 notifications

## Access
Navigate to: `Training Admin > Move Training User`

## Usage

### Step 1: Search for User
1. Enter the user ID in the search field
2. Click "ค้นหา" (Search) or press Enter
3. View user information displayed in the info section

### Step 2: Filter Time Slots (Optional)
1. Use the filter dropdowns to narrow down available time slots
2. Click "กรอง" (Filter) to apply filters
3. View filtered results in the time slots section

### Step 3: Select Destination Time
1. Review available time slots with seat information
2. Click "เลือกรอบนี้" (Select this time) on the desired time slot
3. Confirm the move in the confirmation dialog

### Step 4: Confirm Move
1. Review the confirmation details
2. Click "ยืนยันการย้าย" (Confirm Move) to proceed
3. Wait for success confirmation

## Technical Details

### Database Changes
- No new database tables required
- Uses existing `training_users`, `training_times`, and related tables
- Updates `time_id` in `training_users` table
- Updates `available_seat` and `max_seat` in `training_times` table

### API Endpoints
- `GET /training/admin/move` - Main page
- `POST /training/admin/move/get-user-info` - Get user information
- `POST /training/admin/move/get-available-times` - Get filtered time slots
- `POST /training/admin/move/user` - Execute user move

### Security
- Requires admin authentication (`HrAdmin` middleware)
- Input validation on all endpoints
- Logging of all move operations

### Error Handling
- User not found validation
- Time slot not found validation
- Database transaction safety
- User-friendly error messages in Thai

## Files Modified/Created

### New Files
- `resources/views/training/admin/move/index.blade.php` - Main view
- `MOVE_USER_README.md` - This documentation

### Modified Files
- `app/Http/Controllers/TrainingController.php` - Added move user methods
- `routes/web.php` - Added move user routes
- `resources/views/training/admin/index.blade.php` - Added navigation link

### New Controller Methods
- `adminMoveUserIndex()` - Display main page
- `adminMoveUser()` - Execute user move
- `adminGetUserInfo()` - Get user information
- `adminGetAvailableTimes()` - Get filtered time slots

## Browser Compatibility
- Modern browsers with ES6 support
- Requires jQuery and SweetAlert2 (already included in layout)
- Responsive design for mobile and desktop

## Testing
1. Access the move user page
2. Search for an existing user ID
3. Filter time slots if needed
4. Select a destination time slot
5. Confirm the move
6. Verify the user has been moved successfully
7. Check that seat counts are updated correctly

## Troubleshooting
- **User not found**: Ensure the user ID exists in the training system
- **No time slots available**: Check if there are active time slots in the system
- **Permission denied**: Ensure you have admin access
- **Database errors**: Check database connectivity and table structure
