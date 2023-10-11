<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Lion - Portal</title>
    <!-- <link rel="apple-touch-icon" href="{{asset('backend/images/ico/apple-icon-120.png')}}"> -->
    <link rel="icon" href="{{asset('images/iconlion.png')}}">
    <!-- <link rel="shortcut icon" type="image/x-icon" href="{{asset('backend/images/ico/favicon.ico')}}"> -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('backend/vendors/css/vendors.min.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('backend/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('backend/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('backend/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('backend/css/components.css')}}">
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('backend/css/core/colors/palette-gradient.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('backend/css/pages/authentication.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('backend/css/style.css')}}">
    <!-- END: Custom CSS-->

</head>
<body class="vertical-layout vertical-menu-modern 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body"> 
                <section class="row flexbox-container">
                    <div class="col-xl-8 col-11 d-flex justify-content-center">
                        <div class="card bg-authentication rounded-0 mb-0">
                            <div class="row m-0">
                                <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                                    <img src="{{ asset('images/newlogin3.png') }}" >
                                </div>
                                <div class="col-lg-6 col-12 p-0">
                                    <div class="card rounded-0 mb-0 px-2">
                                        <div class="card-header pb-1">
                                            @if($message = Session::get('error'))
                                            <div class="alert alert-danger alert-block">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @endif
                                            <div class="card-title">
                                                <h4 class="mb-0">Login Lion-Portal</h4>
                                            </div>
                                        </div>
                                        <p class="px-2">Selamat Datang, Masuk Menggunakan Akun Kamu.</p>
                                        <div class="card-content">
                                            <div class="card-body pt-1">
                                                <form action="{{route('login')}}" method="POST">
                                                                        
                                                    @if(Session::has('error'))
                                                        <div class="alert alert-danger alert-message">
                                                            {{Session::get('error')}}
                                                        </div>
                                                    @endif

                                                    @if($errors->any())
                                                        <div class="alert alert-danger alert-message">
                                                            {{$errors->first()}}
                                                        </div>
                                                    @endif

                                                    @csrf
                                                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                        <input type="text" name="userid" class="form-control {{ $errors->has('userid') ? 'is-invalid' : '' }}" id="userid" placeholder="User ID" value="{{ old('userid') }}" maxlength="6">
                                                        @if($errors->has('userid'))
                                                        <span class="invalid-feedback text-danger" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @endif
                                                        <div class="form-control-position">
                                                            <i class="feather icon-user"></i>
                                                        </div>
                                                        <label for="userid">Userid</label>
                                                    </fieldset>

                                                    <fieldset class="form-label-group position-relative has-icon-left">
                                                        <input type="password" name="password" class="form-control {{ $errors->has('pass') ? 'is-invalid' : '' }}"" id="user-password" placeholder="Password">
                                                        @if($errors->has('pass'))
                                                        <span class="invalid-feedback text-danger" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @endif
                                                        <div class="form-control-position">
                                                            <i class="feather icon-lock"></i>
                                                        </div>
                                                        <label for="user-password">Password</label>
                                                    </fieldset>
                                                    <button type="submit" class="btn btn-success float-right btn-inline btn-block">Login</button>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- <span class="mt-1 ml-2" style="text-align: left"><a href="">Lupa Password ?</a></span> -->
                                        <div class="login-footer">
                                            <div class="divider">
                                                <div class="divider-text"><a>Lion-Portal</a></div>
                                            </div>
                                            <p style="font-size:10px;text-align:center">Copyright<i class="fa fa-copyright" aria-hidden="true"></i> 2023 <a>Lion Wings Indonesia. All rights reserved.</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- BEGIN: Vendor JS-->
    <script src="{{asset('backend/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{asset('backend/js/core/app-menu.js')}}"></script>
    <script src="{{asset('backend/js/core/app.js')}}"></script>
    <script src="{{asset('backend/js/scripts/components.js')}}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->
</body>
</html>

