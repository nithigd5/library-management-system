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
                <h1>Manage All Books</h1>
            </div>

            <div class="section-body">
                <x-session-message :message="session('message')" :status="session('status')"></x-session-message>
                <div class="row">
                    @foreach($books as $book)
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                            <article class="article">
                                <div class="article-header">
                                    <div class="article-image"
                                         style="background-image: url({{ Storage::url($book->image) }});">
                                    </div>
                                    <div class="article-title">
                                        <h2><a href="#">{{ $book->name }}</a></h2>
                                    </div>
                                </div>
                                <div class="article-details">
                                    <p>{{ $book->author }}. </p>
                                    <div class="article-cta">
                                        <a href="{{ route('admin.books.show', $book->id) }}" class="btn btn-primary">View</a>
                                        <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-secondary">Edit</a>
                                        <a href="#" onclick="$(this).siblings('form').submit()" class="btn btn-danger">Delete</a>

                                        <form style="display: none" action="{{ route('admin.books.destroy', $book->id) }}"
                                              method="post">
                                            @csrf
                                            @method('DELETE')

                                        </form>
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
@endpush
