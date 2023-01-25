@extends('layouts.admin-app')

@section('title', 'Request Book')

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
                <form action="{{ route('bookrequest.store') }}" enctype="multipart/form-data" method="post" class="row">
                    @csrf
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Book Specs</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Book Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" name="name">
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
                                               value="{{ old('author') }}">
                                        <div class="invalid-feedback">
                                            @error('author') {{ $message }} @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Status</h4>
                                <select class="form-control @error('mode') is-invalid @enderror" name="mode">
                                    <option disabled selected>None</option>
                                    <option value="Pending" @selected(old('mode') == 'Pending')>Pending</option>
                                    <option value="Accepted" @selected(old('mode') == 'Accepted')>Accepted</option>
                                    <option value="Rejected" @selected(old('mode') == 'Rejected')>Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col flex-row-reverse d-flex">
                        <button type="submit" class="btn btn-primary">Request</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')

@endpush
