<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'stripe_id',
        'payment_status',
        'stripe_price',
        'stripe_subscription_id',
        'invoice_id',
        'currency',
        'quantity',
        'trial_ends_at',
        'ends_at',
        'price_id',
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
