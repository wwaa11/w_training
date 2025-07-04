<?php
namespace App\Imports;

use App\Models\TrainingUser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TrainingTeamImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $find = TrainingUser::where('user_id', $row[0])->first();
            if ($find) {
                $find->team = $row[1];
            } else {
                $find          = new TrainingUser();
                $find->user_id = $row[0];
                $find->team    = $row[1];
            }
            $find->save();
        }
    }
}
