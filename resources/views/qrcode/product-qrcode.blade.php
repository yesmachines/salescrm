<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Special Deal Form </title>

  <link rel="stylesheet" href="{{ asset('css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">


  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;600;700&display=swap" rel="stylesheet">

</head>

<body>


  <!-- ============================
  FORM AREA START
  ============================-->


  <!-- Your form content -->

  <section class="form-area">
    <div class="container">
      <!-- <div class="row">
      <div class="col-xl-10 col-lg-12 col-md-12 form-banner">
      <img src="{{ asset('./img/open-house-2024-banner.jpg') }}" alt="Open house 2024 banner" class="img-fluid">
    </div>
  </div> -->
  <div class="row">
    <div class="col-xl-10 col-lg-12 col-md-12 mx-auto">
      <div class="form-box">

        <div class="row form-content">
          <div class="col-xl-6 col-lg-10 col-md-12 p-0">
            <div class="form-text">
              @if(Session::has('submitted'))
              <div class="thank-you text-center text-success">
                <h2><i class="fas fa-check-circle"></i> Thank You!</h2>
                <h6 style="padding-bottom: 8px;font-size: 12px;padding-right: 15px;padding-left: 15px;">You have successfully requested a quote; to view the price, please check your inbox.</h6>
              </div>
              @endif

              <!-- <img src="./img/open-house-2024.jpg" alt="Open house 2024" class="img-fluid"> -->
              <h2>{{$product->title}}</h2>
              <img src="{{ asset('storage/' . $product->image_url)}}"  style="width:200px; max-width: 200px;padding:20px;">
              <h4> {{$product->modelno}}</h4>
               <h6> {{$product->supplier->brand}} {{ $product->supplier?->country?->name }}</h6>
              <p>
                <pre style="white-space: pre-wrap; font-family: inherit; font-size: 14px; font-weight: inherit; line-height: inherit; width: 100%; margin: 0;padding:1px;">{{$product->description}}</pre>

              </p>
            </div>
          </div>

          <div class="col-xl-6 col-lg-10 col-md-12 p-0">
            <div id="form-div">
              <form action="{{ route('qrcodeScanning') }}" method="post">
                @csrf

                <p class="name">
                  <input name="name" type="text"
                  class="validate[required,custom[onlyLetter],length[0,100]] feedback-input mandatory"
                  placeholder="Name *" id="name" required />
                </p>
                <input name="product_model" type="hidden" value="{{$product->id}}" />


                <p class="company">
                  <input name="company" type="text"
                  class="validate[required,custom[onlyLetter],length[0,100]] feedback-input"
                  placeholder="Company *" id="company" required/>
                </p>
                <p class="email">
                  <input name="email" type="email" class="validate[required,custom[email]] feedback-input"
                  id="email" placeholder="Email *" required />
                  <span class="error-message" id="email-error" style="color: red; font-size: 12px;"></span>
                </p>
                <p class="phone">
                  <input name="phone" type="text"
                  class="validate[required,custom[onlyNumbers]] feedback-input" id="phone"
                  placeholder="Phone *"  required/>
                </p>
                <p class="text">
                  <textarea name="remarks"
                  class="validate[required,length[6,300]] feedback-input" id="remarks"
                  placeholder="Remarks"></textarea>
                </p>
                <div class="submit">
                  <input type="submit" value="SUBMIT" id="button-blue" />
                  <div class="ease"></div>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
</section>

<!-- ============================
FORM AREA END
============================-->


<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('special-deal-form');
  const thankYouSection = document.getElementById('thank-you');

  form.addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    // Hide the form section
    form.style.display = 'none';

    // Show the thank you message section
    thankYouSection.style.display = 'block';
  });
});
</script>
<script>
// Function to validate email format
function validateEmail(email) {
  var regex = /^[^\s@]+@[^\s@]+$/;
  return regex.test(email);
}
var emailInput = document.getElementById('email');
var emailError = document.getElementById('email-error');
emailInput.addEventListener('input', function() {
  var email = emailInput.value;
  var isValid = validateEmail(email);
  if (!isValid) {
    emailError.textContent = 'Please enter a valid email address.';
  } else {
    emailError.textContent = '';
  }
});
</script>


</body>

</html>
