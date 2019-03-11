@extends('layouts/main')

@section('content')
    @include('event/_header')

    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <style type="text/css">
        .legendColour {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 0px 10px 0px 20px;
            padding-left: 4px;
        }
    </style>
{{--}}

    <div class="row">
        <div class="col-xl-6 col-lg-12">
            <!--Begin::Portlet-->
            <div class="m-portlet  m-portlet--full-height ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Recent Activities
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">
                                <a href="#" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl m-dropdown__toggle">
                                    <i class="la la-ellipsis-h m--font-brand"></i>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
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
                                                    <li class="m-nav__item">
                                                        <a href="" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-info"></i>
                                                            <span class="m-nav__link-text">FAQ</span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                            <span class="m-nav__link-text">Support</span>
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
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="380" data-mobile-height="300" style="height: 380px; overflow: hidden;">
                        <!--Begin::Timeline 2 -->
                        <div class="m-timeline-2">
                            <div class="m-timeline-2__items  m--padding-top-25 m--padding-bottom-30">
                                <div class="m-timeline-2__item">
                                    <span class="m-timeline-2__item-time">10:00</span>
                                    <div class="m-timeline-2__item-cricle">
                                        <i class="fa fa-genderless m--font-danger"></i>
                                    </div>
                                    <div class="m-timeline-2__item-text  m--padding-top-5">
                                        Lorem ipsum dolor sit amit,consectetur eiusmdd tempor<br>
                                        incididunt ut labore et dolore magna
                                    </div>
                                </div>
                                <div class="m-timeline-2__item m--margin-top-30">
                                    <span class="m-timeline-2__item-time">12:45</span>
                                    <div class="m-timeline-2__item-cricle">
                                        <i class="fa fa-genderless m--font-success"></i>
                                    </div>
                                    <div class="m-timeline-2__item-text m-timeline-2__item-text--bold">
                                        AEOL Meeting With
                                    </div>
                                    <div class="m-list-pics m-list-pics--sm m--padding-left-20">
                                        <a href="#"><img src="assets/app/media/img/users/100_4.jpg" title=""></a>
                                        <a href="#"><img src="assets/app/media/img/users/100_13.jpg" title=""></a>
                                        <a href="#"><img src="assets/app/media/img/users/100_11.jpg" title=""></a>
                                        <a href="#"><img src="assets/app/media/img/users/100_14.jpg" title=""></a>
                                    </div>
                                </div>
                                <div class="m-timeline-2__item m--margin-top-30">
                                    <span class="m-timeline-2__item-time">14:00</span>
                                    <div class="m-timeline-2__item-cricle">
                                        <i class="fa fa-genderless m--font-brand"></i>
                                    </div>
                                    <div class="m-timeline-2__item-text m--padding-top-5">
                                        Make Deposit <a href="#" class="m-link m-link--brand m--font-bolder">USD 700</a> To ESL.
                                    </div>
                                </div>
                                <div class="m-timeline-2__item m--margin-top-30">
                                    <span class="m-timeline-2__item-time">16:00</span>
                                    <div class="m-timeline-2__item-cricle">
                                        <i class="fa fa-genderless m--font-warning"></i>
                                    </div>
                                    <div class="m-timeline-2__item-text m--padding-top-5">
                                        Lorem ipsum dolor sit amit,consectetur eiusmdd tempor<br>
                                        incididunt ut labore et dolore magna elit enim at minim<br>
                                        veniam quis nostrud
                                    </div>
                                </div>
                                <div class="m-timeline-2__item m--margin-top-30">
                                    <span class="m-timeline-2__item-time">17:00</span>
                                    <div class="m-timeline-2__item-cricle">
                                        <i class="fa fa-genderless m--font-info"></i>
                                    </div>
                                    <div class="m-timeline-2__item-text m--padding-top-5">
                                        Placed a new order in <a href="#" class="m-link m-link--brand m--font-bolder">SIGNATURE MOBILE</a> marketplace.
                                    </div>
                                </div>
                                <div class="m-timeline-2__item m--margin-top-30">
                                    <span class="m-timeline-2__item-time">16:00</span>
                                    <div class="m-timeline-2__item-cricle">
                                        <i class="fa fa-genderless m--font-brand"></i>
                                    </div>
                                    <div class="m-timeline-2__item-text m--padding-top-5">
                                        Lorem ipsum dolor sit amit,consectetur eiusmdd tempor<br>
                                        incididunt ut labore et dolore magna elit enim at minim<br>
                                        veniam quis nostrud
                                    </div>
                                </div>
                                <div class="m-timeline-2__item m--margin-top-30">
                                    <span class="m-timeline-2__item-time">17:00</span>
                                    <div class="m-timeline-2__item-cricle">
                                        <i class="fa fa-genderless m--font-danger"></i>
                                    </div>
                                    <div class="m-timeline-2__item-text m--padding-top-5">
                                        Received a new feedback on <a href="#" class="m-link m-link--brand m--font-bolder">FinancePro App</a> product.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End::Timeline 2 -->
                        <div class="ps__rail-x" style="left: 0px; bottom: -32px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 32px; height: 380px; right: 4px;"><div class="ps__thumb-y" tabindex="0" style="top: 23px; height: 277px;"></div></div></div>
                </div>
            </div>
            <!--End::Portlet-->	</div>
        <div class="col-xl-6 col-lg-12">
            <!--Begin::Portlet-->
            <div class="m-portlet m-portlet--full-height ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Recent Notifications
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget2_tab1_content" role="tab">
                                    Today
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab2_content" role="tab">
                                    Month
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="m_widget2_tab1_content">
                            <!--Begin::Timeline 3 -->
                            <div class="m-timeline-3">
                                <div class="m-timeline-3__items">
                                    <div class="m-timeline-3__item m-timeline-3__item--info">
                                        <span class="m-timeline-3__item-time">09:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Lorem ipsum dolor sit amit,consectetur eiusmdd tempor
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By Bob
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--warning">
                                        <span class="m-timeline-3__item-time">10:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Lorem ipsum dolor sit amit
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By Sean
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--brand">
                                        <span class="m-timeline-3__item-time">11:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Lorem ipsum dolor sit amit eiusmdd tempor
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By James
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--success">
                                        <span class="m-timeline-3__item-time">12:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Lorem ipsum dolor
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By James
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--danger">
                                        <span class="m-timeline-3__item-time">14:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Lorem ipsum dolor sit amit,consectetur eiusmdd
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By Derrick
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--info">
                                        <span class="m-timeline-3__item-time">15:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Lorem ipsum dolor sit amit,consectetur
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By Iman
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--brand">
                                        <span class="m-timeline-3__item-time">17:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Lorem ipsum dolor sit consectetur eiusmdd tempor
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By Aziko
                                </a>
								</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End::Timeline 3 -->
                        </div>
                        <div class="tab-pane" id="m_widget2_tab2_content">
                            <!--Begin::Timeline 3 -->
                            <div class="m-timeline-3">
                                <div class="m-timeline-3__items">
                                    <div class="m-timeline-3__item m-timeline-3__item--info">
                                        <span class="m-timeline-3__item-time m--font-focus">09:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Contrary to popular belief, Lorem Ipsum is not simply random text.
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By Bob
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--warning">
                                        <span class="m-timeline-3__item-time m--font-warning">10:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								There are many variations of passages of Lorem Ipsum available.
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By Sean
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--brand">
                                        <span class="m-timeline-3__item-time m--font-primary">11:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Contrary to popular belief, Lorem Ipsum is not simply random text.
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By James
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--success">
                                        <span class="m-timeline-3__item-time m--font-success">12:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								The standard chunk of Lorem Ipsum used since the 1500s is reproduced.
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By James
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--danger">
                                        <span class="m-timeline-3__item-time m--font-warning">14:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Latin words, combined with a handful of model sentence structures.
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By Derrick
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--info">
                                        <span class="m-timeline-3__item-time m--font-info">15:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Contrary to popular belief, Lorem Ipsum is not simply random text.
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By Iman
                                </a>
								</span>
                                        </div>
                                    </div>
                                    <div class="m-timeline-3__item m-timeline-3__item--brand">
                                        <span class="m-timeline-3__item-time m--font-danger">17:00</span>
                                        <div class="m-timeline-3__item-desc">
								<span class="m-timeline-3__item-text">
								Lorem Ipsum is therefore always free from repetition, injected humour.
								</span><br>
								<span class="m-timeline-3__item-user-name">
								<a href="#" class="m-link m-link--metal m-timeline-3__item-link">
                                    By Aziko
                                </a>
								</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End::Timeline 3 -->
                        </div>
                    </div>
                </div>
            </div>
            <!--End::Portlet-->  	</div>
    </div>

--}}

    <div class="row">
        {{-- Overview --}}
        <?php
        $students_last_week = $event->studentAttendance(1);
        $students_last_month = $event->studentAttendance(4);
        $students_last_month3 = $event->studentAttendance(12);
        $students_last_year = $event->studentAttendance(52);
        $new_students_last_month = $event->studentAttendance(4, 'new');
        $mia_students_last_month3 = $event->studentMIA(12);
        ?>
        <div class="col-md-8">
            <div class="m-portlet">
                <div class="m-portlet__body" style="padding-bottom: 0px">
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-10">
                            <h4>Overview</h4>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-secondary m-btn--pill btn-sm m-btn pull-right" data-container="body" data-toggle="m-popover"
                                    data-placement="left" data-original-title="" title="" data-content="If a student has checked-in in the last 3 months, they count as an active student. Absent students are the remaining students who have previously attended this event in the past. ">
                                <i class="fa fa-question"></i>
                            </button>
                        </div>
                    </div>
                    {{-- Basic Stats --}}
                    <div class="row" style="color: #fff; font-size: 16px;">
                        <div class="col-md-4" style="height: 100px; margin-bottom: 10px">
                            <div style="background: #9DA8ED; padding: 10px 20px;">
                                <h3 style="padding-top: 15px">{{ count($students_last_week) }} Students</h3>
                                <p>Last Week</p>
                            </div>
                        </div>
                        <div class="col-md-4" style="height: 100px; margin-bottom: 10px">
                            <div style="background: #9DA8ED; padding: 10px 20px">
                                <h3 style="padding-top: 15px">{{ count($students_last_month) }} Students</h3>
                                <p>Last Month</p>
                            </div>
                        </div>
                        <div class="col-md-4" style="height: 100px; margin-bottom: 10px">
                            <div style="background: #9DA8ED; padding: 10px 20px;">
                                <h3 style="padding-top: 15px">{{ count($students_last_year) }} Students</h3>
                                <p>Last Year</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs  m-tabs-line m-tabs-line--primary" role="tablist">
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_tabs_3_1" role="tab">New Students ({{ $new_students_last_month->count() }})</a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_3_2" role="tab">Active Students ({{ $students_last_month3->count() }})</a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_3_3" role="tab">Absent Students ({{ $mia_students_last_month3->count() }})</a>
                                </li>
                                {{--}}
                                <li class="nav-item dropdown m-tabs__item">
                                    <a class="nav-link m-tabs__link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Settings</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Action</a>
                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Another action</a>
                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Something else here</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Separated link</a>
                                    </div>
                                </li>--}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding-top: 0px">
                    <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="200" data-scrollbar-shown="true" style="height: 300px; overflow: hidden;">
                        <div class="tab-content">
                            {{-- New Students --}}
                            <div class="tab-pane active show" id="m_tabs_3_1" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>First Check-in</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if ($new_students_last_month->count())
                                            @foreach ($new_students_last_month->sortBy('firstname')->sortByDesc('firstEventEver') as $student)
                                                <tr id="new-{{ $student->id }}" style="cursor: pointer" class="link-person">
                                                    <td><img src="{{ $student->photoSmPath }}" width="30" class="rounded-circle" style="margin-right: 15px"> {{ $student->name }}</td>
                                                    <td>{!!  ($student->phone) ? "<i class='fa fa-phone' style='padding-right: 4px'></i>$student->phone" : '' !!}</td>
                                                    <td>{{ $student->firstEvent($event->id)->start->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">No new students</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- Active Students --}}
                            <div class="tab-pane" id="m_tabs_3_2" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Last Check-in</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if ($students_last_month3->count())
                                            @foreach ($students_last_month3->sortBy('firstname')->sortByDesc('lastEventEver') as $student)
                                                <tr id="active-{{ $student->id }}" style="cursor: pointer" class="link-person">
                                                    <td><img src="{{ $student->photoSmPath }}" width="30" class="rounded-circle" style="margin-right: 15px"> {{ $student->name }}</td>
                                                    <td>{!!  ($student->phone) ? "<i class='fa fa-phone' style='padding-right: 4px'></i>$student->phone" : '' !!}</td>
                                                    <td>{{ $student->lastEvent($event->id)->start->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">No active students</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- Absent Students --}}
                            <div class="tab-pane" id="m_tabs_3_3" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Last Check-in</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if ($mia_students_last_month3->count())
                                            @foreach ($mia_students_last_month3->sortBy('firstname')->sortByDesc('lastEventEver') as $student)
                                                <tr id="active-{{ $student->id }}" style="cursor: pointer" class="link-person">
                                                    <td><img src="{{ $student->photoSmPath }}" width="30" class="rounded-circle" style="margin-right: 15px"> {{ $student->name }}</td>
                                                    <td>{!!  ($student->phone) ? "<i class='fa fa-phone' style='padding-right: 4px'></i>$student->phone" : '' !!}</td>
                                                    <td>{{ ($student->lastEvent($event->id)) ? $student->lastEvent($event->id)->start->diffForHumans() : 'never'}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">No absent students</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="m_tabs_3_4" role="tabpanel">
                                something
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Top Attendance --}}
        <div class="col-md-4">
            <div class="m-portlet">
                <div class="m-portlet__head" style="border: none">
                    <div class="row" style="padding: 25px 0px">
                        <div class="col-12">
                            <h4>Top Attendance
                                <small style="color:#999"> &nbsp; {{ ($event->id == 3) ? "(Past 12 months)" : "(Past 12 weeks)" }}</small>
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding-top: 0px">
                    <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="400" data-mobile-height="400" style="height: 400px; overflow: hidden;">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                <?php
                                $x = 0;
                                $now = \Carbon\Carbon::now();
                                $weeks = ($event->id == 3) ? 52 : 12;
                                $from = \Carbon\Carbon::now()->subWeeks($weeks);
                                $instances = $event->betweenDates($from->format('Y-m-d'), $now->format('Y-m-d'));
                                $list = $event->studentTopAttendance($weeks);
                                ?>
                                @foreach ($list as $pid => $count )
                                    <?php
                                    $x ++;
                                    $student = \App\Models\People\People::find($pid);
                                    ?>
                                    <tr id="top-{{ $student->id }}" style="cursor: pointer" class="link-person">
                                        <td>
                                            <img src="{{ $student->photoSmPath }}" width="40" class="rounded-circle" style="margin-right: 15px">
                                        </td>
                                        <td>
                                            <div style="font-size: 14px">{{ $student->name }}</div>
                                            <div style="font-size: 10px;">{{ ($student->grade) ? "Grade $student->grade" : '' }}</div>
                                        </td>
                                        <td>
                                            <div style="font-size: 18px" data-container="body" data-toggle="m-popover" data-placement="left" data-original-title="" title="" data-content="{{ $count }}/{{ count($instances) }}">
                                                {{ round($count/count($instances) * 100, 0, PHP_ROUND_HALF_UP) }}%
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Weekly Totals --}}
    <div class="row">
        <div class="col-12">
            <div class="m-portlet">
                <div class="m-portlet__body">
                    {{-- Chart --}}
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-10">
                            <h4 id="chart_title"></h4>
                        </div>
                        <div class="col-2">
                            <div class="dropdown pull-right">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">View</button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                                    <button class="dropdown-item" type="button" id="sel_weekly">Weekly Totals</button>
                                    <button class="dropdown-item" type="button" id="sel_compare3">3yr Comparison</button>
                                    <button class="dropdown-item" type="button" id="sel_compare5">5yr Comparison</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="chart_totals" style="height: 500px;">
                                <div class="text-center" id="chart_loading"><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom" style="margin-top: 110px"></i> Loading...</div>
                            </div>
                            <div id="chart_legend" align="center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('vendor-scripts')
@stop

@section('page-styles')
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="/js/event-shared-functions.js" type="text/javascript"></script>
<script src="/assets/app/js/dashboard.js" type="text/javascript"></script>

<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    $(document).ready(function () {

        $(".link-person").click(function () {
            var split = this.id.split("-");
            var id = split[1];
            window.location.href = "/people/" + id;

        });

        $("#sel_weekly").click(function () {
            weeklyTotals()
        });

        $("#sel_compare3").click(function () {
            compareYear3()
        });

        $("#sel_compare5").click(function () {
            compareYear5()
        });

        weeklyTotals()

        function weeklyTotals() {
            $("#chart_title").text("Weekly Totals");
            $("#chart_totals").empty();
            $("#chart_totals").html('<div class="text-center" id="chart_loading"><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom" style="margin-top: 110px"></i> Loading...</div>');

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "/stats/event/weekly-totals",
                data: {eid: "{{ $event->id }}"},
            }).done(function (data) {
                $("#chart_totals").empty();
                chart_totals = new Morris.Bar({
                    element: 'chart_totals',
                    barGap: 3,         // sets the space between bars in a single bar group. Default 3:
                    barSizeRatio: 0.9, // proportion of the width of the entire graph given to bars. Default 0.75
                    stacked: true,
                    resize: true,
                    data: [0, 0, 0],
                    xkey: 'y',
                    ykeys: ['a', 'b'],
                    barColors: ["#73BEE0", "#E17294",],
                    labels: ['Students', 'New Students']
                });
                // When the response to the AJAX request comes back render the chart with new data
                chart_totals.setData(data);

                $('#chart_legend').empty();
                chart_totals.options.labels.forEach(function (label, i) {
                    var legendLabel = $('<span>' + label + '</span>');
                    var legendItem = $('<span class="legendColour"></span>').css('background-color', chart_totals.options.barColors[i]);
                    $('#chart_legend').append(legendItem);
                    $('#chart_legend').append(legendLabel);
                })
                $('#chart_loading').hide();

            }).fail(function () {
                alert("error occured"); // If there is no communication between the server, show an error
            });
        }

        function compareYear3() {
            $("#chart_title").text("3 Year Comparison");
            $("#chart_totals").empty();
            $("#chart_totals").html('<div class="text-center" id="chart_loading"><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom" style="margin-top: 110px"></i> Loading...</div>');

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "/stats/event/compare-year/3",
                data: {eid: "{{ $event->id }}"},
            }).done(function (data) {
                chart_totals = new Morris.Bar({
                    element: 'chart_totals',
                    barGap: 3,         // sets the space between bars in a single bar group. Default 3:
                    barSizeRatio: 0.9, // proportion of the width of the entire graph given to bars. Default 0.75
                    resize: true,
                    data: [0, 0, 0, 0],
                    xkey: 'y',
                    ykeys: ['a', 'b', 'c'],
                    barColors: ["#73BEE0", "#717CE0", "#CF72E0"],
                    labels: ['2017', '2018', '2019']
                });
                // When the response to the AJAX request comes back render the chart with new data
                chart_totals.setData(data);

                $('#chart_legend').empty();
                chart_totals.options.labels.forEach(function (label, i) {
                    var legendLabel = $('<span>' + label + '</span>');
                    var legendItem = $('<span class="legendColour"></span>').css('background-color', chart_totals.options.barColors[i]);
                    $('#chart_legend').append(legendItem);
                    $('#chart_legend').append(legendLabel);
                })
                $('#chart_loading').hide();
            }).fail(function () {
                alert("error occured"); // If there is no communication between the server, show an error
            });
        }

        function compareYear5() {
            $("#chart_title").text("5 Year Comparison");
            $("#chart_totals").empty();
            $("#chart_totals").html('<div class="text-center" id="chart_loading"><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom" style="margin-top: 110px"></i> Loading...</div>');

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "/stats/event/compare-year/5",
                data: {eid: "{{ $event->id }}"},
            }).done(function (data) {
                chart_totals = new Morris.Bar({
                    element: 'chart_totals',
                    barGap: 3,         // sets the space between bars in a single bar group. Default 3:
                    barSizeRatio: 0.9, // proportion of the width of the entire graph given to bars. Default 0.75
                    resize: true,
                    data: [0, 0, 0, 0, 0, 0],
                    xkey: 'y',
                    ykeys: ['a', 'b', 'c', 'd', 'e'],
                    barColors: ["#73BEE0", "#717CE0", "#CF72E0", '#E17294', '#DFB873'],
                    labels: ['2015', '2016', '2017', '2018', '2019']
                });
                // When the response to the AJAX request comes back render the chart with new data
                chart_totals.setData(data);

                $('#chart_legend').empty();
                chart_totals.options.labels.forEach(function (label, i) {
                    var legendLabel = $('<span>' + label + '</span>');
                    var legendItem = $('<span class="legendColour"></span>').css('background-color', chart_totals.options.barColors[i]);
                    $('#chart_legend').append(legendItem);
                    $('#chart_legend').append(legendLabel);
                })
                $('#chart_loading').hide();
            }).fail(function () {
                alert("error occured"); // If there is no communication between the server, show an error
            });
        }
    });
</script>
@stop
