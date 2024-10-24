@extends('doctor.layouts.app')
@section('panel')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row mb-none-30">
        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-3 bg--primary align-items-center">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage(getFilePath('doctorProfile').'/'. $doctor->image,getFileSize('doctorProfile'))}}" alt="@lang('Image')">
                        </div>
                        <div class="ps-3">
                            <h4 class="text--white">{{__($doctor->name)}}</h4>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Name')
                            <span class="fw-bold">{{__($doctor->name)}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span  class="fw-bold">{{__($doctor->username)}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span  class="fw-bold">{{$doctor->email}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Mobile')
                            <span  class="fw-bold">{{$doctor->mobile}}</span>
                        </li>
                        @if (isset($doctor->department) && isset($doctor->department->name))
                        @php
                            $currentshowdept = explode(",",$doctor->department_id);
                        @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Department')
                                <span  class="fw-bold">
                                    @php $printed = false; @endphp
                                    @foreach ($departments as $department)
                                        @if (in_array($department->id, $currentshowdept))
                                            @if ($printed)
                                                ,
                                            @endif
                                            {{ __($department->name) }}
                                            @php $printed = true; @endphp
                                        @endif
                                    @endforeach
                                </span>
                            </li>
                        @endif
                        @if (isset($doctor->location->name))
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Location')
                            <span  class="fw-bold">{{$doctor->location->name}}</span>
                        </li>
                        @endif

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Qualification')
                            <span  class="fw-bold">{{$doctor->qualification}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Fees')
                            <span  class="fw-bold">{{$doctor->fees}} {{__($general->cur_text)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Country')
                            @foreach ($countries as $country)
                                @if($doctor->country_id == $country->id)<span  class="fw-bold">{{$country->name}}</span>@endif
                            @endforeach
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('State')
                            @foreach ($states as $state)
                                @if($doctor->state_id == $state->id)<span  class="fw-bold">{{$state->name}}</span>@endif
                            @endforeach
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('City')
                            @foreach ($cities as $city)
                                @if($doctor->city_id == $city->id)<span  class="fw-bold">{{$city->city_name}}</span>@endif
                            @endforeach
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Latitude')
                            <span  class="fw-bold">{{__($doctor->item_lat)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Longitude')
                            <span  class="fw-bold">{{__($doctor->item_lng)}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Profile Information')</h5>

                    <form action="{{ route('doctor.info.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row d-flex justify-content-center items-center">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('doctorProfile').'/'.$doctor->image,getFileSize('doctorProfile')) }})">
                                                    <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                <label for="profilePicUpload1" class="bg--success">@lang('Upload Image')</label>
                                                <small class="mt-2  ">@lang('Supported files'): <b>@lang('jpeg'), @lang('jpg').</b> @lang('Image will be resized into 400x400px') </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" id="name" class="form-control" name="name" value="{{$doctor->name}}" autocomplete="off"  required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Username')</label>
                                    <input type="text" id="username" class="form-control username" name="username" value="{{$doctor->username}}" autocomplete="off"  required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input type="email" name="email" value="{{$doctor->email}}" class="form-control" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Mobile')</label>
                                    <input type="text" name="mobile" value="{{$doctor->mobile}}" class="form-control phone" autocomplete="off" required />
                                </div>
                            </div>
                                    @php
                                        $currentdept = explode(",",$doctor->department_id);
                                    @endphp
                                    <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-one">
                                            <label>@lang('Department')</label>
                                            <select class="select2-multi-select form-control" name="department[]" multiple="multiple" required>
                                                <option disabled >@lang('Select multiple')</option>
                                                @foreach ($departments as $department)
                                                <option  value="{{ $department->id }}" @if(in_array($department->id, $currentdept )) selected="selected" @endif >
                                                        {{ __($department->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Qualification')</label>
                                    <input type="text" name="qualification" value="{{$doctor->qualification}}" class="form-control" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Fees')</label>
                                    <input type="text" name="fees" value="{{$doctor->fees}}" class="form-control" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" id="select2-wrapper-country">
                                    <label>@lang('Country')</label>
                                    <select class="form-select" name="country_id" id="countrys_id" required>
                                        <option value="" selected disabled>{{ __('Select One') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" {{ $doctor->country_id == $country->id ? 'selected' : '' }}>
                                                {{ __($country->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" id="select2-wrapper-state">
                                    <label>@lang('State')</label>
                                    <select class="form-select" name="state_id" required id="states">
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}" @if($doctor->state_id == $state->id) selected @endif>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group" id="select2-wrapper-city">
                                    <label>@lang('City')</label>
                                    <select class="form-select" name="city_id" required id="cities">
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}" @if($doctor->city_id == $city->id) selected @endif>
                                                {{ __($city->city_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Latitude')</label>
                                    <input type="text" name="latitude" value="{{$doctor->item_lat}}" class="form-control" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Longitude')</label>
                                    <input type="text" name="longitude" value="{{$doctor->item_lng}}" class="form-control" autocomplete="off" required />
                                </div>
                            </div>
                        </div>
                        </div>
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        'use strict';
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
    </script>
@endpush

@push('breadcrumb-plugins')
    <a href="{{route('doctor.password')}}" class="btn btn-sm btn-outline--primary"><i class="las la-key"></i>@lang('Password Setting')</a>
@endpush
