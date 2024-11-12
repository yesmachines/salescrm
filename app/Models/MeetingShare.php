<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingShare extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $hidden = [
        'meeting_id',
        'shared_by',
        'shared_to',
        'parent_id',
        'created_at',
        'updated_at',
    ];

    public function meeting() {
        return $this->belongsTo(Meeting::class, 'id', 'meetings_id');
    }
    
    public function sharedProducts() {
        return $this->hasMany(MeetingSharedProduct::class, 'meetings_shared_id', 'id');
    }
}
