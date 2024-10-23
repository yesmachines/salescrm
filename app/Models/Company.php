<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\Visitor;
use App\Models\Region;
use App\Models\Country;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'reference_no',
        'email_address',
        'landphone',
        'address',
        'website',
        'region_id',
        'country_id',
        'status'
    ];
    public function lead()
    {
        return $this->hasOne(Lead::class);
    }
    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
    public function visitor()
    {
        return $this->hasOne(Visitor::class);
    }
    public function purchaseRequisition()
    {
        return $this->hasMany(PurchaseRequisition::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
