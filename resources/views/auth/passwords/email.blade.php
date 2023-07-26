@extends('layouts.auth')

@section('content')
<!-- Container -->
<div class="container-xxl">
    <!-- Row -->
    <div class="row">
        <div class="col-sm-10 position-relative mx-auto">
            <div class="auth-content py-8">

                <form class="w-100" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="row">
                        <div class="col-lg-5 col-md-7 col-sm-10 mx-auto">
                            <div class="text-center mb-7">
                                <a class="navbar-brand me-0" href="/">
                                    <img class="brand-img d-inline-block" src="{{asset('dist/img/logo.png')}}" alt="brand">
                                </a>
                            </div>
                            <div class="card card-flush">
                                <div class="card-body text-center">
                                    @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                    @endif
                                    <h4>Reset your Password</h4>
                                    <p class="mb-4">No worries we will mail you a link to your recovery email address to reset your password</p>
                                    <div class="row gx-3">
                                        <div class="form-group col-lg-12">
                                            <div class="form-label-group">
                                                <label for="email">Email Address</label>
                                            </div>
                                            <input id="email" placeholder="Recovery email ID" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-uppercase btn-block">
                                        {{ __('Send Password Reset Link') }}
                                    </button>

                                    <p class="p-xs mt-2 text-center">Already have account? <a href="{{route('login')}}"><u>Login Again</u></a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /Container -->
@endsection