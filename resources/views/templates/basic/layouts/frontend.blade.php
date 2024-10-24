<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $general->siteName(__($pageTitle)) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    @include('partials.seo')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css?version=001') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}" />

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/chosen.css') }}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/themify.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/animate.css') }}">
    <!-- main style css link -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/style.css?version=032') }}">

    @stack('style-lib')

    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css?version=007') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ $general->base_color }}">

    {{-- <script src="//sdk.twilio.com/js/video/releases/2.3.0/twilio-video.min.js"></script> --}}
    {{-- <script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script> --}}
    <script src="//sdk.twilio.com/js/video/releases/2.28.1/twilio-video.min.js"></script>
    <script src="https://media.twiliocdn.com/sdk/js/video/releases/2.3.0/twilio-video.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-r86tO9gWZxzRiELiw3DQYa2D3_o1CVk&libraries=places"></script>
<style>
    #media-div{
        height: 300px;
    }
 </style>
</head>

<body>
    @stack('fbComment')
    <div class="body-overlay"></div>

    {{-- <div class="loader">
        <div class="heartbeatloader">
            <svg class="svgdraw" width="100%" height="100%" viewBox="0 0 150 400">
                <path class="path"
                    d="M 0 200 l 40 0 l 5 -40 l 5 40 l 10 0 l 5 15 l 10 -140 l 10 220 l 5 -95 l 10 0 l 5 20 l 5 -20 l 30 0"
                    fill="transparent" stroke-width="4" stroke="black" />
            </svg>
            <img src="{{ asset('assets/loader/images/gif2.gif') }}" alt="@lang('image')">
            <div class="innercircle"></div>
            <div class="outercircle"></div>
        </div>
    </div> --}}

    @include($activeTemplate . 'partials.header')

    <div class="scrollToTop">
        <span class="scroll-icon">
            <i class="fa fa-angle-up"></i>
        </span>
    </div>

    <div class="all-sections">
        @if (!request()->routeIs('home'))
            @include($activeTemplate . 'partials.breadcrumb')
        @endif

        @yield('content')
    </div>

    @include($activeTemplate . 'partials.footer')

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('assets/global/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.nice-select.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/chosen.jquery.js') }}"></script>
    <script src="{{asset('assets/admin/js/vendor/select2.min.js')}}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/swiper.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js?version=0.04') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/front.js?version=0.17') }}"></script>

    @stack('script-lib')

    @stack('script')

    @include('partials.plugins')

    @include('partials.notify')

    <script>
        (function($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{ route('home') }}/change/" + $(this).val();
            });

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                matched = event.matches;
                if (matched) {
                    $('body').addClass('dark-mode');
                    $('.navbar').addClass('navbar-dark');
                } else {
                    $('body').removeClass('dark-mode');
                    $('.navbar').removeClass('navbar-dark');
                }
            });

            let matched = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (matched) {
                $('body').addClass('dark-mode');
                $('.navbar').addClass('navbar-dark');
            } else {
                $('body').removeClass('dark-mode');
                $('.navbar').removeClass('navbar-dark');
            }

            var inputElements = $('input,select');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

        })(jQuery);
    </script>


</body>

</html>
