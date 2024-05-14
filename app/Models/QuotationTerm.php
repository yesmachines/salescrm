<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationTerm extends Model
{
    use HasFactory;
    protected $fillable = [
      'quotation_id',
      'title',
      'short_code',
      'description',
      'sort_order',
      'group_title',

   ];
}
