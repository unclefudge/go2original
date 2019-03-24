@extends('layout/main')

@section('bodystyle')
    style="background-image: url(/img/head-purple.jpg)"
@endsection

@section('subheader')
    @include('account/_header')
@endsection

@section('content')
    <style>
        .grade-active {
            padding: 5px;
            cursor: pointer;
        }

        .grade-inactive {
            padding: 5px;
            opacity: .5;
        }
    </style>
    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="vue-app">
        <div class="container-fluid">
            <div class="row">
                @include('account/_sidebar')
                {{-- Main Content --}}
                <div class="col" style="height: 100% !important; min-height: 100% !important;">
                    @include('account/_sidebar-mobile')
                    <div class="row">
                        <div class="col">
                            {{-- Schools --}}
                            <div class="kt-portlet kt-portlet--tabs">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-toolbar">
                                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-primary nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold">
                                            <li class="nav-item">
                                                <a class="nav-link active show" data-toggle="tab" href="#" role="tab" aria-selected="true" id="type_schools">
                                                    <i class="fa fa-graduation-cap"></i> Schools
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link show" data-toggle="tab" href="/account/grades" role="tab" aria-selected="true" id="type_grades">
                                                    <i class="fa fa-book"></i> Grades
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    {{--}}
                                    <div class="pull-right" style="min-width:150px; padding:10px">
                                        <button type="button" class="btn btn-secondary btn-sm  pull-right" style="margin-left: 10px" id="but_show_inactive"><i class="fa fa-eye"></i></button>
                                        <button type="button" class="btn btn-sm pull-right" style="margin-left: 10px; color: #000000; background: #eee" id="but_hide_inactive"><i class="fa fa-eye-slash" style="padding-right: 5px"></i> Hide Inactive</button>
                                        <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#modal_create_person">Add</button>
                                    </div>--}}
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="alert alert-secondary" role="alert">
                                                <div class="alert-text">
                                                    A list of schools your students attend and the relevant grades.<br><br>
                                                    Simply hide/show which grades apply to each school.<br><br>
                                                    You can add and delete schools as required. To modify grades please use the grades tab above.
                                                </div>
                                            </div>
                                            <div class="input-group" style="padding-bottom: 20px">
                                                <input v-model="xx.addItem" type="text" class="form-control form-control m-input" placeholder="Add new school">
                                                <div class="input-group-append">
                                                    <span v-on:click="add()" class="input-group-text" style="color: #FFFFFF; background: #666; padding: 0px 20px; cursor: pointer">Add</span>
                                                </div>
                                                <span></span>
                                            </div>
                                            <div class="d-none d-md-block">
                                                <button v-on:click="save()" class="btn btn-primary" id="saveList1">Save</button>
                                            </div>
                                        </div>
                                        <div class="col-md-7" style="padding-bottom: 30px">
                                            <h5 class="text-center">Schools</h5>
                                            <school-list :data="xx.users" :columns="xx.columns" :filter-key="xx.searchQuery"></school-list>
                                            <div class="d-md-none">
                                                <br><br>
                                                <button v-on:click="save()" class="btn btn-primary">Save</button>
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
    <div type="text/x-template" id="school-template">
        <div class="accordion accordion-toggle-plus" id="school_list" role="tablist">
            <template v-for="school in xx.schools">
                <div class="card">
                    <div class="card-header" :id="idHead(school)">
                        <div class="card-title collapsed" data-toggle="collapse" :data-target="idHref(school)" aria-expanded="false" :aria-controls="idBody(school)">
                            @{{school.name}}
                        </div>
                    </div>
                    <div :id="idBody(school)" class="collapse" :aria-labelledby="idHead(school)" data-parent="#school_list">
                        <div class="card-body">
                            {{-- Display School Name + edit/del icons --}}
                            <div v-if="!xx.edit_name" class='row' style='padding:5px 5px 15px 5px; border-bottom: 1px solid #ccc; font-size: 14px'>
                                <div class='col-sm-9'>
                                    <h5 style="font-weight:400" style="margin-left: 10px">@{{ school.name }}</h5>
                                </div>
                                <div class='col-sm-3'>
                                    <i v-if="school.name != 'Other'" v-on:click="delSchool(school)" class="fa fa-trash-alt item-del"></i>
                                    <i v-if="school.name != 'Other'" v-on:click="editSchool(school)" class="fa fa-edit item-edit"></i>
                                </div>
                            </div>
                            {{-- Edit School Name --}}
                            <div v-if="xx.edit_name" class='row' style='padding:0px 0px 15px 0px; border-bottom: 1px solid #ccc; font-size: 14px'>
                                <div class='col'>
                                    <div class="input-group">
                                        <input v-model="xx.new_name" type="text" class="form-control m-input new_name" id="new_name">
                                        <div class="input-group-append">
                                            <span v-on:click="saveName(school)" class="input-group-text" style="color: #FFFFFF; background: #5867dd; padding: 0px 10px; cursor: pointer" id="save_name">Save</span>
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
                                {{-- Grade toggle / count --}}
                                <div v-if="school.name != 'Other'" v-on:click="toggleGrade(school, grade)" :class='gradeClass(grade)'>
                                    <div class='col-1'>
                                        <i v-if="grade.status && grade.linked == 0" class="fa fa-times" style="color: #f4516c"></i>
                                        <i v-if="grade.status && grade.linked == 1" class="fa fa-check" style="color: #34bfa3"></i>
                                    </div>
                                    <div class='col-5'> @{{ grade.name }}</div>
                                    <div class='col-5'> @{{ grade.students }}</div>
                                </div>
                                {{-- Can't toggle grades for 'Other' school --}}
                                <div v-if="school.name == 'Other'" :class='gradeClass2(grade)' style="padding: 5px;">
                                    <div class='col-1'>
                                        <i v-if="grade.status" class="fa fa-check" style="color: #34bfa3"></i>
                                    </div>
                                    <div class='col-5'> @{{ grade.name }}</div>
                                    <div class='col-5'> @{{ grade.students }}</div>
                                </div>
                                <?php $counter ++ ?>
                            </template>

                            {{-- Totals --}}
                            <div class='row' style='padding:5px; border-top: 1px solid #ccc; font-size: 14px'>
                                <div class='col-1'></div>
                                <div class='col-5 text-right'>Total</div>
                                <div class='col-5'>@{{ school.students }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $account->displayUpdatedBy() !!}
        </div>
    </div>
@endsection

@section('page-styles')
@endsection

@section('vendor-scripts')
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
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
                    return "heading" + item.id;
                },
                idBody: function (item) {
                    return "collapse" + item.id;
                },
                idHref: function (item) {
                    return "#collapse" + item.id;
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
                    updateSchoolDB(school, this.xx.new_name).then(function (result) {
                        if (result) {
                            this.xx.edit_name = !this.xx.edit_name;
                            school.name = this.xx.new_name;
                        }
                    }.bind(this));
                },
                cancelName: function (school) {
                    this.xx.edit_name = !this.xx.edit_name;
                },
                toggleGrade: function (school, grade) {
                    updateSchoolGradeDB(school, grade).then(function (result) {
                        if (result)
                            grade.linked = !grade.linked;
                    }.bind(this));
                },
                delSchool: function (school) {
                    //console.log('delete id:' + item.id + ' name:' + item.name);
                    Swal.fire({
                        title: "Are you sure?",
                        html: "You currently have <b>" + school.students + " students</b> in this school.<br><br><span class='m--font-danger'><i class='fa fa-exclamation-triangle'></i> All these students will be moved to 'Other' school</span> ",
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
                            this.xx.schools = this.xx.schools.filter(function (obj) {
                                return obj.id !== school.id;
                            });
                        }
                    }.bind(this));

                },
                gradeClass: function (grade) {
                    if (grade.status == 1)
                        return "row grade-active";
                    return "row grade-inactive";
                },
                gradeClass2: function (grade) {
                    if (grade.status == 1)
                        return "row";
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


        // Update Event Instance in Database Attendance and return a 'promise'
        function updateSchoolDB(school, new_name) {
            return new Promise(function (resolve, reject) {
                var record = {id: school.id, name: new_name, _method: 'patch'};
                //school._method = 'patch';
                //school.name = new_name;
                $.ajax({
                    url: '/settings/schools/' + school.id,
                    type: 'POST',
                    data: record,
                    success: function (result) {
                        //console.log('DB updated school:[' + school.id + '] ' + school.name);
                        resolve(school);
                    },
                    error: function (result) {
                        alert("Something went wrong updating school " + school.name + '. Please refresh the page to resync.');
                        //console.log('DB updated school grade FAILED:[' + school.id + '] ' + school.name);
                        reject(false);
                    }
                });
            });
        }

        // Update Event Instance in Database Attendance and return a 'promise'
        function updateSchoolGradeDB(school, grade) {
            return new Promise(function (resolve, reject) {
                $.ajax({
                    url: '/data/school/' + school.id + '/grade/' + grade.id + '/link/' + grade.linked,
                    type: 'GET',
                    success: function (result) {
                        //console.log('DB updated school:[' + school.id + '] ' + school.name);
                        resolve(true);
                    },
                    error: function (result) {
                        alert("Something went wrong updating school grade " + school.name + '. Please refresh the page to resync.');
                        //console.log('DB updated school grade FAILED:[' + school.id + '] ' + school.name);
                        reject(false);
                    }
                });
            });
        }


        $(document).ready(function () {
            // Clear New Name for School each click within list
            // - listens for any mouse clicks outside of id #new_name or #save_name
            $(document).on('click', function (event) {
                if (!($(event.target).closest('#new_name').length || $(event.target).closest('#save_name').length)) {
                    xx.edit_name = 0;
                    xx.new_name = '';
                }
            });

            $("#type_schools").click(function () {
                window.location.href = "/account/schools";
            });

            $("#type_grades").click(function () {
                window.location.href = "/account/grades";
            });
        });


    </script>
@endsection
