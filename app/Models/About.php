<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'title',
        'arabic_title',
        'arabic_description',
        'description',
        'image',
        'image_title',
        'status',
        'created_by',
        'updated_by'
    ];
}
