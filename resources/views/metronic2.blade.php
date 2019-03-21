@extends('layout/main')

<?php $people = \App\User::find(67); ?>
@section('headimage')
    @if ($people->status)
        style="background-image: url(/massets/demo/demo4/media/bg/header.jpg)"
    @else
        style="background-image: url(/img/wallpapers/purple-abstract.png)"
    @endif
@endsection

@section('subheader')
    <style>
        .mbar {
            padding: 0;
            overflow: hidden;
            color: #fff;
            position: relative;
        }

        /* mBar Name */
        .mbar .mbar-name {
            width: 620px;
            margin: 20px 0 16px 90px;
            display: inline-block;
            text-transform: uppercase;
            /*background: #ff0000;*/
        }

        .mbar-bar .mbar-name a {
            color: #fff;
            font-size: 30px;
        }

        .mbar-bar .mbar-name a:hover {
            color: #fff;
        }

        .mbar .mbar-name a.mbar-status {
            font-size: 13px;
            opacity: 0.7;
        }

        .mbar .mbar-name a.mbar-status:hover {
            opacity: 1;
        }

        .mbar .mbar-name .mbar-type, .mbar .mbar-name .mbar-split {
            display: inline-block;
            font-weight: normal;
            font-size: 1.2rem;
            opacity: 0.6;
        }

        .mbar-fullname {
            font-weight: normal;
            font-size: 26px;
            text-transform: none;
        }

        /*
         * Avatar
         */

        /* Container needed to position the overlay. Adjust the width as needed */
        .mbar .mbar-name .avatar-container {
            position: relative;
            width: 90px;
            margin: -20px 20px -20px -90px; /* margin: 0 20px 0 -80px; */
            cursor: pointer;
            float: left;
        }

        /* The overlay effect (full height and width) - lays on top of the container and over the image */
        .mbar .mbar-name .avatar-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            width: 96px;
            opacity: 0;
            transition: .3s ease;
            background: rgb(0, 0, 0);
            background: rgba(0, 0, 0, 0.3); /* Black see-through */
            border-radius: 50%;
        }

        /* When you mouse over the container, fade in the overlay icon*/
        .mbar .mbar-name .avatar-container:hover .avatar-overlay {
            opacity: 1;
        }

        .mbar .mbar-name .avatar-image {
            height: 96px;
            border-radius: 50%;
        }

        .mbar .mbar-name .avatar-edit:hover {
            color: #FF0000;
            background: rgb(0, 0, 0);
            background: rgba(0, 0, 0, 0.3); /* Black see-through */
        }

        .mbar .mbar-name .avatar-icon {
            position: absolute;
            bottom: 1px;
            right: 1px;
        }

        /* Mbar Menu (right side) */
        .mbar .mbar-menu {
            padding: 0;
            float: right;
            margin: 0px;
            background: #ff0000;;
        }

        .mbar .mbar-actions {
            margin: 20px 20px 10px 0px;
            display: inline-block;
            text-transform: uppercase;
        }

        /* Small devices */
        @media screen and (max-width: 625px) {
            .mbar {
                overflow: hidden;
                margin-left: 25%;
            }

            .mbar .mbar-name {
                margin: 20px auto 16px;
                display: block;
                text-align: center;
                width: auto;
            }

            .mbar .mbar-name .avatar-container {
                display: block;
                float: none;
                margin: 0px;
                width: auto;
                text-align: center;
            }

            .mbar .mbar-name .avatar-overlay {
                left: 50%;
                right: 50%;
                width: auto;
            }

            .mbar .mbar-name .avatar-icon {
                position: absolute;
                top: 60px;
                left: 12px
            }

            .mbar .mbar-menu {
                width: 100%;
                position: relative;
                z-index: 2;
                text-align: center !important; /* attempt to center all content */
            }

            .mbar .mbar-actions {
                margin: 0px;
            }

            .mbar .mbar-info {
                margin: 10px 0px;
            }
        }

        @media screen and (max-width: 991px) {
            .mbar .mbar-name {
                width: 530px;
            }
        }

        @media screen and (max-width: 920px) {
            .mbar .mbar-name {
                width: 430px;
            }
        }

        @media screen and (max-width: 780px) {
            .mbar .mbar-name {
                width: 270px;
            }
        }

        @media screen and (max-width: 480px) {
            .mbar .mbar-name {
                padding: 0 20px;
            }
        }

    </style>

    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">

            {{-- Member bar --}}
            <div class="mbar">
                <!--<i class="iicon-user-member-bar hidden-xs-down"></i>-->
                <div class="mbar-name">
                    {{-- Avatar --}}
                    <div class="avatar-container" id="avatar">
                        <img class="avatar-image" src="{{ $people->photoSmPath }}?<?=rand(1, 32000)?>" alt="Avatar">
                        <div class="avatar-overlay">
                            <a href="#" class="avatar-edit" title="Edit" id="avatar-edit"><img class="avatar-icon" src="/img/icon-edit-avatar.png" height="35px"></a>
                        </div>
                    </div>
                    <div class="mbar-fullname">{{ $people->firstname }} {{ $people->lastname }}</div>
                    <span class="mbar-type">{{ $people->type }}</span>
                    <span class="mbar-split">&nbsp;|&nbsp;</span>

                    {{-- Status --}}
                    <span class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" style="padding: 1px 1px 1px 8px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ ($people->status) ? 'Active' : 'Inactive' }}</button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ ($people->status) ? '#' : "/people/$people->id/status/1" }}">Active</a>
                            <a class="dropdown-item" href="{{ (!$people->status) ? '#' : "/people/$people->id/status/0" }}">Inactive</a>
                            @if (!$people->status)
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" id="but_del_person">Delete</a>
                            @endif
                        </div>
                    </span>
                </div>
            </div>
        </div>

        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="#" class="btn kt-subheader__btn-secondary">Reports</a>
                <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="Quick actions" data-placement="top">
                    <a href="#" class="btn btn-danger kt-subheader__btn-options" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Products</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#"><i class="la la-plus"></i> New Product</a>
                        <a class="dropdown-item" href="#"><i class="la la-user"></i> New Order</a>
                        <a class="dropdown-item" href="#"><i class="la la-cloud-download"></i> New Download</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"><i class="la la-cog"></i> Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="kt-content kt-grid__item kt-grid__item--fluid">

        <div class="container-fluid">
            <div class="row">
                <div class="col left-sidebar-menu">
                    {{-- Sidebar --}}
                    <div id="sidebar-wrapper">
                        <br>
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">Dashboard</a>
                            <a href="#" class="list-group-item list-group-item-action ">Shortcuts</a>
                            <a href="#" class="list-group-item list-group-item-action">Overview</a>
                            <a href="#" class="list-group-item list-group-item-action">Events</a>
                            <a href="#" class="list-group-item list-group-item-action">Profile</a>
                            <a href="#" class="list-group-item list-group-item-action">Status</a>
                        </div>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="col">


                    <div class="alert alert-light alert-elevate" role="alert" id="left-sidebar-mobile">
                        <div class="alert-text">
                            {!! Form::select('state',['menu1', 'menu2', 'menu3'], 'TAS', ['class' => 'form-control kt-selectpicker']) !!}
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-lg-8">
                            <div class="kt-content kt-grid__item kt-grid__item--fluid">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="kt-portlet kt-portlet--height-fluid kt-portlet--mobile ">
                                            <div class="kt-portlet__body">
                                                hjgjhgjhgjh
                                                1<br><br><br><br>
                                                2<br><br><br><br>
                                                3<br><br><br><br>
                                                1<br><br><br><br>
                                                5<br><br><br><br>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="kt-portlet kt-portlet--tab">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Divider</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <!--begin::Section-->
                                    <div class="kt-section">
                                        <span class="kt-section__info">Basic divider:</span>
                                        <div class="kt-section__content kt-section__content--solid">
                                            <div class="kt-divider">
                                                <span></span>
                                                <span>or</span>
                                                <span></span>
                                            </div>
                                        </div>
                                        <br><br><br>
                                        Bottom
                                    </div>
                                    <!--end::Section-->
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>

@endsection

@section('vendor-scripts')
@endsection

@section('page-styles')
    @include('/layout/sidebar')
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
    <script>
        <!-- Menu Toggle Script -->
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper555").toggleClass("toggled");
        });
    </script>
@endsection