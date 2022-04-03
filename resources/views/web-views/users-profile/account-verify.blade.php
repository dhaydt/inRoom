@extends('layouts.front-end.app')

@section('title',auth('customer')->user()->f_name.' '.auth('customer')->user()->l_name)

@push('css_or_js')
    <style>
        .headerTitle {
            font-size: 24px;
            font-weight: 600;
            margin-top: 1rem;
        }
        .border:hover {
            border: 3px solid{{$web_config['primary_color']}};
            margin-bottom: 5px;
            margin-top: -6px;
        }
        body {
            font-family: 'Titillium Web', sans-serif
        }
        .form-check.user-account [type="radio"] {
            border: 0;
            clip: auto !important;
            height: 15px;
            margin: -1px;
            left: 0;
            top: 7px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 15px;
        }
        .footer span {
            font-size: 12px
        }
        .product-qty span {
            font-size: 12px;
            color: #6A6A6A;
        }
        .spandHeadO {
            color: {{$web_config['primary_color']}};
            font-weight: 400;
            font-size: 13px;
        }
        .spandHeadO:hover {
            color: {{$web_config['primary_color']}};
            font-weight: 400;
            font-size: 13px;
        }
        .font-name {
            font-weight: 600;
            margin-top: 0px !important;
            margin-bottom: 0;
            font-size: 15px;
            color: #030303;
        }
        .font-nameA {
            font-weight: 600;
            margin-top: 0px;
            margin-bottom: 7px !important;
            font-size: 17px;
            color: #030303;
        }
        label {
            font-size: 16px;
        }
        .photoHeader {
            margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 1rem;
            margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 2rem;
            padding: 13px;
        }
        .card-header {
            border-bottom: none;
        }
        .sidebarL h3:hover + .divider-role {
            border-bottom: 3px solid {{$web_config['primary_color']}}          !important;
            transition: .2s ease-in-out;
        }

        @media (max-width: 350px) {
            .photoHeader {
                margin-left: 0.1px !important;
                margin-right: 0.1px !important;
                padding: 0.1px !important;
            }
        }

        @media (max-width: 600px) {
            .sidebar_heading {
                background: {{$web_config['primary_color']}};
            }
            .photoHeader {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 2px !important;
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 1px !important;
                padding: 13px;
            }
            .sidebar_heading h1 {
                text-align: center;
                color: aliceblue;
                padding-bottom: 17px;
                font-size: 19px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Page Title-->
    <div class="container rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9 sidebar_heading">
                <h1 class="h3  mb-0 float-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} headerTitle">{{\App\CPU\translate('account_verification')}}</h1>
            </div>
        </div>
    </div>
    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl"
        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-9 col-md-9">
                <div class="card box-shadow-sm verif-account">
                    <div class="card-body">
                        <h5 class="card-title">{{ App\CPU\translate('email_and_phone') }}</h5>
                        <div class="row">
                            <div class="col-2">
                                <div class="fa fa-envelope-o"></div>
                            </div>
                        </div><div class="row">
                            <div class="col-2">
                                <div class="fa fa-phone"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card box-shadow-sm verif-id mt-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ App\CPU\translate('identity verification') }}</h5>
                        <div class="row">
                            <div class="col-2">
                                <div class="fa fa-envelope-o"></div>
                            </div>
                        </div><div class="row">
                            <div class="col-2">
                                <div class="fa fa-phone"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            {{-- <section class="col-lg-8">
                <!-- Toolbar-->
                <div class="d-none d-lg-flex justify-content-between align-items-center pt-lg-3 pb-4 pb-lg-5 mb-lg-3">
                    <h6 class="font-size-base text-light mb-0">Update you profile details below:</h6><a class="btn btn-primaryrimary btn-sm" href="{{route('customer.auth.logout')}}"><i class="czi-sign-out mr-2"></i>Sign out</a>
                </div>
                <!-- Profile form-->
                    <form action="{{route('user-update')}}" method="post" enctype="multipart/form-data">
                        @foreach($customerDetails as $customerDetail)
                        @csrf
                        <div class="bg-secondary rounded-lg p-4 mb-4">
                            <div class="media align-items-center">
                                <img id="blah" style="width: 80px; height: 80px;" src="{{asset('storage/app/public/profile')}}/{{$customerDetail['image']}}" width="90" alt="{{$customerDetail['f_name']}}">
                                <div class="media-body pl-3">
                                        <label for="files" style="cursor: pointer;"><i class="czi-loading mr-2"></i>Change avatar</label>
                                        <input id="files" name="image" style="visibility:hidden;" type="file">
                                    <div class="p mb-0 font-size-ms text-muted">Upload JPG, GIF or PNG image. 300 x 300 required.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="account-fn">First Name</label>
                                <input class="form-control" type="text" id="f_name" name="f_name" value="{{$customerDetail['f_name']}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="account-ln">Last Name</label>
                                <input class="form-control" type="text" id="l_name" name="l_name" value="{{$customerDetail['l_name']}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="account-email">Email Address</label>
                                <input class="form-control" type="email" id="account-email" value="{{$customerDetail['email']}}" disabled>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="account-phone">Phone Number</label>
                                <input class="form-control" type="text" id="phone" name="phone" value="{{$customerDetail['phone']}}" >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="account-pass">New Password</label>
                                <div class="password-toggle">
                                    <input class="form-control" type="password" id="password" name="password">
                                    <label class="password-toggle-btn">
                                        <input class="custom-control-input" type="checkbox"><i class="czi-eye password-toggle-indicator"></i><span class="sr-only">Show password</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="account-confirm-pass">Confirm Password</label>
                                <div class="password-toggle">
                                    <input class="form-control" type="password" id="confirm-password" name="con_password">
                                    <label class="password-toggle-btn">
                                        <input class="custom-control-input" type="checkbox"><i class="czi-eye password-toggle-indicator"></i><span class="sr-only">Show password</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="mt-2 mb-3">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div class="custom-control custom-checkbox d-block">
                                </div>
                                <button class="btn btn-primaryrimary mt-3 mt-sm-0" type="submit">Update profile</button>
                            </div>
                        </div>
                    </div>
                        @endforeach
                </form>
            </section> --}}
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.js"></script>
    <script src="{{asset('public/assets/back-end/js/croppie.js')}}"></script>
    <script>
        $(document).ready(function(){
            var mhs = $('#mhs').is(":checked")
            var krywn = $('#krywn').is(":checked")
            var other = $('#other').is(":checked")

            if(mhs){
                $('#univ').removeClass('d-none');
            }if(krywn){
                $('#office').removeClass('d-none');
            }if(other){
                $('#desc').removeClass('d-none');
            }

            $('#mhs').on('change', function(){
                $('#univ').removeClass('d-none');
                $('#office').addClass('d-none');
                $('#desc').addClass('d-none');
            })

            $('#krywn').on('change', function(){
                $('#univ').addClass('d-none');
                $('#office').removeClass('d-none');
                $('#desc').addClass('d-none');
            })

            $('#other').on('change', function(){
                $('#univ').addClass('d-none');
                $('#office').addClass('d-none');
                $('#desc').removeClass('d-none');
            })
        })

    </script>
    <script>
        function checkPasswordMatch() {
            var password = $("#new_password").val();
            var confirmPassword = $("#confirm_password").val();
            $("#message").removeAttr("style");
            $("#message").html("");
            if (confirmPassword == "") {
                $("#message").attr("style", "color:black");
                $("#message").html("{{\App\CPU\translate('Please ReType Password')}}");

            } else if (password == "") {
                $("#message").removeAttr("style");
                $("#message").html("");

            } else if (password != confirmPassword) {
                $("#message").html("{{\App\CPU\translate('Passwords do not match')}}!");
                $("#message").attr("style", "color:red");
            } else if (confirmPassword.length <= 6) {
                $("#message").html("{{\App\CPU\translate('password Must Be 6 Character')}}");
                $("#message").attr("style", "color:red");
            } else {

                $("#message").html("{{\App\CPU\translate('Passwords match')}}.");
                $("#message").attr("style", "color:green");
            }

        }

        $(document).ready(function () {
            $("#confirm_password").keyup(checkPasswordMatch);

        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#files").change(function () {
            readURL(this);
        });

    </script>
@endpush
