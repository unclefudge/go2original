@extends('layouts/main')

@section('content')
    @include('people/_header')

    <div class="row">
        <div class="col-lg-4">
            <div class="m-portlet">
                <div class="m-portlet__body">
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-12"><h4>Overview</h4></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {!! Form::select('event_id', $events, (session('aid') == 2) ? 2 : null, ['class' => 'form-control m-bootstrap-select m_selectpicker', 'id' => 'event_id']) !!}
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-7">First Attended</div>
                        <div class="col text-right" id="attended_first"></div>
                    </div>
                    <div class="row">
                        <div class="col-7">Last Attended</div>
                        <div class="col text-right" id="attended_last"></div>
                    </div>
                    <div class="row">
                        <div class="col-7">Past Month</div>
                        <div class="col text-right" id="attended_month"></div>
                    </div>
                    <div class="row">
                        <div class="col-7">Past Year</div>
                        <div class="col text-right" id="attended_year"></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Activity --}}
        <style>
            .activity-event-link {
                color: #bbb;
            }

            .activity-event-link:hover {
                color: #32c5d2;
            }
        </style>
        <div class="col-lg-8">
            <div class="m-portlet">
                <div class="m-portlet__body">
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-12"><h4>Activity</h4></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="m-accordion m-accordion--bordered" id="activity" role="tablist">
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div id="remove-row">
                            <div class="col-4" style="text-align: center">
                                <br><br><br><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i> Loading...<br><br><br><br>
                            </div>
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
@stop


@section('vendor-scripts')
@stop

@section('page-styles')
    <link href="/css/slim.min.css" rel="stylesheet">
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/js/slim.kickstart.min.js"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    $(document).ready(function () {
        getStats();
        loadData(0);

        $("#event_id").change(function (e) {
            getStats();
        });

        function getStats() {
            $.ajax({
                url: '/stats/event/' + $("#event_id").val() + '/student/' + "{{ $user->id }}",
                method: "GET",
                data: {uid: "{{ $user->id }}", eid: $("#event_id").val()},
                success: function (result) {
                    console.log(result.attended_last);
                    $("#attended_first").html(result.attended_first);
                    if (result.attended_first != 'Never') {
                        $("#attended_last").html(result.attended_last);
                        $("#attended_month").html(result.attended_month);
                        $("#attended_year").html(result.attended_year);
                    } else {
                        $("#attended_last").html('');
                        $("#attended_month").html('');
                        $("#attended_year").html('');
                    }


                },
                error: function (result) {
                    alert("Error loading overview stats. Please try refresh screen");
                }
            });
        }

        $(document).on('click', '#btn-more', function () {
            loadData($(this).data('offset'));
        });

        function loadData(offset) {
            //alert(offset)
            $("#btn-more").html("<i class='fa fa-spinner fa-pulse fa-fw margin-bottom'></i> Loading...");
            $.ajax({
                url: '/data/activity/',
                method: "POST",
                data: {uid: "{{ $user->id }}", offset: offset},
                dataType: "text",
                success: function (data) {
                    if (data != '') {
                        $('#remove-row').remove();
                        $('#activity').append(data);
                    }
                    else {
                        $('#btn-more').html("No Data");
                    }
                }
            });
        }


        $("#avatar").click(function () {
            $("#modal_avatar_edit").modal('show');
        });

        $("#avatar-edit").click(function (e) {
            e.stopPropagation();
            $("#modal_avatar_edit").modal('show');
        });

    });
</script>
@stop
