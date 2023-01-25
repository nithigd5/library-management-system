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
                <h1>Books</h1>
            </div>

            <div class="section-body">
                @if(session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                @endif
                <div class="row">
                    @foreach($books as $book)
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                            <article class="article" onclick="location.href='{{route('book.show',['id'=>$book->id])}}'" style="border: 2px solid blue;" >
                                <div class="article-header">
                                    <div class="article-image"
                                         style="background-image: url({{ Storage::url($book->image) }});">
                                    </div>
                                    <div class="article-title">
                                        <img src="{{asset('img/dummy.jpg')}}" alt="image">
                                        <h2><a href="#">{{ $book->name }}</a></h2>
                                    </div>
                                </div>
                                <div class="article-details">
                                    <p>{{ $book->author }}. </p>
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
