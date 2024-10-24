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
                                    <th>@lang('ID')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Slug')</th>
                                    <th>@lang('Image')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pet_disease as $item)
                                <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $item->name }}</span>
                                            <br>
                                            <small>
                                                <a href="{{ route('admin.pets.detail-disease', $item->id) }}"><span>@</span>{{ $item->name }}</a>
                                            </small>
                                        </td>
                                        <td>{{ $item->slug }}</td>
                                        <td>
                                            <div class="avatar avatar--md">
                                                <img src="{{ getImage(getFilePath('petsdisease') . '/' . $item->image, getFileSize('petsdisease')) }}" alt="@lang('Image')">
                                            </div>
                                        </td>
                                        <td>@php echo $item->statusBadge @endphp</td>

                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.pets.detail-disease', $item->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>


                                                @if ($item->status == Status::ACTIVE)
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.pets.status-disease', $item->id) }}"
                                                        data-question="@lang('Are you sure to Inactive this Type?')">
                                                        <i class="la la-eye-slash"></i> @lang('Inactive')
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-action="{{ route('admin.pets.status-disease', $item->id) }}"
                                                        data-question="@lang('Are you sure to Active this pet?')">
                                                        <i class="la la-eye"></i> @lang('Active')
                                                    </button>
                                                @endif

                                                <a href="{{ route('admin.pets.delete-disease', $item->id) }}"
                                                    class="btn btn-sm btn-outline--danger">
                                                    <i class="las la-trash"></i> @lang('Delete')
                                                </a>
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
                @if ($pet_disease->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($pet_disease) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form/>
    <a href="{{ route('admin.pets.form-disease')}}" type="button" class="btn btn-sm btn-outline--primary">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush
