@include('emails.includes.header')
<table style="width:100%; margin: 0 auto; height: 100%; ">
    <tbody>
        <tr>
            <td>
                <h4 style="text-align: left; padding-left: 30px;">  {{trans('email.reset_password.with_name',['user_name' => $mailData->name])}}, </h4>
            </td>
        </tr>
        <tr> 
            <td style="text-align: left; padding-left: 30px;padding-bottom: 4px; font-size: 13px; line-height: 18px;">{{trans('email.reset_password.content_1')}}</td>
        </tr>
    </tbody>
</table>

<table>
    <tr>
        <td
            style="text-align: left; padding-left: 30px; padding-bottom: 4px; font-size: 14px; padding-right: 30px; padding-top: 10px;">
            <h3
                style="background-color: #9dc33b; padding-top: 10px; padding-left: 15px; padding-bottom: 10px; color: #fff;">
                {{trans('email.reset_password.otp')}} :  {{$mailData->otp}}
            </h3>
        </td>
    </tr>

    <tr>
        <td style="text-align: left; padding-left: 30px;padding-bottom: 4px; font-size: 14px;">
            {{trans('email.reset_password.content_2')}}
        </td>
    </tr>
    <tr>
        <td style="text-align: left; padding-left: 30px;padding-bottom: 8px; font-size: 14px;">
            {{trans('email.reset_password.content_3')}}
        </td>
    </tr>
</table>
@include('emails.includes.footer')