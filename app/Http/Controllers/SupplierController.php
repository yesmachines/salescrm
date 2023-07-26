<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\CountryService;
use App\Services\SupplierService;
use App\Services\EmployeeService;

class SupplierController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request,
        SupplierService $supplierService,
        CountryService $countryService,
        EmployeeService $employeeService
    ) {
        //
        $input = $request->all();
        $suppliers = $supplierService->getAllSupplier($input);

        $managers = $employeeService->listEmployees();

        $countries = $countryService->getAllCountry();

        return view('suppliers.index', compact('suppliers', 'countries', 'managers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //return view('suppliers.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SupplierService $supplierService)
    {
        // $this->validate($request, [
        //     'brand'          => 'required',
        //     'country_id'     => 'required',
        //     'division'       => 'required',
        //     'logo_url'       => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        // ]);
        $input = $request->all();

        $logoUrl = $supplierService->uploadLogo($request);
        $input['logo_path'] = $logoUrl;

        $supplierService->createSupplier($input);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully');
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
    public function edit($id, SupplierService $supplierService, CountryService $countryService, EmployeeService $employeeService)
    {
        //
        $managers = $employeeService->listEmployees();

        $countries = $countryService->getAllCountry();

        $supplier = $supplierService->getSupplier($id);

        $body = view('suppliers._edit')
            ->with(compact('supplier', 'countries', 'managers'))
            ->render();

        return response()->json(array('success' => true, 'html' => $body));


        // return view('suppliers.edit',  compact('countries',  'supplier'));
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
        SupplierService $supplierService
    ) {
        //
        // $this->validate($request, [
        //     'brand'          => 'required',
        //     'short_code'     => 'required|unique:suppliers,short_code,' . $id,
        //     'country_id'     => 'required',
        //     'division'       => 'required',
        //     'logo_url'       => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048'

        // ]);

        $input = $request->all();

        $supplier = $supplierService->getSupplier($id);

        $logo = null;
        if (!empty($input['logo_url'])) {
            ($supplier->logo_url) ? $supplierService->deleteImage($supplier->logo_url) : '';

            $logo = $supplierService->uploadLogo($request);
            $input['logo_path'] = $logo;
        }

        $supplierService->updateSupplier($supplier, $input);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, SupplierService $supplierService)
    {
        //
        $supplier = $supplierService->getSupplier($id);

        $supplierService->deleteImage($supplier->logo_url);

        $supplierService->deleteSupplier($id);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully');
    }
}
