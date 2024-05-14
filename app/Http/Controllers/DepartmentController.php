<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\DepartmentService;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, DepartmentService $departmentService)
    {
        //
        $input = $request->all();
        $departments = $departmentService->getAllDepartment($input);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //  return view('country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DepartmentService $departmentService)
    {
        $input = $request->all();

        $departmentService->createDepartment($input);

        return redirect()->route('departments.index')->with('success', 'Department created successfully');
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
    public function edit($id, DepartmentService $departmentService)
    {

        $department = $departmentService->getDepartment($id);

        $body = view('departments._edit')
            ->with(compact('department'))
            ->render();


        return response()->json(array('success' => true, 'html' => $body));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, DepartmentService $departmentService)
    {
        //
        $input = $request->all();

        $department = $departmentService->getDepartment($id);
        $departmentService->updateDepartment($department, $input);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, DepartmentService $departmentService)
    {
        //
        $departmentService->DeleteDepartment($id);

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully');
    }
}
