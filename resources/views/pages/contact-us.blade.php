<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no"
        name="viewport">
    <title>Contact &mdash; Library Management System</title>

    <!-- General CSS Files -->
    <link rel="stylesheet"
        href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />

    <!-- CSS Libraries -->

    <!-- Template CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/style.css') }}">
    <link rel="stylesheet"
        href="{{ asset('css/components.css') }}">

    <!-- Start GA -->
    <script async
        src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-md-10 offset-md-1 col-lg-10 offset-lg-1">
                        <div class="login-brand">
                            Library Management System
                        </div>

                        <div class="card card-primary">
                            <div class="row m-0">
                                <div class="col-12 col-md-12 col-lg-5 p-0">
                                    <div class="card-header text-center">
                                        <h4>Contact Us</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" data-parsley-validate>
                                            <div class="form-group floating-addon">
                                                <label>Name</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="far fa-user"></i>
                                                        </div>
                                                    </div>
                                                    <input id="name"
                                                        type="text"
                                                        class="form-control"
                                                        name="name"
                                                        autofocus
                                                        placeholder="Name" required data-parsley-errors-container="#name-error">
                                                    <div id="name-error" style="color: red"></div>
                                                </div>
                                            </div>

                                            <div class="form-group floating-addon">
                                                <label>Email</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-envelope"></i>
                                                        </div>
                                                    </div>
                                                    <input id="email"
                                                        type="email"
                                                        class="form-control"
                                                        name="email"
                                                        placeholder="Email" required data-parsley-type="email" data-parsley-errors-container="#email-error">
                                                    <div id="email-error" style="color: red"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Message</label>
                                                <textarea class="form-control"
                                                    placeholder="Type your message"
                                                    data-height="150" required data-parsley-errors-container="#message_error"></textarea>
                                                <div id="message_error" style="color: red"></div>
                                            </div>

                                            <div class="form-group text-right">
                                                <button type="submit"
                                                    class="btn btn-round btn-lg btn-primary">
                                                    Send Message
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-7 p-0">
                                    <div id="map"
                                        class="contact-map"></div>
                                </div>
                            </div>
                        </div>
                        <div class="simple-footer">
                            Copyright &copy; LMS 2023
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap"></script>
    <script src="{{ asset('library/gmaps/gmaps.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/utilities-contact.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/parsley.js') }}"></script>
</body>

</html>