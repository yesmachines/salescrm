<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Services\CustomerService;
use App\Services\VisitorService;

class VisitorController extends BaseController
{

    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CustomerService $custService)
    {
        //
        $input = $request->all();

        $companies = $custService->getCompanies();

        $customers = $custService->getAllCustomer($input);

        //return view('customers.index', compact('customers', 'companies'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, CustomerService $custService, VisitorService $guestService)
    {

        $data = $request->all();
        // create company
        if (isset($data['company_id']) && !empty($data['company_id'])) {
            // update company
            $company = $custService->getCompany($data['company_id']);
            //   $custService->updateCompany($company, $data);
        } else {
            // add new
            $company = $custService->createCompany($data);
            $data['company_id'] = $company->id;
        }

        // create customer
        if (isset($data['customer_id']) && !empty($data['customer_id'])) {

            // update customer
            $customer = $custService->getCustomer($data['customer_id']);
            // $custService->updateCustomer($customer, $data);
        } else {

            // new customer
            $customer = $custService->createCustomer($data);
            $data['customer_id'] = $customer->id;
        }
        // dd($data);

        $visitor = $guestService->createGuest($data);


        return $this->sendResponse($visitor, 'Customer register successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, VisitorService $guestService)
    {
        $visitor = $guestService->getVisitor($id);

        if (is_null($visitor)) {
            return $this->sendError('Visitor not found.');
        }
        $visitor->companyname = $visitor->company->company;
        $visitor->fullname    = $visitor->customer->fullname;
        $visitor->email    = $visitor->customer->email;
        $visitor->phone    = $visitor->customer->phone;

        return $this->sendResponse($visitor, 'Visitor retrieved successfully.');
    }


    public function searchByCustomer(Request $request, CustomerService $custService)
    {

        $input = $request->all();

        $resData = [];

        if (isset($input['company'])) {
            $resData = $custService->getAllCompany($input);
        } else if (isset($input['fullname'])) {
            $resData = $custService->getAllCustomer($input);
        }

        return $this->sendResponse($resData, 'Search data retrieved successfully.');
    }

    // public function searchCustomer(Request $request, CustomerService $custService)
    // {
    //     $input = $request->all();

    //     $customers = $custService->getAllCustomer($input);

    //     return $this->sendResponse($customers, 'Customers retrieved successfully.');
    // }
}
