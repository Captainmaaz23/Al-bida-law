<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class OurTeam extends Model
{
    protected $fillable = [
        'image',
        'name',
        'title',
        'bio',
        'position',
        'created_by',
        'updated_by'
    ]; 
    
    private function userCan($permission)
    {
        return Auth::check() && Auth::user()->can($permission);
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

}
