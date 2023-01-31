@extends('layouts.customer-app')

@section('title', 'Book Request')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Book Request</h1>
            </div>

            <div class="section-body">
                <form action="{{route('bookrequest.store')}}" enctype="multipart/form-data" method="post" class="row">
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
                                    <input type="text" class="form-control @error('book_name') is-invalid @enderror"
                                           value="{{ old('book_name') }}" name="book_name">
                                    <div class="invalid-feedback">
                                        @error('book_name') {{ $message }} @enderror
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
                                               class="form-control @error('book_author') is-invalid @enderror"
                                               value="{{ old('book_author') }}">
                                        <div class="invalid-feedback">
                                            @error('book_author') {{ $message }} @enderror
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
                                                  class="form-control @error('description') is-invalid @enderror">
                                            </textarea>
                                        <div class="invalid-feedback">
                                            @error('description') {{ $message }} @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex mx-auto" style="padding-left: 200px">
                            <button type="submit" class="btn btn-primary">Request</button>
                        </div>
                    </div>
                    </div>
                </form>

            </div>
        </section>
    </div>
@endsection

@push('scripts')

@endpush
