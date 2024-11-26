<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $hidden = [
        'user_id',
        'scheduled_at',
        'created_at',
        'updated_at',
    ];

    public function products() {
        return $this->hasMany(MeetingProduct::class, 'meeting_id', 'id');
    }

    public function shares() {
        return $this->hasMany(MeetingShare::class, 'meeting_id', 'id');
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
