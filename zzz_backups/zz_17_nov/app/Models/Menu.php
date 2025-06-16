<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model {

    use SoftDeletes, HasFactory;

    public $table = 'menus';

    public function items() {
        return $this->hasMany('App\Models\Items', 'menu_id');
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
