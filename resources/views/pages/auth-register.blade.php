@php use Illuminate\Support\Facades\URL; @endphp
@extends('layouts.guest')

@section('title', 'Register as a Customer')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
          href="{{ asset('library/selectric/public/selectric.css') }}">

@endpush

@section('main')
    <section class="section">
        <div class="d-flex align-items-stretch flex-wrap">
            <div class="col-md-8 col-12 order-lg-1 min-vh-100 order-2 m-auto bg-white">
                <div class="m-3 p-4">
                    <img src="{{ asset('img/lib_icon.png') }}"
                         alt="logo"
                         width="80"
                         class="shadow-light rounded-circle mb-5 mt-2">
                    <h4 class="text-dark font-weight-normal">Welcome to <span class="font-weight-bold">Library Management System.</span>
                        <span class="text-primary">Invitations Only!</span>
                    </h4>
                    <p class="text-muted">Register if you already
                        have an account you can <a class="font-weight-bold" href="{{ route('login') }}">log in</a>
                        instead.</p>

                    <x-session-message :message="session('message')" :status="session('status')"></x-session-message>
                    <x-forms.user-register header="Register as Customer" method="post"
                                           :route="$url"
                                           first_name="{{ old('first_name') }}" last_name="{{ old('last_name') }}"
                                           email="{{ old('email') }}" phone="{{ old('phone')  }}"
                                           address="{{ old('address') }}"
                    />

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
        </div>
    </section>

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>
    <script src="{{ asset('js/parsley.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
@endpush
