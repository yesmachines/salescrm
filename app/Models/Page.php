<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model {

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status'
    ];
    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
    ];
}
