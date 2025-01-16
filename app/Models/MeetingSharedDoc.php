<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingSharedDoc extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function meetingShares() {
        return $this->belongsTo(MeetingShare::class, 'id', 'meeting_shared_id');
    }
    
     public function getFileNameAttribute($value)
    {
        return cdn($value) ;
    }
}
