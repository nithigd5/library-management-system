@extends('layouts.admin-app')

@section('title', 'Book Request')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Book Purchase</h1>
            </div>
            <div class="section-body">
                <form action="{{route('admin.purchases.store')}}" id="create-purchase" enctype="multipart/form-data"
                      method="POST"
                      class="row">
                    @csrf
                    <div class="row w-100 justify-content-center">
                        <div class="card w-100 col-5">
                            <div class="card-header">
                                <h4>Create a offline Purchase</h4>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="user">User</label>
                                    <select class="form-control @error('user') is-invalid @enderror" id="user"
                                            name="user">
                                        <option></option>
                                    </select>

                                    <div class="invalid-feedback">
                                        @error('user') {{ $message }} @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="book">Book</label>
                                    <select class="form-control @error('book') is-invalid @enderror" id="book"
                                            name="book">
                                        <option></option>
                                    </select>

                                    <div class="invalid-feedback">
                                        @error('book') {{ $message }} @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="book_price">Book Price</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">₹</span>
                                        <input id="book_price" class="form-control" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="control-label">Is Book for Rent</div>
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="for_rent" id="for_rent"
                                               class="custom-switch-input" @checked(old('for_rent'))>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Rent will be <span
                                                id="rent_percentage">{{ config('book.rent_percentage') }}</span>% of Book price.</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="amount">Amount paying now</label>

                                    <div class="input-group mb-3">
                                        <span class="input-group-text">₹</span>

                                        <input id="amount" type="number"
                                               class="form-control @error('amount') is-invalid @enderror"
                                               aria-label="Amount (to the nearest rupee)"
                                               value="{{ old('amount') }}" name="amount">

                                        <span class="input-group-text">.00</span>

                                        <div class="invalid-feedback">
                                            @error('amount') {{ $message }} @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-success font-weight-bold" id="success"></div>
                                <div class="col d-flex mx-auto" style="padding-left: 200px">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </section>
    </div>
@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#user').select2({
                placeholder: "Search User with just ID, email or Name",
                ajax: {
                    url: '{{ route('admin.api.customers.index') }}',
                    dataType: 'json',
                    processResults: function (data) {
                        return {
                            results: data.data.map((user) => {
                                return {
                                    'id': user.id,
                                    'text': `${user.first_name} ${user.last_name} | ${user.email}`
                                }
                            }),
                            pagination: {
                                more: data.meta.last_page > data.meta.current_page
                            }
                        }
                    }
                }
            });

            $('#book').select2({
                placeholder: "Search Book with just ID or Name",
                ajax: {
                    url: '{{ route('admin.api.books.index') }}',
                    dataType: 'json',
                    processResults: function (data) {
                        return {
                            results: data.data.map((book) => {
                                return {
                                    'id': book.id,
                                    'text': `${book.name} (${book.mode})`
                                }
                            }),
                            pagination: {
                                more: data.meta.last_page > data.meta.current_page
                            }
                        }
                    }
                },
            });

            $('#create-purchase').submit(function (e) {
                e.preventDefault()
                let form = $.find("#create-purchase")[0]
                $(form).find('#success').text('');
                createAndValidateAjax('#create-purchase', function (data) {
                    console.log(data)
                    $(form).find('#success').text(data.message)

                    setTimeout(function () {
                        // window.location.reload()
                    }, 1000);
                })
            })
        });

        $("#book").change(function () {
            let url = '/admin/api/books';
            let book = $(this).val()

            $.get(`${url}/${book}`, function (data) {
                console.log(data)
                let price = Math.round(data.data.book.price)
                $("#book_price").val(price)
                $("#amount").val(price)
            }).fail(function (data) {
                console.log(data)
            })
        })

        $("#for_rent").change(function () {
            if ($(this).is(":checked")) {
                let percentage = parseInt($("#rent_percentage").text())

                let price = Math.round(percentage * $("#book_price").val() / 100)

                $("#amount").val(price)
            } else {
                $("#amount").val($("#book_price").val())
            }
        })

    </script>

@endpush
