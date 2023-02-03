@extends('layouts.auth')

@section('title', 'Forgot Password')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Forgot Password</h4>
        </div>

        <div class="card-body">
            <p class="text-muted">We will send a link to reset your password</p>
            <form method="POST" data-parsley-validate>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control @error('email') in-invalid @enderror"
                           name="email" tabindex="1" required autofocus data-parsley-type="email">
                    <div class="invalid-feedback">@error('email') {{ $message }} @enderror</div>
                </div>
                <div class="form-group">
                    <button type="submit"
                            class="btn btn-primary btn-lg btn-block"
                            tabindex="4">
                        Forgot Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
