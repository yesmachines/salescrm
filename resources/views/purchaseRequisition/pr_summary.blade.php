<style>
    th,
    td {
        border: 1px solid black;
        padding: 1px;
    }

    table {
        font-size: 12px;
        border-spacing: 0;
        border-collapse: collapse;
    }
</style>
<table width="50%" align="center">
    <tbody>
        <tr>
            <td align="center" height="30">
                <h2>PURCHASE REQUISITION</h2>
            </td>
        </tr>
    </tbody>
</table>

<table width="100%" align="center" style="margin-top:10px;">
    <tbody>
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td style="border: 0;background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                            Date</td>
                        <td style="border: 0; padding: 0 3px;">
                            {{date("d-m-Y", strtotime($purchaseRequest->pr_date))}}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td style="border: 0;background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                            Supplier</td>
                        <td style="border: 0; padding: 0 3px;">
                            {{$purchaseRequest->supplier->brand}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td style="border: 0;background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                            For Client</td>
                        <td style="border: 0; padding: 0 3px;">
                            @if($purchaseRequest->pr_type == 'client')
                            {{$purchaseRequest->company->company}}
                            @else
                            {{$purchaseRequest->pr_type}}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td style="border: 0;background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                            For Currency</td>
                        <td style="border: 0; padding: 0 3px; text-transform:uppercase;">
                            {{$purchaseRequest->currency}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td style="border: 0;background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                            OS No.</td>
                        <td style="border: 0; padding: 0 3px;">
                            @php $os_num = explode("_", $purchaseRequest->pr_number) @endphp
                            {{$os_num[0]}}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td style="border: 0;background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                            PR No.</td>
                        <td style="border: 0; padding: 0 3px; text-transform:uppercase;">
                            {{$purchaseRequest->pr_number}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td style="border: 0;background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                            Supplier Contact</td>
                        <td style="border: 0; padding: 0 3px;">
                            {{$purchaseRequest->purchaseDelivery->supplier_contact}}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td style="border: 0;background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                            Supplier Email</td>
                        <td style="border: 0; padding: 0 3px; text-transform:uppercase;">
                            {{$purchaseRequest->purchaseDelivery->supplier_email}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<table width="100%" align="center" style="margin-top:10px;">
    <thead>
        <tr>
            <th style="background: #ccc; height:30px;" width="5%">#</th>
            <th style="background: #ccc; height:30px;" width="15%">PartNo.</th>
            <th style="background: #ccc; height:30px;" width="40%">Product</th>
            <th style="background: #ccc; height:30px;" width="15%">Unit Price</th>
            <th style="background: #ccc; height:30px;" width="10%">Qty</th>
            <th style="background: #ccc; height:30px;" width="10%">Dis(%)</th>
            <th style="background: #ccc; height:30px;" width="15%">Final Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseRequest->purchaseItem as $item)
        <tr>
            <td align="center">
                {{$loop->iteration}}
            </td>
            <td align="center">{{$item->partno}} </td>
            <td style="padding: 2px;">

                {!! nl2br(e($item->item_description)) !!}
                <p>{{$item->yes_number}}</p>
            </td>
            <td align="center">{{$item->unit_price}}</td>
            <td align="center">{{$item->quantity}}</td>
            <td align="center">{{$item->discount}}</td>
            <td align="center">{{$item->total_amount}} </td>
        </tr>
        @endforeach

        @foreach($purchaseRequest->purchaseCharge as $charge)
        <tr>
            <td colspan="5" align="right" height="25">{{$charge->title }} </td>

            <td align="center">
                {{$charge->considered}}
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="6" align="right" style="background: #ccc; height:30px; text-transform: uppercase; padding: 4px; ">
                <b>Total Amount IN {{$purchaseRequest->currency}}</b>
            </td>
            <td align="center">
                {{$purchaseRequest->total_price}}
            </td>
        </tr>
    </tbody>
</table>

<table width="100%" align="center" style="margin-top:10px;">
    <tbody>
        <tr>
            <td valign="middle" style="background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                Payment Term
            </td>
            <td style="padding: 2px 4px;">
                @foreach($purchaseRequest->purchasePaymentTerm as $payment)
                <p> {{$payment->payment_term}}</p>
                <p> {{$payment->remarks}}</p>
                @endforeach
            </td>
        </tr>
        <tr>
            <td valign="middle" style="background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                Delivery Term
            </td>
            <td style="padding: 2px 4px;">
                {{$purchaseRequest->purchaseDelivery->delivery_term}}
            </td>
        </tr>
        <tr>
            <td valign="middle" style="background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                Shipment Mode
            </td>
            <td style="padding: 2px 4px;">
                {{$purchaseRequest->purchaseDelivery->shipping_mode}}
            </td>
            </td>
        </tr>
        <tr>
            <td valign="middle" style="background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                Availability
            </td>
            <td style="padding: 2px 4px;">
                {{$purchaseRequest->purchaseDelivery->availability}}
            </td>
        </tr>
        <tr>
            <td valign="middle" style="background-color: #ccc; height:25px; font-size: 14px; font-weight: bold; padding: 0 2px;" width="30%">
                Warranty
            </td>
            <td style="padding: 2px 4px;">
                {{$purchaseRequest->purchaseDelivery->warranty}}
            </td>
        </tr>
</table>


<table width="100%" align="center" style="margin-top:10px;">
    <thead>
        <tr>
            <th style="background: #ccc; height:30px;" width="20%">REQUESTED BY</th>
            <th style="background: #ccc; height:30px;" width="20%">OPERATION MANAGER</th>
            <th style="background: #ccc; height:30px;" width="20%">FINANCE MANAGER</th>
            <th style="background: #ccc; height:30px;" width="20%">DIRECTOR</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td align="center" height="30">
                {{$purchaseRequest->assigned->user->name}}
            </td>

            <td align="center"></td>

            <td align="center"></td>

            <td align="center"></td>

        </tr>

    </tbody>
</table>