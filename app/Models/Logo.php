<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    protected $fillable = ['image','created_by','updated_by','status'];
    
    private function userCan($permission)
    {
        return Auth::check() && Auth::user()->can($permission);
    }
}
