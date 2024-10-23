<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\Employee;
use App\Models\Quotation;
use App\Models\QuotationItem;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'logo_url',
        'website',
        'description',
        'country_id',
        'manager_id',
        'status'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function manager()
    {
        return $this->belongsTo(Employee::class);
    }
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
    public function quotationItem()
    {
        return $this->hasMany(QuotationItem::class);
    }
    public function purchaseRequisition()
    {
        return $this->hasMany(PurchaseRequisition::class);
    }
}
