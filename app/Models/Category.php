<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Listing};

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "slug",
        "category_name",
        "category_image",
    ];

    public function listings(){
        return $this->hasMany(ListingCategory::class);
    }

    public function categoryList(){
        return $this->belongsToMany(Listing::class , 'listings_categories' , 'category_id' , 'listing_id');
    }
}
