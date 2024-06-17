<?php

namespace App\Repositories\Transaction;

use App\Models\TransactionDetail;
use App\Repositories\MasterDataRepository;

class TransactionDetailRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return TransactionDetail::class;
    }
}
