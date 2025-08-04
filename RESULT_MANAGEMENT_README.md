# Result Management Feature

## Overview
This feature allows HR administrators to manage evaluation results for HR projects by uploading Excel files. It's similar to the existing `ScoresImport.php` functionality but specifically designed for HR results using the `hr_result` and `hr_result_header` tables.

## Features

### 1. Result Management Page
- **Location**: `/hrd/admin/projects/{id}/results`
- **Access**: HR Admin users only
- **Purpose**: Upload Excel files with evaluation results and manage existing results

### 2. Excel Template Download
- **Feature**: Download a pre-filled Excel template with all registered users
- **Format**: Includes columns for up to 10 evaluation criteria
- **Columns**: 
  - ลำดับ (Order)
  - รหัสพนักงาน (Employee ID)
  - ชื่อ-นามสกุล (Name)
  - ตำแหน่ง (Position)
  - หน่วยงาน (Department)
  - ผลการประเมิน 1-10 (Evaluation Results 1-10)
- **Multiple Attendance Handling**: If a user has multiple attendance records, only one row is shown in the template

### 3. Excel Import
- **File Types**: `.xlsx`, `.xls`
- **Process**: 
  - First row: Defines evaluation criteria names (stored in `hr_result_header`)
  - Subsequent rows: Contains user results (stored in `hr_result`)
- **Validation**: Matches users by employee ID from existing attendance records
- **Multiple Attendance Handling**: If a user has multiple attendance records, the same result data is applied to all their attendance records

### 4. Data Management
- **View Results**: Display all imported results in a table format (one row per user)
- **Clear Data**: Remove all results and evaluation criteria for a project
- **Header Display**: Show evaluation criteria names when available

## Database Structure

### hr_result_headers Table
```sql
- project_id (Foreign Key)
- result_1_name through result_10_name (String)
```

### hr_results Table
```sql
- project_id (Foreign Key)
- attend_id (Foreign Key to hr_attends)
- user_id (Foreign Key to users)
- result_1 through result_10 (String)
```

## Multiple Attendance Records Handling

### Import Behavior
When a user has multiple attendance records for the same project:
1. **Template Generation**: Only one row is created per user in the Excel template
2. **Data Import**: The same result data is applied to all attendance records for that user
3. **Database Storage**: Multiple `hr_result` records are created (one for each attendance record) with identical data
4. **Display**: Only one result row is shown per user in the management interface

### Example Scenario
If User A has 3 attendance records for Project X:
- Excel template shows: 1 row for User A
- Import creates: 3 `hr_result` records (one for each attendance)
- Management interface shows: 1 row for User A
- All 3 attendance records have the same evaluation results

## Files Created/Modified

### New Files
1. `app/Imports/HrResultsImport.php` - Excel import handler
2. `app/Exports/Hr/ResultsTemplateExport.php` - Excel template generator
3. `resources/views/hrd/admin/projects/results.blade.php` - Results management view

### Modified Files
1. `app/Http/Controllers/HRController.php` - Added result management functions
2. `routes/web.php` - Added result management routes
3. `resources/views/hrd/admin/projects/show.blade.php` - Added results button

## Usage Instructions

### For Administrators

1. **Access Result Management**:
   - Go to HRD Admin → Projects → Select a project
   - Click "จัดการผลการประเมิน" (Manage Results) button

2. **Download Template**:
   - Click "ดาวน์โหลดเทมเพลต" (Download Template)
   - The Excel file will contain all registered users for the project (one row per user)

3. **Fill in Results**:
   - Edit the first row to define evaluation criteria names
   - Fill in evaluation results for each user
   - Save the file

4. **Upload Results**:
   - Click "เลือกไฟล์" (Choose File)
   - Select your completed Excel file
   - Click "นำเข้าข้อมูล" (Import Data)

5. **View Results**:
   - Results will be displayed in a table format (one row per user)
   - Evaluation criteria names will be shown as column headers

6. **Clear Data** (if needed):
   - Click "ลบข้อมูลทั้งหมด" (Clear All Data)
   - Confirm the action

### Excel File Format

The Excel file should have the following structure:

| Column A | Column B | Column C | Column D | Column E | Column F | Column G | ... | Column O |
|----------|----------|----------|----------|----------|----------|----------|-----|----------|
| ลำดับ | รหัสพนักงาน | ชื่อ-นามสกุล | ตำแหน่ง | หน่วยงาน | ผลการประเมิน 1 | ผลการประเมิน 2 | ... | ผลการประเมิน 10 |
| 1 | 001 | John Doe | Manager | IT | A | B | ... | A |
| 2 | 002 | Jane Smith | Staff | HR | B | A | ... | B |

**Important Notes**:
- Column F (index 5) contains the first evaluation criteria name
- Columns F-O (indices 5-14) contain evaluation criteria names and results
- User matching is done by employee ID (Column B)
- Only users with existing attendance records will be processed
- **Multiple Attendance**: If a user has multiple attendance records, only one row appears in the template, but the same result data applies to all their attendance records

## Error Handling

- **File Validation**: Only `.xlsx` and `.xls` files are accepted
- **User Matching**: Users not found in attendance records are logged but not processed
- **Import Errors**: Detailed error messages are displayed if import fails
- **Data Integrity**: Existing results are updated, new results are created
- **Multiple Attendance Logging**: System logs when users have multiple attendance records

## Security

- **Access Control**: Only HR Admin users can access this feature
- **File Validation**: Strict file type validation
- **Data Validation**: User existence and attendance validation
- **CSRF Protection**: All forms include CSRF tokens

## Technical Details

### Import Process
1. Read Excel file using Maatwebsite Excel package
2. First row: Update/create `hr_result_header` record
3. Subsequent rows: Match users by employee ID
4. For each user: Create/update `hr_result` records for all their attendance records
5. Log unmatched users and multiple attendance scenarios for review

### Export Process
1. Query project attendance records grouped by user_id
2. Generate Excel template with unique user data
3. Apply styling (bold headers)
4. Download as `.xlsx` file

### Database Relationships
- `HrProject` → `HrResultHeader` (One-to-One)
- `HrProject` → `HrResult` (One-to-Many)
- `HrAttend` → `HrResult` (One-to-One)
- `User` → `HrResult` (One-to-Many)

### Multiple Attendance Logic
- **Template**: Group attendance records by `user_id`, show only first record
- **Import**: For each user, create result records for all their attendance records
- **Display**: Group result records by `user_id`, show only first record
- **Data Consistency**: All attendance records for a user get identical result data 