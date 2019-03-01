@extends('layouts/main')

@section('content')
    @include('event/_header')

    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <div class="row">
        <div class="col-12">
            <div class="m-portlet">
                <div class="m-portlet__body">
                    {{-- Rego Form Info --}}
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-10">
                            <h4>Weekly Totals</h4>
                        </div>
                        <div class="col-2">
                            @if ($event->status)
                                <a href="#" class="pull-right" data-toggle="modal" data-target="#modal_personal">Edit</a>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="chart-weekly-totals" style="height: 250px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-12 col-sm-12">

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
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="/js/event-shared-functions.js" type="text/javascript"></script>
<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    var weekly_totals = new Morris.Bar({
        element: 'chart-weekly-totals',
        data: [0, 0, 0],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B']
    });

    // Fire off an AJAX request to load the data
    $.ajax({
                type: "POST",
                dataType: 'json',
                url: "/stats/event/weekly-totals",
                data: { eid: 2, days: 7 }
            })
            .done(function( data ) {
                // When the response to the AJAX request comes back render the chart with new data
                weekly_totals.setData(data);
            })
            .fail(function() {
                // If there is no communication between the server, show an error
                alert( "error occured" );
            });


</script>
@stop
