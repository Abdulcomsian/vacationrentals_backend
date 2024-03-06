<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
