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
                            <li class="list-group-item d-flex justify-content-between align-items-left fw-bold">
                                @lang('Pet Parent Name') :
                                <span class="name"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 border">
                        <ul class="list-group-flush list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-left fw-bold">
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
                <div class="row border">
                    <div class="col-md-12">
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
