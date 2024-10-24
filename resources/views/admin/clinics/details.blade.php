@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Information of') {{ $clinics->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.clinics.store', $clinics->id) }}" method="POST"
                        enctype="multipart/form-data">
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
                                                        style="background-image: url({{ getImage(getFilePath('clinic') . '/' . $clinics->logo, getFileSize('clinic')) }})">
                                                        <button type="button" class="remove-image"><i
                                                                class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                                <div class="avatar-edit mt-0">
                                                    <input type="file" class="profilePicUpload" name="logo"
                                                        value="{{ $clinics->logo }}" id="profilePicUpload1"
                                                        accept=".png, .jpg, .jpeg">
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
                                                class="form-control " required value="{{ $clinics->name }}"
                                                />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Phone')</label>
                                            <input type="text" name="phone" 
                                                class="form-control " required value="{{ $clinics->phone }}"
                                                />
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-country">
                                            <label>@lang('Country')</label>
                                            <select class="select2-basic-one form-control" name="country" required id="country_id" >
                                                <option disabled selected>@lang('Select One')</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}" @selected($country->id == $clinics->country) >
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
                                                    <option  value="{{$state->id }}" @selected($state->id == $clinics->state) >
                                                        {{ __($state->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    
                                    
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group" id="select2-wrapper-staff">
                                            <label>@lang('Assign Staffs')</label>
                                            <select class="select2-multi-select form-control" name="staff_id[]"
                                                multiple="multiple" required>
                                                <option disabled>@lang('Select One')</option>
                                                @foreach ($staffs as $staff)
                                                    <option
                                                    @foreach ($clinics->staffs as $data) @selected($data->id == @$staff->id) @endforeach value="{{ $staff->id }}">{{ $staff->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('City')</label>
                                            <input type="text" name="city" 
                                                class="form-control " required value="{{ $clinics->city }}" />
                                        </div>
                                    </div> --}}
                                    @php
                                        $currentDept = explode(",",$clinics->department); 
                                    @endphp
                                    <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-dept">
                                            <label>@lang('Department')</label>
                                            <select class="select2-multi-select form-control" name="department[]" multiple="multiple" required>
                                                <option disabled >@lang('Select One')</option>
                                                @foreach ($departments as $department)
                                                    <option @if(in_array($department->id, $currentDept ))  selected="selected" @endif value="{{ $department->id }}">
                                                        {{ __($department->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    @php
                                        $currentcategories = explode(",",$clinics->categories); 
                                    @endphp
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-catg">
                                            <label>@lang('Category')</label>
                                            <select class="select2-multi-select form-control" name="categories[]" multiple="multiple" required>
                                                <option disabled >@lang('Select One')</option>
                                                @foreach ($categories as $category)
                                                    <option  value="{{ $category->id }}" @if(in_array($category->id, $currentcategories )) selected="selected" @endif >
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
                                                class="form-control " required value="{{ $clinics->week_days }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Timings')</label>
                                            <input type="text" name="timings" 
                                                class="form-control " required value="{{ $clinics->timings }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Clinic Owner')</label>
                                            <input type="text" name="clinic_owner" 
                                                class="form-control " required value="{{ $clinics->clinic_owner }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Clinic Owner Phone')</label>
                                            <input type="text" name="clinic_owner_phone" 
                                                class="form-control " required value="{{ $clinics->clinic_owner_phone }}" />
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> @lang('Address')</label>
                                            <input type="text" id="address" class="form-control" value="{{ $clinics->address }}" name="address" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <input type="hidden" name="latitude" id="latitude"  value="{{ $clinics->latitude }}">
                                    <input type="hidden" name="longitude" id="longitude"  value="{{ $clinics->longitude }}">
                                    <input type="hidden" name="postal_code" id="postal_code"  value="{{ $clinics->postal_code }}">

                                    <input type="hidden" name="country" id="country"  value="{{ $clinics->country }}">
                                    <input type="hidden" name="state" id="state"  value="{{ $clinics->state }}">
                                    <input type="hidden" name="city" id="city"  value="{{ $clinics->city }}">

                                    <div class="col-sm-12">
                                        <div class="form-group" id="select2-wrapper">
                                            <label>@lang('Assign Doctors')</label>
                                            <select class="select2-multi-select form-control" name="doctor_id[]"
                                                multiple="multiple" required>
                                                <option disabled>@lang('Select One')</option>
                                                @foreach ($doctors as $doctor)
                                                    <option
                                                    @foreach ($clinics->doctors as $data) @selected($data->id == @$doctor->id) @endforeach value="{{ $doctor->id }}">{{ $doctor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Meta Title')</label>
                                            <input type="text" name="meta_title" 
                                                class="form-control " value="{{ $clinics->meta_title }}"  />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Meta Keywords')</label>
                                            <input type="text" name="meta_keywords" 
                                                class="form-control " value="{{ $clinics->meta_keywords }}" />
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> @lang('Meta Description')</label>
                                            <textarea name="meta_description" class="form-control" rows="4" >{{ $clinics->meta_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> @lang('Description')</label>
                                            <textarea name="description" class="form-control" rows="4" >{{ $clinics->description }}</textarea>
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
