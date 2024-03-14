<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Listing extends Model
{
    /*
        Listing Status Meanings
        0 - Draft
        1 - Pending
        2 - Approved
        3 - Rejected
    */
    
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'company_name',
        'company_link',
        'company_tagline',
        'short_description',
        'company_logo',
        'status',
        'plan_id',
    ];

    public function getCategories(){
        return $this->hasMany(ListingCategory::class);
    }

    public function plan(){
        return $this->belongsTo(Plan::class);
    }

    public function deals(){
        return $this->hasMany(Deal::class);
    }
}
