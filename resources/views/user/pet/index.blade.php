@extends('user.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    
                                    <th>@lang('Image')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Age')</th>
                                    <th>@lang('Short Description')</th>
                                    <th>Pet Type</th>
                                    <th></th>
                                    
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($userPets as $userPet)
                                    <tr>
                                        @php
                                            $image              = $userPet->attachments->where('attachment_type', 'image')->first();
                                            //$video              = $userPet->attachments->where('attachment_type', 'video')->first();
                                            //$previous_record    = $userPet->attachments->where('attachment_type', 'previous_record')->first();
                                        @endphp
                                        <td><div class="avatar avatar--md"><img src="{{ getImage(getFilePath('pets') . '/' . @$image->attachment, getFileSize('pets')) }}"  /></div> </td>
                                        <td><span class="fw-bold d-block"> {{ __($userPet->name) }}</span></td>
                                        <td>{{ $userPet->age }} </td>
                                        <td>{{ $userPet->short_description }}</td>
                                        <td>{{ @$userPet->pettype->name }}</td>
                                        <td></td>
                                        <td><a href="{{ url('user/petdelete', $userPet->id) }}"
                                            class="btn btn-sm border-danger text-danger" onclick="return confirm('Are you sure?')">
                                            <i class="text-danger las la-trash"></i> @lang('Delete')
                                        </a>&nbsp;<a href="{{ route('user.petdetail', $userPet->id) }}"
                                            class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> @lang('Details')
                                        </a></td>
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
                
            </div><!-- card end -->
        </div>
    </div>

    {{-- DETAILS MODAL --}}
    
    {{-- Remove MODAL --}}
    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-search-form />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

          

            $('.removeBtn').on('click', function() {
                var modal = $('#removeModal');
                var route = $(this).data('route');
                $('.remove-route').attr('action', route);
            });

           

        })(jQuery);
    </script>
@endpush
