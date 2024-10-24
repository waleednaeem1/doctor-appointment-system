@extends('user.layouts.app')
@section('panel')
<div class="row gy-4 mt-2">
    <div class="col-xxl-3 col-sm-6">
        <x-widget style="2" link="{{ route('user.petslist') }}" icon="las la-stethoscope"
            title="PETS" value="" color="success" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-widget style="2" link="{{ route('user.info.profile') }}" icon="las la-users" title="PROFILE"
            value="" color="warning" />
    </div>
    {{-- <div class="col-xxl-3 col-sm-6">
        <x-widget style="2" link="" icon="las la-user-friends"
            title="Total Assistants" value="" color="danger" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-widget style="2" link="" icon="la la-ticket"
            title="Pending Support Tickets" value="" color="primary" />
    </div> --}}
</div><!-- row end-->
    <div class="row">
        <h5 class="my-3">@lang('')</h5>
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">

                </div>

            </div><!-- card end -->
        </div>
    </div>


@endsection
