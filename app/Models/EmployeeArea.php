<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeArea extends Model {

    protected $fillable = [
        'user_id',
        'area_id'
    ];
}
