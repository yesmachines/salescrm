<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingInvite extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $hidden = [
        'meeting_id',
        'created_at',
        'updated_at',
    ];
    
    protected $fillable = [
        'meeting_id',
        'user_id',
        'status',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function meeting() {
        return $this->belongsTo(Meeting::class, 'id', 'meeting_id');
    }
}
