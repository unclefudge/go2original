<div class="member-bar {{ (!$people->status) ? 'member-inactive' : '' }}">
    <i class="iicon-user-member-bar hidden-xs-down"></i>
    <div class="member-name">
        <div class="member-fullname">{{ $people->firstname }} {{ $people->lastname }}</div>
        <span class="member-number">{{ $people->type }}</span>
        <span class="member-split">&nbsp;|&nbsp;</span>
        <span class="dropdown" style="text-transform: none">
            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" style="padding: 1px 1px 1px 8px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ ($people->status) ? 'Active' : 'Inactive' }}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ ($people->status) ? '#' : "/people/$people->id/status/1" }}">Active</a>
                <a class="dropdown-item" href="{{ (!$people->status) ? '#' : "/people/$people->id/status/0" }}">Inactive</a>
                @if (!$people->status)
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" id="but_del_person">Delete</a>
                @endif
            </div>
        </span>
    </div>

    <ul class="member-bar-menu">
        <li class="member-bar-item "><i class="iicon-profile"></i><a class="member-bar-link" href="/user/" title="Profile">PROFILE</a></li>

        <li class="member-bar-item "><i class="iicon-document"></i><a class="member-bar-link" href="/user//doc" title="Documents">
                <span class="d-none d-md-block">DOCUMENTS</span><span class="d-md-none">DOCS</span></a></li>

        <li class="member-bar-item "><i class="iicon-lock"></i><a class="member-bar-link" href="/user//security" title="Security">SECURITY</a></li>
    </ul>
</div>