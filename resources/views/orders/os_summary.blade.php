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
<table>
    <tbody>
        <tr>
            <td align="center" colspan="2">
                <h2>ORDER SUMMARY</h2>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td style="border:0; border-right: 1px solid black;" width="25%">OS No.</td>
                        <td style="border:0; border-right: 1px solid black;">
                            <b>{{$orderDetails->os_number}}</b>
                        </td>
                        <td style="border:0; border-right: 1px solid black;" width="25%">OS Date</td>
                        <td style="border:0;">{{$orderDetails->os_date}}</td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td style="border:0; border-right: 1px solid black;" width="25%">PO No.</td>
                        <td style="border:0; border-right: 1px solid black;">
                            <b>{{$orderDetails->po_number}}</b>
                        </td>
                        <td style="border:0; border-right: 1px solid black;" width="25%">PO Date</td>
                        <td style="border:0;">{{$orderDetails->po_date}}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">CLIENT/BUYER NAME & COUNTRY</td>
                        <td style="border:0;">
                            {{$orderDetails->company->company}}<br />
                            {{$orderDetails->company->country->name}}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table style="border:0;">
                    <tr>
                        <td style="border:0; border-right: 1px solid black;" width="25%">DELIVERY TERM</td>
                        <td style="border:0; border-right: 1px solid black;">
                            {{$orderDetails->orderClient->delivery_term}}
                        </td>
                        <td style="border:0; border-right: 1px solid black;" width="25%">PO Received Date</td>
                        <td style="border:0;">{{$orderDetails->po_received}}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">ITEM SHORT, DESCRIPTION, BRAND, MODEL, MAKE</td>
                        <td style="border:0; ">
                            @foreach($orderDetails->orderItem as $item)
                            <p>{{$item->item_name}}<br />
                                {{$item->quantity}} (qty), {{$item->partno? $item->partno. ' (PartNo.)' : ''}},
                                {{($item->product)? $item->product->modelno.' (ModelNo.)': ''}}, {{$item->yes_number}}
                            </p>
                            @endforeach
                            @if($optionalItems->isNotEmpty())
                            @foreach($optionalItems as $value)
                            <p>{{$value->item_name}}({{$value->quantity}}qty)<br />
                            </p>
                            @endforeach
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">

            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                  <?php

                  $optionalItemsSum = $optionalItems->sum('amount');
                  $totalAmount = $orderDetails->selling_price + $optionalItemsSum;
                  ?>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">SELLING PRICE (AED)</td>
                        <td style="border:0;">
                          @if($optionalItems->isNotEmpty())
                          {{$totalAmount }} AED
                          @else
                          {{ $orderDetails->selling_price}} AED
                          @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">PROMISED DELIVERY</td>
                        <td style="border:0;">
                            {{ $orderDetails->orderClient->promised_delivery}}<br />
                            {{ $orderDetails->orderClient->targeted_delivery}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">PRICE BASIS/TERMS</td>
                        <td style="border:0; ">
                            {{ str_replace("_", " ", $orderDetails->orderClient->price_basis) }}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">

            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">INSTALLATION & TRAINING</td>
                        <td style="border:0; ">
                            {{ $orderDetails->orderClient->installation_training }}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">

            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">SERVICE EXPERTS</td>
                        <td style="border:0; ">
                            {{ $orderDetails->orderClient->service_expert }}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">

            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">ESTIMATED INSTALLATION</td>
                        <td style="border:0; ">
                            {{ $orderDetails->orderClient->estimated_installation }}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">

            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">PAYMENT TERMS</td>
                        <td style="border:0; border-right: 1px solid black;" width="10%" align="center">#</td>
                        <td style="border:0;" align="center">
                            DESCRIPTION
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="10%" style="border:0; border-right: 1px solid black;" align="center">YES</td>
                        <td style="border:0; border-right: 1px solid black;" width="10%" align="center">
                            NO
                        </td>
                        <td style="border:0; border-right: 1px solid black; " width="30%" align="center">
                            EXPECTED DATE
                        </td>
                        <td style="border:0; " align="center">
                            REMARKS
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @php $c=0; @endphp
        @foreach($orderDetails->orderPayment as $cpayment)
        @if($cpayment->section_type == 'client')
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">
                            <p>PAYMENT TERMS</p>
                        </td>
                        <td style="border:0; border-right: 1px solid black;" width="10%" align="center">P{{++$c}}</td>
                        <td style="border:0;" align="center">
                            {{$cpayment->payment_term}}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="10%" style="border:0; border-right: 1px solid black;" align="center"> @if($cpayment->status ==1) &#10004; @else &#160; @endif</td>
                        <td style="border:0; border-right: 1px solid black;" width="10%" align="center">
                            @if($cpayment->status ==0) &#10004; @else &#160; @endif
                        </td>
                        <td style="border:0; border-right: 1px solid black; " align="center" width="30%">
                            {{$cpayment->expected_date}}
                        </td>
                        <td style="border:0; " align="center">
                            {{$cpayment->remarks}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif
        @endforeach
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">SUPPLIER NAME & COUNTRY</td>
                        <td style="border:0;" align="center">
                            @foreach($orderDetails->orderSupplier as $sup)
                            <p> {{$sup->supplier->brand}},
                                {{$sup->supplier->country->name}}
                            </p>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table style="border:0;">
                    <tr>
                        <td style="border:0; border-right: 1px solid black;" width="30%">DELIVERY TERM</td>
                        <td style="border:0;" align="center">
                            @foreach($orderDetails->orderSupplier as $sup)
                            <p> {{$sup->delivery_term}}</p>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">BUYING PRICE (AED)</td>
                        <td style="border:0;">
                            {{$orderDetails->buying_price}} AED
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">

            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">PRICE BASIS/TERM</td>
                        <td style="border:0;" align="center">
                            @foreach($orderDetails->orderSupplier as $sup)
                            <p> {{str_replace("_", "", $sup->price_basis)}}</p>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">

            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">PAYMENT TERMS</td>
                        <td style="border:0; border-right: 1px solid black;" width="10%" align="center">#</td>
                        <td style="border:0;" align="center">
                            DESCRIPTION
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="10%" style="border:0; border-right: 1px solid black;" align="center">YES</td>
                        <td style="border:0; border-right: 1px solid black;" width="10%" align="center">
                            NO
                        </td>
                        <td style="border:0; border-right: 1px solid black; " align="center" width="30%">
                            EXPECTED DATE
                        </td>
                        <td style="border:0; " align="center">
                            REMARKS
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @php $c=0; @endphp
        @foreach($orderDetails->orderPayment as $cpayment)
        @if($cpayment->section_type == 'supplier')
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">
                            <p>PAYMENT TERMS</p>
                        </td>
                        <td style="border:0; border-right: 1px solid black;" width="10%" align="center">P{{++$c}}</td>
                        <td style="border:0;" align="center">
                            {{$cpayment->payment_term}}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="10%" style="border:0; border-right: 1px solid black;" align="center"> @if($cpayment->status ==1) &#10004;@endif</td>
                        <td style="border:0; border-right: 1px solid black;" width="10%" align="center">
                            @if($cpayment->status ==0) &#10004;@endif
                        </td>
                        <td style="border:0; border-right: 1px solid black; " align="center" width="30%">
                            {{$cpayment->expected_date}}
                        </td>
                        <td style="border:0; " align="center">
                            {{$cpayment->remarks}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif
        @endforeach
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">OTHER CHARGES</td>
                        <td style="border:0; border-right: 1px solid black;">
                            CONSIDERED
                        </td>
                        <td style="border:0;">
                            ACTUAL
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%" align="center">
                REMARKS
            </td>
        </tr>
        @foreach($orderDetails->orderCharge as $charge)
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">{{strtoupper($charge->title)}} CHARGES</td>
                        <td style="border:0; border-right: 1px solid black;">{{$charge->considered}} AED</td>
                        <td style="border:0;" align="right"></td>
                    </tr>
                </table>
            </td>
            <td width="50%" align="center" align="center">
                {{$charge->remarks}}
            </td>
        </tr>
        @endforeach
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">MARGIN ON THE ORDER</td>
                        <td style="border:0; border-right: 1px solid black;">{{$orderDetails->projected_margin}} AED</td>
                        <td style="border:0;" align="right"></td>
                    </tr>
                </table>
            </td>
            <td width="50%" align="center">

            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">DELIVERY CONTACT NAME</td>
                        <td style="border:0; ">
                            {{$orderDetails->orderClient->contact_person}}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">DELIVERY EMAIL</td>
                        <td style="border:0; ">
                            {{$orderDetails->orderClient->contact_email}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">DELIVERY ADDRESS</td>
                        <td style="border:0; ">
                            {{$orderDetails->orderClient->delivery_address}}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">DELIVERY PHONE</td>
                        <td style="border:0; ">
                            {{$orderDetails->orderClient->contact_phone}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">DELIVERY REMARKS</td>
                        <td style="border:0; ">
                            {{$orderDetails->orderClient->remarks}}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">

            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">ACCOUNT NAME</td>
                        <td style="border:0; ">

                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">ACCOUNT EMAIL</td>
                        <td style="border:0; border-right: 1px solid black; ">

                        </td>
                        <td width="30%" style="border:0; border-right: 1px solid black;">MOB</td>
                        <td style="border:0; ">

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">PROCUREMENT NAME</td>
                        <td style="border:0; ">

                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">PROCUREMENT EMAIL</td>
                        <td style="border:0; border-right: 1px solid black; ">

                        </td>
                        <td width="30%" style="border:0; border-right: 1px solid black;">MOB</td>
                        <td style="border:0; ">

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">OS MEETING BY</td>
                        <td style="border:0;" align="center"></td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td width="30%" style="border:0; border-right: 1px solid black;">DEMO DONE BY</td>
                        <td style="border:0;" align="center"> {{($orderDetails->orderClient->demoby)? $orderDetails->orderClient->demoby->name: ''}}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" width="100%">
                <table width="100%">
                    <tr>
                        <td width="33%" style="border:0; border-right: 1px solid black;" align="center">
                            HANDED OVER BY
                        </td>
                        <td width="33%" style="border:0; border-right: 1px solid black;" align="center">
                            TAKEN OVER BY
                        </td>
                        <td width="33%" style="border:0;" align="center">
                            VERIFIED BY
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" width="100%">
                <table width="100%">
                    <tr>
                        <td width="33%" style="border:0; border-right: 1px solid black;">
                            NAME: {{$orderDetails->assigned->user->name}}
                        </td>
                        <td width="33%" style="border:0; border-right: 1px solid black;">
                            NAME
                        </td>
                        <td width="33%" style="border:0;">
                            NAME
                        </td>
                    </tr>
                    <tr>
                        <td width="33%" style="border:0; border-right: 1px solid black;">
                            SIGN
                        </td>
                        <td width="33%" style="border:0; border-right: 1px solid black;">
                            SIGN
                        </td>
                        <td width="33%" style="border:0;">
                            SIGN
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @if(isset($salesCommission))
        <tr>
            <td align="left" colspan="2">
                <h6 style="padding:2px;">SALES COMMISIONS</h6>
            </td>
        </tr>
        <tr>
            <td colspan="2" width="100%">
                <table width="100%">
              @foreach($salesCommission as $sale)
                    <tr>
                        <td width="50%" style="border:0; border-right: 1px solid black;">
                            NAME: {{$sale->manager->user->name}}
                        </td>
                        <td width="105.6%" style="border:0; border-right: 0px solid black;">
                        {{$sale->percent}}%
                        </td>

                    </tr>
                @endforeach

                </table>
            </td>
        </tr>
        @endif
    </tbody>
</table>
