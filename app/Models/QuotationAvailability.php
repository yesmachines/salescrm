<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationAvailability extends Model
{
  use HasFactory;
  protected $fillable = [
    'quotation_id',
    'stock_status',
    'working_weeks',
    'working_period'

  ];
}
