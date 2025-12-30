<?php
namespace App\Imports;

use App\Models\HrAttend;
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
            $actualRowNumber = $rowIndex + 3;
            try {
                // 1. Corrected empty check
                if (empty($row['user_id']) || empty($row['date']) || empty($row['time'])) {
                    $errors[] = "แถว {$actualRowNumber}: ข้อมูลไม่สมบูรณ์ (User ID, Date, or Time is missing)";
                    $skippedCount++;
                    continue;
                }

                // 2. Parse Data
                $timeRange = explode('-', $row['time']);
                if (count($timeRange) < 2) {
                    $errors[] = "แถว {$actualRowNumber}: รูปแบบเวลาไม่ถูกต้อง (ต้องเป็น H:i-H:i)";
                    $skippedCount++;
                    continue;
                }
                $startTime = date('H:i:s', strtotime(trim($timeRange[0])));
                $endTime   = date('H:i:s', strtotime(trim($timeRange[1])));
                $dateOnly  = date('Y-m-d', strtotime(trim($row['date'])));

                // 3. Find User
                $user = User::where('userid', trim($row['user_id']))->first();
                if (! $user) {
                    $errors[] = "แถว {$actualRowNumber}: ไม่พบผู้ใช้รหัส '{$row['user_id']}'";
                    $skippedCount++;
                    continue;
                }

                // 4. Find Date (Scoped to Project)
                $existingDate = HrDate::where('project_id', $this->projectId)
                    ->whereDate('date_datetime', $dateOnly)
                    ->first();
                if (! $existingDate) {
                    $errors[] = "แถว {$actualRowNumber}: ไม่พบวันที่ {$dateOnly} ในโปรเจกต์นี้";
                    $skippedCount++;
                    continue;
                }

                // 5. Find Time (Scoped to the found Date)
                $existingTime = HrTime::where('date_id', $existingDate->id)
                    ->whereTime('time_start', $startTime)
                    ->whereTime('time_end', $endTime)
                    ->first();

                if (! $existingTime) {
                    $errors[] = "แถว {$actualRowNumber}: ไม่พบช่วงเวลา {$row['time']} ในวันที่ระบุ";
                    $skippedCount++;
                    continue;
                }

                $attendance = HrAttend::updateOrCreate(
                    [
                        'project_id'    => $this->projectId,
                        'time_id'       => $existingTime->id,
                        'user_id'       => $user->id,
                        'attend_delete' => false,
                    ],
                    [
                        'date_id'          => $existingDate->id,
                        'attend_datetime'  => ! empty($row['attend_datetime']) ? date('Y-m-d H:i:s', strtotime($row['attend_datetime'])) : now(),
                        'approve_datetime' => ! empty($row['approve_datetime']) ? date('Y-m-d H:i:s', strtotime($row['approve_datetime'])) : null,
                    ]
                );

                if ($attendance->wasRecentlyCreated) {
                    $importedCount++;
                } else {
                    $updatedCount++;
                }

            } catch (\Exception $e) {
                $errors[] = "แถว {$actualRowNumber}: " . $e->getMessage();
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
