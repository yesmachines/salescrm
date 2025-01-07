<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadHistory extends Model {

    protected $fillable = [
        'lead_id',
        'status_id',
        'comment',
        'priority',
        'userid',
    ];
}
