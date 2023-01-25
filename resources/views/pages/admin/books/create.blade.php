@extends('layouts.admin-app')

@section('title', 'Create Book')

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
                <x-session-message :message="session('message')" :status="session('status')"></x-session-message>
                <form action="{{ route('books.store') }}" enctype="multipart/form-data" method="post" class="row">
                    @csrf
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Book Specs</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Book Name</label>
                                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" name="name">
                                    <div class="invalid-feedback">
                                        @error('name') {{ $message }} @enderror
                                    </div>
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
                                               class="form-control @error('author') is-invalid @enderror"
                                               value="{{ old('author') }}">
                                        <div class="invalid-feedback">
                                            @error('author') {{ $message }} @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="version">Version</label>
                                        <input type="number" name="version" id="version"
                                               class="form-control @error('version') is-invalid @enderror"
                                               value="{{ old('version') }}">
                                        <div class="invalid-feedback">
                                            @error('version') {{ $message }} @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                        <input type="number" name="price" id="price"
                                               class="form-control @error('price') is-invalid @enderror"
                                               value="{{ old('price') }}">
                                        <div class="invalid-feedback">
                                            @error('price') {{ $message }} @enderror
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
                                    <label for="book_mode">Book Mode</label>
                                    <select id="book_mode" class="form-control @error('mode') is-invalid @enderror" name="mode">
                                        <option disabled selected>Book Mode (Online or Offline ?)</option>
                                        <option value="online" @selected(old('mode') == 'online')>online</option>
                                        <option value="offline" @selected(old('mode') == 'offline')>Offline</option>
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
                                            id="is_download_allowed1" value="1">
                                        <label class="form-check-label" for="is_download_allowed1">
                                            Allow Downloads
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input @error('is_download_allowed') is-invalid @enderror"
                                            type="radio" name="is_download_allowed"
                                            id="is_download_allowed2" value="0">
                                        <label class="form-check-label" for="is_download_allowed2">
                                            Don't Allow Downloads
                                        </label>
                                        <div class="invalid-feedback">
                                            @error('is_download_allowed') {{ $message }} @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col flex-row-reverse d-flex">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </section>
    </div>
@endsection
