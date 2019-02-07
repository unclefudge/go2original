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
                <div class="col-1" style="padding-left: 0px">
                    <button type="button" class="btn btn-sm m-btn--pill btn-brand pull-right" data-toggle="modal" data-target="#modal_create_event">Add</button>
                    <hr class="d-none d-md-block" style="padding-top: 20px; margin-top: 48px">
                </div>
            </div>
            {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}
            {!! Form::hidden('event_type', 'recur', ['id' => 'event_type']) !!}

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

            </style>

            <div id="events_recur">
                <div class="row">
                    <?php $bl = [1, 2, 3, 4, 5] ?>
                    {{-- @foreach ($bl as $event) --}}
                    @foreach ($events->where('recur', 1) as $event)

                        <div class="col-md-4 col-sm-6">
                            <div class="m-portlet m-portlet--head-overlay m-portlet--full-height  m-portlet--rounded-force">
                                <div class="card card-image" style="height: 120px; background-image: url('/img/bg-event3.jpg'); background-size:100% auto; cursor: pointer" id="event-{{ $event->id }}">
                                    <div>
                                        <a href="/event/{{ $event->id }}/del" class="btn text-white waves-effect waves-light pull-right event-del" style="padding: 5px"><i class="fa fa-trash-alt"></i></a>
                                    </div>
                                    <div class="text-white text-center align-items-center rgba-black-strong">
                                        <div>
                                            <h3 class="card-title pt-2"><strong>{{ $event->name }}</strong></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="padding: 10px;">
                                    <div class="col-6">
                                        <a href="/checkin/{{ $event->id }}" class="btn btn-accent m-btn btn-block">Check-In</a>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline-accent m-btn btn-block">Stats</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


                <div class="row">
                    <?php $bl = [1, 2, 3, 4, 5] ?>
                    {{-- @foreach ($bl as $event) --}}
{{--}}
                    @foreach ($events->where('recur', 1) as $event)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <!--begin:: Widgets/Blog-->
                            <div class="m-portlet m-portlet--head-overlay m-portlet--full-height  m-portlet--rounded-force">
                                <a href="/event/{{ $event->id }}">
                                    <div class="m-portlet__head m-portlet__head--fit-" id="event_{{ $event->id }}" style="cursor: pointer;">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <h3 class="m-portlet__head-text m--font-light"></h3>
                                            </div>
                                        </div>
                                        <div class="m-portlet__head-tools">
                                            <ul class="m-portlet__nav">
                                                <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">
                                                    <a href="#" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl">
                                                        <i class="la la-cog m--font-light" style="font-size: 1.8rem"></i>
                                                    </a>
                                                    <div class="m-dropdown__wrapper" style="z-index: 101;">
                                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 14.5px;"></span>
                                                        <div class="m-dropdown__inner">
                                                            <div class="m-dropdown__body">
                                                                <div class="m-dropdown__content">
                                                                    <ul class="m-nav">
                                                                        <li class="m-nav__section m-nav__section--first">
                                                                            <span class="m-nav__section-text">Quick Actions</span>
                                                                        </li>
                                                                        <li class="m-nav__item">
                                                                            <a href="/event/{{ $event->id }}/edit" class="m-nav__link">
                                                                                <i class="m-nav__link-icon flaticon-interface-1"></i>
                                                                                <span class="m-nav__link-text">Settings</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="m-nav__item">
                                                                            <a href="" class="m-nav__link">
                                                                                <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                                <span class="m-nav__link-text">Messages</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="m-nav__separator m-nav__separator--fit">
                                                                        </li>
                                                                        <li class="m-nav__item">
                                                                            <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">Cancel</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </a>
                                <div class="m-portlet__body">
                                    <div class="m-widget27 m-portlet-fit--sides">
                                        <div class="m-widget27__pic">
                                            <img src="/assets/app/media/img//bg/bg-4.jpg" alt="" style="cursor: pointer;">
                                            <h2 class="m-widget27__title m--font-light"></h2>
                                            <!--<div class="m-widget27__btn">
                                                <button type="button" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--bolder">Inclusive All Earnings</button>
                                            </div>-->
                                        </div>
                                        <div class="m-widget27__container">
                                            <div class="row">
                                                <div class="col-4">
                                                    <button type="button" class="btn btn-accent">Check-In</button>
                                                </div>
                                                <div class="col-4">
                                                    <button type="button" class="btn btn-secondary">Stats</button>
                                                </div>

                                                <div class="col-4">
                                                    <button type="button" class="btn btn-outline-accent m-btn">Stats</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    --}}
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
        }

        $("#type_recur").click(function () {
            $("#event_type").val('recur');
            display_fields();
        });

        $("#type_onetime").click(function () {
            $("#event_type").val('onetime');
            display_fields();
        });

        $(".card").click(function () {
            var split = this.id.split("-");
            var id = split[1];
            window.location.href = "/event/"+id;
        });

        $('.date-picker').datepicker({
            autoclose: true,
            clearBtn: true,
            format: 'dd/mm/yyyy',
        });

    });

</script>
@stop