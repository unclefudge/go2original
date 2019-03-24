@extends('layout/main')

@section('bodystyle')
    @if ($event->status)
        style="background-image: url(/img/head-purple.jpg)"
    @else
        style="background-image: url(/img/head-darkgrey.jpg)"
    @endif
@endsection

@section('subheader')
    @include('event/view/_header')
@endsection
@section('content')
    <style>
        .anyClass {
            height:200px;
            overflow-y: scroll;
        }
    </style>
    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="container-fluid">
            <div class="row">
                @include('event/view/_sidebar')
                {{-- Main Content --}}
                <div class="col" style="height: 100% !important; min-height: 100% !important;">
                    @include('event/view/_sidebar-mobile')
                    <div class="row">
                        <div class="col-lg-8">
                            {{-- Overview --}}
                            <div class="kt-portlet">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Overview</h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        <button type="button" class="btn btn-light btn-sm" data-container="body" data-toggle="kt-popover"
                                                data-placement="left" data-original-title="" title="" data-content="If a student has checked-in in the last 3 months, they count as an active student. Absent students are the remaining students who have previously attended this event in the past. ">
                                            <i class="fa fa-question"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <?php
                                    $students_last_week = $event->studentAttendance(1);
                                    $students_last_month = $event->studentAttendance(4);
                                    $students_last_month3 = $event->studentAttendance(12);
                                    $students_last_year = $event->studentAttendance(52);
                                    $new_students_last_month = $event->studentAttendance(4, 'new');
                                    $mia_students_last_month3 = $event->studentMIA(12);
                                    ?>
                                    {{-- Basic Stats --}}
                                    <div class="row" style="color: #fff; font-size: 16px;">
                                        <div class="col-md-4" style="height: 100px; margin-bottom: 10px">
                                            <div style="background: #9DA8ED; padding: 10px 20px;">
                                                <h3 class="text-white" style="padding-top: 15px">{{ count($students_last_week) }} Students</h3>
                                                <p>Last Week</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="height: 100px; margin-bottom: 10px">
                                            <div style="background: #9DA8ED; padding: 10px 20px">
                                                <h3 class="text-white" style="padding-top: 15px">{{ count($students_last_month) }} Students</h3>
                                                <p>Last Month</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="height: 100px; margin-bottom: 10px">
                                            <div style="background: #9DA8ED; padding: 10px 20px;">
                                                <h3 class="text-white" style="padding-top: 15px">{{ count($students_last_year) }} Students</h3>
                                                <p>Last Year</p>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-2x nav-tabs-line-primary" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab" href="#m_tabs_3_1" role="tab">New Students ({{ $new_students_last_month->count() }})</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#m_tabs_3_2" role="tab">Active Students ({{ $students_last_month3->count() }})</a>
                                                </li>
                                                <li class="nav-item ">
                                                    <a class="nav-link" data-toggle="tab" href="#m_tabs_3_3" role="tab">Absent Students ({{ $mia_students_last_month3->count() }})</a>
                                                </li>
                                                {{--}}
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link  dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Settings</a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Action</a>
                                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Another action</a>
                                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Separated link</a>
                                                    </div>
                                                </li>--}}
                                            </ul>
                                            <div style="height: 200px; overflow-y: scroll;">
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
                            </div>
                        </div>
                        <div class="col-lg-4">
                            {{-- Top Attendance --}}
                            <div class="kt-portlet">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Top Attendance
                                            <small style="color:#999"> &nbsp; {{ ($event->id == 3) ? "(Past 12 months)" : "(Past 12 weeks)" }}</small>
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div style="height: 400px; overflow-y: scroll;">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <tbody>
                                                <?php
                                                $x = 0;
                                                $now = \Carbon\Carbon::now();
                                                $weeks = ($event->id == 3) ? 52 : 12;
                                                $from = \Carbon\Carbon::now()->subWeeks($weeks);
                                                $instances = $event->betweenUTCDates($from->format('Y-m-d'), $now->format('Y-m-d'));
                                                $list = $event->studentTopAttendance($weeks);
                                                ?>
                                                @if (count($list))
                                                    @foreach ($list as $pid => $count )
                                                        <?php
                                                        $x ++;
                                                        $student = \App\User::find($pid);
                                                        ?>
                                                        <tr id="top-{{ $student->id }}" style="cursor: pointer" class="link-person">
                                                            <td>
                                                                <img src="{{ $student->photoSmPath }}" width="40" class="rounded-circle" style="margin-right: 15px">
                                                            </td>
                                                            <td>
                                                                <div style="font-size: 14px">{{ $student->name }}</div>
                                                                <div style="font-size: 10px;">{{ ($student->grade_id) ? "$student->grade_name" : '' }}</div>
                                                            </td>
                                                            <td>
                                                                <div style="font-size: 18px" data-container="body" data-toggle="m-popover" data-placement="left" data-original-title="" title="" data-content="{{ $count }}/{{ count($instances) }}">
                                                                    {{ round($count/count($instances) * 100, 0, PHP_ROUND_HALF_UP) }}%
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <br>No attendees
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                        </div>
                                        <div class="ps__rail-y" style="top: 0px; height: 200px; right: 0px;">
                                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 150px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Weekly Stats --}}
                    <div class="row">
                        <div class="col">
                            <div class="kt-portlet">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title" id="chart_title"></h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
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
                                <div class="kt-portlet__body">
                                    {{-- Chart --}}
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-styles')
@endsection

@section('vendor-scripts')
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
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
@endsection
