@php use App\Models\BookRequest; @endphp
@extends('layouts.admin-app')

@section('title', 'Book Details')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Book Request Details</h1>
            </div>

            <div class="section-body">
                <x-session-message :message="session('message')" :status="session('status')"></x-session-message>
                <div class="d-flex justify-content-center text-dark">
                    <div class="col-lg-5 col-md-8">
                        <div class="card d-flex mx-auto">
                            <div class="card-header">
                                <h4>Book Specs</h4>
                                <span
                                    class="badge badge-{{ $book->status == 'accepted' ? 'success' : ($book->status == 'rejected' ? 'danger' : 'warning')}}">{{ $book->status }}</span>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <h6 class="h6 font-weight-bold">Book Name</h6>
                                    <p>{{ $book->book_name}}</p>
                                </div>
                                <div class="form-group">
                                    <h6 class="h6 font-weight-bold">Author</h6>
                                    <p>{{ $book->book_author}}</p>
                                </div>
                                <div class="form-group">
                                    <h6 class="h6 font-weight-bold">Description</h6>
                                    <p>{{ $book->description }}</p>
                                </div>
                                <div class="form-group">
                                    <h6 class="h6 font-weight-bold">Comment</h6>
                                    <p id="comment">{{ $book->comment }}</p>
                                </div>
                                <button onclick="showBookRequestUpdateModal()"
                                        class="btn btn-primary col mx-2">Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
<div class="modal fade text-dark" id="book-request-update-modal" tabindex="-1" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update a Book Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.book-requests.update', $book->id) }}"
                      id="book-request-update">
                    <div class="form-group">
                        <label class="form-label">Status:</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="{{ BookRequest::STATUS_ACCEPTED }}"
                                       class="selectgroup-input" @checked($book->status === BookRequest::STATUS_ACCEPTED)>
                                <span class="selectgroup-button">Accept</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="{{ BookRequest::STATUS_PENDING }}"
                                       class="selectgroup-input" @checked($book->status === BookRequest::STATUS_PENDING)>
                                <span class="selectgroup-button">Pending</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="{{ BookRequest::STATUS_REJECTED }}"
                                       class="selectgroup-input" @checked($book->status === BookRequest::STATUS_REJECTED)>
                                <span class="selectgroup-button">Reject</span>
                            </label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea class="form-control text-dark" id="comment" name="comment"
                                  style="height: auto">{{ $book->comment }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    @csrf @method('PUT')
                </form>
            </div>
            <div class="modal-footer">
                <p class="text-success" id="update-success"></p>
                <button type="button" class="btn btn-primary" onclick="updateRequest()">Save
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function showBookRequestUpdateModal() {
            $('#book-request-update-modal').modal('show');
        }

        function updateRequest() {
            createAndValidateAjax("#book-request-update", function (data) {
                console.log(data)
                $('#book-request-update-modal').modal('hide');
                window.location.reload()
            })
        }
    </script>
@endpush
