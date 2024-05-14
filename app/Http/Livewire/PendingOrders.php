<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendingOrders extends Component
{

  public $page = 1;
  use WithPagination;
  public $paginationTheme = 'bootstrap';
  private $orders;
  public $startDate;
  public $endDate;
  public $selectedDateOption;
  public $customDateRange;
  public $search;
  public $perPage = 100;
  protected $listeners = ['dateRangePicker' => 'updateDateRange'];


  public function render(Request $request)
  {
    $query = $this->getPendingQuery($request);
    $orders = $query->paginate($this->perPage, ['*'], 'page', $this->page); // Manually set the current page

    return view('livewire.pending-orders', ['orders' => $orders]);
  }

  public function getPendingQuery(Request $request)
  {

    $search = $this->search;
    $trimmedSearch = trim($this->search);

    $data = array('status' => ['open', 'partial']);
    // $orders = $orderService->getOrder($data);
    if (isset($data['status']) && $data['status']) {
      $query = Order::whereIn('status', $data['status']);
    }
    if ($this->startDate && $this->endDate) {
      $query->whereBetween('os_date', [$this->startDate, $this->endDate]);
    }
    $query->when($trimmedSearch, function ($query) use ($trimmedSearch) {
      return $query->whereHas('company', function ($subquery) use ($trimmedSearch) {
        $subquery->where('company', 'like', $trimmedSearch . '%');
      })
        ->orWhere('os_number', 'like', $trimmedSearch . '%')
        ->orWhere('po_number', 'like', $trimmedSearch . '%');
    });
    $query->orderBy('id', 'desc');

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
