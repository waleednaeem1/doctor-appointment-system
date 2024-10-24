@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Information of') {{ $petDetail ? $petDetail->name : '' }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4" style="text-align: center;">
                            <img src="{{ getImage(getFilePath('pets') . '/' . @$petImage->attachment, getFileSize('pets')) }}"
                            alt="@lang('Image')">
                        </div>
                        <div class="col-md-8">
                            <h5 class="card-title mb-3">@lang('Details of') {{ $petDetail ? $petDetail->name : '' }}</h5>
                            <p><b>Pet Name:</b> {{$petDetail ? $petDetail->name : ''}} </p>
                            <p><b>Species:</b> {{$petspecie ? $petspecie->name : ''}} </p>
                            <p><b>Breed:</b> {{$petDetail ? $petDetail->breed : ''}} </p>
                            <p><b>Gender:</b> {{$petDetail ? $petDetail->gender : ''}} </p>
                            <p><b>Weight:</b> {{$petDetail ? $petDetail->weight : ''}}{{ $petDetail ? $petDetail->unit : '' }} </p>
                            <p><b>Age:</b> {{$petDetail ? $petDetail->age : ''}}{{ $petDetail ? $petDetail->age_in : '' }} </p>
                            <p><b>Short Description:</b> {{$petDetail ? $petDetail->short_description : ''}} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.pets.index') }}" class="btn btn-sm btn-outline--primary"><i class="la la-undo"></i>
        @lang('Back') </a>
@endpush
