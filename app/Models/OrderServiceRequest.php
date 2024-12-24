<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderServiceRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'site_readiness',
        'training_requirement',
        'consumables',
        'warranty_period',
        'special_offers',
        'documents_required',
        'machine_objective',
        'fat_test',
        'fat_expectation',
        'sat_objective'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
