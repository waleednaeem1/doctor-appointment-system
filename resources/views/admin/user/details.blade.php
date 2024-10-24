@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-12">


            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Information of') {{ $user->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user.store', $user->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>@lang('Image')</label>
                                        <div class="image-upload">
                                            <div class="thumb">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview"
                                                        style="background-image: url({{ getImage(getFilePath('userProfile') . '/' . $user->user_image, getFileSize('userProfile')) }})">
                                                        <button type="button" class="remove-image"><i
                                                                class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                                <div class="avatar-edit mt-0">
                                                    <input type="file" class="profilePicUpload" name="image"
                                                        value="{{ $user->user_image }}" id="profilePicUpload1"
                                                        accept=".png, .jpg, .jpeg">
                                                    <label for="profilePicUpload1"
                                                        class="btn btn--success btn-block btn-lg">@lang('Upload')</label>
                                                    <small>@lang('Support Images'):
                                                        <b>@lang('jpeg'), @lang('jpg'), @lang('png'),</b> @lang('resized into 400x400px')
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                            <input type="text" name="name" value="{{ $user->name }}"
                                                class="form-control " required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Username')</label>
                                            <input type="text" name="username" value="{{ $user->username }}"
                                                class="form-control " required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('New Password')</label>
                                            <input type="password" autocomplete="off" name="password" class="form-control " />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Confirm Password')</label>
                                            <input type="password" autocomplete="off" name="password_confirmation" class="form-control "  />
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('E-mail')</label>
                                            <input type="text" name="email" value="{{ $user->email }}"
                                                class="form-control " required />
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Phone')
                                                <i class="fa fa-info-circle text--primary" title="@lang('Add the country code by general setting. Otherwise, SMS won\'t send to that number.')">
                                                </i>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{ $general->country_code }}</span>
                                                <input type="text" name="phone"
                                                    value="{{ str_replace($general->country_code, '', $user->phone) }}"
                                                    class="form-control " autocomplete="off" required>
                                            </div>

                                        </div>
                                    </div>





                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Gender')</label>
                                            <div class="input-group">
                                                <select class="select2-one-select form-control" name="gender" required>
                                                    <option disabled >@lang('Select One')</option>
                                                    <option  value="male" @if($user->gender =='male') selected="selected" @endif >Male</option>
                                                    <option  value="female" @if($user->gender =='female') selected="selected" @endif >Femal</option>
                                                    <option  value='Don’t want to specify' @if($user->gender =='Don’t want to specify')  selected="selected" @endif >Don’t want to specify</option>


                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Status')</label>
                                            <div class="input-group">
                                                <select class="select2-one-select form-control" name="status" required>
                                                    <option disabled >@lang('Select One')</option>
                                                    <option  value="1" @if($user->status =='1') selected="selected" @endif >Active</option>
                                                    <option  value="0" @if($user->status =='0') selected="selected" @endif >Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-check-input" style="margin-top: 4px;" type="checkbox" name="email_verified_at" @if ($user->email_verified_at != null) {{ 'checked' }} @endif>
                                        <label class="form-check-label">Email Verify</label>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-outline--primary"><i class="la la-undo"></i>
        @lang('Back') </a>
@endpush
