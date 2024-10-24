@extends('user.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-6 col-md-6 mb-30">
            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-3 bg--primary">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage(getFilePath('userProfile').'/'. $user->user_image,getFileSize('userProfile'))}}" alt="@lang('Image')">
                        </div>
                        <div class="ps-3">
                            <h4 class="text--white">{{__($user->name)}}</h4>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Name')
                            <span class="fw-bold">{{ __($user->name) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span  class="fw-bold">{{ __($user->username) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span  class="fw-bold">{{ $user->email }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Change Password')</h5>
                    <form action="{{ route('user.password.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group password-toggle">
                            <label>@lang('Password')</label>
                            <div class="input-container">
                                <input class="form-control" type="password" name="old_password" required>
                                <span class="password-icon" onclick="togglePassworduserVisibility(this)">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group password-toggle">
                            <label>@lang('New Password')</label>
                            <div class="input-container">
                                <input class="form-control" type="password" name="password" required>
                                <span class="password-icon" onclick="togglePassworduserVisibility(this)">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group password-toggle">
                            <label>@lang('Confirm Password')</label>
                            <div class="input-container">
                                <input class="form-control" type="password" name="password_confirmation" required>
                                <span class="password-icon" onclick="togglePassworduserVisibility(this)">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 btn-lg h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('user.info.profile')}}" class="btn btn-sm btn-outline--primary" ><i class="las la-user"></i>@lang('Profile Setting')</a>
@endpush
