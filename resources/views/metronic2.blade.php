@extends('layout/main')

@section('headimage')
    style="background-image: url(/massets/demo/demo4/media/bg/header.jpg)"
    {{--}}style="background-image: url(/img/wallpapers/purple-abstract.png)"--}}
@endsection

@section('subheader')
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Aden Bosworth</h3>
            <h4 class="kt-subheader__desc">STUDENT | Active</h4>
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
                    <style>
                        #sidebar-wrapper {
                            /*min-height: 100vh;*/
                            -webkit-transition: margin .25s ease-out;
                            -moz-transition: margin .25s ease-out;
                            -o-transition: margin .25s ease-out;
                            transition: margin .25s ease-out;
                        }

                        #sidebar-wrapper .sidebar-heading {
                            padding: 20px 0px 10px 0px;
                            /*padding: 0.875rem 1.25rem;*/
                            font-size: 1.2rem;
                        }

                        #sidebar-wrapper .list-group {
                            /*width: 15rem;*/
                        }

                        #sidebar-wrapper .list-group .list-group-item:first-child {
                            border-top: 0px;
                            border-bottom: 1px solid #ebedf2;
                        }

                        #sidebar-wrapper .list-group .list-group-item {
                            border-bottom: 1px solid #ebedf2;
                        }

                        #sidebar-wrapper .list-group-item-action:hover {
                            color: #ff0000;
                            background: inherit;
                            /*width: 15rem;*/
                        }

                        #sidebar-wrapper .list-group-item.active {
                            color: #ff0000;
                            background: inherit;
                            border: inherit;
                            /*background: #ff0000;(/
                            /*width: 15rem;*/
                        }
                        #wrapper.toggled #sidebar-wrapper {
                            margin-left: 0;
                        }

                    </style>


                    <!-- Sidebar -->
                    <div id="sidebar-wrapper">
                        <!--<div class="sidebar-heading">Start Bootstrap</div>-->
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
                    <!-- /#sidebar-wrapper -->

                </div>

                {{-- Right Cpntent --}}
                <div class="col">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="kt-content kt-grid__item kt-grid__item--fluid">
                                <?php $user = \App\User::find(67); ?>
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

    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="alert alert-light alert-elevate" role="alert">
            <div class="alert-icon alert-icon-top"><i class="flaticon-warning kt-font-brand"></i></div>
            <div class="alert-text">
                <p>
                    The layout builder helps to configure the layout with preferred options and preview it in real time.
                    The configured layout options will be saved until you change or reset them.
                    To use the layout builder choose the layout options and click the <code>Preview</code> button to preview
                    the changes and click the <code>Export</code> to download the HTML template with its includable partials of this demo.
                    In the downloaded folder the partials(header, footer, aside, topbar, etc) will be placed seperated from the base layout to allow you to integrate base layout into your application
                </p>
                <p>
                    <span class="kt-badge kt-badge--inline kt-badge--pill kt-badge--danger kt-badge--rounded">NOTE:</span>&nbsp;&nbsp;The downloaded version does not include the assets folder since the layout builder's main purpose is to help to generate the final HTML code
                    without hassle.
                </p>
            </div>
        </div>

        <div class="kt-portlet kt-portlet--tabs">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-toolbar">
                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-left nav-tabs-line-primary" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#kt_builder_page" role="tab">Page</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  active" data-toggle="tab" href="#kt_builder_header" role="tab">Header</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#kt_builder_footer" role="tab">Footer</a>
                        </li>
                    </ul>
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