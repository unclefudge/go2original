@extends('layouts/main')

@section('content')
    @include('event/_header')
    <style>
        th.active .arrow {
            opacity: 1;
        }

        .arrow {
            display: inline-block;
            vertical-align: middle;
            width: 0;
            height: 0;
            margin-left: 5px;
            opacity: 0.4;
        }

        .arrow.asc {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 4px solid #fff;
        }

        .arrow.dsc {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid #fff;
        }

        .person_inactive {
            background: #777;
            color: #fff;
        }

        .person_inactive:hover {
            /*background: #777;*/
            color: #FF0000;
        }

        .avatar {
            height: 50px;
            width: 50px;
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            position: relative;
            background-position: 50% 50%;
            background-position-x: 50%;
            background-position-y: 50%;
            background-size: cover;
            overflow: hidden;
        }

        .event-del {
            color: #fff;
        }

        .event-del:hover {
            color: red !important;
        }

        /* Inceased Select2 Dates */
        .select2-selection {
            height: 60px;
            min-height: 60px;
            font-size: 20px;
            padding-top: 5px;
        }

        .legendColour {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 0px 10px 0px 20px;
            padding-left: 4px;
        }

    </style>

    <div id="vue-app">
        {{-- Chats / Stats --}}
        <div v-if="xx.date" class="row" style="min-height: 300px;">
            {{-- Genders Chart --}}
            <div class="col-md-3">
                <div class="m-portlet  m-portlet--full-height ">
                    <div class="m-portlet__body">
                        <div class="row" style="padding-bottom: 10px">
                            <div class="col-12">
                                <h4>Genders</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="gender_chart" style="height: 150px;"></div>
                                <div id="gender_legend" align="center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Grades Chart --}}
            <div class="col-md-4">
                <div class="m-portlet  m-portlet--full-height ">
                    <div class="m-portlet__body">
                        <div class="row" style="padding-bottom: 10px">
                            <div class="col-12">
                                <h4>Grades</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div id="grade_chart" style="height: 170px;"></div>
                            </div>
                            <div class="col-6">
                                <div id="grade_legend" align=""></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Schools Chart --}}
            <div class="col-md-5">
                <div class="m-portlet  m-portlet--full-height ">
                    <div class="m-portlet__body">
                        <div class="row" style="padding-bottom: 10px">
                            <div class="col-12">
                                <h4>Schools</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="school_chart" style="height: 170px;"></div>
                            </div>
                            {{--}}
                            <div class="col-6">
                                <div id="school_legend" align=""></div>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="m-portlet">
                    <div class="m-portlet__body">
                        {{-- Header + Stats --}}
                        <div class="row" style="padding-bottom: 10px">
                            <div class="col-9"><h4>Attendance</h4>
                                Total: @{{ xx.count_all }} &nbsp; | &nbsp; Students: @{{ xx.count_students }} &nbsp; | &nbsp; Volunteers: @{{ xx.count_volunteers }}</div>
                            <div v-if="xx.estatus != 0" class="col-3">
                                <button v-if="xx.instance.id != 0" class="btn btn-sm m-btn--pill pull-right event-del" style="margin-left: 10px; background: #333333; color: #FFFFFF" id="but_delete_event"><i class="fa fa-trash-alt"></i></button>
                                <a href="#" class="btn btn-sm m-btn--pill btn-brand pull-right" data-toggle="modal" data-target="#modal_create_instance">Add Past Event</a>
                            </div>
                        </div>

                        {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}
                        <input v-model="xx.eid" type="hidden" value="{{ $event->id }}">
                        <input v-model="xx.estatus" type="hidden" value="{{ $event->status }}">
                        <input v-model="xx.instance.id" type="hidden" value="{{ ($instance) ? $instance->id : 0 }}">
                        <input v-model="xx.date" type="hidden" value="{{ $date }}">
                        <input v-model="xx.count_male" type="hidden" value="">
                        <input v-model="xx.count_female" type="hidden" value="">

                        <div v-if="xx.date == 0">
                            <br>No {{ $event->name }} events have occured yet. Please add one.
                            <a href="#" class="btn btn-sm m-btn--pill btn-outline-brand" data-toggle="modal" data-target="#modal_attendance">Add Past Event</a>
                            &nbsp; or &nbsp; <a href="/checkin/{{ $event->id }}" class="btn btn-sm m-btn--pill btn-outline-brand">Check-in one for today</a>
                        </div>
                        <div v-if="xx.date">
                            <div class="row" style="padding: 20px 0px 10px 0px">
                                <div class="col-8">
                                    <h1 v-if="!xx.edit_name">@{{ xx.instance.name }} <i v-if="xx.estatus != 0" v-on:click="toggleEditName" class="fa fa-edit" style="color: #9eacb4; font-size: 13px; padding: 7px 20px ; vertical-align: top; cursor: pointer"></i></h1>
                                    {{-- Edit Instance Name --}}
                                    <div v-if="xx.edit_name" class="col-8" style="padding-left: 5px">
                                        <div class="input-group">
                                            <input v-model="xx.instance.name" type="text" class="form-control form-control-lg m-input" style="font-size: 30px">
                                            <div class="input-group-append">
                                                <span v-on:click="saveName" class="input-group-text" style="color: #FFFFFF; background: #34bfa3; padding: 0px 20px; cursor: pointer">Save</span>
                                            </div>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Show/Hide Option --}}
                                <div class="col-lg-1" style="margin-bottom: 10px">
                                </div>
                                <div class="col-lg-3" style="margin-bottom: 10px">
                                    {{-- Show/Hide Photos --}}
                                    <div>
                                        <span v-if="!xx.show_photos" v-on:click="togglePhotos" style="cursor: pointer;"><i class="far fa-square" style="padding-right: 10px"></i> Show photos</span>
                                        <span v-if="xx.show_photos" v-on:click="togglePhotos" style="cursor: pointer;"><i class="fa fa-check-square" style="padding-right: 10px"></i> Show photos</span>
                                    </div>
                                    {{-- Show/Hide Archived --}}
                                    <div>
                                        <span v-if="!xx.show_inactive" v-on:click="toggleDeleted" style="cursor: pointer;"><i class="far fa-square" style="padding-right: 10px"></i> Show inactive people</span>
                                        <span v-if="xx.show_inactive" v-on:click="toggleDeleted" style="cursor: pointer;"><i class="fa fa-check-square" style="padding-right: 10px"></i> Show inactive people</span>
                                    </div>
                                    {{-- Show/Hide Checked-in --}}
                                    <div>
                                        <span v-if="!xx.show_checked" v-on:click="toggleChecked" style="cursor: pointer;"><i class="far fa-square" style="padding-right: 10px"></i> Show only checked-in people</span>
                                        <span v-if="xx.show_checked" v-on:click="toggleChecked" style="cursor: pointer;"><i class="fa fa-check-square" style="padding-right: 10px"></i> Show only checked-in people</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="padding: 5px;">
                                {{-- Date --}}
                                <div class="col-lg-5 col-md-6" style="margin-bottom: 10px">
                                    {!! Form::select('date', $dates, $date, ['class' => 'form-control form-control-lg m-input select2', 'style' => 'font-size:25px; height: 50px !important; line-height: 32px !important; min-height: 50px;', 'id' => 'date']) !!}
                                </div>
                                <div class="col-lg-7 col-md-6" style="margin-bottom: 10px">
                                    <div class="input-group">
                                        <input v-model="xx.searchQuery" type="search" class="form-control form-control-lg m-input" placeholder="Search for something" name="query">
                                        <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                                    </div>
                                </div>
                            </div>
                            <br>

                            {{-- Attendance Grid --}}
                            <div class="row" style="padding: 5px">
                                <div class="col-12">
                                    <h4 v-if="xx.estatus == 0" class="m--font-warning">Check-ins currently disabled due to event is INACTIVE</h4>
                                    <attendance-grid :data="xx.people" :columns="xx.columns" :filter-key="xx.searchQuery"></attendance-grid>
                                </div>
                            </div>

                        </div>

                        <!-- loading Spinner -->
                        <div v-show="xx.searching" style="background-color: #FFF; padding: 20px;">
                            <div class="loadSpinnerOverlay">
                                <div class="loadSpinner"><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i> Loading...</div>
                            </div>
                        </div>

                        <!--<pre>@{{ $data }}</pre>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('event/_create_instance')

    {{-- Attendence template --}}
    <script type="text/x-template" id="attendance-template">
        <table class="table table-hover table-bordered  m-table--head-bg-brand m-table no-footer dtr-inline">
            <thead>
            <tr>
                <th width="8%">Check-In</th>
                <th v-on:click="sortBy('name')" :class="{ active: sortKey == 'name' }">Name<span class="arrow" :class="sortOrders['name'] > 0 ? 'asc' : 'dsc'"></span></th>
                <th v-on:click="sortBy('type')" :class="{ active: sortKey == 'type' }">Type<span class="arrow" :class="sortOrders['type'] > 0 ? 'asc' : 'dsc'"></span></th>
                <th width="10%" v-on:click="sortBy('grade')" :class="{ active: sortKey == 'grade' }">Grade<span class="arrow" :class="sortOrders['grade'] > 0 ? 'asc' : 'dsc'"></span></th>
                <th v-on:click="sortBy('school')" :class="{ active: sortKey == 'school' }">School<span class="arrow" :class="sortOrders['school'] > 0 ? 'asc' : 'dsc'"></span></th>
            </tr>
            </thead>
            <tbody>
            <template v-for="person in filteredData">
                <tr v-if="show(person)" :class="{ person_inactive: !person.status }">
                    {{-- Active People --}}
                    <td v-if="person.status && xx.estatus == 1" v-on:click="cellSelect(person)" class="text-center" style="cursor: pointer;">
                        {{-- Icons --}}
                        <div v-if="!xx.show_photos">
                            <span v-if="person.in"><i class="fa fa-check-square fa-2x" style="color: #45ccb1"></i></span>
                            <span v-if="!person.in"><i class="far fa-square fa-2x" style="color: #c4c5d6"></i></span>
                        </div>
                        {{-- Photos --}}
                        <div v-if="xx.show_photos">
                            <div v-if="person.in && xx.show_photos" class="avatar" :style="backgroundImage(person)">
                                <img src="/img/check-32.png" height="32px" style="margin: 10px">
                            </div>
                            <div v-if="!person.in && xx.show_photos" class="avatar" :style="backgroundImage(person)"></div>
                        </div>
                    </td>

                    {{-- Deleted People --}}
                    <td v-if="!person.status || xx.estatus == 0" class="text-center">
                        {{-- Icons --}}
                        <div v-if="!xx.show_photos">
                            <span v-if="person.in && !xx.show_photos"><i class="fa fa-check-square fa-2x" style="color: #45ccb1; cursor: default"></i></span>
                            <span v-if="!person.in && !xx.show_photos"><i class="far fa-square fa-2x" style="color: #c4c5d6; cursor: default"></i></span>
                        </div>
                        {{-- Photos --}}
                        <div v-if="xx.show_photos">
                            <div v-if="person.in && xx.show_photos" class="avatar" :style="backgroundImage(person)">
                                <img src="/img/check-32.png" height="32px" style="margin: 10px">
                            </div>
                            <div v-if="!person.in && xx.show_photos" class="avatar" :style="backgroundImage(person)"></div>
                        </div>
                    </td>

                    {{-- Remaining Columns --}}
                    <td style="padding: 10px">
                        @{{person.name}}
                        <span v-if="person.new == 1" class="m-badge m-badge--warning m-badge--wide">NEW</span>
                        <span v-if="!person.status" style="padding-left: 20px">** INACTIVE **</span>
                    </td>
                    <td style="padding: 10px">@{{person.type}}</td>
                    <td style="padding: 10px">@{{person.grade}}</td>
                    <td style="padding: 10px">@{{person.school}}</td>
                </tr>
            </template>
            </tbody>
        </table>
    </script>
@stop


@section('vendor-scripts')
@stop

@section('page-styles')
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="/js/event-shared-functions.js" type="text/javascript"></script>
<script src="/js/vue.min.js"></script>
<script src="/js/vue-checkin-functions.js"></script>
<script type="text/javascript">
    // Form errors - show modal
    if ($('#formerrors').val() == 'event')
        $('#modal_create_instance').modal('show');

    var xx = {
        eid: "{{ $event->id }}", estatus: "{{ $event->status }}", date: "{{ $date }}", date_format: '',
        instance: {
            id: "{{ ($instance) ? $instance->id : 0 }}",
            name: "{{ ($instance) ? $instance->name : '' }}",
        },
        count_all: 0, count_students: 0, count_volunteers: 0, count_male: 0, count_female: 0,
        count_grades: {1: 0, 2: 0, 3: 0, 4: 0, 5: 0, 6: 0, 7: 0, 8: 0, 9: 0, 10: 0, 11: 0, 12: 0}, count_schools: {},
        searchQuery: "{!! app('request')->input('query') !!}",
        edit_name: false, show_photos: false, show_checked: true, show_inactive: true,
        people: [], columns: ['in', 'name', 'type', 'grade', 'school'],
    };

    $(document).ready(function () {
        $('#date').select2({width: '100%'});
        $("#date").change(function () {
            window.location.href = "/event/" + xx.eid + '/attendance/' + $('#date').val();
        });

        // Create Past Check-in Date
        $("#pastdate").datepicker({
            todayHighlight: !0,
            orientation: "bottom left",
            autoclose: true,
            clearBtn: true,
            endDate: '-1d',
            format: "{{ session('df-datepicker') }}",
        });

        // Hide Past Check-in Save button until date selected
        $("#pastdate").change(function () {
            $("#but_create_event").hide();
            if ($("#pastdate").val() != '')
                $("#but_create_event").show();
        });

        // Delete Event Instance
        $("#but_delete_event").click(function () {
            swal({
                title: "Are you sure?",
                html: "You will not be able to recover this event<br><b>" + xx.instance.name + " - " + xx.date_format + "</b><br>and all check-ins will be lost!",
                cancelButtonText: "Cancel!",
                confirmButtonText: "Yes, delete it!",
                confirmButtonClass: "btn btn-danger",
                showCancelButton: true,
                reverseButtons: true,
                allowOutsideClick: true
            }).then(function (result) {
                if (result.value) {
                    window.location.href = "/event/instance/" + xx.instance.id + '/del';
                }
            });
        });
    });


    // register the attendance component
    Vue.component('attendance-grid', {
        template: '#attendance-template',
        props: {
            data: Array,
            columns: Array,
            filterKey: String
        },
        data: function () {
            var sortOrders = {}
            this.columns.forEach(function (key) {
                sortOrders[key] = 1
            })
            return {
                sortKey: '',
                sortOrders: sortOrders,
                background: '',
                xx: xx
            }
        },
        computed: {
            filteredData: function () {
                var sortKey = this.sortKey
                var filterKey = this.filterKey && this.filterKey.toLowerCase()
                var order = this.sortOrders[sortKey] || 1
                var data = this.data
                if (filterKey) {
                    data = data.filter(function (row) {
                        return Object.keys(row).some(function (key) {
                            return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                        })
                    })
                }
                if (sortKey) {
                    if (sortKey == 'grade') {
                        // Sort Numeric
                        data = data.slice().sort(function (a, b) {
                            a = a[sortKey]
                            b = b[sortKey]
                            return (a - b) * order
                        })
                    } else {
                        // Sort AlphaNumeric
                        data = data.slice().sort(function (a, b) {
                            a = a[sortKey]
                            b = b[sortKey]
                            return (a === b ? 0 : a > b ? 1 : -1) * order
                        })
                    }
                }
                return data
            },
        },
        methods: {
            sortBy: function (key) {
                this.sortKey = key
                this.sortOrders[key] = this.sortOrders[key] * -1
            },
            show: function (person) {
                // Hide Parents
                if (person.type == 'Parent')
                    return false;

                // Hide Non Checked-in unless Checked-in checked
                if (this.xx.show_checked && !person.in)
                    return false;
                // Hide Deleted People unless Deleted checked
                if (!this.xx.show_inactive && !person.status)
                    return false;
                // Hide Deleted People unless Deleted checked + Person Checked-in checked
                if (this.xx.show_inactive && !person.status && !person.in)
                    return false;

                return true;
            },
            url: function () {
                return "url";
            },
            backgroundImage: function (person) {
                var str;
                if (person.in)
                    str = "background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,.5)), url('" + person.photo + "')";
                else
                    str = "background-image: url('" + person.photo + "')";

                // Set cursor to defaul tfor inactive people to ensure user doesn't think they can click + update
                if (!person.status)
                    str = str + '; cursor:default';

                return str;

            },
            cellSelect: function (person) {
                if (person.in)
                    delAttendanceDB(person).then(function (result) {
                        if (result)
                            this.updateAttendance();
                    }.bind(this));
                else
                    addAttendanceDB(person, 'manual').then(function (result) {
                        if (result)
                            this.updateAttendance();
                    }.bind(this));
            },
            updateAttendance: function () {
                countAttendance();
            }
        }
    })


    var vue_app = new Vue({
        el: '#vue-app',
        data: {xx: xx,},
        created: function () {
            this.getPeople();
        },
        methods: {
            getPeople: function () {
                if (this.xx.instance.id != 0) {
                    this.xx.searching = true;
                    $.getJSON('/data/event/people/' + this.xx.instance.id, function (data) {
                        this.xx.people = data;
                        this.xx.searching = false;
                        this.countAttendance();
                        this.xx.date_format = moment(this.xx.date).format("{{ session('df-moment') }}");
                    }.bind(this));
                }
                // Default - Show Only Checked-in for Inactive Events
                if (this.xx.estatus == 0)
                    this.xx.show_checked = true;
            },
            saveName: function () {
                updateInstancetDB(this.xx.instance).then(function (result) {
                    if (result)
                        this.xx.edit_name = !this.xx.edit_name;
                }.bind(this));
            },
            toggleEditName: function () {
                this.xx.edit_name = !this.xx.edit_name;
            },
            toggleChecked: function () {
                this.xx.show_checked = !this.xx.show_checked;
            },
            toggleDeleted: function () {
                this.xx.show_inactive = !this.xx.show_inactive;
            },
            togglePhotos: function () {
                this.xx.show_photos = !this.xx.show_photos;
            },
            countAttendance: function () {
                countAttendance();
            }
        },
    });

    function countAttendance() {
        this.xx.count_all = 0;
        this.xx.count_students = 0;
        this.xx.count_volunteers = 0;
        this.xx.count_male = 0;
        this.xx.count_female = 0;
        this.xx.count_grades = {'1': 0, 2: 0, 3: 0, 4: 0, 5: 0, 6: 0, 7: 0, "8": 0, "9": 0, '10': 0, 11: 0, 12: 0, Unknown: 0};
        this.xx.count_schools = {Other: 0};
        this.xx.people.forEach(function (person) {
            if (person.in) {
                this.xx.count_all++;
                if (person.type == 'Student' || person.type == 'Student/Volunteer') {
                    this.xx.count_students++;
                    // Gender
                    if (person.gender == 'Male')
                        this.xx.count_male++;
                    if (person.gender == 'Female')
                        this.xx.count_female++;
                    // Grade
                    if (person.grade)
                        this.xx.count_grades[person.grade]++;
                    else
                        this.xx.count_grades['Unknown']++;

                    if (person.school) {
                        if (person.school in this.xx.count_schools)
                            this.xx.count_schools[person.school]++;
                        else
                            this.xx.count_schools[person.school] = 1;
                    } else
                        this.xx.count_schools['Other']++;

                    // School
                }
                if (person.type == 'Volunteer' || person.type == 'Student/Volunteer' || person.type == 'Parent/Volunteer')
                    this.xx.count_volunteers++;
            }
        });
        drawGenderChart();
        drawGradeChart();
        drawSchoolChart();
    }


    // Update Event Instance in Database Attendance and return a 'promise'
    function updateInstancetDB(instance) {
        return new Promise(function (resolve, reject) {
            instance._method = 'patch';
            $.ajax({
                url: '/event/instance/' + instance.id,
                type: 'POST',
                data: instance,
                success: function (result) {
                    delete instance._method;
                    console.log('DB updated instance:[' + instance.id + '] ' + instance.name);
                    resolve(instance);
                },
                error: function (result) {
                    alert("failed updating event instance " + instance.name + '. Please refresh the page to resync event');
                    console.log('DB updated event instance FAILED:[' + instance.id + '] ' + instance.name);
                    reject(false);
                }
            });
        });
    }

    function drawGenderChart() {
        $("#gender_chart").empty();
        genderChart = new Morris.Donut({
            element: 'gender_chart',
            resize: true,
            data: [
                {label: "Male", value: Math.round(this.xx.count_male / this.xx.count_students * 100)},
                {label: "Female", value: Math.round(this.xx.count_female / this.xx.count_students * 100)},
            ],
            //backgroundColor: '#ddd',
            labelColor: '#000',
            colors: ['#73BEE0', '#E072D5',],
            formatter: function (x) {
                return x + "%"
            }
        });

        var label_male = "<span class='legendColour' style='background:#73BEE0'></span><span>" + this.xx.count_male + " Male</span>";
        var label_female = "<span class='legendColour' style='background:#E072D5'></span><span>" + this.xx.count_female + " Female</span>";
        $('#gender_legend').html(label_male + label_female);
    }

    function drawGradeChart() {
        $("#grade_chart").empty();
        gradeChart = new Morris.Donut({
            element: 'grade_chart',
            resize: true,
            data: [
                {label: "Grade 6", value: Math.round(this.xx.count_grades[6] / this.xx.count_students * 100)},
                {label: "Grade 7", value: Math.round(this.xx.count_grades[7] / this.xx.count_students * 100)},
                {label: "Grade 8", value: Math.round(this.xx.count_grades[8] / this.xx.count_students * 100)},
                {label: "Grade 9", value: Math.round(this.xx.count_grades[9] / this.xx.count_students * 100)},
                {label: "Grade 10", value: Math.round(this.xx.count_grades[10] / this.xx.count_students * 100)},
                {label: "Grade 11", value: Math.round(this.xx.count_grades[11] / this.xx.count_students * 100)},
                {label: "Grade 12", value: Math.round(this.xx.count_grades[12] / this.xx.count_students * 100)},
            ],
            //backgroundColor: '#ddd',
            labelColor: '#000',
            colors: ['#73BEE0', '#739EE1', '#717CE0', '#8B72E0', '#AC72E0', '#CF72E0', '#E072D5'],
            formatter: function (x) {
                return x + "%"
            }
        });

        $('#grade_legend').empty();
        var grade = 6;
        gradeChart.options.colors.forEach(function (color, i) {
            var legendLabel = $('<span>Grade ' + grade++ + '</span><br>');
            var legendItem = $('<span class="legendColour"></span>').css('background-color', color);
            $('#grade_legend').append(legendItem);
            $('#grade_legend').append(legendLabel);
        })
    }


    function drawSchoolChart() {
        $("#school_chart").empty();

        // Set data
        var data = [];
        var labels = [];
        var colours = ['#73BEE0', '#739EE1', '#717CE0', '#8B72E0', '#AC72E0', '#CF72E0', '#E072D5', '#E072B4', '#E17294', '#E07473', '#E09772', '#DFB873'];

        Object.keys(this.xx.count_schools).forEach(function (key, index) {
            data.push({y: key, a: this.xx.count_schools[key]});
            labels.push(key);
        });

        schoolChart = new Morris.Bar({
            element: 'school_chart',
            barGap: 3,         // sets the space between bars in a single bar group. Default 3:
            barSizeRatio: 0.75, // proportion of the width of the entire graph given to bars. Default 0.75
            stacked: true,
            resize: true,
            data: data,
            xkey: 'y',
            ykeys: ['a'],
            //xLabelAngle: '70',
            axes: 'y',
            barColors: colours,
            labels: labels,
        });

        $('#school_legend').empty();
        schoolChart.options.labels.forEach(function (label, i) {
            var legendLabel = $('<span>' + label + '</span><br>');
            var legendItem = $('<span class="legendColour"></span>').css('background-color', schoolChart.options.barColors[i]);
            $('#school_legend').append(legendItem);
            $('#school_legend').append(legendLabel);
        })
    }
</script>
@stop
