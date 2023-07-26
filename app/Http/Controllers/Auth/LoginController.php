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
            if (!$user->hasRole('admin')) {
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
}
