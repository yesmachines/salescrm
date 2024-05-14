<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderCharge extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'title',
        'currency',
        'considered',
        'actual',
        'remarks'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
