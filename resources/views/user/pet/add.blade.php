@extends('user.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-1 col-lg-4 mb-30">
            <div class="card b-radius--5 overflow-hidden" >

            </div>


        </div>

        <div class="col-xl-9 col-lg-8 mb-30">
            <form action="{{ route('user.mypets') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="card b-radius--10 overflow-hidden box--shadow1 mt-4">
                    <div class="card-body p-0">
                        <div class="row p-3 bg--white">
                            <h3 class="py-2">@lang('Pet Information')</h3>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Pet Name')</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Pet Age')</label>
                                    <div class="input-group">
                                        <input type="number" name="age"  class="form-control" value="{{ old('age') }}" required>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <input type="file" name="images" class="form-control"  accept="image/*" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('video')</label>
                                    <input type="file" id="video" name="video" class="form-control" placeholder="Video"  required accept="video/mp4,video/x-m4v,video/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Previous Medical Record')</label>
                                    <input type="file" id="previous_record" class="form-control" name="previous_record" placeholder="Image"  >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Pet Type')</label>
                                    <select class="form-control" name="pet_type_id" required>
                                        <option value="" selected disabled >@lang('Select one option')</option>
                                        @foreach ($pet_type as $pet )
                                        <option value="{{ $pet->id }}" >{{ $pet->name }}</option>
                                        @endforeach
                                            
                                        
                                      </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>@lang('Short Description')</label>
                                <textarea name="short_description" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $(".available-time").on('click', function() {
                $(this).parent('.time-serial-parent').find('.btn--success').removeClass(
                    'btn--success disabled').addClass('btn--primary');

                $('[name=time_serial]').val($(this).data('value'));
                $(this).removeClass('btn--primary');
                $(this).addClass('btn--success disabled');
            })

        })(jQuery);
    </script>
@endpush
