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
                        <div class="col-lg-6">
                            {{-- Overview --}}
                            <div class="kt-portlet">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Overview</h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar"></div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="row">
                                        <div class="col-12">
                                            {!! Form::select('event_id', $events, (session('aid') == 2) ? 2 : null, ['class' => 'form-control kt-selectpicker', 'id' => 'event_id']) !!}
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
                        {{-- Something else --}}
                        {{--}}
                        <div class="col-lg-6">
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-portlet__body">
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    {{-- Activity List --}}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Activity</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="accordion accordion-toggle-plus" id="activity" role="tablist">
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
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
    <script src="/js/people-shared-functions.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        $(document).ready(function () {
            getStats();
            loadData(0);

            $("#event_id").change(function (e) {
                getStats();
            });

            function getStats() {
                if ($("#event_id").val()) {
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
        });
    </script>
@endsection
