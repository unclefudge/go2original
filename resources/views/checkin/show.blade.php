@extends('layout/checkin')

@section('content')
    <style>
        body, html {
            @if ($event->background)
               background-image: linear-gradient(rgba(0, 0, 0, .2), rgba(0, 0, 0, 0.2)), url("{!! $event->background_path !!}") !important;
            @endif

            height: 100%; /* set height */

            /* Create the parallax scrolling effect */
            background-attachment: fixed !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
        }

        .search-row {
            padding: 30px 80px;
        }

        .search-row:hover {
           background: inherit;
        }

        .btn-register {
            margin-left: 20px;
            float: right;
        }

        .people-grid {
            padding: 30px;
        }

        .people-container {
            width: 95%;
            margin: 10px auto;
            position: relative;
            text-align: center;
        }

        .people-cell {
            font-size: 11px;
            /*height: 100px;
            width: 110px;*/
            height: 80px;
            width: 90px;
            display: inline-block;
            margin: 5px;
            cursor: pointer;

            position: relative;
            /*background-color: #f6f6f6;*/
            background-color: #eee;

            background-position: 50% 50%;
            background-position-x: 50%;
            background-position-y: 50%;
            background-size: cover;
            overflow: hidden;
        }

        .people-in {
            opacity: 0.5;
            background-image: url('/img/avatar-user');
        }

        .people-label {
            color: #fff;
            background-color: #333;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 25px;
            padding: 0px 3px;
            line-height: 25px;
            vertical-align: middle;
        }

        .leader-label {
            /* background-color: #B2E2F0;*/ /* #2998B3;*/
            background-color: #222;
            color: #00c5dc;
        }

        .people-check {
            position: absolute;
            top: 5px;
            left: 20px;
            height: 50px;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 50px;
            background-color: white;
            color: black;
        }

        @media screen and (max-width: 720px) {
            /* 480px */
            .search-row {
                padding: 15px 20px;
            }

            .btn-register {
                margin-left: 10px;
            }

            .people-grid {
                padding: 10px;
            }

            .people-container {
                margin: 10px;
                width: 100%;
            }

            .people-cell {
                height: 58px;
                width: 65px;
            }

            .people-label {
                height: 20px;
                padding: 0px 3px;
                line-height: 20px;
                vertical-align: middle;
            }

            /* override metronic lg control */
            .form-control-lg {
                height: calc(2.95rem + 2px); /* 4.375rem + 2px, 1.25 rem 1.65rm, 1.25rem, .25rem */
                padding: .85rem 1.15rem;
                font-size: 1rem;
                line-height: 1.25;
                border-radius: .25rem;
            }

            .input-group-append {
                margin-left: -1px;
                height: calc(2.95rem + 2px); /* 4.375rem + 2px, 1.25 rem 1.65rm, 1.25rem, .25rem */
                font-size: 1rem !important;
                line-height: 1.25 !important;
            }

            /* override metronic lg button */
            .btn-lg {
                padding: .85rem 1.15rem;
                font-size: 1rem;
                line-height: 1.25;
            }
        }
    </style>
    <body style="background: #F7F7F7" style="height: 100%">
    <div id="vue-app">
        {{-- Header --}}
        <div class="row" style="height:70px; background-image: url(/img/head-purple.jpg); border-bottom: 1px solid rgba(255, 255, 255, 0.1)">
            <div class="col text-center">
                <a href="/checkin"><img src="/img/logo-med.png" style="float: left; padding:5px 0px 5px 20px"></a>
                <h1 v-if="!xx.edit_name" class="text-white">@{{ xx.instance.name }} <i v-if="xx.estatus != 0" v-on:click="toggleEditName" class="fa fa-edit" style="color: #9eacb4; font-size: 13px; padding: 7px 20px ; vertical-align: top; cursor: pointer"></i>
                    <span class="pull-right" style="font-size: 14px; padding-right: 20px">{!! \Carbon\Carbon::now()->timezone(session('tz'))->format(session('df'). " g:i a") !!}</span></h1>

                {{-- Edit Instance Name --}}
                <div v-if="xx.edit_name" style="padding-left: 5px">
                    <div class="input-group" style="width: 50%; margin-left: auto; margin-right: auto; padding-top: 5px">
                        <input v-model="xx.instance.name" type="text" class="form-control">
                        <div class="input-group-append">
                            <span v-on:click="saveName" class="input-group-text" style="color: #FFFFFF; background: #34bfa3; padding: 0px 20px; cursor: pointer">Save</span>
                        </div>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Check-in Search  --}}
        <div class="row search-row">
            <div class="col-12">
                <input v-model="xx.instance_id" type="hidden" value="{{ $instance->id }}">
                <div class="input-group">
                    <input v-model="xx.searchQuery" type="search" class="form-control form-control-lg" placeholder="Search for someone" name="query">
                    <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                    <a href="/checkin/{{ $instance->id }}/register/student" class="btn btn-accent btn-lg btn-register">Register</a>
                </div>
            </div>
        </div>

        {{-- People Grid --}}
        <div class="row people-grid">
            <div class="col-12">
                <checkin-grid :data="xx.people" :filter-key="xx.searchQuery"></checkin-grid>
            </div>
        </div>

        <br><br><br>

        <div class="kt-footer  kt-footer--extended  kt-grid__item footer" id="kt_footer" style="background-image: url('/massets/media/bg/bg-2.jpg');">
            <div class="kt-footer__bottom">
                <div class="kt-container">
                    <div class="kt-footer__wrapper">
                        <div class="kt-footer__logo">
                            <div class="kt-footer__copyright">
                                &copy; Go2Youth | All rights reserved
                            </div>
                        </div>
                        <div class="kt-footer__menu">
                            <a class="text-white" href="#">Students: @{{ xx.student_count }}</a>
                            <a class="text-white" href="#" target="_blank">Volunteers: @{{ xx.volunteer_count }}</a>
                            <a href="#" class="" data-toggle="kt-tooltip" title="Support Center" data-placement="left">
                                <i class="flaticon-questions-circular-button" style="font-size: 15px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- loading Spinner -->
        <div v-show="xx.searching" style="background-color: #FFF; padding: 20px;">
            <div class="loadSpinnerOverlay">
                <div class="loadSpinner"><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i> Loading...</div>
            </div>
        </div>
        <!--<pre style="background: #fff">@{{ $data }}</pre>
            -->
    </div>
    </body>


    <!-- component template -->
    <script type="text/x-template" id="grid-template">
        <div class="people-container">
            <div v-if="filteredData.length == 0 && !this.xx.searching">
                <span style="position: relative; background: #ffffff; padding: 20px; border-radius: 4px;">
                    <span style="font-size: 14px">Couldn't find the person you were looking for. </span>
                </span>
            </div>
            <div v-else>
                <template v-for="person in filteredData">
                    <div v-if="!person.in" class="people-cell" v-on:click="cellSelect(person)" :style="backgroundImage(person)">
                        <div :class="labelClass(person)">@{{ person.name }}</div>
                    </div>
                    <div v-else="person.in" class="people-cell" v-on:click="cellSelect(person)" :style="backgroundImage(person)">
                        <img class="people-check" src="/img/check-64.png" height="50px">
                        <div :class="labelClass(person)">@{{ person.name }}</div>
                    </div>
                </template>
            </div>

        </div>
    </script>
@stop


@section('vendor-scripts')
@stop

@section('page-styles')
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/js/vue.min.js"></script>
<script src="/js/vue-checkin-functions.js"></script>
<script type="text/javascript">

    var xx = {
        student_count: 0, volunteer_count: 0,
        people: [], instance_id: {{ $instance->id }}, searchQuery: "{!! app('request')->input('query') !!}",
        instance: {
            id: "{{ ($instance) ? $instance->id : 0 }}",
            name: "{{ ($instance) ? $instance->name : '' }}",
        },
        edit_name: false,
    };

    // register the grid component
    Vue.component('checkin-grid', {
        template: '#grid-template',
        props: {
            data: Array,
            filterKey: String
        },
        data: function () {
            return {xx: xx}
        },
        computed: {
            filteredData: function () {
                var filterKey = this.filterKey && this.filterKey.toLowerCase()
                var data = this.data
                if (filterKey) {
                    data = data.filter(function (row) {
                        return Object.keys(row).some(function (key) {
                            return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                        })
                    })
                }
                return data
            }
        },
        filters: {
            capitalize: function (str) {
                return str.charAt(0).toUpperCase() + str.slice(1)
            }
        },
        methods: {
            cellSelect: function (person) {
                if (person.in) {
                    delAttendanceDB(person).then(function (result) {
                        if (result)
                            count_attendance();
                    }.bind(this));
                } else {
                    addAttendanceDB(person, 'check-in').then(function (result) {
                        if (result)
                            count_attendance();
                    }.bind(this));
                }

            },
            labelClass: function (person) {
                var str = 'people-label';

                if (person.type == 'Volunteer' || person.type == 'Student/Volunteer' || person.type == 'Parent/Volunteer')
                    str = str + ' leader-label';
                return str;
            },
            backgroundImage: function (person) {
                var str;
                if (person.in)
                    str = "background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,.5)), url('" + person.photo + "')";
                else
                    str = "background-image: url('" + person.photo + "')";
                return str;

            },
        }
    })

    var vue_app = new Vue({
        el: '#vue-app',
        data: {xx: xx},
        created: function () {
            this.getPeople();
        },
        methods: {
            getPeople: function () {
                this.xx.searching = true;
                $.getJSON('/data/checkin/people/' + this.xx.instance_id, function (data) {
                    this.xx.people = data;
                    this.xx.searching = false;
                    //console.log(data);
                    count_attendance();
                }.bind(this));
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
        },
    });

    function count_attendance() {
        xx.student_count = 0;
        xx.volunteer_count = 0;
        $.each(xx.people, function (key, value) {
            if (value.in && (value.type == 'Student' || value.type == 'Student/Volunteer'))
                xx.student_count++;
            if (value.in && (value.type == 'Volunteer' || value.type == 'Parent/Volunteer'))
                xx.volunteer_count++;
        });
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
</script>
@stop