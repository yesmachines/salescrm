<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LeadService;
use App\Services\EmployeeService;
use App\Services\EmployeeManagerService;
use App\Services\CustomerService;
use App\Services\LeadHistoryService;
use App\Services\CountryService;
use App\Services\UserService;
use App\Services\RegionService;
use App\Notifications\EmailNotification;
use Illuminate\Support\Facades\Auth;
use Notification;
use Twilio\Rest\Client;
use App\Models\Company;
use App\Enums\EnquirySource;

class LeadController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * showing only PENDING LEADS
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // showing only pending leads
        // $input = $request->all();
        //
        // $arrIds = [];
        // $empid = $request->session()->get('employeeid');
        // if (Auth::user()->hasAnyRole(['divisionmanager', 'salesmanager'])) {
        //     $arrIds[] = $empid;
        // } elseif (Auth::user()->hasRole('coordinators')) {
        //     $arrIds[] = $empid;
        //
        //     $managers = $employeeManagerService->getManagers($empid);
        //     $arrIds = array_merge($arrIds, $managers);
        // }
        // if (!empty($arrIds)) $input['assigned_to'] = $arrIds;
        //
        // $input['status_id'] = array(1, 2, 3, 4, 5); // exclude converted leads
        //
        // $leads = $leadService->getAllLead($input);

        return view('leads.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        LeadService $leadService,
        CustomerService $custService,
        EmployeeService $employeeService,
        CountryService $countryService
    ) {

        $companies = $custService->getCompanies();

        $leadStatuses = $leadService->getLeadStatus();

        $employees = $employeeService->getAllEmployee();

        $countries = $countryService->getAllCountry();
        
        $enquirySource = EnquirySource::toArray();
        $expo = \App\Models\Expo::pluck('name','id');

        return view('leads.create', compact('companies', 'leadStatuses', 'employees', 'countries', 'enquirySource', 'expo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        LeadService $leadService,
        CustomerService $custService,
        LeadHistoryService $historyService,
        EmployeeService $employeeService,
        UserService $userService
    ) {

        $this->validate($request, [
            'company'       => 'required',
            'fullname'      => 'required',
            'country_id'    => 'required',
            'email'         => 'email',
            'phone'         => 'required',
            'lead_type'     => 'required',
            'enquiry_date'  => 'required|date',
            'assigned_to'   => 'required',
            'assigned_on'   => 'required',
            'status_id'     => 'required',
            'details'       => 'required',
            'expo_id'       => 'required_if:lead_type,expo'
        ]);

        $data = $request->all();

        // if company exists
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
        // if customer exists
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

        $lead = $leadService->createLead($data);

        $status = $leadService->getLeadStatusById($data['status_id']);
        $historyService->addHistory([
            'status_id' => $data['status_id'],
            'comment'   => 'New enquiry is created with status ' . $status->name
        ], $lead->id);

        /**********************************************************/
        // SEND Notification mail to managers
        $employee = $employeeService->getEmployee($data['assigned_to']);

        if (!empty($employee)) {
            $project = [
                'subject' => 'New lead',
                'greeting' => 'Hi ' . $employee->user->name . ',',
                'body' => 'New lead created, please check the below link to qualify it.',
                'thanks' => 'Thank you',
                'actionText' => 'View Lead',
                'actionURL' => url('leads/' . $lead->id)
            ];

            $user = $userService->getUser($employee->user_id);

            //Notification::send($user, new EmailNotification($project));
        }


        return redirect()->back()->with('success', 'Enquiry created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, LeadService $leadService, LeadHistoryService $historyService)
    {
        //
        $lead = $leadService->getLead($id);

        $histories = $historyService->getHistories($id);

        $leadStatuses = $leadService->getLeadStatus();

        return view('leads.show',  compact('lead', 'histories', 'leadStatuses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(
        $id,
        LeadService $leadService,
        CustomerService $custService,
        EmployeeService $employeeService,
        CountryService $countryService,
        RegionService $regionService
    ) {


        $lead = $leadService->getLead($id);

        $countries =  $countryService->getAllCountry();

        //  $companies = $custService->getCompanies();
        $cinput['status'] = 1;
        $companies = $custService->getAllCompany($cinput);

        $customers = $custService->getAllCustomer(['company_id' => $lead->company_id, 'status' => 1]);

        $leadStatuses = $leadService->getLeadStatus();

        $employees = $employeeService->getAllEmployee();

        $regions = [];
        if (isset($lead->company->country_id) && $lead->company->country_id) {

            $regions = $regionService->getAllRegion(['country_id' => $lead->company->country_id]);
        }

        $enquirySource = EnquirySource::toArray();
        $expo = \App\Models\Expo::pluck('name','id');
        return view('leads.edit',  compact('lead', 'customers', 'employees', 'leadStatuses', 'companies', 'countries', 'regions', 'enquirySource','expo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, LeadService $leadService, CustomerService $custService, LeadHistoryService $historyService)
    {
        //
        $this->validate($request, [
            'company'       => 'required',
            'fullname'      => 'required',
            'country_id'    => 'required',
            'email'         => 'email',
            'phone'         => 'required',
            'lead_type'     => 'required',
            'enquiry_date'  => 'required|date',
            'assigned_to'   => 'required',
            'assigned_on'   => 'required',
            // 'status_id'     => 'required',
            'details'       => 'required',
            'expo_id'       => 'required_if:lead_type,expo'
        ]);
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

        $lead = $leadService->getLead($id);

        $leadService->updateLead($lead, $data);

        // For status
        // if ($request->has('status_id')) {
        //     $status = $leadService->getLeadStatusById($data['status_id']);

        //     $historyService->updateHistory([
        //         'status_id' => $data['status_id'],
        //         'comment'   => 'Updated enquiry status to ' .  $status->name
        //     ], $lead->id);
        // }

        return redirect()->back()->with('success', 'Lead updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, LeadService $leadService, LeadHistoryService $historyService)
    {

        $leadService->deleteLead($id);

        // history of leads deleted
        $historyService->deleteHistory($id);

        return redirect()->back()
            ->with('success', 'Lead deleted successfully');
    }

    public function updateLeadStatus(
        Request $request,
        LeadService $leadService,
        LeadHistoryService $historyService,
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
        $id = $input['lead_id'];

        $lead = $leadService->getLead($id);
        $leadService->updateLead($lead, $input);

        $historyService->addHistory($input, $id);

        $statusinfo = $historyService->getLatestStatus($id);

        // SEND NOTIFICATION MAIL
        $empid = $request->session()->get('employeeid');

        $users = $employeeManagerService->getCoordinators($empid, $userService);

        if (!empty($users)) {
            foreach ($users as $user) {
                $project = [
                    'subject' => 'Leads qualified to ' . $statusinfo->status,
                    'greeting' => 'Hi ' . $user->name . ',',
                    'body' => 'The leads get qualified with status "' . $statusinfo->status . '" by the manager "' . $statusinfo->username . '".',
                    'thanks' => 'Thank you',
                    'actionText' => 'View Lead',
                    'actionURL' => url('leads/' . $id)
                ];

                //  Notification::send($user, new EmailNotification($project));
            }
        }

        return response()->json($statusinfo);
    }


    public function checkToQualify($id, LeadService $leadService)
    {
        $isQualify =  $leadService->checkQualifyLead($id);

        return response()->json($isQualify);
    }

    // showing only converted leads
    public function convertedLeads()
    {
        //
        // $input = $request->all();
        //
        // $arrIds = [];
        // $empid = $request->session()->get('employeeid');
        // if (Auth::user()->hasAnyRole(['divisionmanager', 'salesmanager'])) {
        //     $arrIds[] = $empid;
        // } elseif (Auth::user()->hasRole('coordinators')) {
        //     $arrIds[] = $empid;
        //
        //     $managers = $employeeManagerService->getManagers($empid);
        //     $arrIds = array_merge($arrIds, $managers);
        // }
        // if (!empty($arrIds)) $input['assigned_to'] = $arrIds;
        //
        // $input['status_id'] = array(6); // only converted leads
        //
        // $leads = $leadService->getAllLead($input);

        return view('leads.converted');
    }
    public function leadStatusList(LeadService $leadService)
    {

        $leadList = $leadService->leadStatus();

        return view('lead-status.leadStatus', compact('leadList'));
    }
    public function checkCompany(Request $request)
    {
        $companyName = $request->input('companyName');
        $companyExists = Company::where('company', 'LIKE', $companyName)
            ->where('status', 1)
            ->exists();

        return response()->json(['exists' => $companyExists]);
    }
}
