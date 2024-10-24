@extends('staff.layouts.master')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="login-main-custom" style="background-image: url('{{ asset('assets/images/logoIcon/images/background.jpg') }}');">
        <div class="container custom-container">
            <div class="row">
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-8 col-sm-11 d-flex justify-content-center" id="hideonmobile">
                    <img src="/assets/images/logoIcon/images/signup-vector.png" alt="" style="position: fixed; width: 25%; top: 10rem;"/>
                </div>
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-8 col-sm-11">
                    <div class="login-area">
                        <div class="mylogo">
                            <img src="/assets/images/logoIcon/images/logo.png" alt="This is a logo" class="logo" />
                            <h1 class="logo_heading">
                                Welcome to find <br />
                                Doctors and practicee near you
                            </h1>
                        </div>
                        <div class="login-wrapper__body bg-white" style="border-bottom-right-radius: 23px !important; border-bottom-left-radius: 23px !important;">
                            <form method="POST" action="{{ route('register-user') }}" class="cmn-form verify-gcaptcha login-form route" onsubmit="return validateemail(this)" id="register" autocomplete="off">
                                @csrf
                                <div class="d-flex flex-wrap justify-content-between mb-4">
                                    <div class="form-check me-3">
                                        <input name="dr-option" value="2" checked type="radio" id="customer" class="text--black">
                                        <label style="color: rgb(105,105,105);" class="form-radio-label" for="customer">@lang("Sign Up as Pet Parent")</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input name="dr-option" value="1" type="radio" id="dr-doctor" class="text--black">
                                        <label style="color: rgb(105,105,105);" class="form-radio-label" for="dr-doctor">@lang("Sign Up as Doctor/Technician")</label>
                                    </div>
                                    {{-- <div class="form-check me-3">
                                        <input name="dr-option" value="3" type="radio" id="dr-doctor-specialist" class="text--black">
                                        <label style="color: rgb(105,105,105);" class="form-radio-label" for="dr-doctor-specialist">@lang("Sign Up as Specialist")</label>
                                    </div> --}}
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="text--black required">@lang('Name')</label>
                                        <input type="text" id="name" class="form-control text--black" style="border: 1px solid black;" value="{{ old('name') }}" name="name" autocomplete="off">
                                        <p class="text-danger"></p>
                                    </div>
                                    <div class="col">
                                        <label class="text--black required">@lang('Username')</label>
                                        <input type="text"  name="username" style="border: 1px solid black;" value="{{ old('username') }}" autocomplete="new-username" class="form-control text--black" />
                                        <p class="text-danger"></p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="text--black required">@lang('Email')</label>
                                        <input type="text" class="form-control text--black" style="border: 1px solid black;" value="{{ old('email') }}" name="email" id="email" autocomplete="off">
                                        <p class="text-danger"></p>
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="phone" class="form-label text--black required">Phone No</label>
                                        <input type="text" id="phoneNumber" style="border: 1px solid black;" class="form-control phone text--black"   name="phone_number" value="{{old('phone_number')}}" maxlength="10" autocomplete="off">
                                        <p class="text-danger"></p>
                                    </div>
                                    {{-- <div class="col">
                                        <label class="text--black required">@lang('Postal Code')</label>
                                        <input type="text" name="postal_code" style="border: 1px solid black;" value="{{ old('postal_code') }}" autocomplete="off" class="form-control text--black"/>
                                        <p class="text-danger"></p>
                                    </div> --}}
                                </div>
                                {{-- <div class="row location-pannel" class="">
                                    <div class="col">
                                        <div class="form-group" id="select2-wrapper-country">
                                            <label class="text--black required">@lang('Country')</label>
                                            <select class="form-select text--black" style="border: 1px solid black;" name="country_id" id="countrys_id">
                                                <option disabled value="" selected>@lang('Select Country')</option>
                                                @foreach ($countries as $country)
                                                    <option  value="{{$country->id }}">
                                                        {{ __($country->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger"></p>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group" id="select2-wrapper-state">
                                            <label class="text--black required">@lang('State')</label>
                                            <select class="form-select text--black" style="border: 1px solid black;" name="state_id" id="states">
                                                <option value="" selected>@lang('Select Country First')</option>
                                            </select>
                                            <p class="text-danger"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row location-pannel" class="">
                                    <div class="col">
                                        <div class="form-group" id="select2-wrapper-city">
                                            <label class="text--black required">@lang('City')</label>
                                            <select class="form-select text--black" style="border: 1px solid black;" name="city_id" id="cities">
                                                <option value="" selected>@lang('Select City')</option>
                                                @foreach ($cities as $city)
                                                    <option  value="{{$city->id }}">
                                                        {{ __($city->city_name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger"></p>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="text--black required">@lang('Address')</label>
                                        <input type="text" id="address" class="form-control text--black" style="border: 1px solid black;" value="{{ old('address') }}" name="address" autocomplete="off" required>
                                        <p class="text-danger"></p>
                                    </div>
                                </div>
                                <input type="hidden" name="latitude" id="latitude" value="{{old('latitude')}}">
                                <input type="hidden" name="longitude" id="longitude" value="{{old('longitude')}}">
                                <input type="hidden" name="postal_code" id="postal_code" value="{{old('postal_code')}}">

                                <input type="hidden" name="country_id" id="country" value="{{old('country_id')}}">
                                <input type="hidden" name="state_id" id="state" value="{{old('state_id')}}">
                                <input type="hidden" name="city_id" id="city" value="{{old('city_id')}}">
                                
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label d-block text--black">Gender</label>
                                        <div class="form-check form-check-inline my-2">
                                            <input class="text--black" style="margin-top: 4px;" type="radio" name="gender" id="inlineRadio10" value="male" @if (old('gender') == "male") {{ 'checked' }} @endif>
                                            <label class="text--black" for="inlineRadio10"> Male</label>
                                        </div>
                                        <div class="form-check form-check-inline my-2">
                                            <input class="text--black" style="margin-top: 4px;" type="radio" name="gender" id="inlineRadio11" value="female" @if (old('gender') == "female") {{ 'checked' }} @endif>
                                            <label class="text--black" for="inlineRadio11">Female</label>
                                        </div>
                                        <div class="form-check form-check-inline my-2">
                                            <input class="text--black" style="margin-top: 4px;" type="radio" name="gender" id="inlineRadio12" value="Don’t want to specify" @if (old('gender') == "Don’t want to specify") {{ 'checked' }} @endif>
                                            <label class="text--black" for="inlineRadio12">Don’t want to specify</label>
                                        </div>
                                        <div id="genval"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="text--black required">@lang('Password')</label>
                                            <div class="flex justify-between items-center border-gray-200 mt-2 bg-gray-100 input-group">
                                                <input type="password" class="form-control text--black" style="border: 1px solid black; border-right: none; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;" name="password" style="margin-right:-10px;border-right: rgb(232, 240, 254);height:50px;border:none;background-color:aliceblue; color:black;" autocomplete="new-password">
                                                <div class="flex justify-between items-center">
                                                    <span class="input-group-text" style="height:50px;background-color: aliceblue;border-top-left-radius: 0;border-bottom-left-radius: 0; border:1px solid black;border-top-right-radius: 11px; border-bottom-right-radius: 11px;" onclick="password_show_hide();">
                                                        <i class="fas fa-eye-slash" id="show_eye"></i>
                                                        <i class="fas fa-eye d-none" id="hide_eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div id="passval"></div>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-group">
                                            <label class="text--black required">@lang('Confirm Password')</label>
                                            <div class="flex justify-between items-center border-gray-200 mt-2 bg-gray-100 input-group">
                                                    <input type="password" minlength="8" class="form-control text--black" style="border: 1px solid black; border-right: none; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;" name="password_confirmation" style="margin-right:-10px;border-right: rgb(232, 240, 254);height:50px;border:none; background-color:aliceblue;color:black;">
                                                    <div class="flex justify-between items-center">
                                                        <span class="input-group-text" style="height:50px;background-color: aliceblue;border-top-left-radius: 0;border-bottom-left-radius: 0; border:1px solid black;border-top-right-radius: 11px; border-bottom-right-radius: 11px;" onclick="confirm_password_show_hide();">
                                                        <i class="fas fa-eye-slash" id="show_eye1"></i>
                                                        <i class="fas fa-eye d-none" id="hide_eye1"></i>
                                                        </span>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                <x-captcha style="color: black;" />
                                {{-- <div class="d-flex flex-wrap justify-content-between">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" name="remember" type="checkbox" id="remember">
                                        <label class="form-check-label" for="remember">@lang('Remember Me')</label>
                                    </div>
                                    <a class="forget-text forget">@lang('Forgot Password?')</a>
                                </div> --}}
                                </div>
                                <div class="sign-info mt-4">
                                    <span class="d-inline-block line-height-2 text--black">Already have an account? <a class="forget-text text--black" href="{{route('login')}}">Login</a></span>
                                </div>
                                <button type="submit" class="logInBtn">@lang('Sign Up')</button>
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
        input#captcha {
            color: #000 !important;
        }

        label.form-label.required {
            color: #000 !important;
        }

        input#captcha {
            border: 1px solid #000;
        }
    </style>
@endpush
@push('script')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-r86tO9gWZxzRiELiw3DQYa2D3_o1CVk&libraries=places"></script>
    <script>
        'use strict';
        //validation for email field
        function validateemail(form) {
            // $("html, body").animate({scrollTop: 230}, 300);
            if(form['name'].value.length==0){
                form['name'].focus();
                form['name'].nextElementSibling.innerHTML="Please enter name";
                form['name'].classList.add("border-danger");
                return false;
            }else{
                if (form['name'].value.length < 2) {
                    console.log("not validate");
                    form['name'].focus();
                    form['name'].nextElementSibling.innerHTML="Name must be at least 2 characters long!";
                    return false;
                }
                else{
                    form['name'].classList.remove("border-danger");
                    form['name'].nextElementSibling.innerHTML="";
                }
            }
            if(form['username'].value.length==0){
                form['username'].focus();
                form['username'].nextElementSibling.innerHTML="Please enter username";
                form['username'].classList.add("border-danger");
                return false;
            }else{
                var usernameformat = /[^a-z0-9_]/g;
                if(form['username'].value.length < 2 || usernameformat.test((form['username'].value))) {
                    form['username'].focus();
                    form['username'].nextElementSibling.innerHTML = "Username must be 2 characters, allowing numbers, alphabets, and underscores.";
                    form['username'].classList.add("border-danger");
                    return false;
                }else{
                    form['username'].classList.remove("border-danger");
                    form['username'].nextElementSibling.innerHTML = "";
                }
            }
            var emailInput = document.getElementById('email');
            if(emailInput.value.length==0){
                emailInput.focus();
                emailInput.classList.add("border-danger");
                emailInput.nextElementSibling.innerHTML="Please enter valid email address!";
                return false;
            }else{
                var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if (!mailformat.test((emailInput.value))) {
                    emailInput.focus();
                    emailInput.classList.add("border-danger");
                    emailInput.nextElementSibling.innerHTML="You have entered an invalid email address!";
                    return false;
                }
                else{
                    emailInput.classList.remove("border-danger");
                    emailInput.nextElementSibling.innerHTML="";
                }
            }
            if(form['phone_number'].value.length==0){
                form['phone_number'].focus();
                form['phone_number'].nextElementSibling.innerHTML="Please enter phone number";
                form['phone_number'].classList.add("border-danger");
                return false;
            }else{
                form['phone_number'].classList.remove("border-danger");
                form['phone_number'].nextElementSibling.innerHTML="";
            }
            // if(form['postal_code'].value.length==0){
            //     form['postal_code'].focus();
            //     form['postal_code'].nextElementSibling.innerHTML="Please enter postal code";
            //     form['postal_code'].classList.add("border-danger");
            //     return false;
            // }else{
            //     form['postal_code'].classList.remove("border-danger");
            //     form['postal_code'].nextElementSibling.innerHTML="";
            // }
            // if(form['country_id'].value.length==0){
            //     form['country_id'].focus();
            //     form['country_id'].nextElementSibling.innerHTML="Please select country";
            //     form['country_id'].classList.add("border-danger");
            //     return false;
            // }else{
            //     form['country_id'].classList.remove("border-danger");
            //     form['country_id'].nextElementSibling.innerHTML="";
            // }
            // if(form['state_id'].value.length==0){
            //     form['state_id'].focus();
            //     form['state_id'].nextElementSibling.innerHTML="Please select state";
            //     form['state_id'].classList.add("border-danger");
            //     return false;
            // }else{
            //     form['state_id'].classList.remove("border-danger");
            //     form['state_id'].nextElementSibling.innerHTML="";
            // }
            // if(form['city_id'].value.length==0){
            //     form['city_id'].focus();
            //     form['city_id'].nextElementSibling.innerHTML="Please select city";
            //     form['city_id'].classList.add("border-danger");
            //     return false;
            // }else{
            //     form['city_id'].classList.remove("border-danger");
            //     form['city_id'].nextElementSibling.innerHTML="";
            // }
            var selectedGender = false;
            var genderOptions = form.elements['gender'];

            for (var i = 0; i < genderOptions.length; i++) {
                if (genderOptions[i].checked) {
                    selectedGender = true;
                    break;
                }
            }
            if (!selectedGender) {
                var genval = document.getElementById('genval');
                form['gender'][0].focus();
                genval.innerHTML = "<p class='text-danger'>Please select one!</p>";
                return false;
            } else {
                var genval = document.getElementById('genval');
                genval.innerHTML = "";
            }

            if(form['password'].value.length==0){
                form['password'].focus();
                var paasval = document.getElementById('passval');
                paasval.innerHTML="<p class='text-danger'>Please enter password</p>";
                form['password'].classList.add("border-danger");
                return false;
            }else{
                if(form['password'].value.length < 8) {
                    form['password'].focus();
                    var paasval = document.getElementById('passval');
                    paasval.innerHTML="<p class='text-danger'>Password must be at least 8 characters long!</p>";
                    form['password'].classList.add("border-danger");
                    return false;
                }else{
                    form['password'].classList.remove("border-danger");
                    paasval.innerHTML="";
                }
            }
            return true;
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
        } 
        // else if(selectedOption == 3) {
        //     targetRoute = "{{ route('register-specialist') }}";
        //     $('.route').attr('action', targetRoute);
        // } 
        else {
            targetRoute = "{{ route('register-user') }}";
            $('.route').attr('action', targetRoute);
        }
    });
    window.addEventListener("pageshow", () => {
        document.getElementById("register").reset();
    });

    var input = document.getElementById('address');
        var options = {
            types: ['geocode'], // Restrict to geographical addresses
        };

        var autocomplete = new google.maps.places.Autocomplete(input, options);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            var latitudeField = document.getElementById('latitude');
            var longitudeField = document.getElementById('longitude');
            var postalCodeField = document.getElementById('postal_code');
            var countryField = document.getElementById('country');
            var stateField = document.getElementById('state');
            var cityField = document.getElementById('city');

            if (place.geometry && place.geometry.location) {
                latitudeField.value = place.geometry.location.lat();
                longitudeField.value = place.geometry.location.lng();
            }

            postalCodeField.value = '';
            countryField.value = '';
            stateField.value = '';
            cityField.value = '';

            if (place.address_components) {
                for (var i = 0; i < place.address_components.length; i++) {
                    var component = place.address_components[i];

                    if (component.types.includes('postal_code')) {
                        postalCodeField.value = component.long_name;
                    }

                    if (component.types.includes('country')) {
                        countryField.value = component.long_name;
                    }

                    if (component.types.includes('administrative_area_level_1')) {
                        stateField.value = component.long_name;
                    }

                    if (component.types.includes('locality')) {
                        cityField.value = component.long_name;
                    }
                }
            }
        });
</script>
@endpush
