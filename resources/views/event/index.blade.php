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
                        <button class="dropdown-item" onclick="uSet('show_inactive_events', 0)" id="but_hide_inactive"><i class="fa fa-eye-slash" style="width: 25px"></i> Hide inactive events</button>
                        <button class="dropdown-item" onclick="uSet('show_inactive_events', 1)" id="but_show_inactive"><i class="fa fa-eye" style="width: 25px"></i> Show inactive events</button>
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
                            <div class="kt-portlet kt-portlet--tabs">
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
                                    <div class="pull-right" style="padding: 15px">
                                        <span style="" id="inactive_status"></span>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}
                                    {!! Form::hidden('event_type', 'recur', ['id' => 'event_type']) !!}
                                    {!! Form::hidden('show_archived', session('show_inactive_events'), ['id' => 'show_archived']) !!}
                                    <div id="events_recur">
                                        <div class="row">
                                            @if ($events->where('recur', 1)->count())
                                                @foreach ($events->where('recur', 1) as $event)
                                                    <div class="col-md-4 col-sm-6 {{ ($event->status) ? 'event-active' : 'event-archived' }}">
                                                        <div class="card card-image" style="height: 120px;
                                                                background-size:100% auto; cursor: pointer; {!!  ($event->status) ?   "background-image: linear-gradient(rgba(0, 0, 0, .2), rgba(0, 0, 0, 0.2)), url($event->backgroundMedPath)"  : 'background: #777'!!};
                                                                margin-bottom:20px;" id="event-{{ $event->id }}">
                                                            <div>
                                                                @if (!$event->status)
                                                                    <span class="pull-right" style="color: #FFF; padding: 5px"><i class="fa fa-archive"></i> Inactive</span>
                                                                    <!-- <a href="/event/{{ $event->id }}/del" class="btn text-white waves-effect waves-light pull-right event-del" style="padding: 5px"><i class="fa fa-archive"></i></a>-->
                                                                @else
                                                                    <span class="pull-right" style="padding: 5px">&nbsp;</span>
                                                                @endif
                                                            </div>
                                                            <div class="text-center align-items-center rgba-black-strong">
                                                                <div><h3 class="card-title pt-2 text-white"><strong>{{ $event->name }}</strong></h3></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                No recurring events found.... maybe you should create one.
                                            @endif
                                        </div>
                                    </div>

                                    <div id="events_onetime">
                                        One time events - An unrealised dream...
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

            // Link card to event
            $(".card").click(function () {
                var split = this.id.split("-");
                var id = split[1];
                window.location.href = "/event/" + id;
            });

        });

        function uSetReturn(result, val) {
            if (result)
                show_inactive_events = val;
            display_fields();
        }

        function display_fields() {
            var event_type = $("#event_type").val();
            $('#events_recur').hide();
            $('#events_onetime').hide();

            if (event_type == 'recur')
                $('#events_recur').show();
            else
                $('#events_onetime').show();


            // Hide / Show Inactive events
            $('#but_show_inactive').hide();
            $('#but_hide_inactive').hide();
            if (show_inactive_events == 1) {
                $('#but_hide_inactive').show();
                $('#inactive_status').html('Inactive shown');
            } else {
                $('#but_show_inactive').show();
                $('#inactive_status').html('');
            }
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
