@extends('layouts/main')
<?php
$months_array = ['Month', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$days_array = ['Day', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'];
?>

@section('content')


    <div class="m-portlet">
        <div class="m-portlet__body">
            <div class="row" style="padding-bottom: 10px">
                <div class="col-12"><h4>Grades</h4></div>
            </div>
            <div class="row">
                <div class="col">
                    <div id="vue-dragableList">
                        <div class="root"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .item-del {
            color: #aaa;
            cursor: pointer;
            float: right;
            padding-left: 20px;
        }

        .item-del:hover {
            color: red !important;
        }
    </style>

    {{-- List template --}}
    <script type="text/x-template" id="list-template">
        <div class="root">
            <div class="row">
                <div class="col-md-4">
                    <div class="alert m-alert m-alert--default" role="alert">
                        A list of school grades to be used by your students.<br><br>
                        Simply drag them into the correct order of school progression.<br><br>
                        You can delete any grades you don't use or add additional ones.
                    </div>
                    <div class="input-group" style="padding-bottom: 20px">
                        <input v-model="xx.addItem" type="text" class="form-control form-control m-input" placeholder="Add new grade">
                        <div class="input-group-append">
                            <span v-on:click="add()" class="input-group-text" style="color: #FFFFFF; background: #666; padding: 0px 20px; cursor: pointer">Add</span>
                        </div>
                        <span></span>
                    </div>
                    <div class="d-none d-md-block">
                        <button v-on:click="save()" class="btn btn-primary">Save</button>
                    </div>


                </div>
                <div class="col-lg-3 col-md-5" style="padding-bottom: 30px">
                    <h5>Grade Order</h5>
                    <SortableList lockAxis="y" v-model="xx.items" distance="5">
                        <SortableItem v-for="(item, index) in xx.items" :index="index" :key="index" :item="item" :items="xx.items">
                    </SortableList>
                    <div class="d-md-none">
                        <br><br>
                        <button v-on:click="save()" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <div style="height: 100%; margin: 0px 30px; padding: 20px; color: #fff; background: #717ee2;">
                        <h3>Beginning of School Year</h3>
                        <p style="font-size: larger">
                            Once a year Go2Youth will automaticatly move all your students up a grade because they are such excellent pupils.<br><br>
                            What date would you like this to happen each year.
                        </p>
                        <div class="row">
                            <div class="col-md-8" style="padding-bottom: 20px">
                                {!! Form::select('month', $months_array, null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('day', $days_array, null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-md-center">
                            <div class="col-6">
                                <button class="btn btn-secondary btn-block">Save</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <br><br>
            <pre>@{{ $data }}</pre>
        </div>
    </script>

    {{-- Items template --}}
    <script type="text/x-template" id="item-template">
        <li v-bind:sortEnd="movedItem()" v-bind:item.order="index" class="list-group-item">
            <i class="fa fa-arrows-alt-v" style="color:#bbb; padding-right:20px"></i> @{{item.name}}
            <i v-on:click="delItem(item)" class="fa fa-trash-alt item-del" style=""></i>
        </li>
    </script>
@stop


@section('vendor-scripts')
@stop

@section('page-styles')
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/js/vue.min.js"></script>
<script src="https://unpkg.com/vue-slicksort@latest/dist/vue-slicksort.min.js"></script>
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    var { ContainerMixin, ElementMixin, HandleDirective } = window.VueSlicksort;
    //var xx = 'xx';
    var xx = {
        addItem: '',
        items: []
        //items: [{id: '1', order: '0', name: 'Catty'}, {id: '2', order: '1', name: 'Dog'}, {id: '3', order: '2', name: 'Bird'}]
    };

    const SortableList = {
        mixins: [ContainerMixin],
        template: '<ul class="list-group"> <slot /> </ul>',
        data() {
            return {xx: xx};
        },
    };

    const SortableItem = {
        mixins: [ElementMixin],
        props: ['item', 'index', 'items'],
        //template: "#list-template",
        template: '#item-template',
        data() {
            return {xx: xx};
        },
        methods: {
            movedItem: function () {
                this.item.order = this.index;
                //console.log('moved: [' + this.index + '] ' + this.item.name);
            },
            delItem: function (item) {
                console.log('delete id:' + item.id + ' name:' + item.name);

                console.log('before')
                this.xx.items.forEach(function (item) {
                    console.log('id:' + item.id + ' Order: ' + item.order + ' Name: ' + item.name);
                });
                this.xx.items = this.items.filter(function (obj) {
                    return obj.id !== item.id;
                });
                console.log('after')
                this.xx.items.forEach(function (item) {
                    console.log('id:' + item.id + ' Order: ' + item.order + ' Name: ' + item.name);
                });
                //alert(item.name);
            },
        }
    };

    const DraggableList = {
        template: '#list-template',
        components: {
            SortableItem,
            SortableList,
        },
        data() {
            return {xx: xx};
        },
        created: function () {
            this.getList();
        },
        methods: {
            getList: function () {
                $.getJSON('/data/grades', function (data) {
                    this.xx.items = data;
                }.bind(this));
                //this.items = [{id: 'cat', order: '0', name: 'Cat'}, {id: 'dog', order: '1', name: 'Dog'}, {id: 'cow', order: '2', name: 'Cow'}];
            },
            save: function () {
                this.xx.items.forEach(function (item) {
                    console.log('Order: ' + item.order + ' Name: ' + item.name);
                });
            },
            add: function () {
                if (this.xx.addItem != '')
                    this.xx.items.push({id: 'new', order: '', name: this.xx.addItem});
                this.xx.addItem = '';
            },
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
