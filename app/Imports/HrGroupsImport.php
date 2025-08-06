<?php
namespace App\Imports;

use App\Models\HrGroup;
use App\Models\User;
use App\Traits\HrLoggingTrait;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HrGroupsImport implements ToCollection, WithHeadingRow
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

        foreach ($rows as $rowIndex => $row) {
            try {
                // Skip empty rows
                if (empty($row['user_id']) || empty($row['group_name'])) {
                    $skippedCount++;
                    continue;
                }

                $userId    = trim($row['user_id']);
                $groupName = trim($row['group_name']);

                // Find user by user_id
                $user = User::where('userid', $userId)->first();

                if (! $user) {
                    $errors[] = "แถว " . ($rowIndex + 3) . ": ไม่พบผู้ใช้ที่มีรหัส '{$userId}'"; // +4 because we skipped 2 rows and rowIndex starts from 0
                    $skippedCount++;
                    continue;
                }

                // Check if user is already in a group for this project
                $existingGroup = HrGroup::where('project_id', $this->projectId)
                    ->where('user_id', $user->id)
                    ->first();

                if ($existingGroup) {
                    // Update existing group assignment
                    $oldGroup = $existingGroup->group;
                    $existingGroup->update(['group' => $groupName]);
                    $updatedCount++;

                    // Log group assignment update using HR logging trait
                    $this->logGroupAssignment(
                        (object) ['id' => $this->projectId, 'project_name' => 'HR Groups Import'],
                        $user,
                        $groupName,
                        'updated',
                        ['old_group' => $oldGroup]
                    );
                } else {
                    // Create new group assignment
                    HrGroup::create([
                        'project_id' => $this->projectId,
                        'user_id'    => $user->id,
                        'group'      => $groupName,
                    ]);
                    $importedCount++;

                    // Log new group assignment using HR logging trait
                    $this->logGroupAssignment(
                        (object) ['id' => $this->projectId, 'project_name' => 'HR Groups Import'],
                        $user,
                        $groupName,
                        'assigned'
                    );
                }

            } catch (\Exception $e) {
                $errors[] = "แถว " . ($rowIndex + 3) . ": " . $e->getMessage(); // +4 because we skipped 2 rows and rowIndex starts from 0
                $skippedCount++;
            }
        }

        // Log the import operation summary using HR logging trait
        $this->logImportOperation(
            (object) ['id' => $this->projectId, 'project_name' => 'HR Groups Import'],
            'HR_GROUPS',
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
