<?php
$url = explode('/', Request::path());
$active_event = ($url[0] == 'event') ? 'active' : '';
$active_checkin = ($url[0] == 'checkin') ? 'active' : '';
?>
<div class="col left-sidebar-menu">
    {{-- Sidebar Menu --}}
    <div id="sidebar-wrapper">
        <br>
        <div class="list-group list-group-flush">
            <a href="/event" class="list-group-item list-group-item-action {{ $active_event }}"><i class="flaticon2-calendar-4 sidebar-menu-icon"></i></span> Events</a>
            <a href="/checkin" class="list-group-item list-group-item-action  {{ $active_checkin }}"><i class="flaticon2-laptop sidebar-menu-icon"></i> Check-in</a>
        </div>
    </div>
</div>