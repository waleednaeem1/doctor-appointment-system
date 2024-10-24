@extends('doctor.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('doctor.schedule.update') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Slot Type')</label>
                                    <select name="slot_type" id="slot-type" class="form-control" required>
                                        {{-- <option value="" selected disabled>@lang('Select One')</option> --}}
                                        {{-- <option value="1" @selected($doctor->slot_type == 1)>@lang('Serial')</option> --}}
                                        <option value="2" @selected($doctor->slot_type == 2)>@lang('Time')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-6">
                                <div class="form-group">
                                    <label>@lang('For How Many Days')
                                        <i class="fa fa-info-circle text--primary" title="@lang('This will define that your appointment booking will be taken for the next how many days including today. That means with everyday it will add your given value.')">
                                    </i>
                                </label>
                                    <div class="input-group">
                                        {{-- <input class="form-control" type="number" name="serial_day" value="{{ $doctor->serial_day }}" required> --}}
                                        <input class="form-control" type="text" name="serial_day" pattern="\d*" value="7" readonly onkeypress="return onlyNumberKey(event)" maxlength="3" required>
                                        <span class="input-group-text">@lang('Days')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-6 startfirst d-none">
                                <div class="form-group">
                                    <label>@lang('Start Time')</label>
                                    <div class="input-group">
                                        <input type="text" name="start_time" value="{{ old('start_time', $doctor->start_time) }}" readonly class="form-control time-first-picker" autocomplete="off">
                                        <span class="input-group-text"><i class="las la-clock"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-6 endfirst d-none">
                                <div class="form-group">
                                    <label>@lang('End Time')</label>
                                    <div class="input-group">
                                        <input type="text" name="end_time" value="{{ old('end_time', $doctor->end_time) }}" readonly class="form-control time-first-picker" autocomplete="off">
                                        <span class="input-group-text"><i class="las la-clock"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-6">
                                <div class="form-group">
                                    <label>@lang('General Fees')
                                        <i class="fa fa-info-circle text--primary" title="@lang('This will define your fees if select time which is not in your schedule.')">
                                    </i>
                                </label>
                                    <div class="input-group">
                                        {{-- <input class="form-control" type="number" name="serial_day" value="{{ $doctor->serial_day }}" required> --}}
                                        <input class="form-control" type="text" name="fees" pattern="\d*" value="{{ old('fees', $doctor->fees) }}" onkeypress="return onlyNumberKey(event)" maxlength="6" required>
                                        <span class="input-group-text">@lang('USD')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 duration d-none">
                                <div class="form-group">
                                    <label> @lang('Time Duration')</label>
                                    <div class="input-group">
                                        <input type="text" name="duration" class="form-control" onkeypress="return onlyNumberKey(event)" maxlength="2" value="{{ old('duration', $doctor->duration) }}">
                                            <span class="input-group-text">@lang('Minutes')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 duration d-none">
                                <div class="form-group">
                                    <label> @lang('Week Days')</label>
                                    <div class="select-group">
                                        <select name="weekday[]" class="form-control" multiple="multiple" id="weekdaySelect">
                                            @for ($i = 0; $i < 7; $i++)
                                                @php
                                                    $currentDate = \Carbon\Carbon::now()->addDays($i);
                                                    $formattedDate = $currentDate->format('Y-m-d');
                                                @endphp
                                                <option value="{{ $formattedDate }}"
                                                    @if(!isset($doctor->weekday) || (isset($doctor->weekday) && in_array($formattedDate, json_decode($doctor->weekday))))
                                                        selected
                                                    @endif>
                                                    {{ $currentDate->format('l') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <input class="form-check-input" style="margin-top: 4px; margin-bottom: 4px;" type="checkbox" name="emergency_dealing" @if ($doctor->emergency_dealing != Status::NO) {{ 'checked' }} @endif>
                                <label class="form-check-label">Deal in Emergency</label>
                            </div>

                            <div class="col-sm-6 col-lg-12 serial d-none">
                                <div class="form-group">
                                    <label> @lang('Maximum Serial')</label>
                                    <input type="text" class="form-control" name="max_serial" onkeypress="return onlyNumberKey(event)" value="{{ old('max_serial', $doctor->max_serial) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-none-30 mt-4 mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if ($doctor->slot_type && $doctor->serial_or_slot)
                        <h4>@lang('System of Current Schedule')</h4>
                        <hr>
                        <div class="mt-4">
                            @foreach ($doctor->serial_or_slot as $item)
                                <button type="button" class="btn btn--primary mr-2 mb-2">{{ $item }}</button>
                            @endforeach
                        </div>
                    @else
                        <h5 class="text-center">@lang('You have no schedule')!</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
        (function($) {
            'use strict';
            $(document).ready(function () {
                $('#weekdaySelect').select2();
            });

            $('select[name=slot_type]').on('change', function() {
                var type = $(this).val();
                scheduleFirst(type);
            })

            var type = $('select[name=slot_type]').val();
            if (type) {
                scheduleFirst(type);
            }

            function scheduleFirst(type) {
                if (type == 1) {
                    $('.duration').addClass('d-none');
                    $('.serial').removeClass('d-none');
                    $('.startfirst').addClass('d-none');
                    $('.endfirst').addClass('d-none');
                } else {
                    $('.startfirst').removeClass('d-none');
                    $('.endfirst').removeClass('d-none');
                    $('.serial').addClass('d-none');
                    $('.duration').removeClass('d-none')
                }
            }

            initTimeFirstPicker();

            function initTimeFirstPicker() {
                var start = new Date();
                start.setHours(9);
                start.setMinutes(0);

                $('.time-first-picker').datepicker({
                    onlyTimepicker: true,
                    timepicker: true,
                    startDate: start,
                    language: 'en',
                    minHours: 0,
                    maxHours: 23,
                });
            }

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

        function onlyNumberKey(evt) {

             // Only ASCII character in that range allowed
             var ASCIICode = (evt.which) ? evt.which : evt.keyCode
             if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                 return false;
             return true;
         }
    </script>
@endpush
