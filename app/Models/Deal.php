<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Deal extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "listing_id",
        "deal_name",
        "currency",
        "discount_price",
        "actual_price",
        "billing_interval",
        "type",
        "coupon_code",
        "link",
    ];
}
