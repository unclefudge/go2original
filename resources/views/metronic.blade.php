<!DOCTYPE html>
<html lang="en">

<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <title>Metronic | Dashboard</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families": ["Poppins:300,400,500,600,700"]},
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Fonts -->

    <!--begin::Page Vendors Styles(used by this page) -->

    <!--end::Page Vendors Styles -->

    <!--begin:: Global Mandatory Vendors -->
    <link href="/massets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>

    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->
    <link href="/massets/vendors/general/tether/dist/css/tether.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/nouislider/distribute/nouislider.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/summernote/dist/summernote.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/morris.js/morris.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/general/socicon/css/socicon.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css"/>
    <link href="/massets/vendors/custom/vendors/fontawesome5/css/all.min.css" rel="stylesheet" type="text/css"/>

    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="/massets/demo/demo4/base/style.bundle.css" rel="stylesheet" type="text/css"/>

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="/massets/media/logos/favicon.ico"/>
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body style="background-image: url('/massets/demo/demo4/media/bg/header.jpg')" class="kt-page--fixed kt-header--fixed kt-header--minimize-menu kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent">

<!-- begin:: Page -->
<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
    <div class="kt-header-mobile__logo">
        <a href="/">
            <img alt="Logo" src="/massets/media/logos/logo-4-sm.png"/>
        </a>
    </div>
    <div class="kt-header-mobile__toolbar">
        <button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
        <button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more-1"></i></button>
    </div>
</div>
<!-- end:: Header Mobile -->

<div class="kt-grid kt-grid--hor kt-grid--root">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper " id="kt_wrapper">

            @include('layout/section/header')

            <style>
                .left-sidebar {
                    -ms-flex: 0 0 250px;
                    flex: 0 0 250px;
                    background-color: #FFF; /*greenyellow;*/
                    border-radius: 5px 5px 0px 0px;
                    box-shadow: 2px 0px 1px rgba(0, 0, 0, 0.1);
                    -moz-box-shadow: 2px 0px 1px rgba(0, 0, 0, 0.03);
                    -webkit-box-shadow: 2px 0px 1px rgba(0, 0, 0, 0.03);
                    -o-box-shadow: 2px 0px 1px rgba(0, 0, 0, 0.03);

                    margin: 0px 10px 50px 0px;
                }

                @media (max-width: 690px) {
                    .left-sidebar {
                        display: none;
                    }
                }
            </style>

            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
                <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
                    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                        <!-- begin:: Subheader -->
                        <div class="kt-subheader   kt-grid__item" id="kt_subheader">
                            <div class="kt-subheader__main">
                                <h3 class="kt-subheader__title">Aden Bosworth</h3>
                                <h4 class="kt-subheader__desc">STUDENT | Active</h4>
                            </div>
                            <div class="kt-subheader__toolbar">
                                <div class="kt-subheader__wrapper">
                                    <a href="#" class="btn kt-subheader__btn-secondary">
                                        Reports
                                    </a>
                                    <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="Quick actions" data-placement="top">
                                        <a href="#" class="btn btn-danger kt-subheader__btn-options" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Products
                                        </a>
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

                        <!-- end:: Subheader -->

                        <!-- begin:: Content -->
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col left-sidebar">
                                    <h2>LEFT</h2>
                                    <h6>(FIXED 230px COLUMN)</h6>
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
                                                                6<br><br><br><br>
                                                                8<br><br><br><br>
                                                                9<br><br><br><br>
                                                                10<br><br><br><br>
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

                        <!-- end:: Content -->
                    </div>
                </div>
            </div>
            @include('layout/section/footer')
        </div>
    </div>
</div>

<!-- end:: Page -->

@include('layout/section/quickpanel')

<!-- begin::Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop">
    <i class="fa fa-arrow-up"></i>
</div>

<!-- end::Scrolltop -->

<!-- end::Sticky Toolbar -->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#366cf3",
                "light": "#ffffff",
                "dark": "#282a3c",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };
</script>

<!-- end::Global Config -->

<!--begin:: Global Mandatory Vendors -->
<script src="/massets/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
<script src="/massets/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
<script src="/massets/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript"></script>
<script src="/massets/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>
<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->
<script src="/massets/vendors/general/jquery-form/dist/jquery.form.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="/massets/vendors/custom/components/vendors/bootstrap-datepicker/init.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="/massets/vendors/custom/components/vendors/bootstrap-timepicker/init.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js" type="text/javascript"></script>
<script src="/massets/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js" type="text/javascript"></script>
<script src="/massets/vendors/custom/components/vendors/bootstrap-switch/init.js" type="text/javascript"></script>
<script src="/massets/vendors/general/select2/dist/js/select2.full.js" type="text/javascript"></script>
<script src="/massets/vendors/general/ion-rangeslider/js/ion.rangeSlider.js" type="text/javascript"></script>
<script src="/massets/vendors/general/typeahead.js/dist/typeahead.bundle.js" type="text/javascript"></script>
<script src="/massets/vendors/general/handlebars/dist/handlebars.js" type="text/javascript"></script>
<script src="/massets/vendors/general/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript"></script>
<script src="/massets/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js" type="text/javascript"></script>
<script src="/massets/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js" type="text/javascript"></script>
<script src="/massets/vendors/general/nouislider/distribute/nouislider.js" type="text/javascript"></script>
<script src="/massets/vendors/general/owl.carousel/dist/owl.carousel.js" type="text/javascript"></script>
<script src="/massets/vendors/general/autosize/dist/autosize.js" type="text/javascript"></script>
<script src="/massets/vendors/general/clipboard/dist/clipboard.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/dropzone/dist/dropzone.js" type="text/javascript"></script>
<script src="/massets/vendors/general/summernote/dist/summernote.js" type="text/javascript"></script>
<script src="/massets/vendors/general/markdown/lib/markdown.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
<script src="/massets/vendors/custom/components/vendors/bootstrap-markdown/init.js" type="text/javascript"></script>
<script src="/massets/vendors/general/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript"></script>
<script src="/massets/vendors/custom/components/vendors/bootstrap-notify/init.js" type="text/javascript"></script>
<script src="/massets/vendors/general/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
<script src="/massets/vendors/general/jquery-validation/dist/additional-methods.js" type="text/javascript"></script>
<script src="/massets/vendors/custom/components/vendors/jquery-validation/init.js" type="text/javascript"></script>
<script src="/massets/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/raphael/raphael.js" type="text/javascript"></script>
<script src="/massets/vendors/general/morris.js/morris.js" type="text/javascript"></script>
<script src="/massets/vendors/general/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
<script src="/massets/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js" type="text/javascript"></script>
<script src="/massets/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/waypoints/lib/jquery.waypoints.js" type="text/javascript"></script>
<script src="/massets/vendors/general/counterup/jquery.counterup.js" type="text/javascript"></script>
<script src="/massets/vendors/general/es6-promise-polyfill/promise.min.js" type="text/javascript"></script>
<script src="/massets/vendors/general/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
<script src="/massets/vendors/custom/components/vendors/sweetalert2/init.js" type="text/javascript"></script>
<script src="/massets/vendors/general/jquery.repeater/src/lib.js" type="text/javascript"></script>
<script src="/massets/vendors/general/jquery.repeater/src/jquery.input.js" type="text/javascript"></script>
<script src="/massets/vendors/general/jquery.repeater/src/repeater.js" type="text/javascript"></script>
<script src="/massets/vendors/general/dompurify/dist/purify.js" type="text/javascript"></script>
<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle(used by all pages) -->
<script src="/massets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
<script src="/massets/demo/demo4/base/scripts.bundle.js" type="text/javascript"></script>
<!--end::Global Theme Bundle -->

<!--begin::Page Vendors(used by this page) -->
<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<!--end::Page Scripts -->

<!--begin::Global App Bundle(used by all pages) -->
<script src="/massets/app/bundle/app.bundle.js" type="text/javascript"></script>
<!--end::Global App Bundle -->

</body>
<!-- end::Body -->
</html>