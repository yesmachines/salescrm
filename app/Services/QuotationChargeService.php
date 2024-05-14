<?php

namespace App\Services;

use App\Models\QuotationCharge;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\QuotationField;
use Illuminate\Database\Eloquent\Collection;


class QuotationChargeService
{
  public function getQuotationCharge($data = []): Object
  {

    $quotationCharges = QuotationField::where('is_default', '=','1')
    ->where('field_type','=','quotation_charges')
    ->get();
    return $quotationCharges;
  }
  public function getQuotationTerms($data = []): Object
  {

    $quotationTerms = QuotationField::where('is_default', '=','1')
    ->where('field_type','=','quotation_terms')
    ->get();
    return $quotationTerms;
  }
  public function getPaymentCyclesList($data = []): Object
  {

    $paymentTerms= QuotationField::where('is_default', '=','1')
    ->where('field_type','=','payment_terms')
    ->get();
    return $paymentTerms;
  }

  public function getQuotionCharge($id): Collection
  {

    return Collection::make(QuotationCharge::where('quotation_id', $id)->get());

  }
}
