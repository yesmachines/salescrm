<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'name',
        'comment'
    ];
}
