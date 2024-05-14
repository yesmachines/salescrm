<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Quotation;
use App\Models\Employee;

class SalesCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'commission_amount',
        'percent',
        'manager_id',
        'status'
    ];
    public function setCommissionAmountAttribute($value)
    {
        $b = str_replace(',', '', $value);
        return $this->attributes['commission_amount'] = ($value) ? (float)$b : 0;
    }
    public function getCommissionAmountAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'id');
    }
}
