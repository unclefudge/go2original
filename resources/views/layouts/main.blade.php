<!DOCTYPE html>
<html lang="en">

<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <title>Go2Youth</title>
    <meta name="description" content="Event and Attendance for Youth Ministries">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta id="token" name="token" value="{{ csrf_token() }}" />

    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->

    <!--begin::Global Theme Styles -->
    <link href="/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/demo/demo5/base/style.bundle.css" rel="stylesheet" type="text/css"/>
    <!--end::Global Theme Styles -->

    <!--begin::Page Vendors Styles -->
    <link href="/css/custom.css" rel="stylesheet" type="text/css"/>
    <!--<link href="/css/reseller.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="//framework.dreamscape.cloud/scripts/reseller/framework/vendors.bundle.js?vendors_timestamp=1539856691"></script>-->
    @yield('page-styles')
    <!--end::Page Vendors Styles -->
    <link rel="shortcut icon" href="/assets/demo/demo5/media/img/logo/favicon.ico"/>
</head>
<!-- end::Head -->

<!-- begin::Body -->
<body class="m-page--wide m-header--fixed m-header--fixed-mobile m-footer--push m-aside--offcanvas-default" style="background: #F7F7F7">

<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">

    {{--}}@include('layouts/section/header')--}}
    <!-- begin::Header -->
    <header id="m_header" class="m-grid__item m-header " m-minimize="minimize" m-minimize-offset="200" m-minimize-mobile-offset="200">

        @include('layouts/section/topbar')

        @include('layouts/section/menu')

    </header>

    <!-- end::Header -->

    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop m-container m-container--responsive m-container--xxl m-page__container m-body">
        <div class="m-grid__item m-grid__item--fluid m-wrapper">

            @yield('subheader')

            <div class="m-content">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- end::Body -->

    @include('layouts/section/footer')
</div>
<!-- end:: Page -->

{{-- @include('layouts/section/sidebar') --}}

<!-- begin::Scroll Top -->
<div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
</div>

<!-- end::Scroll Top -->

 {{-- @include('layouts/section/quicknav') --}}

<!--begin::Global Theme Bundle -->
<script src="/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
<script src="/assets/demo/demo5/base/scripts.bundle.js" type="text/javascript"></script>
<!--end::Global Theme Bundle -->

<!--begin::Page Vendors -->
@yield('vendor-scripts')
<!--end::Page Vendors -->

<!--begin::Page Scripts -->
@yield('page-scripts')
<!--end::Page Scripts -->

{!! Toastr::render() !!}

</body>

<!-- end::Body -->
</html>