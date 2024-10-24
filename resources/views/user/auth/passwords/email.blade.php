@extends('doctor.layouts.master')
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
                            <h3 class="title text-black text-center mb-4">@lang('Recover Account')</h3>
                            <form action="{{ route('user.password.reset') }}" method="POST" class="cmn-form verify-gcaptcha login-form route">
                                @csrf
                                <div class="d-flex flex-wrap justify-content-between mb-4">
                                    <div class="form-check me-3">
                                        <input class="form-radio-input text--black" name="dr-option" value="2" checked type="radio" id="customer">
                                        <label style="color: rgb(105,105,105);" class="form-radio-label" for="customer">@lang("I'm a Pet Parent")</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-radio-input text--black" name="dr-option" value="1" type="radio" id="dr-doctor">
                                        <label style="color: rgb(105,105,105);" class="form-radio-label" for="dr-doctor">@lang("I'm a Doctor/Technician")</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="text--black">@lang('Email')</label>
                                    <input type="email" class="form-control text--black" style="border: 1px solid black;" value="{{ old('email') }}"
                                        name="email" required>
                                </div>
                                <x-captcha />
                                <div class="d-flex flex-wrap justify-content-between">
                                    <a href="{{ route('login') }}" class="forget-text">@lang('Login Here')</a>
                                </div>
                                <button type="submit" class="logInBtn">@lang('Submit')</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('input[name="dr-option"]').change(function () {
                var selectedOption = $('input[name="dr-option"]:checked').val();
                var targetRoute = "{{ route('register-user') }}";

                if (selectedOption == 1) {
                    targetRout = "{{ route('doctor.password.reset') }}";
                    $('.route').attr('action', targetRout);
                } else {
                    targetRout = "{{ route('user.password.reset') }}";
                    $('.route').attr('action', targetRout);
                }
            });
    </script>
@endsection


