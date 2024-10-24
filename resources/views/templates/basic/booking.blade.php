@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="booking-section booking-section-two pd-t-80 pd-b-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="booking-item d-flex flex-wrap align-items-center justify-content-between mb-5">
                        <div class="booking-left d-flex align-items-center">
                            <div class="booking-thumb">
                                <img src="{{ getImage(getFilePath('doctorProfile') . '/' . $doctor->image, getFileSize('doctorProfile')) }}"
                                    alt="@lang('doctor')">
                                @if ($doctor->featured)
                                    <span class="fav-btn"><i class="las la-medal"></i></span>
                                @endif
                            </div>
                            <div class="booking-content">
                                @if(isset($doctor->department) && isset($doctor->department->name))
                                    <span class="sub-title"><a href="#0">{{ __($doctor->department->name) }}</a></span>
                                @endif
                                <h5 class="title">{{ __($doctor->name) }} <i class="fas fa-check-circle"></i></h5>
                                <p>{{ __($doctor->qualification) }}</p>

                                <ul class="booking-list">
                                    @if (isset($doctor->location->name))
                                        <li><i class="fas fa-street-view"></i>{{ __($doctor->location->name) }}</li>
                                        <li><i class="fas fa-phone"></i> {{ __($doctor->mobile) }}</li>
                                    @endif
                                </ul>

                                @if(isset($speciesString))
                                    <h5 class="title">Treats:  <span><p>{{$speciesString}}</p></span> </h5>
                                @endif

                                @if(isset($getStateName))
                                    <h5 class="title">Location:  <span><p>{{$getCountryName->name.', '.$getStateName->name}}</p></span> </h5>
                                @endif

                                @if ($doctor->speciality || !empty($doctor->speciality))
                                    <div class="booking-btn">
                                        @foreach ($doctor->speciality as $item)
                                            <span class="border-btn">{{ __($item) }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="booking-right">
                            <div class="booking-content">
                                <ul class="booking-list">
                                    <li><i class="fas fa-hourglass-start"></i>@lang('Joined us') </li>
                                    <li><i class="fas fa-stethoscope"></i>{{ diffForHumans($doctor->created_at) }}</li>
                                    {{-- <li><span><i class="las la-wallet"></i>@lang('Fees') : {{ __($doctor->fees) }}
                                            {{ __($general->cur_text) }}<span></li> --}}
                                </ul>
                                <ul class="booking-tag">
                                    @foreach ($doctor->socialIcons as $social)
                                        <li><a href="{{ $social->url }}" target="_blank">@php echo $social->icon @endphp</a></li>
                                    @endforeach
                                </ul>
                                <div class="booking-btn">
                                    @if ($doctor->serial_day && $doctor->serial_or_slot && $doctor->weekday)
                                        <span class="border-btn active"><i class="la la-check-circle"></i>
                                            @lang('Available')</span>
                                    @else
                                        <span class="border-btn active"><i class="la la-times-circle"></i>
                                            @lang('Unavailable')</span>
                                    @endif
                                </div>
                                @php
                                    $currentDate = date('Y-m-d', strtotime(\Carbon\Carbon::now()));
                                @endphp
                                <div class="booking-btn">
                                    @if ($doctor->emergency_dealing != Status::NO && in_array($currentDate, json_decode($doctor->weekday)) && $doctor->serial_day && $doctor->serial_or_slot)
                                        <span class="border-btn active"><i class="las la-exclamation-triangle"></i>
                                            @lang('Deal in Emergency')</span>
                                    @else
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="booking-section booking-section-two pb-4 ">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="col-md-10">
                                    <h2>Add Your Pet Profile</h2>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="div1">
                                    <div class="cover_spin_petCreated"></div>
                                    <form class="contact-form verify-gcaptcha" method="POST" enctype="multipart/form-data" id="petCreatedForm" >
                                        @csrf
                                        <input type="hidden" name="page_type" value="booking">
                                        <div class="row ml-b-20">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Pet Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('Pet Name')" value="{{ old('name') }}"  autocomplete="off" required>
                                                    <input type="hidden" name="record_type" value="pet">
                                                </div>
                                                <div class="form-group">
                                                    <label>Breed(optional)</label>
                                                    <input type="text" class="form-control" id="breed" name="breed" placeholder="@lang('Breed')" value="{{ old('breed') }}"  autocomplete="off">
                                                </div>
                                                <div class="form-group">
                                                    <label>Weight</label>
                                                    <input type="text" min="0" class="form-control" id="weight" name="weight" placeholder="@lang('Weight')" value="{{ old('weight') }}" onkeypress = "return numericOnly(this);" ondrop = "return false;" onpaste = "return false;" maxlength="4"  autocomplete="off" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Age</label>
                                                    <input type="text" class="form-control" id="age" name="age" placeholder="@lang('Age')" value="{{ old('age') }}" oninput="validateAge(this)" autocomplete="off" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Image</label>
                                                    <input type="file" class="form-control" id="images" multiple name="images[]" placeholder="@lang('Image')" value="{{ old('image') }}" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>@lang('Species')</label>
                                                    <select class="form-control" name="pet_type_id" required>
                                                        <option value="" selected disabled >@lang('Select one option')</option>
                                                        @foreach ($petType as $pet )
                                                        <option value="{{ $pet->id }}" >{{ $pet->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select class="form-control" name="gender" required>
                                                        <option value="" selected>@lang('Select gender')</option>
                                                        <option value="male">@lang('Male')</option>
                                                        <option value="female">@lang('Female')</option>
                                                    </select>
                                                </div>
                                                <div class="" style="margin-top: 60px;">
                                                    <div class="btn-group btn-radio-group" data-toggle="buttons">
                                                        <label class="btn unit-btn active rounded">
                                                            <input type="radio" name="unit" id="option1" autocomplete="off" checked value="lbs"> lbs
                                                        </label>
                                                        <label class="btn unit-btn rounded">
                                                            <input type="radio" name="unit" id="option2" autocomplete="off" value="kg"> kg
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <div class="" style="margin-top: 50px;">
                                                        <div class="btn-group btn-radio-group" data-toggle="buttons">
                                                            <label class="btn unit-btn active rounded">
                                                                <input type="radio" name="age_in" id="option1" autocomplete="off" checked value="year"> year
                                                            </label>
                                                            <label class="btn unit-btn rounded">
                                                                <input type="radio" name="age_in" id="option2" autocomplete="off" value="month"> month
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="form-group" style="margin-top: 10px;">
                                                    <label>Video</label>
                                                    <input type="file" class="form-control" id="video" name="video[]" multiple placeholder="@lang('Video')" value="{{ old('video') }}" accept="video/mp4,video/x-m4v,video/*">
                                                </div> --}}
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <textarea rows="4" placeholder="@lang('Short Description')" class="form-control" id="short_description" name="short_description">{{ old('short_description') }}</textarea>
                                                </div>
                                            </div>
                                            <button type="submit" id="PetCreateButton" class="cmn-btn">@lang('Add Pet Detail')</button>
                                        </div>
                                    </form>
                                </div>
                                <div id="div2" style="display: none;">
                                    <div class="cover_spin_petCreated"></div>
                                    <form class="contact-form verify-gcaptcha" method="POST" enctype="multipart/form-data" id="preRecordForm" >
                                        @csrf
                                        <div class="row ml-b-20">
                                            <div class="form-group">
                                                <label>Previous Medical Record</label>
                                                <input type="file" id="previous_record" multiple name="previous_record[]" placeholder="@lang('Image')" value="{{ old('image') }}" required accept=".doc,.docx,.pdf,video/mp4,video/x-m4v,video/*,image/*" />
                                                <input type="hidden" name="record_type" value="record">
                                            </div>
                                            <button type="submit" id="PreRecordButton" class="cmn-btn">@lang('Add Pet Previous Record')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer" style="border-top:none; margin-bottom:20px;">
                            </div>
                        </div>
                        </div>
                    </div>

                    <!--start login modal -->
                    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
                        <div class="modal-dialog modal-ml modal-dialog-centered p2 pt-5" style="background-image: url(http://127.0.0.1:8000/assets/images/logoIcon/images/background.jpg);
                        background-color: none;">
                        <div class="modal-content" style="padding-top:26%; margin:0 auto;width:95%;">
                            <!--sample -->
                            <div class="align-items-center d-flex">
                                <div class="login-area p-3" style="width: -webkit-fill-available;">
                                    <div class="mylogo">
                                        <img src="/assets/images/logoIcon/images/logo.png" alt="This is a logo" class="logo" style="position: absolute;top: -10%;left: 0%;" />
                                        <h5 class="logo_heading" style="position: absolute;top:14%;left: 16%;color: white;">
                                            Welcome to find <br />
                                            Doctors and practicee near you
                                        </h5>
                                    </div>
                                    <div class="login-wrapper__body bg-white" style="border-bottom-right-radius: 23px !important; border-bottom-left-radius: 23px !important;">
                                        <form method="POST" class="cmn-form verify-gcaptcha login-form route" id="loginSubmitForm">
                                            {{-- <input type="hidden" name="_token" value="8VpQ4gKAJp2ilO1GB3ubG5lvjOtUHB6eE4nkhLeM" id="_token"> --}}



                                            <div class="form-group">
                                                <label class="text--black"><small>@lang('Username')</small></label>
                                                <input type="text" class="form-control text--black" style="border: 1px solid black;height:31px;" value="{{ old('username') }}"
                                                    name="username" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="text--black"><small>@lang('Password')</small></label>
                                                {{-- <input type="Password" class="form-control" name="password" id="password" required> --}}
                                                <div class="flex justify-between items-center border-gray-200 mt-2 bg-gray-100 input-group mb-3">
                                                    <input type="password" class="form-control text--black" style="height:31px; border: 1px solid black; border-right: none; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;" name="password" required style="margin-right:-10px;border-right: rgb(232, 240, 254);height:50px;border:none;background-color:aliceblue; color:black;">
                                                    <div class="flex justify-between items-center mb-3">
                                                        <span class="input-group-text" style="height:31px ;border-top-left-radius: 0 !important;border-bottom-left-radius: 0 !important;border: 1px solid black;border-top-right-radius: 11px !important;
                                                        border-bottom-right-radius: 11px !important;" onclick="password_show_hide();">
                                                        <i class="fas fa-eye-slash" id="show_eye"></i>
                                                        <i class="fas fa-eye d-none" id="hide_eye"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <x-captcha /> --}}
                                            <div class="d-flex flex-wrap justify-between">
                                                {{-- <br>
                                                <div class="form-check me-3">
                                                    <input name="remember" type="checkbox" id="remember">
                                                    <label class="text--black" for="remember"><small>@lang('Remember Me')</small></label>
                                                </div> --}}
                                                <p id="loginerrormsg" class="col-lg-12 col-md-6 col-sm-6 mrb-30 alert alert-danger text-center" role="alert" style="display: none;font-size:11px;"></p>
                                                <a href="{{ route('user.password.reset') }}" class="forget-text text--black"><small>@lang('Forgot Password?')</small></a>
                                            </div>
                                            <button type="submit" class="logInBtn" style="color: #ffffff;
                                            width: 100%;
                                            background: linear-gradient(to right, #a740cd, #6f73de);
                                            border-radius: 250px;
                                            padding-top: 10px;
                                            padding-bottom: 10px;
                                            margin-top: 20px;" >@lang('LOGIN')</button>
                                            <div class="sign-info mt-4">
                                                <span class="d-inline-block line-height-2 text--black">Don't have an account? <a class="forget-text text--black" href="{{route('register')}}">Sign Up</a></span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--end sample -->
                            {{-- <div class="modal-header">
                                <div class="col-md-10">
                                    <h5>Login As a Pet Parent</h5>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-8" id="loginCustomer" >
                                    <div class="cover_spin_petCreated"></div>
                                    <form class="contact-form verify-gcaptcha" method="POST" enctype="multipart/form-data" id="loginSubmitForm" >
                                        @csrf
                                        <div class="row ml-b-20">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" class="form-control" id="username" name="username" placeholder="@lang('User Name')" value="{{ old('username') }}"  autocomplete="off" required>

                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="@lang('Password')" value="{{ old('password') }}"  autocomplete="off" required>

                                            </div>
                                            <button type="submit" id="loginButton" class="cmn-btn">@lang('Login')</button>
                                        </div>
                                    </form>
                                </div>
                            </div> --}}
                            <div class="modal-footer" style="border-top:none; margin-bottom:20px;">
                            </div>
                        </div>
                        </div>
                    </div>
                    <!--end login modal -->
                </div>
            </div>
        </div>
    </section>

    <section class="overview-section pd-b-80">
        <div class="container">
            <div class="overview-area mrb-40">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="overview-tab-wrapper">
                            <ul class="tab-menu">
                                <li>@lang('Overview')</li>
                                <li class="active">@lang('Booking')</li>
                                {{-- @if (loadFbComment() != null) --}}
                                @if($loggedInUser)
                                    <li>@lang('Review')</li>
                                @endif
                                {{-- @endif --}}
                            </ul>
                            <div class="tab-cont">
                                <div class="tab-item">
                                    <div class="overview-tab-content ml-b-30">
                                        <div class="overview-content">
                                            <h5 class="title">@lang('About')</h5>
                                            <p>
                                                @if ($doctor->about)
                                                    {{ __($doctor->about) }}
                                                @else
                                                    <span>@lang('Doctor about will be appearing soon')</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="overview-content">
                                            <h5 class="title">@lang('Education')</h5>
                                            <div class="overview-box">
                                                @if (count($doctor->educationDetails))
                                                    <ul class="overview-list">
                                                        @foreach ($doctor->educationDetails as $education)
                                                            <li>
                                                                <div class="overview-user">
                                                                    <div class="before-circle"></div>
                                                                </div>
                                                                <div class="overview-details">
                                                                    <h6 class="title">{{ __($education->institution) }}
                                                                    </h6>
                                                                    <div>{{ __($education->discipline) }}</div>
                                                                    <span>{{ __($education->period) }}</span>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span>@lang('Education data will be appearing soon')</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="overview-content">
                                            <h5 class="title">@lang('Work & Experience')</h5>
                                            <div class="overview-box">
                                                @if (count($doctor->experienceDetails))
                                                    <ul class="overview-list">
                                                        @foreach ($doctor->experienceDetails as $experience)
                                                            <li>
                                                                <div class="overview-user">
                                                                    <div class="before-circle"></div>
                                                                </div>
                                                                <div class="overview-details">
                                                                    <h6 class="title">{{ __($experience->institution) }}
                                                                    </h6>
                                                                    <div>{{ __($experience->discipline) }}</div>
                                                                    <span>{{ __($experience->period) }}</span>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span>@lang('Experience data will be appearing soon')</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="overview-content">
                                            <h5 class="title">@lang('Specializations')</h5>
                                            <div class="overview-footer-area d-flex flex-wrap justify-content-between">
                                                @if ($doctor->speciality)
                                                    <ul class="overview-footer-list">
                                                        @if ($doctor->speciality || !empty($doctor->speciality))
                                                            @foreach ($doctor->speciality as $item)
                                                                <li><i
                                                                        class="fas fa-long-arrow-alt-right"></i>{{ __($item) }}
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                @else
                                                    <span>@lang('Specializations data will be appearing soon')</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-item">
                                    <div class="overview-tab-content changeButtonAppointment">
                                        @if($loggedInUser && count($userPets) > 0 || $userPets->isNotEmpty())
                                            {{-- @if("request didnot came from allvetsearch page then show below content") --}}

                                            @if($vetid != 0)
                                                <a href="{{ route('getAppointmentsPets') }}" class="cmn-btn">@lang('Make an appointment')</a>
                                            @else

                                                <div class="overview-booking-header d-flex flex-wrap justify-content-between ml-b-10">
                                                    <div class="overview-booking-header-left mrb-10">
                                                        @if ($doctor->serial_day && $doctor->serial_or_slot && $doctor->weekday)
                                                            <h4 class="title">@lang('Available Schedule')</h4>
                                                            <ul class="overview-booking-list">
                                                                <li class="available">@lang('Available')</li>
                                                                <li class="booked">@lang('Booked')</li>
                                                                <li class="selected">@lang('Selected')</li>
                                                            </ul>
                                                        @else
                                                            <h4 class="title">@lang('No Schedule Available Yet')</h4>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if ($doctor->serial_day && $doctor->serial_or_slot && $doctor->weekday)
                                                    <form action="{{ route('doctors.appointment.store', $doctor->id) }}"
                                                        method="post" class="appointment-from" onsubmit="return formValidator()">
                                                        @csrf
                                                        <div class="overview-booking-area">
                                                            <div class="overview-booking-header-right mrb-10">
                                                                <div
                                                                    class="overview-date-area d-flex flex-wrap align-items-center justify-content-between">
                                                                    <div class="overview-date-header">
                                                                        <h5 class="title">@lang('Choose Your Date & Time')</h5>
                                                                    </div>
                                                                    <div class="overview-date-select">
                                                                        <select class="form-control date-select" name="booking_date"
                                                                            required>
                                                                            <option value="" selected disabled>
                                                                                @lang('Select Date')</option>
                                                                            @foreach ($availableDate as $date)
                                                                                @if(isset($doctor->weekday) && in_array($date, json_decode($doctor->weekday)))
                                                                                    <option value="{{ $date }}">
                                                                                        {{ __($date) }}</option>
                                                                                @else
                                                                                    @continue
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <ul class="text-center dummy">
                                                                <div class="text-center">

                                                                    <h5 class="title mt-2">Please select date first</h5>
                                                                </div>
                                                            </ul>
                                                            <ul class="clearfix time-serial-parent">
                                                                @foreach ($doctor->serial_or_slot as $item)
                                                                    <li>
                                                                        <a href="javascript:void(0)"
                                                                            class="btn btn--primary mr-2 mb-2 available-time item-{{ slug($item) }}"
                                                                            data-value="{{ $item }}">{{ __($item) }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                                <input type="hidden" name="time_serial" class="time" required>
                                                            </ul>
                                                        </div>
                                                        <div class="booking-appoint-area">
                                                            @if($loggedInUser)
                                                                <div class="row justify-content-center ml-b-30">
                                                                    <div class="col-lg-6 mrb-30">
                                                                        <p id="loadermsg" class="col-lg-5 col-md-6 col-sm-6 mrb-30 alert alert-danger text-center" role="alert" style="display: none;">Choose Your Time Slot </p>
                                                                        <div class="booking-appoint-form-area">
                                                                            <h4 class="title">@lang('Appointment Form')</h4>
                                                                            <div class="booking-appoint-form">
                                                                                <div class="row">
                                                                                    <div class="col-lg-6 form-group">
                                                                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="@lang('Name')" required>
                                                                                    </div>
                                                                                    <div class="col-lg-6 form-group">
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-text">{{ $general->country_code }}</span>
                                                                                            <input type="text" class="form-control phone" required name="mobile" value="{{old('mobile')}}" maxlength="14" placeholder="@lang('Mobile Number')" required>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-12 form-group">
                                                                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="@lang('Email')" required>
                                                                                    </div>
                                                                                    @if(Session::get('petId') == 0)
                                                                                        <div class="col-lg-12 form-group" >
                                                                                            <select name="pet" class="form-control" >
                                                                                                <option selected disabled>@lang('Select Pet')</option>
                                                                                                @foreach ($userPets as $pet)

                                                                                                    <option @if((old('pet') || Session::get('petId')) == $pet->id) selected @endif value="{{ $pet->id }}">{{ __($pet->name) }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    @endif
                                                                                    <div class="col-lg-12 form-group">
                                                                                        <label>Additional Comments</label>
                                                                                        <textarea name="disease" required placeholder="@lang('Additional Comments')">{{ old('disease') }}</textarea>
                                                                                    </div>
                                                                                    <div
                                                                                        class="col-lg-12 form-group d-flex flex-wrap justify-content-between">
                                                                                        {{-- <button type="submit" class="cmn-btn payment-system" data-value="2">@lang('Will Pay In Cash')</button> --}}
                                                                                        @if ($general->online_payment)
                                                                                            <button type="submit" class="cmn-btn payment-system" data-value="1">@lang('Proceed to Payment')</button>
                                                                                        @endif
                                                                                        <input type="hidden" name="payment_system" class="payment" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 mrb-30">
                                                                        <div class="booking-confirm-area">
                                                                            <h4 class="title">@lang('Confirm Your Booking')</h4>
                                                                            <ul class="booking-confirm-list">
                                                                                <li><span>@lang('Name')</span> : <span
                                                                                        class="custom-color name"></span>
                                                                                </li>
                                                                                <li><span>@lang('Email')</span> : <span
                                                                                        class="custom-color email"></span>
                                                                                </li>
                                                                                <li><span>@lang('Phone Number')</span> : <span
                                                                                        class="custom-color mobile"></span>
                                                                                </li>
                                                                                <li><span>@lang('Pet')</span> : <span
                                                                                        class="custom-color pet">{{ Session::get('ptname') }}</span>
                                                                                </li>
                                                                                <li><span>@lang('Date')</span> : <span
                                                                                        class="custom-color date"></span>
                                                                                </li>
                                                                                <li><span>@lang('Serial / Slot')</span> :
                                                                                    <span class="custom-color book-time"></span>
                                                                                </li>
                                                                                <li><span>@lang('Fees')</span> :
                                                                                    <span class="custom-color fees"></span>
                                                                                </li>
                                                                            </ul>
                                                                            <div class="booking-confirm-btn">
                                                                                <button type="button"
                                                                                    class="cmn-btn-active reset">@lang('Reset')</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <a href="{{ route('login') }}" class="cmn-btn" data-bs-toggle="modal" data-bs-target="#loginModal" >@lang('Please login/signup for appointment')</a>
                                                            @endif
                                                        </div>
                                                    </form>
                                                @endif
                                            @endif
                                        @elseif (!$loggedInUser)
                                            <a href="{{ route('login') }}" class="cmn-btn" data-bs-toggle="modal" data-bs-target="#loginModal" >@lang('Please login/signup for appointment')</a>
                                        @elseif (count($userPets) < 0 || $userPets->isEmpty())
                                            <button type="button" class="cmn-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                @lang('Please add your pet first')
                                            </button>
                                        @endif
                                    </div>
                                    <div>
                                        @if(isset($relatedDoctors) && count($relatedDoctors) > 0)
                                            <div class="booking-search-area mt-5">
                                                <div class="row justify-content-center">
                                                    <div class="col-lg-12">
                                                        <h2 class="title">@lang('Related Doctors')</h2>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">

                                                <div class="booking-slider">
                                                    <div class="swiper-wrapper">
                                                        @foreach ($relatedDoctors as $relatedDoctor)
                                                            <div class="swiper-slide">
                                                                <div class="booking-item" style="height: 400px; display: flex; flex-direction: column;">
                                                                    <div class="booking-thumb">
                                                                        <a href="{{ route('doctors.booking',$relatedDoctor->id) }}"><img src="{{ getImage(getFilePath('doctorProfile') . '/' . @$relatedDoctor->image, getFileSize('doctorProfile')) }}" alt="@lang('doctor')"></a>
                                                                    </div>
                                                                    <div class="booking-content">
                                                                        @if(isset($relatedDoctor->department) && $relatedDoctor->department !=='')
                                                                            <span class="sub-title">
                                                                                <a href="{{ route('doctors.departments', $relatedDoctor->department->id) }}">{{ __($relatedDoctor->department->name) }}</a>
                                                                            </span>
                                                                        @endif
                                                                        <h5 class="title">{{ __($relatedDoctor->name) }} <i class="fas fa-check-circle"></i></h5>
                                                                        <p>{{ strLimit(__($relatedDoctor->qualification), 50) }}</p>
                                                                        <ul class="booking-list">
                                                                            <li><i class="fas fa-dollar-sign"></i> {{ __($relatedDoctor->fees) }}</li>
                                                                        </ul>
                                                                        <div class="booking-btn for-booking-class">
                                                                            <a href="{{ route('doctors.booking',$relatedDoctor->id) }}" class="cmn-btn w-100 text-center">@lang('Get Appointment')</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="ruddra-next">
                                                        <i class="las la-angle-right"></i>
                                                    </div>
                                                    <div class="ruddra-prev">
                                                        <i class="las la-angle-left"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                {{-- @if (loadFbComment() != null) --}}
                                    <div class="tab-item">
                                        <div class="comments-section">
                                            {{-- <div class="fb-comments" data-href="{{ url()->current() }}" data-numposts="5"> --}}
                                                <form action="{{ route('doctors.review.store', $doctor->id) }}" method="post">
                                                    @csrf
                                                    <div class="booking-appoint-area">
                                                        <div class="row justify-content-center ml-b-30">
                                                            <div class="col-lg-6 mrb-30">
                                                                <p id="loadermsg" class="col-lg-5 col-md-6 col-sm-6 mrb-30 alert alert-danger text-center" role="alert" style="display: none;">Choose Your Time Slot </p>
                                                                <div class="booking-appoint-form-area">
                                                                    <h4 class="title">@lang('Appointment Form')</h4>
                                                                    <input type="hidden" name="vetId" value="{{$doctor->id}}"/>
                                                                    <div class="booking-appoint-form">
                                                                        <div class="row">
                                                                            <div class="col-lg-12 form-group">
                                                                                <textarea name="vetReview" required placeholder="@lang('Enter your review')">{{ old('vetReview') }}</textarea>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="rating" class="col-md-4 control-label">Rating</label>
                                                                                <div class="col-md-6">
                                                                                    <div class="star-rating">
                                                                                        <span class="fa fa-star star" data-rating="5"></span>
                                                                                        <span class="fa fa-star star" data-rating="4"></span>
                                                                                        <span class="fa fa-star star" data-rating="3"></span>
                                                                                        <span class="fa fa-star star" data-rating="2"></span>
                                                                                        <span class="fa fa-star star" data-rating="1"></span>
                                                                                        <input type="hidden" name="rating" class="selected-rating">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="cmn-btn">@lang('Add Review')</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                {{-- @php echo loadFbComment() @endphp --}}
                                            {{-- </div> --}}
                                        </div>
                                        @if($loggedInUser && !$vetReviews->isEmpty())
                                            <div class="comments-section mt-4">
                                                <div class="row justify-content-center ml-b-30">
                                                    <div class="col-lg-6 mrb-30">
                                                        <table class="table table--light style--two">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('Name')</th>
                                                                    <th>@lang('Email')</th>
                                                                    <th>@lang('Review')</th>
                                                                    <th>@lang('Rating')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($vetReviews as $review)
                                                                    <tr>
                                                                        <td>{{ $review->user->name }} </td>
                                                                        <td>{{ $review->user->email }} </td>
                                                                        <td>{{ $review->review }} </td>
                                                                        <td style="width: 130px;">
                                                                            @for($i = 1; $i <= $review->rating; $i++)
                                                                            <span class="fa fa-star checked"></span>
                                                                            @endfor
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                {{-- @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('style')
    <style>
        .input-group-text {
            border-radius: 0.5rem 0 0 0.5rem !important;
        }
    </style>
@endpush



@push('script')
    <script>
        (function($) {
            "use strict";

            $(".available-time").on('click', function() {
                var currency = '{{ $general->cur_text }}';
                let url = "/veterinarians/fees";
                let data = {
                    time: $(this).data('value'),
                    doctor_id: '{{ $doctor->id }}'
                }
                $.get(url, data, function(response) {
                    // Success
                    console.log(response.fees);
                    $('.fees').text(response.fees +' '+ currency);
                }).fail(function() {
                    // error
                    console.log(error);

                });

                $('.time').val($(this).data('value'));
                $('.book-time').text($(this).data('value'));
            })

            function slug(text) {
                return text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
            }

            $("select[name=booking_date]").on('change', function() {
                $('.date').text(`${$(this).val()}`); // Add date to view

                $('.dummy').addClass('d-none');
                $('.time-serial-parent').removeClass('d-none');
                $('.available-time').removeClass('active');
                $('.book-time').text('');
                $('.available-time').removeClass('btn--success disabled').addClass('active-time');
                let url = "/veterinarians/booking/date/availability";
                let data = {
                    date: $(this).val(),
                    doctor_id: '{{ $doctor->id }}'
                }

                $.get(url, data, function(response) {
                    if (!response.value.length) {
                        $('.available-time').removeClass('active-time disabled');
                    } else {
                        $.each(response.value, function(key, value) {
                            var demo = slug(value);
                            $(`.item-${demo}`).addClass('active-time disabled');
                        });
                    }

                    if (response.unavailableTime.length) {
                        $.each(response.unavailableTime, function(key, value) {
                            var demo = slug(value);
                            $(`.item-${demo}`).addClass('disabled');
                        });
                    }
                });
            });

            $("[name=name]").on('input', function() {
                $('.name').text(`${$(this).val()}`);
            });
            $("[name=email]").on('input', function() {
                $('.email').text(`${$(this).val()}`);
            });
            $("[name=mobile]").on('input', function() {
                $('.mobile').text(`${$(this).val()}`);
            });
            $("[name=pet]").on('change', function() {
                var selectedPetName = $(this).find("option:selected").text();
                $('.pet').text(selectedPetName);
            });
            // $("[name=pet]").on('input', function() {
            //     $('.pet').text(`${$(this).val()}`);
            // });
            $(".reset").on('click', function() {
                $('.appointment-from')[0].reset();
                $('.name').text('');
                $('.email').text('');
                $('.mobile').text('');
                $('.pet').text('');
                $('.date').text('');
                $('.book-time').text('');
                $('.fees').text('');
            });

            $('.payment-system').on('click', function() {
                $('.payment').val($(this).data('value'));
            });

        })(jQuery);

        $('.phone').on('input', function(event) {
            var input = event.target.value;
            var cleanInput = input.replace(/[^a-zA-Z0-9]/g, '');
            if (cleanInput.length >= 10) {
                var formattedNumber = '(' + cleanInput.substr(0, 3) + ') ' + cleanInput.substr(3, 3) + '-' + cleanInput.substr(6, 4);
                event.target.value = formattedNumber;
            } else {
                event.target.value = cleanInput;
            }
        });

        //login From Submit
        $('#loginSubmitForm').on('submit', function(e){
                    e.preventDefault();
                    // $.ajaxSetup({
                    //              headers: {
                    //                 'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //             }
                    // });

                    var form = $(this);
                    var submitButton = form.find('button[type="submit"]');
                    // Disable submit button
                    //submitButton.attr('disabled', 'disabled');
                    var formData = new FormData(form[0]);
                    $.ajax({
                    url:'/user',
                    // headers: {'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method:$(this).attr('method'),
                    data:new FormData(this),
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    error: function(resp) {
                        if (resp.responseJSON && resp.responseJSON.errors && resp.responseJSON.errors.username) {
                            notify('error', resp.responseJSON.errors.username);
                        } else {
                            notify('error', 'Your email is not verified.');
                        }
                    //    $("#loginerrormsg").show();
                    //    $("#loginerrormsg").html('Username or Password not match');
                        //$("#loginerrormsg").html(resp.responseJSON.errors.username);

                    //    $("#loginerrormsg").fadeOut(3000);
                       // return false;
                        //alert(resp.responseJSON.errors.username);
                        }
                    }).done(function(response){
                        console.log(1);
                        //$("#loginerrormsg").show();
                        //$("#loginerrormsg").html(resp.responseJSON.errors.username);
                        //$("#loginerrormsg").fadeOut(3000);
                        //$('.all-sections').load('.all-sections')
                       // $('.all-sections').hide().load(" .all-sections"+"> *").fadeIn(0);
                      location.reload();
                       // alert(response.message);

                    });
        });

        function password_show_hide() {
            var x = document.getElementById("password");
            var show_eye = document.getElementById("show_eye");
            var hide_eye = document.getElementById("hide_eye");
            hide_eye.classList.remove("d-none");
            if (x.type === "password") {
                x.type = "text";
                show_eye.style.display = "none";
                hide_eye.style.display = "block";
            } else {
                x.type = "password";
                show_eye.style.display = "block";
                hide_eye.style.display = "none";
            }
        }

        function formValidator(){

            const valTimeSerial = $("#time_serial").val();
            if(valTimeSerial==''){
                $('html, body').animate({
                    scrollTop: $(".available-time").offset().top
                }, 1000);
                $("#loadermsg").show();
                $("#loadermsg").fadeOut(7000);
                return false;


            }else{

                return true;
            }


        }
        function onlyNumberKey(evt) {

             // Only ASCII character in that range allowed
             var ASCIICode = (evt.which) ? evt.which : evt.keyCode
             if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                 return false;
             return true;
         }

            var specialKeys = new Array();

            specialKeys.push(8); //Backspace

        function numericOnly(elementRef) {

                var keyCodeEntered = (event.which) ? event.which : (window.event.keyCode) ?    window.event.keyCode : -1;

                if ((keyCodeEntered >= 48) && (keyCodeEntered <= 57)) {

                return true;

                }

        // '.' decimal point...

                else if (keyCodeEntered == 46) {

                // Allow only 1 decimal point ('.')...

                if ((elementRef.value) && (elementRef.value.indexOf('.') >= 0))

                    return false;

                else

                    return true;

                }

                return false;

        }
        document.addEventListener('DOMContentLoaded', function() {
            $('.star').on('click', function () {
                var rating = this.getAttribute('data-rating');
                $('input[name="rating"]').val(rating);
                $('.star').removeClass('checked');
                $(this).nextAll('.star').addBack().addClass('checked');
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endpush
