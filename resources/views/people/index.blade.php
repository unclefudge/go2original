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
        {{--}}<img class="rounded-circle hidden-xs" src="/assets/app/media/img/users/user4.jpg" alt="">--}}
        <div class="member-name">
            <div class="member-fullname">Fudge Jordan</div>
            <span class="member-number">User ID #1</span>
            <span class="member-split">&nbsp;|&nbsp;</span>
            <span class="member-number">{!! (true) ? 'ACTIVE' : '<span class="label label-sm label-danger">INACTIVE</span>' !!}</span>
            <!--<a href="/reseller/member/member_account_status/?member_id=8013759" class="member-status">Active</a>-->
        </div>

        <ul class="member-bar-menu">
            <li class="member-bar-item "><i class="iicon-profile"></i><a class="member-bar-link" href="/user/" title="Profile">PROFILE</a></li>

            <li class="member-bar-item "><i class="iicon-document"></i><a class="member-bar-link" href="/user//doc" title="Documents">
                    <span class="d-none d-md-block">DOCUMENTS</span><span class="d-md-none">DOCS</span></a></li>

            <li class="member-bar-item "><i class="iicon-lock"></i><a class="member-bar-link" href="/user//security" title="Security">SECURITY</a></li>
        </ul>
    </div>

    <div>
        @foreach($people as $p)
            <div class="row">
                <a href="/people/{{$p->id}}">{{ $p->firstname }} {{ $p->lastname }}</a>
            </div>
        @endforeach

    </div>

@stop


@section('vendor-scripts')
@stop

@section('page-styles')
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}

@stop
