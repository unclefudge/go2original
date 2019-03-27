<!--begin: User bar -->
<div class="kt-header__topbar-item kt-header__topbar-item--user">
    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
        <span class="kt-header__topbar-welcome">Hi,</span>
        <span class="kt-header__topbar-username">{{ Auth::user()->firstname }}</span>

        <span class="kt-header__topbar-icon"><i class="fa fa-user"></i></span>
        <img alt="Pic" src="/massets/media/users/300_21.jpg" class="kt-hidden"/>
    </div>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
        <!--begin: Head -->
        <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(/img/bg-profile.jpg)">
            <div class="kt-user-card__avatar">
                <img alt="Pic" src="{{ Auth::user()->photoSmPath  }}"/>
                <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                <!--<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"></span>-->
            </div>
            <div class="kt-user-card__name">{{ Auth::user()->name }}</div>
            <!--
            <div class="kt-user-card__badge">
                <span class="btn btn-success btn-sm btn-bold btn-font-md">23 messages</span>
            </div>
            -->
        </div>
        <!--end: Head -->

        <!--begin: Navigation -->
        <div class="kt-notification">
            <a href="/people/{{ Auth::user()->id }}" class="kt-notification__item">
                <div class="kt-notification__item-icon"><i class="flaticon2-calendar-3 kt-font-primary"></i></div>
                <div class="kt-notification__item-details">
                    <div class="kt-notification__item-title kt-font-bold">My Profile</div>
                    <div class="kt-notification__item-time">Account settings and localisation</div>
                </div>
            </a>
            <a href="/account" class="kt-notification__item">
                <div class="kt-notification__item-icon"><i class="flaticon2-hourglass kt-font-brand"></i></div>
                <div class="kt-notification__item-details">
                    <div class="kt-notification__item-title kt-font-bold">Account</div>
                    <div class="kt-notification__item-time">Account settings and more</div>
                </div>
            </a>
            <div class="kt-notification__custom">
                <a href="/logout" class="btn btn-dark btn-sm btn-bold">Sign Out</a>
            </div>
        </div>
        <!--end: Navigation -->
    </div>
</div>
<!--end: User bar -->