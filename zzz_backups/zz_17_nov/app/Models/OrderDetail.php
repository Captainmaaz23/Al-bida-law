<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model {

    use SoftDeletes, HasFactory;
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
