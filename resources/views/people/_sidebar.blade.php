<?php
$active_profile = $active_activity = $active_medical = $active_notes = '';
list($first, $rest) = explode('/', Request::path(), 2);
if (!ctype_digit($rest)) {
    list($uid, $rest) = explode('/', $rest, 2);
    $active_activity = (preg_match('/^activity*/', $rest)) ? 'active' : '';
    $active_medical = (preg_match('/^medical*/', $rest)) ? 'active' : '';
    $active_notes = (preg_match('/^notes*/', $rest)) ? 'active' : '';
} else
    $active_profile = 'active';
?>
<div class="col left-sidebar-menu">
    {{-- Sidebar Menu --}}
    <div id="sidebar-wrapper">
        <br>
        <div class="list-group list-group-flush">
            <a href="/people/{{ $user->id }}" class="list-group-item list-group-item-action {{ $active_profile }}"><i class="flaticon-avatar sidebar-menu-icon"></i></span> &nbsp; Profile</a>
            <a href="/people/{{ $user->id }}/medical" class="list-group-item list-group-item-action  {{ $active_medical }}"><i class="la la-medkit" style="font-size: 24px; width: 30px"></i> Medical</a>
            <a href="/people/{{ $user->id }}/activity" class="list-group-item list-group-item-action  {{ $active_activity }}"><i class="flaticon2-rocket-2 sidebar-menu-icon"></i> Activity</a>
            <a href="/people/{{ $user->id }}/notes" class="list-group-item list-group-item-action  {{ $active_notes }}"><i class="flaticon-notes sidebar-menu-icon"></i> &nbsp; Notes</a>
        </div>
    </div>
</div>