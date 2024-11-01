<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseRequisition;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use DB;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\PurchaseRequisitionService;

class PurchaseRequisitionController extends Controller
{
    public function index(PurchaseRequisitionService $purchaseRequest)
    {
        return view('purchaseRequisition.index');
    }
    public function createNewPR(
        $id,
        OrderService $orderService,
        PurchaseRequisitionService $purchaseRequest,
        ProductService $productService,
        CurrencyService $currencyService
    ) {

        $currencies = $currencyService->getAllCurrency();
        $order = $orderService->getOrder($id);
        foreach ($order->orderItem as $x => $item) {

            $purchase = $productService->buyingPriceByProduct($item->product_id);

            if (!empty($purchase) && isset($purchase->buying_price)) {

                $unit_price = $purchase->buying_price;
                $buying_currency = $purchase->buying_currency;

                $line_total = $unit_price * $item->quantity;
                $line_total = number_format($line_total, 2, '.', '');

                $order->orderItem[$x]->buying_price = $unit_price;
                $order->orderItem[$x]->buying_currency = $buying_currency;
                $order->orderItem[$x]->line_total = $line_total;
            }
        }

        foreach ($order->orderSupplier as $y => $sup) {
            $c_rate = 0;
            $currency_rate = DB::table('currency_conversion')->where('currency', $sup->price_basis)->first();
            if ($currency_rate) {
                $c_rate = $currency_rate->standard_rate;
            }

            $order->orderSupplier[$y]->currency_rate = $c_rate;
        }


        return view('purchaseRequisition.create', compact('order',  'currencies'));
    }


    public function store(
        Request $request,
        PurchaseRequisitionService $purchaseRequest
    ) {
        // $request->validate([
        //     'supplier.*.supplier_email'     => 'required',
        //     'supplier.*.supplier_contact'   => 'required',
        //     'item.*.unit_price'             => 'required|decimal:0,4',
        //      'item.*.currency'               => 'required',
        //      'item.*.product_id'             => 'required',
        // ]);

        $valid = [
            'supplier.*.supplier_email'     => 'required',
            'supplier.*.supplier_contact'   => 'required',
        ];
        $input = $request->all();
        //  dd($input);
        $selectedCount = 0;

        foreach ($input['item'] as $i => $item) {

            if (isset($item['product_id'])) {
                $selectedCount++;
                $valid['item.' . $i . '.unit_price'] = 'required|decimal:0,4';
            }
        }
        if ($selectedCount == 0) {
            $valid['product_id'] = 'required';
        }
        // validation
        $this->validate($request, $valid);


        foreach ($input['supplier'] as $supdata) {
            $total_price = 0;

            $insertPR = [
                'os_id'         => $input['os_id'],
                'pr_for'        => $input['pr_for'],
                'created_by'    => $input['created_by'],
                'pr_date'       => $input['pr_date'],
                'pr_type'       => $input['pr_type'],
                'company_id'    => $input['company_id'],
                'status'        => $input['status'],
                'supplier_id'   => $supdata['supplier_id'],
                'pr_number'     => $supdata['pr_number'],
                'currency'      => $supdata['currency'],
                'total_price'   => $total_price
            ];
            // Insert PR
            $pr = $purchaseRequest->insertPR($insertPR);

            $insertPRDelivery = [
                'pr_id'            => $pr->id,
                'country_id'       => $supdata['country_id'],
                'supplier_email'   => $supdata['supplier_email'],
                'supplier_contact' => $supdata['supplier_contact'],
                'delivery_term'    => $supdata['delivery_term'],
                'shipping_mode'    => $supdata['shipping_mode'],
                'availability'     => $supdata['availability'],
                'warranty'         => $supdata['warranty'],
                'remarks'          => $supdata['remarks'],
            ];

            // Insert Pr delivery
            $prDelivery = $purchaseRequest->insertPRSupplierDetails($insertPRDelivery);

            foreach ($input['item'] as $item) {
                if (
                    $item['supplierid'] == $supdata['supplier_id'] && isset($item['product_id'])
                    && $item['product_id'] != ''
                ) {
                    $total_price = $total_price + $item['total_amount'];

                    $insertPRItem = [
                        'pr_id'         => $pr->id,
                        'product_id'    => $item['product_id'],
                        'partno'        => $item['partno'],
                        'item_description' => $item['item_description'],
                        'unit_price'       => $item['unit_price'],
                        'quantity'         => $item['quantity'],
                        'total_amount'     => $item['total_amount'],
                        'currency'         => $item['currency'],
                        'yes_number'       => $item['yes_number'],
                    ];
                    // Insert PR Items
                    $prItem = $purchaseRequest->insertPRItems($insertPRItem);

                    // create buying price

                }
            }

            // PR Charges
            if (isset($input['charges']) && !empty($input['charges'])) {
                foreach ($input['charges'] as $charge) {
                    if (isset($charge['charge_id']) && $charge['charge_id'] != '') {
                        $insertPRCharge = [
                            'pr_id'        => $pr->id,
                            'title'        => $charge['title'],
                            'currency'     => $charge['currency'],
                            'considered'   => $charge['considered']
                        ];
                        $total_price = $total_price + $charge['considered'];
                        // insert PR Charges
                        $prCharge = $purchaseRequest->insertPRCharges($insertPRCharge);
                    }
                }
            }

            foreach ($input['payment'] as $payment) {
                $insertPRPayment = [
                    'pr_id'            => $pr->id,
                    'payment_term'     => $payment['payment_term'],
                    'remarks'          => $payment['remarks'],
                    'status'           => $payment['status']
                ];
                // insert PR Payment Term
                $prPayment = $purchaseRequest->insertPRPaymentTerms($insertPRPayment);
            }

            // Update Price - PR
            $updatePR = [
                'pr_id'        => $pr->id,
                'total_price'  => number_format($total_price, 2, '.')
            ];
            $updatepr = $purchaseRequest->updatePR($updatePR);
        }

        return redirect()->route('purchaserequisition.index')->with('success', 'PR created successfully');
    }

    public function edit(
        $id,
        PurchaseRequisitionService $purchaseRequest
    ) {
        $purchaseRequest = $purchaseRequest->getPurchaseRequisition($id);

        return view('purchaseRequisition.edit',  compact(
            'purchaseRequest'
        ));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, PurchaseRequisitionService $purchaseRequest)
    {
        //
        $valid = [
            'supplier.supplier_email'     => 'required',
            'supplier.supplier_contact'   => 'required',
        ];
        $input = $request->all();
        //  dd($input);
        $selectedCount = 0;

        foreach ($input['item'] as $i => $item) {

            if (isset($item['product_id'])) {
                $selectedCount++;
                $valid['item.' . $i . '.unit_price'] = 'required|decimal:0,4';
            }
        }
        if ($selectedCount == 0) {
            $valid['product_id'] = 'required';
        }
        // validation
        $this->validate($request, $valid);

        // $purchase = $purchaseRequest->getPurchaseRequisition($id);

        $total_price = 0;

        $supdata = $input["supplier"];
        $insertPRDelivery = [
            'supplier_email'   => $supdata['supplier_email'],
            'supplier_contact' => $supdata['supplier_contact'],
            'shipping_mode'    => $supdata['shipping_mode'],
            'availability'     => $supdata['availability'],
            'warranty'         => $supdata['warranty']
        ];
        // update Pr delivery
        $prDelivery = $purchaseRequest->updatePRSupplierDetails($insertPRDelivery, $id);

        $deleteItems = [];
        foreach ($input['item'] as $item) {
            if (isset($item['product_id']) && $item['product_id'] != '') {

                $total_price = $total_price + $item['total_amount'];

                $insertPRItem = [
                    'partno'           => $item['partno'],
                    'item_description' => $item['item_description'],
                    'quantity'         => $item['quantity'],
                    'total_amount'     => $item['total_amount'],
                    'yes_number'       => $item['yes_number'],
                ];
                // Update PR Items
                $prItem = $purchaseRequest->updatePRItems($insertPRItem, $item['item_id']);
            } else {
                $deleteItems[] =   $item['item_id'];
            }
        }
        // delete unchecked items
        if (!empty($deleteItems)) {
            $pritems = $purchaseRequest->deletePRItems($deleteItems);
        }

        // PR Charges
        if (isset($input['charges'])) {
            foreach ($input['charges'] as $charge) {
                if (isset($charge['charge_id']) && $charge['charge_id'] != '') {
                    $insertPRCharge = [
                        'title'        => $charge['title'],
                        'considered'   => $charge['considered']
                    ];
                    $total_price = $total_price + $charge['considered'];
                    // insert PR Charges
                    $prCharge = $purchaseRequest->updatePRCharges($insertPRCharge, $charge['charge_id']);
                }
            }
        }
        // PR Payment
        if (isset($input['payment'])) {
            foreach ($input['payment'] as $payment) {
                $insertPRPayment = [
                    'payment_term'     => $payment['payment_term'],
                    'remarks'          => $payment['remarks'],
                    'status'           => $payment['status']
                ];
                // insert PR Payment Term
                $prPayment = $purchaseRequest->updatePRPaymentTerms($insertPRPayment, $payment['payment_id']);
            }
        }
        // Update Price - PR
        $updatePR = [
            'pr_id'        => $id,
            'total_price'  => number_format($total_price, 2, '.')
        ];
        $updatepr = $purchaseRequest->updatePR($updatePR);


        return redirect()->route('purchaserequisition.index')->with('success', 'PR Updated successfully');
    }

    public function show(
        $id,
        PurchaseRequisitionService $purchaseRequest
    ) {
        $purchaseRequest = $purchaseRequest->getPurchaseRequisition($id);

        return view('purchaseRequisition.show',  compact(
            'purchaseRequest'
        ));
    }
    public function downloadPR(
        $id,
        PurchaseRequisitionService $purchaseRequest
    ) {
        $purchaseRequest = $purchaseRequest->getPurchaseRequisition($id);

        $body = view('purchaseRequisition.pr_summary')
            ->with(compact(
                'purchaseRequest'
            ))
            ->render();

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => public_path('uploads/temp'),
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'autoVietnamese' => true,
            'autoArabic' => true,
            // 'margin_top' => 8,
            // 'margin_bottom' => 8,
            'format' => 'A4',
            'setAutoBottomMargin' => 'stretch',
            'setAutoTopMargin' => 'stretch',
            // 'autoMarginPadding' => 2,
            'useOddEven' => 1
        ]);

        $mpdf->useSubstitutions = true;
        $mpdf->SetWatermarkText("YESMACHINERY", 0.1);
        $mpdf->showWatermarkText = true;
        $mpdf->SetTitle('PR-' . $purchaseRequest->pr_number . '.pdf');
        // $mpdf->WriteHTML($header);
        $mpdf->WriteHTML($body);
        $mpdf->Output('PR-' . $purchaseRequest->pr_number . '.pdf', 'I');
    }

    public function destroy($id, PurchaseRequisitionService $purchaseRequest)
    {

        $purchaseRequest->deletePR($id);

        return redirect()->back()
            ->with('success', 'PR deleted successfully');
    }
}
