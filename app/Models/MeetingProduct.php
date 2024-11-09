<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingProduct extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function meeting() {
        return $this->belongsTo(Meeting::class, 'id', 'meeting_id');
    }
}
