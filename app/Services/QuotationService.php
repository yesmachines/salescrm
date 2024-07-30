<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Quotation;
use App\Models\SalesCommission;
use App\Models\QuotationStatus;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Arr;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use App\Models\QuotationItem;
use App\Models\QuotationCharge;
use App\Models\QuotationTerm;
use App\Models\QuotationAvailability;
use App\Models\QuotatationPaymentTerm;
use App\Models\QuotationInstallation;
use App\Models\QuotationOptionalItem;

class QuotationService
{
  public function createQuote(array $userData): Quotation
  {

    return Quotation::create([
      'company_id'          => $userData['company_id'],
      'customer_id'         => $userData['customer_id'],
      'quote_from'          => $userData['quote_from'],
      //  'category_id'       => $userData['category_id'],
      // 'supplier_id'         =>implode(',', $userData['supplier_id']),
      //'product_models'      => (isset($userData['product_models']) && !empty($userData['product_models'])) ? $userData['product_models'] : '',
      'reference_no'        => $this->getReferenceNumber(),
      'total_amount'        => $userData['total_value'],
      'gross_margin'        => $userData['total_margin_value'],
      'submitted_date'      => (isset($userData['submitted_date']) && !empty($userData['submitted_date'])) ? $userData['submitted_date'] : null,
      'closure_date'        => (isset($userData['closure_date']) && !empty($userData['closure_date'])) ? $userData['closure_date'] : null,
      'winning_probability' => $this->roundUpToAny($userData['winning_probability']),
      'status_id'           => $userData['status_id'],
      'remarks'             => $userData['remarks'],
      'assigned_to'         => $userData['assigned_to'],
      'is_active'           => $userData['is_active'],
      'lead_type'           => $userData['lead_type'],
      'quote_for'           => $userData['quote_for'],
      'reminder'            => $userData['reminder'],
      'is_vat'              => $userData['vat_option'],
      'price_basis'         => $userData['price_basis'],
      'vat_amount'          => isset($userData['vat_amount']) ? $userData['vat_amount'] : $userData['vat_service'],
      'preferred_currency'  => $userData['quote_currency'],
      'currency_rate'       => $userData['currency_rate'],
      'total_status'       => $userData['total_status'],
    ]);
  }
  public function roundUpToAny($n, $x = 5)
  {
    if ($n >= 100) {
      $a = 100;
    } else {
      $a = (round($n) % $x === 0) ? round($n) : round(($n + $x / 2) / $x) * $x;
    }

    return $a;
  }

  public function getReferenceNumber(): ?string
  {
    $StartNo = 001;
    Quotation::lockForUpdate()->get();

    $lastRow = Quotation::whereYear('created_at', '=', date('Y'))
      ->where('reference_no', 'not like', "%Rev%")
      ->orderBy("id", "desc")
      ->first();

    $last = 0;
    if ($lastRow) {
      $alast = explode("/", $lastRow->reference_no);
      $last = (count($alast) > 0) ? (int) $alast[3] : 0;
    }
    $lastId = ($last) ? ($StartNo + $last) : $StartNo;

    $randStr =  "YES/QN/" . date('y') . '/' . $lastId;

    return $randStr;
  }
  public function getQuote($id): Quotation
  {
    return Quotation::find($id);
  }
  public function createOrUpdateCommision(array $userData, $id): int
  {
    foreach ($userData as $input) {
      $sql = SalesCommission::where("quotation_id", $id)
        ->where("manager_id", $input["manager_id"]);
      $isExist = $sql->count();

      $commision = $sql->first();
      $update = [];
      if ($isExist > 0) {
        // update
        if (isset($input["commission_amount"]) && $input["commission_amount"]) {
          $update["commission_amount"] = $input["commission_amount"];
        }
        if (isset($input["percent"]) && $input["percent"]) {
          $update["percent"] = $input["percent"];
        }
        if (isset($input["manager_id"]) && $input["manager_id"]) {
          $update["manager_id"] = $input["manager_id"];
        }
        $commision->update($update);
      } else {
        // insert
        SalesCommission::create([
          'quotation_id'  =>  $id,
          'commission_amount' => $input["commission_amount"],
          'percent'   => $input["percent"] ? $input["percent"] : 0,
          'manager_id' => $input["manager_id"]
        ]);
      }
    }

    return true;
  }

  public function getSalesCommission($id): Object
  {
    $scommission = SalesCommission::where("quotation_id", $id)->where('status', 1)->get();

    return $scommission;
  }
  public function deleteSalesCommission($id = 0, $quotationid = 0)
  {
    if ($id) {
      return SalesCommission::find($id)->delete();
    } else if ($quotationid) {
      return SalesCommission::where("quotation_id", $quotationid)->delete();
    }
  }
  public function otherManagerCommissions($quotation)
  {
    $commissions = DB::table('sales_commissions')
      ->select(
        DB::raw('SUM(commission_amount) as commission_amt'),
        DB::raw('SUM(percent) as percentage')
      )
      ->where("quotation_id", $quotation->id)
      ->where('status', 1)
      ->where('manager_id', '!=', $quotation->assigned_to)
      ->groupBy('quotation_id')
      ->first();


    return (!empty($commissions)) ? $commissions : [];
  }

  public function getAllQuotes($data = []): Object
  {
    $sql = Quotation::orderBy('id', 'desc');

    if (isset($data['status_id'])) {
      $sql->whereIn('status_id', $data['status_id']);
    }
    if (isset($data['is_active'])) {
      $sql->whereIn('is_active', $data['is_active']);
    }
    if (isset($data['winning_probability'])) {
      $sql->where('winning_probability', '>=', $data['winning_probability']);
    }
    if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
      $sql->whereBetween('closure_date', array($data['closing_start_date'], $data['closing_end_date']));
    }
    if (isset($data['assigned_to'])) {
      $sql->whereIn('assigned_to', $data['assigned_to']);
    }

    if (isset($data['isPagination']) && $data['isPagination']) {
      return $sql->paginate(10);
    } else {
      return $sql->get();
    }
  }

  public function getQuoteStatus($ignoreStatus = false): array
  {
    if ($ignoreStatus) { // to ignore status check, TRUE
      return QuotationStatus::orderBy('priority', 'asc')->pluck('name', 'id')->toArray();
    } else { // not ignore status check, FALSE
      return QuotationStatus::where('status', 1)->orderBy('priority', 'asc')->pluck('name', 'id')->toArray();
    }
  }

  public function getQuoteStatusById($id): Object
  {
    return QuotationStatus::find($id);
  }

  public function deleteQuote($id): void
  {

    QuotationItem::where('quotation_id', $id)->delete();
    QuotationCharge::where('quotation_id', $id)->delete();
    QuotationAvailability::where('quotation_id', $id)->delete();
    QuotationInstallation::where('quotation_id', $id)->delete();
    QuotatationPaymentTerm::where('quotation_id', $id)->delete();
    QuotationTerm::where('quotation_id', $id)->delete();

    Quotation::find($id)->delete();
  }

  public function updateQuote(Quotation $quote, array $userData): void
  {

    $update = [];

    if (isset($userData['company_id'])) {
      $update['company_id'] = $userData['company_id'];
    }
    if (isset($userData['customer_id'])) {
      $update['customer_id'] = $userData['customer_id'];
    }
    // if (isset($userData['category_id'])) {
    //   $update['category_id'] = $userData['category_id'];
    // }
    // if (isset($userData['supplier_id'])) {
    //     $update['supplier_id'] = $userData['supplier_id'];
    // }
    // if (isset($userData['product_models'])) {
    //   $update['product_models'] = $userData['product_models'];
    // }
    if (isset($userData['reference_no'])) {
      $update['reference_no'] = $userData['reference_no'];
    }
    if (isset($userData['total_value'])) {
      $update['total_amount'] = $userData['total_value'];
    }
    if (isset($userData['total_margin_value'])) {
      $update['gross_margin'] = $userData['total_margin_value'];
    }
    if (isset($userData['submitted_date']) && !empty($userData['submitted_date'])) {
      $update['submitted_date'] = $userData['submitted_date'];
    }
    if (isset($userData['closure_date']) && !empty($userData['closure_date'])) {
      $update['closure_date'] = $userData['closure_date'];
    }
    if (isset($userData['winning_probability'])) {
      $update['winning_probability'] = $this->roundUpToAny($userData['winning_probability']);
    }
    if (isset($userData['status_id'])) {
      $update['status_id'] = $userData['status_id'];
    }
    if (isset($userData['remarks'])) {
      $update['remarks'] = $userData['remarks'];
    }
    if (isset($userData['assigned_to'])) {
      $update['assigned_to'] = $userData['assigned_to'];
    }
    if (isset($userData['is_active'])) {
      $update['is_active'] = $userData['is_active'];
    }
    if (isset($userData['lead_type'])) {
      $update['lead_type'] = $userData['lead_type'];
    }
    if (isset($userData['quote_for'])) {
      $update['quote_for'] = $userData['quote_for'];
    }
    if (isset($userData['reminder']) && $userData['reminder']) {
      $date = strtotime($userData['reminder']);
      $update['reminder'] = date('Y-m-d H:i:s', $date);
    }
    if (isset($userData['win_date']) && !empty($userData['win_date'])) {
      $update['win_date'] = $userData['win_date'];
    }
    if (isset($userData['vat_option'])) {
      $update['is_vat'] = $userData['vat_option'];
    }
    if (isset($userData['vat_amount']) || isset($userData['vat_service'])) {
      $update['vat_amount'] = isset($userData['vat_amount']) ? $userData['vat_amount'] : $userData['vat_service'];
    }
    if (isset($userData['price_basis'])) {
      $update['price_basis'] = $userData['price_basis'];
    }
    if (isset($userData['quote_currency'])) {
      $update['preferred_currency'] = $userData['quote_currency'];
    }
    if (isset($userData['currency_rate'])) {
      $update['currency_rate'] = $userData['currency_rate'];
    }
    if (isset($userData['total_status'])) {
      $update['total_status'] = $userData['total_status'];
    }


    $quote->update($update);
  }

  public function getQuotesTree($quote): array
  {
    $sql =  Quotation::where('root_parent_id', $quote->root_parent_id)
      ->where('id', '!=', $quote->id)
      ->orderBy('id', 'asc');

    $childCount = $sql->count();
    $childs = $sql->get();

    $parent =  Quotation::where('id', $quote->root_parent_id)->get();
    if ($childCount > 0) {
      $result = Arr::crossJoin($childs, $parent);
    } else {
      $result = $parent;
    }
    return Arr::flatten($result);
  }

  public function reviseQuote($id): Object
  {
    // $childQuotes = Quotation::where('parent_id', $id)->latest();
    // $count = $childQuotes->count();

    $quotation = Quotation::find($id);
    $next = 0;
    if (!$quotation->parent_id) {
      // new quotation
      $newquote = $quotation->replicate();
      $newquote->root_parent_id  = $id;
      $next = 1;
      $newquote->reference_no     = $quotation->reference_no . '/Rev' . $next;
    } else {
      //  $childRow = $childQuotes->first();
      //  $newquote = $childRow->replicate();
      $newquote = $quotation->replicate();
      $count = Quotation::where('root_parent_id', $quotation->root_parent_id)->count();
      $newquote->root_parent_id  = $quotation->root_parent_id;
      $next = $count + 1;

      $aref = explode("/", $quotation->reference_no);
      array_pop($aref); // remove last element
      $newRef = 'Rev' . $next; // create new ref no.
      array_push($aref, $newRef);

      $newquote->reference_no   = implode("/", $aref);
    }
    $newquote->submitted_date   = date("Y-m-d");
    $newquote->closure_date     = date("Y-m-d");
    $newquote->remarks       = '';
    $newquote->parent_id     = $id;
    $newquote->save();

    // DISABLE Parent Quote - restrict it from being counted
    $quotation->is_active = 2;
    $quotation->save();

    /************ DISABLE SALES COMMISSIONS *************/
    $ifExists = SalesCommission::where('quotation_id', $quotation->id)->count();
    if ($ifExists) {
      SalesCommission::where('quotation_id', $quotation->id)->update(['status' => 0]);
    }

    /************** Quotation ITEMS **************/
    $quotationItems = QuotationItem::where('quotation_id', $quotation->id)->get();
    if (count($quotationItems) > 0) {
      foreach ($quotationItems as $item) {
        $itemRow = $item->replicate();
        $itemRow->quotation_id  = $newquote->id;
        $itemRow->save();
      }
    }

    /************** Quotation Charges **************/
    $quotationCharges = QuotationCharge::where('quotation_id', $quotation->id)->get();
    if (count($quotationCharges) > 0) {
      foreach ($quotationCharges as $charge) {
        $chargeRow = $charge->replicate();
        $chargeRow->quotation_id  = $newquote->id;
        $chargeRow->save();
      }
    }
    /************** Quotation Availability **************/
    $quotationAvailability = QuotationAvailability::where('quotation_id', $quotation->id)->first();
    if (!empty($quotationAvailability)) {
      $availRow = $quotationAvailability->replicate();
      $availRow->quotation_id  = $newquote->id;
      $availRow->save();
    }

    /************** Quotation Payment Term **************/
    $quotationPayment = QuotatationPaymentTerm::where('quotation_id', $quotation->id)->get();
    if (count($quotationPayment) > 0) {
      foreach ($quotationPayment as $payment) {
        $paymentRow = $payment->replicate();
        $paymentRow->quotation_id  = $newquote->id;
        $paymentRow->save();
      }
    }
    /************** Quotation Installation **************/
    $quotationInstallation = QuotationInstallation::where('quotation_id', $quotation->id)->first();
    if (!empty($quotationInstallation)) {
      $installRow = $quotationInstallation->replicate();
      $installRow->quotation_id  = $newquote->id;
      $installRow->save();
    }

    /************** Quotation Terms **************/
    $quotationTerms = QuotationTerm::where('quotation_id', $quotation->id)->get();
    if (count($quotationTerms) > 0) {
      foreach ($quotationTerms as $terms) {
        $termRow = $terms->replicate();
        $termRow->quotation_id  = $newquote->id;
        $termRow->save();
      }
    }
    $optionalItems = QuotationOptionalItem::where('quotation_id', $quotation->id)->get();
    if (count($optionalItems) > 0) {
      foreach ($optionalItems as $optionalItem) {
        $optionalRow = $optionalItem->replicate();
        $optionalRow->quotation_id  = $newquote->id;
        $optionalRow->save();
      }
    }

    return $newquote;
  }


  public function getQuotesCount($data = []): int
  {
    $sql = Quotation::query()->where('is_active', 1)->orderBy('id', 'desc');

    if (isset($data['status_id'])) {
      $sql->whereIn('status_id', $data['status_id']);
    }
    if (isset($data['start_date']) && isset($data['end_date'])) {
      $sql->whereBetween('submitted_date', [$data['start_date'], $data['end_date']]);
    }
    if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
      $sql->whereBetween('closure_date',  [$data['closing_start_date'], $data['closing_end_date']]);
    }
    return $sql->get()->count();
  }

  public function salesValueByEmployee($data = []): Object
  {
    // $sql = DB::table('quotations as q')
    //     ->join('employees as e', 'q.assigned_to', '=', 'e.id')
    //     ->join('users as u', 'e.user_id', '=', 'u.id')
    //     ->select(
    //         'u.name as employee',
    //         DB::raw('count(*) as quotecount'),
    //         DB::raw('SUM(total_amount) as salesvalue'),
    //         DB::raw('SUM(gross_margin) as margin')
    //     )
    //     ->where('q.is_active', 1);

    // Sales values & quote count
    $sql = DB::table('quotations as q')
      ->join('employees as e', 'q.assigned_to', '=', 'e.id')
      ->join('users as u', 'e.user_id', '=', 'u.id')
      ->select(
        'e.id as empid',
        'u.name as employee',
        DB::raw('count(*) as quotecount'),
        DB::raw('SUM(total_amount) as salesvalue')
      )
      ->where('q.is_active', 1);

    if (isset($data['start_date']) && isset($data['end_date'])) {
      $sql->whereBetween('q.submitted_date', array($data['start_date'], $data['end_date']));
    }
    if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
      $sql->whereBetween('q.closure_date', array($data['closing_start_date'], $data['closing_end_date']));
    }
    $sql->groupBy('q.assigned_to')->orderBy('employee', 'asc');

    $quote_summary = $sql->get();

    // Gross Margins
    $sql2 = DB::table('sales_commissions as s')
      ->join('employees as e', 's.manager_id', '=', 'e.id')
      ->join('users as u', 'e.user_id', '=', 'u.id')
      ->join('quotations as q', 's.quotation_id', '=', 'q.id')
      ->select(
        'e.id as empid',
        DB::raw('SUM(commission_amount) as margin')
      )
      ->where('q.is_active', 1);

    if (isset($data['start_date']) && isset($data['end_date'])) {
      $sql2->whereBetween('q.submitted_date', array($data['start_date'], $data['end_date']));
    }
    if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
      $sql2->whereBetween('q.closure_date', array($data['closing_start_date'], $data['closing_end_date']));
    }
    $sql2->groupBy('s.manager_id')->orderBy('empid', 'asc');

    $sales_commisions = $sql2->get()->pluck('margin', 'empid')->toArray();

    foreach ($quote_summary as $i => $quote) {
      $quote_summary[$i]->margin = $sales_commisions[$quote->empid];
    }

    return $quote_summary;
  }

  public function salesValueByBrands($data = []): Object
  {
    $sql = DB::table('quotations as q')
      ->join('suppliers as s', 'q.supplier_id', '=', 's.id')
      ->select(
        's.brand',
        DB::raw('count(*) as quotecount'),
        DB::raw('SUM(total_amount) as salesvalue'),
        DB::raw('SUM(gross_margin) as margin')
      )
      ->where('q.is_active', 1);

    if (isset($data['start_date']) && isset($data['end_date'])) {
      $sql->whereBetween('q.submitted_date', array($data['start_date'], $data['end_date']));
    }
    if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
      $sql->whereBetween('q.closure_date', array($data['closing_start_date'], $data['closing_end_date']));
    }
    if (isset($data['assigned_to'])) {
      $sql->where('q.assigned_to', $data['assigned_to']);
    }
    if (isset($data['manager_id'])) {
      $sql->where('s.manager_id', $data['manager_id']);
    }

    $sql->groupBy('q.supplier_id')->orderBy('salesvalue', 'desc');

    return $sql->get();
  }

  public function winByEmployee($data = []): Object
  {
    // Sales Value
    $sql = DB::table('quotations as q')
      ->join('employees as e', 'q.assigned_to', '=', 'e.id')
      ->join('users as u', 'e.user_id', '=', 'u.id')
      // ->join('quotation_histories as h', function ($join) {
      //     $join->on('h.quotation_id', '=', 'q.id')->where('h.status_id', '=', 6);
      // })
      ->select(
        'e.id as empid',
        'u.name as employee',
        DB::raw('count(*) as quotecount'),
        DB::raw('SUM(total_amount) as salesvalue')
      )
      ->where('q.is_active', 1)
      ->where('q.status_id', 6); // win quotes

    if (isset($data['start_date']) && isset($data['end_date'])) {
      $sql->whereBetween('q.submitted_date', array($data['start_date'], $data['end_date']));
    }
    // if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
    //     $sql->whereBetween('h.updated_at', array($data['closing_start_date'], $data['closing_end_date']));
    // }
    if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
      $sql->whereBetween('q.win_date', array($data['closing_start_date'], $data['closing_end_date']));
    }
    $sql->groupBy('q.assigned_to')->orderBy('empid', 'asc');

    $winResult =  $sql->get();

    // Gross Margins
    $sql2 = DB::table('sales_commissions as s')
      ->join('employees as e', 's.manager_id', '=', 'e.id')
      ->join('users as u', 'e.user_id', '=', 'u.id')
      ->join('quotations as q', 's.quotation_id', '=', 'q.id')
      ->select(
        'e.id as empid',
        DB::raw('SUM(commission_amount) as margin')
      )
      ->where('q.is_active', 1)
      ->where('q.status_id', 6); // win quotes

    if (isset($data['start_date']) && isset($data['end_date'])) {
      $sql2->whereBetween('q.submitted_date', array($data['start_date'], $data['end_date']));
    }
    if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
      $sql2->whereBetween('q.win_date', array($data['closing_start_date'], $data['closing_end_date']));
    }
    $sql2->groupBy('s.manager_id')->orderBy('empid', 'asc');

    $sales_commisions = $sql2->get()->pluck('margin', 'empid')->toArray();

    foreach ($winResult as $i => $quote) {
      $winResult[$i]->value = $sales_commisions[$quote->empid];
    }
    //dd($winResult);
    return $winResult;
  }

  public function winProbability($data = []): Object
  {
    $sql = DB::table('quotations as q')
      ->select(
        'q.winning_probability',
        DB::raw('count(*) as quotecount'),
        DB::raw('SUM(total_amount) as salesvalue'),
        DB::raw('SUM(gross_margin) as value'),
      )
      ->where('q.is_active', 1);

    if (isset($data['start_date']) && isset($data['end_date'])) {
      $sql->whereBetween('q.submitted_date', array($data['start_date'], $data['end_date']));
    }
    if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
      $sql->whereBetween('q.closure_date', array($data['closing_start_date'], $data['closing_end_date']));
    }
    if (isset($data['status_id'])) {
      $sql->whereIn('q.status_id', $data['status_id']);
    }
    $sql->groupBy('q.winning_probability')->orderBy('winning_probability', 'asc');

    return $sql->get();
  }

  public function winPerformaceByMonth($data = []): Object
  {

    $sql = DB::table('quotations as q')
      ->select(
        DB::raw('count(*) as quotecount'),
        DB::raw('SUM(total_amount) as salesvalue'),
        DB::raw('SUM(gross_margin) as margin'),
        DB::raw("MONTHNAME(win_date) as month_name")
      )
      ->where('q.is_active', 1)
      ->whereYear('q.win_date', $data['year_of'])
      ->where('q.status_id', 6); // win quotes

    if (isset($data['assigned_to'])) {
      $sql->where('q.assigned_to', $data['assigned_to']);
    }
    $winMonthly = $sql->groupBy('month_name')->get();

    // Gross Margins
    $sql2 = DB::table('sales_commissions as s')
      ->join('quotations as q', 's.quotation_id', '=', 'q.id')
      ->select(
        DB::raw("MONTHNAME(win_date) as month_name"),
        DB::raw('SUM(commission_amount) as margin')
      )
      ->where('q.is_active', 1)
      ->whereYear('q.win_date', $data['year_of'])
      ->where('q.status_id', 6); // win quotes

    if (isset($data['assigned_to'])) {
      $sql->where('q.assigned_to', $data['assigned_to']);
    }
    $sql2->groupBy('month_name');
    $sales_commisions = $sql2->get()->pluck('margin', 'month_name')->toArray();


    foreach ($winMonthly as $i => $quote) {
      $winMonthly[$i]->margin = $sales_commisions[$quote->month_name];
    }

    return $winMonthly;
  }

  public function brandPerformaceByMonth($data = []): Object
  {
    $sql = DB::table('quotations as q')
      ->join('suppliers as s', 'q.supplier_id', '=', 's.id')
      ->select(
        's.brand',
        DB::raw('count(*) as quotecount'),
        DB::raw('SUM(total_amount) as salesvalue'),
        DB::raw('SUM(gross_margin) as margin'),
        DB::raw("MONTHNAME(submitted_date) as month_name")
      )
      ->where('q.is_active', 1)
      ->whereYear('q.submitted_date', $data['year_of']);

    if (isset($data['assigned_to'])) {
      $sql->where('q.assigned_to', $data['assigned_to']);
    }
    $sql->groupBy('month_name')->groupBy('q.supplier_id');

    $brand_monthly = $sql->get();

    return $brand_monthly;
  }

  // windate bulk update
  public function updateAllWinDate()
  {
    $winhistories = DB::table('quotation_histories')->where('status_id', 6)->orderBy('quotation_id', 'asc')->get();

    foreach ($winhistories as $his) {
      $quote = Quotation::find($his->quotation_id);
      $quote->win_date = $his->updated_at;
      $quote->save();
      //
    }
  }
  // sales commission bulk update
  public function updateAllCommissions()
  {
    $allquotations = DB::table('quotations')->where('is_active', 1)->orderBy('id', 'asc')->get();

    foreach ($allquotations as $quote) {
      $commision = [
        'quotation_id'  => $quote->id,
        'commission_amount' =>  $quote->gross_margin,
        'percent'   => 100,
        'manager_id' => $quote->assigned_to
      ];
      // create new if not exists
      SalesCommission::firstOrCreate(
        [
          'quotation_id' => $quote->id,
          'manager_id'   => $quote->assigned_to
        ],
        $commision
      );
      //
    }
  }
  public function quotationStatus()
  {
    return QuotationStatus::where('status', 1)->get();
  }
}
