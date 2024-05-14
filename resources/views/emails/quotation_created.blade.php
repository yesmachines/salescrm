<!DOCTYPE html>
<html>
<head>
  <title>{{__('custom_made_solutions')}} </title>
  <style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  body
  {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    color: #373737;

  }
</style>
</head>
<body>
  <table border="0" height="100%" cellpadding="0" cellspacing="0" >
    <tbody>
      <tr>
        <td align="center">
          <table border="0" cellpadding="0" cellspacing="0" style="width:640px;max-width:640px;padding-right:40px;padding-left:40px; background-color: #ebebeb !important; padding-top:40px;padding-bottom:40px;margin: 20px 0; border-radius: 8px;">
            <tbody style="background: white">
              <tr>
                <td>

                  <table class="table" style="width:100%!important; margin:0 auto; padding-bottom: 15px; border-radius: 1px 1px 0 0; margin-bottom: 20px;padding:10px;">
                    <tbody>
                      <tr style="width:100% !important; border-spacing: 0;">
                        <td>
                          <img class="logo" src="{{ asset('quotes/img/logo.png')}}" alt="Logo" style="max-width:90px; padding-left: 10px;">
                        </td>
                        <td style="text-align:right;padding: 10px 10px 10px 0;font-size:14px;">
                          <a style="margin:0 0 5px;" href="mailto:{{$data['email']}}" target="_blank">
                            sales@yesmachinery.ae </a>
                            <p style="margin: 0 0 5px;">
                              +971 65 264 382
                            </p>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    <table class="table" style="width:600px!important;margin:0 auto;padding:10px;">
                      <tbody>
                        <tr>
                          <td>
                            <div>&nbsp; &nbsp;</div>
                            <table border="0" cellspacing="0" cellpadding="0" style="width: 420px;">
                              <tbody>
                                <tr>
                                  <td>
                                    <table style="width:100%">

                                      <tbody style="line-height: 20px;">
                                        <tr>
                                          <td><strong>{{__('Company')}}</strong></td>
                                          <td style="font-size: 14px;"> <strong>:</strong> {{$data['company'] }} </td>
                                        </tr>
                                        <tr>
                                          <td><strong>{{__('Name')}}</strong></td>
                                          <td> <strong>:</strong> {{ $data['name'] }} </td>
                                        </tr>
                                        <tr>
                                          <td><strong>{{__('Phone')}}<strong></strong></strong></td>
                                          <td style="font-size: 15px;"> <strong>:</strong> <strong> {{$data['phone']}}</strong> </td>
                                        </tr>
                                        <tr>
                                          <td><strong>{{__('Email')}}</strong></td>
                                          <td><a href="mailto:{{$data['email']}}" target="_blank"
                                            style="font-size: 14px;"> <strong>:</strong>
                                            {{$data['email']}} </a></td>
                                          </tr>
                                        </tr>
                                      </tbody>
                                    </table><br>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <div>&nbsp;</div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="table table-condensed" style="width: 600px; margin-bottom: 5px; border-collapse: collapse; border: 1px solid #ccc; margin-left: 20px; margin-right: 20px;">
                      <thead style="background: #ccc; font-size: 14px;">
                        <tr>
                          <td style="padding: 10px; font-size: 15px; border: 1px solid #ccc;"><strong>{{__('Product')}}</strong></td>
                          <td style="padding: 10px; font-size: 15px; text-align: center; border: 1px solid #ccc;" class="text-center">
                            <strong>{{__('Model')}}</strong>
                          </td>
                          <td style="padding: 10px; font-size: 15px; text-align: center; border: 1px solid #ccc;" class="text-center">
                            <strong>{{__('Brand')}}</strong>
                          </td>
                          <td style="padding: 10px; font-size: 15px; text-align: center; border: 1px solid #ccc;" class="text-center">
                            <strong>{{__('Country')}}</strong>
                          </td>
                          <td style="padding: 10px; font-size: 15px; text-align: center; border: 1px solid #ccc;" class="text-center">
                            <strong>{{__('Price')}}</strong>
                          </td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr style="line-height: 26px; font-size: 15px;">
                          <td style="padding: 10px; font-size: 14px; border: 1px solid #ccc;">{{ $product['title'] }}<br> <img src="{{ asset('storage/' . $product->image_url)}}"  style="width: 80px; max-width: 80px;"> </td>
                          <td style="padding: 10px; font-size: 14px; border: 1px solid #ccc;">{{ $product['modelno'] }}  </td>
                          <td style="padding: 10px; font-size: 14px; border: 1px solid #ccc;" > {{$product->supplier->brand }}
                          </td>
                          <td style="padding: 10px; font-size: 14px;  border: 1px solid #ccc;" > {{$product->supplier?->country?->name}}
                          </td>
                          <td style="padding: 10px; font-size: 14px; border: 1px solid #ccc;"> {{ number_format($product['selling_price'],2) }}AED
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    <!-- connect with us start -->

                    <div class="row" style="padding:20px;">
                      <div class="col-md-12">
                        <table style="width: 100%; border: 1px solid #ccc; border-collapse: collapse;">
                          <thead>
                            <tr>
                              <th colspan="2" style="padding: 8px; background-color: #f2f2f2; text-align: left;">Terms And Conditions</th>
                            </tr>
                          </thead>
                          <tbody>

                            <tr>
                              <td style="padding: 8px; border: 1px solid #ccc;width: 150px;">Price Basis</td>
                              <td style="padding: 8px; border: 1px solid #ccc;">Quoted in</td>
                            </tr>
                            <tr>
                              <td style="padding: 8px; border: 1px solid #ccc;">Payment Term</td>
                              <td style="padding: 8px; border: 1px solid #ccc;">100% advance along with Purchase order.</td>
                            </tr>
                            <tr>
                              <td style="padding: 8px; border: 1px solid #ccc;">Country of Origin</td>
                              <td style="padding: 8px; border: 1px solid #ccc;">{{$product->supplier?->country?->name}}</td>
                            </tr>
                            <tr>
                              <td style="padding: 8px; border: 1px solid #ccc;">Delivery</td>
                              <td style="padding: 8px; border: 1px solid #ccc;">15 to 20 working weeks from date of receipt of official order/advanced payment/receipt letter of credit. + sea freight time frame</td>
                            </tr>
                            <tr>
                              <td style="padding: 8px; border: 1px solid #ccc;">Installing and Training</td>
                              <td style="padding: 8px; border: 1px solid #ccc;">Installing by YES MACHINERY Engineers (Charges to be defined)</td>
                            </tr>
                            <tr>
                              <td style="padding: 8px; border: 1px solid #ccc;">Import Code</td>
                              <td style="padding: 8px; border: 1px solid #ccc;">Sharjah import code is mandatory in case of generating a code an additional service fee of 150 AED is applicable (subject to receipt of required supporting documents)</td>
                            </tr>
                            <tr>
                              <td style="padding: 8px; border: 1px solid #ccc;">Warranty Period</td>
                              <td style="padding: 8px; border: 1px solid #ccc;">Warranty shall cover component replacement under normal operation for a         period of 24 months from date of delivery. However, warranty is not valid in the damage/breakdown caused due to the operator negligence.</td>
                            </tr>

                            <tr>
                              <td style="padding: 8px; border: 1px solid #ccc;">Offer Validity</td>
                              <td style="padding: 8px; border: 1px solid #ccc;">30 days</td>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>


                    <!--// connect with us end -->
                    <!-- footer logo start -->
                    <table  style="width: 100%; border-spacing: 0; background: #fff; padding-bottom: 20px;">
                      <tr>
                        <td style="width:100%; text-align: center;">
                          <img src="{{ asset('quotes/img/logo.png')}}" alt="logo" style="width: 90px; max-width: 90px; margin-top: 30px;">
                        </td>
                      </tr>
                    </table>
                    <!--// footer logo end -->


                    <!-- footer address start -->
                    <table  style="width: 100%; border-spacing: 0; background: #fff;">
                      <tr>
                        <td style="width:100%; text-align: center;">

                          <a>  YORK ENGINEERING SOLUTIONS FZC,  <br></a>
                          <a>  --  </a>
                          <a>   Office No.LV-27D, PO BOX 42167,  </a>
                          <p>  Hamriyah Free Zone Phase 2, Sharjah, UAE  </p>
                        </td>
                      </tr>
                    </table>


                    <table  style="width: 100%; border-spacing: 0; padding: 15px 0; background: #fff; padding-bottom: 25px;">
                      <tr>
                        <td style="text-align: center; width: 100%;">
                          <p>  Fax: +971 65 264 384 </p>
                        </td>
                      </tr>
                    </table>
                    <!-- connect with us start -->
                    <table style="width: 100%; border-spacing: 0;">
                      <tr>
                        <td style="width: 100%;   padding: 15px; text-align: center; color: #fff;">

                          <img src="{{ asset('frontend/img/fb.png')}}" style="width: 25px;" alt="">
                          <img src="{{ asset('frontend/img/youtube.png')}}" style="width: 25px;" alt="">
                        </td>
                      </tr>
                    </table>
                    <!--// connect with us end -->
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
  </html>
