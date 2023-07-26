<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;
// use App\Http\Requests\StoreCustomerRequest;
use App\Services\CountryService;
use App\Services\RegionService;

class CustomerController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CustomerService $custService, CountryService $countryService)
    {
        //
        $input = $request->all();

        $companies = $custService->getCompanies();

        $customers = $custService->getAllCustomer($input);

        $countries = $countryService->getAllCountry();

        return view('customers.index', compact('customers', 'companies', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CustomerService $custService, CountryService $countryService)
    {
        $countries = $countryService->getAllCountry();
        return view('customers.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CustomerService $custService)
    {
        // $this->validate($request, [
        //     'name'          => 'required',
        //     'emp_num'       => 'required',
        //     'email'         => 'required|email|unique:users,email',
        //     'password'      => 'required|same:confirm-password',
        //     'roles'         => 'required',
        //     'division'      => 'required',
        //     'image_url'     => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        // ]);
        $data = $request->all();

        if ($request->has('company_id')) {
            if ($request->filled('company_id')) {
                // update company
                $company = $custService->getCompany($data['company_id']);
                $custService->updateCompany($company, $data);
            } else {
                // new company
                $company = $custService->createCompany($data);
                $data['company_id'] = $company->id;
            }
        } else {
            // new company
            $company = $custService->createCompany($data);
            $data['company_id'] = $company->id;
        }

        $customer = $custService->createCustomer($data);


        return redirect()->back()->with('success', 'Customer created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, CustomerService $custService)
    {
        //
        $customer = $custService->getCustomer($id);

        return response()->json($customer);
    }
    public function autosearchCompany(Request $request, CustomerService $custService)
    {
        $data = [];
        $input = [];

        $input['company'] = $request->search;
        $input['status'] = 1;

        $data = $custService->getAllCompany($input);

        $customers = [];
        foreach ($data as $i => $cust) {
            $customers[$i] = [
                'value' => $cust->id,
                'label' => $cust->company
            ];
        }

        return response()->json($customers);
    }

    public function customersByCompany(Request $request, CustomerService $custService)
    {
        //
        $data = $request->all();
        $data['status'] = 1;
        $customer = $custService->getAllCustomer($data);

        return response()->json($customer);
    }
    public function companyById(Request $request, CustomerService $custService)
    {
        //
        $data = $request->all();
        $company = $custService->getCompany($data['company_id']);

        return response()->json($company);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, CustomerService $custService, CountryService $countryService, RegionService $regionService)
    {

        $customer = $custService->getCustomer($id);

        $countries = $countryService->getAllCountry();

        $regions = [];
        if (isset($customer->company->country_id) && $customer->company->country_id) {

            $regions = $regionService->getAllRegion(['country_id' => $customer->company->country_id]);
        }

        return view('customers.edit',  compact('customer', 'countries', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, CustomerService $custService)
    {
        //
        // $this->validate($request, [
        //     'name'          => 'required',
        //     'email'         => 'required|email|unique:users,email,' . $request->input('user_id'),
        //     'password'      => 'same:confirm-password',
        //     'roles'         => 'required',
        //     'designation'   => 'required',
        //     'image_url'     =>  'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        // ]);
        $data = $request->all();

        if ($request->has('company_id')) {
            if ($request->filled('company_id')) {
                // update company
                $company = $custService->getCompany($data['company_id']);
                $custService->updateCompany($company, $data);
            } else {
                // new company
                // $company = $custService->createCompany($data);
                //   $data['company_id'] = $company->id;
            }
        }

        $customer = $custService->getCustomer($id);

        $custService->updateCustomer($customer, $data);

        return redirect()->back()->with('success', 'Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, CustomerService $custService)
    {

        $custService->DeleteCustomer($id);

        return redirect()->back()
            ->with('success', 'Customer deleted successfully');
    }
}
