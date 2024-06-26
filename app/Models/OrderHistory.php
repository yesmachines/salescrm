<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Order;

class OrderHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'comment',
        'userid',
        'username',
        'parent_id',
        // 'main_parent_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function replies()
    {
        return $this->hasMany(self::class,  'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
