<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Visitor;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitorid',
        'submitted_by',
        'brand',
        'description',
        'status'
    ];


    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
