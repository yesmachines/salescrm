<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Services\QuotationService;
use App\Services\EmployeeManagerService;
use Illuminate\Support\Facades\Auth;
use App\Models\Quotation;
use Livewire\WithPagination;
use Carbon\Carbon;

class QuotationList extends Component
{
  public $page = 1;
  use WithPagination;
  public $paginationTheme = 'bootstrap';
  private $quotations;
  public $startDate;
  public $endDate;
  public $selectedDateOption;
  public $customDateRange;
  public $search;
  public $perPage = 100; // Default value for number of records per page
  protected $listeners = ['dateRangePicker' => 'updateDateRange'];

  public function render(
    Request $request,
    QuotationService $quotationService,
    EmployeeManagerService $employeeManagerService
  ) {

    $query = $this->getQuotationQuery($request, $quotationService, $employeeManagerService);
    $quotations = $query->paginate($this->perPage, ['*'], 'page', $this->page); // Manually set the current page

    foreach ($quotations as $x => $quote) {
      $brand = [];
      if ($quote->supplier_id == 0) {
        foreach ($quote->quotationItem as $item) {
          $brand[] = $item->supplier->brand;
        }
      }
      $quotations[$x]->suppliername = !empty($brand) ? implode(", ", $brand) : '';
    }
    return view('livewire.quotation-list', ['quotations' => $quotations]);
  }
  protected function getQuotationQuery(Request $request, QuotationService $quotationService, EmployeeManagerService $employeeManagerService)
  {
    $input = $request->all();
    $search = $this->search;
    $input['is_active'] = [0, 1];

    $arrIds = [];
    $empid = $request->session()->get('employeeid');
    if (Auth::user()->hasAnyRole(['divisionmanager', 'salesmanager'])) {
      $arrIds[] = $empid;
    } elseif (Auth::user()->hasRole('coordinators')) {
      $arrIds[] = $empid;

      $managers = $employeeManagerService->getManagers($empid);
      $arrIds = array_merge($arrIds, $managers);
    }
    if (!empty($arrIds)) $input['assigned_to'] = $arrIds;

    $input['status_id'] = [1, 2, 3, 4, 5]; // exclude win

    // $query = $quotationService->getAllQuotes($input);

    $query = Quotation::with('quotationItem')->orderBy('id', 'desc');

    if (isset($input['status_id'])) {
      $query->whereIn('status_id', $input['status_id']);
    }
    if (isset($input['is_active'])) {
      $query->whereIn('is_active', $input['is_active']);
    }
    if (isset($input['winning_probability'])) {
      $query->where('winning_probability', '>=', $input['winning_probability']);
    }
    if (isset($input['closing_start_date']) && isset($input['closing_end_date'])) {
      $query->whereBetween('closure_date', array($input['closing_start_date'], $data['closing_end_date']));
    }
    if (isset($input['assigned_to'])) {
      $query->whereIn('assigned_to', $input['assigned_to']);
    }

    if ($this->startDate && $this->endDate) {
      $query->whereBetween('submitted_date', [$this->startDate, $this->endDate]);
    }
    $trimmedSearch = trim($this->search);

    $query->when($trimmedSearch, function ($query) use ($trimmedSearch) {
      return $query->whereHas('company', function ($subquery) use ($trimmedSearch) {
        $subquery->where('company', 'like', $trimmedSearch . '%');
      })->orWhereHas('supplier', function ($subquery) use ($trimmedSearch) {
        $subquery->where('brand', 'like', $trimmedSearch . '%');
      })
        ->orWhere('reference_no', 'like', $trimmedSearch . '%');
    });
    $query->orderBy('quotations.updated_at', 'desc');
    return $query;
  }

  public function updateDateRange($dateRange)
  {
    $this->startDate = $dateRange['startDate'];
    $this->endDate = $dateRange['endDate'];
    $this->applyDateFilter();
    $this->emit('updateBrowserHistory', ['start_date' => $this->startDate, 'end_date' => $this->endDate]);
  }

  public function updateDateOptions()
  {
    $this->applyDateFilter();
  }

  private function applyDateFilter()
  {
    switch ($this->selectedDateOption) {
      case "all":
        $this->startDate = null;
        $this->endDate = null;
        break;
      case "today":
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        break;
      case "yesterday":
        $this->startDate = now()->subDay()->format('Y-m-d');
        $this->endDate = now()->subDay()->format('Y-m-d');
        break;
      case "month":
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        break;
      case "1_month":
        $this->startDate = now()->subMonth()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->subMonth()->endOfMonth()->format('Y-m-d');
        break;
      case "3_months":
        $this->startDate = now()->subMonths(2)->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        break;
      case "6_months":
        $this->startDate = now()->subMonths(5)->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        break;
      case "custom":
        // Ensure that the start and end dates are properly received
        $this->startDate = Carbon::parse($this->startDate)->addDay()->toDateString();
        $this->endDate = Carbon::parse($this->endDate)->addDay()->toDateString();
        break;
    }
  }

  public function updateSearch($searchTerm)
  {
    $this->resetPage();
  }

  public function gotoPage($page)
  {
    $this->page = $page;
  }
}
