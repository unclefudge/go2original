<?php
$url_path = Request::path();
$active_profile = $active_school = '';
if (Request::path() == 'account')
    $active_profile = 'active';
else {
    list($first, $rest) = explode('/', Request::path(), 2);
    if ($rest) {
        $active_school = (preg_match('/^settings*/', $rest)) ? 'active' : '';
    }
}
?>
<div class="col left-sidebar-menu">
    {{-- Sidebar Menu --}}
    <div id="sidebar-wrapper">
        <br>
        <div class="list-group list-group-flush">
            <a href="/account" class="list-group-item list-group-item-action {{ $active_profile }}"><i class="fa fa-layer-group sidebar-menu-icon"></i></span> Profile</a>
            <a href="/account/schools" class="list-group-item list-group-item-action  {{ $active_school }}"><i class="fa fa-graduation-cap sidebar-menu-icon"></i> Schools</a>
        </div>
    </div>
</div>