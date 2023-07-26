<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Orders;
use App\Models\QuotationStatus;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Arr;

class QuotationService
{
    public function createQuote(array $userData): Quotation
    {
        return Quotation::create([
            'company_id'        => $userData['company_id'],
            'customer_id'       => $userData['customer_id'],
            //  'category_id'       => $userData['category_id'],
            'supplier_id'       => $userData['supplier_id'],
            'product_models'    => (isset($userData['product_models']) && !empty($userData['product_models'])) ? $userData['product_models'] : '',
            'reference_no'      => $this->getReferenceNumber(),
            'total_amount'      => $userData['total_amount'],
            'gross_margin'      => $userData['gross_margin'],
            'submitted_date'    => (isset($userData['submitted_date']) && !empty($userData['submitted_date'])) ? $userData['submitted_date'] : null,
            'closure_date'      => (isset($userData['closure_date']) && !empty($userData['closure_date'])) ? $userData['closure_date'] : null,
            'winning_probability' => $this->roundUpToAny($userData['winning_probability']),
            'status_id'         => $userData['status_id'],
            'remarks'           => $userData['remarks'],
            'assigned_to'       => $userData['assigned_to'],
            'is_active'         => $userData['is_active'],
            'lead_type'         => $userData['lead_type'],
            'quote_for'         => $userData['quote_for'],
            'reminder'          => $userData['reminder']
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
        $StartNo = 841;
        $last = Quotation::latest()->first();
        // $lastId = ($last) ? $last->id : $StartNo;

        $lastId = ($last) ? ($StartNo + $last->id) : $StartNo;

        $randStr =  "YES/QN/" . date('y') . '/' . $lastId;

        return $randStr;
    }

    public function getQuote($id): Quotation
    {
        return Quotation::find($id);
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
        // delete user
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
        if (isset($userData['category_id'])) {
            $update['category_id'] = $userData['category_id'];
        }
        if (isset($userData['supplier_id'])) {
            $update['supplier_id'] = $userData['supplier_id'];
        }
        if (isset($userData['product_models'])) {
            $update['product_models'] = $userData['product_models'];
        }
        if (isset($userData['reference_no'])) {
            $update['reference_no'] = $userData['reference_no'];
        }
        if (isset($userData['total_amount'])) {
            $update['total_amount'] = $userData['total_amount'];
        }
        if (isset($userData['gross_margin'])) {
            $update['gross_margin'] = $userData['gross_margin'];
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

        $quote->update($update);
    }

    public function insertOrder(array $userData): void
    {

        $orders = new Orders();
        // $update = [];
        // if (isset($userData['company_id'])) {
        //     $update['company_id'] = $userData['company_id'];
        // }
        // if (isset($userData['customer_id'])) {
        //     $update['customer_id'] = $userData['customer_id'];
        // }
        // if (isset($userData['pono'])) {
        //     $update['po_number'] = $userData['pono'];
        // }
        // if (isset($userData['po_date'])) {
        //     $update['po_date'] = $userData['po_date'];
        // }
        // if (isset($userData['po_received'])) {
        //     $update['po_received'] = $userData['po_received'];
        // }

        $orders->company_id    =  $userData['company_id'];
        $orders->customer_id   =  $userData['customer_id'];
        $orders->po_number     =  $userData['pono'];
        $orders->yespo_no      =  $userData['yespo_no'];
        $orders->po_date       =  $userData['po_date'];
        $orders->po_received   =  $userData['po_received'];
        $orders->short_link_code   = strtotime("now");
        $orders->status        =  'open';
        $orders->save();
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
        $newquote->total_amount     = 0;
        $newquote->gross_margin     = 0;
        $newquote->submitted_date   = date("Y-m-d");
        $newquote->closure_date     = date("Y-m-d");
        $newquote->winning_probability     = 0;
        $newquote->remarks       = '';
        $newquote->parent_id     = $id;
        $newquote->save();

        // DISABLE Parent Quote - restrict it from being counted
        $quotation->is_active = 2;
        $quotation->save();

        return $newquote;
    }


    public function getQuotesCount($data = []): int
    {
        $sql = Quotation::where('is_active', 1)->orderBy('id', 'desc');

        if (isset($data['status_id'])) {
            $sql->whereIn('status_id', $data['status_id']);
        }
        if (isset($data['start_date']) && isset($data['end_date'])) {
            $sql->whereBetween('submitted_date', array($data['start_date'], $data['end_date']));
        }
        if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
            $sql->whereBetween('closure_date', array($data['closing_start_date'], $data['closing_end_date']));
        }

        return $sql->get()->count();
    }

    public function salesValueByEmployee($data = []): Object
    {
        $sql = DB::table('quotations as q')
            ->join('employees as e', 'q.assigned_to', '=', 'e.id')
            ->join('users as u', 'e.user_id', '=', 'u.id')
            ->select(
                'u.name as employee',
                DB::raw('count(*) as quotecount'),
                DB::raw('SUM(total_amount) as salesvalue'),
                DB::raw('SUM(gross_margin) as margin')
            )
            ->where('q.is_active', 1);


        if (isset($data['start_date']) && isset($data['end_date'])) {
            $sql->whereBetween('submitted_date', array($data['start_date'], $data['end_date']));
        }
        if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
            $sql->whereBetween('closure_date', array($data['closing_start_date'], $data['closing_end_date']));
        }
        $sql->groupBy('q.assigned_to')->orderBy('employee', 'asc');

        return $sql->get();
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
            $sql->whereBetween('submitted_date', array($data['start_date'], $data['end_date']));
        }
        if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
            $sql->whereBetween('closure_date', array($data['closing_start_date'], $data['closing_end_date']));
        }
        if (isset($data['start_date']) && isset($data['end_date'])) {
            $sql->whereBetween('submitted_date', array($data['start_date'], $data['end_date']));
        }
        if (isset($data['start_date']) && isset($data['end_date'])) {
            $sql->whereBetween('submitted_date', array($data['start_date'], $data['end_date']));
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
        //   DB::enableQueryLog();

        $sql = DB::table('quotations as q')
            ->join('employees as e', 'q.assigned_to', '=', 'e.id')
            ->join('users as u', 'e.user_id', '=', 'u.id')
            // ->join('quotation_histories as h', 'h.quotation_id', '=', 'q.id')
            ->join('quotation_histories as h', function ($join) {
                $join->on('h.quotation_id', '=', 'q.id')->where('h.status_id', '=', 6);
            })
            ->select(
                'u.name as employee',
                DB::raw('count(*) as quotecount'),
                //DB::raw('SUM(total_amount) as salesvalue'),
                DB::raw('SUM(gross_margin) as value')
            )
            ->where('q.is_active', 1)
            ->where('q.status_id', 6); // win quotes

        if (isset($data['start_date']) && isset($data['end_date'])) {
            $sql->whereBetween('submitted_date', array($data['start_date'], $data['end_date']));
        }
        if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
            $sql->whereBetween('h.created_at', array($data['closing_start_date'], $data['closing_end_date']));
        }
        $sql->groupBy('q.assigned_to')->orderBy('value', 'asc');

        $winResult =  $sql->get();

        //   dd(DB::getQueryLog());

        return $winResult;
    }

    public function winProbability($data = []): Object
    {
        $sql = DB::table('quotations as q')
            ->select(
                'q.winning_probability',
                DB::raw('count(*) as quotecount'),
                DB::raw('SUM(total_amount) as salesvalue'),
                DB::raw('SUM(gross_margin) as value')
            )
            ->where('q.is_active', 1);


        if (isset($data['start_date']) && isset($data['end_date'])) {
            $sql->whereBetween('submitted_date', array($data['start_date'], $data['end_date']));
        }
        if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
            $sql->whereBetween('closure_date', array($data['closing_start_date'], $data['closing_end_date']));
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
                DB::raw("MONTHNAME(submitted_date) as month_name")
            )
            ->where('q.is_active', 1)
            ->whereYear('q.created_at', $data['year_of'])
            ->where('q.status_id', 6); // win quotes

        // if (isset($data['start_date']) && isset($data['end_date'])) {
        //     $sql->whereBetween('submitted_date', array($data['start_date'], $data['end_date']));
        // }
        // if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
        //     $sql->whereBetween('closure_date', array($data['closing_start_date'], $data['closing_end_date']));
        // }
        if (isset($data['assigned_to'])) {
            $sql->where('q.assigned_to', $data['assigned_to']);
        }
        $sql->groupBy('month_name');

        return $sql->get();
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
            ->whereYear('q.created_at', $data['year_of']);
        // ->where('q.status_id', 6); // win quotes

        // if (isset($data['start_date']) && isset($data['end_date'])) {
        //     $sql->whereBetween('submitted_date', array($data['start_date'], $data['end_date']));
        // }
        // if (isset($data['closing_start_date']) && isset($data['closing_end_date'])) {
        //     $sql->whereBetween('closure_date', array($data['closing_start_date'], $data['closing_end_date']));
        // }
        if (isset($data['assigned_to'])) {
            $sql->where('q.assigned_to', $data['assigned_to']);
        }
        $sql->groupBy('month_name')->groupBy('q.supplier_id');

        return $sql->get();
    }
}
