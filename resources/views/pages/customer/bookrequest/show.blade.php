@extends('layouts.customer-app')

@section('title', 'Book Details')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Book Request Details</h1>
            </div>

            <div class="section-body">
                <x-session-message :message="session('message')" :status="session('status')"></x-session-message>
                <div class="d-flex justify-content-center text-dark">
                    <div class="col-lg-5 col-md-8">
                        <div class="card d-flex mx-auto">
                            <div class="card-header">
                                <h4>Book Specs</h4>
                                <span
                                    class="badge badge-{{ $book->status == 'accepted' ? 'success' : ($book->status == 'rejected' ? 'danger' : 'warning')}}">{{ $book->status }}</span>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <h6 class="h6 font-weight-bold">Book Name</h6>
                                    <p>{{ $book->book_name}}</p>
                                </div>
                                <div class="form-group">
                                    <h6 class="h6 font-weight-bold">Author</h6>
                                    <p>{{ $book->book_author}}</p>
                                </div>
                                <div class="form-group">
                                    <h6 class="h6 font-weight-bold">Description</h6>
                                    <p>{{ $book->description }}</p>
                                </div>
                                <div class="form-group">
                                    <h6 class="h6 font-weight-bold">Comment</h6>
                                    <p id="comment">{{ $book->comment?:'No Comments'  }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
