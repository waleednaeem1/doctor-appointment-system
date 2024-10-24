@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.pets.store') }}" method="POST" enctype="multipart/form-data">
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
                                                        style="background-image: url({{ getImage(getFilePath('pets') . '/' . @$pet_type->image, getFileSize('pets')) }})">
                                                        <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                                <div class="avatar-edit mt-0">
                                                    <input type="file" class="profilePicUpload" name="image"
                                                        id="profilePicUpload1" accept=".png, .jpg, .jpeg, .webp" required>
                                                    <label for="profilePicUpload1"
                                                        class="btn btn--success btn-block btn-lg">@lang('Upload')</label>
                                                    <small>@lang('Support Images'):
                                                        <b>@lang('jpeg'),@lang('webp'),@lang('png'), @lang('jpg').</b> @lang(', resized into 400x400px')
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                                <input type="text" name="name" value="{{ old('name') }}" class="form-control " required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6"></div>
                                </div>
                                <div class="row">
                                    @foreach ($PetDisease as $ptdeses )
                                    <div class="col-sm-6">
                                        <input class="form-check-input" type="checkbox" name="pet_disese_id[]" value="{{ $ptdeses->id }}">&nbsp;{{ $ptdeses->name }}
                                    </div>
                                    @if ($loop->iteration % 2 == 0)
                                        </div><div class="row">
                                    @endif
                                    @endforeach
                                </div>






                            </div>
                        </div>
                        <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn--primary w-10 h-45 ">@lang('Submit')</button>
                                </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <a href="{{ route('admin.pets.listing') }}" class="btn btn-sm btn-outline--primary"><i class="la la-undo"></i>
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
