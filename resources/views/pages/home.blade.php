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
        .dashbutt{
            position:relative;
            overflow:hidden;
            /*padding-bottom:100%;*/
        }
        .dashbutt dashimg{
            position: absolute;
            max-width: 100%;
            max-height: 100%;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
        }
    </style>
    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="container-fluid">
            <div class="row">

                {{-- Checkin button --}}
                <div class="col-6 col-md-3">
                    <div class="kt-portlet">
                        <a href="/checkin" class="thumbnail">
                            <div class="kt-portlet__body kt-portlet__body--fluid">
                                <div class="dashbutt d-block d-md-none"> {{--- mobile only --}}
                                    <img src="/img/button-checkin.png" class="dashimg img-fluid full-width" />
                                </div>
                                <div class="d-none d-md-block">
                                    <h2><i class="flaticon2-laptop" style="font-size:40px; padding-right: 30px"></i>Checkin</h2>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- Events button --}}
                <div class="col-6 col-md-3">
                    <div class="kt-portlet">
                        <a href="/event" class="thumbnail">
                            <div class="kt-portlet__body kt-portlet__body--fluid">
                                <div class="dashbutt d-block d-md-none"> {{--- mobile only --}}
                                    <img src="/img/button-events.png" class="dashimg img-fluid full-width" />
                                </div>
                                <div class="d-none d-md-block">
                                    <h2><i class="flaticon2-calendar-4" style="font-size:40px; padding-right: 30px"></i>Events</h2>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- People button --}}
                <div class="col-6 col-md-3">
                    <div class="kt-portlet">
                        <a href="/people" class="thumbnail">
                            <div class="kt-portlet__body kt-portlet__body--fluid">
                                <div class="dashbutt d-block d-md-none"> {{--- mobile only --}}
                                    <img src="/img/button-people.png" class="dashimg img-fluid full-width" />
                                </div>
                                <div class="d-none d-md-block">
                                    <h2><i class="fa fa-user" style="font-size:40px; padding-right: 30px"></i>People</h2>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- Groups button --}}
                <div class="col-6 col-md-3">
                    <div class="kt-portlet">
                        <a href="/group" class="thumbnail">
                            <div class="kt-portlet__body kt-portlet__body--fluid">
                                <div class="dashbutt d-block d-md-none"> {{--- mobile only --}}
                                    <img src="/img/button-groups.png" class="dashimg img-fluid full-width" />
                                </div>
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
                    <div class="row">
                        <div class="col-12">An unrealised dream...</div>
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

        $(document).ready(function () {

        });
    </script>
@endsection
