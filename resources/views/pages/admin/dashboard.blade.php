@extends('layouts.admin-app')

@section('title', 'Admin Dashboard')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard - A Simple analytics</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats">
                                <div class="card-stats-title">Book Acquisitions Statistics -
                                    <div class="dropdown d-inline">
                                        <a class="font-weight-600 dropdown-toggle" data-toggle="dropdown" href="#"
                                           id="orders-month">August</a>
                                        <ul class="dropdown-menu dropdown-menu-sm">
                                            <li class="dropdown-title">Select Month</li>
                                            <li><a href="#" class="dropdown-item">January</a></li>
                                            <li><a href="#" class="dropdown-item">February</a></li>
                                            <li><a href="#" class="dropdown-item">March</a></li>
                                            <li><a href="#" class="dropdown-item">April</a></li>
                                            <li><a href="#" class="dropdown-item">May</a></li>
                                            <li><a href="#" class="dropdown-item">June</a></li>
                                            <li><a href="#" class="dropdown-item">July</a></li>
                                            <li><a href="#" class="dropdown-item active">August</a></li>
                                            <li><a href="#" class="dropdown-item">September</a></li>
                                            <li><a href="#" class="dropdown-item">October</a></li>
                                            <li><a href="#" class="dropdown-item">November</a></li>
                                            <li><a href="#" class="dropdown-item">December</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ $rentedBooksCount }}</div>
                                        <div class="card-stats-item-label">Rented</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ $ownedLastMonth }}</div>
                                        <div class="card-stats-item-label">Owned</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">23</div>
                                        <div class="card-stats-item-label">Returned</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-archive"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Acquisitions</h4>
                                </div>
                                <div class="card-body">
                                    {{ $rentedBooksCount + $ownedLastMonth }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Balance</h4>
                                </div>
                                <div class="card-body">
                                    $187,13
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Sales</h4>
                                </div>
                                <div class="card-body">
                                    4,732
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card gradient-bottom">
                            <div class="card-header">
                                <h4>Top 5 Purchased Books</h4>
                                <div class="card-header-action dropdown">
                                    <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle">Month</a>
                                    <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                        <li class="dropdown-title">Select Period</li>
                                        <li><a href="#" class="dropdown-item">Today</a></li>
                                        <li><a href="#" class="dropdown-item">Week</a></li>
                                        <li><a href="#" class="dropdown-item active">Month</a></li>
                                        <li><a href="#" class="dropdown-item">This Year</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body" id="top-5-scroll"
                                 style="overflow: hidden; outline: none;" tabindex="2">
                                <ul class="list-unstyled list-unstyled-border">
                                    @foreach($topBooks as $book)
                                        <li class="media">
                                            <img class="mr-3 rounded" width="55"
                                                 src="{{ Storage::url($book->image) }}"
                                                 alt="product">
                                            <div class="media-body">
                                                <div class="float-right">
                                                    <div class="font-weight-600 text-muted text-small">86 Sales</div>
                                                </div>
                                                <div class="media-title text-truncate" style="width: 200px;">{{ $book->name }}</div>
                                                <div class="mt-1">
                                                    {{ $book->author }}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Latest Book Purchases</h4>
                                <div class="card-header-action">
                                    <a href="#" class="btn btn-danger">View More <i
                                            class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th>Book</th>
                                            <th>User</th>
                                            <th>Payment</th>
                                            <th>Purchased at</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach($latestPurchases as $purchase)
                                            <tr>
                                                <td><a href="#">{{ $purchase->book->name }}</a></td>
                                                <td class="font-weight-600">{{ $purchase->user->first_name.' '.$purchase->user->last_name }}</td>
                                                <td>
                                                    <div
                                                        class="badge badge-{{ $purchase->payment_status == 'completed' ? 'success': 'warning' }}">{{ $purchase->payment_status }}</div>
                                                </td>
                                                <td>{{ $purchase->created_at->toDayDateTimeString() }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-primary">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
