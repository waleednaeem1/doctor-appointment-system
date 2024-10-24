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
                                    <th>@lang('Image')</th>
                                    <th>@lang('Hospital')</th>
                                    <th>@lang('Phone')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hospitals as $hospital)
                                    <tr>
                                        <td>{{$hospitals->firstItem() + $loop->index}}</td>
                                        <td>
                                            <div class="avatar avatar--md">
                                                <img src="{{ getImage(getFilePath('hospital') . '/' . $hospital->logo, getFileSize('hospital')) }}"
                                                    alt="@lang('Image')">
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $hospital->name }}</span>
                                            <br>
                                            <small>
                                                <a href="{{ route('admin.hospital.detail', $hospital->id) }}"><span>@</span>{{ $hospital->name }}</a>
                                            </small>
                                        </td>
                                        <td><span class="d-block fw-bold">{{ $hospital->phone }}</td>

                                        <td>
                                            {{ showDateTime($hospital->created_at) }} <br>
                                            {{ diffForHumans($hospital->created_at) }}
                                        </td>
                                        
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.hospital.detail', $hospital->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
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
                
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form/>
    <a href="{{ route('admin.hospital.form')}}" type="button" class="btn btn-sm btn-outline--primary">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush
