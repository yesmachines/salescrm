<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $guarded = [];
    // public function user()
    // {
    //     return $this->belongsTo(Employee::class, 'assigned_to', 'id');
    // }
    public function assigned()
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'id');
    }

}
