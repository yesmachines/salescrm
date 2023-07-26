<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Region;
use App\Models\Company;

class Country extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'status'
    ];

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    public function regions()
    {
        return $this->hasMany(Region::class);
    }
    public function company()
    {
        return $this->hasMany(Company::class);
    }
}
