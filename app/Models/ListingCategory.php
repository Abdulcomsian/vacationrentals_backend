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

}
