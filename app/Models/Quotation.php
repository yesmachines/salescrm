<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Employee;
use App\Models\QuotationStatus;
use App\Models\Category;
use App\Models\Supplier;
use Carbon\Carbon;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'customer_id',
        'category_id',
        'supplier_id',
        'reference_no',
        'total_amount',
        'gross_margin',
        'submitted_date',
        'closure_date',
        'winning_probability',
        'parent_id',
        'root_parent_id',
        'status_id',
        'remarks',
        'assigned_to',
        'is_active',
        'lead_type',
        'quote_for',
        'reminder',
        'product_models'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function quoteStatus()
    {
        return $this->belongsTo(QuotationStatus::class, 'status_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function assigned()
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'id');
    }
    public function setSubmittedDateAttribute($value)
    {
        $this->attributes['submitted_date'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function setClosureDateAttribute($value)
    {
        $this->attributes['closure_date'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function setReminderAttribute($value)
    {
        $date = ($value) ? strtotime($value) : null;
        $this->attributes['reminder'] = ($date) ? date("Y-m-d H:i:s", $date) : null;
    }
    public function getTotalAmountAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }
    public function getGrossMarginAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }
    public function getWinningProbabilityAttribute($value)
    {
        return number_format((float)$value, 1, '.', '');
    }

    public function setTotalAmountAttribute($value)
    {
        $b = str_replace(',', '', $value);
        return $this->attributes['total_amount'] = ($value) ? (float)$b : 0;
    }
    public function setGrossMarginAttribute($value)
    {
        $b = str_replace(',', '', $value);
        return $this->attributes['gross_margin'] = ($value) ? (float)$b : 0;
    }
    public function setWinningProbabilityAttribute($value)
    {
        return $this->attributes['winning_probability'] = ($value) ? (float)$value : 0;
    }
}
