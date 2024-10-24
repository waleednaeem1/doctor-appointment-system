@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.hospital.store') }}" method="POST" enctype="multipart/form-data">
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
                                                        style="background-image: url({{ getImage(getFilePath('hospital') . '/' . @$hospital->logo, getFileSize('hospital')) }})">
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
                                    
                                    
                                    <div class="col-sm-6">
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
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Address')</label>
                                            <input type="text" name="adress" 
                                                class="form-control " required value="{{ old('adress') }}"
                                                />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group select2-wrapper" id="select2-wrapper">
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
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Hospital Owner')</label>
                                            <input type="text" name="hospital_owner" 
                                                class="form-control " required value="{{ old('hospital_owner') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Hospital Owner Phone')</label>
                                            <input type="text" name="hospital_owner_phone" 
                                                class="form-control " required value="{{ old('hospital_owner_phone') }}" />
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
                                            <textarea name="meta_description" class="form-control" >{{ old('meta_description') }}</textarea>
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
    <a href="{{ route('admin.hospital.index') }}" class="btn btn-sm btn-outline--primary"><i class="la la-undo"></i>
        @lang('Back') </a>
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';
            $('.select2-multi-select').select2({
                dropdownParent: $('#select2-wrapper')
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        #select2-wrapper {
            position: relative;
        }
    </style>
@endpush
