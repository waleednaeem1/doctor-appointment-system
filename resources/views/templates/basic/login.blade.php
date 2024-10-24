@extends('staff.layouts.master')
@section('content')
    <div class="login-main-custom" style="background-image: url('{{ asset('assets/images/logoIcon/images/background.jpg') }}'); background-color:none;">
        <div class="container custom-container">
            <div class="row">
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-8 col-sm-11 d-flex align-items-center justify-content-center" id="hideonmobile">
                    <img src="/assets/images/logoIcon/images/signin-vector.png" alt="" style="position: fixed; width: 25%; top: 10rem;"/>
                </div>
                <div class="align-items-center col-lg-6 col-md-8 col-sm-11 col-xl-6 col-xxl-6 d-flex">
                    <div class="login-area">
                        <div class="mylogo">
                            <img src="/assets/images/logoIcon/images/logo.png" alt="This is a logo" class="logo" />
                            <h1 class="logo_heading">
                                Welcome to find <br />
                                Doctors and practicee near you


                            </h1>
                        </div>
                        <div class="login-wrapper__body bg-white" style="border-bottom-right-radius: 23px !important; border-bottom-left-radius: 23px !important;">
                            <form method="POST" name="userLoginForm" class="cmn-form verify-gcaptcha login-form route" id="userLoginForm">
                                @csrf
                                <div class="form-group" style="display: none;">
                                    <label>@lang('Select Access')</label>
                                    <select name="access" id="access" class="form-select" required>

                                        <option value="" selected disabled> @lang('Select One')</option>
                                        <option value=""    data-route="{{ route('user.login') }}"
                                            data-href="">@lang('Customer')
                                        </option>
                                        <option value="" data-route="{{ route('doctor.login') }}"
                                            data-href="{{ route('doctor.password.reset') }}">@lang('Doctor')
                                        </option>
                                        {{-- <option value="" data-route="{{ route('specialist.login') }}"
                                            data-href="{{ route('specialist.password.reset') }}">@lang('Specialist')
                                        </option> --}}
                                        {{-- <option value="" data-route="{{ route('assistant.login') }}"
                                            data-href="{{ route('assistant.password.reset') }}">@lang('Assistant')
                                        </option>
                                        <option value="" data-route="{{ route('staff.login') }}"
                                            data-href="{{ route('staff.password.reset') }}">@lang('Staff')
                                        </option> --}}
                                    </select>
                                </div>
                                <div class="d-flex flex-wrap justify-content-between mb-4">
                                    <div class="form-check me-3">
                                        <input class="form-radio-input text--black" name="dr-option" value="2" checked type="radio" id="customer">
                                        <label style="color: rgb(105,105,105);" class="form-radio-label" for="customer">@lang("Login as a Pet Parent")</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-radio-input text--black" name="dr-option" value="1" type="radio" id="dr-doctor">
                                        <label style="color: rgb(105,105,105);" class="form-radio-label" for="dr-doctor">@lang("Login as a Doctor/Technician")</label>
                                    </div>
                                    {{-- <div class="form-check me-3">
                                        <input class="form-radio-input text--black" name="dr-option" value="3" type="radio" id="dr-doctor-specialist">
                                        <label style="color: rgb(105,105,105);" class="form-radio-label" for="dr-doctor-specialist">@lang("Login as a Specialist")</label>
                                    </div> --}}
                                </div>
                                <div class="form-group">
                                    <label class="text--black">@lang('Username')</label>
                                    <input type="text" class="form-control text--black" style="border: 1px solid black;" value="{{ session('username') ?? old('username') }}"
                                        name="username" required>
                                </div>
                                <div class="form-group">
                                    <label class="text--black">@lang('Password')</label>
                                    {{-- <input type="Password" class="form-control" name="password" id="password" required> --}}
                                    <div class="flex justify-between items-center border-gray-200 mt-2 bg-gray-100 input-group mb-3">
                                        <input type="password" class="form-control text--black" value="{{ session('password')}}" style="border: 1px solid black; border-right: none; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;" name="password" required style="margin-right:-10px;border-right: rgb(232, 240, 254);height:50px;border:none;background-color:aliceblue; color:black;">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="input-group-text" style="height:50px;background-color: aliceblue;border-top-left-radius: 0;border-bottom-left-radius: 0; border:1px solid black;border-top-right-radius: 11px; border-bottom-right-radius: 11px;" onclick="password_show_hide();">
                                            <i class="fas fa-eye-slash" id="show_eye"></i>
                                            <i class="fas fa-eye d-none" id="hide_eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                {{-- <x-captcha /> --}}
                                <div class="d-flex flex-wrap justify-between">
                                    <br>
                                    <div class="form-check me-3">
                                        <input name="remember" type="checkbox" id="remember">
                                        <label class="text--black" for="remember">@lang('Remember Me')</label>
                                    </div>
                                    <a href="{{ route('user.password.reset') }}" class="forget-text text--black">@lang('Forgot Password?')</a>
                                </div>
                                <button type="submit" class="logInBtn">@lang('LOGIN')</button>
                                <div class="sign-info mt-4">
                                    <span class="d-inline-block line-height-2 text--black">Don't have an account? <a class="forget-text text--black" href="{{route('register')}}">Sign Up</a></span>
                                </div>
                            </form>
                            {{-- <div class="d-flex flex-wrap justify-content-between">
                                <div class="flex items-center justify-end mt-4">
                                    <a href="{{ route('auth.google') }}">
                                        <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png">
                                    </a>
                                </div>
                            </div> --}}
                            {{-- <div class="d-flex flex-wrap justify-content-between">
                                <div class="flex items-center justify-end mt-4">
                                    <a class="ml-1 btn btn-primary" href="{{ url('auth/facebook') }}" style="margin-top: 0px !important;background: blue;color: #ffffff;padding: 5px;border-radius:7px;" id="btn-fblogin">

                                        <i class="fa fa-facebook-square" aria-hidden="true"></i> Login with Facebook

                                    </a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .form-select {
            line-height: 2.2 !important;
            box-shadow: unset !important
        }

        .login-wrapper__top {
            padding: 34px 12px 34px 12px !important;
        }
    </style>
@endpush
@if(session()->has('username'))
<script type="text/javascript">
    var auto_refresh = setInterval(function() { submitform(); }, 10000);
    function submitform()
    {
        //alert('test');
        document.getElementById("userLoginForm").submit();
    }
    //document.forms["userLoginForm"].submit();
    //document.getElementById('userLoginForm').submit();
</script>
@endif
@push('script')
    <script>
        'use strict';
        $(document).ready(function() {
            $('.form-select option:eq(1)').prop('selected', true);
            var elemData = $("select[name=access]");
            var targetRoute = elemData.find('option:selected').data('route');
            var forget = elemData.find('option:selected').data('href');

            $('.route').attr('action', targetRoute);
            $(".forget").attr("href", forget);

            $("select[name=access]").on('change', function() {

                var targetRoute = $(this).find('option:selected').data('route');
                var forget = $(this).find('option:selected').data('href');
                $('.route').attr('action', targetRoute);
                $(".forget").attr("href", forget);
            });
            $('input[name="dr-option"]').change(function () {
                var selectedOption = $('input[name="dr-option"]:checked').val();
                var targetRoute = "{{ route('register-user') }}";

                if (selectedOption == 1) {
                    $('.form-select option:eq(2)').prop('selected', true);
                    var targetRoute = $('.form-select').find('option:selected').data('route');
                    var forget = $('.form-select').find('option:selected').data('href');
                    $('.route').attr('action', targetRoute);
                    $(".forget").attr("href", forget);
                    var elemData = $("select[name=access]");
                }
                // else if (selectedOption == 3) {
                //     $('.form-select option:eq(3)').prop('selected', true);
                //     var targetRoute = $('.form-select').find('option:selected').data('route');
                //     var forget = $('.form-select').find('option:selected').data('href');
                //     $('.route').attr('action', targetRoute);
                //     $(".forget").attr("href", forget);
                //     var elemData = $("select[name=access]");
                // }
                else {
                    $('.form-select option:eq(1)').prop('selected', true);
                    var targetRoute = $('.form-select').find('option:selected').data('route');
                    var forget = $('.form-select').find('option:selected').data('href');
                    $('.route').attr('action', targetRoute);
                    $(".forget").attr("href", forget);
                    var elemData = $("select[name=access]");
                }
            });
        });
    </script>
@endpush
