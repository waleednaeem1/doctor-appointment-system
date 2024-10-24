@extends('doctor.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Pet Parent') | @lang('Mobile')</th>
                                    <th>@lang('Added By')</th>
                                    @if (request()->routeIs('doctor.appointment.trashed'))
                                        <th>@lang('Trashed By')</th>
                                    @endif
                                    <th>@lang('Booking Date')</th>
                                    <th>@lang('Time / Serial No')</th>
                                    <th>@lang('Payment Status')</th>
                                    @if (!request()->routeIs('doctor.appointment.trashed'))
                                        <th>@lang('Service')</th>
                                    @endif
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointments->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="fw-bold d-block"> {{ __($appointment->name) }}</span>
                                            {{ $appointment->mobile }}
                                        </td>
                                        <td> @php  echo $appointment->addedByBadge;  @endphp </td>
                                        @if (request()->routeIs('doctor.appointment.trashed'))
                                            <td> @php  echo $appointment->trashBadge;  @endphp </td>
                                        @endif
                                        <td>{{ showDateTime($appointment->booking_date, 'y-m-d') }}</td>
                                        <td>{{ $appointment->time_serial }}</td>
                                        <td> @php  echo $appointment->paymentBadge;  @endphp </td>
                                        @if (!request()->routeIs('doctor.appointment.trashed'))
                                            <td> @php  echo $appointment->serviceBadge;  @endphp </td>
                                        @endif
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary detailBtn"
                                                    data-route="{{ route('doctor.appointment.dealing', $appointment->id) }}"
                                                    data-resourse="{{ $appointment }}">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </button>
                                                @if (request()->routeIs('doctor.appointment.new'))

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        @if (!$appointment->is_delete && !$appointment->payment_status) ''  @else disabled @endif
                                                        data-action="{{ route('doctor.appointment.remove', $appointment->id) }}"
                                                        data-question="@lang('Are you sure to remove this appointment')?">
                                                        <i class="la la-trash"></i> @lang('Trash')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($appointments->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($appointments) @endphp
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    {{-- DETAILS MODAL --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" style="max-width: 650px" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Appointment Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Pet Parent Name') :
                                    <span class="name"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Contact No') :
                                    <span class="mobile"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row border">
                        <div class="col-md-12">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-start align-items-center fw-bold">
                                    @lang('E-mail') :
                                    <span class="email"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Time or Serial no') :
                                    <span class="timeSerial"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Booking Date') :
                                    <span class="bookingDate"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item justify-content-between align-items-center fw-bold">
                                    @lang('Disease') :
                                    <span class="disease"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Fees') :
                                    <span class="appointment_fees"></span>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <h5 class="modal-title mt-2">@lang('Pet Detail')</h5>
                    <div class="row">
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Name') :
                                    <span class="pet_name"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Gender') :
                                    <span class="gender"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Age') :
                                    <span class="pet_age" style="margin-right: -150px;"></span><span class="age_in"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Weight') :
                                    <span class="weight" style="margin-right: -150px;"></span><span class="unit"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Species') :
                                    <span class="pet_type"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                    @lang('Breed') :
                                    <span class="breed"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 border">
                            <ul class="list-group-flush list-group">
                                <li class="list-group-item justify-content-start align-items-center fw-bold">
                                    @lang('Short Description') :
                                    <span class="pet_descp"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <p class="text--warning text-center"><i class="las la-exclamation-triangle"></i> @lang('Are you sure that the patient has paid')?
                        </p>
                        <p class="text-center text--success"><i class="las la-exclamation-triangle"></i> @lang('If yes, then you can mark this as service done').
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id=petDetailButton></div>
                    <form action="" class="dealing-route" method="post">
                        @csrf
                        <button type="submit" class="btn btn-outline--success btn-sm serviceDoneBtn"><i
                                class="las la-check"></i> @lang('Done')</button>
                        <button type="button" class="btn btn--dark btn-sm"
                            data-bs-dismiss="modal">@lang('Close')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Remove MODAL --}}
    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-search-form />

    {{-- <a href="{{ route('doctor.appointment.booking') }}" type="button" class="btn btn-sm btn-outline--primary h-45">
        <i class="las la-plus"></i>@lang('Make New')
    </a> --}}
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.detailBtn').on('click', function() {
                let modal = $('#detailModal');
                let resourse = $(this).data('resourse');
                let user_pets = resourse.user_pets_id;
                $('.name').text('');
                $('.email').text('');
                $('.mobile').text('');
                $('.bookingDate').text('');
                $('.timeSerial').text('');
                $('.age').text('');
                $('.appointment_fees').text('');
                $('.disease').text('');
                $('.user_pets').text('');
                $('.pet_name').text('');
                $('.pet_age').text('');
                $('.pet_descp').text('');
                $('.age_in').text('');
                $('.gender').text('');
                $('.weight').text('');
                $('.unit').text('');
                $('.breed').text('');
                $('.pet_type').text('');
                $('.name').text(resourse.name);
                $('.email').text(resourse.email);
                $('.mobile').text(resourse.mobile);
                $('.bookingDate').text(resourse.booking_date);
                $('.timeSerial').text(resourse.time_serial);
                $('.age').text(resourse.age);
                $('.appointment_fees').text(resourse.doctor.fees + ' ' + `{{ $general->cur_text }}`);
                $('.disease').text(resourse.disease);
                if(resourse.pet == null){
                $('.pet_name').text('');
                $('.pet_age').text('');
                $('.pet_descp').text('');
                $('.age_in').text('');
                $('.gender').text('');
                $('.weight').text('');
                $('.unit').text('');
                $('.breed').text('');
                $('.pet_type').text('');
                }else{
                    $('.pet_name').text(resourse.pet.name);
                    $('.pet_age').text(resourse.pet.age);
                    $('.pet_descp').text(resourse.pet.short_description);
                    $('.age_in').text(resourse.pet.age_in);
                    $('.gender').text(resourse.pet.gender);
                    $('.weight').text(resourse.pet.weight);
                    $('.unit').text(resourse.pet.unit);
                    $('.breed').text(resourse.pet.breed);
                    $('.pet_type').text(resourse.pet.pettype.name);
                }
                let petdiv = document.getElementById("petDetailButton");
                petdiv.innerHTML = '';
                let aTag = document.createElement('a');
                aTag.setAttribute('target',"_blank");
                aTag.setAttribute('href',"https://dev.searchavet.com/pet/doctor/"+ user_pets);
                aTag.classList.add('btn', 'btn-sm', 'btn-outline--primary', 'petDetailBtn');
                aTag.innerText = "See Pet Details";
                petdiv.appendChild(aTag);

                var route = $(this).data('route');
                $('.dealing-route').attr('action', route);

                if (resourse.is_delete == 1 || resourse.is_complete == 1) {
                    modal.find('.serviceDoneBtn').hide();
                } else if (!resourse.is_complete && resourse.payment_status != 2) {
                    modal.find('.serviceDoneBtn').show();
                } else {
                    modal.find('.serviceDoneBtn').show();
                }

                modal.modal('show');
            });

            $('.removeBtn').on('click', function() {
                var modal = $('#removeModal');
                var route = $(this).data('route');
                $('.remove-route').attr('action', route);
            });

        })(jQuery);
    </script>
@endpush
