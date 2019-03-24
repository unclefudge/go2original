<?php
$active_overview = $active_attend = $active_settings = '';
list($first, $rest) = explode('/', Request::path(), 2);
if (!ctype_digit($rest)) {
    list($uid, $rest) = explode('/', $rest, 2);
    $active_attend = (preg_match('/^attendance*/', $rest)) ? 'active' : '';
    $active_settings = (preg_match('/^settings*/', $rest)) ? 'active' : '';
} else
    $active_overview = 'active';
?>
<div class="col left-sidebar-menu">
    {{-- Sidebar Menu --}}
    <div id="sidebar-wrapper">
        <br>
        <div class="list-group list-group-flush">
            <a href="/event/{{ $event->id }}" class="list-group-item list-group-item-action {{ $active_overview }}"><i class="flaticon2-analytics-2 sidebar-menu-icon"></i></span>&nbsp; Overview</a>
            <a href="/event/{{ $event->id }}/attendance/0" class="list-group-item list-group-item-action  {{ $active_attend }}"><i class="fa fa-user-friends" style="font-size:16px; padding-right: 7px"></i> Attendance</a>
            <a href="/event/{{ $event->id }}/settings" class="list-group-item list-group-item-action  {{ $active_settings }}"><i class="flaticon2-console sidebar-menu-icon"></i> Settings</a>
        </div>
    </div>
</div>