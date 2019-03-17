@extends('layouts/main')

@section('subheader')
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">Dashboard</h3>
            </div>
        </div>
    </div>
@stop

@section('content')
    {{--}}
    <div class="m-portlet ">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-md-12 col-lg-6 col-xl-3">

                    <!--begin::Total Profit-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">Total Profit</h4><br>
                            <span class="m-widget24__desc">All Customs Value</span>
                            <span class="m-widget24__stats m--font-brand">$18M</span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm">
                                <div class="progress-bar m--bg-brand" role="progressbar" style="width: 78%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="m-widget24__change">Change</span>
                            <span class="m-widget24__number">78%</span>
                        </div>
                    </div>
                    <!--end::Total Profit-->
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">

                    <!--begin::New Feedbacks-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">New Feedbacks</h4><br>
                            <span class="m-widget24__desc">Customer Review</span>
                            <span class="m-widget24__stats m--font-info">1349</span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm">
                                <div class="progress-bar m--bg-info" role="progressbar" style="width: 84%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="m-widget24__change">Change</span>
                            <span class="m-widget24__number">84%</span>
                        </div>
                    </div>
                    <!--end::New Feedbacks-->
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">

                    <!--begin::New Orders-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">New Orders</h4><br>
                            <span class="m-widget24__desc">Fresh Order Amount</span>
                            <span class="m-widget24__stats m--font-danger">567</span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm">
                                <div class="progress-bar m--bg-danger" role="progressbar" style="width: 69%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="m-widget24__change">Change</span>
                            <span class="m-widget24__number">69%</span>
                        </div>
                    </div>
                    <!--end::New Orders-->
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">

                    <!--begin::New Users-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">New Users</h4><br>
                            <span class="m-widget24__desc">Joined New User</span>
                            <span class="m-widget24__stats m--font-success">276</span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm">
                                <div class="progress-bar m--bg-success" role="progressbar" style="width: 90%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="m-widget24__change">Change</span>
                            <span class="m-widget24__number">90%</span>
                        </div>
                    </div>
                    <!--end::New Users-->
                </div>
            </div>
        </div>
    </div> --}}


    <div>
        aid: {{ session('aid') }}<br>
        tz: {{ session('tz') }}<br>
        df: {{ session('df') }}<br>
        df-datepicker: {{ session('df-datepicker') }}<br>
        df-moment: {{ session('df-moment') }}<br>
        username: {{ Auth::user()->username }}<br>

        <?php

        $utc = \Carbon\Carbon::createFromDate('2019', '03', '11', 'UTC');
        $tas = \Carbon\Carbon::createFromDate('2019', '03', '11', 'Australia/Hobart');
        $today = \Carbon\Carbon::today();
        $todayEnd = \Carbon\Carbon::today(session('tz'))->endOfDay()->timezone('UTC');
        $now = \Carbon\Carbon::now();
        $utcTodayStart = \Carbon\Carbon::today(session('tz'))->timezone('UTC');
        $utcTodayEnd = \Carbon\Carbon::today(session('tz'))->endOfDay()->timezone('UTC');

        echo "UTC: " . $utc->toDateTimeString() . "<br>";
        echo "TAS: " . $tas->toDateTimeString() . "<br>";
        echo "today: " . $today->toDateTimeString() . "<br>";
        echo "todayEnd: " . $todayEnd->toDateTimeString() . "<br>";
        echo "now: " . $now->toDateTimeString() . "<br>";
        echo "nowTAS: " . $now->timezone(session('tz'))->toDateTimeString() . "<br>";
        echo "utcTodayStart: " . $utcTodayStart->toDateTimeString() . "<br>";
        echo "utcTodayEnd: " . $utcTodayEnd->toDateTimeString() . "<br>";
        ?>

        <br><br>Testing1<br>
        @foreach (\App\Models\Event\EventInstance::whereDate('start', '2019-03-11')->get() as $instance)
            {{ $instance->name }} - {{ $instance->start }} - {{ $instance->start->timezone(session('tz'))->toDateTimeString() }}<br>
        @endforeach

        <br><br>Testing2<br>
        @foreach (\App\Models\Event\EventInstance::whereBetween('start', [$utcTodayStart, $utcTodayEnd])->get() as $instance)
            {{ $instance->name }} - {{ $instance->start }} - {{ $instance->start->timezone(session('tz'))->toDateTimeString() }}<br>
        @endforeach
    </div>

    <div id="vue-dragableList">
        <div class="root"></div>
    </div>

    {{-- list template --}}
    <script type="text/x-template" id="list-template">
        <div class="root">
            <div class="row">
                <div class="col-3">
                    <SortableList lockAxis="y" v-model="items">
                        <SortableItem v-for="(item, index) in items" :index="index" :key="index" :item="item">
                    </SortableList>

                    <button v-on:click="add()">Add</button>
                    <button v-on:click="save()">Save Me</button>
                    <br><br>
                    <pre>@{{ $data }}</pre>
                </div>
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
<script src="https://unpkg.com/vue-slicksort@latest/dist/vue-slicksort.min.js"></script>
<script>

    var { ContainerMixin, ElementMixin, HandleDirective } = window.VueSlicksort;
    var xx = 'xx';
    //var xx = {
    //    items: [{id: 'cat', order: '0', name: 'Cat'}, {id: 'dog', order: '1', name: 'Dog'}, {id: 'cow', order: '2', name: 'Cow'}]
    //};

    const SortableList = {
        mixins: [ContainerMixin],
        template: '<ul class="list-group"> <slot /> </ul>',
    };

    const SortableItem = {
        mixins: [ElementMixin],
        props: ['item', 'index'],
        //template: "#list-template",  // v-bind:xx.item.order="index"
        template: `
                <li v-bind:sortEnd="movedItem()" v-bind:item.order="index" class="list-group-item">
                   <i class="fa fa-arrows-alt-v" style="color:#bbb; padding-right:20px"></i> @{{item.name}}
                </li>`,
        methods: {
            movedItem: function () {
                this.item.order = this.index;
                console.log('moved: [' + this.index + '] ' + this.item.name);
            }
        }
    };

    const DraggableList = {
        template: '#list-template',
        components: {
            SortableItem,
            SortableList,
        },
        data() {
            return {items: []};
        },
        created: function () {
            this.getList();
        },
        methods: {
            getList: function () {
                this.items = [{id: 'cat', order: '0', name: 'Cat'}, {id: 'dog', order: '1', name: 'Dog'}, {id: 'cow', order: '2', name: 'Cow'}];
            },
            save: function () {
                this.items.forEach(function (item) {
                    console.log('Order: ' + item.order + ' Name: ' + item.name);
                });
            },
            add: function () {
                this.items.push({id: 'new', order: '3', name: 'new'});
            }
        }
    };

    const root = new Vue({
        el: '#vue-dragableList',
        data: {xx: xx,},
        render: function (h) {
            return h(DraggableList)
        }
        //render: (h) => h(DraggableList),
    });
</script>
@stop
