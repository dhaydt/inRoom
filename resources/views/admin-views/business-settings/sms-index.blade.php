@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('SMS Module Setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-sm-0">
                    <h1 class="page-header-title">{{\App\CPU\translate('sms')}} {{\App\CPU\translate('gateway')}} {{\App\CPU\translate('setup')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" style="padding-bottom: 20px">

            {{-- <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}" style="padding: 20px">
                        <h5 class="text-center">{{\App\CPU\translate('releans_sms')}}</h5>
                        <span class="badge badge-soft-info mb-3">NB : #OTP# will be replace with otp</span>
                        @php($config=\App\CPU\Helpers::get_business_settings('releans_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['releans_sms']):'javascript:'}}"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                              method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{\App\CPU\translate('releans_sms')}}</label>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <input type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('active')}}</label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <input type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('inactive')}} </label>
                                <br>
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('api_key')}}</label><br>
                                <input type="text" class="form-control" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('from')}}</label><br>
                                <input type="text" class="form-control" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('otp_template')}}</label><br>
                                <input type="text" class="form-control" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{\App\CPU\translate('save')}}</button>
                        </form>
                    </div>
                </div>
            </div> --}}

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}" style="padding: 20px">
                        <h5 class="text-center">{{\App\CPU\translate('Zenziva_sms')}}</h5>
                        <span class="badge badge-soft-info mb-3">NB : #OTP# will be replace with otp</span>
                        @php($config=\App\CPU\Helpers::get_business_settings('twilio_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['twilio_sms']):'javascript:'}}"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{\App\CPU\translate('Zenziva')}}</label>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <input type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('active')}}</label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <input type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                    style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('UserKey')}}</label><br>
                                <input type="text" class="form-control" name="sid"
                                    value="{{env('APP_MODE')!='demo'?$config['sid']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                    style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('API_Key')}}</label><br>
                                <input type="text" class="form-control" name="messaging_service_sid"
                                    value="{{env('APP_MODE')!='demo'?$config['messaging_service_sid']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('API_Endpoint')}}</label><br>
                                <input type="text" class="form-control" name="token"
                                    value="{{env('APP_MODE')!='demo'?$config['token']??"":''}}">
                            </div>

                            <div class="form-group mb-2 d-none">
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('from')}}</label><br>
                                <input type="hidden" class="form-control" name="from"
                                    value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('otp_template')}}</label><br>
                                <input type="text" class="form-control" name="otp_template"
                                    value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{\App\CPU\translate('save')}}</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}" style="padding: 20px">
                        <h5 class="text-center">{{\App\CPU\translate('Whatsapp Redirect')}}</h5>
                        <span class="badge badge-soft-info mb-3">NB : Message will be redirect to your WhatsApp number (ex: 0822 3344 5566)</span>
                        @php($config=\App\CPU\Helpers::get_business_settings('whatsapp'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['whatsapp']):'javascript:'}}"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            method="post">
                            @csrf
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                    style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('whatsapp_number')}}</label><br>
                                <input type="number" class="form-control" name="no"
                                    value="{{env('APP_MODE')!='demo'?$config??"":''}}">
                            </div>
                            {{-- <div class="form-group mb-2">
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('template')}}</label><br>
                                <input type="text" class="form-control" name="api_secret"
                                    value="{{env('APP_MODE')!='demo'?$config['api_secret']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('from')}}</label><br>
                                <input type="text" class="form-control" name="from"
                                    value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('otp_template')}}</label><br>
                                <input type="text" class="form-control" name="otp_template"
                                    value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                            </div> --}}

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{\App\CPU\translate('save')}}</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-6 mt-4">
                <div class="card">
                    <div class="card-body text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}" style="padding: 20px">
                        <h5 class="text-center">{{\App\CPU\translate('2factor_sms')}}</h5>
                        <span class="badge badge-soft-info">{{\App\CPU\translate("EX of SMS provider's template : your OTP is XXXX here, please check")}}.</span><br>
                        <span class="badge badge-soft-info mb-3">{{\App\CPU\translate('NB : XXXX will be replace with otp')}}</span>
                        @php($config=\App\CPU\Helpers::get_business_settings('2factor_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['2factor_sms']):'javascript:'}}"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                              method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{\App\CPU\translate('2factor_sms')}}</label>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <input type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('active')}}</label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <input type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('api_key')}}</label><br>
                                <input type="text" class="form-control" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}">
                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{\App\CPU\translate('save')}}</button>
                        </form>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-md-6 mt-4">
                <div class="card">
                    <div class="card-body text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}" style="padding: 20px">
                        <h5 class="text-center">{{\App\CPU\translate('msg91_sms')}}</h5>
                        <span class="badge badge-soft-info mb-3">{{\App\CPU\translate('NB : Keep an OTP variable in your SMS providers OTP Template')}}.</span><br>
                        @php($config=\App\CPU\Helpers::get_business_settings('msg91_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['msg91_sms']):'javascript:'}}"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                              method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{\App\CPU\translate('msg91_sms')}}</label>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <input type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('active')}}</label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <input type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('template_id')}}</label><br>
                                <input type="text" class="form-control" name="template_id"
                                       value="{{env('APP_MODE')!='demo'?$config['template_id']??"":''}}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10px">{{\App\CPU\translate('authkey')}}</label><br>
                                <input type="text" class="form-control" name="authkey"
                                       value="{{env('APP_MODE')!='demo'?$config['authkey']??"":''}}">
                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{\App\CPU\translate('save')}}</button>
                        </form>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection

@push('script_2')

@endpush
