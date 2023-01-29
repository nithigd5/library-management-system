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
                <form class="row">
                    @csrf
                    <div class="d-flex justify-content-center" style="padding-left: 250px">
                        <div class="col-6">
                            <div class="card d-flex mx-auto" style="width: 30rem;">
                                <div class="card-header">
                                    <h4>Book Specs</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Book Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ $book->book_name}}" name="book_name" disabled>
                                        <div class="invalid-feedback">
                                            @error('name') {{ $message }} @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Author</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-pencil"></i>
                                                </div>
                                            </div>
                                            <input type="text" name="book_author"
                                                   class="form-control @error('author') is-invalid @enderror"
                                                   value="{{ $book->book_author}}" disabled>
                                            <div class="invalid-feedback">
                                                @error('author') {{ $message }} @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-pencil"></i>
                                                </div>
                                            </div>
                                            <textarea rows="3" name="description"
                                                   class="form-control @error('description') is-invalid @enderror"
                                                   disabled>
                                                {{ $book->description }}
                                            </textarea>
                                            <div class="invalid-feedback">
                                                @error('description') {{ $message }} @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </section>
    </div>
@endsection
