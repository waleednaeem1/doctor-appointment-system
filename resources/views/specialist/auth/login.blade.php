@extends('doctor.layouts.master')
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
                        <h3 class="title text-black text-center">@lang('Welcome to') <strong>{{ __($general->site_name) }}</strong></h3>
                            <p class="text-black text-center">{{ __($pageTitle) }} @lang('to') {{ __($general->site_name) }}
                                @lang('Dashboard')</p>
                        <form action="{{ route('doctor.login') }}" method="POST" class="cmn-form verify-gcaptcha login-form route">
                            @csrf
                            <div class="form-group">
                                <label class="text--black">@lang('Username')</label>
                                <input type="text" class="form-control text--black" style="border: 1px solid black;" value="{{ old('username') }}"
                                    name="username" required>
                            </div>
                            <div class="form-group">
                                <label class="text--black">@lang('Password')</label>
                                <div class="flex justify-between items-center border-gray-200 mt-2 bg-gray-100 input-group mb-3">
                                    <input type="password" class="form-control text--black" style="border: 1px solid black; border-right: none; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;" name="password" required style="margin-right:-10px;border-right: rgb(232, 240, 254);height:50px;border:none;background-color:aliceblue; color:black;">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="input-group-text" style="height:50px;background-color: aliceblue;border-top-left-radius: 0;border-bottom-left-radius: 0; border:1px solid black;border-top-right-radius: 11px; border-bottom-right-radius: 11px;" onclick="password_show_hide();">
                                        <i class="fas fa-eye-slash" id="show_eye"></i>
                                        <i class="fas fa-eye d-none" id="hide_eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <x-captcha />
                            <div class="d-flex flex-wrap justify-between">
                                <br>
                                <div class="form-check me-3">
                                    <input name="remember" type="checkbox" id="remember">
                                    <label class="text--black" for="remember">@lang('Remember Me')</label>
                                </div>
                                <a href="{{ route('doctor.password.reset') }}" class="forget-text text--black">@lang('Forgot Password?')</a>
                            </div>
                            <button type="submit" class="logInBtn">@lang('LOGIN')</button>
                        </form>
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
