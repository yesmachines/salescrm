<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;

class LeadStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'priority',
        'status'
    ];

    public function lead()
    {
        return $this->hasOne(Lead::class);
    }
}
