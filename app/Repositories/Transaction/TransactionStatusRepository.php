<?php

namespace App\Repositories\Transaction;

use App\Models\TransactionStatus;
use App\Repositories\MasterDataRepository;

class TransactionStatusRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return TransactionStatus::class;
    }
}
