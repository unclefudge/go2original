@inject('ozstates', 'App\Http\Utilities\Ozstates')
<?php $people_types = ['Student' => 'Student', 'Student/Volunteer' => 'Student/Volunteer', 'Parent' => 'Parent', 'Parent/Volunteer' => 'Parent/Volunteer', 'Volunteer' => 'Volunteer'] ?>
@extends('layouts/main')

@section('content')
    @include('people/_header')

    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <div class="row">
        <div class="col-lg-9 col-xs-12 col-sm-12">
            {{-- Personal Info --}}
            @include('people/_personal')
        </div>
        <div class="col-lg-3 col-xs-12 col-sm-12">
            {{-- Houshold --}}
            @include('people/_household')
        </div>

    </div>

    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $people->displayUpdatedBy() !!}
        </div>
    </div>
@stop


@section('vendor-scripts')
@stop

@section('page-styles')
    <link href="/css/slim.min.css" rel="stylesheet">
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="/js/slim.kickstart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.2/dist/vue.js"></script>
<script type="text/javascript">

    // Form errors - show modal
    if ($('#formerrors').val() == 'personal')
        $('#modal_personal').modal('show');

    display_fields();

    function display_fields() {
        var type = $("#type").val();
        $('#fields_student').hide();
        $('#fields_volunteer').hide();

        if (type == 'Student' || type == 'Student/Volunteer') {
            $('#fields_student').show();
        }
        if (type == 'Volunteer' || type == 'Student/Volunteer' || type == 'Parent/Volunteer') {
            $('#fields_volunteer').show();
        }

        // Dynamic School dropdown from Grade
        $("#school_id").select2({width: '100%', minimumResultsForSearch: -1});
        var grade = $("#grade").val();
        var school = $("#school_id").val();
        if (grade) {
            $.ajax({
                url: '/data/schools-by-grade/' + grade,
                type: "GET",
                dataType: "json",
                beforeSend: function () {
                    $('#loader').css("visibility", "visible");
                },

                success: function (data) {
                    $("#school_id").empty();
                    $("#school_id").append('<option value="">Select school</option>');

                    var school_names = [];
                    $.each(data, function (key, value) {
                        school_names.push(value);
                    });
                    school_names.sort();
                    var other_key = 0;
                    for (var i = 0; i < school_names.length; i++) {
                        var val = school_names[i];
                        var key = Object.keys(data)[Object.values(data).indexOf(school_names[i])];
                        if (val == 'Other') {
                            other_key = key;
                        } else {
                            if (school == key)
                                $("#school_id").append('<option value="' + key + '" selected>' + val + '</option>');
                            else
                                $("#school_id").append('<option value="' + key + '">' + val + '</option>');
                        }
                    }
                    // Append Other to end of list
                    if (school == 'Other')
                        $("#school_id").append('<option value="' + other_key + '" selected>Other</option>');
                    else
                        $("#school_id").append('<option value="' + other_key + '">Other</option>');
                },
                complete: function () {
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $("#school_id").empty();
            $("#school_id").append('<option value="">Select grade first</option>');
        }
    }

    $("#avatar").click(function () {
        //$("#modal_avatar_show").modal('show');
        $("#modal_avatar_edit").modal('show');
    });

    $("#avatar-edit").click(function (e) {
        e.stopPropagation();
        $("#modal_avatar_edit").modal('show');
    });


    $("#type").change(function () {
        display_fields();
    });

    $("#grade").change(function () {
        display_fields();
    });

    // DOB
    $("#dob").datepicker({
        todayHighlight: !0,
        orientation: "bottom left",
        autoclose: true,
        clearBtn: true,
        format: "{{ session('df-datepicker') }}",
    });

    // WWC Exp
    $("#wwc_exp").datepicker({
        todayHighlight: !0,
        orientation: "bottom left",
        autoclose: true,
        clearBtn: true,
        format: "{{ session('df-datepicker') }}",
    });

    //
    // Delete Person
    //
    $("#but_del_person").click(function (e) {
        swal({
            title: "Are you sure?",
            html: "All information and check-ins will be deleted for<br><b>" + "{{ $people->name }}" + "</b><br><br><span class='m--font-danger'><i class='fa fa-exclamation-triangle'></i>You will not be able to recover this person!</span> ",
            cancelButtonText: "Cancel!",
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass: "btn btn-danger",
            showCancelButton: true,
            reverseButtons: true,
            allowOutsideClick: true
        }).then(function (result) {
            if (result.value) {
                window.location.href = "/people/" + "{{ $people->id }}" + '/del';
            }
        });
    });

    $(".household-link").click(function (e) {
        var split = this.id.split("-p");
        var id = split[1];
        window.location.href = "/people/" + id;
    });


</script>

{{-- Household Vue Code --}}
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    var xx = {
        date: "", date_format: '',
        person: {id: "{{ $people->id }}", firstname: "{{ $people->firstname }}"},
        household: {
            id: "1",
            name: "{{ $people->households->first()->name }}",
            pid: "{{ $people->households->first()->head->id }}",
            members: [],
        },
        searchQuery: "{!! app('request')->input('query') !!}",
        member_search: false, households: [], members: [], people: []
    };

    // register the attendance component
    Vue.component('household-grid', {
        template: '#household-template',
        data: function () {
            return {xx: xx}
        },
        methods: {
            deleteMember: function (person) {
                // get index of object to delete
                var removeIndex = this.xx.household.members.map(function (item) {
                    return item.pid;
                }).indexOf(person.pid);
                this.xx.household.members.splice(removeIndex, 1); // delete object
            },
            headMember: function (person) {
                this.xx.household.pid = person.pid;
            },
        }
    })

    // register the attendance component
    Vue.component('search-grid', {
        template: '#search-template',
        props: {data: Array, filterKey: String},
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
                    return data.slice(0, 5); // Return first x records only
                }
                return []; // Return no records unless search string contains characters
            }
        },
        methods: {
            searchSelect: function (person) {
                this.xx.member_search = false;
                person.hid = this.xx.household.id;
                xx.household.members.push(person);
            },
        }
    })


    var vue_household = new Vue({
        el: '#modal_household',
        data: {xx: xx,},
        created: function () {
            this.getHouseholds();
        },
        methods: {
            getHouseholds: function () {
                $.getJSON('/data/household/members/' + this.xx.person.id, function (data) {
                    this.xx.households = data[0];
                    this.xx.household.members = data[1];
                    this.xx.members = data[2];
                    this.xx.people = data[3];
                }.bind(this));
            },
            toggleSearchMember: function () {
                this.xx.member_search = !this.xx.member_search;
            },
            saveHousehold: function () {
                updateHouseholdDB(this.xx.household).then(function (result) {
                    if (result)
                        window.location.href = "/people/" + this.xx.person.id;
                }.bind(this));
            }
        },
    });


    // Update house in Database Attendance and return a 'promise'
    function updateHouseholdDB(household) {
        console.log('Updating household:[' + household.id + '] ' + household.name);
        return new Promise(function (resolve, reject) {
            household._method = 'patch';
            $.ajax({
                url: '/household/' + household.id,
                type: 'POST',
                data: household,
                success: function (result) {
                    delete household._method;
                    console.log('DB updated household:[' + household.id + '] ' + household.name);
                    resolve(household);
                },
                error: function (result) {
                    alert("failed updating household " + household.name + '. Please refresh the page to resync household');
                    //console.log('DB updated household FAILED:[' + household.id + '] ' + household.name);
                    reject(false);
                }
            });
        });
    }

</script>
@stop
