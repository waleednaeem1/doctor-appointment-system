@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.location.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-1">
                             
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('City Name')</label>
                                            <input type="text" name="city_name" value="{{ old('city_name') }}"
                                                class="form-control " required />
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper-one">
                                            <label>@lang('State')</label>
                                            <select class="select2-multi-select form-control" name="state_id"  required>
                                                <option disabled >@lang('Select One')</option>
                                                @foreach ($states as $state)
                                                    <option  value="{{ $state->id }}">
                                                        {{ __($state->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Latitude')</label>
                                            <input type="text" name="city_lat" class="form-control " required value="{{ old('city_lat') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Longitude')</label>
                                            <input type="text" name="city_lng" class="form-control " required value="{{ old('city_lng') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                                    </div>
                                    
                                </div>
                                
                            </div>
                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.location.cities') }}" class="btn btn-sm btn-outline--primary"><i class="la la-undo"></i>
        @lang('Back') </a>
@endpush

@push('script')
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
    </script>
@endpush

@push('style')
    <style>
        .select2-wrapper {
            position: relative;
        }
    </style>
@endpush
