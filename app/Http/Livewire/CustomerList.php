<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\Customer;


class CustomerList extends Component
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

  public function render(Request $request, CustomerService $custService)
  {
    $query = $this->getCustomer($request, $custService);
    $customers = $query->paginate($this->perPage, ['*'], 'page', $this->page);
    return view('livewire.customer-list', ['customers' => $customers]);
  }

  protected function getCustomer(Request $request, CustomerService $custService)
  {
    $input = $request->all();
    $search = $this->search;

    $trimmedSearch = trim($this->search);

    $sql = Customer::orderBy('id', 'desc');

    if (isset($data['company_id'])) {
      $sql->where('company_id', $data['company_id']);
    }
    if (isset($data['status'])) {
      $sql->where('status', $data['status']);
    }

    if (!empty($trimmedSearch)) {
      $sql->where(function($query) use ($trimmedSearch) {
        $query->where('fullname', 'like', $trimmedSearch . '%')
        ->orWhere('email', 'like', $trimmedSearch . '%')
        ->orWhere('phone', 'like', $trimmedSearch . '%');
      });

      $sql->orWhereHas('company', function ($query) use ($trimmedSearch) {
        $query->where('company', 'like', $trimmedSearch . '%');
      });

    }
    if (!empty($this->startDate) && !empty($this->endDate)) {
      $sql->whereBetween('created_at', [$this->startDate, $this->endDate]);
    }
    $query= $sql;
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
  public function toggleStatus($customerId)
  {

    $customer = Customer::findOrFail($customerId);
    $customer->status = !$customer->status ? 1 : 0;
    $customer->save();
  }
}
