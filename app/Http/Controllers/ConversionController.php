<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConversionService;
use App\Models\CurrencyConversion;

class ConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ConversionService $conversionService)
    {
        //
        $input = $request->all();
        $conversions = $conversionService->getAllConversion($input);

        return view('conversion.index', compact('conversions'));
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
    public function store(Request $request, ConversionService $conversionService)
    {
        $input = $request->all();

        $conversionService->createConversion($input);

        return redirect()->route('conversion.index')->with('success', 'Conversion created successfully');
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
    public function edit($id, ConversionService $conversionService)
    {

        $conversion = $conversionService->getConversion($id);

        $body = view('conversion._edit')
            ->with(compact('conversion'))
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
    public function update(Request $request, $id, ConversionService $conversionService)
    {
        //
        $input = $request->all();

        $conversion = $conversionService->getConversion($id);

        $conversionService->updateConversion($conversion, $input);

        return redirect()->route('currency-conversion.index')->with('success', 'Conversion updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ConversionService $conversionService)
    {
        //
        $conversionService->DeleteConversion($id);

        return redirect()->route('conversion.index')
            ->with('success', 'Conversion deleted successfully');
    }
    public function currencyConversionRate(Request $request)
    {

        $currency = $request->currencyCode;
        $conversionRates = CurrencyConversion::where('currency', $currency)
            ->where('status', 1)
            ->select('standard_rate')->first();
        return $conversionRates;
    }
}
