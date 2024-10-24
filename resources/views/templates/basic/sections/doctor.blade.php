@php
    $doctorContent = getContent('doctor.content', true);
    $doctors    = \App\Models\Doctor::active()
        ->with('department', 'location')
        ->orderBy('id', 'DESC')
        ->get(['id', 'name', 'qualification', 'fees', 'image', 'department_id', 'location_id']);
@endphp
<!-- our Doctors section start -->
<section class="booking-section ptb-80">
    <div class="container-fluid">
        <div class="row ml-b-20">
            <div class="booking-right-area">
                <div class="col-lg-12">
                    <div class="section-header">
                        <h2 class="section-title">{{ __($doctorContent->data_values->heading) }}</h2>
                        <p class="m-0">{{ __($doctorContent->data_values->subheading) }}</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="booking-slider">
                        <div class="swiper-wrapper">
                            @foreach ($doctors as $doctor)
                                <div class="swiper-slide">
                                    <div class="booking-item" style="height: 380px; display: flex; flex-direction: column;">
                                        <div class="booking-thumb">
                                            <a href="{{ route('doctors.booking',[$doctor->id,$doctor->id]) }}"><img src="{{ getImage(getFilePath('doctorProfile') . '/' . @$doctor->image, getFileSize('doctorProfile')) }}" alt="@lang('doctor')"></a>
                                            {{-- @if(isset($doctor->department) && $doctor->department !=='')
                                                <div class="doc-deg">{{ __($doctor->department->name) }}</div>
                                            @endif --}}
                                            @if ($doctor->featured)
                                                <span class="fav-btn"><i class="las la-medal"></i></span>
                                            @endif
                                        </div>
                                        <div class="booking-content">
                                            @if(isset($doctor->department) && $doctor->department !=='')
                                                <span class="sub-title">
                                                    <a href="{{ route('doctors.departments', $doctor->department->id) }}">{{ __($doctor->department->name) }}</a>
                                                </span>
                                            @endif
                                            <h5 class="title">{{ __($doctor->name) }}<i
                                                    class="fas fa-check-circle m-0"></i></h5>
                                            <p>{{ strLimit(__($doctor->qualification), 50) }}</p>
                                            <ul class="booking-list">
                                                {{-- <li><i class="las la-street-view"></i><a
                                                        href="{{ route('doctors.locations', $doctor->location->id) }}">{{ __($doctor->location->name) }}</a>
                                                </li> --}}
                                                {{-- <li><i class="fas fa-dollar-sign"></i> {{ __($doctor->fees) }}</li> --}}
                                            </ul>
                                            <div class="booking-btn for-booking-class">
                                                <a href="{{ route('doctors.booking',[$doctor->id,$doctor->id]) }}" class="cmn-btn w-100 text-center">@lang('Get Appointment')</a>
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
            </div>
        </div>
    </div>
</section>
<!-- booking-section end -->
