<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Lead;
use App\Models\Supplier;
use App\Models\SalesCommission;
use App\Models\Quotation;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'emp_num',
        'phone',
        'designation',
        'division',
        'image_url',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
    public function lead()
    {
        return $this->hasOne(Lead::class, 'assigned_to');
    }
    public function salescommission()
    {
        return $this->hasMany(SalesCommission::class, 'manager_id');
    }
    public function quotations()
   {
       return $this->hasMany(Quotation::class, 'assigned_to', 'id');
   }
}
