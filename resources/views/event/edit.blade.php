@extends('layouts/main')

@section('content')

    @include('event/_header')

    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <div class="row">
        <div class="col-12">
yy
        </div>
    </div>

@stop


@section('vendor-scripts')
@stop

@section('page-styles')
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
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

    }

    $("#type").change(function () {
        display_fields();
    });

    $("#grade").change(function () {
        display_fields();
    });

    $('.date-picker').datepicker({
        autoclose: true,
        clearBtn: true,
        format: 'dd/mm/yyyy',
    });


</script>
@stop
