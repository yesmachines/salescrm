<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LeadStatus;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Quotation;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'customer_id',
        'lead_type',
        'enquiry_mode',
        'enquiry_date',
        'details',
        'assigned_to',
        'assigned_on',
        'respond_on',
        'status_id',
        'created_by',
        'interested',
        'expo_id'
    ];
    public function setEnquiryDateAttribute($value)
    {
        $this->attributes['enquiry_date'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function setAssignedOnAttribute($value)
    {
        $this->attributes['assigned_on'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function setRespondOnAttribute($value)
    {
        $this->attributes['respond_on'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }

    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id', 'id');
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
    public function getLeadTypeLabelAttribute()
    {
        return \App\Enums\EnquirySource::from($this->lead_type)->label(); // assuming $this->status contains the string value
    }
    public function getEnquiryModeLabelAttribute()
    {
        return \App\Enums\EnquiryMode::from($this->enquiry_mode)->label();
    }
    public function expo()
    {
        return $this->hasOne(Expo::class, 'id', 'expo_id');
    }
     public function calls() {
        return $this->hasMany(LeadCallHistory::class, 'lead_id', 'id');
    }
}
