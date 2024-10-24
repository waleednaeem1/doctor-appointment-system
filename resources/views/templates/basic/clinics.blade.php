@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="appoint-section ptb-80">
        <div class="container">
            <div class="booking-search-area">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <div class="appoint-content">
                            <form class="appoint-form" action="{{ route('clinics.search') }}" method="get">
                                <div class="search-location form-group">
                                    <div class="appoint-select">
                                        <select class="chosen-select locations" name="states" @if(request()->states) @disabled(true) @endif >
                                            <option value="" >@lang('States')</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" @selected($state->id == request()->states)>
                                                    {{ __($state->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="search-location form-group">
                                    <div class="appoint-select">
                                        <select class="chosen-select locations" name="name" @if(request()->name) @disabled(true) @endif>
                                            <option value="">@lang('Clinics Name')</option>
                                            @foreach ($allclinics as $clinic)
                                                <option value="{{ $clinic->name }}" @if($clinic->name == request()->name) selected @endif>
                                                    {{ __($clinic->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="search-btn cmn-btn"><i class="las la-search"></i></button>
                                <button type="submit" class="search-btn cmn-btn" style="margin-left:10px;"><a href="{{ route('clinics.all') }}">Reset</a></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @if ($clinics->hasPages())
            <div class="mb-4" style="font-weight: bold;">
                Clinics {{ $clinics->firstItem() }}-{{ $clinics->lastItem() }} OF {{$clinics->total()}}
            </div>
            @endif
            <div class="row justify-content-center ml-b-30">
                @forelse($clinics as $clinic)
                    <div class="col-lg-3 col-md-6 col-sm-6 mrb-30" style="display: flex;">
                        <div class="booking-item" style="padding-bottom: 50px; position: relative;">
                            <div class="booking-thumb">
                                <a href="{{ route('clinics.details', @$clinic->id) }}"><img src="{{ getImage(getFilePath('clinic') . '/' . @$clinic->logo, getFileSize('clinic')) }}"
                                    alt="@lang('booking')"></a>
                            </div>
                            <div class="booking-content">
                                <h5 class="title mb-3"><a href="{{ route('clinics.details', @$clinic->id) }}">{{ __(@$clinic->name) }}<i class="fas fa-check-circle text-success"></i></a></h5>
                                <p>Timing: {{$clinic ? $clinic->timings : ''}}</p>
                                <p>City: {{$clinic ? $clinic->city : ''}}</p>
                                @if (isset($clinic->phone) && $clinic->phone !== '')
                                    <p>Phone: {{$clinic ? $clinic->phone : ''}}</p>
                                @endif
                                <div class="booking-btn for-booking-class">
                                    <a href="{{ route('clinics.details', @$clinic->id) }}" class="cmn-btn w-100 text-center">@lang('View Details')</a>
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
            {{ $clinics->links() }}
        </div>
    </section>
@endsection

@if(request()->states || request()->name  )

<style>
    .appoint-content .appoint-form .appoint-select .chosen-container .chosen-single span {
        color: black !important;
    }
</style>
@endif
