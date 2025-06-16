<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServeTable extends Model {

    use SoftDeletes, HasFactory;

    public $table = 'serve_tables';
    
    protected $fillable = [
        'rest_id',
        'title',
        'availability',
        'icon',
        'created_by',
        // other attributes you want to allow mass assignment for
    ];
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }    

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'rest_id');
    }
}
