<?php
namespace App\Imports;

use App\Models\HrDate;
use App\Models\HrProject;
use App\Models\HrTime;
use App\Models\User;
use App\Traits\HrLoggingTrait;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HrAttendImport implements ToCollection, WithHeadingRow
{
    use HrLoggingTrait;

    private $projectId;

    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
    }

    public function collection(Collection $rows)
    {
        $importedCount = 0;
        $updatedCount  = 0;
        $skippedCount  = 0;
        $errors        = [];

        // Skip first 2 rows and start from row 3
        $rows = $rows->slice(1);

        $project = HrProject::find($this->projectId);

        foreach ($rows as $rowIndex => $row) {
            try {
                // Skip empty rows
                if (empty($row['user_id']) || empty($row['date'] || empty($row['time']))) {
                    $error[] = "แถว " . ($rowIndex + 2) . ' ข้อมูลไม่สมบูรณ์';
                    $skippedCount++;
                    continue;
                }

                $userId           = trim($row['user_id']);
                $date             = trim($row['date']);
                $time             = trim($row['time']);
                $expoldeTime      = explode('-', $time);
                $startTime        = date('H:i', strtotime($expoldeTime[0]));
                $endTime          = date('H:i', strtotime($expoldeTime[1]));
                $attendDateTime   = date('Y-m-d H:i:s', strtotime(trim($row['attend_datetime'])));
                $approve_datetime = date('Y-m-d H:i:s', strtotime(trim($row['approve_datetime'])));

                // Find user by user_id
                $user = User::where('userid', $userId)->first();
                if (! $user) {
                    $errors[] = "แถว " . ($rowIndex + 3) . ": ไม่พบผู้ใช้ที่มีรหัส '{$userId}'"; // +4 because we skipped 2 rows and rowIndex starts from 0
                    $skippedCount++;
                    continue;
                }

                // Check if user is already in a group for this project
                $existingDate = HrDate::where('project_id', $this->projectId)
                    ->whereDate('date_datetime', $date)
                    ->first();

                $existingTime = HrTime::whereTime('time_start', $startTime)
                    ->whereTime('time_end', $endTime)
                    ->first();

                if ($existingDate && $existingTime) {
                    $existingRegistration = $project->attends()
                        ->where('user_id', $user->id)
                        ->where('time_id', $existingTime->id)
                        ->where('attend_delete', false)
                        ->first();

                    if ($existingRegistration == null) {
                        $importedCount++;
                        $attendance = $project->attends()->create([
                            'date_id'          => $existingDate->id,
                            'time_id'          => $existingTime->id,
                            'user_id'          => $user->id,
                            'attend_datetime'  => $attendDateTime,
                            'approve_datetime' => $approve_datetime,
                            'attend_delete'    => false,
                        ]);
                    } else {
                        $skippedCount++;
                        $errors[] = "แถว " . ($rowIndex + 3) . ": มีข้อมูลการลงทะเบียนแล้ว";
                    }
                } else {
                    $skippedCount++;
                    $errors[] = "แถว " . ($rowIndex + 3) . ": ไม่พบวันที่ หรือ เวลาที่ระบุ";
                }

            } catch (\Exception $e) {
                $errors[] = "แถว " . ($rowIndex + 3) . ": " . $e->getMessage(); // +4 because we skipped 2 rows and rowIndex starts from 0
                $skippedCount++;
            }
        }

        // Log the import operation summary using HR logging trait
        $this->logImportOperation(
            (object) ['id' => $this->projectId, 'project_name' => 'HR Register Import'],
            'HR_Register',
            $importedCount + $updatedCount,
            $skippedCount,
            $errors,
            [
                'imported_count'  => $importedCount,
                'updated_count'   => $updatedCount,
                'total_processed' => $importedCount + $updatedCount + $skippedCount,
            ]
        );

        // Store results in session for display
        session([
            'import_results' => [
                'imported' => $importedCount,
                'updated'  => $updatedCount,
                'skipped'  => $skippedCount,
                'errors'   => $errors,
            ],
        ]);
    }
}
