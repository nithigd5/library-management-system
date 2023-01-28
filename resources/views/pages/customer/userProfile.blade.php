@extends('layouts.customer-app')

@section('title', 'My Profile')
@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <div class="section-body">
        <div class="row">
            <div class="col-12">
                <!-- Page title -->
                <div class="my-5">
                    <h3>My Profile</h3>
                    <hr>
                </div>
                <!-- Profile Photo -->
                <div class="text-center">
                    <div class="bg-secondary-soft px-4 rounded">
                        <div class="text-center">
                            <h4 class="mb-2 mt-0">Profile Photo</h4>
                            <div class="text-center">
                                <!-- Image  -->
                                <div class="square position-relative display-2 mb-3">
                                    <img src="{{asset('/img/dummy.jpg')}}"  style="width:150px; height:150px;"/>
                                </div>
                                <!-- Button -->

                                <!-- Content -->
                                <p class="text-muted mt-3 mb-0"><span class="me-1">Note:</span>Minimum size 300px x 300px</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Form START -->
                <form class="file-upload">
                    <div class="row mb-5 gx-5">
                        <!-- Profile detail -->
                        <div class="col-xxl-8 mb-5 mb-xxl-0">
                            <div class="bg-secondary-soft px-4 py-5 rounded">
                                <div class="row g-3">
                                    <h4 class="mb-4 mt-0">My Details</h4>
                                    <div class="col-md-6">
                                        <label class="form-label"></label>
                                    </div>

                                    <!-- First Name -->
                                    <div class="col-md-6">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" placeholder="" aria-label="First name" value={{$user->first_name}} disabled>
                                    </div>
                                    <!-- Last name -->
                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" placeholder="" aria-label="Last name" value={{$user->last_name}} disabled>
                                    </div>
                                    <!-- Phone number -->
                                    <div class="col-md-6">
                                        <label class="form-label">Phone number</label>
                                        <input type="text" class="form-control" placeholder="" aria-label="Phone number" value={{$user->phone}} disabled>
                                    </div>
                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <label for="inputEmail4" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="inputEmail4" value={{$user->email}} disabled>
                                    </div>
                                    <!-- Address -->
                                    <div class="col-md-6">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" placeholder="" aria-label="Address" value={{$user->address}} disabled>
                                    </div>
                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <label class="form-label">Status </label>
                                        <input type="text" class="form-control" placeholder="" aria-label="Status" value={{$user->status}} disabled>
                                    </div>
                                </div> <!-- Row END -->
                            </div>
                        </div>

                    </div> <!-- Row END -->

                    <!-- Social media detail -->
                    <!-- button -->
                </form> <!-- Form END -->
            </div>
        </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
