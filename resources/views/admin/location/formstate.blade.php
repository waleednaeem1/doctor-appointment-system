@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.location.storestate') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-1">
                             
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('State Name')</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control " required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-2">
                                        
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
    <a href="{{ route('admin.location.states') }}" class="btn btn-sm btn-outline--primary"><i class="la la-undo"></i>
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
