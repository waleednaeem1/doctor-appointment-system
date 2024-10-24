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
                                    <th>@lang('Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('User Name')</th>
                                    <th>@lang('Phone')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $user->name }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $user->email }}</span>
                                        </td>
                                        <td><span class="fw-bold">{{ $user->username }}</span></td>
                                        <td><span class="fw-bold">{{ $user->phone }}</span> </td>
                                       <td>{{ showDateTime($user->created_at) }} <br>
                                            {{ diffForHumans($user->created_at) }}
                                        </td>
                                        <td> {{ ($user->status ==1) ? 'Active' : 'InActive' }} </td>
                                        
                                        <td><a href="{{ route('admin.user.detail', $user->id) }}"
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
                @if ($users->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($users) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')

@endpush

@push('style')
    <style>
        .dropdown-menu {
            padding: 0 0;
        }

        .dropdown-item {
            padding: 0.4rem 0.8rem;
        }
    </style>
@endpush
