@extends($activeTemplate . 'layouts.frontend')
@section('content')
<section class="appoint-section ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center" style="background-color: #ffffff; padding:2rem;">
                <img src="{{ getImage(getFilePath('clinic') . '/' . $clinic->logo, getFileSize('clinic')) }}" style="width:350px; height:350px;" />
            </div>
            <div class="col-md-6" style="background-color: #ffffff; padding-top:2rem;">
                <h2>{{ $clinic ? $clinic->name : '' }} Details:</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">Name: </span>
                            {{ $clinic ? $clinic->name : '' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">Phone: </span>
                            {{ $clinic ? $clinic->phone : '' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">Timing: </span>
                            {{ $clinic ? $clinic->timings : '' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">Week Days: </span>
                            {{ $clinic ? $clinic->week_days : '' }}
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">Owner: </span>
                            {{ $clinic ? $clinic->clinic_owner : '' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">Owner Phone: </span>
                            {{ $clinic ? $clinic->clinic_owner_phone : '' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">Country: </span>
                            {{ $country? $country->name : '' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">State: </span>
                            {{ $state ? $state->name : '' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">City: </span>
                            {{ $clinic ? $clinic->city : '' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">Address: </span>
                            {{ $clinic ? $clinic->adress : '' }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">Departments: </span>
                            @if (isset($departments))
                            @foreach ($departments as $index => $department)
                                {{ $department->name }}
                                @if ($index < count($departments) - 1)
                                    ,
                                @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div style="padding: 5px 0px !important;"><span style="font-size: 18px; font-weight: 600;">Description: </span>
                            {{ $clinic ? $clinic->description : '' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="booking-search-area">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="appoint-content">
                        <form class="appoint-form" action="{{ route('clinics.doctorSearch') }}" method="get" style="justify-content: left;">
                            <div class="search-location form-group">
                                <div class="appoint-select">
                                    <select class="chosen-select locations" name="name">
                                        <option value="" selected disabled>@lang('Doctor Name')</option>
                                        @forelse($doctors as $doctor)
                                            <option value="{{ $doctor->name }}" @selected($doctor->name == request()->doctor)>
                                                {{ __($doctor->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="id" value="{{ $clinic ? $clinic->id : '' }}">
                            </div>
                            <button type="submit" class="search-btn cmn-btn"><i class="las la-search"></i></button>
                            <button type="submit" class="search-btn cmn-btn" style="margin-left:10px;"><a href="javascript:window.history.back();">Reset</a></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="booking-search-area">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <h2 style="text-align: left;">{{ $clinic ? $clinic->name : '' }} Doctors</h2>
                </div>
            </div>
        </div>
        <div class="row" style="padding-top: 30px;">
            @forelse($doctors as $doctor)
                <div class="col-lg-3 col-md-6 col-sm-6 mrb-30" style="display: flex;">
                    <div class="booking-item" style="padding-bottom: 50px; position: relative;">
                        <div class="booking-thumb">
                            <a href="{{ route('doctors.booking', [$doctor->id,$doctor->id]) }}" ><img src="{{ getImage(getFilePath('doctorProfile') . '/' . @$doctor->image, getFileSize('doctorProfile')) }}"
                                alt="@lang('booking')"></a>
                        </div>
                        <div class="booking-content">
                            <span class="sub-title">
                            @if(isset($doctor['clinicsViseDepartment']->id))
                                <span class="sub-title">
                                    {{ __($doctor['clinicsViseDepartment']->name) }}
                                </span>
                            @endif
                            </span>
                            <h5 class="title mb-3">{{ __($doctor->name) }}<i class="fas fa-check-circle text-success"></i></h5>
                            <p>Qualification:{{ $doctor ? $doctor->qualification : '' }}</p>
                            <p>Fees: <i class="fas fa-dollar-sign"></i>{{' '.$doctor ? $doctor->fees : '' }}</p>
                            <div class="booking-btn for-booking-class">
                                <a href="{{ route('doctors.booking', [$doctor->id,$doctor->id]) }}" class="cmn-btn w-100 text-center">@lang('Get Appointment')</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-lg-12 col-md-12 col-sm-12 mrb-30">
                    <div class="booking-item text-center">
                        <h3 class="title mt-2">{{ __($emptyMessage) }}</h3>
                    </div>
                </div>
            @endforelse
        </div>
        {{ $doctors->links() }}
    </div>
</section>
@endsection
