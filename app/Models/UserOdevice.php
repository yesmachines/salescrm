<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOdevice extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'os_sid'
    ];
}
