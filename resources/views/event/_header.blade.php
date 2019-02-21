<div class="member-bar {{ (!$event->status) ? 'member-inactive' : '' }}"">
    <i class="iicon-event-member-bar hidden-xs-down"></i>
    <div class="member-name">
        <div class="member-fullname">{{ $event->name }}</div>
        <span class="member-number">
            @if ($event->recur)
                <i class="fa fa-redo" style="padding-right: 5px"></i> Recurring
            @else
                <i class="fa fa-calendar" style="padding-right: 5px"></i> One-time
            @endif</span>
        <span class="member-split">&nbsp;|&nbsp;</span>
        <span class="dropdown" style="text-transform: none">
            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" style="padding: 1px 1px 1px 8px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ ($event->status) ? 'Active' : 'Inactive' }}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ ($event->status) ? '#' : "/event/$event->id/status/1" }}">Active</a>
                <a class="dropdown-item" href="{{ (!$event->status) ? '#' : "/event/$event->id/status/0" }}">Inactive</a>
                @if (!$event->status)
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-name="{{ $event->name }}" id="but_del_event">Delete</a>
                @endif
            </div>
        </span>
    </div>
    <?php
    $active_overview = $active_settings = $active_attendance = '';
    list($first, $rest) = explode('/', Request::path(), 2);
    if (!ctype_digit($rest)) {
        list($uid, $rest) = explode('/', $rest, 2);
        $active_settings = (preg_match('/^settings*/', $rest)) ? 'active' : '';
        $active_attendance = (preg_match('/^attendance*/', $rest)) ? 'active' : '';
    } else
        $active_overview = 'active';
    ?>

    <ul class="member-bar-menu" style="padding-right: 20px">
        <li class="member-bar-item {{ $active_overview }}"><i class="iicon-peoplechart"></i><a class="member-bar-link" href="/event/{{ $event->id }}" title="Overview">OVERVIEW</a></li>
        <li class="member-bar-item {{ $active_settings }}"><i class="iicon-settings"></i><a class="member-bar-link" href="/event/{{ $event->id }}/settings" title="Settings">SETTINGS</a></li>
        <li class="member-bar-item {{ $active_attendance }}"><i class="iicon-people"></i><a class="member-bar-link" href="/event/{{ $event->id }}/attendance/0" title="Attendance">
                <span class="d-none d-md-block">ATTENDANCE</span><span class="d-md-none">ATTEND</span></a></li>
    </ul>
</div>