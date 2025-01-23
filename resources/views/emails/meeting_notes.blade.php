@include('emails.includes.header')
<table style="width:100%; margin: 0 auto; height: 100%; ">
    <tbody>
        <tr>
            <td>
                <h4 style="text-align: left; padding-left: 30px;"> {{$mailData->title}} </h4>
            </td>
        </tr>
        <tr>
            <td>
                @if(!empty($mailData->business_card))
                <table style="margin: 0 auto; height: 100%; text-align: center;">
                    <tr cellpadding="0" cellspacing="0">
                        <td cellpadding="0" cellspacing="0" style="text-align: center;">
                            <img src="{{$mailData->business_card}}" alt="logo"
                                 style="width: 200px; height: auto; padding-bottom:10px; padding-top: 10px;">
                        </td>
                    </tr>
                </table>
                @endif
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 30px;"><strong>Shared By : </strong></td>
            <td> {{$mailData->sharedBy->name}} </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 30px; padding-bottom: 10px;"><strong>Meeting Held On
                    :</strong>
            </td>
            <td>{{$mailData->date}}</td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 30px; padding-bottom: 4px; font-size: 14px;">
                <strong>Company Name :</strong>
            </td>
            <td> {{$mailData->company_name}}</td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 30px;padding-bottom: 4px; font-size: 14px;">
                <strong>Company Representative :</strong>
            </td>
            <td> {{$mailData->company_representative}} </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 30px;padding-bottom: 4px; font-size: 14px;">
                <strong>Email</strong>
            </td>
            <td><a href="mailto:robingeorgegiraf@gmail.com" target="_blank">{{$mailData->email}}</a></td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 30px;padding-bottom: 4px; font-size: 14px;">
                <strong>Location :</strong>
            </td>
            <td> {{$mailData->location}}  </td>
        </tr>
    </tbody>
</table>

<table>
    @if(!$mailData->products->isEmpty())
    <tr>
        <td
            style="text-align: left; padding-left: 30px; padding-bottom: 4px; font-size: 14px; padding-right: 30px; padding-top: 20px;">
            <h3
                style="background-color: #9dc33b; padding-top: 10px; padding-left: 15px; padding-bottom: 10px; color: #fff;">
                Products
            </h3>
        </td>
    </tr>
    @foreach($mailData->products as $product)
    <tr>
        <td style="text-align: left; padding-left: 30px;padding-bottom: 4px; padding-top: 8px; font-size: 14px;">
            {{$product->title}}
        </td>
    </tr>
    <tr>
        <td style="text-align: left; padding-left: 30px;padding-bottom: 4px; font-size: 14px;">
            {{$product->brand}}
        </td>
    </tr>
    <tr style="border-bottom: 2px solid #fff; padding-bottom: 6px; display: block;"> </tr>
    @endforeach
    @else
    <tr style="border-bottom: 2px solid #fff; padding-bottom: 6px; display: block;"> </tr>
    @endif
    <tr>
        <td
            style="text-align: left; padding-left: 30px;padding-bottom: 6px; font-size: 14px; padding-top: 8px;">
            Meeting Notes:
        </td>
    </tr>
    <tr>
        <td style="text-align: left; padding-left: 30px;padding-bottom: 10px; font-size: 14px;">
            {{$mailData->meeting_notes}}
        </td>
    </tr>
    <tr style="border-bottom: 2px solid #fff; padding-bottom: 6px; display: block;"> </tr>
</table>
@include('emails.includes.footer')