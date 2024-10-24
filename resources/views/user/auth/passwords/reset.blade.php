@extends('user.layouts.master')
@section('content')
    <div class="login-main-custom" style="background-image: url('{{ asset('assets/images/logoIcon/images/background.jpg') }}'); background-color:none;">
        <div class="container custom-container">
            <div class="row">
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-8 col-sm-11 d-flex align-items-center justify-content-center" id="hideonmobile">
                    <img src="/assets/images/logoIcon/images/signin-vector.png" alt="" style="position: fixed; width: 25%; top: 10rem;"/>
                </div>
                <div class="align-items-center col-lg-6 col-md-8 col-sm-11 col-xl-6 col-xxl-6 d-flex">
                    <div class="">
                        <div class="mylogo">
                            <img src="/assets/images/logoIcon/images/logo.png" alt="This is a logo" class="logo" />
                            <h1 class="logo_heading">
                                Welcome to find <br />
                                Doctors and practicee near you
                            </h1>
                        </div>
                        <div class="login-wrapper__body bg-white" style="border-bottom-right-radius: 23px !important; border-bottom-left-radius: 23px !important;">
                            <h3 class="title text-black text-center">@lang('Recover Account')</h3>
                            <form action="{{ route('user.password.change') }}" method="POST" class="cmn-form verify-gcaptcha login-form">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-group">
                                    <label class="text--black">@lang('Username')</label>
                                    <input type="text" class="form-control text--black" style="border: 1px solid black;" value="{{ $username }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="text--black">@lang('New Password')</label>
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
                                <div class="form-group">
                                    <label class="text--black">@lang('Re-type New Password')</label>
                                    <div class="flex justify-between items-center border-gray-200 mt-2 bg-gray-100 input-group mb-3">
                                        <input type="password" class="form-control text--black" style="border: 1px solid black; border-right: none; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;" name="password_confirmation" required style="margin-right:-10px;border-right: rgb(232, 240, 254);height:50px;border:none;background-color:aliceblue; color:black;">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="input-group-text" style="height:50px;background-color: aliceblue;border-top-left-radius: 0;border-bottom-left-radius: 0; border:1px solid black;border-top-right-radius: 11px; border-bottom-right-radius: 11px;" onclick="confirm_password_show_hide();">
                                            <i class="fas fa-eye-slash" id="show_eye1"></i>
                                            <i class="fas fa-eye d-none" id="hide_eye1"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap justify-content-between">
                                    <a href="{{ route('login') }}" class="forget-text text-black">@lang('Login Here')</a>
                                </div>
                                <button type="submit" class="logInBtn">@lang('Submit')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/verification_code.css') }}">
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

