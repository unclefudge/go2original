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

{{-- Mobile Sidebar Menu --}}
<div class="alert alert-light alert-elevate" role="alert" id="left-sidebar-mobile">
    <div class="alert-text">
        <select class="form-control kt-selectpicker" name="people_tab" id="people_tab" onchange="redirectPeople(this.value)">
            <option value="0" {{ ($active_profile) ? 'selected' : '' }}>Profile</option>
            <option value="1" {{ ($active_medical) ? 'selected' : '' }}>Medical</option>
            <option value="2" {{ ($active_activity) ? 'selected' : '' }}>Activity</option>
            <option value="3" {{ ($active_notes) ? 'selected' : '' }}>Notes</option>
        </select>
    </div>
</div>
<script>
    function redirectPeople(val) {
        var uid = "{{ $user->id }}";
        switch (val) {
            case '0':
                window.location.href = "/people/" + uid;
                break;
            case '1':
                window.location.href = "/people/" + uid;
                break;
            case '2':
                window.location.href = "/people/" + uid + '/activity';
                break;
            case 3:
                window.location.href = "/people/" + uid;
                break;
        }
    };
</script>