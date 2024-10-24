@extends('user.layouts.master')

@section('content')
    <div class="page-wrapper default-version">
        @include('user.partials.sidenav')
        @include('user.partials.topnav')
        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                @include('user.partials.breadcrumb')
                @yield('panel')
            </div>
        </div>
    </div>

@endsection

