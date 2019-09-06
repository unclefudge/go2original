@extends('layout/main')

@section('bodystyle')
    style="background-image: url(/img/head-purple.jpg)"
@endsection

@section('subheader')
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Dashboard</h3>
            <h4 class="kt-subheader__desc">Event & attendance summary</h4>
        </div>
    </div>
    @include('event/_create_event')
@endsection

@section('content')
    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <style>
        .image{
            position:relative;
            overflow:hidden;
            padding-bottom:100%;
        }
        .image img{
            position:absolute;
        }
    </style>
    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="container-fluid">
            <div class="row">

                <div class="col-6 col-md-3">
                    <div class="kt-portlet">
                        <a href="/checkin" class="thumbnail">
                            <div class="kt-portlet__body kt-portlet__body--fluid">
                                <div class="image d-block d-md-none"> {{--- mobile only --}}
                                    <img src="/img/button-checkin.png" class="img img-responsive full-width" />
                                </div>
                                <div class="d-none d-md-block">
                                    <h2><i class="flaticon2-laptop" style="font-size:40px; padding-right: 30px"></i>Checkin</h2>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kt-portlet">
                        <a href="/event">
                            <div class="kt-portlet__body kt-portlet__body--fluid">
                                <img src="/img/button-events.png" class="img-fluid d-block d-md-none"> {{--- mobile only --}}
                                <div class="d-none d-md-block">
                                    <h2><i class="flaticon2-calendar-4" style="font-size:40px; padding-right: 30px"></i>Events</h2>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kt-portlet">
                        <a href="/people">
                            <div class="kt-portlet__body kt-portlet__body--fluid">
                                <img src="/img/button-people.png" class="img-fluid d-block d-md-none"> {{--- mobile only --}}
                                <div class="d-none d-md-block">
                                    <h2><i class="fa fa-user" style="font-size:40px; padding-right: 30px"></i>People</h2>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="kt-portlet">
                        <a href="/group">
                            <div class="kt-portlet__body kt-portlet__body--fluid">
                                <img src="/img/button-groups.png" class="img-fluid d-block d-md-none"> {{--- mobile only --}}
                                <div class="d-none d-md-block">
                                    <h2><i class="fa fa-users" style="font-size:40px; padding-right: 30px"></i>Groups</h2>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>


            </div>

            {{-- Dashboard --}}
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h4 class="kt-portlet__head-title">Summary</h4>
                    </div>
                </div>
                <div class="kt-portlet__body">
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

        $(document).ready(function () {

        });
    </script>
@endsection
