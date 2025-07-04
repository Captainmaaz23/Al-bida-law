<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'status',
        'created_by',
        'updated_by',
    ];
    
}
