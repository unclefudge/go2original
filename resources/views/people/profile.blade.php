@inject('ozstates', 'App\Http\Utilities\Ozstates')
@extends('layout/main')

@section('bodystyle')
    @if ($user->status)
        style="background-image: url(/img/head-purple.jpg)"
    @else
        style="background-image: url(/img/head-darkgrey.jpg)"
    @endif
@endsection

@section('subheader')
    @include('people/_header')
@endsection

@section('content')
    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="container-fluid">
            <div class="row">
                @include('people/_sidebar')
                {{-- Main Content --}}
                <div class="col">
                    @include('people/_sidebar-mobile')
                    <div class="row">
                        <div class="col-lg-8">
                            @include('people/_personal')
                        </div>
                        <div class="col-lg-4">
                            @include('people/_household')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $user->displayUpdatedBy() !!}
        </div>
    </div>
@endsection

@section('page-styles')
    <link href="/css/slim.min.css" rel="stylesheet">
@endsection

@section('vendor-scripts')
    <script src="/js/slim.kickstart.min.js"></script>
    <script src="/js/vue.min.js"></script>
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
    <script src="/js/people-shared-functions.js"></script>
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
            var gid = $("#grade_id").val();
            var school = $("#school_id").val();
            if (gid) {
                $.ajax({
                    url: '/data/schools-by-grade/' + gid,
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

        $("#type").change(function () {
            display_fields();
        });

        $("#grade_id").change(function () {
            display_fields();
        });

        // DOB
        $("#dob").datepicker({todayHighlight: !0, orientation: "bottom left", autoclose: true, clearBtn: true, format: "{{ session('df-datepicker') }}"});
        // WWC Exp
        $("#wwc_exp").datepicker({todayHighlight: !0, orientation: "bottom left", autoclose: true, clearBtn: true, format: "{{ session('df-datepicker') }}"});

        $(".household-link").click(function (e) {
            var split = this.id.split("-u");
            var id = split[1];
            window.location.href = "/people/" + id;
        });
    </script>

    @include('people/_household-vue')
@endsection
