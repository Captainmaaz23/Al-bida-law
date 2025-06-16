<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'english_question',
        'arabic_question',
        'english_description',
        'arabic_description',
        'status',
        'created_by',
        'updated_by',
    ];

    private function userCan($permission)
    {
        return Auth::check() && Auth::user()->can($permission);
    }
    
}
