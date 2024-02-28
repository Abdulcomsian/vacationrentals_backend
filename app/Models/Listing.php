<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Listing extends Model
{
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
    ];
}
