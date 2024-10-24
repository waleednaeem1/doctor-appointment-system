@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="appoint-section ptb-80">
        <div class="container">
            @if(isset($pageType) && $pageType !=='nearbyVets')
                @if(isset(auth()->guard('user')->user()->id))
                    <a class="cmn-btn mb-2" href="{{ route('doctors.favoriteDoctorList')}}">Favorite Doctors</a>
                @endif
            @endif
            @if(isset($pageType) && $pageType =='nearbyVets')
                <form class="appoint-form mb-4" action="{{ route('doctors.nearByVets') }}" method="POST" id="searchForm">
                    @csrf
                    <input type="hidden" id="autoUserLatitude" name="autoUserLatitude" value="{{$userLatitude}}">
                    <input type="hidden" id="autoUserLongitude" name="autoUserLongitude" value="{{$userLongitude}}">
                    <div class="form-group">
                        <label for="radiusSelect">Select Radius:</label>
                        <select class="form-control" id="radiusSelect" name="radius">
                            <option value="10" {{ $radius == 10 ? 'selected' : '' }}>10 miles</option>
                            <option value="20" {{ $radius == 20 ? 'selected' : '' }}>20 miles</option>
                            <option value="30" {{ $radius == 30 ? 'selected' : '' }}>30 miles</option>
                            <option value="40" {{ $radius == 40 ? 'selected' : '' }}>40 miles</option>
                            <option value="100" {{ $radius == 100 ? 'selected' : '' }}>100 miles</option>
                            {{-- <option value="10000" {{ $radius == 10000 ? 'selected' : '' }}>10000 miles</option> --}}
                        </select>
                    </div>
                    <button type="submit" class="cmn-btn w-100 text-center">Find Vets according to your current location</button>
                </form>
            @endif

            @if(isset($pageType) && $pageType !=='nearbyVets')
                <div class="booking-search-area">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 text-center">
                            <div class="appoint-content">
                                <form class="appoint-form" action="{{ route('doctors.search') }}" method="get">
                                    <div class="search-location form-group">
                                        <div class="appoint-select">
                                            <select class="chosen-select locations" name="species">
                                            @if (!request()->species)
                                            <option value="" selected disabled>@lang('Species')</option>
                                            @endif
                                                @foreach ($species as $specie)
                                                    <option value="{{ $specie->id }}" @selected($specie->id == request()->species)>
                                                        {{ __($specie->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="search-location form-group">
                                        <div class="appoint-select">
                                            <select class="chosen-select locations" name="state">
                                                @if (!request()->state)
                                                <option value="" selected disabled>@lang('States')</option>
                                                @endif

                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}" @selected($state->id == request()->state)>
                                                        {{ __($state->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="search-location form-group">
                                        <div class="appoint-select">
                                            <select class="chosen-select locations" name="city">
                                                @if (!request()->city)
                                                <option value="" selected disabled>@lang('Cities')</option>
                                                @endif

                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" @selected($city->id == request()->city)>
                                                        {{ __($city->city_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="search-location form-group">
                                        <div class="appoint-select">
                                            <select class="chosen-select locations" name="department">

                                                @if (!request()->department)
                                                <option value="" selected disabled>@lang('Department')</option>
                                                @endif
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}" @selected($department->id == request()->department)>
                                                        {{ __($department->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="search-location form-group">
                                        <div class="appoint-select">
                                            <select class="chosen-select locations" name="doctor">
                                                @if (!request()->doctor)
                                                <option value="" selected disabled>@lang('Doctors')</option>
                                                @endif

                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}" @selected($doctor->id == request()->doctor)>
                                                        {{ __($doctor->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="search-btn cmn-btn"><i class="las la-search"></i></button>
                                    <button type="submit" class="search-btn cmn-btn" style="margin-left:10px;"><a href="{{ route('doctors.all') }}">Reset</a></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($pageType) && $pageType !=='nearbyVets')
                @if ($doctors->hasPages())
                    <div class="mb-4" style="font-weight: bold;">
                        Doctors {{ $doctors->firstItem() }}-{{ $doctors->lastItem() }} OF {{$doctors->total()}}
                    </div>
                @endif
            @endif
            <div class="row justify-content-center ml-b-30">
                @forelse($doctors as $doctor)
                    <div class="col-lg-3 col-md-6 col-sm-6 mrb-30" style="display: flex;">
                        <div class="booking-item" style="padding-bottom: 50px; position: relative;">
                            <div class="booking-thumb">
                                <a href="{{ route('doctors.booking', [$doctor->id,$doctor->id]) }}" >
                                <img src="{{ getImage(getFilePath('doctorProfile') . '/' . @$doctor->image, getFileSize('doctorProfile')) }}"
                                    alt="@lang('booking')">
                                    {{-- @if (isset($doctor->department->id))
                                        <span class="doc-deg">
                                        <a href="{{ route('doctors.departments', $doctor->department->id) }}">{{ __($doctor->department->name) }}</a>
                                        </span>
                                    @endif     --}}
                                @php
                                    $currentDate = date('Y-m-d', strtotime(\Carbon\Carbon::now()));
                                @endphp
                                @if ($doctor->featured)
                                    <span class="fav-btn"><i class="fas fa-medal"></i></span>
                                @endif
                                @if ($doctor->emergency_dealing != Status::NO && in_array($currentDate, json_decode($doctor->weekday)) && $doctor->serial_day && $doctor->serial_or_slot)
                                <span class="emergency-deal">Deal in Emergency</span>
                                @endif
                                @if(isset($pageType) && $pageType !=='nearbyVets')
                                    @if(isset(auth()->guard('user')->user()->id))
                                        <span class="bottom-0 btn position-absolute" style="right: 0px" onclick="addToFavorite('{{ $doctor->id }}');">
                                            @if(isset($doctor['favorite']) && $doctor['favorite']->pluck('user_id')->contains(auth()->guard('user')->user()->id))
                                                <i id="favorite-icon-{{ $doctor->id }}" class="fas fa-star text-color fs-4"></i>
                                            @else
                                                <i id="favorite-icon-{{ $doctor->id }}" class="far fa-star text-color fs-4"></i>
                                            @endif
                                        </span>
                                    @endif
                                @endif
                                </a>
                            </div>
                            <div class="booking-content">
                            @if(isset($doctor->department) && $doctor->department !=='')
                                <span class="sub-title">
                                    <a href="{{ route('doctors.departments', $doctor->department->id) }}">{{ __($doctor->department->name) }}</a>
                                </span>
                            @endif
                                <h5 class="title">{{ __($doctor->name) }} <i class="fas fa-check-circle text-success"></i></h5>
                                <p>{{ strLimit(__($doctor->qualification), 50) }}</p>
                                <ul class="booking-list">
                                    @if (isset($doctor->location->id))
                                    <li><i class="fas fa-street-view"></i>
                                        <a href="{{ route('doctors.locations', $doctor->location->id) }}">{{ __($doctor->location->name) }}</a>
                                    </li>
                                    @endif
                                    <li><i class="fas fa-dollar-sign"></i> {{ __($doctor->fees) }}</li>

                                </ul>
                                <div class="booking-btn for-booking-class">
                                    <a href="{{ route('doctors.booking', [$doctor->id,$doctor->id]) }}" class="cmn-btn w-100 text-center">@lang('Get Appointment')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-12 col-md-12 col-sm-12 mrb-30">
                        <div class="booking-item text-center">
                            {{-- <h3 class="title mt-2">{{ __($emptyMessage) }}</h3> --}}
                            <h3 class="title mt-2">{{ __($vetMessage) }}</h3>
                            <div class="booking-btn mt-4 mb-2">
                                <a href="javascript:window.history.back();" class="cmn-btn">@lang('Go Back')</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            @if(isset($pageType) && $pageType !=='nearbyVets')
                {{ $doctors->links() }}
            @endif
        </div>
    </section>
@endsection
