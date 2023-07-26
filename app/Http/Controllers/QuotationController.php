<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuotationService;
use App\Services\LeadService;
use App\Services\LeadHistoryService;
use App\Services\EmployeeService;
use App\Services\CustomerService;
use App\Services\QuoteHistoryService;
use App\Services\SupplierService;
use App\Services\CategoryService;
use App\Services\CountryService;
use App\Services\EmployeeManagerService;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EmailNotification;
use Notification;
use App\Services\UserService;
use App\Services\CreateWordDocxService;
use App\Services\RegionService;
use Illuminate\Http\RedirectResponse;

class QuotationController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, QuotationService $quotationService, EmployeeManagerService $employeeManagerService)
    {
        //
        $input = $request->all();

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


        $quotations = $quotationService->getAllQuotes($input);

        return view('quotation.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        QuotationService $quotationService,
        CustomerService $custService,
        EmployeeService $employeeService,
        CategoryService $categoryService,
        SupplierService $supplierService,
        CountryService $countryService
    ) {

        $companies = $custService->getCompanies();

        $employees = $employeeService->getAllEmployee();

        $quoteStatuses = $quotationService->getQuoteStatus();

        $categories = $categoryService->categoryArray();
        $suppliers = $supplierService->getAllSupplier();
        $countries = $countryService->getAllCountry();

        return view('quotation.create', compact('companies', 'quoteStatuses', 'employees', 'categories', 'suppliers', 'countries'));
    }

    public function convertToQuotation(
        $id,
        QuotationService $quotationService,
        LeadService $leadService,
        CategoryService $categoryService,
        SupplierService $supplierService
    ) {
        $quoteStatuses = $quotationService->getQuoteStatus();

        $categories = $categoryService->categoryArray();
        $suppliers = $supplierService->getAllSupplier();

        $lead = $leadService->getLead($id);

        return view('quotation.convert', compact('lead', 'quoteStatuses', 'categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        QuotationService $quotationService,
        CustomerService $custService,
        LeadService $leadService,
        LeadHistoryService $historyService,
        QuoteHistoryService $quoteHistoryService,
        CreateWordDocxService $wordService
    ) {

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

        // During the lead covertion
        if (isset($data['lead_id']) && !empty($data['lead_id'])) {
            // update leads status
            $lead = $leadService->getLead($data['lead_id']);

            $leadService->updateLead($lead, ['status_id' => 6]); // converted = 6

            $historyService->updateHistory([
                'status_id' => 6, // converted = 6
                'comment'   => 'Enquiry converted to quotation'
            ], $lead->id);
        } else {
            // create new quotation
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
            }

            if ($request->has('customer_id')) {
                if ($request->filled('customer_id')) {

                    // update customer
                    $customer = $custService->getCustomer($data['customer_id']);
                    $custService->updateCustomer($customer, $data);
                } else {
                    // new customer
                    $customer = $custService->createCustomer($data);
                    $data['customer_id'] = $customer->id;
                }
            }
        }
        // display status updates
        if ($request->has('draft')) {
            // draft
            $data['is_active'] = 0;
        } else if ($request->has('publish')) {
            // publish
            $data['is_active'] = 1;
        }
        // create new quotation
        $quotes = $quotationService->createQuote($data);

        // add Quote history
        $status = $quotationService->getQuoteStatusById($data['status_id']);
        $quoteHistoryService->addHistory([
            'status_id' => $data['status_id'],
            'comment'   => 'Quotation is created with status ' . $status->name
        ], $quotes->id);

        /************ Download Quotation **************/
        // $wordService->generateWordDocx($quotes);


        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, QuotationService $quotationService, QuoteHistoryService $quoteHistoryService)
    {

        $quoteStatuses = $quotationService->getQuoteStatus();

        $histories = $quoteHistoryService->getHistories($id);

        $quotation = $quotationService->getQuote($id);

        $parentQuotes  = [];
        if ($quotation->root_parent_id <> 0) {
            $parentQuotes  = $quotationService->getQuotesTree($quotation);
        }

        return view('quotation.show',  compact('quotation', 'histories', 'quoteStatuses', 'parentQuotes'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(
        $id,
        QuotationService $quotationService,
        CustomerService $custService,
        EmployeeService $employeeService,
        CountryService $countryService,
        CategoryService $categoryService,
        SupplierService $supplierService,
        RegionService $regionService
    ) {

        $quotation = $quotationService->getQuote($id);

        $countries = $countryService->getAllCountry();
        $companies = $custService->getCompanies();

        $customers = $custService->getAllCustomer(['company_id' => $quotation->company_id, 'status' => 1]);

        $quoteStatuses = $quotationService->getQuoteStatus();

        $employees = $employeeService->getAllEmployee();

        $categories = $categoryService->categoryArray();
        $suppliers = $supplierService->getAllSupplier();

        $regions = [];
        if (isset($quotation->company->country_id) && $quotation->company->country_id) {

            $regions = $regionService->getAllRegion(['country_id' => $quotation->company->country_id]);
        }

        return view('quotation.edit',  compact(
            'quotation',
            'categories',
            'suppliers',
            'customers',
            'employees',
            'quoteStatuses',
            'companies',
            'countries',
            'regions'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        $id,
        QuotationService $quotationService,
        CustomerService $custService,
        QuoteHistoryService $quoteHistoryService
    ) {
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
                $company = $custService->createCompany($data);
                $data['company_id'] = $company->id;
            }
        }

        if ($request->has('customer_id')) {
            if ($request->filled('customer_id')) {
                // update customer
                $customer = $custService->getCustomer($data['customer_id']);
                $custService->updateCustomer($customer, $data);
            } else {
                // new customer
                $customer = $custService->createCustomer($data);
                $data['customer_id'] = $customer->id;
            }
        }

        // display status updates
        if ($request->has('draft')) {
            // draft
            $data['is_active'] = 0;
        } else if ($request->has('publish')) {
            // publish
            $data['is_active'] = 1;
        }
        // update quotation
        $quotation = $quotationService->getQuote($id);
        $quotationService->updateQuote($quotation, $data);

        // Update the quotation history
        // if ($request->has('status_id')) {
        //     $status = $quotationService->getQuoteStatusById($data['status_id']);
        //     $quoteHistoryService->updateHistory([
        //         'status_id' => $data['status_id'],
        //         'comment'   => 'Updated quotation status to ' .  $status->name
        //     ], $quotation->id);
        // }

        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, QuotationService $quotationService, QuoteHistoryService $quoteHistoryService)
    {
        $quotationService->deleteQuote($id);

        // history of leads deleted
        $quoteHistoryService->deleteHistory($id);

        return redirect()->back()
            ->with('success', 'Quote deleted successfully');
    }

    public function reviseQuotation(
        $id,
        QuotationService $quotationService,
        QuoteHistoryService $quoteHistoryService,
        CustomerService $custService,
        EmployeeService $employeeService
    ) {
        // revise quotation
        $quotation = $quotationService->reviseQuote($id);

        /** Quotation History **/
        $quoteHistoryService->addHistory([
            'status_id' => $quotation->status_id,
            'comment'   => 'Quotation is revised to new version ' . $quotation->reference_no
        ], $quotation->id);


        // $companies = $custService->getCompanies();

        // $customers = $custService->getAllCustomer();

        // $quoteStatuses = $quotationService->getQuoteStatus();

        // $employees = $employeeService->getAllEmployee();


        // return view('quotation.edit',  compact('quotation', 'customers', 'employees', 'quoteStatuses', 'companies'));

        return redirect()->route('quotations.edit', $quotation->id);
    }

    public function updateQuotationStatus(
        Request $request,
        QuotationService $quotationService,
        QuoteHistoryService $quoteHistoryService,
        EmployeeManagerService $employeeManagerService,
        UserService $userService
    ) {

        // $validator = Validator::make($request->all(), [
        //     'title' => 'required',
        //     'body' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => $validator->errors()->all()
        //     ]);
        // }
        $input = $request->all();
        $id = $input['quotation_id'];

        $quotation = $quotationService->getQuote($id);

        $quotationService->updateQuote($quotation, $input);

        $quoteHistoryService->addHistory($input, $id);

        $statusinfo = $quoteHistoryService->getLatestStatus($id);

        // SEND NOTIFICATION MAIL
        $empid = $request->session()->get('employeeid');

        $users = $employeeManagerService->getCoordinators($empid, $userService);

        if (!empty($users)) {
            foreach ($users as $user) {
                $project = [
                    'subject' => 'Quotation qualified to ' . $statusinfo->status,
                    'greeting' => 'Hi ' . $user->name . ',',
                    'body' => 'The quotation get qualified with status "' . $statusinfo->status . '" by the manager "' . $statusinfo->username . '".',
                    'thanks' => 'Thank you',
                    'actionText' => 'View Quotation',
                    'actionURL' => url('quotations/' . $id)
                ];

                // Notification::send($user, new EmailNotification($project));
            }
        }

        return response()->json($statusinfo);
    }

    public function updateQuotationStatusWithOrder(
        Request $request,
        QuotationService $quotationService,
        QuoteHistoryService $quoteHistoryService,
        EmployeeManagerService $employeeManagerService,
        UserService $userService
    ) {

        $request->validate([
            'pono' => 'required|unique:orders,po_number',
            'po_date' => 'required',
            'po_received' => 'required',
        ]);
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required',
        //     'body' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => $validator->errors()->all()
        //     ]);
        // }
        $input = $request->all();

        $id = $input['quotation_id'];

        $quotation = $quotationService->getQuote($id);


        $quotationService->updateQuote($quotation, $input);

        $quotationService->insertOrder($input);

        $quoteHistoryService->addHistory($input, $id);

        $statusinfo = $quoteHistoryService->getLatestStatus($id);

        // SEND NOTIFICATION MAIL
        $empid = $request->session()->get('employeeid');

        $users = $employeeManagerService->getCoordinators($empid, $userService);

        if (!empty($users)) {
            foreach ($users as $user) {
                $project = [
                    'subject' => 'Quotation qualified to ' . $statusinfo->status,
                    'greeting' => 'Hi ' . $user->name . ',',
                    'body' => 'The quotation get qualified with status "' . $statusinfo->status . '" by the manager "' . $statusinfo->username . '".',
                    'thanks' => 'Thank you',
                    'actionText' => 'View Quotation',
                    'actionURL' => url('quotations/' . $id)
                ];

                // Notification::send($user, new EmailNotification($project));
            }
        }

        return response()->json($statusinfo);
    }

    public function downloadQuotation(
        $id,
        QuotationService $quotationService,
        CreateWordDocxService $wordService
    ) {
        $quotation = $quotationService->getQuote($id);


        $wordService->generateWordDocx($quotation);
        //$wordService->sample();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function winningQuotations(Request $request, QuotationService $quotationService, EmployeeManagerService $employeeManagerService)
    {
        //
        $input = $request->all();

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

        $input['status_id'] = [6]; // exclude win 

        $quotations = $quotationService->getAllQuotes($input);

        return view('quotation.won', compact('quotations'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allQuotations(
        Request $request,
        QuotationService $quotationService,
        EmployeeManagerService $employeeManagerService
    ) {
        //
        $input = $request->all();

        $input['is_active'] = [1]; // active quotations only

        $quotations = $quotationService->getAllQuotes($input);

        return view('quotation.listall', compact('quotations'));
    }

    public function setReminder(Request $request, QuotationService $quotationService)
    {
        $data = $request->all();

        $id = $data['quotation_id'];

        $quotation = $quotationService->getQuote($id);


        $quotationService->updateQuote($quotation, $data);

        return response()->json(['success' => true]);
    }

    public function myReminderCalender(Request $request)
    {
        return view('quotation.mycalender');
    }

    public function updateWinDate(
        Request $request,
        QuotationService $quotationService,
        QuoteHistoryService $quoteHistoryService
    ) {
        $input = $request->all();
        $input['status_id'] = 6;
        $id = $input['quotation_id'];

        $quotation = $quotationService->getQuote($id);

        //  $quotationService->updateQuote($quotation, $input);
        if (isset($input['updated_at']) && $input['updated_at']) {
            $quotation->updated_at = strtotime($input['updated_at']);
            $quotation->save(['timestamps' => false]);
        }
        $quoteHistoryService->updateHistory($input, $id);

        return redirect()->back()
            ->with('success', 'Winning Date updated successfully');
    }
}
