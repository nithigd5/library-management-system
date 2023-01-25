@props(['books'])

@extends('layouts.admin-app')

@section('title', 'Customers')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header row justify-content-between">
                <h1 class="col-6">Manage All Customers</h1>

                <button type="button" class="btn btn-primary col-3 btn btn-primary"
                        onclick="generateLink()">
                    Generate a Customer Invitation Link
                </button>
            </div>

            <div class="section-body">
                <x-session-message :message="session('message')" :status="session('status')"></x-session-message>
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
<x-invite-link-modal></x-invite-link-modal>

@push('scripts')
@endpush
