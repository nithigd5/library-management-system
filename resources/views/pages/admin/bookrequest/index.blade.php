@props(['books'])

@extends('layouts.admin-app')

@section('title', 'Books')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Manage Requested Books</h1>
            </div>
            <div class="section-header">
                <form action="{{ request()->url() }}" id="filter" class="container" method="get">
                    <div class="row">
                        <div class="form-group col-md-3">
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
                        <div class="form-group col-md-4">
                            <label class="form-label">Status</label>
                            <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="all"
                                           class="selectgroup-input" @checked(request('status') == 'all')>
                                    <span class="selectgroup-button">All</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="accepted"
                                           class="selectgroup-input" @checked(request('status') == 'accepted')>
                                    <span class="selectgroup-button">Accepted</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="rejected"
                                           class="selectgroup-input" @checked(request('status') == 'rejected')>
                                    <span class="selectgroup-button">Rejected</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="pending"
                                           class="selectgroup-input" @checked(request('status') == 'pending')>
                                    <span class="selectgroup-button">Pending</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="sort">Sort the Result</label>
                            <select class="form-control" id="sort" name="sort">
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
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th>Book Name</th>
                            <th>Book Author</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        @foreach($books as $book)
                            <tr>
                                <td>{{ $book->book_name }}</td>
                                <td>{{ $book->book_author  }}</td>
                                <td>{{ $book->description}}</td>
                                <td><span
                                        class="badge badge-{{ $book->status == 'accepted' ? 'success' : ($book->status == 'rejected' ? 'danger' : 'warning')}}">{{ $book->status }}</span>
                                </td>
                                <td>{{ $book->updated_at->toFormattedDateString() }}</td>
                                <td>
                                    <a href="{{ route('admin.book-requests.show', $book->id) }}"
                                       class="btn btn-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $books->links() }}
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
@endpush
