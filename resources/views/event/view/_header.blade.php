<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">{{ $event->name }}</h3>
        <h4 class="kt-subheader__desc">
            @if ($event->recur)
                <i class="fa fa-redo" style="padding-right: 5px"></i> Recurring
            @else
                <i class="fa fa-calendar" style="padding-right: 5px"></i> One-time
            @endif
        </h4>
    </div>
    <div class="kt-subheader__toolbar">
        <div class="kt-subheader__wrapper">
            <div>
                <div class="dropdown dropdown-inline">
                    <a href="#" class="btn btn-light kt-subheader__btn-options dropdown-toggle" style="padding: .5rem 1rem" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ ($event->status) ? 'Active' : 'Inactive' }}</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if (!$event->status)
                            <a class="dropdown-item" href="{{ ($event->status) ? '#' : "/event/$event->id/status/1" }}"><i class="fa fa-eye" style="width: 25px"></i> Make active</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-eid="{{ $event->id }}" data-name="{{ $event->name }}" id="but_del_event"><i class="fa fa-trash-alt" style="width: 25px"></i> Delete</a>
                        @else
                            <a class="dropdown-item" href="{{ (!$event->status) ? '#' : "/event/$event->id/status/0" }}"><i class="fa fa-eye-slash" style="width: 25px"></i> Make inactive</a>
                        @endif
                    </div>
                </div>
                <div class="dropdown dropdown-inline">
                    <a href="#" class="btn kt-subheader__btn-secondary kt-subheader__btn-options" style="padding: 1.4rem 1rem;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" style="font-size: 18px !important; color: #fff"></i> </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#"><i class="fa fa-lock" style="width: 25px"></i> Something</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-user-friends" style="width: 25px"></i> Something else</a>
                        @if (!$event->status)
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" id="but_del_person"><i class="fa fa-trash-alt" style="width: 25px"></i> Delete</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
