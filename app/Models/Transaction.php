<?php

namespace App\Models;

use App\Models\User;
use App\Helpers\UserHelper;
use App\Helpers\NumberGenerator;
use Spatie\Permission\Models\Role;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'user_id',
        'proof_of_payment',
        'payment_method_id',
    ];

    protected static function onBoot()
    {
        self::creating(function ($model) {
            $model->number = NumberGenerator::generate('TR', self::class);

            $payment_method = $model->payment_method;
            if($payment_method)
            {
                $model->payment_method_name = $payment_method->name;
                $model->payment_method_description = $payment_method->description;
            }
        });
        self::created(function ($model) {
            $status = new TransactionStatus();
            $status->transaction_id = $model->id;
            $status->name = TransactionStatus::STATUS_PAYMENT_PENDING;
            $status->description = TransactionStatus::STATUS_PAYMENT_PENDING;
            $status->save();
        });
        self::updating(function ($model) {
            $payment_method = $model->payment_method;
            if($payment_method)
            {
                $model->payment_method_name = $payment_method->name;
                $model->payment_method_description = $payment_method->description;
            }
        });
    }

    public function isEditable()
    {
        if(UserHelper::role() == User::ROLE_ADMIN)
        {
            if($this->last_status->name == TransactionStatus::STATUS_DONE || 
            $this->last_status->name == TransactionStatus::STATUS_CANCEL){
                return false;
            }
        }else{
            if($this->last_status->name == TransactionStatus::STATUS_DONE || 
            $this->last_status->name == TransactionStatus::STATUS_ORDER_CONFIRMATION_PENDING || 
            $this->last_status->name == TransactionStatus::STATUS_CANCEL )
            {
                return false;
            }
        }
        return true;
    }

    public function last_status()
    {
        return $this->belongsTo(TransactionStatus::class, 'last_status_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }

    public function statuses()
    {
        return $this->hasMany(TransactionStatus::class, 'transaction_id', 'id');
    }
}
