@inject('ozstates', 'App\Http\Utilities\Ozstates')
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
            {!! $user->displayUpdatedBy() !!}
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
<script src="/js/vue.min.js"></script>
<script type="text/javascript">

    // Form errors - show modal
    if ($('#formerrors').val() == 'personal')
        $('#modal_personal').modal('show');

    display_fields();

    function display_fields() {
        var type = $("#type").val();
        $('#fields_student').hide();
        $('#fields_volunteer').hide();

        if (type == 'Student' || type == 'Student/Volunteer')
            $('#fields_student').show();

        if (type == 'Volunteer' || type == 'Student/Volunteer' || type == 'Parent/Volunteer')
            $('#fields_volunteer').show();


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
    $("#dob").datepicker({todayHighlight: !0, orientation: "bottom left", autoclose: true, clearBtn: true, format: "{{ session('df-datepicker') }}"});
    // WWC Exp
    $("#wwc_exp").datepicker({todayHighlight: !0, orientation: "bottom left", autoclose: true, clearBtn: true, format: "{{ session('df-datepicker') }}"});

    //
    // Delete Person
    //
    $("#but_del_person").click(function (e) {
        swal({
            title: "Are you sure?",
            html: "All information and check-ins will be deleted for<br><b>" + "{{ $user->name }}" + "</b><br><br><span class='m--font-danger'><i class='fa fa-exclamation-triangle'></i>You will not be able to recover this person!</span> ",
            cancelButtonText: "Cancel!",
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass: "btn btn-danger",
            showCancelButton: true,
            reverseButtons: true,
            allowOutsideClick: true
        }).then(function (result) {
            if (result.value) {
                window.location.href = "/people/" + "{{ $user->id }}" + '/del';
            }
        });
    });

    $(".household-link").click(function (e) {
        var split = this.id.split("-u");
        var id = split[1];
        window.location.href = "/people/" + id;
    });
</script>

@include('people/_household-vue')

@stop
