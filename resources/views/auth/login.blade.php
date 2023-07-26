@extends('layouts.auth')

@section('content')


<div class="container-xxl">

    <div class="row">
        <div class="col-sm-10 position-relative mx-auto">
            <div class="auth-content py-8">
                <form class="w-100" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-9 mx-auto">
                            <div class="text-center mb-7">
                                <a class="navbar-brand me-0" href="/">
                                    <img class="brand-img d-inline-block" src="{{asset('dist/img/logo.png')}}" alt="YesCRM">
                                </a>
                            </div>
                            <div class="card card-lg card-border">
                                <div class="card-body">
                                    <h4 class="mb-4 text-center">Sign in to your account</h4>
                                    <div class="row gx-3">
                                        <div class="form-group col-lg-12">
                                            <div class="form-label-group">
                                                <label for="email">Email Address</label>
                                            </div>
                                            <input id="email" type="email" placeholder="Enter username or email ID" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <div class="form-label-group">
                                                <label>Password</label>
                                                <!-- @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="fs-7 fw-medium">Forgot Password ?</a>
                                                </a>
                                                @endif -->
                                            </div>
                                            <div class="input-group password-check">
                                                <span class="input-affix-wrapper">
                                                    <input id="password" type="password" placeholder="Enter your password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror

                                                    <a href="#" class="input-suffix text-muted">
                                                        <span class="feather-icon"><i class="form-icon" data-feather="eye"></i></span>
                                                        <span class="feather-icon d-none"><i class="form-icon" data-feather="eye-off"></i></span>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check form-check-sm mb-3">
                                            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label text-muted fs-7" for="remember">Keep me logged in</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-uppercase btn-block">Login</button>
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
@endsection