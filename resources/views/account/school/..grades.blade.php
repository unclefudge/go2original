@extends('layouts/main')
<?php
$months_array = ['Month', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$days_array = ['Day', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'];
?>

@section('content')

    <div class="row">
        <div class="col-lg-8">
            <div class="m-portlet">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col" style="padding-right: 0px">
                            <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x">
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="/settings/schools" role="tab" aria-selected="true" id="type_schools">
                                        <i class="fa fa-graduation-cap"></i> Schools
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#" role="tab" aria-selected="true" id="type_grades">
                                        <i class="fa fa-book"></i> Grades
                                    </a>
                                </li>
                            </ul>
                        </div>
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
        </div>
        <div class="col-lg-4">
            <div class="m-portlet m-portlet--skin-dark m-portlet--bordered-semi m--bg-brand">
                <div class="m-portlet__body">
                    <div class="row" style="padding: 20px 0px">
                        <div class="col-12"><h3 class="text-center">Beginning of School Year</h3></div>
                    </div>
                    <div class="row">
                        <div class="col">

                            <p style="font-size: larger">
                                Once a year Go2Youth will automaticatly move all your students up a grade because they are such excellent pupils.<br><br>
                                What date would you like this to happen each year.
                            </p>
                            <div class="row justify-content-sm-center">
                                <div class="col-lg-6 col-md-3 col-sm-4" style="padding-bottom: 20px">
                                    {!! Form::select('month', $months_array, null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                                </div>
                                <div class="col-lg-4 col-md-2 col-sm-3">
                                    {!! Form::select('day', $days_array, null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-sm-center">
                                <div class="col-lg-6 col-md-3 col-sm-3">
                                    <button class="btn btn-secondary btn-block">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .item-edit {
            color: #aaa;
            cursor: pointer;
            float: right;
            padding-left: 20px;
        }

        .item-edit:hover {
            color: #34bfa3;
            /*  #9DA8EB light brand */
        }

        .item-del {
            color: #aaa;
            cursor: pointer;
            float: right;
            padding-left: 20px;
        }

        .item-del:hover {
            color: red;
        }
    </style>

    {{-- List template --}}
    <script type="text/x-template" id="list-template">
        <div class="root">
            <div class="row">
                <div class="col-md-6">
                    <div class="alert m-alert m-alert--default" role="alert">
                        A list of school grades to be used by your students.<br><br>
                        Simply drag them into the correct order of school progression.<br><br>
                        You can hide or delete any grades you don't use or add additional ones.
                    </div>
                    <div class="input-group" style="padding-bottom: 20px">
                        <input v-model="xx.addItem" type="text" class="form-control form-control m-input" placeholder="Add new grade">
                        <div class="input-group-append">
                            <span v-on:click="add()" class="input-group-text" style="color: #FFFFFF; background: #666; padding: 0px 20px; cursor: pointer">Add</span>
                        </div>
                        <span></span>
                    </div>
                    <div class="d-none d-md-block">
                        <button v-on:click="save()" class="btn btn-primary" id="saveList1">Save</button>
                        <a href="#" v-on:click="restore()" style="margin-left: 30px">Restore previous grades</a>
                    </div>


                </div>
                <div class="col-md-6" style="padding-bottom: 30px">
                    <h5 class="text-center">Grade Order</h5>
                    <SortableList lockAxis="y" v-model="xx.items" distance="5">
                        <SortableItem v-for="(item, index) in xx.items" :index="index" :key="index" :item="item" :items="xx.items">
                    </SortableList>
                    <div class="d-md-none">
                        <br><br>
                        <button v-on:click="save()" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>

            <br><br>
            <pre>@{{ $data }}</pre>
            -->
        </div>
    </script>

    {{-- Items template --}}
    <script type="text/x-template" id="item-template">
        <li v-bind:sortEnd="movedItem()" v-bind:item.order="index" class="list-group-item" :style="itemStyle(item)">
            <div v-if="item.edit == 0">
                <i class="fa fa-arrows-alt-v" style="color:#bbb; padding-right:20px"></i> @{{item.name}}
                <i v-on:click="delItem(item)" class="fa fa-trash-alt item-del" style=""></i>
                <i v-if="item.status == 0" v-on:click="showItem(item)" class="fa fa-eye-slash item-edit" style=""></i>
                <i v-if="item.status == 1" v-on:click="hideItem(item)" class="fa fa-eye item-edit" style=""></i>
                <i v-if="item.status == 1" v-on:click="editItem(item)" class="fa fa-edit item-edit" style=""></i>
            </div>
            <div v-if="item.edit == 1">
                <div class="input-group">
                    <input v-on:keyup="editVerify(item)" v-model="item.name" type="text" class="form-control m-input">
                    <div v-if="xx.editOK == 1" class="input-group-append">
                        <span v-on:click="saveItem(item)" class="input-group-text" style="color: #FFFFFF; background: #5867dd; padding: 0px 10px; cursor: pointer">Save</span>
                    </div>
                    <span></span>
                </div>
            </div>
        </li>
    </script>

    {{-- Edit Grade Modal --}}
    {{--}}
    <div class="modal fade" id="modal_edit_grade" tabindex="-1" role="dialog" aria-labelledby="Edit Item" aria-hidden="true">
        <div class="modal-dialog modal-med" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #32c5d2; padding: 20px;">
                    <h5 class="modal-title text-white" id="ModalLabel">Edit Grade</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #F7F7F7; padding:20px;">
                    <div class="row">
                        <div class="col">
                            <div class="form-group m-form__group {!! fieldHasError('name', $errors) !!}">
                                {!! Form::label('grade_name', "Grade Name", ['class' => 'form-control-label']) !!}
                                {!! Form::text('grade_name', null, ['class' => 'form-control', 'id' => 'grade_name', 'placeholder' => 'Enter grade name']) !!}
                                {!! Form::hidden('grade_id', null, ['id' => 'grade_id']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px">
                    <button type="button" class="btn btn-secondary" style="border: 0" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="but_edit_grade">Save</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    --}}
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
        addItem: '', editOK: 1,
        items: [],
        original: []
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
            editItem: function (item) {
                //console.log('edit id:' + item.id + ' name:' + item.name);
                item.edit = 1;
                //$('#grade_name').val(item.name);
                //$('#grade_id').val(item.id);
                //$('#modal_edit_grade').modal('show');
            },
            editVerify: function (item) {
                if (item.name == '')
                    this.xx.editOK = 0;
                else
                    this.xx.editOK = 1;

            },
            saveItem: function (item) {
                //console.log('edit id:' + item.id + ' name:' + item.name);
                item.edit = 0;
            },
            showItem: function (item) {
                item.status = 1;
            },
            hideItem: function (item) {
                item.status = 0;
            },
            delItem: function (item) {
                //console.log('delete id:' + item.id + ' name:' + item.name);
                if (item.count > 0) {
                    Swal.fire({
                        title: "Are you sure?",
                        html: "You currently have <b>" + item.count + " students</b> in this grade.<br><br><span class='m--font-danger'><i class='fa fa-exclamation-triangle'></i> All these students grades will be cleared!</span> ",
                        cancelButtonText: "Cancel!",
                        cancelButtonClass: "btn btn-secondary",
                        confirmButtonText: "Yes, delete it!",
                        confirmButtonClass: "btn btn-danger",
                        showCancelButton: true,
                        reverseButtons: true,
                        allowOutsideClick: true,
                        animation: false,
                        customClass: {popup: 'animated tada'}
                    }).then(function (result) {
                        if (result.value) {
                            // Delete grade
                            this.xx.items = this.items.filter(function (obj) {
                                return obj.id !== item.id;
                            });
                        }
                    }.bind(this));
                } else {
                    // Delete grade
                    this.xx.items = this.items.filter(function (obj) {
                        return obj.id !== item.id;
                    });
                }

                //alert(item.name);
            },
            itemStyle: function (item) {
                //console.log(item.name);
                var style;
                if (item.status == 0)
                    style = "opacity: .5"

                return style;
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
            return {xx: xx};
        },
        created: function () {
            this.getList();
        },
        methods: {
            getList: function () {
                $.getJSON('/data/grades', function (data) {
                    this.xx.items = data;
                    this.xx.original = JSON.stringify(this.xx.items);
                }.bind(this));
            },
            save: function () {
                $('#saveList1').html('Saving');
                $('#saveList1').addClass('m-loader m-loader--light m-loader--right');

                updateGradeDB(JSON.stringify(this.xx.items)).then(function (result) {
                    if (result)
                        window.location.href = "/settings/grades";
                }.bind(this));
            },
            add: function () {
                if (this.xx.addItem != '')
                    this.xx.items.push({id: 'new', order: '', name: this.xx.addItem});
                this.xx.addItem = '';
            },
            restore: function () {
                this.xx.items = JSON.parse(this.xx.original);
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

    // Update user in Database Attendance and return a 'promise'
    function updateGradeDB(items) {
        return new Promise(function (resolve, reject) {
            var grades = {grades: items};
            grades._method = 'patch';
            $.ajax({
                url: '/grades/' + "{{ session('aid') }}",
                type: 'POST',
                data: grades,
                success: function (result) {
                    resolve(items);
                },
                error: function (result) {
                    alert("Something went wrong updating grades. Please refresh the page to resync.");
                    //console.log('DB updated user FAILED:[' + user.eid + '] ' + user.name);
                    reject(false);
                }
            });
        });
    }
    ;


    // Tab Page Links
    $("#type_schools").click(function () {
        window.location.href = "/settings/schools";
    });

    $("#type_grades").click(function () {
        window.location.href = "/settings/grades";
    });

    // Hide Edit Grade Save button on empty name
    $("#grade_name").keyup(function () {
        $('#but_edit_grade').hide();
        if ($('#grade_name').val() != '')
            $('#but_edit_grade').show();
    });
    // Hide Edit Grade Save button on empty name
    $(".edit-name").keyup(function () {
        alert('key');
        $('#but_edit_grade').hide();
        if ($('#grade_name').val() != '')
            $('#but_edit_grade').show();
    });

    // Save new grade name to item array
    $("#but_edit_grade").click(function () {
        $('#modal_edit_grade').modal('hide');
        $.each(xx.items, function (key, value) {
            if (value.id == $('#grade_id').val())
                value.name = $('#grade_name').val()
        });
    });
</script>
@stop
