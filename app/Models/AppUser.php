<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class AppUser extends Model {

    use SoftDeletes, HasApiTokens, HasFactory;

    public $table = 'app_users';
    
    protected $fillable = [
        'name',
        'phone',
        'user_type',
        'username',
        'password',
        'file_upload'
    ];

    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
