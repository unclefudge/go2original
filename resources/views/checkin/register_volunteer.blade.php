@inject('ozstates', 'App\Http\Utilities\Ozstates')
@extends('layouts/checkin')

@section('content')
    <style>
        body, html {
            @if ($instance->event->background)
              background-image: url("{!! $instance->event->background_path !!}") !important;
            @endif
              height: 100%; /* set height */

            /* Create the parallax scrolling effect */
            background-attachment: fixed !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 50px;
            background-color: white;
            color: black;
        }

    </style>
    <div>
        <div class="row text-white" style="height:70px; background: rgb(61, 59, 86)">
            <div class="col text-center">
                <img src="/img/logo-med.png" style="float: left; padding:5px 0px 5px 20px">
                <h1 style="padding-top: 10px">{{ $instance->name }} <span class="pull-right" style="font-size: 14px; padding-right: 20px">{!! \Carbon\Carbon::now()->timezone(session('tz'))->format(session('df'). " g:i a") !!}</span></h1>
            </div>
        </div>

        {{-- Volunteer Rego  --}}
        <div class="row justify-content-lg-center" style="padding: 30px">
            <div class="col-lg-8">
                <div class="m-portlet">
                    <div class="m-portlet__body">
                        {!! Form::model('people', ['action' => ['Event\CheckinController@volunteerRegister', $instance->id], 'files' => true]) !!}
                        @include('form-error')
                        <div class="row" style="padding-bottom: 10px">
                            <div class="col-md-5">
                                <h4>Volunteer Registration</h4>
                            </div>
                            <div class="col-md-7">
                                <a href="/checkin/{{ $instance->id }}/register/student" class="btn btn-brand pull-right" style="margin-left: 20px">Switch to Student</a>
                                <a href="/checkin/{{ $instance->eid }}" class="btn btn-secondary pull-right">Return to check-in</a>
                            </div>
                        </div>
                        {{-- First + Last Name --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-form__group {!! fieldHasError('firstname', $errors) !!}">
                                    {!! Form::label('firstname', 'First Name', ['class' => 'form-control-label']) !!}
                                    {!! Form::text('firstname', null, ['class' => 'form-control', 'required']) !!}
                                    {!! fieldErrorMessage('firstname', $errors) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {!! fieldHasError('lastname', $errors) !!}">
                                    {!! Form::label('lastname', 'Last Name', ['class' => 'control-label']) !!}
                                    {!! Form::text('lastname', null, ['class' => 'form-control', 'required']) !!}
                                    {!! fieldErrorMessage('lastname', $errors) !!}
                                </div>
                            </div>
                        </div>
                        {{-- Gender + DOB --}}
                        <div class="row">
                            {{-- Gender --}}
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group {!! fieldHasError('gender', $errors) !!}">
                                    {!! Form::label('gender', 'Gender', ['class' => 'control-label']) !!}
                                    {!! Form::select('gender', ['' => 'Gender', 'Male' => 'Male', 'Female' => 'Female'], null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                                    {!! fieldErrorMessage('gender', $errors) !!}
                                </div>
                            </div>
                            {{-- Birthday --}}
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group {!! fieldHasError('dob', $errors) !!}">
                                    {!! Form::label('dob', 'Birthday', ['class' => 'control-label']) !!}
                                    <div class="input-group date">
                                        {!! Form::text('dob', null, ['class' => 'form-control m-input', 'style' => 'background:#FFF', 'placeholder' => session('df-datepicker'), 'id' => 'dob']) !!}
                                    </div>
                                    {!! fieldErrorMessage('dob', $errors) !!}
                                </div>
                            </div>
                        </div>
                        {{-- Phone + Email --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {!! fieldHasError('phone', $errors) !!}">
                                    {!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}
                                    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                                    {!! fieldErrorMessage('phone', $errors) !!}
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group {!! fieldHasError('email', $errors) !!}">
                                    {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
                                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                    {!! fieldErrorMessage('email', $errors) !!}
                                </div>
                            </div>
                        </div>
                        {{-- Adddress --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {!! fieldHasError('address', $errors) !!}">
                                    {!! Form::label('address', 'Address', ['class' => 'control-label']) !!}
                                    {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'street address']) !!}
                                    {!! fieldErrorMessage('address', $errors) !!}
                                </div>
                            </div>
                        </div>
                        {{-- Suburb + State + Postcode --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {!! fieldHasError('suburb', $errors) !!}">
                                    {!! Form::text('suburb', null, ['class' => 'form-control', 'placeholder' => 'suburb']) !!}
                                    {!! fieldErrorMessage('suburb', $errors) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {!! fieldHasError('state', $errors) !!}">
                                    {!! Form::select('state', $ozstates::all(), 'TAS', ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                                    {!! fieldErrorMessage('state', $errors) !!}
                                </div>
                            </div>
                            {{-- Postcode --}}
                            <div class="col-md-3">
                                <div class="form-group {!! fieldHasError('postcode', $errors) !!}">
                                    {!! Form::text('postcode', null, ['class' => 'form-control', 'placeholder' => 'postcode']) !!}
                                    {!! fieldErrorMessage('postcode', $errors) !!}
                                </div>
                            </div>
                        </div>

                        {{-- WWC info --}}
                        <div class="row" style="background-color: #F7F7F7; margin: 10px -25px; padding: 10px 30px">
                            <div class="col-md-3">
                                <br><h6>WWC Registration</h6>
                            </div>
                            {{-- WWC No. --}}
                            <div class="col-md-3">
                                <div class="form-group {!! fieldHasError('wwc_no', $errors) !!}">
                                    {!! Form::label('wwc_no', 'No.', ['class' => 'control-label']) !!}
                                    {!! Form::text('wwc_no', null, ['class' => 'form-control']) !!}
                                    {!! fieldErrorMessage('wwc_no', $errors) !!}
                                </div>
                            </div>
                            {{--  WWC Expiry  --}}
                            <div class="col-md-3">
                                <div class="form-group {!! fieldHasError('wwc_exp', $errors) !!}">
                                    {!! Form::label('wwc_exp', 'Expiry', ['class' => 'control-label']) !!}
                                    <div class="input-group date">
                                        {!! Form::text('wwc_exp', null, ['class' => 'form-control m-input', 'style' => 'background:#FFF', 'placeholder' => session('df-datepicker'), 'id' => 'wwc_exp']) !!}
                                    </div>
                                    {!! fieldErrorMessage('wwc_exp', $errors) !!}
                                </div>
                            </div>
                        </div>

                        <hr>
                        {{-- Photo --}}
                        <div class="row">
                            <div class="col-md-12">
                                <span class="{!! fieldHasError('photo', $errors) !!}" style="font-size: 16px">{!! fieldErrorMessage('photo', $errors) !!}</span>
                                <div class="slim {!! fieldHasError('photo', $errors) !!}" data-ratio="1:1" data-push=true data-size="400,400" data-label="<i class='fa fa-camera fa-4x'></i><br>Click and smile!">
                                    <input type="file" name="photo"/>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Register</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="footer">
            <p><a href="/event" class="btn btn-secondary">Back to Events</a></p>
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
<script type="text/javascript">

    // Form errors - show modal
    if ($('#formerrors').val() == 'personal')
        $('#modal_personal').modal('show');

    display_fields();

    function display_fields() {
        var type = 'Student';

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

    $("#grade_id").change(function () {
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

    // DOB
    $("#wwc_exp").datepicker({
        todayHighlight: !0,
        orientation: "bottom left",
        autoclose: true,
        clearBtn: true,
        format: "{{ session('df-datepicker') }}",
    });

</script>
@stop