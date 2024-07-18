<?php

namespace App\Services;

use App\Models\QuotationOptionalItem;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;


class QuotationOptionalService
{



  public function quotationOptionalItem($id): Collection
  {

    return Collection::make(QuotationOptionalItem::where('quotation_id', $id)->get());

  }
}
