@extends('staff.layouts.master')
@section('content')
    <div class="login-main" style="background-image: url('{{ asset('assets/admin/images/login.jpg') }}')">
        <div class="container custom-container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-8 col-sm-11">
                    <div class="login-area">
                        <div class="login-wrapper">
                            <div class="login-wrapper__top">
                                <a class="site-logo site-title" href="{{ route('home') }}"><img
                                        src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="logo"></a>
                                <h4 class="title text-white mt-1">@lang('Welcome to')
                                    <strong>{{ __($general->site_name) }}</strong>
                                </h4>

                            </div>
                            <div class="login-wrapper__body">
                                <form method="POST" class="cmn-form verify-gcaptcha login-form route">
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
                                            <input class="form-radio-input" name="dr-option" value="2" checked type="radio" id="customer">
                                            <label class="form-radio-label" for="customer">@lang("Login As a Customer")</label>
                                        </div>
                                        <div class="form-check me-3">
                                            <input class="form-radio-input" name="dr-option" value="1" type="radio" id="dr-doctor">
                                            <label class="form-radio-label" for="dr-doctor">@lang("Login As a Doctor/Technician")</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Username')</label>
                                        <input type="text" class="form-control" value="{{ old('username') }}"
                                            name="username" required>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Password')</label>
                                        {{-- <input type="Password" class="form-control" name="password" id="password" required> --}}
                                        <div class="flex justify-between items-center border-gray-200 mt-2 bg-gray-100 input-group mb-3">
                                            <input type="password" class="form-control" name="password" required style="margin-right:-10px;border-right: rgb(232, 240, 254);height:50px;border:none;background-color:aliceblue; color:black;">
                                            <div class="flex justify-between items-center  mb-3">
                                                <span class="input-group-text" style="height:50px;background-color: aliceblue;border-top-left-radius: 0;border-bottom-left-radius: 0;" onclick="password_show_hide();">
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
                                            <input class="form-check-input" name="remember" type="checkbox" id="remember">
                                            <label class="form-check-label" for="remember">@lang('Remember Me')</label>
                                        </div>
                                        <a href="{{ route('user.password.reset') }}" class="forget-text ">@lang('Forgot Password?')</a>
                                    </div>
                                    <div class="sign-info mt-4">
                                        <span class="d-inline-block line-height-2" style="color: white !important;">Don't have an account? <a class="forget-text" href="{{route('register')}}">Sign Up</a></span>
                                    </div>
                                    <button type="submit" class="btn cmn-btn w-100">@lang('LOGIN')</button>
                                </form>
                            </div>
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

            // function submitUserForm() {
            //     var response = grecaptcha.getResponse();
            //     if (response.length == 0) {
            //         document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">@lang('Captcha field is required.')</span>';
            //         return false;
            //     }
            //     return true;
            // }
            // function verifyCaptcha() {
            //     document.getElementById('g-recaptcha-error').innerHTML = '';
            // }
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
                } else {
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

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link


      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href= 
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Find Veterinarians and Practices Near You - Login</title>
<style>
        /* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
*/
:root {
  --white: #fff;
}
@import url('https://fonts.googleapis.com/css2?family=ADLaM+Display&family=Arizonia&family=Montserrat&family=Open+Sans:ital,wght@0,700;1,700&family=Pacifico&family=Permanent+Marker&family=Rubik+Iso&family=Space+Grotesk:wght@300&display=swap');
html,
body,
div,
span,
applet,
object,
iframe,
h1,
h2,
h3,
h4,
h5,
h6,
p,
blockquote,
pre,
a,
abbr,
acronym,
address,
big,
cite,
code,
del,
dfn,
em,
img,
ins,
kbd,
q,
s,
samp,
small,
strike,
strong,
sub,
sup,
tt,
var,
b,
u,
i,
center,
dl,
dt,
dd,
ol,
ul,
li,
fieldset,
form,
label,
legend,
table,
caption,
tbody,
tfoot,
thead,
tr,
th,
td,
article,
aside,
canvas,
details,
embed,
figure,
figcaption,
footer,
header,
hgroup,
menu,
nav,
output,
ruby,
section,
summary,
time,
mark,
audio,
video {
  margin: 0;
  padding: 0;
  border: 0;
  font-size: 100%;
  font: inherit;
  vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
menu,
nav,
section {
  display: block;
}
body {
  line-height: 1;
  font-family: 'Montserrat', sans-serif;
}
ol,
ul {
  list-style: none;
}
blockquote,
q {
  quotes: none;
}
blockquote:before,
blockquote:after,
q:before,
q:after {
  content: "";
  content: none;
}
table {
  border-collapse: collapse;
  border-spacing: 0;
}

.background {
  background: url("/assets/images/logoIcon/images/background.jpg");
  background-repeat: no-repeat;
  background-attachment: fixed;
  width: 100%;
  height: 100vh;
}
.container1 {
  display: flex;
}
.span {
  font-size: 14px;
  color: #737373;
  margin-top: -2px;
}
.image {
  display: flex;
  max-width: 43%;
  height: 100vh;
  align-items: center;
  justify-content: flex-end;
}
.contaniner_image {
  width: 72%;
  justify-content: center;
  align-items: center;
  display: flex;
}
.form {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: center;
  width: 45%;
}
.inner_form {
  background: var(--white);
  height: 31rem;
  width: 80%;
  border-radius: 23px;
}
.mylogo {
  margin-top: -53px;
}
.logo {
  width: 100%;
}
.logo_heading {
  text-align: center;
  text-transform: uppercase;
  color: white;
  margin-top: -5.9rem;
  letter-spacing: 2px;
  line-height: 23px;
  font-size: 0.9vw;
  font-weight: bolder;
}
.forms {
  margin-top: 3.5rem;
  display: flex;
  flex-direction: column;

  justify-content: center;
}
.checkbox {
  display: flex;
  align-items: center;
  justify-content: center;
}
.check[type="radio"] {
  margin: 10px;
  margin-left: 20px;
  box-sizing: border-box;
  appearance: none;
  background: white;
  outline: 2px solid #737373;
  border: 1.5px solid white;
  width: 11px;
  height: 11px;
  border-radius: 100px;
}
.check[type="radio"]:checked{
  background-color: #6f73de;
}
.q[type="radio"] {
  margin: 10px;
  margin-left: 2rem;
  box-sizing: border-box;
  appearance: none;
  background: white;
  outline: 2px solid #737373;
  border: 1.5px solid white;
  width: 11px;
  height: 11px;
  border-radius: 100px;
}
.q[type="radio"]:checked{
  background-color: #6f73de;
}
.inner_inputs {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.inner_inputs input {
  width: 80%;
  border: 0.5px solid grey;
  border-radius: 25px;
  padding: 0.7rem 2.1rem;
  margin-top: 1rem;
  /* height: 3rem; */
}

.forgot {
  margin-top: 1.1rem;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-around;
  /* padding-right: 30px; */
  color: #737373;
}
.forgot a {
  padding-left: 1.9rem;
  color: #737373;
}
.button {
  margin-top: 1rem;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.btn {
  color: var(--white);
  width: 80%;
  background: linear-gradient(to right, #a740cd, #6f73de);
  border-radius: 250px;
}
.btn:hover {
  color: var(--white);
}
.last {
  width: 100%;
  height: 6vh;
  display: flex;
  align-items: flex-end;
  margin-left: 3.7rem;
  
  color: #737373;
}

a {
  text-decoration: none;
  color: #737373;
}
.icon {
  display: flex;
  width: 27.2%;
  margin-top: 1.8rem;
  position: absolute;
}

.lock {
  display: flex;
  width: 27.2%;
  margin-top: 5.5rem;
  position: absolute;
}

.btnn {
  position: absolute;
  margin-top: 0px;
  margin-left: -5rem;
}
.form-select {
    line-height: 2.2 !important;
    box-shadow: unset !important
}

.login-wrapper__top {
    padding: 34px 12px 34px 12px !important;
}
</style>
  </head>
  <body>
    <div class="background">
      <div class="container1">
        <div class="image">
          <img src="/assets/images/logoIcon/images/signin-vector.png" alt="" class="contaniner_image" />
        </div>
        <div class="form">
          <div class="inner_form">
            <div class="mylogo">
              <img src="/assets/images/logoIcon/images/logo.png" alt="This is a logo" class="logo" />
              <h1 class="logo_heading">
                Welcome to find <br />
                Veterinarians and practicee near you
              </h1>
            </div>
            <div class="forms">

              <form method="POST" class="route" action="/user">
                @csrf
                <div class="checkbox">
                    <input class="check" name="dr-option" value="2" checked type="radio" id="customer">
                    <label class="form-radio-label" for="customer">@lang("Login As a Customer")</label>
                    <input class="check" name="dr-option" value="1" type="radio" id="dr-doctor">
                    <label class="form-radio-label" for="dr-doctor">@lang("Login As a Veterinarian/Technician")</label>
                </div>
                <div class="inner_inputs">
                  <i class="fa fa-user icon" style="color: #000000"></i>
                  <input
                    type="text"
                    class="input"
                    value="{{ old('username') }}"
                    name="username"
                    placeholder="Username"
                    autocomplete="off"required
                    />
                  <i class="fa fa-lock lock" style="color: #000000"></i>
                  <input
                    type="password"
                    class="input"
                    name="password"
                    placeholder="Password"
                    autocomplete="off"
                  />
                </div>
                <div class="forgot">
                    <div>
                        <input type="checkbox" class="checking" placeholder="1" name="remember" id="remember"/>
                        <label for="remember">Remember Me</label>
                    </div>
                  <a href="{{ route('user.password.reset') }}">Forgot Password</a>
                </div>
                <div class="button">
                  <button type="submit" class="btn">Login</button>
                  <i class="fa fa-user btnn" style="color: #ffffff"></i>
                </div>
              </form>
              <p class="last">Don't have an Account?<a href="/register"> Sign Up</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('input[name="dr-option"]').change(function () {
            var selectedOption = $('input[name="dr-option"]:checked').val();
            var targetRoute = "/user";

            if (selectedOption == 1) {
                targetRoute = "/doctor";
                $('.route').attr('action', targetRoute);
            } else {
                targetRoute = "/user";
                $('.route').attr('action', targetRoute);
            }
        });
    </script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"
    ></script>
  </body>
</html>


@extends('staff.layouts.master')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="login-main" style="background-image: url('{{ asset('assets/admin/images/login.jpg') }}')">
        <div class="container custom-container">
            <div class="row justify-content-center">
                <div class="col-xxl-7 col-xl-7 col-lg-6 col-md-8 col-sm-11">
                    <div class="login-area">
                        <div class="login-wrapper">
                            <div class="login-wrapper__top">
                                <a class="site-logo site-title" href="{{ route('home') }}"><img
                                        src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="logo"></a>
                                <h4 class="title text-white mt-1">@lang('Welcome to')
                                    <strong>{{ __($general->site_name) }}</strong>
                                </h4>

                            </div>
                            <div class="login-wrapper__body">
                                <form method="POST" action="{{ route('register-user') }}" class="cmn-form verify-gcaptcha login-form route" id="register">
                                    @csrf
                                    <div class="d-flex flex-wrap justify-content-between mb-4">
                                        <div class="form-check me-3">
                                            <input class="form-radio-input" name="dr-option" value="2" checked type="radio" id="customer">
                                            <label class="form-radio-label" for="customer">@lang("Sign Up as Customer")</label>
                                        </div>
                                        <div class="form-check me-3">
                                            <input class="form-radio-input" name="dr-option" value="1" type="radio" id="dr-doctor">
                                            <label class="form-radio-label" for="dr-doctor">@lang("Sign Up as Veterinarian/Technician")</label>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <label>@lang('Name')</label>
                                            <input type="text" class="form-control" value="{{ old('name') }}" name="name" required>
                                        </div>
                                        <div class="col">
                                            <label>@lang('Username')</label>
                                            <input type="text" name="username" value="{{ old('username') }}"
                                                class="form-control " required id="username-name" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <label>@lang('Email')</label>
                                            <input type="text" class="form-control" value="{{ old('email') }}" name="email" id="email" oninput="validateemail(this)" required>
                                            <p class="text-danger"></p>
                                        </div>

                                    </div>



                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="phone" class="form-label">Phone No</label>
                                            <input type="text" id="phoneNumber" class="form-control phone"   name="phone_number" value="{{old('phone_number')}}" maxlength="10"  required autocomplete="nope">
                                        </div>
                                        <div class="col">
                                        <label>@lang('Postal Code')</label>
                                        <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                            class="form-control " required />
                                        </div>
                                    </div>


                                    <div class="row d-none location-pannel" class="">
                                        <div class="col">
                                            <div class="form-group" id="select2-wrapper-country">
                                                <label>@lang('Country')</label>
                                                <select class="form-select" name="country_id" id="countrys_id" required>
                                                    <option disabled selected>@lang('Select One')</option>
                                                    @foreach ($countries as $country)
                                                        <option  value="{{$country->id }}">
                                                            {{ __($country->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group" id="select2-wrapper-state">
                                                <label>@lang('State')</label>
                                                <select class="form-select" name="state_id" disabled required id="states">
                                                    <option disabled selected>@lang('Select Country')</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row d-none location-pannel" class="">
                                        <div class="col">
                                            <div class="form-group" id="select2-wrapper-city">
                                                <label>@lang('City')</label>
                                                <select class="form-select" required disabled name="city_id" id="cities">
                                                    <option disabled selected>@lang('Select One')</option>
                                                    @foreach ($cities as $city)
                                                        <option  value="{{$city->id }}">
                                                            {{ __($city->city_name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label d-block">Gender</label>
                                            <div class="form-check form-check-inline my-2">
                                                <input class="form-check-input" style="margin-top: 4px;" required type="radio" name="gender" id="inlineRadio10" value="male" @if (old('gender') == "male") {{ 'checked' }} @endif>
                                                <label class="form-check-label" for="inlineRadio10"> Male</label>
                                            </div>
                                            <div class="form-check form-check-inline my-2">
                                                <input class="form-check-input" style="margin-top: 4px;" type="radio" name="gender" id="inlineRadio11" value="female" @if (old('gender') == "female") {{ 'checked' }} @endif>
                                                <label class="form-check-label" for="inlineRadio11">Female</label>
                                            </div>
                                            <div class="form-check form-check-inline my-2">
                                                <input class="form-check-input" style="margin-top: 4px;" type="radio" name="gender" id="inlineRadio12" value="Don’t want to specify" @if (old('gender') == "Don’t want to specify") {{ 'checked' }} @endif>
                                                <label class="form-check-label" for="inlineRadio12">Don’t want to specify</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>@lang('Password')</label>
                                                <div class="flex justify-between items-center border-gray-200 mt-2 bg-gray-100 input-group mb-3">
                                                    <input type="password" class="form-control" name="password" required style="margin-right:-10px;border-right: rgb(232, 240, 254);height:50px;border:none;background-color:aliceblue; color:black;">
                                                    <div class="flex justify-between items-center  mb-3">
                                                        <span class="input-group-text" style="height:50px;background-color: aliceblue;border-top-left-radius: 0;border-bottom-left-radius: 0; border-top-right-radius:10px; border-bottom-right-radius:10px;" onclick="password_show_hide();">
                                                        <i class="fas fa-eye-slash" id="show_eye"></i>
                                                        <i class="fas fa-eye d-none" id="hide_eye"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label>@lang('Confirm Password')</label>
                                                <div class="flex justify-between items-center border-gray-200 mt-2 bg-gray-100 input-group mb-3">
                                                        <input type="password" class="form-control" name="password_confirmation" required style="margin-right:-10px;border-right: rgb(232, 240, 254);height:50px;border:none; background-color:aliceblue;color:black;">
                                                        <div class="flex justify-between items-center  mb-3">
                                                            <span class="input-group-text" style="height:50px;background-color: aliceblue;border-top-left-radius: 0;border-bottom-left-radius: 0; border-top-right-radius:10px; border-bottom-right-radius:10px;" onclick="confirm_password_show_hide();">
                                                            <i class="fas fa-eye-slash" id="show_eye1"></i>
                                                            <i class="fas fa-eye d-none" id="hide_eye1"></i>
                                                            </span>
                                                       </div>
                                                </div>
                                            </div>
                                        </div>
                                    <x-captcha />
                                    {{-- <div class="d-flex flex-wrap justify-content-between">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" name="remember" type="checkbox" id="remember">
                                            <label class="form-check-label" for="remember">@lang('Remember Me')</label>
                                        </div>
                                        <a class="forget-text forget">@lang('Forgot Password?')</a>
                                    </div> --}}
                                    </div>
                                    <div class="sign-info mt-4">
                                        <span class="d-inline-block line-height-2" style="color: white !important;">Already have an account? <a class="forget-text" href="{{route('login')}}">Login</a></span>
                                    </div>
                                    <button type="submit" class="btn cmn-btn w-100">@lang('Sign Up')</button>
                                </form>
                            </div>
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
@push('script')
    <script>
        'use strict';
        //validation for email field
        function validateemail(input) {
            var emailInput = document.getElementById('email');
        if(emailInput.value.length==0){
            emailInput.classList.add("border-danger");
            emailInput.nextElementSibling.innerHTML="Please enter valid email address!";
            return false;
        }else{
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (!mailformat.test((emailInput.value))) {
                emailInput.classList.add("border-danger");
                emailInput.nextElementSibling.innerHTML="You have entered an invalid email address!";
                return false;
            }
            else{
                emailInput.classList.remove("border-danger");
                emailInput.nextElementSibling.innerHTML="";
            }
        }
    }
        // $(document).ready(function() {
        //     var elemData = $("select[name=access]");

        //     var targetRoute = elemData.find('option:selected').data('route');
        //     var forget = elemData.find('option:selected').data('href');
        //     $('.route').attr('action', targetRoute);
        //     $(".forget").attr("href", forget);

        //     $("select[name=access]").on('change', function() {
        //         var targetRoute = $(this).find('option:selected').data('route');
        //         var forget = $(this).find('option:selected').data('href');
        //         $('.route').attr('action', targetRoute);
        //         $(".forget").attr("href", forget);
        //     });

        //     function submitUserForm() {
        //         var response = grecaptcha.getResponse();
        //         if (response.length == 0) {
        //             document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">@lang('Captcha field is required.')</span>';
        //             return false;
        //         }
        //         return true;
        //     }
        //     function verifyCaptcha() {
        //         document.getElementById('g-recaptcha-error').innerHTML = '';
        //     }
        // });

        $('.phone').on('input', function(event) {
            var input = event.target.value;
            var regex = /[^a-zA-Z0-9]/g;
            if (regex.test(input)) {
                event.target.value = input.replace(regex, '');
            }
            $(this).val(formatPhoneNumber($(this).val()));
        });

        //check For Route
        $('input[name="dr-option"]').change(function () {
        var selectedOption = $('input[name="dr-option"]:checked').val();
        var targetRoute = "{{ route('register-user') }}";

        if (selectedOption == 1) {
            targetRoute = "{{ route('register-doctor') }}";
            $('.route').attr('action', targetRoute);
            $('#states').attr('disabled', false);
            $('#cities').attr('disabled', false);
            $('.location-pannel').removeClass('d-none');
        } else {
            targetRoute = "{{ route('register-user') }}";
            $('.route').attr('action', targetRoute);
            $('#states').attr('disabled', true);
            $('#cities').attr('disabled', true);
            $('.location-pannel').addClass('d-none');
        }
    });
    window.onpageshow = function(event) {
        if (event.persisted) {
            document.getElementById("register").reset();
        }
    };
    </script>
@endpush
