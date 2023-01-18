@props(['book'])

@extends('layouts.admin-app')

@section('title', 'Edit Book')

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
                <form action="{{ route('books.update', $book->id) }}" enctype="multipart/form-data" method="post" class="row">
                    @csrf
                    @method('put')
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Book Specs</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Book Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ $book->name }}" name="name">
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
                                        <input type="text" name="author"
                                               class="form-control @error('author') is-invalid @enderror"
                                               value="{{ $book->author }}">
                                        <div class="invalid-feedback">
                                            @error('author') {{ $message }} @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Version</label>
                                    <div class="input-group">
                                        <input type="number" name="version"
                                               class="form-control @error('version') is-invalid @enderror"
                                               value="{{ $book->version }}">
                                        <div class="invalid-feedback">
                                            @error('version') {{ $message }} @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <div class="input-group">
                                        <input type="number" name="price"
                                               class="form-control @error('price') is-invalid @enderror"
                                               value="{{ $book->price }}">
                                        <div class="invalid-feedback">
                                            @error('price') {{ $message }} @enderror
                                        </div>
                                    </div>
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
                                    <label>Book Mode</label>
                                    <select class="form-control @error('mode') is-invalid @enderror" name="mode">
                                        <option disabled selected>Book Mode (Online or Offline ?)</option>
                                        <option value="online" @selected($book->mode == 'online')>online</option>
                                        <option value="offline" @selected($book->mode == 'offline')>Offline</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('mode') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="custom-file form-group">
                                    <input type="file" name="image" accept="image/*"
                                           class="custom-file-input @error('image') is-invalid @enderror"
                                           id="book_image">
                                    <label class="custom-file-label" for="book_file">Book Front Image</label>
                                    <div class="invalid-feedback">
                                        @error('image') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="custom-file form-group">
                                    <input type="file" name="book" accept="application/pdf"
                                           class="custom-file-input @error('book') is-invalid @enderror"
                                           id="book_pdf">
                                    <label class="custom-file-label" for="book_pdf">Book PDF</label>
                                    <div class="invalid-feedback">
                                        @error('book') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input @error('is_download_allowed') is-invalid @enderror"
                                            type="radio" name="is_download_allowed"
                                            id="is_download_allowed1" value="1" @selected($book->is_download_allowed)>
                                        <label class="form-check-label" for="is_download_allowed1">
                                            Allow Downloads
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input @error('is_download_allowed') is-invalid @enderror"
                                            type="radio" name="is_download_allowed"
                                            id="is_download_allowed2" value="0" @selected($book->is_download_allowed)>
                                        <label class="form-check-label" for="is_download_allowed2">
                                            Don't Allow Downloads
                                        </label>
                                        <div class="invalid-feedback">
                                            @error('is_download_allowed') {{ $message }} @enderror
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col flex-row-reverse d-flex">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')

@endpush
