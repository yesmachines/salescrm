<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Company;
use App\Models\Visitor;
use App\Models\Country;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'company_id',
        'email',
        'phone',
        'location',
        'status'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function lead()
    {
        return $this->hasOne(Lead::class);
    }
    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }
    public function visitor()
    {
        return $this->hasOne(Visitor::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'location', 'id');
    }
}
