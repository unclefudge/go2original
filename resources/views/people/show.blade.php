@inject('ozstates', 'App\Http\Utilities\Ozstates')
@extends('layouts/main')

@section('subheader')
    {{--}}
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">People</h3>
            </div>
            <div>
            <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
            </div>
        </div>
    </div>--}}
@stop

@section('content')
    <div class="member-bar">
        <!--<i class="fa fa-user ppicon-user-member-bar" style="font-size: 80px; opacity: .5; padding:5px"></i>-->
        {{--}}
        <div class="m-card-profile">
            <div class="m-card-profile__title m--hide">Your Profile</div>
            <div class="m-card-profile__pic">
                <div class="m-card-profile__pic-wrapper">
                    <img src="/assets/app/media/img/users/user4.jpg" alt="">
                </div>
            </div>
            <div class="m-card-profile__details">
                <span class="m-card-profile__name">Mark Andre</span>
                <a href="" class="m-card-profile__email m-link">mark.andre@gmail.com</a>
            </div>
        </div>
        --}}
        <i class="iicon-user-member-bar hidden-xs-down"></i>
        <div class="member-name">
            <div class="member-fullname">{{ $people->firstname }} {{ $people->lastname }}</div>
            <span class="member-number">{{ $people->type }}</span>
            <span class="member-split">&nbsp;|&nbsp;</span>
            <span class="member-number">{{ ($people->type == 'Student') ? "Grade $people->grade" : '' }} {!! (!$people->status) ? 'ACTIVE' : '<span class="label label-sm label-danger">ARCHIVED</span>' !!}</span>
            <!--<a href="/reseller/member/member_account_status/?member_id=8013759" class="member-status">Active</a>-->
        </div>

        <ul class="member-bar-menu">
            <li class="member-bar-item "><i class="iicon-profile"></i><a class="member-bar-link" href="/user/" title="Profile">PROFILE</a></li>

            <li class="member-bar-item "><i class="iicon-document"></i><a class="member-bar-link" href="/user//doc" title="Documents">
                    <span class="d-none d-md-block">DOCUMENTS</span><span class="d-md-none">DOCS</span></a></li>

            <li class="member-bar-item "><i class="iicon-lock"></i><a class="member-bar-link" href="/user//security" title="Security">SECURITY</a></li>
        </ul>
    </div>

    {{--}}@include('people/_header')--}}

    <div class="row">
        {{--}}
        <div class="col-lg-6 col-xs-12 col-sm-12">
            @include('people/_show-contact')
        </div>--}}


        <div class="col-lg-9 col-xs-12 col-sm-12">
            {{-- Personal Info --}}
            @include('people/_show_personal')
            @include('people/_edit_personal')

        </div>
        <div class="col-lg-3 col-xs-12 col-sm-12">
            {{-- Houshold --}}
            @include('people/_show_household')

        </div>

    </div>

    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $people->displayUpdatedBy() !!}
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


    // Modal Popup on return Form Errors
            @if (count($errors) > 0)
    var errors = {!! $errors !!};
    if (errors.FORM == 'profile') {
        $('#modal_profile').modal('show');
    }
    @endif
</script>
@stop
