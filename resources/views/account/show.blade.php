@inject('ozstates', 'App\Http\Utilities\Ozstates')
@inject('timezones', 'App\Http\Utilities\Timezones')
@extends('layouts/main')

@section('content')
    <div class="member-bar">
        <i class="iicon-user-member-bar hidden-xs-down"></i>
        <div class="member-name">
            <div class="member-fullname">{{ $account->name }}</div>
            <span class="member-number">ACCount ID #{{ $account->id }}</span>
            <span class="member-split">&nbsp;|&nbsp;</span>
            <span class="member-number">Active</span>
        </div>
<!--
        <ul class="member-bar-menu">
            <li class="member-bar-item "><i class="iicon-profile"></i><a class="member-bar-link" href="/user/" title="Profile">PROFILE</a></li>

            <li class="member-bar-item "><i class="iicon-document"></i><a class="member-bar-link" href="/user//doc" title="Documents">
                    <span class="d-none d-md-block">DOCUMENTS</span><span class="d-md-none">DOCS</span></a></li>

            <li class="member-bar-item "><i class="iicon-lock"></i><a class="member-bar-link" href="/user//security" title="Security">SECURITY</a></li>
        </ul>
        -->
    </div>

    {{--}}@include('people/_header')--}}

    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <div class="row">
        {{--}}
        <div class="col-lg-6 col-xs-12 col-sm-12">
            @include('people/_show-contact')
        </div>--}}


        <div class="col-lg-6 col-xs-12 col-sm-12">
            {{-- Personal Info --}}
            @include('account/_show_account')
            @include('account/_edit_account')

        </div>
        <div class="col-lg-6 col-xs-12 col-sm-12">
            {{-- Houshold --}}
            {{--}}
            @include('people/_show_household')
            @include('people/_edit_household')--}}

        </div>

    </div>

    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $account->displayUpdatedBy() !!}
        </div>
    </div>

@stop


@section('vendor-scripts')
@stop

@section('page-styles')
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script type="text/javascript">

    // Form errors - show modal
    if ($('#formerrors').val() == 'personal')
        $('#modal_personal').modal('show');
</script>
@stop
