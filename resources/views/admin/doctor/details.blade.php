@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="row gy-4">
                <div class="col-xxl-4 col-sm-6">
                    <x-widget link="#" icon="las la-wallet f-size--56" title="Total Online Earn"
                        value="{{ $general->cur_sym }}{{ showAmount($totalOnlineEarn) }}" bg="19" />
                </div>
                <!-- dashboard-w1 end -->
                <div class="col-xxl-4 col-sm-6">
                    <x-widget link="#" icon="las la-hand-holding-usd f-size--56" title="Total Cash Earn"
                        value="{{ $general->cur_sym }}{{ showAmount($totalCashEarn) }}" bg="11" />
                </div>
                <!-- dashboard-w1 end -->
                <div class="col-xxl-4 col-sm-6">
                    <x-widget link="#" icon="las la-handshake f-size--56" title="Total Appointments"
                        value="{{ $totalAppointments }}" bg="3" />
                </div>
                <!-- dashboard-w1 end -->
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="3" icon="las la-check-circle f-size--56" title="Total Done Appointments"
                        value="{{ $completeAppointments }}" bg="success" />
                </div>
                <!-- dashboard-w1 end -->
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="3" icon="las la-calendar-day f-size--56" title="Total Booking Days"
                        value="{{ $doctor->serial_day }}" bg="primary" />
                </div>
                <!-- dashboard-w1 end -->
                <div class="col-xxl-4 col-sm-6">
                    <x-widget style="3" icon="las la-trash f-size--56" title="Total Trashed Appointments" value="{{ $trashedAppointments }}" bg="danger" />
                </div>
                <!-- dashboard-w1 end -->
            </div>

            <div class="d-flex flex-wrap gap-3 mt-4">
                <div class="flex-fill">
                    <a href="{{ route('admin.doctor.login.history', $doctor->id) }}" class="btn btn--primary w-100 btn-lg">
                        <i class="las la-history"></i>@lang('Login History')
                    </a>
                </div>
                <div class="flex-fill">
                    <a href="{{ route('admin.doctor.notification.log', $doctor->id) }}"
                        class="btn btn--warning w-100 btn-lg">
                        <i class="las la-envelope"></i>@lang('Notification Logs')
                    </a>
                </div>
                <div class="flex-fill">
                    <a href="{{ route('admin.doctor.login', $doctor->id) }}" target="_blank"
                        class="btn btn--primary btn--gradi w-100 btn-lg">
                        <i class="las la-sign-in-alt"></i>@lang('Login as Doctor')
                    </a>
                </div>
                <div class="flex-fill">
                    <a href="{{ route('admin.doctor.reviews', $doctor->id) }}" target="_blank"
                        class="btn btn--primary btn--gradi w-100 btn-lg">
                        <i class="las la-sign-in-alt"></i>@lang('See Reviews')
                    </a>
                </div>
            </div>
            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Information of') {{ $doctor->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.doctor.store', $doctor->id) }}" method="POST" enctype="multipart/form-data">
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
                                                        style="background-image: url({{ getImage(getFilePath('doctorProfile') . '/' . $doctor->image, getFileSize('doctorProfile')) }})">
                                                        <button type="button" class="remove-image"><i
                                                                class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                                <div class="avatar-edit mt-0">
                                                    <input type="file" class="profilePicUpload" name="image"
                                                        value="{{ $doctor->image }}" id="profilePicUpload1"
                                                        accept=".png, .jpg, .jpeg">
                                                    <label for="profilePicUpload1"
                                                        class="btn btn--success btn-block btn-lg">@lang('Upload')</label>
                                                    <small>@lang('Support Images'):
                                                        <b>@lang('jpeg'), @lang('jpg'), @lang('png'),</b> @lang('resized into 400x400px')
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
                                            <input type="text" name="name" value="{{ $doctor->name }}"
                                                class="form-control " required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Username')</label>
                                            <input type="text" name="username" value="{{ $doctor->username }}"
                                                class="form-control " required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('E-mail')</label>
                                            <input type="text" name="email" value="{{ $doctor->email }}"
                                                class="form-control " required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Mobile')</label>
                                            <div class="input-group">
                                                <input type="text" name="mobile"
                                                    value="{{ str_replace($general->country_code, '', $doctor->mobile) }}"
                                                    class="form-control " autocomplete="off" >
                                            </div>
                                        </div>
                                    </div>

                                    {{-- @php
                                        $currentcategories = explode(",",$doctor->categories); 
                                    @endphp
                                    <div class="col-sm-6">
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

                                    {{-- @php
                                        $currentpettype = explode(",",$doctor->pet_type_id); 
                                    @endphp
                                    <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-four">
                                            <label>@lang('Pet Type')</label>
                                            <select class="select2-multi-select form-control" name="pet_type[]" multiple="multiple" required>
                                                <option disabled >@lang('Select multiple')</option>
                                                @foreach ($petType as $pType)
                                                <option  value="{{ $pType->id }}" @if(in_array($pType->id, $currentpettype )) selected="selected" @endif >
                                                        {{ __($pType->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- @php
                                        $currentpet_disese_id = explode(",",$doctor->pet_disese_id); 
                                    @endphp
                                    <div class="col-sm-12">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-five">
                                            <label>@lang('Pet Disese')</label>
                                            <select class="select2-multi-select form-control" name="pet_disese_id[]" multiple="multiple" required>
                                                <option disabled >@lang('Select multiple')</option>
                                                @foreach ($PetDisease as $pdisese)
                                                <option  value="{{ $pdisese->id }}" @if(in_array($pdisese->id,$currentpet_disese_id)) selected="selected" @endif >
                                                        {{ __($pdisese->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    --}}

                                    {{-- <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Fees')</label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{ $general->cur_sym }}</span>
                                                <input type="number" name="fees" value="{{ $doctor->fees }}"
                                                    class="form-control" required />
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Qualification')</label>
                                            <input type="text" name="qualification"
                                                value="{{ $doctor->qualification }}" class="form-control" required />
                                        </div>
                                    </div>

                                    <!--start new fields -->
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-country">
                                            <label>@lang('Country')</label>
                                            <select class="select2-basic-one form-control" name="country" required id="country_id" >
                                                <option disabled selected>@lang('Select One')</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}" @selected($country->id == $doctor->country_id)>
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
                                                    <option  value="{{ $state->id }}" @selected($state->id == $doctor->state_id )>
                                                        {{ __($state->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-city">
                                            <label>@lang('City')</label>
                                            <select class="select2-basic-one form-control" name="city" required id="cities">
                                                <option disabled selected>@lang('Select One')</option>
                                                @foreach ($cities as $city)
                                                    <option  value="{{ $city->id }}" @selected($city->id == $doctor->city_id)>
                                                        {{ __($city->city_name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Postal Code')</label>
                                            <input type="text" name="item_postal_code" 
                                                class="form-control " required value="{{ $doctor->item_postal_code }}" />
                                        </div>
                                    </div> --}}
                                    <!--end new fields -->

                                    {{-- <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>@lang('Address') </label>
                                            <textarea name="address" class="form-control" required>{{ $doctor->address }}</textarea>
                                        </div>
                                    </div> --}}

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> @lang('Address')</label>
                                            <input type="text" id="address" class="form-control" value="{{ $doctor->address }}" name="address" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <input type="hidden" name="latitude" id="latitude" value="{{ $doctor->item_lat }}">
                                    <input type="hidden" name="longitude" id="longitude" value="{{ $doctor->item_lng }}">
                                    <input type="hidden" name="postal_code" id="postal_code" value="{{ $doctor->item_postal_code }}">

                                    <input type="hidden" name="country_id" id="country" value="{{ $doctor->country_id }}">
                                    <input type="hidden" name="state_id" id="state" value="{{ $doctor->state_id }}">
                                    <input type="hidden" name="city_id" id="city" value="{{ $doctor->city_id }}">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('New Password')</label>
                                            <input type="password" autocomplete="off" name="password" class="form-control " />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Confirm Password')</label>
                                            <input type="password" autocomplete="off" name="password_confirmation" class="form-control "  />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Website')</label>
                                            <input type="text" name="item_website" 
                                                class="form-control "  value="{{ $doctor->item_website }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Facebook')</label>
                                            <input type="text" name="item_social_facebook" 
                                                class="form-control "  value="{{ $doctor->item_social_facebook }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Twitter')</label>
                                            <input type="text" name="item_social_twitter" 
                                                class="form-control "  value="{{ $doctor->item_social_twitter }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('LinkedIn')</label>
                                            <input type="text" name="item_social_linkedin" 
                                                class="form-control "  value="{{ $doctor->item_social_linkedin }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('WhatsApp')</label>
                                            <input type="text" name="item_social_whatsapp" 
                                                class="form-control "  value="{{ $doctor->item_social_whatsapp }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Instagram')</label>
                                            <input type="text" name="item_social_instagram" 
                                                class="form-control "  value="{{ $doctor->item_social_instagram }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="from-group">
                                            <label class="required">@lang('About') </label>
                                            <textarea name="about" rows="4" class="form-control" required>{{ $doctor->about }}</textarea>
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
                                                            @php
                                                                $i=0;
                                                            @endphp
                                                            @foreach ($petType as $ptype)
                                                                @php
                                                                    //get record for selected checkbox
                                                                    $pet_diseases_on_type_basis  = DB::table('pet_diseases_on_type_basis')->where('doc_id',$doctor->id)->where('pet_type_id',$ptype->id)->first();
                                                                    //dd($pet_diseases_on_type_basis->disease_id);    
                                                                    if($pet_diseases_on_type_basis){
                                                                        $currentpet_disese_id = explode(",",$pet_diseases_on_type_basis->disease_id); 
                                                                        $getPetType =  $pet_diseases_on_type_basis->pet_type_id;                                                                                           
                                                                    }else{
                                                                        $currentpet_disese_id=[];
                                                                        $getPetType="";
                                                                    }
                                                                @endphp
                                                                <tr>
                                                                    <td><span class="fw-bold"> <input class="form-check-input" type="checkbox" name="pet_type_id[{{ $ptype->id }}]" value="{{ $ptype->id }}" @if ($getPetType == $ptype->id ) @checked(true) @endif   > &nbsp; {{ $ptype->name }}</span></td>
                                                                    <td style="text-align: left;"> 
                                                                        @foreach ($PetDisease as $ptdeses)
                                                                            <input class="form-check-input" type="checkbox" name="disease_id[{{ $ptype->id }}][]" value="{{ $ptdeses->id }}" @if (in_array($ptdeses->id,$currentpet_disese_id) ) @checked(true) @endif    >&nbsp;<span class="fw-bold "  >{{ $ptdeses->name }}</span>&nbsp;&nbsp;&nbsp;
                                                                        @endforeach
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $i++;
                                                                @endphp
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
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
