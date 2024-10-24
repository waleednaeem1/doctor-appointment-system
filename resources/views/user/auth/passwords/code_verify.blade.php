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
                            <div class="">
                                <h2 class="text-black text-center mb-2">@lang('Verify Code')</h2>
                                <p class="text-black mb-2">@lang('Please check your email and enter the verification code you got in your email.')</p>
                            </div>
                            <form action="{{ route('user.password.verify.code') }}" method="POST" class="cmn-form verify-gcaptcha login-form">
                                @csrf
                                <div class="code-box-wrapper d-flex w-100">
                                    <div class="form-group mb-3 flex-fill">
                                        <span class="text-black fw-bold">@lang('Verification Code')</span>
                                        <div class="verification-code">
                                            <input type="text" name="code" class="overflow-hidden" autocomplete="off">
                                            <div class="boxes">
                                                <span>-</span>
                                                <span>-</span>
                                                <span>-</span>
                                                <span>-</span>
                                                <span>-</span>
                                                <span>-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="email" value="{{$email}}">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <a href="{{ route('user.email.resend', ['email' => $email]) }}" class="forget-text">@lang('Try to send again')</a>
                                </div>
                                <button type="submit" class="btn logInBtn w-100 mt-4">@lang('Submit')</button>
                            </form>
                            <a href="{{ route('login') }}" class="text-black mt-4"><i class="las la-sign-in-alt" aria-hidden="true"></i>@lang('Back to Login')</a>
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

@push('script')
    <script>
        (function($){
            'use strict';
            $('[name=code]').on('input', function () {

                $(this).val(function(i, val){
                    if (val.length >= 6) {
                        $('form').find('button[type=submit]').html('<i class="las la-spinner fa-spin"></i>');
                        $('form').find('button[type=submit]').removeClass('disabled');
                        $('form')[0].submit();
                    }else{
                        $('form').find('button[type=submit]').addClass('disabled');
                    }
                    if(val.length > 6){
                        return val.substring(0, val.length - 1);
                    }
                    return val;
                });

                for (let index = $(this).val().length; index >= 0 ; index--) {
                    $($('.boxes span')[index]).html('');
                }
            });

        })(jQuery)
    </script>
@endpush
