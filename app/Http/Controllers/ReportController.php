<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\LeadService;
use App\Services\QuotationService;
use App\Services\EmployeeService;
use App\Services\CustomerService;
use App\Services\CountryService;

class ReportController extends Controller
{
    protected $this_start_month = null;
    protected $this_end_month = null;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->this_start_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->this_end_month = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function leadsSummary(Request $request, LeadService $leadService)
    {
        //
        $input = $request->all();
        if (empty($input)) {
            $input = [
                'start_date' => $this->this_start_month,
                'end_date'   => $this->this_end_month
            ];
        }

        $leadsEmpSummary = $leadService->leadsByEmployee($input);

        return view('reports.leadSummary', compact('leadsEmpSummary', 'input'));
    }

    public function quotationSummary(Request $request, QuotationService $quotationService)
    {
        //
        $input = $request->all();
        if (empty($input)) {
            $input = [
                'start_date' => $this->this_start_month,
                'end_date'   => $this->this_end_month
            ];
        }

        $quoteSummary = $quotationService->salesValueByEmployee($input);

        return view('reports.quotationSummary', compact('quoteSummary', 'input'));
    }

    public function employeeWinStanding(Request $request, QuotationService $quotationService)
    {
        // $input = [];
        // $winSummary = [];
        $input = $request->all();
        if (empty($input)) {
            $input['closing_start_date'] = $this->this_start_month;
            $input['closing_end_date']   = $this->this_end_month;
        }
        $winSummary = $quotationService->winByEmployee($input);


        return view('reports.winnerSummary', compact('winSummary', 'input'));
    }

    public function brandSaleSummary(Request $request, QuotationService $quotationService)
    {
        //
        $input = $request->all();
        if (empty($input)) {
            $input = [
                'start_date' => $this->this_start_month,
                'end_date'   => $this->this_end_month
            ];
        }

        $brandSummary = $quotationService->salesValueByBrands($input);

        return view('reports.brandSummary', compact('brandSummary', 'input'));
    }

    public function probabilitySummary(Request $request, QuotationService $quotationService)
    {
        //
        $input = $request->all();
        $arStatus = $quotationService->getQuoteStatus(false);

        if (empty($input)) {
            $input['closing_start_date'] = $this->this_start_month;
            $input['closing_end_date']   = $this->this_end_month;
        }
        $input['status_id']  = array_keys($arStatus);

        $probabilitySummary = $quotationService->winProbability($input);

        return view('reports.probabilitySummary', compact('probabilitySummary', 'input'));
    }
    public function employeeLists(Request $request, EmployeeService $employeeService)
    {
        $input = $request->all();

        $input['status'] = 1;
        $allEmp = $employeeService->getAllEmployee($input);

        $employees = [];
        $i = 0;
        foreach ($allEmp as $emp) {
            if ($emp->lead) {
                $i++;
                $employees[$i] = $emp;
            }
        }

        return view('reports.employeeLists', compact('employees'));
    }

    public function employeeSalesProfile(
        $id,
        Request $request,
        EmployeeService $employeeService,
        LeadService $leadService,
        QuotationService $quotationService
    ) {
        $input = $request->all();

        $employee = $employeeService->getEmployee($id);
        $target = $employeeService->getEmployeeTarget($id, ['fiscal_year' => isset($input['yearof']) ? $input['yearof'] : date('Y')]);

        // Leads Overview
        $data = [
            'assigned_to' => $id,
            'status_id'     => [1, 2, 3, 5, 6]
        ];
        $leads = $leadService->leadsByType($data);

        // Brand vs Enquiry Count
        $data1 = [
            'manager_id' => $id,
        ];
        if (!empty($input) && isset($input['start_date']) && isset($input['end_date'])) {
            $data1['start_date']   = $input['start_date'];
            $data1['end_date']   = $input['end_date'];
        }
        $brandenquiries = $quotationService->salesValueByBrands($data1);

        $overallQuotes = [];
        $sales = 0;
        $margin = 0;
        $totalEnq = 0;
        foreach ($brandenquiries as $enq) {
            $sales += $enq->salesvalue;
            $margin += $enq->margin;
            $totalEnq += $enq->quotecount;
        }
        $overallQuotes = ['total_sales' => $sales, 'total_margin' => $margin, 'total_enquiry' => $totalEnq];

        // WIN/ Sales Performance Vs Months
        $data2 = [
            'assigned_to' => $id,
            'year_of'     => isset($input['yearof']) ? $input['yearof'] : date('Y')
        ];
        $salesPerformace = $quotationService->winPerformaceByMonth($data2);

        $overallWin = [];
        $s = 0;
        $m = 0;
        $w = 0;
        foreach ($salesPerformace as $enq) {
            $s += $enq->salesvalue;
            $m += $enq->margin;
            $w += $enq->quotecount;
        }
        $overallWin = ['total_sales' => $s, 'total_margin' => $m, 'total_enquiry' => $w];

        // Product Performance Vs Months
        $data3 = [
            'assigned_to' => $id,
            'year_of'     =>  isset($input['yearof']) ? $input['yearof'] : date('Y')
        ];
        $productMonth = $quotationService->brandPerformaceByMonth($data3);


        return view('reports.employeeProfile', compact(
            'employee',
            'target',
            'leads',
            'brandenquiries',
            'overallQuotes',
            'input',
            'salesPerformace',
            'overallWin',
            'productMonth'
        ));
    }
    public function customerReports() {
      return view('reports.customerReports');
  }

}
