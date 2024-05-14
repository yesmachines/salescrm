<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Services\EmployeeManagerService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\Lead;


class LeadsTable extends Component
{
  use WithPagination;

  public $paginationTheme = 'bootstrap';
  public $search;
  public $selectedDateOption;
  public $customDateRange;
  public $startDate;
  public $endDate;
  public $perPage = 100;



  protected $listeners = ['dateRangePicker' => 'updateDateRange'];

  public function render(Request $request, EmployeeManagerService $employeeManagerService)
  {
    $query = $this->getLeadQuery($request, $employeeManagerService);
    $leads = $query->paginate($this->perPage, ['*'], 'page', $this->page); // Manually set the current page
    return view('livewire.leads-table', ['leads' => $leads]);
  }

  protected function getLeadQuery(Request $request, EmployeeManagerService $employeeManagerService)
  {
    $input = $request->all();
    $search = $this->search;

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

    $input['status_id'] = array(1, 2, 3, 4, 5); // exclude converted leads


    // $query = $leadService->getAllLead($input);
    $query = Lead::orderBy('id', 'desc');

    if (isset($input['customer_id'])) {
      $query->where('customer_id', $input['customer_id']);
    }
    if (isset($input['status_id'])) {
      $query->whereIn('status_id', $input['status_id']);
    }

    if (isset($input['assigned_to'])) {
      $query->whereIn('assigned_to', $input['assigned_to']);
    }

    if ($this->startDate && $this->endDate) {

      $query->whereBetween('enquiry_date', [$this->startDate, $this->endDate]);
    }

    $trimmedSearch = trim($this->search);

    $query->when($trimmedSearch, function ($query) use ($trimmedSearch) {
      return $query->where(function ($subquery) use ($trimmedSearch) {
        $subquery->whereHas('company', function ($companyQuery) use ($trimmedSearch) {
          $companyQuery->where('company', 'like', $trimmedSearch . '%');
        });

        $subquery->orWhereHas('customer', function ($customerQuery) use ($trimmedSearch) {
          $customerQuery->where(function ($phoneOrEmailQuery) use ($trimmedSearch) {
            $phoneOrEmailQuery->where('phone', 'like', $trimmedSearch . '%')
            ->orWhere('email', 'like', $trimmedSearch . '%');
          });
        });
        $subquery->orWhere(function ($leadQuery) use ($trimmedSearch) {
          $leadQuery->where('details', 'like', $trimmedSearch . '%');

        });
      });
    });
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
      $this->endDate =null;
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
