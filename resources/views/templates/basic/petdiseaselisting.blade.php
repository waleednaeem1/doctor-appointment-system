@extends($activeTemplate . 'layouts.frontend')
@section('content')

    <section class="contact-item-section pd-t-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="contact-form-area">
                        <div class="row ml-b-30" style="margin-bottom: 10px;">
                            <form method="GET" action="{{ route('allvetsearch') }}" class="cmn-form verify-gcaptcha login-form route">
                                <input type="hidden" name="pet_type_id" value="{{ $id }}" />
                                <div class="row justify-content-center mrt-30">
                                    @if(count($petDisease) > 0)
                                        @foreach($petDisease as $index => $petDises)
                                            <div class="col-md-3 mrb-30">
                                                <div class="booking-item" style="max-height:175px">
                                                    <div class="booking-thumb">
                                                        <img style="height: 70px;object-fit: contain;" src="{{ getImage(getFilePath('petsdisease') . '/' . $petDises->image, getFileSize('petsdisease')) }}" alt="@lang('pets')">
                                                        <span class="fav-btn"><i class="fas fa-medal"></i></span>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="d-flex flex-row align-items-center">
                                                            <div class="p-2">
                                                                <p class="card-text"><span class="title"><input class="form-check-input petdisesechecked" type="checkbox" name="pet_disese_id[]" value="{{ $petDises->id }}" style="vertical-align: middle"  onclick="checkedSubmit()"></span></p>
                                                            </div>
                                                            <div class="p-2">
                                                                {{ $petDises->name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- @if(($index + 1) % 5 == 0)
                                                </div><div class="row justify-content-center mrt-30">
                                            @endif --}}
                                        @endforeach
                                    @endif
                                    <br>
                                    <button type="submit" class="btn cmn-btn w-100" id="btn-process-next-step" style="display:none; ">@lang('Next')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="booking-btn mb-5 text-center">
                <a href="javascript:window.history.back();" class="cmn-btn">@lang('Go Back')</a>
            </div>
        </div>
    </section>
    <!-- contact-item-section end -->
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('input[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){

                $("#btn-process-next-step").show();
            }
            else if($(this).prop("checked") == false){
                if ($('.petdisesechecked').filter(':checked').length < 1){
                    $("#btn-process-next-step").hide();
                }
            }
        });

        })(jQuery);
    </script>
@endpush

