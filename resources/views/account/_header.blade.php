<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">{{ $account->name }}</h3>
        <h4 class="kt-subheader__desc">Account details & settings</h4>
    </div>
    {{--}}
    <div class="kt-subheader__toolbar">
        <div class="kt-subheader__wrapper">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal_create_event">Add Event</a>
            <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="">
                <a href="#" class="btn kt-subheader__btn-secondary kt-subheader__btn-options" style="padding: 1.4rem 1rem;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" style="font-size: 18px !important; color: #fff"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" onclick="uSet('show_inactive_events', 0)" id="but_hide_archived"><i class="fa fa-eye-slash" style="width: 25px"></i> Hide inactive events</button>
                    <button class="dropdown-item" onclick="uSet('show_inactive_events', 1)" id="but_show_archived"><i class="fa fa-eye" style="width: 25px"></i> Show inactive events</button>
                </div>
            </div>
        </div>
    </div>
    --}}
</div>