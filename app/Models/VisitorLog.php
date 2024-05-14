<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Visitor;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'checkin',
        'purpose',
        'status'
    ];
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
