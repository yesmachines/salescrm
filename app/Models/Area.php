<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model {

    protected $fillable = [
        'name',
        'timezone',
        'status'
    ];
    
     protected $hidden = [
        'timezone',
        'status',
        'created_at',
        'updated_at',
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'employee_areas')->withTimestamps();
    }
}
