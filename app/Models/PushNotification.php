<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'extras' => 'array',
    ];
}
