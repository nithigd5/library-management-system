@props(['purchases'])

@extends('layouts.admin-app')

@section('title', 'Customers')

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
                    <div class="row justify-content-end">
                        <div class="form-group col-md-6 col-lg-6  col-xl-9">
                            <label class="form-label">Filters</label>
                            <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="radio" name="due" value="none" class="selectgroup-input" @checked(request('due') == 'none')>
                                    <span class="selectgroup-button">None</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="due" value="all" class="selectgroup-input" @checked(request('due') == 'all')>
                                    <span class="selectgroup-button">Book and Payment Due</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="due" value="book_due" class="selectgroup-input"
                                        @checked(request('due') == 'book_due')>
                                    <span class="selectgroup-button">Book Due</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="due" value="payment_due" class="selectgroup-input" @checked(request('due') == 'payment_due')>
                                    <span class="selectgroup-button">Payment Due</span>
                                </label>
                            </div>
                            <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="radio" name="type" value="all" class="selectgroup-input"  @checked(request('type') == 'all')>
                                    <span class="selectgroup-button">All</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="type" value="rented" class="selectgroup-input" @checked(request('type') == 'rented')>
                                    <span class="selectgroup-button">Rented</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="type" value="owned" class="selectgroup-input" @checked(request('type') == 'owned')>
                                    <span class="selectgroup-button">Owned</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-xl-3">
                            <label for="date_range">Date Range</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                                <input id="date_range" name="date_range" type="text" class="form-control daterange-cus">
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">Get</button>

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
        console.log($('input[name="filter"]').val())

        $(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();

            $('#date_range').daterangepicker({
                opens: 'left',
                startDate: start,
                endDate: end,
                value: null
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });
        //
        // $('#filter input').change(function (e){
        //     $("#filter").submit()
        // });

    </script>
@endpush
