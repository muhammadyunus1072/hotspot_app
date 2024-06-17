<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use App\Repositories\MasterDataRepository;

class TransactionRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return Transaction::class;
    }

    public static function datatable()
    {
        return Transaction::with('details', 'last_status');
    }

    public static function findWithDetail($id)
    {
        return Transaction::
        with('details', 'last_status', 'user')
        ->where('id', $id)
        ->first();
    }
}
