@php use Illuminate\Support\Facades\URL; @endphp
@extends('layouts.guest')

@section('title', 'Login')

@push('style')

@endpush

@section('main')
    <section class="section">
        <div class="d-flex align-items-stretch flex-wrap">
            <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">

                <div class="m-3 p-4">
                    <x-session-message :message="session('message')" :status="session('status')"></x-session-message>
                    <img src="{{ asset('img/lib_icon.png') }}"
                         alt="logo"
                         width="80"
                         class="shadow-light rounded-circle mb-5 mt-2">
                    <h4 class="text-dark font-weight-normal">Welcome to <span class="font-weight-bold">Library Management System</span>
                    </h4>
                    <p class="text-muted">Before you get started, you must login or register if you don't already
                        have an account.</p>
                    <form method="POST" action="{{ route('login') }}" data-parsley-validate>
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email"
                                   type="email"
                                   value="{{ old('email') }}"
                                   class="form-control"
                                   name="email"
                                   tabindex="1"
                                   autofocus required
                                   data-parsley-type="email" data-parsley-trigger="change">

                            <div class="invalid-feedback">@error('email'){{ $message }}@enderror</div>

                        </div>

                        <div class="form-group">
                            <div class="d-block">
                                <label for="password"
                                       class="control-label">Password</label>
                            </div>
                            <input id="password"
                                   type="password"
                                   class="form-control"
                                   name="password"
                                   tabindex="2"
                                   required data-parsley-errors-container="#password-error" data-parsley-minlength="8"
                                   data-parsley-trigger="change">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       name="remember"
                                       class="custom-control-input"
                                       tabindex="3"
                                       id="remember-me">
                                <label class="custom-control-label"
                                       for="remember-me">Remember Me</label>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <a href="{{url('auth-forgot-password')}}"
                               class="float-left mt-3">
                                Forgot Password?
                            </a>
                            <button type="submit"
                                    class="btn btn-primary btn-lg btn-icon icon-right"
                                    tabindex="4">
                                Login
                            </button>
                        </div>

                        <div class="mt-5 text-center">
                            Don't have an account? <a href="{{url('contact-us')}}">Contact Us</a>
                        </div>
                    </form>

                    <div class="text-small mt-5 text-center">
                        Copyright &copy; Your Company. Made with 💙 by Aman & Nithi
                        <div class="mt-2">
                            <a href="#">Privacy Policy</a>
                            <div class="bullet"></div>
                            <a href="#">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="col-lg-8 col-12 order-lg-2 min-vh-100 background-walk-y position-relative overlay-gradient-bottom order-1"
                data-background="{{ asset('img/unsplash/library_img.jpg') }}">
                <div class="absolute-bottom-left index-2">
                    <div class="text-light p-5 pb-2">
                        <div class="mb-5 pb-3">
                            <h1 class="display-4 font-weight-bold mb-2">Welcome Readers!</h1>
                            <h5 class="font-weight-normal text-muted-transparent">Library Management System</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
