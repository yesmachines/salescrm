<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Review;
use App\Models\Enquiry;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'customer_id',
        'codeno',
        'checkin',
        'purpose',
        'status'
    ];

    public function setCheckinAttribute($value)
    {
        $this->attributes['checkin'] = date("Y-m-d", strtotime($value));
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function enquiry()
    {
        return $this->hasMany(Enquiry::class);
    }
}
