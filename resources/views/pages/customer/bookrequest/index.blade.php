    @props(['books'])

@extends('layouts.customer-app')

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

            <div class="section-body">
                <div class="pull-right">
                    <a class="btn btn-success btn-lg" href="{{ route('bookrequest.create') }}">
                        Book Request</a>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>Sr.No.</th>
                        <th>Book Name</th>
                        <th>Book Author</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                    @php
                        $i=1;
                    @endphp
                    @foreach($books as $book)
                        <tr>
                            <td>{{ $i++}}</td>
                            <td>{{ $book->book_name }}</td>
                            <td>{{ $book->book_author  }}</td>
                            <td>{{ $book->description}}</td>
                            <td><span class="badge badge-{{ $book->status == 'accepted' ? 'success' : ($book->status == 'rejected' ? 'danger' : 'warning')}}">{{ $book->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('bookrequest.show', $book->id) }}"
                                   class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
@endpush
