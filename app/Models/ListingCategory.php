<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingCategory extends Model
{
    use HasFactory;
    protected $table = "listings_categories";
    protected $fillable = [
        "listing_id",
        "category_id",
    ];

    public function listings(){
        return $this->belongsTo(Listing::class, 'listing_id')->where('status', '2')->where('subscription_status', 'active');
    }
}
