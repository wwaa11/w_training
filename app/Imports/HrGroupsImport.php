<?php
namespace App\Imports;

use App\Models\HrGroup;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HrGroupsImport implements ToCollection, WithHeadingRow
{
    private $projectId;

    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
    }

    public function collection(Collection $rows)
    {
        $importedCount = 0;
        $skippedCount  = 0;
        $errors        = [];

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
                    $errors[] = "แถว " . ($rowIndex + 2) . ": ไม่พบผู้ใช้ที่มีรหัส '{$userId}'";
                    $skippedCount++;
                    continue;
                }

                // Note: Users can be added to groups even if they haven't attended yet
                // This allows for pre-assignment of groups before attendance

                // Check if user is already in a group for this project
                $existingGroup = HrGroup::where('project_id', $this->projectId)
                    ->where('user_id', $user->id)
                    ->first();

                if ($existingGroup) {
                    $errors[] = "แถว " . ($rowIndex + 2) . ": ผู้ใช้ {$user->name} (รหัส: {$userId}) ถูกจัดกลุ่มแล้วในกลุ่ม '{$existingGroup->group}'";
                    $skippedCount++;
                    continue;
                }

                // Create new group assignment
                HrGroup::create([
                    'project_id' => $this->projectId,
                    'user_id'    => $user->id,
                    'group'      => $groupName,
                ]);

                $importedCount++;

            } catch (\Exception $e) {
                $errors[] = "แถว " . ($rowIndex + 2) . ": " . $e->getMessage();
                $skippedCount++;
            }
        }

        // Log the import results
        Log::channel('hrd_admin')->info('Imported HR groups from Excel', [
            'project_id'     => $this->projectId,
            'imported_count' => $importedCount,
            'skipped_count'  => $skippedCount,
            'errors'         => $errors,
        ]);

        // Store results in session for display
        session([
            'import_results' => [
                'imported' => $importedCount,
                'skipped'  => $skippedCount,
                'errors'   => $errors,
            ],
        ]);
    }
}
