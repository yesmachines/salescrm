<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use App\Http\Requests\StoreEmployeeRequest;
use App\Services\UserService;
use App\Services\EmployeeService;
use App\Services\EmployeeManagerService;

class EmployeeController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EmployeeService $employeeService)
    {
        //
        $input = $request->all();

        //   $employees = Employee::orderBy('created_at', 'desc')->paginate(25);

        $employees = $employeeService->getAllEmployee($input);

        $managers = $employeeService->listEmployees();

        $roles = Role::where('id', '<>', '1')->pluck('name', 'name')->all();

        return view('employees.index', compact('employees', 'roles', 'managers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(EmployeeService $employeeService)
    {
        $roles = Role::where('id', '<>', '1')->pluck('name', 'name')->all();

        $managers = $employeeService->listEmployees();

        return view('employees.create', compact('roles', 'managers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserService $userService, EmployeeService $employeeService, EmployeeManagerService $empmanagerService)
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

        $user = $userService->createUser($data);
        $userService->assignUserRoles($data, $user);

        $avatar = $employeeService->uploadAvatar($request);
        $employee = $employeeService->createEmployee($data, $user, $avatar);

        $empmanagerService->addManagers($data,  $employee->id);

        return redirect()->back()->with('success', 'Employee created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, EmployeeService $employeeService, EmployeeManagerService $empmanagerService)
    {

        $employee = $employeeService->getEmployee($id);

        $target = $employeeService->getEmployeeTarget($id);

        $managers = $employeeService->listEmployees($employee->user_id);

        $selManagers = $empmanagerService->getManagers($id);


        $roles = Role::where('id', '<>', '1')->get(['id', 'name']);
        $userRoles = $employee->user->roles->pluck('id')->toArray();

        // $departments = DB::table('departments')->pluck('name', 'id')->toArray();

        return view('employees.edit',  compact('employee',  'roles', 'userRoles', 'managers', 'selManagers', 'target'));
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
        UserService $userService,
        EmployeeService $employeeService,
        EmployeeManagerService $empmanagerService
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
        $input = $request->all();

        // update User
        $userService->updateUser($input);

        $employee = $employeeService->getEmployee($id);

        $avatar = null;
        if (!empty($request->file('image_url'))) {
            ($employee->image_url) ? $employeeService->deleteImage($employee->image_url) : '';
            $avatar = $employeeService->uploadAvatar($request);
        }

        $employeeService->updateEmployee($employee, $input, $avatar);


        $empmanagerService->updateManager($input, $id);

        return redirect()->back()->with('success', 'Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, EmployeeService $employeeService)
    {
        $employee = $employeeService->getEmployee($id);

        $employeeService->deleteImage($employee->image_url);

        $employeeService->DeleteEmployee($employee);

        return redirect()->back()
            ->with('success', 'Employee deleted successfully');
    }
}
