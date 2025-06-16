<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FounderMessage extends Model
{
    protected $fillable = [
        'founder_name',
        'designation',
        'message',
        'image',
        'status',
        'created_by',
        'updated_by',
    ];
    
}
