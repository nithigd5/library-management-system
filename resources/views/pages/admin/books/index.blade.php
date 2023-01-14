@props(['books'])

@extends('layouts.admin-app')

@section('title', 'Admin Dashboard')

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
                <div class="row">
                    @foreach($books as $book)
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                            <article class="article">
                                <div class="article-header">
                                    <div class="article-image"  style="background-image: url({{ Storage::url($book->image) }});">
                                    </div>
                                    <div class="article-title">
                                        <h2><a href="#">{{ $book->name }}</a></h2>
                                    </div>
                                </div>
                                <div class="article-details">
                                    <p>{{ $book->author }}. </p>
                                    <div class="article-cta">
                                        <a href="#" class="btn btn-primary">View</a>
                                        <a href="#" class="btn btn-secondary">Edit</a>
                                        <a href="#" class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
