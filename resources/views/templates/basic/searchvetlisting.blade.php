@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="appoint-section ptb-80">
        <div class="container">
            <div class="row justify-content-center ml-b-30">
                @forelse($doctors as $doctor)
                    <div class="col-lg-3 col-md-6 col-sm-6 mrb-30" style="display: flex;">
                        <div class="booking-item" style="padding-bottom: 50px; position: relative;">
                            <div class="booking-thumb">
                                
                                <a href="{{ route('doctors.booking', $doctor->id) }}">
                                <img src="{{ getImage(getFilePath('doctorProfile') . '/' . @$doctor->image, getFileSize('doctorProfile')) }}"
                                    alt="@lang('booking')">
                                    {{-- @if (isset($doctor->department->id))
                                        <span class="doc-deg">
                                        <a href="{{ route('doctors.departments', $doctor->department->id) }}">{{ __($doctor->department->name) }}</a>
                                        </span>
                                    @endif     --}}
                                @if ($doctor->featured)
                                    <span class="fav-btn"><i class="fas fa-medal"></i></span>
                                @endif
                                @if(isset(auth()->guard('user')->user()->id))
                                    <span class="bottom-0 btn position-absolute" style="right: 0px" onclick="addToFavorite('{{ $doctor->id }}');">
                                        @if(isset($doctor['favorite']) && $doctor['favorite']->pluck('user_id')->contains(auth()->guard('user')->user()->id))
                                            <i id="favorite-icon-{{ $doctor->id }}" class="fas fa-star text-color fs-4"></i>
                                        @else
                                            <i id="favorite-icon-{{ $doctor->id }}" class="far fa-star text-color fs-4"></i>
                                        @endif
                                    </span>
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
                                    <a href="{{ route('doctors.booking', $doctor->id) }}" class="cmn-btn w-100 text-center">@lang('Get Appointment')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-12 col-md-12 col-sm-12 mrb-30">
                        <div class="booking-item text-center">
                            <h3 class="title mt-2">{{ __($emptyMessage) }}</h3>
                            <div class="booking-btn mt-4 mb-2">
                                <a href="javascript:window.history.back();" class="cmn-btn">@lang('Go Back')</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            {{ $doctors->links() }}
        </div>
        <div class="booking-btn mb-2 mt-4 text-center">
            <a href="javascript:window.history.back();" class="cmn-btn">@lang('Go Back')</a>
        </div>
    </section>
@endsection
