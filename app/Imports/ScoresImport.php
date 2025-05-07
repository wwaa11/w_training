<?php
namespace App\Imports;

use App\Models\Score;
use App\Models\ScoreHeader;
use App\Models\Transaction;
use Illuminate\Support\Collection;
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
                $header = ScoreHeader::where('project_id', $id)->first();
                if ($header == null) {
                    $header             = new ScoreHeader;
                    $header->project_id = $id;
                }
                for ($i = 6; $i <= 25; $i++) {
                    switch ($i) {
                        case 6:
                            $slot = 'title_1';
                            break;
                        case 7:
                            $slot = 'title_2';
                            break;
                        case 8:
                            $slot = 'title_3';
                            break;
                        case 9:
                            $slot = 'title_4';
                            break;
                        case 10:
                            $slot = 'title_5';
                            break;
                        case 11:
                            $slot = 'title_6';
                            break;
                        case 12:
                            $slot = 'title_7';
                            break;
                        case 13:
                            $slot = 'title_8';
                            break;
                        case 14:
                            $slot = 'title_9';
                            break;
                        case 15:
                            $slot = 'title_10';
                            break;
                        case 16:
                            $slot = 'title_11';
                            break;
                        case 17:
                            $slot = 'title_12';
                            break;
                        case 18:
                            $slot = 'title_13';
                            break;
                        case 19:
                            $slot = 'title_14';
                            break;
                        case 20:
                            $slot = 'title_15';
                            break;
                        case 21:
                            $slot = 'title_16';
                            break;
                        case 22:
                            $slot = 'title_17';
                            break;
                        case 23:
                            $slot = 'title_18';
                            break;
                        case 24:
                            $slot = 'title_19';
                            break;
                        case 25:
                            $slot = 'title_20';
                            break;
                    }
                    ($row->has($i)) ? $header->$slot = $row[$i] : null;
                }
                $header->save();
            } else {
                $transaction = Transaction::where('project_id', $id)
                    ->where('transaction_active', true)
                    ->where('user', $row[1])
                    ->first();
                if ($transaction !== null) {
                    $score = Score::where('transaction_id', $transaction->id)->first();
                    if ($score == null) {
                        $score                 = new Score;
                        $score->transaction_id = $transaction->id;
                        $score->user_id        = $row[1];
                    }
                    for ($i = 6; $i <= 25; $i++) {
                        switch ($i) {
                            case 6:
                                $slot = 'result_1';
                                break;
                            case 7:
                                $slot = 'result_2';
                                break;
                            case 8:
                                $slot = 'result_3';
                                break;
                            case 9:
                                $slot = 'result_4';
                                break;
                            case 10:
                                $slot = 'result_5';
                                break;
                            case 11:
                                $slot = 'result_6';
                                break;
                            case 12:
                                $slot = 'result_7';
                                break;
                            case 13:
                                $slot = 'result_8';
                                break;
                            case 14:
                                $slot = 'result_9';
                                break;
                            case 15:
                                $slot = 'result_10';
                                break;
                            case 16:
                                $slot = 'result_11';
                                break;
                            case 17:
                                $slot = 'result_12';
                                break;
                            case 18:
                                $slot = 'result_13';
                                break;
                            case 19:
                                $slot = 'result_14';
                                break;
                            case 20:
                                $slot = 'result_15';
                                break;
                            case 21:
                                $slot = 'result_16';
                                break;
                            case 22:
                                $slot = 'result_17';
                                break;
                            case 23:
                                $slot = 'result_18';
                                break;
                            case 24:
                                $slot = 'result_19';
                                break;
                            case 25:
                                $slot = 'result_20';
                                break;
                        }
                        ($row->has($i)) ? $score->$slot = $row[$i] : null;
                    }
                    $score->save();
                }
            }
        }
    }
}
