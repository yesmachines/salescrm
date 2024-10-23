<?php

namespace App\Http\Livewire;


use Illuminate\Http\Request;
use Livewire\Component;
use App\Services\CountryService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Exports\CustomerReportExport;

class CustomerReports extends Component
{
  use WithPagination;

  public $paginationTheme = 'bootstrap';
  public $search;
  public $selectedCountry = '';

  public function render(Request $request, CustomerService $custService, CountryService $countryService)
  {
    $query = $this->getCustomerQuery($request, $custService, $countryService);
    $countries = $countryService->getAllCountry();
    $customers = $query->paginate(100, ['*'], 'page', $this->page); // Manually set the current page
    return view('livewire.customer-reports', ['customersData' => $customers, 'countries' => $countries]);
  }

  protected function getCustomerQuery(Request $request, CustomerService $custService, CountryService $countryService)
  {
    $input = $request->all();
    $search = $this->search;
    $input['status'] = 1;
    // $query = Customer::where('status', $input['status']);
    // if (!empty($this->selectedCountry)) {
    //   $query->where('location', $this->selectedCountry);
    // }
    $query = Customer::query()
      ->leftJoin('companies', 'customers.company_id', '=', 'companies.id')
      ->leftJoin('countries', 'companies.country_id', '=', 'countries.id')
      ->where('customers.status', $input['status'])
      ->select(
        'customers.*',
        'companies.company as company_name',
        'companies.reference_no as company_reference_no',
        'countries.name as country_name',
      );

    if (!empty($this->selectedCountry)) {
      $query->where('companies.country_id', $this->selectedCountry);
    }
    $query->orderBy('customers.id', 'desc');
    return $query;
  }
  public function exportToExcel()
  {
    $query = $this->getCustomerQuery(request(), new CustomerService(), new CountryService());
    $customerData = $query->lazy();
    $selectedCustomerIds = $this->getSelectedCustomerIds();
    if (isset($selectedCustomerIds)) {
      $customerData = Customer::whereIn('id', $selectedCustomerIds)->get();
    }
    return (new CustomerReportExport($customerData))->download('customer-reports.xlsx');
  }
  public function updateSearch()
  {
    $this->resetPage();
  }
  public function updatedSelectedCountry($value)
  {
    logger('Country Changed:', [$value]);
  }
  protected function getSelectedCustomerIds() {}

  public function gotoPage($page)
  {
    $this->page = $page;
  }
}
