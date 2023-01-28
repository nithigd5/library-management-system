@extends('layouts.admin-app')

@section('title', 'Book Details')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Books</h1>
            </div>

            <div class="section-body">
                <form class="row">
                    @csrf
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Book Specs</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Book Name</label>
                                    <input type="text" id="name" class="form-control"
                                           value="{{ $book->name}}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="author">Author</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-pencil"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="author" id="author"
                                               class="form-control"
                                               value="{{ $book->author}}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="version">Version</label>
                                    <input type="number" name="version" id="version"
                                           class="form-control"
                                           value="{{ $book->version}}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" name="price" id="price"
                                           class="form-control"
                                           value="{{ $book->price}}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Book Availability</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="book_mode">Book Mode</label>
                                    <select id="book_mode" class="form-control" name="mode">
                                        <option disabled selected>{{$book->mode}}</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="d-flex align-items-center col-6">
                                        <img src="{{ Storage::url($book->image) }}" alt="..." class="img-thumbnail"
                                             width="150px" height="250px">
                                    </div>
                                    <div class="d-flex align-items-center col-6">
                                        <div class="col">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="radio" name="is_download_allowed"
                                                        id="is_download_allowed1" value="1"
                                                        {{ $book->is_download_allowed == 1 ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="is_download_allowed1">
                                                        Download is Allowed
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="radio" name="is_download_allowed"
                                                        id="is_download_allowed2" value="{{$book->is_download_allowed}}"
                                                        disabled>
                                                    <label class="form-check-label" for="is_download_allowed2">
                                                        Download not Allowed
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-3">
                                                    <a href="{{route('purchase.create',$book->id)}}"
                                                       class="btn btn-success">Buy</a>
                                                </div>
                                                <br>
                                                <div class="col-3">
                                                    <a href="{{route('book.viewpdf',$book->id)}}"
                                                       class="btn btn-primary">View</a>
                                                </div>
                                                <br>
                                                <div class="col-3">
                                                    <a href="{{route('book.download',$book->id)}}"
                                                       class="btn btn-warning">Download</a>
                                                </div>
                                                @if(session('status'))
                                                    <p class="mt-3 text-primary text-danger"> {{ session('status') }}</p>
                                                @endif
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
