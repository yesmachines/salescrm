<?php

namespace App\Services;

use App\Models\QuotationTerm;
use Illuminate\Http\Request;
use App\Models\Quotation;

use Illuminate\Database\Eloquent\Collection;


class QuotationTermService
{

  public function getQuotionTerm($id): Collection
  {

    return Collection::make(QuotationTerm::where('quotation_id', $id)
    ->where('group_title','quotation_terms')->get());

  }
  public function getPaymentCycle($id): Collection
  {

    return Collection::make(QuotationTerm::where('quotation_id', $id)
    ->where('group_title','payment_terms')->get());

  }
    public function getQuotationTerms($id): Collection
  {

   $quotationTerms=QuotationTerm::where('quotation_id', $id)
    ->where('group_title','quotation_terms')->get();
    return  $quotationTerms;
  }


}
