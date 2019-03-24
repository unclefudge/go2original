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
{{-- Mobile Sidebar Menu --}}
<div class="alert alert-light alert-elevate" role="alert" id="left-sidebar-mobile">
    <div class="alert-text">
        <select class="form-control kt-selectpicker" name="people_tab" id="people_tab" onchange="redirectEvent(this.value)">
            <option value="0" {{ ($active_overview) ? 'selected' : '' }}>Overview</option>
            <option value="1" {{ ($active_attend) ? 'selected' : '' }}>Attendance</option>
            <option value="2" {{ ($active_settings) ? 'selected' : '' }}>Settings</option>
        </select>
    </div>
</div>
<script>
    function redirectEvent(val) {
        switch (val) {
            case '0':
                window.location.href = "/event/{{ $event->id }}";
                break;
            case '1':
                window.location.href = "/event/{{ $event->id }}/attendance/0";
                break;
            case '2':
                window.location.href = "/event/{{ $event->id }}/settings";
                break;
        }
    };
</script>