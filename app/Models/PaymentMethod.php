<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    const MIDTRANS_ID = 1;
    
    protected $fillable = [
        'name',
        'description',
    ];
}
