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

    <div class="row">
        {{-- Overview --}}
        <?php
        $students_last_week = $event->studentAttendance(1);
        $students_last_month = $event->studentAttendance(4);
        $students_last_month3 = $event->studentAttendance(12);
        $students_last_year = $event->studentAttendance(52);
        ?>
        <div class="col-md-8">
            <div class="m-portlet">
                <div class="m-portlet__body" style="padding-bottom: 0px">
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-12">
                            <h4>Overview</h4>
                        </div>
                    </div>
                    <div class="row" style="color: #fff; font-size: 16px;">
                        <div class="col-md-4" style="height: 100px; margin-bottom: 10px">
                            <div style="background: #bbb; padding: 10px 20px;">
                                <h3 style="padding-top: 15px">{{ count($students_last_week) }} Students</h3>
                                <p>Last Week</p>
                            </div>
                        </div>
                        <div class="col-md-4" style="height: 100px; margin-bottom: 10px">
                            <div style="background: #bbb; padding: 10px 20px">
                                <h3 style="padding-top: 15px">{{ count($students_last_month) }} Students</h3>
                                <p>Last Month</p>
                            </div>
                        </div>
                        <div class="col-md-4" style="height: 100px; margin-bottom: 10px">
                            <div style="background: #bbb; padding: 10px 20px;">
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
                                    <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_tabs_3_1" role="tab">New Students</a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_3_2" role="tab">Active Students</a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_3_3" role="tab">Absent Students</a>
                                </li>
                                <li class="nav-item dropdown m-tabs__item">
                                    <a class="nav-link m-tabs__link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Settings</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Action</a>
                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Another action</a>
                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Something else here</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" data-toggle="tab" href="#m_tabs_3_4">Separated link</a>
                                    </div>
                                </li>
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
                                        @foreach ($event->studentAttendance(4, 'new')->sortBy('firstname')->sortByDesc('firstEvent') as $student)
                                            <tr id="new-{{ $student->id }}" style="cursor: pointer" class="link-person">
                                                <td><img src="{{ $student->photoSmPath }}" width="30" class="rounded-circle" style="margin-right: 15px"> {{ $student->name }}</td>
                                                <td>{!!  ($student->phone) ? "<i class='fa fa-phone' style='padding-right: 4px'></i>$student->phone" : '' !!}</td>
                                                <td>{{ $student->firstEvent->start->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
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
                                        @foreach ($event->studentAttendance(12)->sortBy('firstname')->sortByDesc('lastEvent') as $student)
                                            <tr id="active-{{ $student->id }}" style="cursor: pointer" class="link-person">
                                                <td><img src="{{ $student->photoSmPath }}" width="30" class="rounded-circle" style="margin-right: 15px"> {{ $student->name }}</td>
                                                <td>{!!  ($student->phone) ? "<i class='fa fa-phone' style='padding-right: 4px'></i>$student->phone" : '' !!}</td>
                                                <td>{{ $student->lastEvent->start->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
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
                                        @foreach ($event->studentMIA(12)->sortBy('firstname')->sortByDesc('lastEvent') as $student)
                                            <tr id="active-{{ $student->id }}" style="cursor: pointer" class="link-person">
                                                <td><img src="{{ $student->photoSmPath }}" width="30" class="rounded-circle" style="margin-right: 15px"> {{ $student->name }}</td>
                                                <td>{!!  ($student->phone) ? "<i class='fa fa-phone' style='padding-right: 4px'></i>$student->phone" : '' !!}</td>
                                                <td>{{ ($student->lastEvent) ? $student->lastEvent->start->diffForHumans() : 'never'}}</td>
                                            </tr>
                                        @endforeach
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
                                <small style="color:#999"> &nbsp; (Past 12 weeks)</small>
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
                                $now = \Carbon\Carbon::now()->timezone(session('tz'));
                                $from = \Carbon\Carbon::now()->timezone(session('tz'))->subWeeks(12);
                                $instances = $event->betweenDates($from->format('Y-m-d'), $now->format('Y-m-d'));
                                ?>
                                @foreach ($event->studentTopAttendance(12) as $pid => $count )
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
                                            <span style="font-size: 18px">{{ $count/count($instances) * 100 }}%</span><br>
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
    <script src="//www.amcharts.com/lib/4/core.js"></script>
    <script src="//www.amcharts.com/lib/4/charts.js"></script>
    <script src="//www.amcharts.com/lib/4/maps.js"></script>
@stop

@section('page-styles')
    <link href="//www.amcharts.com/lib/3/plugins/export/export.css" rel="stylesheet" type="text/css"/>
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="/js/event-shared-functions.js" type="text/javascript"></script>

<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    $(document).ready(function () {

        $(".link-person").click(function () {
            var split = this.id.split("-");
            var id = split[1];
            window.location.href = "/people/" + id;

        });


        weeklyTotals()

        $("#sel_weekly").click(function () {
            weeklyTotals()
        });

        $("#sel_compare3").click(function () {
            compareYear3()
        });

        $("#sel_compare5").click(function () {
            compareYear5()
        });


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
                // If there is no communication between the server, show an error
                alert("error occured");
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
                // If there is no communication between the server, show an error
                alert("error occured");
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
                // If there is no communication between the server, show an error
                alert("error occured");
            });
        }
    });
</script>
@stop
