@extends('layouts/main')

@section('content')
    <style>
        .item-edit {
            color: #aaa;
            cursor: pointer;
            float: right;
            padding-left: 15px;
        }

        .item-edit:hover {
            color: #34bfa3;
            /*  #9DA8EB light brand */
        }

        .item-del {
            color: #aaa;
            cursor: pointer;
            float: right;
            padding-left: 15px;
        }

        .item-del:hover {
            color: red;
        }

        .grade-active {
            padding: 5px;
            cursor: pointer;
        }

        .grade-inactive {
            padding: 5px;
            opacity: .5;
        }
    </style>

    <div class="row" id="vue-app">
        <div class="col-lg-12">
            <div class="m-portlet">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col" style="padding-right: 0px">
                            <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x">
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#" role="tab" aria-selected="true" id="type_schools">
                                        <i class="fa fa-graduation-cap"></i> Schools
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link show" data-toggle="tab" href="/settings/grades" role="tab" aria-selected="true" id="type_grades">
                                        <i class="fa fa-book"></i> Grades
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            {{-- Schools --}}
                            <div class="row" style="padding: 5px">
                                <div class="col-xl-6 col-lg-8 col-md-10">
                                    <school-list :data="xx.users" :columns="xx.columns" :filter-key="xx.searchQuery"></school-list>
                                </div>
                            </div>

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

        <pre>@{{ $data }}</pre>
        -->
    </div>


    {{-- Attendence template --}}
    <script type="text/x-template" id="school-template">
        <div class="m-accordion m-accordion--bordered" id="school_list" role="tablist">
            <template v-for="school in xx.schools">
                <div class="m-accordion__item">
                    <div class="m-accordion__item-head collapsed" role="tab" :id="idHead(school)" data-toggle="collapse" :href="idHref(school)" aria-expanded=" false">
                        <span class="m-accordion__item-icon"></span>
                        <span class="m-accordion__item-title"><div>@{{school.name}} &nbsp;
                                <small style="color:#888">#@{{ school.students }}</small>
                            </div></span>
                        <span class="m-accordion__item-mode"></span>
                    </div>

                    <div class="m-accordion__item-body collapse" :id="idBody(school)" role="tabpanel" :aria-labelledby="idHead(school)" data-parent="#school_list">
                        <div class='m-accordion__item-content'>
                            {{-- Display School Name + edit/del icons --}}
                            <div v-if="!xx.edit_name" class='row' style='padding:5px 5px 15px 5px; border-bottom: 1px solid #ccc; font-size: 14px'>
                                <div class='col-sm-9'>
                                    <h5 style="font-weight:400" style="margin-left: 10px">@{{ school.name }}</h5>
                                </div>
                                <div class='col-sm-3'>
                                    <i v-on:click="delSchool(school)" class="fa fa-trash-alt item-del" style=""></i>
                                    <i v-on:click="editSchool(school)" class="fa fa-edit item-edit" style=""></i>
                                </div>
                            </div>
                            {{-- Edit School Name --}}
                            <div v-if="xx.edit_name" class='row' style='padding:0px 0px 15px 0px; border-bottom: 1px solid #ccc; font-size: 14px'>
                                <div class='col'>
                                    <div class="input-group">
                                        <input v-model="xx.new_name" type="text" class="form-control m-input new_name" id="new_name">
                                        <div class="input-group-append">
                                            <span v-on:click="saveName(school)" class="input-group-text" style="color: #FFFFFF; background: #5867dd; padding: 0px 10px; cursor: pointer">Save</span>
                                            <!--<span v-on:click="cancelName(school)" class="input-group-text" style="color: #FFFFFF; background: #666; padding: 0px 10px; cursor: pointer">Cancel</span>-->
                                        </div>
                                        <span></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Row Tiles --}}
                            <div class='row' style='padding:5px; border-bottom: 1px solid #ccc; font-size: 14px'>
                                <div class='col-1'></div>
                                <div class='col-5'>Grades</div>
                                <div class='col-5'># Students</div>
                            </div>

                            <?php $counter = 0 ?>
                            <template v-for="grade, counter in school.grades">
                                <div v-on:click="toggleGrade(grade)" :class='gradeClass(grade)'>
                                    <div class='col-1'>
                                        <i v-if="grade.status && grade.linked == 0" v-on:click="showGrade(grade)" class="fa fa-times" style="color: #f4516c"></i>
                                        <i v-if="grade.status && grade.linked == 1" v-on:click="hideGrade(grade)" class="fa fa-check" style="color: #34bfa3"></i>
                                    </div>
                                    <div class='col-5'> @{{ grade.name }}</div>
                                    <div class='col-5'> @{{ grade.students }}</div>
                                    <?php $counter ++ ?>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </script>
@stop


@section('vendor-scripts')
@stop

@section('page-styles')
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/js/vue.min.js"></script>
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    var xx = {
        edit_name: 0, new_name: '',
        schools: [],
    };


    // register the attendance component
    Vue.component('school-list', {
        template: '#school-template',
        data: function () {
            return {xx: xx}
        },
        methods: {
            idHead: function (item) {
                return "m_accordion_item_" + item.id + "_head";
            },
            idBody: function (item) {
                return "m_accordion_item_" + item.id + "_body";
            },
            idHref: function (item) {
                return "#m_accordion_item_" + item.id + "_body";
            },
            toggleEditName: function () {

            },
            editSchool: function (school) {
                // Create slight delay to ensure showing of edit_name isn't prevented by our listener
                // to clear this text box on outside clicks
                setTimeout(function () {
                    this.xx.edit_name = !this.xx.edit_name;
                    this.xx.new_name = school.name;
                }, 100);
            },
            saveName: function (school) {
                this.xx.edit_name = !this.xx.edit_name;
                school.name = this.xx.new_name;
            },
            cancelName: function (school) {
                this.xx.edit_name = !this.xx.edit_name;
            },
            showItem: function (item) {
                item.status = 1;
            },
            hideItem: function (item) {
                item.status = 0;
            },
            showGrade: function (grade) {
                grade.linked = 1;
            },
            hideGrade: function (grade) {
                grade.linked = 0;
            },
            toggleGrade: function (grade) {
                grade.linked = !grade.linked;
            },
            delSchool: function (item) {
                //console.log('delete id:' + item.id + ' name:' + item.name);
                if (item.count > 0) {
                    swal({
                        title: "Are you sure?",
                        html: "You currently have <b>" + item.count + " students</b> in this school.<br><br><span class='m--font-danger'><i class='fa fa-exclamation-triangle'></i> All these students grades will be cleared!</span> ",
                        cancelButtonText: "Cancel!",
                        confirmButtonText: "Yes, delete it!",
                        confirmButtonClass: "btn btn-danger",
                        showCancelButton: true,
                        reverseButtons: true,
                        allowOutsideClick: true
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
            },
            gradeClass: function (grade) {
                if (grade.status == 1)
                    return "row grade-active";
                return "row grade-inactive";
            }
        }
    });


    var vue_app = new Vue({
        el: '#vue-app',
        data: {xx: xx,},
        created: function () {
            this.getUsers();
        },
        methods: {
            getUsers: function () {
                this.xx.searching = true;
                $.getJSON('/data/schools', function (data) {
                    this.xx.schools = data;
                    this.xx.searching = false;
                }.bind(this));
            },
        }
    });


    $(document).ready(function () {


        // Clear New Name for School each click within list
        // - listens for any mouse clicks outside of id #new_name
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#new_name').length) {
                xx.edit_name = 0;
                xx.new_name = '';
            }
        });

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

        // Save new grade name to item array
        $("#but_edit_grade").click(function () {
            $('#modal_edit_grade').modal('hide');
            $.each(xx.items, function (key, value) {
                if (value.id == $('#grade_id').val())
                    value.name = $('#grade_name').val()
            });
        });
    });


</script>
@stop
