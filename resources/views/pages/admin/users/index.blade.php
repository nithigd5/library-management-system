@props(['books'])

@extends('layouts.admin-app')

@section('title', 'Customers')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Manage all Customers</h1>
            </div>

            <div class="section-body">
                @if(session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                @endif
                <div class="row">
                    @foreach($users as $user)
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                            <article class="article">
                                <div class="article-header">
                                    <div class="article-image"  style="background-image: url({{ Storage::url($user->profile_image) }});">
                                    </div>
                                    <div class="article-title">
                                        <h2><a href="#">{{ $user->first_name.' '.$user->last_name }}</a></h2>
                                    </div>
                                </div>
                                <div class="article-details">
                                    <div class="article-cta">
                                        <a href="#" class="btn btn-primary">View</a>
                                        <a href="{{ route('customers.edit', $user->id) }}" class="btn btn-secondary">Edit</a>
                                        <a href="#" onclick="$(this).siblings('form').submit()" class="btn btn-danger">Delete</a>

                                        <form style="display: none" action="{{ route('customers.destroy', $user->id) }}" method="post">
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
