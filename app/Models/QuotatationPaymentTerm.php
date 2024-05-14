<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotatationPaymentTerm extends Model
{
  use HasFactory;
  protected $fillable = [
    'quotation_id',
    'title',
    'payment_amount',

  ];
}
