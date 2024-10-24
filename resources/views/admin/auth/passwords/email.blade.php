@extends('admin.layouts.master')
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
                            <form action="{{ route('admin.password.reset') }}" method="POST" class="cmn-form verify-gcaptcha login-form route">
                                @csrf
                                <div class="form-group">
                                    <label class="text--black">@lang('Email')</label>
                                    <input type="email" class="form-control text--black" style="border: 1px solid black;" value="{{ old('email') }}"
                                        name="email" required>
                                </div>
                                <x-captcha />
                                <div class="d-flex flex-wrap justify-content-between">
                                    <a href="{{ route('admin.login') }}" class="forget-text">@lang('Login Here')</a>
                                </div>
                                <button type="submit" class="logInBtn">@lang('Submit')</button>

                                {{-- <button type="submit" class="logInBtn">@lang('LOGIN')</button>
                                <div class="sign-info mt-4">
                                    <span class="d-inline-block line-height-2 text--black">Don't have an account? <a class="forget-text text--black" href="{{route('register')}}">Sign Up</a></span>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection


