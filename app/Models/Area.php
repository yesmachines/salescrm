<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model {

    protected $fillable = [
        'name',
        'timezone',
        'status'
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'employee_areas')->withTimestamps();
    }
}
