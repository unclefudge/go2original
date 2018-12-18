<div class="m-portlet">
    {{--
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">Personal Information</h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <button type="button" class="m-portlet__nav-link btn btn-sm btn-outline-accent m-btn--pill" data-toggle="modal" data-target="#modal_profile">Edit</button>
                </li>
                <!--
                <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">
                    <a href="#" class="m-portlet__nav-link m-dropdown__toggle btn btn-sm btn-accent m-btn m-btn--pill">Settings</a>
                    <div class="m-dropdown__wrapper" style="z-index: 101;">
                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 47px;"></span>
                        <div class="m-dropdown__inner">
                            <div class="m-dropdown__body">
                                <div class="m-dropdown__content">
                                    <ul class="m-nav">
                                        <li class="m-nav__section m-nav__section--first">
                                            <span class="m-nav__section-text">Quick Actions</span>
                                        </li>
                                        <li class="m-nav__item">
                                            <a href="" class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                <span class="m-nav__link-text">Activity</span>
                                            </a>
                                        </li>
                                        <li class="m-nav__item">
                                            <a href="" class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                <span class="m-nav__link-text">Messages</span>
                                            </a>
                                        </li>
                                        <li class="m-nav__separator m-nav__separator--fit">
                                        </li>
                                        <li class="m-nav__item">
                                            <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">Cancel</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>-->
            </ul>
        </div>
    </div> --}}
    @include('form-error')
    <div class="m-portlet__body">
        {{-- Personal Info --}}
        <div class="row" style="padding-bottom: 10px">
            <div class="col-10"><h4>Personal Info</h4></div>
            <div class="col-2"><a href="#" class="pull-right" data-toggle="modal" data-target="#modal_personal"> <i class="fa fa-edit" style="padding-right: 3px"></i><span class="d-none d-md-inline">Edit</span></a></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row" style="padding: 5px 0px">
                    <div class="col-1">
                        @if ($people->gender == 'Male')
                            <i class="fa fa-2x fa-male"></i>
                        @elseif ($people->gender == 'Male')
                            <i class="fa fa-2x fa-female" style="padding-right: 5px"></i>
                        @else
                            <i class="fa fa-user" style="padding-right: 5px"></i>
                        @endif
                    </div>
                    <div class="col col-sm-11 col-md-11">
                        {{ $people->type }} <br>
                        {!! ($people->dob) ? "$people->age years old &nbsp; (".$people->dob->format('M d Y').") <br>" : '' !!}
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="row" style="padding: 5px 0px">
                    <div class="col-1 col-md-3"><i class="fa fa-envelope" style="padding-right: 5px"></i> <span class="d-none d-sm-inline">Email</span></div>
                    <div class="col col-md-9">{!! ($people->email) ? "<a href='mailto:$people->email'> $people->email</a>" : '-' !!}</div>
                </div>
                <div class="row" style="padding: 5px 0px">
                    <div class="col-1 col-md-3"><i class="fa fa-phone" style="padding-right: 5px"></i> <span class="d-none d-md-inline">Phone</span></div>
                    <div class="col col-md-9">{!! ($people->phone) ? "<a href='tel:'".preg_replace("/[^0-9]/", "", $people->phone)."> $people->phone </a>" : '-' !!}</div>
                </div>
                <div class="row" style="padding: 5px 0px">
                    <div class="col-1 col-md-3"><i class="fa fa-map-marker-alt" style="padding-right: 5px"></i> <span class="d-none d-md-inline">Address</span></div>
                    <div class="col col-md-9">{!! $people->address_formatted !!}</div>
                </div>

            </div>
        </div>

        <hr class="field-hr">

        @if ($people->type == 'Student')
            <div class="row">
                {{-- School --}}
                <div class="col-md-6">
                    <div class="row" style="padding: 5px 0px">
                        <div class="col-10"><h5>School</h5></div>
                        <div class="col-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-1"><i class="fa fa-apple-alt"></i></div>
                        <div class="col">Grade {{ $people->grade }}</div>
                    </div>
                </div>

                {{-- Media --}}
                <div class="col-md-6">
                    <div class="row" style="padding: 5px 0px">
                        <div class="col-10"><h5>Media Consent</h5></div>
                        <div class="col-2"><!--<a href="#" class="pull-right" data-toggle="modal" data-target="#modal_profile"> <i class="fa fa-edit"></i></a>--></div>
                    </div>
                    <div class="row">
                        <div class="col-1"><i class="fa fa-user-slash"></i></div>
                        <div class="col">Media Consent</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

