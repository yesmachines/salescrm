<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingDoc extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $hidden = [
        'meeting_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function meeting() {
        return $this->belongsTo(Meeting::class, 'id', 'meeting_id');
    }
    
     public function getFileNameAttribute($value)
    {
        return cdn($value) ;
    }
}
