@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.clinics.store') }}" method="POST" enctype="multipart/form-data">
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
                                                        style="background-image: url({{ getImage(getFilePath('clinic') . '/' . @$clinic->logo, getFileSize('clinic')) }})">
                                                        <button type="button" class="remove-image"><i
                                                                class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                                <div class="avatar-edit mt-0">
                                                    <input type="file" class="profilePicUpload" name="logo"
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
                                            <input type="text" name="name" 
                                                class="form-control " required value="{{ old('name') }}"
                                                />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Phone')</label>
                                            <input type="text" name="phone" 
                                                class="form-control " required value="{{ old('phone') }}"
                                                />
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group" id="select2-wrapper-staff">
                                            <label>@lang('Assign Staff')</label>
                                            <select class="select2-multi-select form-control" name="staff_id[]"
                                                multiple="multiple" required>
                                                <option disabled>@lang('Select One')</option>
                                                @foreach ($staffs as $staff)
                                                    <option value="{{ $staff->id }}">{{ __($staff->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    
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
                                    </div>

                                    <div class="col-sm-6">
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
                                        <div class="form-group">
                                            <label>@lang('City')</label>
                                            <input type="text" name="city" 
                                                class="form-control " required value="{{ old('city') }}" />
                                        </div>
                                    </div> --}}
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-dept">
                                            <label>@lang('Department')</label>
                                            <select class="select2-multi-select form-control" name="department[]" multiple="multiple" required>
                                                <option disabled >@lang('Select One')</option>
                                                @foreach ($departments as $department)
                                                    <option @selected($department->id == @$clinic->department) value="{{ $department->id }}">
                                                        {{ __($department->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
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
                                        <div class="form-group">
                                            <label>@lang('Week Days')</label>
                                            <input type="text" name="week_days" 
                                                class="form-control " required value="{{ old('week_days') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Timings')</label>
                                            <input type="text" name="timings" 
                                                class="form-control " required value="{{ old('timings') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Clinic Owner')</label>
                                            <input type="text" name="clinic_owner" 
                                                class="form-control " required value="{{ old('clinic_owner') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Clinic Owner Phone')</label>
                                            <input type="text" name="clinic_owner_phone" 
                                                class="form-control " required value="{{ old('clinic_owner_phone') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> @lang('Address')</label>
                                            <input type="text" id="address" class="form-control" value="{{ old('address') }}" name="address" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                                    <input type="hidden" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">

                                    <input type="hidden" name="country" id="country" value="{{ old('country') }}">
                                    <input type="hidden" name="state" id="state" value="{{ old('state') }}">
                                    <input type="hidden" name="city" id="city" value="{{ old('city') }}">


                                    <div class="col-sm-12">
                                        <div class="form-group" id="select2-wrapper">
                                            <label>@lang('Assign Doctors')</label>
                                            <select class="select2-multi-select form-control" name="doctor_id[]"
                                                multiple="multiple" required>
                                                <option disabled>@lang('Select One')</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}">{{ __($doctor->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Meta Title')</label>
                                            <input type="text" name="meta_title" 
                                                class="form-control " value="{{ old('meta_title') }}"  />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Meta Keywords')</label>
                                            <input type="text" name="meta_keywords" 
                                                class="form-control " value="{{ old('meta_keywords') }}" />
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> @lang('Meta Description')</label>
                                            <textarea name="meta_description" class="form-control" rows="4">{{ old('meta_description') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> @lang('Description')</label>
                                            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
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
    <a href="{{ route('admin.clinics.index') }}" class="btn btn-sm btn-outline--primary"><i class="la la-undo"></i>
        @lang('Back') </a>
@endpush

@push('script')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-r86tO9gWZxzRiELiw3DQYa2D3_o1CVk&libraries=places"></script>
    <script>
        (function($) {
            'use strict';
            $('.select2-multi-select').select2({
                dropdownParent: $('#select2-wrapper')
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
        #select2-wrapper {
            position: relative;
        }
    </style>
@endpush
