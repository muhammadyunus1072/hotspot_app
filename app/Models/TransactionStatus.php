<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionStatus extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'transaction_id',
        'name',
        'description',
    ];

    const STATUS_PAYMENT_PENDING = "Menunggu Pembayaran";
    const STATUS_ORDER_CONFIRMATION_PENDING = "Menunggu Konfirmasi";
    const STATUS_DONE = "Selesai";
    const STATUS_CANCEL = "Batal";

    const STATUS_CHOICE = [
        self::STATUS_PAYMENT_PENDING => self::STATUS_PAYMENT_PENDING,
        self::STATUS_ORDER_CONFIRMATION_PENDING => self::STATUS_ORDER_CONFIRMATION_PENDING,
        self::STATUS_DONE => self::STATUS_DONE,
        self::STATUS_CANCEL => self::STATUS_CANCEL,
    ];

    const ADMIN_STATUS_CHOICE = [
        self::STATUS_DONE => self::STATUS_DONE,
        self::STATUS_CANCEL => self::STATUS_CANCEL,
    ];

    protected static function onBoot()
    {
        self::created(function ($model) {
            $transaction = $model->transaction;
            $transaction->last_status_id = $model->id;
            $transaction->save();
        });
    }

    public static function status_style($name)
    {
        switch ($name) {
            case self::STATUS_PAYMENT_PENDING:
                return "warning";
                break;
            case self::STATUS_ORDER_CONFIRMATION_PENDING:
                return "info";
                break;
            case self::STATUS_DONE:
                return "success";
                break;
            case self::STATUS_CANCEL:
                return "danger";
                break;
        }
    }

    public function get_beautify()
    {
        $class = self::status_style($this->name);

        return "<div class='badge badge-$class' style='font-size:15px;'>$this->name</div>";
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
