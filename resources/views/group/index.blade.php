@extends('layout/main')

@section('bodystyle')
    style="background-image: url(/img/head-purple.jpg)"
@endsection

@section('subheader')
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Groups</h3>
            <h4 class="kt-subheader__desc">Some groups are smart, some are dumb</h4>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal_create_group">Add Goup</a>
                <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="">
                    <a href="#" class="btn kt-subheader__btn-secondary kt-subheader__btn-options" style="padding: 1.4rem 1rem;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" style="font-size: 18px !important; color: #fff"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button class="dropdown-item" onclick="uSet('show_inactive_groups', 0)" id="but_hide_archived"><i class="fa fa-eye-slash" style="width: 25px"></i> Hide inactive groups</button>
                        <button class="dropdown-item" onclick="uSet('show_inactive_groups', 1)" id="but_show_archived"><i class="fa fa-eye" style="width: 25px"></i> Show inactive events</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('event/_create_event')
@endsection

@section('content')
    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="container-fluid">
            {{-- Dashboard --}}
            <div class="kt-portlet" style="height: 100%; min-height: 100%;">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h4 class="kt-portlet__head-title">Groups</h4>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div>
                       It's a dream yet unrealised
                        <br><br> <br><br> <br><br> <br><br> <br><br> <br><br> <br><br> <br><br>
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
