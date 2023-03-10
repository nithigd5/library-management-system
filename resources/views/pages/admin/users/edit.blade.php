@props(['$customer'])

@extends('layouts.admin-app')

@section('title', 'Edit a Customer')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
          href="{{ asset('library/selectric/public/selectric.css') }}">

@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit a Customer</h1>
            </div>
            <div class="section-body">
                <x-session-message :message="session('message')" :status="session('status')"></x-session-message>
                <x-forms.user-register method="put" route="{{ route('admin.customers.update', $customer->id) }}" class="col-12 col-md-6 col-lg-6 m-auto"
                                       header="Update a Customer"
                                       :first_name="$customer->first_name" :last_name="$customer->last_name"
                                       :email="$customer->email" :phone="$customer->phone" :address="$customer->address"
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
