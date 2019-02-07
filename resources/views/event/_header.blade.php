<div class="member-bar">
    <!--<i class="fa fa-user ppicon-user-member-bar" style="font-size: 80px; opacity: .5; padding:5px"></i>-->
    {{--}}
    <div class="m-card-profile">
        <div class="m-card-profile__title m--hide">Your Profile</div>
        <div class="m-card-profile__pic">
            <div class="m-card-profile__pic-wrapper">
                <img src="/assets/app/media/img/users/user4.jpg" alt="">
            </div>
        </div>
        <div class="m-card-profile__details">
            <span class="m-card-profile__name">Mark Andre</span>
            <a href="" class="m-card-profile__email m-link">mark.andre@gmail.com</a>
        </div>
    </div>
    --}}
    <i class="iicon-user-member-bar hidden-xs-down"></i>
    <div class="member-name">
        <div class="member-fullname">{{ $event->name }}</div>
        <span class="member-number">{{ $event->recur }}</span>
        <span class="member-split">&nbsp;|&nbsp;</span>
        <span class="member-number">Grade</span>
        <!--<a href="/reseller/member/member_account_status/?member_id=8013759" class="member-status">Active</a>-->
    </div>

    <ul class="member-bar-menu">
        <li class="member-bar-item "><i class="iicon-profile"></i><a class="member-bar-link" href="/user/" title="Profile">SETTINGS</a></li>

        <li class="member-bar-item "><i class="iicon-document"></i><a class="member-bar-link" href="/user//doc" title="Documents">
                <span class="d-none d-md-block">ATTENDANCE</span><span class="d-md-none">ATTEND</span></a></li>

        <li class="member-bar-item "><i class="iicon-lock"></i><a class="member-bar-link" href="/user//security" title="Security">STATS</a></li>
    </ul>
</div>