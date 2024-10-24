@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.doctor.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>@lang('Image')</label>
                                        <div class="image-upload">
                                            <div class="thumb">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview"
                                                        style="background-image: url({{ getImage(getFilePath('doctorProfile') . '/' . @$doctor->image, getFileSize('doctorProfile')) }})">
                                                        <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                                <div class="avatar-edit mt-0">
                                                    <input type="file" class="profilePicUpload" name="image"
                                                        id="profilePicUpload1" accept=".png, .jpg, .jpeg" required>
                                                    <label for="profilePicUpload1"
                                                        class="btn btn--success btn-block btn-lg">@lang('Upload')</label>
                                                    <small>@lang('Support Images'):
                                                        <b>@lang('jpeg'), @lang('jpg').</b> @lang(', resized into 400x400px')
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control " required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Username')</label>
                                            <input type="text" name="username" value="{{ old('username') }}"
                                                class="form-control " required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('E-mail')</label>
                                            <input type="text" name="email" value="{{ old('email') }}"
                                                class="form-control " required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Mobile')
                                                
                                            </label>
                                            <div class="input-group">
                                                
                                                <input type="number" name="mobile" value="{{ old('mobile') }}"
                                                    class="form-control" autocomplete="off" >
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-catg">
                                            <label>@lang('Category')</label>
                                            <select class="select2-multi-select form-control" name="categories[]" multiple="multiple" required>
                                                <option disabled >@lang('Select One')</option>
                                                @foreach ($categories as $category)
                                                    <option  value="{{ $category->id }}">
                                                        {{ __($category->category_name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-one">
                                            <label>@lang('Department')</label>
                                            <select class="select2-multi-select form-control" name="department[]" multiple="multiple" required>
                                                <option disabled >@lang('Select One')</option>
                                                @foreach ($departments as $department)
                                                    <option @selected($department->id == @$doctor->department_id) value="{{ $department->id }}">
                                                        {{ __($department->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-four">
                                            <label>@lang('Pet Type')</label>
                                            <select class="select2-multi-select form-control" name="pet_type[]" multiple="multiple" required>
                                                <option disabled >@lang('Select One')</option>
                                                @foreach ($petType as $ptype)
                                                    <option @selected($ptype->id == @$doctor->pet_type_id) value="{{ $ptype->id }}">
                                                        {{ __($ptype->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                        {{-- <div class="col-sm-12">
                                            <div class="form-group select2-wrapper" id="select2-wrapper-six">
                                                <label>@lang('Disease')</label>
                                                <select class="select2-multi-select form-control" name="pet_disese_id[]" multiple="multiple" required>
                                                    <option disabled >@lang('Select One')</option>
                                                    @foreach ($PetDisease as $ptdeses)
                                                        <option @selected($ptdeses->id == @$doctor->pet_disese_id) value="{{ $ptdeses->id }}">
                                                            {{ __($ptdeses->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
                                       
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-two">
                                            <label>@lang('Location')</label>
                                            <select class="select2-basic-two form-control" name="location" required>
                                                <option disabled selected>@lang('Select One')</option>
                                                @foreach ($locations as $location)
                                                    <option @selected($location->id == @$doctor->location_id) value="{{ $location->id }}">
                                                        {{ __($location->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Fees')</label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{ $general->cur_sym }}</span>
                                                <input type="number" name="fees" value="{{ old('fees') }}"
                                                    class="form-control" required />
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Qualification')</label>
                                            <input type="text" name="qualification" value="{{ old('qualification') }}" class="form-control" required />
                                        </div>
                                    </div>
                                    <!--new item listing -->
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-country">
                                            <label>@lang('Country')</label>
                                            <select class="select2-basic-one form-control" name="country" required id="country_id" >
                                                <option disabled selected>@lang('Select One')</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">
                                                        {{ __($country->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-state">
                                            <label>@lang('State')</label>
                                            <select class="select2-basic-one form-control" name="state" required id="states">
                                                <option disabled selected>@lang('Select One')</option>
                                                @foreach ($states as $state)
                                                    <option  value="{{$state->id }}">
                                                        {{ __($state->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-city">
                                            <label>@lang('City')</label>
                                            <select class="select2-basic-one form-control" required name="city" id="cities">
                                                <option disabled selected>@lang('Select One')</option>
                                                @foreach ($cities as $city)
                                                    <option  value="{{$city->id }}">
                                                        {{ __($city->city_name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Postal Code')</label>
                                            <input type="text" required name="item_postal_code" class="form-control" value="{{ old('item_postal_code') }}" />
                                        </div>
                                    </div> --}}

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> @lang('Address')</label>
                                            {{-- <textarea name="address" class="form-control" required>{{ old('address') }}</textarea> --}}
                                            <input type="text" id="address" class="form-control" value="{{ old('address') }}" name="address" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                                    <input type="hidden" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">

                                    <input type="hidden" name="country_id" id="country" value="{{ old('country_id') }}">
                                    <input type="hidden" name="state_id" id="state" value="{{ old('state_id') }}">
                                    <input type="hidden" name="city_id" id="city" value="{{ old('city_id') }}">

                                    {{-- <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Latitude')</label>
                                            <input type="text" name="item_lat" class="form-control " required value="{{ old('item_lat') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Longitude')</label>
                                            <input type="text" name="item_lng" class="form-control " required value="{{ old('item_lng') }}" />
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Price')</label>
                                            <input type="number" name="item_price" class="form-control " value="{{ old('item_price') }}" />
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Website')</label>
                                            <input type="text" name="item_website" class="form-control " value="{{ old('item_website') }}" />
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Facebook')</label>
                                            <input type="text" name="item_social_facebook" class="form-control " value="{{ old('item_social_facebook') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Twitter')</label>
                                            <input type="text" name="item_social_twitter" class="form-control " value="{{ old('item_social_twitter') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('LinkedIn')</label>
                                            <input type="text" name="item_social_linkedin" class="form-control " value="{{ old('item_social_linkedin') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('WhatsApp')</label>
                                            <input type="text" name="item_social_whatsapp" class="form-control " value="{{ old('item_social_whatsapp') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Instagram')</label>
                                            <input type="text" name="item_social_instagram" class="form-control " value="{{ old('item_social_instagram') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label> @lang('About')</label>
                                        <textarea name="about" rows="4" class="form-control" required>{{ old('about') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card b-radius--10 ">
                                        <div class="card-body p-0">
                                            <div class="table-responsive--md  table-responsive">
                                                <table class="table  style--two table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('Pet Type')</th>
                                                            <th style="text-align: left;">@lang('Disease')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($petType as $ptype)
                                                            <tr>
                                                                <td><span class="fw-bold"><input class="form-check-input" type="checkbox" name="pet_type_id[{{ $ptype->id }}]" value="{{ $ptype->id }}"  >&nbsp;{{ $ptype->name }}</span></td>
                                                                <td style="text-align: left;"> 
                                                                    @foreach ($PetDisease as $ptdeses)
                                                                        <input class="form-check-input" type="checkbox" name="disease_id[{{ $ptype->id }}][]" value="{{ $ptdeses->id }}"  >&nbsp;<span class="fw-bold ">{{ $ptdeses->name }}</span>&nbsp;&nbsp;&nbsp;
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.doctor.index') }}" class="btn btn-sm btn-outline--primary"><i class="la la-undo"></i>
        @lang('Back') </a>
@endpush

@push('script')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-r86tO9gWZxzRiELiw3DQYa2D3_o1CVk&libraries=places"></script>
    <script>
        (function($) {
            'use strict';
            $('.select2-basic-one').select2({
                dropdownParent: $('#select2-wrapper-one')
            });
            $('.select2-basic-two').select2({
                dropdownParent: $('#select2-wrapper-two')
            });
        })(jQuery);

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

@push('style')
    <style>
        .select2-wrapper {
            position: relative;
        }
    </style>
@endpush
