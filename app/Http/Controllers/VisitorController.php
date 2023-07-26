<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;
use App\Services\VisitorService;


class VisitorController extends Controller
{

    function __construct()
    {
        $this->middleware('can:visitors.index', ['only' => ['index']]);
        $this->middleware('can:visitors.show', ['only' => ['show']]);
        $this->middleware('can:visitors.create', ['only' => ['create', 'store']]);
        $this->middleware('can:visitors.edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:visitors.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, VisitorService $guestService, CustomerService $custService)
    {
        //
        $input = $request->all();

        $companies = $custService->getCompanies();

        $visitors = $guestService->getAllVisitor($input);

        return view('visitors.index', compact('visitors', 'companies'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(VisitorService $guestService)
    {

        return view('visitors.create');
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
            $custService->updateCompany($company, $data);
        } else {
            // add new
            $company = $custService->createCompany($data);
            $data['company_id'] = $company->id;
        }

        // create customer
        if (isset($data['customer_id']) && !empty($data['customer_id'])) {

            // update customer
            $customer = $custService->getCustomer($data['customer_id']);
            $custService->updateCustomer($customer, $data);
        } else {

            // new customer
            $customer = $custService->createCustomer($data);
            $data['customer_id'] = $customer->id;
        }
        // dd($data);

        $visitor = $guestService->createGuest($data);

        return redirect()->back()->with('success', 'Visitor created successfully');
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

        //Generate pdf
        $body = view('visitors._printqrcode')
            ->with(compact('visitor'))
            ->render();

        // if (is_null($visitor)) {
        //     return $this->sendError('Visitor not found.');
        // }

        return response()->json(array('success' => true, 'html' => $body));
    }
}
