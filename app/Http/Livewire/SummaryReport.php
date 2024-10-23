<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Stock;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Exports\SummaryNumberExport;
use Illuminate\Support\Facades\DB;

class SummaryReport extends Component
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
    $query = $this->getSummaryQuery($request);
    $currentPage = $this->page;
    $perPage = $this->perPage;
    $currentPageResults = $query->forPage($currentPage, $perPage);
    $totalResults = $query->count();

    $summaryNumbers = new LengthAwarePaginator(
      $currentPageResults,
      $totalResults,
      $perPage,
      $currentPage,
      ['path' => Paginator::resolveCurrentPath()]
    );

    return view('livewire.summary-report', ['summaryNumbers' => $summaryNumbers]);
  }

  public function getSummaryQuery(Request $request)
  {

    $orders = Order::all();
    $stocks = Stock::all();
    $combinedData = $orders->concat($stocks);
    if ($this->search) {
        $combinedData = $combinedData->filter(function ($item) {
            return isset($item->os_number) && stripos(trim($item->os_number), trim($this->search)) !== false;
        });
    }

    $sortedData = $combinedData->sortByDesc(function ($item) {
      return isset($item->os_number) ? (int) last(explode('/', $item->os_number)) : 0;
    });

    if ($this->startDate && $this->endDate) {
      $sortedData = $sortedData->filter(function ($item) {
        $date = Carbon::parse($item->created_at);
        return $date->between($this->startDate, $this->endDate);
      });
    }

    return $sortedData->map(function ($item) {
      if ($item instanceof Stock) {
        return $item;
      } else {
        $item->buying_price = null;
        return $item;
      }
    })->values();
  }


  public function downloadSummaryReport()
  {
    $summaryNumbers = $this->getSummaryQuery(request());
    return (new SummaryNumberExport($summaryNumbers))->download('summary-report.xlsx');
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
