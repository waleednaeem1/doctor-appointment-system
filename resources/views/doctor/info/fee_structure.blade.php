@extends('doctor.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('doctor.info.fee.structure.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="payment-method-item">
                                    <div class="payment-method-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card border--primary mt-3">
                                                    <div class="card-header bg--primary d-flex justify-content-between">
                                                        <h5 class="text-white">@lang('Fee Structure')</h5>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-light float-end add-speciality-data">
                                                            <i class="la la-fw la-plus"></i>@lang('Add New')</button>
                                                    </div>
                                                </div>
                                                <div class="card-body addedField">
                                                    @if ($feesStructures != null || !empty($feesStructures))
                                                    @forelse ($feesStructures as $feesStructure)
                                                        <div class="row align-items-center speciality-data">
                                                            <div class="col-md-4 mt-3">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input type="text" name="start_time[]" value="{{ $feesStructure->start_time }}" placeholder="@lang('Set start time')" readonly class="form-control" autocomplete="off">
                                                                        <span class="input-group-text"><i class="las la-clock"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mt-3">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input type="text" name="end_time[]" value="{{ $feesStructure->end_time }}" placeholder="@lang('Set end time')" readonly class="form-control" autocomplete="off">
                                                                        <span class="input-group-text"><i class="las la-clock"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 mt-4">
                                                                <div class="form-group">
                                                                    <input name="fees[]" class="form-control" value="{{ $feesStructure->fees }}" type="text" placeholder="@lang('Enter fees')" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1 mt-md-0 text-right">
                                                                <button class="btn btn--danger removeBtn removeBtn--style w-100 h-45" type="button">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <span class="text-center">@lang('Data not found')</span>
                                                    @endforelse
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .removeBtn--style {
            margin-top: 10px;
        }

        @media(max-width: 1199px) {
            .removeBtn--style {
                margin-bottom: 15px;
            }
        }
    </style>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';

        (function($) {
            $('.add-speciality-data').on('click', function() {
            var html = `<div class="row align-items-center speciality-data">
                <div class="col-md-4 mt-3">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="start_time[]" value="" placeholder="@lang('Set start time')" readonly class="form-control time-picker" autocomplete="off">
                            <span class="input-group-text"><i class="las la-clock"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mt-3">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="end_time[]" value="" placeholder="@lang('Set end time')" readonly class="form-control time-picker" autocomplete="off">
                            <span class="input-group-text"><i class="las la-clock"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mt-4">
                        <div class="form-group">
                            <input name="fees[]" class="form-control" type="text" placeholder="@lang('Enter fees')" required>
                        </div>
                    </div>

                <div class="col-md-1 mt-md-0 text-right">
                    <button class="btn btn--danger removeBtn removeBtn--style w-100 h-45" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

            </div>`;
            $('.addedField').append(html);

            initTimePicker();
        });

            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.speciality-data').remove();
            });


            $('.add-speciality-data').on('click', function(){
                $('.card-footer').removeClass('d-none')
            })

            $('select[name=slot_type]').on('change', function() {
                var type = $(this).val();
                schedule(type);
            })

            var type = $('select[name=slot_type]').val();
            if (type) {
                schedule(type);
            }

            function schedule(type) {
                if (type == 1) {
                    $('.duration').addClass('d-none');
                    $('.serial').removeClass('d-none');
                    $('.start').addClass('d-none');
                    $('.end').addClass('d-none');
                } else {
                    $('.start').removeClass('d-none');
                    $('.end').removeClass('d-none');
                    $('.serial').addClass('d-none');
                    $('.duration').removeClass('d-none')
                }
            }

            initTimePicker();

            function initTimePicker() {
                var start = new Date();
                start.setHours(9);
                start.setMinutes(0);

                $('.time-picker').datepicker({
                    onlyTimepicker: true,
                    timepicker: true,
                    startDate: start,
                    language: 'en',
                    minHours: 0,
                    maxHours: 23,
                });
            }

        })(jQuery);
    </script>
@endpush

