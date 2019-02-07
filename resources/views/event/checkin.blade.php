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
    <div class="bg" id="demo">
        <div class="row text-white" style="height:70px; background: rgb(61, 59, 86)">
            <div class="col text-center">
                <h1 style="padding-top: 10px">{{ $event->name }}</h1>
            </div>
        </div>
        {{-- Check-in Search  --}}
        <div v-if="!xx.register">
            <div class="row" style="padding: 30px 80px">
                <div class="col-12">
                    <input v-model="xx.instance_id" type="hidden" value="{{ $instance->id }}">
                    <div class="input-group">
                        <input v-model="searchQuery" type="search" class="form-control form-control-lg m-input" placeholder="Search for someone" name="query">
                        <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                        <button v-on:click="register()" class="btn btn-accent btn-lg" style="margin-left: 20px; float: right;">Register</button>
                    </div>
                </div>
            </div>

            {{-- People Grid --}}
            <div class="row" style="padding: 30px">
                <div class="col-12">
                    <demo-grid :data="gridData" :filter-key="searchQuery"></demo-grid>
                </div>
            </div>
        </div>

        {{-- Register Form --}}
        <div v-if="xx.register" class="row" style="padding: 30px">
            <div class="col-lg-6 col-md-9 mx-auto">
                <!--begin::Portlet-->
                <div class="m-portlet m-portlet--tab">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text" style="text-align: center">Registration</h3>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
                            {{--First Name --}}
                            <div class="row">
                                <div class="col">
                                    <div class="form-group m-form__group {!! fieldHasError('firstname', $errors) !!}">
                                        {!! Form::text('firstname', null, ['class' => 'form-control form-control-lg', 'placeholder' => 'First Name']) !!}
                                        {!! fieldErrorMessage('firstname', $errors) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="hidden-sm-down" style="margin: 20px"></div>
                            {{--Last Name --}}
                            <div class="row">
                                <div class="col">
                                    <div class="form-group m-form__group {!! fieldHasError('lastname', $errors) !!}">
                                        {!! Form::text('lastname', null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Last Name']) !!}
                                        {!! fieldErrorMessage('lastname', $errors) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions text-center">
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                <button type="reset" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->
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
                        <img src="/img/checkin-tick2.png" style="margin: 10px">
                        <div class="people-label">@{{ person.name }}</div>
                    </div>
                </template>
            </div>

        </div>
    </script>


    <!---
    <script type="text/x-template" id="grid-template">
       <div class="row text-center" style="text-align:center; position:relative;">
           <span v-for="entry in filteredData" style="margin: 10px; background-color: #f6f6f6;">
               <div><img src="/img/d90.jpg"></div>
               <div style="height: 50px; width: 90px; padding: 5px;">@{{ entry.name }}</div>
           </span>
       </div>
    </script> -->
    <!--
    <script type="text/x-template" id="grid-template">
        <table>
            <thead>
            <tr>
                <th v-for="key in columns" @click="sortBy(key)" :class="{ active: sortKey == key }"> @{{ key | capitalize }}
            <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'"></span>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="entry in filteredData">
                <td v-for="key in columns">@{{entry[key]}}</td>
            </tr>
            </tbody>
        </table>
    </script>-->

@stop


@section('vendor-scripts')
    <script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
@stop

@section('page-styles')
    <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.2/dist/vue.js"></script>
<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    var xx = {
        params: {date: '', _token: $('meta[name=token]').attr('value')},
        people: [], register: false, instance_id: {{ $instance->id }},
    };
    // register the grid component
    Vue.component('demo-grid', {
        template: '#grid-template',
        props: {
            data: Array,
            filterKey: String
        },
        data: function () {
            return {xx: xx,}
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
            //sortBy: function (key) {
            //    this.sortKey = key
            //    this.sortOrders[key] = this.sortOrders[key] * -1
            //},
            cellSelect: function (person) {
                if (person.in)
                    delAttendanceDB(person);
                else
                    addAttendanceDB(person);
            },
            cellClass: function (person) {
                var str = 'people-cell';

                if (person.in != null)
                    str = str + ' people-in';
                return str;
            },
        }
    })

    // bootstrap the demo
    var demo = new Vue({
        el: '#demo',
        data: {
            searchQuery: "{!! app('request')->input('query') !!}",
            gridData: [],
            gridData2: [
                {name: 'Chuck Norris', power: Infinity},
                {name: 'Bruce Lee', power: 9000},
                {name: 'Jackie Chan Long', power: 7000},
                {name: 'Jet Li', power: 8000},
                {name: '2 Chuck Norris', power: Infinity},
                {name: '2 Bruce Lee', power: 9000},
                {name: '2 Jackie Chan Eaxtra Long', power: 7000},
                {name: '2 Jet Li', power: 8000},
                {name: '3 Chuck Norris', power: Infinity},
                {name: '3 Bruce Lee', power: 9000},
                {name: '3 Jackie Chan', power: 7000},
                {name: '3 Jet Li', power: 8000},
                {name: '4 Chuck Norris', power: Infinity},
                {name: '4 Bruce Lee', power: 9000},
                {name: '4 Jackie Chan', power: 7000},
                {name: '4 Jet Li', power: 8000}
            ],
            xx: xx,
        },
        created: function () {
            this.getPeople();
        },
        methods: {
            getPeople: function () {
                this.xx.searching = true;
                $.getJSON('/data/checkin/people/' + this.xx.instance_id, function (data) {
                    this.gridData = data;
                    this.xx.people = data;
                    this.xx.searching = false;
                    //console.log(data);
                }.bind(this));
            },
            register: function () {
                this.xx.register = 'student';
            }
        },
    });


    // Add person to Database Attendance and return a 'promise'
    function addAttendanceDB(person) {
        //alert('add:' + person.name);
        person.in = moment().format('YYYY-MM-DD HH:mm:ss');
        return new Promise(function (resolve, reject) {
            delete person._method; // ensure _method not set else throws a Laravel MethodNotAllowedHttpException error. Requires a POST request to store
            $.ajax({
                url: '/attendance',
                type: 'POST',
                data: person,
                success: function (result) {
                    console.log('DB added person:[' + result.eid + '] ' + person.name);
                    resolve(result);
                },
                error: function (result) {
                    alert("Failed check-in for " + person.name + '. Please refresh the page to resync attendance');
                    console.log('DB added person FAILED:[' + result.eid + '] ' + person.name);
                    reject(false);
                }
            });
        });
    }

    // Delete person from Database Attendance and return a 'promise'
    function delAttendanceDB(person) {
        //alert('del:' + person.name);
        //console.log('Deleting person:[' + person.eid + '] ' + person.name);
        return new Promise(function (resolve, reject) {
            person._method = 'delete';
            $.ajax({
                url: '/attendance/' + person.eid,
                type: 'POST',
                data: person,
                success: function (result) {
                    delete person._method;
                    person.in = null;
                    console.log('DB deleted person:[' + person.eid + '] ' + person.name);
                    resolve(person);
                },
                error: function (result) {
                    alert("Failed check-out " + person.name + '. Please refresh the page to resync attendance');
                    console.log('DB deleted person FAILED:[' + person.eid + '] ' + person.name);
                    reject(false);
                }
            });

            /*
             fetch('/attendance', {
             method: 'POST',
             body: JSON.stringify({title: "a new todo"})
             }).then((res) => res.json())
             .then((data) => console.log(data))
             .catch((err) => console.error(err))*/

        });
    }

    // Update person in Database Attendance and return a 'promise'
    function updateAttendanceDB(person) {
        return new Promise(function (resolve, reject) {
            person._method = 'patch';
            $.ajax({
                url: '/attendance/' + person.eid,
                type: 'POST',
                data: person,
                success: function (result) {
                    delete person._method;
                    console.log('DB updated person:[' + person.eid + '] ' + person.name);
                    resolve(task);
                },
                error: function (result) {
                    alert("failed updating attendance " + person.name + '. Please refresh the page to resync attendance');
                    console.log('DB updated person FAILED:[' + person.eid + '] ' + person.name);
                    reject(false);
                }
            });
        });
    }

</script>
@stop