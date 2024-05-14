<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationInstallation extends Model
{
  use HasFactory;
  protected $fillable = [
    'quotation_id',
    'installation_by',
    'installation_periods',
    'install_accommodation',
    'install_tickets',
    'install_transport',
    'install_buyer_site'
  ];
}
