@extends('layouts.auth')

@section('title', 'Reset Password')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Set new Password</h4>
        </div>

        <div class="card-body">
            <form method="POST" data-parsley-validate>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password" tabindex="2" required data-parsley-minlength="8">
                    <div class="invalid-feedback"></div>
                    <div id="pwindicator" class="pwindicator"><div class="bar"></div><div class="label"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-password_confirmation">Confirm Password</label>
                    <input id="password-password_confirmation" type="password" class="form-control" name="confirm-password" tabindex="2" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
@endpush
