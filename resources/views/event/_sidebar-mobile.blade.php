<?php
$url = explode('/', Request::path());
$active_event = ($url[0] == 'event') ? 'active' : '';
$active_checkin = ($url[0] == 'checkin') ? 'active' : '';
?>
{{-- Mobile Sidebar Menu --}}
<div class="alert alert-light alert-elevate" role="alert" id="left-sidebar-mobile">
    <div class="alert-text">
        <select class="form-control kt-selectpicker" name="people_tab" id="people_tab" onchange="redirectEvent(this.value)">
            <option value="0" {{ ($active_event) ? 'selected' : '' }}>Events</option>
            <option value="1" {{ ($active_checkin) ? 'selected' : '' }}>Checkin</option>
        </select>
    </div>
</div>
<script>
    function redirectEvent(val) {
        switch (val) {
            case '0':
                window.location.href = "/events";
                break;
            case '1':
                window.location.href = "/checkin";
                break;
        }
    };
</script>