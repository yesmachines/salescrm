@include('emails.includes.header')
<table style="width:100%; margin: 0 auto; height: 100%; ">
    <tbody>
        <tr>
            <td>
                <h4 style="text-align: left; padding-left: 30px;">  Hello {{$coordinator->name}}, </h4>
            </td>
        </tr>
        <tr> 
            <td style="text-align: left; padding-left: 30px;padding-bottom: 4px; font-size: 13px; line-height: 18px;">
                {{auth('sanctum')->user()->name}} has updated an enquiry.For more details please click the following link.
            </td>
        </tr>
    </tbody>
</table>

<table>
    <tr>
        <td style="text-align: left; padding-left: 30px; padding-bottom: 4px; font-size: 14px; padding-right: 30px; padding-top: 10px;">
            <h4
                style="background-color: #9dc33b; padding-top: 10px; padding-left: 15px; padding-bottom: 10px; color: #fff; font-size: 13px;">
                For more details please click the following link <a href="{{env('APP_URL')}}/leads/{{$enquiry->id}}">{{env('APP_URL')}}/leads/{{$enquiry->id}}</a>
            </h4>

        </td>
    </tr>
</table>
@include('emails.includes.footer')