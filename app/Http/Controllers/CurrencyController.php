<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\CurrencyService;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CurrencyService $currencyService)
    {
        //
        $input = $request->all();
        $currencies = $currencyService->getAllCurrency($input);

        return view('currency.index', compact('currencies'));
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
    public function store(Request $request, CurrencyService $currencyService)
    {
        $input = $request->all();

        $currencyService->createCurrency($input);

        return redirect()->route('currency.index')->with('success', 'Currency created successfully');
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
    public function edit($id, CurrencyService $currencyService)
    {

        $currency = $currencyService->getCurrency($id);

        $body = view('currency._edit')
            ->with(compact('currency'))
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
    public function update(Request $request, $id, CurrencyService $currencyService)
    {
        //
        $input = $request->all();

        $currency = $currencyService->getCurrency($id);
        $currencyService->updateCurrency($currency, $input);

        return redirect()->route('currency.index')->with('success', 'Currency updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, CurrencyService $currencyService)
    {
        //
        $currencyService->DeleteCurrency($id);

        return redirect()->route('currency.index')
            ->with('success', 'Currency deleted successfully');
    }
}
