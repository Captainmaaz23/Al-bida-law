<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientReview extends Model
{
    protected $fillable = [
        'heading',
        'heading_arabic',
        'summary',
        'summary_arabic',
        'image',
        'status',
        'created_by',
        'updated_by',
    ];    
}
