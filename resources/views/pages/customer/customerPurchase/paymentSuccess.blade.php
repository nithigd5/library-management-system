@extends('layouts.customer-app')

@section('title',"Payment Success")

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Payment Success</h1>
            </div>
            <div class="card" style="background-color: #eee;" align="center">
                <div class="card-body p-4">
                    <div class="col-lg-5">
            <div class="text-center">
                <h1 class="mt-5">Payment Successful</h1>
                <p class="lead">Thank you for your purchase.</p>
                <a href="{{route('book.show',$book->id)}}" class="btn btn-success"><span>Click to Go Back and Read</span></a>
            </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
