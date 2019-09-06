@extends('layout/main')

@section('bodystyle')
    style="background-image: url(/img/head-purple.jpg)"
@endsection

@section('subheader')
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Events</h3>
            <h4 class="kt-subheader__desc">Events & check-ins</h4>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal_create_event">Add Event</a>
                <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="">
                    <a href="#" class="btn kt-subheader__btn-secondary kt-subheader__btn-options" style="padding: 1.4rem 1rem;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" style="font-size: 18px !important; color: #fff"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button class="dropdown-item" onclick="uSet('show_inactive_events', 0)" id="but_hide_archived"><i class="fa fa-eye-slash" style="width: 25px"></i> Hide Inactive Events</button>
                        <button class="dropdown-item" onclick="uSet('show_inactive_events', 1)" id="but_show_archived"><i class="fa fa-eye" style="width: 25px"></i> Show inactive events</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('event/_create_event')
@endsection
@section('content')
    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="container-fluid">
            <div class="row">
                @include('event/_sidebar')
                {{-- Main Content --}}
                <div class="col" style="height: 100% !important; min-height: 100% !important;">
                    @include('event/_sidebar-mobile')
                    <div class="row">
                        <div class="col">
                            {{-- Event List --}}
                            <div class="kt-portlet kt-portlet--tabs" style="height: 100%; min-height: height: 100%">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-toolbar">
                                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-primary nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold">
                                            <li class="nav-item">
                                                <a class="nav-link active show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_recur">
                                                    <i class="fa fa-redo"></i> Recurring Events
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_onetime">
                                                    <i class="fa fa-calendar"></i> One-Time Events
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}
                                    {!! Form::hidden('event_type', 'recur', ['id' => 'event_type']) !!}
                                    {!! Form::hidden('show_archived', session('show_inactive_events'), ['id' => 'show_archived']) !!}
                                    <div id="events_recur">
                                        <div class="row">
                                            @foreach ($events->where('recur', 1)->where('status', 1) as $event)
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="card" style="height: 120px;
                                                            background-size:100% auto; cursor: pointer; {!!  ($event->status) ?   "background-image: linear-gradient(rgba(0, 0, 0, .2), rgba(0, 0, 0, 0.2)), url($event->backgroundMedPath)"  : 'background: #777'!!};
                                                            margin-bottom:20px;" id="event-{{ $event->id }}">
                                                        <div class="row no-gutters">
                                                            <div class="col-auto" style="height: 120px">
                                                                <img src="/img/evt-checkin.jpg" class="img-fluid" alt="">
                                                            </div>
                                                            <div class="col text-center align-items-center rgba-black-strong" style="padding: 30px 30px 30px 0px; color: #FFF;">
                                                                <div class="card-block px-2">
                                                                    <h3 class="card-title pt-2 text-white"><strong>{{ $event->name }}</strong></h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div id="events_onetime">
                                        One time
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-styles')
@endsection

@section('vendor-scripts')
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
    <script type="text/javascript">
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        // Form errors - show modal
        if ($('#formerrors').val() == 'event')
            $('#modal_create_event').modal('show');

        var show_inactive_events = "{{ session('show_inactive_events') }}";

        $(document).ready(function () {

            display_fields();

            $("#type_recur").click(function () {
                $("#event_type").val('recur');
                display_fields();
            });

            $("#type_onetime").click(function () {
                $("#event_type").val('onetime');
                display_fields();
            });

            // Hide Create Event Save button until Name + Frequency selected
            $("#name").keyup(function () {
                $("#but_create_event").hide();
                if ($("#name").val() != '' && $("#frequency").val() != '')
                    $("#but_create_event").show();
            });
            $("#frequency").change(function () {
                $("#but_create_event").hide();
                if ($("#name").val() != '' && $("#frequency").val() != '')
                    $("#but_create_event").show();
            });

            $(".card").click(function () {
                var split = this.id.split("-");
                var id = split[1];
                window.location.href = "/checkin/" + id;
            });

        });

        function display_fields() {
            var event_type = $("#event_type").val();
            $('#events_recur').hide();
            $('#events_onetime').hide();

            if (event_type == 'recur')
                $('#events_recur').show();
            else
                $('#events_onetime').show();

            // Hide / Show Inactive events
            $('#but_show_archived').hide();
            $('#but_hide_archived').hide();
            if (show_inactive_events == 1)
                $('#but_hide_archived').show();
            else
                $('#but_show_archived').show();
            var archived_events = document.getElementsByClassName('event-archived');
            for (var i = 0; i < archived_events.length; ++i) {
                var item = archived_events[i];
                if (show_inactive_events == 1)
                    item.classList.remove("kt-hide");
                else
                    item.classList.add("kt-hide");
            }
        }
    </script>
@endsection
