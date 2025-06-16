<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Items extends Model {

    use SoftDeletes, HasFactory;

    public $table = 'items';
    protected $dates = ['deleted_at'];
    public $fillable = [
        'name',
        'menu_id',
        'price',
        'description',
        'image',
        'discount',
        'total_value',
        'created_by',
        'updated_by',
    ];
    
    protected $casts = [
        'id'          => 'integer',
        'menu_id'     => 'integer',
        'name'        => 'string',
        'price'       => 'double',
        'description' => 'string',
        'image'       => 'string',
    ];
    
    public static $rules = [
        'name'        => 'required',
        'menu_id'     => 'required',
        'price'       => 'required',
        'description' => 'required',
    ];
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
