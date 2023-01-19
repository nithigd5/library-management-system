@props(['route', 'method', 'first_name', 'last_name', 'email', 'phone', 'address'])

<form method="POST" action="{{ $route }}" enctype="multipart/form-data"
      data-parsley-validate>
    @if($method === 'put')
        @method('put')
    @endif
    @csrf
    <div class="col-12 col-md-6 col-lg-6 m-auto">
        <div class="card">
            <div class="card-header">
                <h4>Add a Customer</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label for="first_name">First Name</label>
                        <input id="first_name" type="text"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ $first_name }}" minlength="3" name="first_name" autofocus
                               required>
                        <div
                            class="invalid-feedback">@error('first_name') {{ $message }} @enderror</div>
                    </div>
                    <div class="form-group col-6">
                        <label for="last_name">Last Name</label>
                        <input id="last_name" type="text"
                               class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ $last_name }}" name="last_name" minlength="2" required>
                        <div class="invalid-feedback">@error('last_name') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ $email }}"
                           name="email" required>
                    <div class="invalid-feedback">@error('email') {{ $message }} @enderror</div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input id="phone" type="number"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ $phone }}"
                           name="phone" pattern="[6-9][0-9]{9}" required>
                    <div class="invalid-feedback">@error('phone') {{ $message }} @enderror</div>
                </div>

                @if($method == 'post')
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="password">Password</label>
                            <input id="password" type="password"
                                   class="form-control pwstrength @error('password') is-invalid @enderror"
                                   data-indicator="pwindicator"
                                   name="password" required
                                   data-parsley-minlength="8">
                            <div class="invalid-feedback">@error('password') {{ $message }} @enderror</div>
                            <div id="pwindicator"
                                 class="pwindicator">
                                <div class="bar"></div>
                                <div class="label"></div>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="password_confirmation">Password Confirmation</label>
                            <input id="password_confirmation" type="password" class="form-control"
                                   name="password_confirmation" required data-parsley-equalto="#password">
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label for="address">Full Address</label>
                    <textarea id="address" type="address" rows="4" minlength="8" style="height: auto;"
                              class="form-control @error('address') is-invalid @enderror"
                              name="address" required>{{ $address }}</textarea>
                    <div class="invalid-feedback">@error('address') {{ $message }} @enderror</div>
                </div>

                <div class="custom-file form-group">
                    <input type="file" name="profile_image" accept="image/*"
                           class="custom-file-input @error('image') is-invalid @enderror"
                           id="profile_image" @required($method === 'post')>
                    <label class="custom-file-label" for="profile_image">Profile Image</label>
                    <div class="invalid-feedback">
                        @error('image') {{ $message }} @enderror
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit"
                            class="btn btn-primary btn-lg btn-block">
                        @if($method === 'put') Update @else Register @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
