@extends('user.layouts.app')
@section('panel')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row mb-none-30">
        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-3 bg--primary align-items-center">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage(getFilePath('userProfile').'/'. $user->user_image,getFileSize('userProfile'))}}" alt="@lang('Image')">
                        </div>
                        <div class="ps-3">
                            <h4 class="text--white">{{__($user->name)}}</h4>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Name')
                            <span class="fw-bold">{{__($user->name)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span  class="fw-bold">{{__($user->username)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span  class="fw-bold">{{$user->email}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Phone')
                            <span  class="fw-bold">{{$user->phone}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('About Yourself')
                            <span  class="fw-bold">{{$user->user_about}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Prefer Language')
                            <span  class="fw-bold">{{$user->user_prefer_language}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Gender')
                            <span  class="fw-bold">{{$user->gender}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Address')
                            <span  class="fw-bold">{{__($user->address)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Country')
                            <span  class="fw-bold">{{__($user->country_id)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('State')
                            <span  class="fw-bold">{{__($user->state_id)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('City')
                            <span  class="fw-bold">{{__($user->city_id)}}</span>
                        </li>
                        {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Country')
                            @foreach ($countries as $country)
                                @if($user->country_id == $country->id)<span  class="fw-bold">{{$country->name}}</span>@endif
                            @endforeach
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('State')
                            @foreach ($states as $state)
                                @if($user->state_id == $state->id)<span  class="fw-bold">{{$state->name}}</span>@endif
                            @endforeach
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('City')
                            @foreach ($cities as $city)
                                @if($user->city_id == $city->id)<span  class="fw-bold">{{$city->city_name}}</span>@endif
                            @endforeach
                        </li> --}}
                        {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Latitude')
                            <span  class="fw-bold">{{$user->latitude}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Longitude')
                            <span  class="fw-bold">{{$user->longitude}}</span>
                        </li> --}}
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Profile Information')</h5>
                    <form action="{{ route('user.info.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row d-flex justify-content-center items-center">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('userProfile').'/'.$user->user_image,getFileSize('userProfile')) }})">
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
                                    <input type="text" id="name" class="form-control" name="name" value="{{$user->name}}" autocomplete="off"  required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Username')</label>
                                    <input type="text" id="username" class="form-control username" name="username" value="{{$user->username}}" autocomplete="off"  required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input type="email" name="email" value="{{$user->email}}" class="form-control" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Phone')</label>
                                    <input type="text" id="phoneNumber" class="form-control phone" required  name="phone" value="{{$user->phone}}" maxlength="10" autocomplete="off"  required>
                                </div>
                            </div>
                            {{-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Postal Code')</label>
                                    <input type="text" name="postal_code" value="{{$user->postal_code}}" class="form-control " required />
                                </div>
                            </div> --}}
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Prefer Language')</label>
                                    <input type="text" name="user_prefer_language" value="{{$user->user_prefer_language}}" class="form-control " />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Gender')</label>
                                    <select class="form-control" name="gender" required>
                                        <option value="male" @selected($user->gender == 'male')>@lang('Male')</option>
                                        <option value="female" @selected($user->gender == 'Female')>@lang('Female')</option>
                                        <option value="Don’t want to specify" @selected($user->gender == 'Don’t want to specify')>@lang('Don’t want to specify')</option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-sm-6">
                                <div class="form-group" id="select2-wrapper-country">
                                    <label>@lang('Country')</label>
                                    <select class="form-select" name="country_id" id="countrys_id" required>
                                        <option value="" selected disabled>{{ __('Select One') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" {{ $user->country_id == $country->id ? 'selected' : '' }}>
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
                                            <option value="{{ $state->id }}" @if($user->state_id == $state->id) selected @endif>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('City')</label>
                                    <input type="text" id="city_id" class="form-control username" name="city_id" value="{{$user->city_id}}" autocomplete="off"  required>
                                </div>
                            </div> --}}
                        </div>
                        {{-- <div class="col-sm-12">
                            <div class="form-group" id="select2-wrapper-city">
                                <label>@lang('City')</label>
                                <select class="form-select" name="city_id" required id="cities">
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" @if($user->city_id == $city->id) selected @endif>
                                            {{ __($city->city_name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="col-sm-12">
                            <div class="form-group">
                                <label>@lang('City')</label>
                                <input type="text" id="city_id" class="form-control username" name="city_id" value="{{$user->city_id}}" autocomplete="off"  required>
                            </div>
                        </div> --}}
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>@lang('Address')</label>
                                <input type="text" id="address" class="form-control" value="{{@$user->address}}" name="address" autocomplete="off" required>
                            </div>
                        </div>
                        
                        <input type="hidden" name="latitude" id="latitude" value="{{@$user->latitude}}">
                        <input type="hidden" name="longitude" id="longitude" value="{{@$user->longitude}}">
                        <input type="hidden" name="postal_code" id="postal_code" value="{{@$user->postal_code}}">

                        <input type="hidden" name="country_id" id="country" value="{{@$user->country_id}}">
                        <input type="hidden" name="state_id" id="state" value="{{@$user->state_id}}">
                        <input type="hidden" name="city_id" id="city" value="{{@$user->city_id}}">
                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>@lang('About Yourself')</label>
                                <textarea class="form-control" name="user_about" rows="5">{{$user->user_about }}</textarea>
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

@push('breadcrumb-plugins')
    <a href="{{route('user.password')}}" class="btn btn-sm btn-outline--primary"><i class="las la-key"></i>@lang('Password Setting')</a>
@endpush

@push('script')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-r86tO9gWZxzRiELiw3DQYa2D3_o1CVk&libraries=places"></script>
    <script>
        $('.phone').on('input', function(event) {
            var input = event.target.value;
            var regex = /[^a-zA-Z0-9]/g;
            if (regex.test(input)) {
                event.target.value = input.replace(regex, '');
            }
            $(this).val(formatPhoneNumber($(this).val()));
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
