<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expo extends Model {

    protected $fillable = [
        'name',
        'status'
    ];

    public function lead() {
        return $this->belongsTo(Lead::class);
    }
}
