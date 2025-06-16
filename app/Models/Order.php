<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {

    use SoftDeletes, HasFactory;

    public $table = 'orders';
    protected $dates = ['deleted_at'];
    public $fillable = [
        'user_id',
        'total',
        'store_id',
        'status'
    ];
    
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'total' => 'double',
        'store_id' => 'integer',
        'status' => 'integer'
    ];
    
    public static $rules = [
        'user_id' => 'required',
        'total' => 'required',
        'store_id' => 'required',
        'status' => 'required'
    ];
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
