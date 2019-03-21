<!-- begin:: Header -->
<div id="kt_header" class="kt-header  kt-header--fixed " data-ktheader-minimize="on">
    <div class="kt-container">
        <!-- begin:: Brand -->
        <div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
            <a class="kt-header__brand-logo" href="?page=index&demo=demo4">
                <img alt="Logo" src="/massets/media/logos/logo-4.png" class="kt-header__brand-logo-default"/>
                <img alt="Logo" src="/massets/media/logos/logo-4-sm.png" class="kt-header__brand-logo-sticky"/>
            </a>
        </div>
        <!-- end:: Brand -->

        <!-- begin: Header Menu -->
        @include('layout/section/menu')
        <!-- end: Header Menu -->

        <!-- begin:: Header Topbar -->
        <div class="kt-header__topbar kt-grid__item">
            @include('layout/section/header-search')
            @include('layout/section/header-notifications')
            @include('layout/section/header-quickactions')
            @include('layout/section/header-cart')
            @include('layout/section/header-quickpanel')
            @include('layout/section/header-language')
            @include('layout/section/header-user')
        </div>
        <!-- end:: Header Topbar -->
    </div>
</div>
<!-- end:: Header -->