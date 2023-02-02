@props(['purchases'])

@extends('layouts.admin-app')

@section('title', 'My Purchases')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header mb-1">
                <h1 class="col">View recent {{ $status }} Purchases</h1>
            </div>
            <div class="section-header pb-1 mt-0">
                <form action="{{ request()->url() }}" id="filter" class="container" method="get">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="form-label">Status</label>
                            <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="all"
                                           class="selectgroup-input" @checked(request('status') == 'all')>
                                    <span class="selectgroup-button">All</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="active"
                                           class="selectgroup-input" @checked(request('status') == 'active')>
                                    <span class="selectgroup-button">Active</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="inactive"
                                           class="selectgroup-input" @checked(request('status') == 'inactive')>
                                    <span class="selectgroup-button">InActive</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-label">Type</label>
                            <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="radio" name="type" value="all"
                                           class="selectgroup-input" @checked(request('type') == 'all')>
                                    <span class="selectgroup-button">All</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="type" value="rented"
                                           class="selectgroup-input" @checked(request('type') == 'rented')>
                                    <span class="selectgroup-button">Rented</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="type" value="owned"
                                           class="selectgroup-input" @checked(request('type') == 'owned')>
                                    <span class="selectgroup-button">Owned</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-label">Payment</label>
                            <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="radio" name="payment" value="all"
                                           class="selectgroup-input" @checked(request('payment') == 'all')>
                                    <span class="selectgroup-button">All</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="payment" value="2"
                                           class="selectgroup-input" @checked(request('payment') == '2')>
                                    <span class="selectgroup-button">Half-Paid</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="payment" value="1"
                                           class="selectgroup-input" @checked(request('payment') == '1')>
                                    <span class="selectgroup-button">Paid</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="payment" value="0"
                                           class="selectgroup-input" @checked(request('payment') == '0')>
                                    <span class="selectgroup-button">UnPaid</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="date_range">Date Range</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                                <input id="date_range" value="{{ request('date_range') }}" name="date_range" type="text" class="form-control daterange-cus">
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-label">Due</label>
                            <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="radio" name="due" value="none" class="selectgroup-input"
                                        @checked(request('due') == 'none')>
                                    <span class="selectgroup-button">None</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="due" value="book_due" class="selectgroup-input"
                                        @checked(request('due') == 'book_due')>
                                    <span class="selectgroup-button">Book Due</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="due" value="payment_due"
                                           class="selectgroup-input" @checked(request('due') == 'payment_due')>
                                    <span class="selectgroup-button">Payment Due</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="due" value="all"
                                           class="selectgroup-input" @checked(request('due') == 'all')>
                                    <span class="selectgroup-button">Book and Payment Due</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Book Returned</label>
                            <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="radio" name="returned" value="all"
                                           class="selectgroup-input" @checked(request('returned') == 'all')>
                                    <span class="selectgroup-button">All</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="returned" value="0"
                                           class="selectgroup-input" @checked(request('returned') == '0')>
                                    <span class="selectgroup-button">Not Returned</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="returned" value="1"
                                           class="selectgroup-input" @checked(request('returned') == '1')>
                                    <span class="selectgroup-button">Returned</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="sort">Sort the Result</label>
                            <select id="sort" class="form-control" name="sort">
                                <option value="recent" selected>Most Recent</option>
                                <option value="oldest" @selected(request('sort') === 'oldest')>Oldest</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary col-4 position-absolute" style=" right: 0; bottom: 15px">Get</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="section-body">
                <x-session-message :message="session('message')" :status="session('status')"></x-session-message>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body p-0">
                                <x-purchase-table :$purchases/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script>
    </script>
@endpush
