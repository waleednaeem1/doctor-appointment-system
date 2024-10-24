<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="/" class="sidebar__main-logo"><img
                    {{-- src="{{ getImage(getFilePath('logoIcon') . '/logo_dark.png') }}" alt="@lang('image')"></a> --}}
                    src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('image')"></a>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('user.dashboard') }}">
                    <a href="{{ route('user.dashboard') }}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('', 3) }}">
                        <i class="menu-icon las la-handshake"></i>
                        <span class="menu-title">@lang('My Pets')</span>
                        @if (@$newAppointmentsCount)
                            <span class="menu-badge pill bg--danger ms-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{ menuActive('user*', 2) }} ">
                        <ul>
                            {{-- <li class="sidebar-menu-item {{ menuActive('user.mypets') }} ">
                                <a href="{{ route('user.mypets') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Add Pet')</span>
                                </a>
                            </li> --}}
                            <li class="sidebar-menu-item {{ menuActive('user.petslist') }} ">
                                <a href="{{ route('user.petslist') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Pets')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                        
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('doctor.appointment*', 3) }}">
                        <i class="menu-icon las la-handshake"></i>
                        <span class="menu-title">@lang('Appointments')</span>
                        @if (@$newAppointmentsCount)
                            <span class="menu-badge pill bg--danger ms-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{ menuActive('user.appointment*', 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('user.appointment.new') }} ">
                                <a href="{{ route('user.appointment.new') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('New Appointments')</span>
                                    
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('user.appointment.done') }} ">
                                <a href="{{ route('user.appointment.done') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Done Appointments')</span>
                                    
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('user.appointment.trashed') }} ">
                                <a href="{{ route('user.appointment.trashed') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Trashed Appointments')</span>
                                    
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- <li class="sidebar-menu-item {{ menuActive('user.paymentHistory') }} ">
                    <a href="{{ route('user.paymentHistory') }}" class="nav-link">
                        <i class="menu-icon las la-dot-circle"></i>
                        <span class="menu-title">@lang('Payment History')</span>
                    </a>
                </li> --}}

            </ul>
            {{-- <div class="text-center mb-3 text-uppercase">
                <span class="text--primary">{{ __(systemDetails()['name']) }}</span>
                <span class="text--success">@lang('V'){{ systemDetails()['version'] }} </span>
            </div> --}}
        </div>
    </div>
</div>
<!-- sidebar end -->

@push('script')
    <script>
        if ($('li').hasClass('active')) {
            $('#sidebar__menuWrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            }, 500);
        }
    </script>
@endpush
