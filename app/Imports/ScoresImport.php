<?php
namespace App\Imports;

use App\Models\Score;
use App\Models\ScoreHeader;
use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ScoresImport implements ToCollection, WithCalculatedFormulas
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function collection(Collection $rows)
    {
        $id = $this->id;
        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex == 0) {
                $header             = ScoreHeader::where('project_id', $id)->delete();
                $header             = new ScoreHeader;
                $header->project_id = $id;
                for ($i = 5; $i <= 24; $i++) {
                    $slot = 'title_' . ($i - 4);
                    if ($row->has($i) && ! is_null($row[$i])) {
                        $header->$slot = $row[$i];
                    }
                }
                $header->save();
            } else {
                $transaction = Transaction::where('project_id', $id)
                    ->where('transaction_active', true)
                    ->where('user', $row[0])
                    ->first();
                if ($transaction !== null) {
                    $score = Score::where('transaction_id', $transaction->id)->first();
                    if ($score == null) {
                        $score                 = new Score;
                        $score->transaction_id = $transaction->id;
                        $score->user_id        = $row[0];
                    }
                    for ($i = 5; $i <= 24; $i++) {
                        $slot = 'result_' . ($i - 4);
                        if ($row->has($i) && ! is_null($row[$i])) {
                            $score->$slot = $row[$i];
                        }
                    }
                    $score->save();
                } else {
                    Log::channel('hr_delete')->info('Import : ' . $row[1] . ' not found active transaction.');
                }
            }
        }
    }
}
