<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationField extends Model
{
  use HasFactory;
  protected $fillable = [
    'title',
    'short_code',
    'amount',
    'field_type',
    'description',
    'is_default',
    'status'
  ];
}
