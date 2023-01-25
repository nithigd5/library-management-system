@props(['purchase'])

@extends('layouts.admin-app')

@section('title', 'Customers')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <x-session-message :message="session('message')" :status="session('status')"></x-session-message>
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8 col-xl-7">
                        <div class="card border-top border-bottom border-3 shadow-lg"
                             style="border-color: var(--primary) !important;">
                            <div class="card-body p-5">

                                <p class="lead fw-bold mb-5" style="color: var(--primary);">Purchase Details</p>

                                <div class="row">
                                    <div class="col mb-3">
                                        <p class="small text-muted mb-1">Date</p>
                                        <p>{{ $purchase->created_at->toDayDateTimeString() }}</p>
                                    </div>
                                    <div class="col mb-3">
                                        <p class="small text-muted mb-1">Purchase ID.</p>
                                        <p>{{ $purchase->id }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col mb-3">
                                        <p class="small text-muted mb-1">Book</p>
                                        <a href="#">{{ $purchase->book->name }}</a>
                                    </div>
                                    <div class="col mb-3">
                                        <p class="small text-muted mb-1">User</p>
                                        <a href="#">{{ $purchase->user->first_name.' '.$purchase->user->last_name }}</a>
                                    </div>
                                </div>

                                <div class="mx-n5 px-5 py-4 text-white" style="background-color: var(--secondary);">
                                    <div class="row">
                                        <div class="col-md-8 col-lg-9">
                                            <p>Book Issued At</p>
                                        </div>
                                        <div class="col-md-4 col-lg-3">
                                            <p>{{ $purchase->book_issued_at->toDayDateTimeString() }}</p>
                                        </div>
                                    </div>
                                    @if($purchase->for_rent)
                                        <div class="row">
                                            <div class="col-md-8 col-lg-9">
                                                <p>Book Return Due</p>
                                            </div>
                                            <div class="col-md-4 col-lg-3">
                                                <p>{{ $purchase->book_return_due }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 col-lg-9">
                                                <p>Book Returned on</p>
                                            </div>
                                            <div class="col-md-4 col-lg-3">
                                                <p>{{ $purchase->book_returned_at?->toDayDateTimeString() ?: 'Not Returned' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mx-n5 px-5 py-4 text-white" style="background-color: var(--primary);">
                                    <div class="row">
                                        <div class="col-md-8 col-lg-9">
                                            <p>Book Purchase type</p>
                                        </div>
                                        <div class="col-md-4 col-lg-3">
                                            <p>{{ $purchase->for_rent ? 'Rent' : 'Owned' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 col-lg-9">
                                            <p>Cost</p>
                                        </div>
                                        <div class="col-md-4 col-lg-3">
                                            <p>@money($purchase->price)</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 col-lg-9">
                                            <p>Payment Status</p>
                                        </div>
                                        <div class="col-md-4 col-lg-3">
                                            <p>{{ $purchase->getPaymentStatus() }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 col-lg-9">
                                            <p class="mb-0">Payment Due</p>
                                        </div>
                                        <div class="col-md-4 col-lg-3">
                                            <p class="mb-0">{{ $purchase->payment_due->toDateString() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row text-right my-4 justify-content-end">
                                    <div class="col-md-8">
                                        <p class="lead mb-0" style="color: var(--primary);"><span
                                                class="mr-2">Pending Amount </span> @money($purchase->pending_amount)
                                        </p>
                                    </div>
                                </div>

                                @if($purchase->isOpen())
                                    <p class="lead font-weight-bold mb-4 pb-2" style="color: var(--primary);">Purchase
                                        Actions</p>
                                    <div class="row">
                                        @if($purchase->toReturn())
                                            <a href="#" onclick="$(this).siblings('form').submit()"
                                               class="btn btn-primary col mx-2">Set as returned</a>
                                            <form class="d-none"
                                                  action="{{ route('purchases.return-book', $purchase->id) }}"
                                                  method="post"> @csrf @method('put')</form>
                                        @endif
                                        @if($purchase->toPay())
                                            <a href="#" class="btn btn-primary col mx-2">Pay Balance amount</a>
                                        @endif
                                    </div>
                                @endif

                                {{--                                <p class="mt-4 pt-2 mb-0">Want any help? <a href="#!" style="color: var(--primary);">Please--}}
                                {{--                                        contact us</a></p>--}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
@endpush
