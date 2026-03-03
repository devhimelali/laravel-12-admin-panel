@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    <div class="login-wrapper login-new">
        <div class="row w-100">
            <div class="col-lg-4 mx-auto">
                <div class="login-content user-login">
                    <div class="login-logo">
                        <img src="{{asset('assets/img/logo.svg')}}" alt="img">
                        <a href="index.html" class="login-logo logo-white">
                            <img src="{{asset('assets/img/logo-white.svg')}}" alt="Img">
                        </a>
                    </div>
                    <form action="{{route('login.store')}}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-body p-5">
                                <div class="login-userheading">
                                    <h3>Sign In</h3>
                                    <h4>Access the {{config('app.name')}} panel using your email and passcode.</h4>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger"> *</span></label>
                                    <div class="input-group">
                                        <input type="text" name="email" class="form-control border-end-0"
                                               value="{{ old('email') }}" placeholder="Ex: user@example.com">
                                        <span class="input-group-text border-start-0">
                                                        <i class="ti ti-mail"></i>
                                                    </span>
                                    </div>
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password <span class="text-danger"> *</span></label>
                                    <div class="pass-group">
                                        <input type="password" name="password" class="pass-input form-control">
                                        <span class="ti toggle-password ti-eye-off text-gray-9"></span>
                                    </div>
                                    @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-login authentication-check">
                                    <div class="row">
                                        <div class="col-12 d-flex align-items-center justify-content-between">
                                            <div class="custom-control custom-checkbox">
                                                <label class="checkboxs ps-4 mb-0 pb-0 line-height-1 fs-16 text-gray-6">
                                                    <input type="checkbox" name="remember" class="form-control">
                                                    <span class="checkmarks"></span>Remember me
                                                </label>
                                            </div>
                                            <div class="text-end">
                                                <a class="text-orange fs-16 fw-medium" href="forgot-password.html">Forgot
                                                    Password?</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-login">
                                    <button type="submit" class="btn btn-primary w-100">Sign In</button>
                                </div>
                                <div class="signinform">
                                    <h4>New on our platform?<a href="register.html" class="hover-a"> Create an
                                            account</a></h4>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                    <p>Copyright &copy; {{date('Y')}} {{config('app.name')}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
