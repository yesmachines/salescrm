<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function products() {
        return $this->hasMany(MeetingProduct::class, 'meeting_id', 'id');
    }
}
