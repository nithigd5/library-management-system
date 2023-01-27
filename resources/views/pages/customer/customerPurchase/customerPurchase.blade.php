@extends('layouts.customer-app')

@section('title',"Payment")

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Payment Page</h1>
            </div>
            <div class="card" style="background-color: #eee;" align="center">
                <div class="card-body p-4">
                    <div class="col-lg-5">

                        <div class="card bg-primary text-white rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">Card details</h5>
                                </div>

                                <p class="small mb-2">Card type</p>
                                <a href="#!" type="submit" class="text-white"><i
                                        class="fab fa-cc-mastercard fa-2x me-2"></i></a>
                                <a href="#!" type="submit" class="text-white"><i
                                        class="fab fa-cc-visa fa-2x me-2"></i></a>
                                <a href="#!" type="submit" class="text-white"><i
                                        class="fab fa-cc-amex fa-2x me-2"></i></a>
                                <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-paypal fa-2x"></i></a>

                                <form class="mt-4" action="{{route('purchase.store',$book->id)}}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-outline form-white mb-4">
                                        <input type="text" id="typeName" class="form-control form-control-lg"
                                               name="holderName" size="17"
                                               placeholder="Cardholder's Name"/>
                                        <label class="form-label" for="typeName">Cardholder's Name</label>
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="text" id="typeText" class="form-control form-control-lg"
                                               name="cNum" size="17"
                                               placeholder="1234 5678 9012 3457" minlength="19" maxlength="19"/>
                                        <label class="form-label" for="typeText">Card Number</label>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-outline form-white">
                                                <input type="text" id="typeExp" name="expDate"
                                                       class="form-control form-control-lg"
                                                       placeholder="MM/YYYY" size="7" id="exp" minlength="7"
                                                       maxlength="7"/>
                                                <label class="form-label" for="typeExp">Expiration</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline form-white">
                                                <input type="password" name="cvv" id="typeText"
                                                       class="form-control form-control-lg"
                                                       placeholder="&#9679;&#9679;&#9679;" size="1" minlength="3"
                                                       maxlength="3"/>
                                                <label class="form-label" for="typeText">Cvv</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label text-white">Rent</label>
                                        <input type="checkbox" class="ml-3" name="rentOrBuy" id="toggleInput"
                                               data-variable="{{$book->price}}" checked data-toggle="toggle"
                                               data-onstyle="warning" data-on="Buy" data-off="Rent">
                                        <label class="form-label text-white mr-3">Buy</label>
                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-between">
                                        <p class="mb-2 font-weight-bold">Subtotal</p>
                                        <div class="mb-2 col-sm-5">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rs.</span>
                                                </div>
                                                <input type="text" class="form-control col-sm-8" name="paidPrice"
                                                       data-variable="{{$book->price}}" value="{{old('paidPrice')}}" id="subTotal"
                                                       aria-describedby="helpId" placeholder="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-2">
                                    @error('paidPrice')
                                    <span class="text-white">{{ $message }}</span>
                                    @enderror
                                    </div>

                                    <button type="submit" class="btn btn-info btn-block btn-lg">
                                        <div class="d-flex justify-content-between">
                                            <span id="subTotalPrice"></span>
                                            <span>Checkout <i class="fas fa-long-arrow-alt-right ms-2"></i></span>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
