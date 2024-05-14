<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\DivisionService;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, DivisionService $divisionService)
    {
        //
        $input = $request->all();
        $divisions = $divisionService->getAllDivision($input);

        return view('divisions.index', compact('divisions'));
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
    public function store(Request $request, DivisionService $divisionService)
    {
        $input = $request->all();

        $divisionService->createDivision($input);

        return redirect()->route('divisions.index')->with('success', 'Division created successfully');
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
    public function edit($id, DivisionService $divisionService)
    {

        $division = $divisionService->getDivision($id);

        $body = view('divisions._edit')
            ->with(compact('division'))
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
    public function update(Request $request, $id, DivisionService $divisionService)
    {
        //
        $input = $request->all();

        $division = $divisionService->getDivision($id);
        $divisionService->updateDivision($division, $input);

        return redirect()->route('divisions.index')->with('success', 'Division updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, DivisionService $divisionService)
    {
        //
        $divisionService->DeleteDivision($id);

        return redirect()->route('divisions.index')
            ->with('success', 'Division deleted successfully');
    }
}
