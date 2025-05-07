<?php
namespace App\Jobs;

use App\Models\Item;
use App\Models\Seat;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class HrAssignSeat implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {

    }

    public function handle(): void
    {
        $transactions = Transaction::where('transaction_active', true)
            ->whereNull('seat')
            ->get();
        foreach ($transactions as $transaction) {
            $seatArray = Seat::firstOrNew(['item_id' => $transaction->item_id]);
            if ($seatArray->seats == null) {
                $arrayTemp = [];
                $items     = Item::where('id', $transaction->item_id)->first();
                for ($i = 0; $i < $items->item_max_available; $i++) {
                    $arrayTemp[] = [
                        'dept' => null,
                        'user' => null,
                    ];
                }
                $seatArray->seats = $arrayTemp;
                $seatArray->save();
            }
            $maxSeat       = $seatArray->item->item_max_available;
            $maxSeat_range = $maxSeat - 1;
            $tempSeatArray = $seatArray->seats;
            $success       = false;
            $newUser       = [
                'dept' => $transaction->userData->department,
                'user' => $transaction->user,
            ];
            if (array_key_exists('-1', $tempSeatArray)) {
                unset($tempSeatArray[-1]);
            }
            for ($i = 0; $i <= $maxSeat_range; $i++) {
                $seatNumber = $i + 1;
                if ($tempSeatArray[$i]['user'] == null) {
                    switch ($i) {
                        case 0:
                            $tempSeatArray[$i] = $newUser;
                            $success           = true;
                            break;
                        case $maxSeat_range:
                            if ($tempSeatArray[$i - 1]['dept'] !== $newUser['dept']) {
                                $tempSeatArray[$i] = $newUser;
                                $success           = true;
                            }
                            break;
                        default:
                            if ($tempSeatArray[$i - 1]['dept'] !== $newUser['dept'] && $tempSeatArray[$i + 1]['dept'] !== $newUser['dept']) {
                                $tempSeatArray[$i] = $newUser;
                                $success           = true;
                            }
                            break;
                    }
                }
                if ($success) {
                    break;
                }
            }
            if (! $success) {
                for ($i = 0; $i <= $maxSeat_range; $i++) {
                    $seatNumber = $i + 1;
                    if ($tempSeatArray[$i]['user'] == null) {
                        $tempSeatArray[$i] = $newUser;
                        $success           = true;
                        break;
                    }
                }
            }
            if ($success) {
                $transaction->seat = $seatNumber;
                $transaction->save();

                $seatArray->seats = $tempSeatArray;
                $seatArray->save();
            }
        }
        HrAssignSeat::dispatch()->delay(60);
    }
}
