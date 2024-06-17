<?php

namespace App\Repositories\Member;

use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\HotspotMember;
use Illuminate\Support\Facades\Crypt;


class MemberRepository
{
    public static function getData($id)
    {
        $data = [];
        $user_id = Crypt::decrypt($id);

        $paket = HotspotMember::select(
            'products.name',
            'products.description',
            'products.price',
        )
        ->join('products', 'products.id', '=', 'hotspot_members.product_id')
        ->where('hotspot_members.user_id', '=', $user_id)
        ->whereNull('hotspot_members.deleted_at')
        ->whereNull('products.deleted_at')
        ->first();
        $data['product'] = $paket;

        $this_month = Carbon::now()->month;
        $transaction = Transaction::select('transactions.number', 'transactions.payment_method_name', 'transaction_statuses.name as status_name')
        ->join('transaction_statuses', 'transaction_statuses.id', '=', 'transactions.last_status_id')
        ->whereMonth('transactions.created_at', '=', $this_month)
        ->where('transactions.user_id', '=', $user_id)
        ->first();

        $data['transaction'] = $transaction;
        return $data->toArray();
    }
}
