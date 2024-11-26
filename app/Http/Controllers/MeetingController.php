<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Models\Meeting;
use App\Models\User;
use App\Models\MeetingShare;
use App\Models\MeetingProduct;

class MeetingController extends Controller {

    protected $this_start_month = null;
    protected $this_end_month = null;

    function __construct() {
        $this->middleware('can:meetings.index', ['only' => ['index', 'datatable']]);
        $this->middleware('can:meetings.show', ['only' => ['show']]);

        $this->this_start_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->this_end_month = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $input = [
            'start_date' => $this->this_start_month,
            'end_date' => $this->this_end_month
        ];
        $roleNames = ['salesmanager', 'satellite'];
        $users = User::whereHas('roles', function ($query) use ($roleNames) {
                    $query->whereIn('name', $roleNames);
                })->get();

        return view('meetings.index', compact('users', 'input'));
    }

    public function datatable(Request $request) {
        $requestedTimezone = $request->header('timezone', config('app.timezone'));
        $reqDatas = Meeting::select('*')->with('user');

        if (!empty($request->start_date)) {
            $fromDateDubai = Carbon::createFromFormat('Y-m-d', $request->start_date, $requestedTimezone);
            $fromDateUTC = $fromDateDubai->startOfDay()->setTimezone('UTC');
            $reqDatas->where('scheduled_at', '>=', $fromDateUTC);
        }
        if (!empty($request->end_date)) {
            $toDateDubai = Carbon::createFromFormat('Y-m-d', $request->end_date, $requestedTimezone);
            $toDateUTC = $toDateDubai->endOfDay()->setTimezone('UTC');
            $reqDatas->where('scheduled_at', '<=', $toDateUTC);
        }
        if (!empty($request->assigned_to)) {
            $reqDatas->where('user_id', $request->assigned_to);
        }
        if ($request->status != '') {
            switch ($request->status) {
                case 0:
                    $reqDatas->where('status', $request->status);
                    break;
                case 1:
                    $reqDatas->where('status', '>=', $request->status);
                    break;
                case 2:
                    $reqDatas->where('status', $request->status);
                    break;
            }
        }

        return Datatables::of($reqDatas)
                        ->rawColumns(['actions', 'status'])
                        ->editColumn('scheduled_at', function ($reqData) use ($requestedTimezone) {
                            $meetingTimeInUserTimezone = Carbon::parse($reqData->scheduled_at, 'UTC')->setTimezone($requestedTimezone);
                            return $meetingTimeInUserTimezone->format('M d, Y h:i a');
                        })
                        ->editColumn('status', function ($reqData) {
                            switch ($reqData->status) {
                                case 0:
                                    return '<span class="badge badge-soft-danger  my-1  me-2">Not Yet Started</span>';
                                    break;
                                case 1:
                                    return '<span class="badge badge-soft-success  my-1  me-2">Finished</span>';
                                    break;
                                case 2:
                                    return '<span class="badge badge-soft-warning  my-1  me-2">Shared</span>';
                                    break;
                            }
                        })
                        ->editColumn('actions', function ($reqData) {
                            $b = '<div class="d-flex align-items-center"><div class="d-flex">';
                            $b .= '<a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="Show" data-bs-original-title="Show" href="' . route('meetings.show', $reqData->id) . '"><span class="icon"><span class="feather-icon"><i data-feather="eye"></i></span></span></a>';
                            $b .= '</div></div>';
                            return $b;
                        })->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $requestedTimezone = $request->header('timezone', config('app.timezone'));
        $meeting = Meeting::findOrFail($id);
        $meeting->scheduled_at = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($requestedTimezone)->format('M d, Y h:i a');
        $meeting->products = MeetingProduct::select('meeting_products.id', 'suppliers.brand', 'products.title')
                ->join('suppliers', 'suppliers.id', 'meeting_products.supplier_id')
                ->join('products', 'products.id', 'meeting_products.product_id')
                ->where('meeting_products.meeting_id', $id)
                ->get();
        $shares = MeetingShare::with(['sharedBy', 'sharedTo'])->where('meeting_id', $id)->get();
        return view('meetings.show', compact('meeting', 'shares'));
    }
}
