<?php

namespace App\Repositories\Member\Transaction;

use App\Models\Transaction;
use App\Helpers\NumberFormatter;
use App\Models\TransactionStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MasterDataRepository;

class BillRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return Transaction::class;
    }

    public static function datatable()
    {
        $user = Auth::user();
        return Transaction::where('user_id', $user->id)
        ->select('transactions.id', 
        'transactions.number', 
        'transactions.user_id', 
        'transactions.last_status_id',
        'transactions.payment_method_id',
        'transactions.payment_method_name',
        )
        ->with('details', 'last_status', 'user')
        ->join('transaction_statuses', 'transaction_statuses.id', '=', 'transactions.last_status_id')
        ->orderByRaw("FIELD(transaction_statuses.name, 'Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Selesai', 'Batal')");
    
    }

    public static function findTransaction($transaction_id)
    {
        $user = Auth::user();
        return Transaction::where('id', $transaction_id)
        ->withSum('details', 'product_price')
        ->with('details', 'last_status', 'user')
        ->where('user_id', $user->id)
        ->first();
    }

    public static function midtransCallback($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);
        if ($transaction) {
            $transaction_status = new TransactionStatus();
            $transaction_status->transaction_id = $transaction->id;
            $transaction_status->name = TransactionStatus::STATUS_DONE;
            $transaction_status->description = TransactionStatus::STATUS_DONE;
            $transaction_status->save();
        } else {
            // Log transaction not found
            Log::error('Transaction with ID ' . $transaction_id . ' not found.');
        }
    }
}
