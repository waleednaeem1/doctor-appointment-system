@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Clinic')</th>
                                    <th>@lang('Phone')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clinics as $clinic)
                                    <tr>
                                        <td>{{$clinics->firstItem() + $loop->index}}</td>
                                        <td>
                                            <span class="fw-bold">{{ $clinic->name }}</span>
                                            <br>
                                            <small>
                                                <a href="{{ route('admin.clinics.detail', $clinic->id) }}"><span>@</span>{{ $clinic->name }}</a>
                                            </small>
                                        </td>
                                        <td><span class="d-block fw-bold">{{ $clinic->phone }}</td>

                                        <td>
                                            {{ showDateTime($clinic->created_at) }} <br>
                                            {{ diffForHumans($clinic->created_at) }}
                                        </td>
                                        
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.clinics.detail', $clinic->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                                {{-- @if ($assistant->status == Status::ACTIVE)
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.assistant.status', $assistant->id) }}"
                                                        data-question="@lang('Are you sure to inactive this assistant?')">
                                                        <i class="la la-eye-slash"></i> @lang('Inactive')
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-action="{{ route('admin.assistant.status', $assistant->id) }}"
                                                        data-question="@lang('Are you sure to active this assistant?')">
                                                        <i class="la la-eye"></i> @lang('Active')
                                                    </button>
                                                @endif --}}
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
                @if ($clinics->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($clinics) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form/>
    <a href="{{ route('admin.clinics.form')}}" type="button" class="btn btn-sm btn-outline--primary h-45">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush
