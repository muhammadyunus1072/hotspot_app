<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'price_before_discount',
    ];
}
