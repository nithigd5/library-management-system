@extends('layouts.admin-app')

@section('title', 'Register a Customer')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
          href="{{ asset('library/selectric/public/selectric.css') }}">

@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Register a Customer</h1>
            </div>
            <div class="section-body">
                @if(session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                @endif
                <x-forms.user-register method="post" route="{{ route('customers.store') }}"
                                       first_name="{{ old('first_name') }}" last_name="{{ old('last_name') }}"
                                       email="{{ old('email') }}" phone="{{ old('phone')  }}"
                                       address="{{ old('address') }}"
                />
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>
    <script src="{{ asset('js/parsley.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
@endpush
