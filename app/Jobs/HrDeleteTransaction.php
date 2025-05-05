<?php
namespace App\Jobs;

use App\Models\Item;
use App\Models\Seat;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class HrDeleteTransaction implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $hrTransactionTodays = Transaction::whereDate('date', '<=', date('Y-m-d'))
            ->where('transaction_active', 1)
            ->whereNull('checkin_datetime')
            ->whereNull('hr_approve_datetime')
            ->get();

        $nowTime = date('Y-m-d H:i');
        foreach ($hrTransactionTodays as $transaction) {
            $endTime = date('Y-m-d H:i', strtotime($transaction->item->link_end));
            if ($nowTime > $endTime) {
                $transaction->transaction_active = false;
                $transaction->save();

                $item = Item::where('id', $transaction->item_id)->first();
                $item->item_available += 1;
                $item->save();

                if ($transaction->seat !== null) {
                    $seatArray                            = Seat::where('item_id', $transaction->item_id)->first();
                    $temp                                 = $seatArray->seats;
                    $temp[$transaction->seat - 1]['user'] = null;
                    $temp[$transaction->seat - 1]['dept'] = null;
                    $seatArray->seats                     = $temp;
                    $seatArray->save();
                }
                Log::channel('hr_delete')->info('Service : delete transaction id: ' . $transaction->id . ' LAST : ' . $endTime);
            }
        }

        HrDeleteTransaction::dispatch()->delay(600);
    }
}
