<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use App\Http\Resources\PaginateResource;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeadStatusToCoordinator;
use App\Enums\EnquirySource;
use App\Enums\EnquiryMode;
use App\Enums\EnquiryPriority;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\LeadHistory;
use App\Models\Product;
use App\Models\LeadStatus;
use App\Models\LeadProduct;
use App\Models\LeadShare;
use App\Models\User;
use App\Models\Area;

class EnquiryController extends Controller {

    public function getExtras() {
        $data['modes'] = EnquiryMode::toKeyLabelArray();
        $data['statuses'] = LeadStatus::select('id', 'name')
                ->where('status', 1)
                ->orderBy('priority', 'asc')
                ->get();
        $data['priorities'] = EnquiryPriority::toKeyLabelArray();
        $data['coordinator'] = $this->getCoordinator();
        $data['areas'] = Area::select('id', 'name')
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        $data['expos'] = \App\Models\Expo::select('id', 'name')->where('status', 1)->get();
        return successResponse(trans('api.success'), $data);
    }

    public function getStatus() {
        $data = LeadStatus::select('id', 'name')
                ->where('status', 1)
                ->orderBy('priority', 'asc')
                ->get();
        return successResponse(trans('api.success'), $data);
    }

    public function getCompanies(Request $request) {
        $rules = [
            'search_text' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $companies = Company::select('id', 'company', 'region_id', 'country_id')
                        ->orderBy('company', 'asc')
                        ->where('company', 'LIKE', "%{$request->search_text}%")
                        ->where('status', 1)->get()->map(function ($company) {
            if ($company->region_id > 0) {
                $company->company .= ($company->region) ? ', ' . $company->region->state : '';
            }
            if ($company->country_id > 0) {
                $company->company .= ($company->country) ? ', ' . $company->country->name : '';
            }
            return [
        'id' => $company->id,
        'name' => $company->company,
            ];
        });
        return successResponse(trans('api.success'), $companies);
    }

    public function getCustomers($company_id) {
        $customers = Customer::select('id', 'fullname', 'email', 'phone')
                ->where('company_id', $company_id)
                ->where('status', 1)
                ->get();
        return successResponse(trans('api.success'), $customers);
    }

    public function getExpo() {
        $expo = \App\Models\Expo::select('id', 'name')->where('status', 1)->get();
        return successResponse(trans('api.success'), $expo);
    }

    public function dashboard(Request $request) {
        $leadStatus = LeadStatus::where('status', 1)->get();
        $leadToQualifyStatusIds = $leadStatus->where('is_qualify', 0)->pluck('id');
        $leadQualifiedStatusIds = $leadStatus->where('is_qualify', 1)->pluck('id');

        $data['to_qualify'] = Lead::where('assigned_to', $request->user()->employee->id)->whereIn('status_id', $leadToQualifyStatusIds)->count();
        $data['qualified'] = Lead::where('assigned_to', $request->user()->employee->id)->whereIn('status_id', $leadQualifiedStatusIds)->count();
        $data['sahred'] = LeadShare::where('shared_by', $request->user()->id)->count();
        $data['sahred_to_me'] = LeadShare::where('shared_to', $request->user()->id)->count();
        return successResponse(trans('api.success'), $data);
    }

    public function index(Request $request) {
        $enquiryTypes = array_column(EnquirySource::cases(), 'value');
        $rules = [
            'type' => ['required', 'in:to_qualify,qualified,sahred,sahred_to_me,' . implode(',', $enquiryTypes)],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $enquirySql = Lead::select('leads.id', 'leads.expo_id', 'leads.company_id', 'leads.status_id', 'leads.details', 'leads.enquiry_date', 'leads.lead_type')
                ->with(['company:id,company', 'leadStatus:id,name', 'expo:id,name'])
                ->orderBy('leads.enquiry_date', 'DESC');

        if (in_array($request->type, $enquiryTypes)) {
            $enquirySql->where('leads.assigned_to', $request->user()->employee->id)
                    ->where('leads.lead_type', $request->type);
            if (!empty($request->expo_id)) {
                $enquirySql->where('leads.expo_id', $request->expo_id);
            }
        } else {
            switch ($request->type) {
                case 'to_qualify':
                    $leadQualifiedStatus = LeadStatus::where('status', 1)
                            ->where('is_qualify', 0)
                            ->pluck('id');
                    $enquirySql->where('leads.assigned_to', $request->user()->employee->id)
                            ->whereIn('leads.status_id', $leadQualifiedStatus);
                    break;
                case 'qualified':
                    $leadQualifiedStatus = LeadStatus::where('status', 1)
                            ->where('is_qualify', 1)
                            ->pluck('id');
                    $enquirySql->where('leads.assigned_to', $request->user()->employee->id)
                            ->whereIn('leads.status_id', $leadQualifiedStatus);
                    break;
                case 'sahred':
                    $enquirySql->join('lead_shares', 'lead_shares.lead_id', 'leads.id')
                            ->where('lead_shares.shared_by', $request->user()->id);

                    $stext = $request->search_text;
                    $enquirySql->when($stext, function ($query) use ($stext) {
                        $query->where(function ($subQuery) use ($stext) {
                            // Search in company name
                            $subQuery->whereHas('company', function ($query) use ($stext) {
                                        $query->where('company', 'like', '%' . $stext . '%');
                                    })
                                    // Search in lead details
                                    ->orWhere('leads.details', 'like', '%' . $stext . '%');
                        });
                    });

                    break;
                case 'sahred_to_me':
                    $enquirySql->join('lead_shares', 'lead_shares.lead_id', 'leads.id')
                            ->where('lead_shares.shared_to', $request->user()->id);
                    break;
            }
        }

        $enquiry = new PaginateResource($enquirySql->paginate($this->paginateNumber));
        return successResponse(trans('api.success'), $enquiry);
    }

    public function store(Request $request) {
        $enquiryTypes = array_column(EnquirySource::cases(), 'value');
        $rules = [
            'type' => ['required', 'in:' . implode(',', $enquiryTypes)],
            'enquiry_mode' => 'required_if:type,' . implode(',', array_diff($enquiryTypes, ['expo', 'democenter'])),
            'interested' => 'required_if:type,expo,democenter',
            'expo_id' => 'required_if:type,expo',
            'enquiry_date' => 'required|date_format:Y-m-d',
            "area_id" => 'required',
            "company_id" => 'required',
            "customer_id" => 'required',
            'products' => 'required|array',
            'products.*.brand_id' => 'present|required_unless:products.*.product_id,null',
            'products.*.product_id' => 'present|required_unless:products.*.brand_id,null',
            'products.*.status_id' => 'required',
            'products.*.priority' => 'required',
            'products.*.notes' => 'present|required_if:products.*.product_id,null|nullable',
            'products.*.manager_id' => 'present|nullable',
            'products.*.assign_type' => ['required', 'in:self,assist,share'],
            'products.*.assign_id' => 'present|required_if:products.*.assign_type,assist,share|nullable',
            'schedule_meeting' => 'required|boolean',
            'meeting_date' => 'required_if:schedule_meeting,1|date_format:Y-m-d',
            'meeting_time' => 'required_if:schedule_meeting,1|date_format:H:i',
            'timezone' => 'required_if:schedule_meeting,1|timezone',
            'location' => 'required_if:schedule_meeting,1'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $scheduled_notes = null;
        foreach ($request->products as $product) {
            $details = $product['notes'];
            $historyMessage = "New enquiry is created with status " . LeadStatus::find($product['status_id'])->name;
            if (!empty($product['brand_id']) && !empty($product['product_id'])) {
                $pdetails = Product::select(
                                \DB::raw("REPLACE(REPLACE(cm_products.title, '\\t', ''), '\\n', ' ') AS title"),
                                's.brand'
                        )
                        ->leftJoin('suppliers as s', 'products.brand_id', '=', 's.id')
                        ->where('products.id', '=', $product['product_id'])
                        ->first();
                //append to details and append shceduled notes
                $details .= ' | ' . $pdetails->title . ' | ' . $pdetails->brand;
                $scheduled_notes .= $details . ' | ';
            }
            //Save to leads with details
            $enquiry = new Lead();
            $enquiry->company_id = $request->company_id;
            $enquiry->customer_id = $request->customer_id;
            $enquiry->lead_type = $request->type;
            $enquiry->enquiry_date = $request->enquiry_date;
            $enquiry->interested = $request->interested;
            $enquiry->details = $details;

            if ($product['assign_type'] == "share") {
                $enquiry->assigned_to = \App\Models\Employee::where('user_id', $product['assign_id'])->first()->id;
            } else {
                $enquiry->assigned_to = $request->user()->employee->id;
            }

            $enquiry->assigned_on = date('Y-m-d');
            $enquiry->status_id = $product['status_id'];
            if (!empty($request->enquiry_mode)) {
                $enquiry->enquiry_mode = $request->enquiry_mode;
            }
            if (!empty($request->expo_id)) {
                $enquiry->expo_id = $request->expo_id;
            }
            $enquiry->created_by = $request->user()->id;
            $enquiry->save();
            //Save to lead histories with historyMessage
            LeadHistory::create(
                    [
                        'lead_id' => $enquiry->id,
                        'status_id' => $enquiry->status_id,
                        'comment' => $historyMessage,
                        'priority' => $product['priority'],
                        'userid' => $request->user()->id,
                    ]
            );
            //Save to lead prodcuts
            LeadProduct::create(
                    [
                        'lead_id' => $enquiry->id,
                        'supplier_id' => $product['brand_id'],
                        'product_id' => $product['product_id'],
                        'notes' => $product['notes'],
                        'area_id' => $request->area_id,
                        'manager_id' => $product['manager_id'],
                        'assistant_id' => ($product['assign_type'] == "assist") ? $product['assign_id'] : null,
                    ]
            );
            if ($product['assign_type'] == "share") {
                LeadShare::create(
                        [
                            'lead_id' => $enquiry->id,
                            'shared_by' => $request->user()->id,
                            'shared_to' => $product['assign_id'],
                        ]
                );
                //Send Notification to user
                $this->notifyEnquiryShared($enquiry, $product['assign_id'], 'share');
            } elseif ($product['assign_type'] == "assist") {
                $this->notifyEnquiryShared($enquiry, $product['assign_id'], 'assist');
            }
            //if manager_id is not null then send notification to manager 
            if (!empty($product['manager_id'])) {
                $this->notifyEnquiryShared($enquiry, $product['manager_id'], 'manager');
            }
        }

        //if meeting schedule save with scheduled notes
        /* if ($request->schedule_meeting) {
          $this->createMeeting($request, $scheduled_notes);
          } */
        return successResponse(trans('api.enquiry.created'));
    }

    public function createMeeting($request, $scheduled_notes) {
        $request->offsetUnset('area_id');
        $request['company_name'] = Company::select('company')
                        ->where('id', $request->company_id)
                        ->first()->company;
        $customer = Customer::where('id', $request->customer_id)->first();
        $request['company_representative'] = $customer->fullname;
        $request['phone'] = $customer->phone;
        $request['email'] = $customer->email;
        $request['title'] = "Meeting with " . $request->company_name;
        $request['scheduled_notes'] = $scheduled_notes;

        $meetingController = new \App\Http\Controllers\API\User\MeetingController();
        return $meetingController->store($request);
    }

    public function show(Request $request, $id) {
        $enquiry = Lead::select('id', 'company_id', 'customer_id', 'status_id', 'details', 'assigned_to', 'enquiry_date', 'lead_type', 'enquiry_mode', 'expo_id', 'interested')
                ->with(['company:id,country_id,region_id,company', 'company.country:id,name', 'company.region:id,state', 'customer:id,fullname,phone'])
                ->where('id', $id)
                ->first();

        if ($enquiry->assigned_to == $request->user()->employee->id) {
            $enquiry->editable = true;
        } else {
            $enquiry->editable = false;
        }

        if ($enquiry->lead_type == 'expo') {
            if (!empty($enquiry->expo_id)) {
                $enquiry->lead_type = $enquiry->expo->name;
                unset($enquiry->expo);
            }
        }

        if (!empty($enquiry->enquiry_mode)) {
            $enquiry->enquiry_mode = $enquiry->enquiry_mode_label;
        }

        $enquiry->status_id = ($enquiry->status_id != 0) ? LeadStatus::where('id', $enquiry->status_id)->first()->name : null;

        $enquiry->product = LeadProduct::selectRaw("REPLACE(REPLACE(cm_p.title, '\\t', ''), '\\n', ' ') AS title,cm_s.brand,cm_lead_products.notes,cm_lead_products.area_id,cm_lead_products.manager_id,cm_lead_products.assistant_id")
                ->leftJoin('suppliers as s', 'lead_products.supplier_id', '=', 's.id')
                ->leftJoin('products as p', 'lead_products.product_id', '=', 'p.id')
                ->where('lead_products.lead_id', '=', $enquiry->id)
                ->first();

        if (!empty($enquiry->product)) {
            $enquiry->area = Area::select('id', 'name')->where('id', $enquiry->product->area_id)->first();
            $enquiry->manager = User::select('users.id', 'users.name', 'employees.image_url as pimg')
                            ->join('employees', 'employees.user_id', 'users.id')
                            ->where('users.id', $enquiry->product->manager_id)->first();
            $enquiry->assistant = User::select('users.id', 'users.name', 'employees.image_url as pimg')
                            ->join('employees', 'employees.user_id', 'users.id')
                            ->where('users.id', $enquiry->product->assistant_id)->first();
        }

        //Get latest history;
        $lleadHistory = LeadHistory::where('lead_id', $enquiry->id)->orderBy('created_at', 'desc')->first();
        if ($lleadHistory) {
            $enquiry->priority = $lleadHistory->priority_label;
        } else {
            $enquiry->priority = 'low';
        }

        $requestedTimezone = $request->header('timezone', config('app.timezone'));

        $enquiry->calls->each(function ($call) use ($requestedTimezone) {
            $meetingTimeInUserTimezone = Carbon::parse($call->called_at, 'UTC')->setTimezone($requestedTimezone);
            $call->date = $meetingTimeInUserTimezone->format('Y-m-d');
            $call->time = $meetingTimeInUserTimezone->format('h:i A');
        });

        return successResponse(trans('api.success'), $enquiry);
    }

    public function edit(Request $request, $id) {
        $enquiry = Lead::select('id', 'company_id', 'customer_id', 'status_id', 'lead_type', 'enquiry_mode', 'expo_id', 'details', 'enquiry_date', 'interested')
                ->with(['company:id,company', 'customer:id,fullname,phone'])
                ->where('id', $id)
                ->first();

        if ($enquiry->lead_type == 'expo') {
            if (!empty($enquiry->expo_id)) {
                $enquiry->lead_type = $enquiry->expo->name;
                unset($enquiry->expo);
            }
        }
        if (!empty($enquiry->enquiry_mode)) {
            $enquiry->enquiry_mode = $enquiry->enquiry_mode_label;
        }

        $enquiry->product = LeadProduct::selectRaw("product_id, supplier_id as brand_id, REPLACE(REPLACE(cm_p.title, '\\t', ''), '\\n', ' ') AS title,cm_s.brand,cm_lead_products.notes,cm_lead_products.area_id,cm_lead_products.manager_id,cm_lead_products.assistant_id")
                ->leftJoin('suppliers as s', 'lead_products.supplier_id', '=', 's.id')
                ->leftJoin('products as p', 'lead_products.product_id', '=', 'p.id')
                ->where('lead_products.lead_id', '=', $enquiry->id)
                ->first();

        if ($enquiry->product) {
            $enquiry->area = Area::select('id', 'name')->where('id', $enquiry->product->area_id)->first();
            $enquiry->manager = User::select('users.id', 'users.name', 'employees.image_url as pimg', 'employees.division')
                            ->join('employees', 'employees.user_id', 'users.id')
                            ->where('users.id', $enquiry->product->manager_id)->first();
            $enquiry->assistant = User::select('users.id', 'users.name', 'employees.image_url as pimg')
                            ->join('employees', 'employees.user_id', 'users.id')
                            ->where('users.id', $enquiry->product->assistant_id)->first();
        } else {
            $enquiry->area = null;
            $enquiry->manager = null;
            $enquiry->assistant = null;
        }

        //Get latest history;
        $lleadHistory = LeadHistory::where('lead_id', $enquiry->id)->orderBy('created_at', 'desc')->first();
        if ($lleadHistory) {
            $enquiry->priority = $lleadHistory->priority;
        } else {
            $enquiry->priority = 'low';
        }
        //can share?
        $enquiry->can_share = (LeadShare::where('lead_id', $enquiry->id)->count() > 0) ? false : true;
        return successResponse(trans('api.success'), $enquiry);
    }

    public function update(Request $request, $id) {
        $enquiryPs = array_column(EnquiryPriority::cases(), 'value');
        $rules = [
            'status_id' => 'required',
            'priority' => ['required', 'in:' . implode(',', $enquiryPs)],
            'brand_id' => 'present|required_unless:product_id,null',
            'product_id' => 'present|required_unless:brand_id,null',
            'notes' => 'present|required_if:product_id,null|nullable',
            'assign_type' => ['required', 'in:self,assist,share'],
            'share_to' => 'required_if:assign_type,share',
            'area_id' => 'present',
            // 'manager_id' => 'present|required_unless:area_id,null',
            'manager_id' => 'present|nullable',
            'assistant_id' => 'required_if:assign_type,assist',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $enquiry = Lead::where('id', $id)->first();
        if ($enquiry) {
            $currentStatus = $enquiry->status_id;
            $enquiry->status_id = $request->status_id;
            $enquiry->details = $request->notes;

            if (!empty($request->brand_id) && !empty($request->product_id)) {
                $pdetails = Product::select(
                                \DB::raw("REPLACE(REPLACE(cm_products.title, '\\t', ''), '\\n', ' ') AS title"),
                                's.brand'
                        )
                        ->leftJoin('suppliers as s', 'products.brand_id', '=', 's.id')
                        ->where('products.id', '=', $request->product_id)
                        ->first();
                $enquiry->details .= ' | ' . $pdetails->title . ' | ' . $pdetails->brand;
            }

            $product = LeadProduct::where('lead_id', $id)->first();
            if (empty($product)) {
                $product = new LeadProduct();
                $product->lead_id = $id;
                $managerId = null;
                $assistantId = null;
            } else {
                $managerId = $product->manager_id;
                $assistantId = $product->assistant_id;
            }
            $product->product_id = $request->product_id;
            $product->supplier_id = $request->brand_id;
            $product->notes = $request->notes;
            $product->area_id = $request->area_id;
            $product->manager_id = $request->manager_id;
            $product->assistant_id = $request->assistant_id;
            $product->save();

            //set Comment
            $historyMessage = "Enquiry is updated with status " . LeadStatus::find($request->status_id)->name;
            LeadHistory::firstOrCreate(
                    ['lead_id' => $id, 'status_id' => $request->status_id, 'priority' => $request->priority],
                    ['comment' => $historyMessage, 'userid' => $request->user()->id]
            );

            if ($request->assign_type == 'share') {
                $enquiry->assigned_to = \App\Models\Employee::where('user_id', $request->share_to)->first()->id;

                LeadShare::create(
                        [
                            'lead_id' => $enquiry->id,
                            'shared_by' => $request->user()->id,
                            'shared_to' => $request->share_to,
                ]);
                $this->notifyEnquiryShared($enquiry, $request->share_to, 'share');
            }

            $enquiry->save();

            //if manager and assistant send notification
            if (!empty($request->manager_id) && ($managerId != $request->manager_id)) {
                $this->notifyEnquiryShared($enquiry, $request->manager_id, 'manager');
            }
            if (!empty($request->assistant_id) && ($assistantId != $request->assistant_id)) {
                $this->notifyEnquiryShared($enquiry, $request->assistant_id, 'assist');
            }
        }
        if ($currentStatus != $request->status_id && $request->assign_type != 'share') {
            $this->emailCoordinator($enquiry);
        }
        return successResponse(trans('api.success'));
    }

    public function emailCoordinator($enquiry) {
        $coordinator = $this->getCoordinator($enquiry->assigned_to);
        //if need more emails then can send as array of enails;
        if (!empty($coordinator)) {
            //$coordinator->email = "shainu.giraf@gmail.com";
            Mail::to($coordinator->email)->send(new LeadStatusToCoordinator($enquiry, $coordinator));
        }
        return 1;
    }

    public function callLogs(Request $request) {
        $rules = [
            'enquiry_id' => 'required',
            'call_status' => 'required|in:declined,no-answer,call-busy,connected,unavailable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        try {
            $enquiry = Lead::findOrFail($request->enquiry_id);
            $requestedTimezone = $request->header('timezone', config('app.timezone'));
            $currentTimeInUserTimezone = Carbon::now($requestedTimezone);
            $currentTimeInUTC = $currentTimeInUserTimezone->copy()
                    ->setTimezone('UTC')
                    ->toDateTimeString();
            $request['called_at'] = $currentTimeInUTC;
            $request['timezone'] = $requestedTimezone;
            $enquiry->calls()->create($request->all());
            return successResponse(trans('api.success'));
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }
}
