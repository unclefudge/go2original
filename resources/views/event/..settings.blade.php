@extends('layouts/main')

@section('content')
    @include('event/_header')

    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}
    @include('form-error')

    <div class="row">
        <div class="col-md-3 col-xs-12 col-sm-4">
            {{-- Background --}}
            @include('event/_show_background')
        </div>
        <div class="col-md-9 col-xs-12 col-sm-8">
            {{-- Settings --}}
            @include('event/_show_settings')
        </div>
    </div>

    <div class="row">
        <div class="col">
            {{-- Rego Form --}}
            @include('event/_show_regoform')
        </div>
    </div>

    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $event->displayUpdatedBy() !!}
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
<script src="/js/event-shared-functions.js" type="text/javascript"></script>
<script type="text/javascript">
    // Form errors - show modal
    if ($('#formerrors').val() == 'event') {
        toastr.error('Failed to save event', 'Errors!', {timeOut: 9000})
    }

    $("#bg_image").click(function () {
        $("#modal_background").modal('show');
    });

</script>
@stop
