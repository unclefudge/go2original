@extends('layouts/checkin')

@section('content')
    <style>
        body, html {
            height: 100% !important;
        }

        .people-container {
            width: 95%;
            margin: 10px auto;
            position: relative;
            text-align: center;
        }

        .people-cell {
             font-size: 11px;
             height: 100px;
             width: 110px;
             display: inline-block;
             margin: 10px;
             cursor: pointer;

             position: relative;
             background-color: #f6f6f6;

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
            line-height: 25px;
            vertical-align: middle;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 50px;
            background-color: red;
            color: white;
        }

        .bg {
            /* The image used */
            /*background-image: url("/img/morningrise.jpg");*/

            /* Set a specific height */
            height: 100%;

            /* Create the parallax scrolling effect */
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }


    </style>
    <div class="bg" id="vue-app">
        <div class="row text-white" style="height:70px; background: rgb(61, 59, 86)">
            <div class="col text-center">
                <img src="/img/logo-med.png" style="float: left; padding:5px 0px 5px 20px">
                <h1 style="padding-top: 10px">{{ $event->name }} <span class="pull-right" style="font-size: 14px; padding-right: 20px">{!! \Carbon\Carbon::now()->timezone(session('tz'))->format(session('df'). " g:i a") !!}</span> </h1>
            </div>
        </div>

        {{-- Check-in Search  --}}
        <div class="row" style="padding: 30px 80px">
            <div class="col-12">
                <input v-model="xx.instance_id" type="hidden" value="{{ $instance->id }}">
                <div class="input-group">
                    <input v-model="xx.searchQuery" type="search" class="form-control form-control-lg m-input" placeholder="Search for someone" name="query">
                    <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                    <a href="/checkin/{{ $instance->id }}/register/student" class="btn btn-accent btn-lg" style="margin-left: 20px; float: right;">Register</a>
                </div>
            </div>
        </div>

        {{-- People Grid --}}
        <div class="row" style="padding: 30px">
            <div class="col-12">
                <checkin-grid :data="xx.people" :filter-key="xx.searchQuery"></checkin-grid>
            </div>
        </div>

        <div class="footer">
            <p><a href="/event" class="btn btn-secondary">Back to Events</a></p>
        </div>

        <!-- loading Spinner -->
        <div v-show="xx.searching" style="background-color: #FFF; padding: 20px;">
            <div class="loadSpinnerOverlay">
                <div class="loadSpinner"><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i> Loading...</div>
            </div>
        </div>

        <!--<pre>@{{ $data }}</pre>-->
    </div>


    <!-- component template -->
    <script type="text/x-template" id="grid-template">
        <div class="people-container">
            <div v-if="filteredData.length == 0 && !this.xx.searching">
                <h4>Couldn't find the person you were looking for.</h4><br>
            </div>
            <div v-else>
                <template v-for="person in filteredData">
                    <div v-if="!person.in" class="people-cell" v-on:click="cellSelect(person)" style="background-image: url('/img/d90.jpg');">
                        <div class="people-label">@{{ person.name }}</div>
                    </div>
                    <div v-else="person.in" class="people-cell" v-on:click="cellSelect(person)" style="background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,.5)), url('/img/d90.jpg');">
                        <img src="/img/check-64.png" style="margin: 10px">
                        <div class="people-label">@{{ person.name }}</div>
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
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.2/dist/vue.js"></script>
<script src="/js/vue-checkin-functions.js"></script>
<script type="text/javascript">

    var xx = {
        params: {date: '', _token: $('meta[name=token]').attr('value')},
        people: [], instance_id: {{ $instance->id }}, searchQuery: "{!! app('request')->input('query') !!}",
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
                if (person.in)
                    delAttendanceDB(person);
                else
                    addAttendanceDB(person, 'checkin');
            },
            cellClass: function (person) {
                var str = 'people-cell';

                if (person.in != null)
                    str = str + ' people-in';
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
                }.bind(this));
            },
        },
    });
</script>
@stop