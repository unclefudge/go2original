<?php
$url = explode('/', Request::path());
$active_dashboard = ($url[0] == 'dashboard') ? 'kt-menu__item--open kt-menu__item--here' : '';
$active_people = ($url[0] == 'people') ? 'kt-menu__item--open kt-menu__item--here' : '';
$active_event = ($url[0] == 'event') ? 'kt-menu__item--open kt-menu__item--here' : '';
$active_group = ($url[0] == 'group') ? 'kt-menu__item--open kt-menu__item--here' : '';
?>

<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
<div class="kt-header-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_header_menu_wrapper">
    <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile ">
        <ul class="kt-menu__nav ">
            <li class="kt-menu__item {{ $active_dashboard }}"><a href="/dashboard" class="kt-menu__link"><span class="kt-menu__link-text">Dashboard</span></a></li>
            <li class="kt-menu__item {{ $active_people }}"><a href="/people" class="kt-menu__link"><span class="kt-menu__link-text">People</span></a></li>
            <li class="kt-menu__item {{ $active_event }}"><a href="/event" class="kt-menu__link"><span class="kt-menu__link-text">Events</span></a></li>
            <li class="kt-menu__item {{ $active_group }}"><a href="/group" class="kt-menu__link"><span class="kt-menu__link-text">Groups</span></a></li>
        </ul>
    </div>
</div>