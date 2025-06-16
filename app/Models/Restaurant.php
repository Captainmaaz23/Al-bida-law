<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model {

    use SoftDeletes;

    use HasFactory;

    protected $fillable = [
        'name',
        'arabic_name',
        'phoneno',
        'email',
        'location',
        'status',
        'is_featured',
        'profile',
        'instagram_link',
        'facebook_link',
        'website_link',
        'description',
        'arabic_description'
    ];
    public $table = 'restaurants';
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
