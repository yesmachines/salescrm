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
        'is_qualify',
        'send_mail',
        'priority',
        'status'
    ];

    public function lead()
    {
        return $this->hasOne(Lead::class);
    }
}
