@extends('layouts.front-end.app')
@section('title', \App\CPU\translate('Login'))
@push('css_or_js')
    <style>
        .password-toggle-btn .custom-control-input:checked ~ .password-toggle-indicator {
            color: {{$web_config['primary_color']}};
        }

        .for-no-account {
            margin: auto;
            text-align: center;
        }
        .g-recaptcha{
            margin: auto;
        }
        .captcha .text-danger{
            text-align: center;
        }

        .captcha .text-danger strong{
            font-weight: 400 !important;
            margin-top: 8px !important;
        }
    </style>
@endpush
@section('content')
    <div class="container py-4 py-lg-5 my-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 box-shadow">
                    <div class="card-body">
                        <h2 class="h4 mb-1">{{\App\CPU\translate('Masuk')}}</h2>
                        <hr class="mt-2">
                        {{-- <h3 class="font-size-base pt-4 pb-2">{{\App\CPU\translate('or_using_form_below')}}</h3> --}}
                        <form class="needs-validation mt-2" autocomplete="off" action="{{route('customer.auth.login')}}"
                              method="post" id="sign-in-form">
                            @csrf
                            <div class="form-group">
                                <label for="si-email">{{\App\CPU\translate('alamat_email')}} / {{\App\CPU\translate('HP')}}</label>
                                <input class="form-control" type="text" name="user_id" id="si-email"
                                       style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" value="{{old('user_id')}}"
                                       placeholder="{{\App\CPU\translate('Enter_email_address_or_phone_number')}}"
                                       required>
                                <div class="invalid-feedback">{{\App\CPU\translate('tolong_masukan_email_atau_HP_anda')}}.</div>
                            </div>
                            <div class="form-group">
                                <label for="si-password">{{\App\CPU\translate('password')}}</label>
                                <div class="password-toggle">
                                    <input class="form-control" name="password" type="password" id="si-password"
                                           style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                           required>
                                    <label class="password-toggle-btn">
                                        <input class="custom-control-input" type="checkbox"><i
                                            class="czi-eye password-toggle-indicator"></i><span
                                            class="sr-only">{{\App\CPU\translate('lihat')}} {{\App\CPU\translate('password')}} </span>
                                    </label>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <div class="col-md-12 d-flex justify-content-center flex-column captcha">
                                    {!! NoCaptcha::display() !!}
                                    {!! NoCaptcha::renderJs() !!}
                                    @error('g-recaptcha-response')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div> --}}
                            <!-- google recaptcha -->
                            <div class="form-group row jusify-content-center w-100 align-items-center {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                <div class="col-md-12 d-flex flex-column align-items-center">
                                    <div class="g-recaptcha" data-sitekey="{{ $sk }}"></div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group d-flex flex-wrap justify-content-between">

                                <div class="form-group">
                                    <input type="checkbox" class="{{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"
                                           name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="" for="remember">{{\App\CPU\translate('Ingatkan_saya')}}</label>
                                </div>
                                <a class="font-size-sm" href="{{route('customer.auth.recover-password')}}">
                                    {{\App\CPU\translate('lupa_password')}}?
                                </a>
                            </div>
                            <button class="btn btn-primary btn-block btn-shadow"
                                    type="submit">{{\App\CPU\translate('Masuk')}}</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 flex-between row p-0" style="direction: {{ Session::get('direction') }}">
                                <div class="mb-3 {{Session::get('direction') === "rtl" ? '' : 'ml-2'}}">
                                    <h6 class="mt-auto">User baru? Daftar disini</h6>
                                </div>
                                <div class="mb-3 {{Session::get('direction') === "rtl" ? 'ml-2' : ''}}">
                                    <a class="btn btn-outline-primary"
                                       href="{{route('customer.auth.register')}}">
                                        <i class="fa fa-user-circle"></i> {{\App\CPU\translate('daftar')}}
                                    </a>
                                </div>
                            </div>
                            @foreach (\App\CPU\Helpers::get_business_settings('social_login') as $socialLoginService)
                                @if (isset($socialLoginService) && $socialLoginService['status']==true)
                                    <div class="col-sm-6 text-center mb-1">
                                        <a class="btn btn-outline-primary"
                                           href="{{route('customer.auth.service-login', $socialLoginService['login_medium'])}}"
                                           style="width: 100%">
                                            <i class="czi-{{ $socialLoginService['login_medium'] }} mr-2 ml-n1"></i>{{\App\CPU\translate('sing_in_with_'.$socialLoginService['login_medium'])}}
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<!--    <script>
        $('#sign-in-form').submit(function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('customer.auth.login')}}',
                dataType: 'json',
                data: $('#sign-in-form').serialize(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setInterval(function () {
                            location.href = data.url;
                        }, 2000);
                    }
                },
                complete: function () {
                    $('#loading').hide();
                },
                error: function () {
                    toastr.error('Credentials do not match or account has been suspended.', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>-->
@endpush
