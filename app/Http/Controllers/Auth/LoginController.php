<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Services\EmployeeService;
use App\Services\EmployeeManagerService;
use Illuminate\Support\Facades\Auth;
use \Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request, EmployeeService $employeeService)
    {

        $inputVal = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $remember_me  = (!empty($request->remember_me)) ? TRUE : FALSE;

        if (auth()->attempt(
            array('email' => $inputVal['email'], 'password' => $inputVal['password']),
            $remember_me
        )) {
            $user = Auth::user();
            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp()
            ]);
            // if it is not super admin, please check employee 
            if (!$user->hasRole('superadmin')) {
                $employee = $employeeService->getEmployeeByUserId(auth()->user()->id);

                if (!empty($employee)) {
                    $request->session()->put('employeeid', $employee->id);
                }
            }

            return redirect()->route('home');
        } else {
            return redirect()->route('login')
                ->with('error', 'Email & Password are incorrect.');
        }
    }
    public function testEmail() {
        $coordinator = new \stdClass();
        $coordinator->name = 'Shainu';
         return view('emails.lead_update_status_to_cord', compact('coordinator'));
        $meeting = \App\Models\Meeting::findOrFail('9d7087a2-b577-4488-9973-49c23529b7af');
        $mailData = \App\Models\MeetingShare::find('9d7a1c2f-89c6-40bd-88b1-390a71f35672');
         $meetingDate = \Carbon\Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($meeting->timezone);
        $mailData->date = $meetingDate->format('M d, Y g:i A'). ' - '.$meeting->timezone;
        $mailData->business_card = empty($mailData->business_card) ? null : asset('storage') . '/' . $mailData->business_card;
        
         $mailData->products = \App\Models\MeetingSharedProduct::select('meeting_shared_products.id', 'suppliers.brand', 'products.title')
                            ->join('suppliers', 'suppliers.id', 'meeting_shared_products.supplier_id')
                            ->join('products', 'products.id', 'meeting_shared_products.product_id')
                            ->where('meeting_shared_products.meetings_shared_id', $mailData->id)
                            ->get();
         $shareTo = \App\Models\User::findOrFail($mailData->shared_to);
         
         /*$shareTo->email =  'shainu.giraf@gmail.com';
         Notification::send($shareTo, new EmailMeetingNotes($mailData));*/
         
        return view('emails.meeting_notes', compact('mailData'));
        //return view('emails.password_email_otp', compact('mailData'));
    }
}
