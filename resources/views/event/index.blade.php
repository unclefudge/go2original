@extends('layouts/main')

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col" style="padding-right: 0px">
                    <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x">
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_recur">
                                <i class="fa fa-redo"></i> Recurring Events
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_onetime">
                                <i class="fa fa-calendar"></i> One-Time Events
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-2 col-sm-4 col-xs-6" style="padding-left: 0px">
                    <button type="button" class="btn btn-secondary btn-sm m-btn--pill pull-right" style="margin-left: 10px" id="but_show_archived"><i class="fa fa-eye"></i></button>
                    <button type="button" class="btn btn-sm m-btn--pill pull-right" style="margin-left: 10px; color: #000000; background: #eee" id="but_hide_archived"><i class="fa fa-eye-slash" style="padding-right: 5px"></i> Hide Inactive</button>

                    <button type="button" class="btn btn-sm m-btn--pill btn-brand pull-right" data-toggle="modal" data-target="#modal_create_event">Add</button>
                    <hr class="d-none d-md-block" style="padding-top: 20px; margin-top: 48px">
                </div>
            </div>
            {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}
            {!! Form::hidden('event_type', 'recur', ['id' => 'event_type']) !!}
            {!! Form::hidden('show_archived', '0', ['id' => 'show_archived']) !!}

            <style>
                .event-del {
                    color: #fff;
                }

                .event-del:hover {
                    color: red !important;
                }

                .m-widget27 .m-widget27__pic > img {
                    width: 100%;
                    height: 120px;
                }

                .m-widget27 .m-widget27__container {
                    margin-top: 0.5rem;
                    width: 100%;
                    padding: 1rem 0.5rem 0 0.5rem;
                }

                .event-hide {
                    display: none;
                }

            </style>

            <div id="events_recur">
                <div class="row">
                    <?php $bl = [1, 2, 3, 4, 5] ?>
                    {{-- @foreach ($bl as $event) --}}
                    @foreach ($events->where('recur', 1) as $event)
                        <div class="col-md-4 col-sm-6 {{ ($event->status) ? 'event-active' : 'event-archived event-hide' }}">
                            <div class="m-portlet m-portlet--head-overlay m-portlet--full-height  m-portlet--rounded-force">
                                <div class="card card-image" style="height: 120px; background-size:100% auto; cursor: pointer; {!!  ($event->status) ?   "background-image: linear-gradient(rgba(0, 0, 0, .2), rgba(0, 0, 0, 0.2)), url($event->backgroundMedPath)"  : 'background: #777'!!}" id="event-{{ $event->id }}">
                                    <div>
                                        @if (!$event->status)
                                            <span class="pull-right" style="color: #FFF; padding: 5px"><i class="fa fa-archive"></i> Inactive</span>
                                            <!-- <a href="/event/{{ $event->id }}/del" class="btn text-white waves-effect waves-light pull-right event-del" style="padding: 5px"><i class="fa fa-archive"></i></a>-->
                                        @else
                                            <span class="pull-right" style="padding: 5px">&nbsp;</span>
                                        @endif
                                    </div>
                                    <div class="text-white text-center align-items-center rgba-black-strong">
                                        <div>
                                            <h3 class="card-title pt-2"><strong>{{ $event->name }}</strong></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center" style="padding: 10px;">
                                    @if ($event->status)
                                        <div class="col-lg-6">
                                            <a href="/checkin/{{ $event->id }}" class="btn btn-accent m-btn btn-block"><i class="fa fa-sign-in-alt"></i> &nbsp; Check-In</a>
                                        </div>
                                    @else
                                        <div class="col-12 text-center" style="padding: 10px">Unable to checkin on inactive events</div>
                                    @endif

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

    @include('event/_create_event')
@stop


@section('vendor-scripts')
    <script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
@stop

@section('page-styles')
    <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // Form errors - show modal
    if ($('#formerrors').val() == 'event')
        $('#modal_create_event').modal('show');

    $(document).ready(function () {

        display_fields();

        function display_fields() {
            var event_type = $("#event_type").val();
            $('#events_recur').hide();
            $('#events_onetime').hide();

            if (event_type == 'recur') {
                $('#events_recur').show();
            } else {
                $('#events_onetime').show();
            }

            $('#but_show_archived').hide();
            $('#but_hide_archived').hide();

            var show_archived = $("#show_archived").val();
            if (show_archived == 0) {
                $('#but_show_archived').show();
            } else
                $('#but_hide_archived').show();


        }

        $("#type_recur").click(function () {
            $("#event_type").val('recur');
            display_fields();
        });

        $("#type_onetime").click(function () {
            $("#event_type").val('onetime');
            display_fields();
        });

        // Show Inactive
        $("#but_show_archived").click(function () {
            $("#show_archived").val(1);

            var archived_events = document.getElementsByClassName('event-archived');
            for (var i = 0; i < archived_events.length; ++i) {
                var item = archived_events[i];
                item.classList.toggle("event-hide");
            }
            display_fields();
        });
        // Hide Inactive
        $("#but_hide_archived").click(function () {
            $("#show_archived").val(0);
            var archived_events = document.getElementsByClassName('event-archived');
            for (var i = 0; i < archived_events.length; ++i) {
                var item = archived_events[i];
                item.classList.toggle("event-hide");
            }
            display_fields();
        });

        // Hide Create Event Save button until Name + Frequency selected
        $("#name").change(function () {
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
            window.location.href = "/event/" + id;
        });

    });

</script>
@stop