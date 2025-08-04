<?php
namespace App\Imports;

use App\Models\HrAttend;
use App\Models\HrResult;
use App\Models\HrResultHeader;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class HrResultsImport implements ToCollection, WithCalculatedFormulas
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function collection(Collection $rows)
    {
        $id           = $this->id;
        $activeFields = []; // Track which fields have non-null headers

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex == 0) {
                $header = HrResultHeader::where('project_id', $id)->first();
                if ($header == null) {
                    $header             = new HrResultHeader;
                    $header->project_id = $id;
                }

                // Process headers and track which fields are active
                for ($i = 5; $i <= 14; $i++) {
                    $slot = 'result_' . ($i - 4) . '_name';
                    if ($row->has($i) && ! empty($row[$i])) {
                        $header->$slot    = $row[$i];
                        $activeFields[$i] = true; // Mark this field as active
                    } else {
                        $header->$slot    = null;
                        $activeFields[$i] = false; // Mark this field as inactive
                    }
                }
                $header->save();
            } else {
                // Find user by userid from row[1]
                $user = User::where('userid', $row[1])->first();

                if (! $user) {
                    Log::channel('hr_delete')->info('Import : User with userid ' . $row[1] . ' not found in User table.');
                    continue;
                }

                // Check if user has any attendance records for this project
                $userAttends = HrAttend::where('project_id', $id)
                    ->where('attend_delete', false)
                    ->where('user_id', $user->id)
                    ->get();

                if ($userAttends->count() > 0) {
                    // Find or create result record for this user and project
                    $result = HrResult::where('project_id', $id)
                        ->where('user_id', $user->id)
                        ->first();

                    if ($result == null) {
                        $result             = new HrResult;
                        $result->project_id = $id;
                        $result->user_id    = $user->id;
                        // Use the first attendance record as the primary attend_id
                        $result->attend_id = $userAttends->first()->id;
                    }

                    // Update result data only for fields with non-null headers
                    for ($i = 5; $i <= 14; $i++) {
                        $slot = 'result_' . ($i - 4);
                        if (isset($activeFields[$i]) && $activeFields[$i] && $row->has($i)) {
                            $result->$slot = $row[$i];
                        }
                    }
                    $result->save();

                    // If user has multiple attendance records, create additional result records
                    // for the remaining attendance records (all with the same data)
                    if ($userAttends->count() > 1) {
                        for ($i = 1; $i < $userAttends->count(); $i++) {
                            $additionalResult = HrResult::where('project_id', $id)
                                ->where('attend_id', $userAttends[$i]->id)
                                ->first();

                            if ($additionalResult == null) {
                                $additionalResult             = new HrResult;
                                $additionalResult->project_id = $id;
                                $additionalResult->attend_id  = $userAttends[$i]->id;
                                $additionalResult->user_id    = $user->id;
                            }

                            // Copy the same result data only for active fields
                            for ($j = 5; $j <= 14; $j++) {
                                $slot = 'result_' . ($j - 4);
                                if (isset($activeFields[$j]) && $activeFields[$j] && $row->has($j)) {
                                    $additionalResult->$slot = $row[$j];
                                }
                            }
                            $additionalResult->save();
                        }
                    }

                    Log::channel('hr_delete')->info('Import : User ' . $user->userid . ' has ' . $userAttends->count() . ' attendance records, created ' . $userAttends->count() . ' result records with same data.');
                } else {
                    Log::channel('hr_delete')->info('Import : ' . $user->userid . ' not found active attendance.');
                }
            }
        }
    }
}
