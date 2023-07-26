<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\Company;

class Region extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'state',
        'country_id',
        'status'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function company()
    {
        return $this->hasMany(Company::class);
    }
}
