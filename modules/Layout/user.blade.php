<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $html_class ?? '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php($favicon = setting_item('site_favicon'))
    <link rel="icon" type="image/png"
        href="{{ !empty($favicon) ? get_file_url($favicon, 'full') : url('images/favicon.png') }}" />
    @include('Layout::parts.seo-meta')

    <link href="{{ asset('dist/frontend/guido/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/daterange/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/css/notification.css') }}" rel="newest stylesheet">

    <link href="{{ asset('dist/frontend/guido/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/font-awesome-animation.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/menu.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/ace-responsive-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/megadropdown.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/simplebar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/progressbar.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/flaticon.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/slider.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/timecounter.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/googlemap.css') }}" rel="stylesheet">

    <link href="{{ asset('dist/frontend/css/app.css?_ver=' . config('app.asset_version')) }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/dashbord_navitaion.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/guido/responsive.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600&family=Poppins:wght@100;300;400;500;600&display=swap"
        rel="stylesheet" type='text/css' media='all'>



    <script>
        var bookingCore = {
            url: '{{ url(app_get_locale()) }}',
            url_root: '{{ url('') }}',
            booking_decimals: {{ (int) get_current_currency('currency_no_decimal', 2) }},
            thousand_separator: '{{ get_current_currency('currency_thousand') }}',
            decimal_separator: '{{ get_current_currency('currency_decimal') }}',
            currency_position: '{{ get_current_currency('currency_format') }}',
            currency_symbol: '{{ currency_symbol() }}',
            currency_rate: '{{ get_current_currency('rate', 1) }}',
            date_format: '{{ get_moment_date_format() }}',
            map_provider: '{{ setting_item('map_provider') }}',
            map_gmap_key: '{{ setting_item('map_gmap_key') }}',
            routes: {
                login: '{{ route('auth.login') }}',
                register: '{{ route('auth.register') }}',
            },
            currentUser: {{ (int) Auth::id() }},
            isAdmin: {{ is_admin() ? 1 : 0 }},
            rtl: {{ setting_item_with_lang('enable_rtl') ? '1' : '0' }},
            markAsRead: '{{ route('core.notification.markAsRead') }}',
            markAllAsRead: '{{ route('core.notification.markAllAsRead') }}',
            loadNotify: '{{ route('core.notification.loadNotify') }}',
            pusher_api_key: '{{ setting_item('pusher_api_key') }}',
            pusher_cluster: '{{ setting_item('pusher_cluster') }}',
        };
        var i18n = {
            warning: "{{ __('Warning') }}",
            success: "{{ __('Success') }}",
            confirm_delete: "{{ __('Do you want to delete?') }}",
            confirm: "{{ __('Confirm') }}",
            cancel: "{{ __('Cancel') }}",
        };
        var daterangepickerLocale = {
            "applyLabel": "{{ __('Apply') }}",
            "cancelLabel": "{{ __('Cancel') }}",
            "fromLabel": "{{ __('From') }}",
            "toLabel": "{{ __('To') }}",
            "customRangeLabel": "{{ __('Custom') }}",
            "weekLabel": "{{ __('W') }}",
            "first_day_of_week": {{ setting_item('site_first_day_of_the_weekin_calendar', '1') }},
            "daysOfWeek": [
                "{{ __('Su') }}",
                "{{ __('Mo') }}",
                "{{ __('Tu') }}",
                "{{ __('We') }}",
                "{{ __('Th') }}",
                "{{ __('Fr') }}",
                "{{ __('Sa') }}"
            ],
            "monthNames": [
                "{{ __('January') }}",
                "{{ __('February') }}",
                "{{ __('March') }}",
                "{{ __('April') }}",
                "{{ __('May') }}",
                "{{ __('June') }}",
                "{{ __('July') }}",
                "{{ __('August') }}",
                "{{ __('September') }}",
                "{{ __('October') }}",
                "{{ __('November') }}",
                "{{ __('December') }}"
            ],
        };
    </script>
    <link href="{{ asset('dist/frontend/module/user/css/user.css') }}" rel="stylesheet">
    <!-- Styles -->
    @yield('head')
    <style type="text/css">
        .bc_topbar,
        .bc_header,
        .bc_footer {
            display: none;
        }

        html,
        body,
        .bc_wrap,
        .bc_user_profile,
        .bc_user_profile>.container-fluid>.row-eq-height>.col-md-3 {
            min-height: 100vh !important;
        }
    </style>
    {{-- Custom Style --}}
    <link href="{{ route('core.style.customCss') }}" rel="stylesheet">
    @if (setting_item_with_lang('enable_rtl'))
        <link href="{{ asset('dist/frontend/css/rtl.css') }}" rel="stylesheet">
    @endif
</head>

<body class="user-page {{ $body_class ?? '' }} @if (setting_item_with_lang('enable_rtl')) is-rtl @endif">
    <div class="wrapper">
        {!! setting_item('body_scripts') !!}
        @if (!is_api())
            @include('Layout::parts.header')
        @endif
        <!-- Our Dashbord -->
        <section class="extra-dashboard-menu dn-992">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ed_menu_list">
                            <ul>
                                @include('User::frontend.layouts.menu')
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="our-dashbord dashbord py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="dashboard_navigationbar dn db-992">
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle btn-block" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-bars pr10"></i> Dashboard Navigation
                                </a>

                                <div class="dropdown-menu">
                                    <ul>
                                        <li class="dropdown-item active">
                                            <a href="https://www.qawmibazar.com/user/dashboard">
                                                <span class="icon text-center"><i class="flaticon-web-page"></i></span>
                                                Dashboard

                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="https://www.qawmibazar.com/user/profile">
                                                <span class="icon text-center"><i class="flaticon-avatar"></i></span>
                                                Profile

                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="https://www.qawmibazar.com/user/listing">
                                                <span class="icon text-center"><i class="flaticon-list"></i></span>
                                                My Listings

                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="https://www.qawmibazar.com/user/verification">
                                                <span class="icon text-center"><i class="fa fa-handshake-o"></i></span>
                                                Verifications

                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="https://www.qawmibazar.com/user/wishlist">
                                                <span class="icon text-center"><i class="flaticon-love"></i></span>
                                                Bookmarks

                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="https://www.qawmibazar.com/user/reviews">
                                                <span class="icon text-center"><i class="flaticon-note"></i></span>
                                                Reviews

                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="logout">
                                                <form id="logout-form-vendor" action="https://www.qawmibazar.com/logout" method="POST" style="display: none;">
                                                    <input type="hidden" name="_token" value="cCvIBb30yF98Jm66AHUQIpIok7y78N6y4v9OBB5K">
                                                </form>
                                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-vendor').submit();"><i class="flaticon-logout"></i> Log Out
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
{{--                            <div class="dropdown">--}}
{{--                                <button onclick="myFunction()" class="dropbtn">--}}
{{--                                    {{ __('Dashboard Navigation') }}</button>--}}
{{--                                <ul id="myDropdown" class="dropdown-content">--}}
{{--                                    @include('User::frontend.layouts.menu')--}}
{{--                                </ul>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                    <div class="col-lg-12 mb15 d-none">
                        @includeIf('Layout::parts.user-bc')
                    </div>
                </div>
                @yield('content')
            </div>

        </section>
        @include('Layout::parts.footer', ['is_user_page' => 1])
        @include('Layout::parts.footer-vendor')
        {!! setting_item('footer_scripts') !!}
    </div>
</body>

</html>
