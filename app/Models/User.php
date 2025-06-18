<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'rest_id');
    }
    
    public function scopeFilter($query, $callback)
    {
        return $callback($query);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function blogs(){
        return $this->hasMany(Blogs::class,'created_by','id');
    }

    public function message(){
        return $this->hasMany(FounderMessage::class,'created_by','id');
    }

    public function ourTeam(){
        return $this->hasMany(OurTeam::class,'created_by','id');
    }

}