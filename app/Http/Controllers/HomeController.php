<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LeadService;
use Carbon\Carbon;
use App\Services\QuotationService;
use Dcblogdev\Dropbox\Facades\Dropbox;
use Illuminate\Support\Facades\Auth;
use App\Services\EmployeeService;
use App\Models\Employee;
use DB;


class HomeController extends Controller
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

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */

  public function index(
    Request $request,
    LeadService $leadService,
    QuotationService $quotationService
  ) {
    $employeeQuotationStatus = $this->getEmployeeQuotationStatus(false);
    $employeeOrderStatus = $this->getEmployeeOrderStatus(false);
    $leadStatus = $this->getLeadStatus(false);
    $quotationStatus = $this->getQuotationStatus(false);

    return view('home', compact(
      'employeeQuotationStatus',
      'employeeOrderStatus',
      'leadStatus',
      'quotationStatus'

    ));

    // $leads = $this->leadsOverview($leadService);
    // $currentMonthLeads = $this->leadsTotalThisMonth($leadService);

    // $quotations = $this->quotationOverview($quotationService);
    // $currentMonthQuotes = $this->quotesTotalThisMonth($quotationService);
    // $currentMonthWonQuote = $this->quotesWonTotalThisMonth($quotationService);

    // $employeeSummary = $this->employeeQuoteSummary($quotationService);

    // $employeeLeadSummary = $this->employeeLeadSummary($leadService);

    // $brandSummary = $this->brandSummary($quotationService);

    // $winStanding = $this->winStandingQuotes($quotationService);

    // $probabilitySummary = $this->winningProbability($quotationService);

    // return view('home', compact(
    //     'leads',
    //     'currentMonthLeads',
    //     'quotations',
    //     'currentMonthQuotes',
    //     'currentMonthWonQuote',
    //     'employeeSummary',
    //     'employeeLeadSummary',
    //     'brandSummary',
    //     'winStanding',
    //     'probabilitySummary'
    // ));
  }

  public function getEmployeeQuotationStatus($returnJson = true)
  {
    $employees = Employee::with(['quotations' => function ($query) {
      $query->select('assigned_to', 'gross_margin');
    }])->get();

    $employeeQuotationStats = [];
    $employeeQuotationStats = $employees->map(function ($employee) {
      return [
        'employee_name' => $employee->user ? $employee->user->name : 'No User Assigned',
        'employee_image' => $employee->image_url,
        'total_margin' => $employee->quotations->sum('gross_margin'),
        'quotation_count' => $employee->quotations->count(),
      ];
    })->filter(function ($employeeStat) {
      return $employeeStat['quotation_count'] > 0;
    })->values();

    return $returnJson ? response()->json($employeeQuotationStats) : $employeeQuotationStats;
  }
  public function getEmployeeOrderStatus($returnJson = true)
  {
    $employeeOrderStats = DB::table('orders')
      ->join('employees', 'employees.id', '=', 'orders.created_by')
      ->leftJoin('users', 'users.id', '=', 'employees.user_id')
      ->select(
        'employees.id as employee_id',
        'employees.image_url as employee_image_url',
        'users.name as user_name',
        DB::raw('SUM(cm_orders.projected_margin) as total_amount'),
        DB::raw('COUNT(cm_orders.id) as order_count')
      )
      ->groupBy('employees.id', 'employees.image_url', 'users.name')
      ->get();

    return $returnJson ? response()->json($employeeOrderStats) : $employeeOrderStats;
  }


  public function getLeadStatus($returnJson = true)
  {
    $leadStats = DB::table('leads')
      ->select(DB::raw('cm_lead_statuses.name as status_name'), DB::raw('count(cm_leads.id) as lead_count'))
      ->join('lead_statuses', 'leads.status_id', '=', 'lead_statuses.id')
      ->where('lead_statuses.name', '!=', 'Converted')
      ->groupBy('lead_statuses.name')
      ->get();

    return $returnJson ? response()->json($leadStats) : $leadStats;
  }
  public function getQuotationStatus($returnJson = true)
  {
    $quotationStats = DB::table('quotations')
      ->select(DB::raw('cm_quotation_statuses.name as status_name'), DB::raw('count(cm_quotations.id) as quotation_count'))
      ->join('quotation_statuses', 'quotations.status_id', '=', 'quotation_statuses.id')
      ->groupBy('quotation_statuses.name')
      ->get();

    return $returnJson ? response()->json($quotationStats) : $quotationStats;
  }


  public function leadsOverview($leadService)
  {
    $data = [
      'start_date' => $this->this_start_month,
      'end_date'   => $this->this_end_month
    ];
    $leads = [];
    $arStatus = $leadService->getLeadStatus(true);

    foreach ($arStatus as $id => $name) {
      $data['status_id'] = [$id];

      $count  = $leadService->getLeadsCount($data);

      $leads[] = ['status' => $name, 'count' => $count];
    }

    return json_encode($leads);
  }

  public function leadsTotalThisMonth($leadService)
  {
    $arStatus = $leadService->getLeadStatus(true);

    $totalCount = $leadService->getLeadsCount([
      'start_date' => $this->this_start_month,
      'end_date'   => $this->this_end_month,
      'status_id'  => array_keys($arStatus)
    ]);

    return $totalCount;
  }

  public function quotationOverview($quotationService)
  {
    $data = [];
    $quotes = [];
    $arStatus = $quotationService->getQuoteStatus(true);

    foreach ($arStatus as $id => $name) {
      $data = [
        'start_date' => $this->this_start_month,
        'end_date'   => $this->this_end_month,
        'status_id'   => [$id]
      ];
      $submitted_count  = $quotationService->getQuotesCount($data);

      $closing_count  = 0;
      // $data = [
      //     'closing_start_date' => $this->this_start_month,
      //     'closing_end_date'   => $this->this_end_month,
      //     'status_id'   => [$id]
      // ];
      // $closing_count  = $quotationService->getQuotesCount($data);

      $quotes[] = ['status' => $name, 'submitted' => $submitted_count, 'closing' => $closing_count];
    }

    return json_encode($quotes);
  }

  public function quotesTotalThisMonth($quotationService)
  {
    $arStatus = $quotationService->getQuoteStatus(false);

    $totalSubmitted = $quotationService->getQuotesCount([
      'start_date' => $this->this_start_month,
      'end_date'   => $this->this_end_month,
      'status_id'  => array_keys($arStatus)
    ]);

    return $totalSubmitted;
  }
  public function quotesWonTotalThisMonth($quotationService)
  {
    $winSummary = $quotationService->winByEmployee([
      'closing_start_date' => $this->this_start_month,
      'closing_end_date'   => $this->this_end_month
    ]);
    $totalwon = 0;
    foreach ($winSummary as $i => $win) {
      $totalwon += (int) $win->quotecount;
    }
    return $totalwon;
  }
  public function employeeLeadSummary($leadService)
  {
    $data = [
      'start_date' => $this->this_start_month,
      'end_date'   => $this->this_end_month
    ];

    $leadsEmpSummary = $leadService->leadsByEmployee($data);

    return json_encode($leadsEmpSummary);
  }

  public function employeeQuoteSummary($quotationService)
  {
    $data = [
      'start_date' => $this->this_start_month,
      'end_date'   => $this->this_end_month
    ];

    $salesSummary = $quotationService->salesValueByEmployee($data);

    return json_encode($salesSummary);
  }
  public function winStandingQuotes($quotationService)
  {
    $data = [
      'closing_start_date' => $this->this_start_month,
      'closing_end_date'   => $this->this_end_month
    ];

    $winSummary = $quotationService->winByEmployee($data);

    foreach ($winSummary as $i => $win) {
      $winSummary[$i]->value = (int) $win->value;
      $winSummary[$i]->quotecount = (int) $win->quotecount;
    }
    return json_encode($winSummary);
  }

  public function brandSummary($quotationService)
  {
    $data = [
      'start_date' => $this->this_start_month,
      'end_date'   => $this->this_end_month
    ];

    $brandsummary = $quotationService->salesValueByBrands($data);

    foreach ($brandsummary as $i => $brd) {
      $brandsummary[$i]->salesvalue = (int) $brd->salesvalue;
    }

    return json_encode($brandsummary);
  }

  public function winningProbability($quotationService)
  {
    $arStatus = $quotationService->getQuoteStatus(false);

    $data = [
      'closing_start_date' => $this->this_start_month,
      'closing_end_date'   => $this->this_end_month,
      'status_id'  => array_keys($arStatus)
    ];

    $probabilitySummary = $quotationService->winProbability($data);

    foreach ($probabilitySummary as $i => $win) {
      $probabilitySummary[$i]->value = (int) $win->value;
      $probabilitySummary[$i]->winning_probability = (int) $win->winning_probability;
    }

    return json_encode($probabilitySummary);
  }
}
