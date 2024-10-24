@extends('doctor.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Icon')</th>
                                    <th>@lang('URL')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($socialIcons as $icon)
                                    <tr>
                                        <td>{{ $socialIcons->firstItem() + $loop->index }}</td>
                                        <td>{{ __($icon->title) }}</td>
                                        <td> @php echo @$icon->icon;  @endphp</td>
                                        <td> <a href="{{ $icon->url }}" target="__blank">{{ $icon->url }}</a> </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary editBtn cuModalBtn" data-resource="{{ $icon }}"    data-modal_title="@lang('Edit Social Icon')" data-has_status="1">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-action="{{ route('doctor.info.social.icon.delete', $icon->id) }}"
                                            data-question="@lang('Are you sure to delete this social icon')?">
                                            <i class="la la-trash"></i> @lang('Delete')
                                        </button>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($socialIcons->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($socialIcons) @endphp
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <!--Cu Modal -->
    <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('doctor.info.social.icon.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Title')</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Icon')</label>
                            <div class="input-group">
                                <input type="text" class="form-control iconPicker" autocomplete="off" name="icon" value="{{ old('icon') }}" required>
                                <span class="input-group-text  input-group-addon" data-icon="la la-home" role="iconpicker"> <i class="lab la-accessible-icon"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('URL')</label>
                            <input type="text" name="url" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />

@endsection

@push('style-lib')
<link href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>
@endpush

@push('breadcrumb-plugins')
<x-search-form />
<button type="button" class="btn btn-sm btn-outline--primary h-45 cuModalBtn" data-modal_title="@lang('Add Social Icon')">
    <i class="las la-plus"></i>@lang('Add New')
</button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.editBtn').on('click', function(){
                let resource = $(this).data('resource');
                $('#cuModal').find('.iconpicker-container .input-group-text').html(resource.icon);
            });

            $('.iconPicker').iconpicker().on('iconpickerSelected', function (e) {
                $(this).closest('.form-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });

        })(jQuery);
    </script>
@endpush
