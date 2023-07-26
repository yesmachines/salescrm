<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Visitor;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitorid',
        'review_type',
        'comments',
        'status'
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
