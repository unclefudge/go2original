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
    <div class="m-portlet">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col" style="padding-right: 0px">
                    <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x">
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_all">
                                <i class="fa fa-users"></i> All &nbsp; ({{ count($people) }})
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_student">
                                <i class="fa fa-user-graduate"></i> Students &nbsp; ({{ count($people->whereIn('type', ['Student', 'Student/Volunteer'])) }})
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_parent">
                                <i class="fa fa-user-tie"></i> Parents &nbsp; ({{ count($people->whereIn('type', ['Parent', 'Parent/Volunteer'])) }})
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_volunteer">
                                <i class="fa fa-user-friends"></i> Volunteers ({{ count($people->whereIn('type', ['Volunteer', 'Parent/Volunteer'])) }})
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-1" style="padding-left: 0px">
                    <button type="button" class="btn btn-sm m-btn--pill btn-brand pull-right">Add</button>
                    <hr class="d-none d-md-block" style="padding-top: 20px; margin-top: 48px">
                </div>
            </div>
            {!! Form::hidden('type', null, ['id' => 'type']) !!}
            @if ($agent->isMobile())
                Mobile
                @else
                Bigger
            @endif
            <table class="table table-hover table-sm table-checkable table-responsive m-table--head-bg-brand m-table" id="datatable1">
                <thead>
                <tr>
                    <th width="5%"> #</th>
                    <th> Name</th>
                    <th> Type</th>
                    <th> Phone</th>
                    <th> Email</th>
                    <th> Address</th>
                    <th> Grade</th>
                    <th> School</th>
                    <th> Media</th>
                    <th> WWC Expiry</th>
                    <th width="5%"> Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@stop


@section('vendor-scripts')
    <script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
@stop

@section('page-styles')
    <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script type="text/javascript">

    $(document).ready(function () {

    });
    var datatable1 = $('#datatable1').DataTable({
        pageLength: 25,
        processing: true,
        serverSide: true,
        //bFilter: false,
        //bLengthChange: false,
        responsive: true,
        select: true,
        dom: "<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>\n\t\t\t<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        //buttons: ["print", "copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
        //dom: 'Bfrtip',
        buttons: [
            {
                text: "<i class='fa fa-cloud-download-alt' style='padding-right: 5px'></i> Export",
                action: function (e, dt, node, config) {
                    alert('Export');
                }
            },
            {
                text: "<i class='fa fa-cloud-upload-alt' style='padding-right: 5px'></i> Import",
                action: function (e, dt, node, config) {
                    alert('Import');
                }
            }
        ],
        ajax: {
            'url': '/data/people',
            'type': 'GET',
            'data': function (d) {
                //d.company_id = {{ $people }};
                d.type = $('#type').val();
                //d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'id', name: 'people.id', visible: false},
            {data: 'full_name', name: 'full_name', orderable: true, searchable: false},
            {data: 'type', name: 'people.type', orderable: false},
            {data: 'phone', name: 'people.phone', orderable: false},
            {data: 'email', name: 'people.email', orderable: false},
            {data: 'address', name: 'people.address', orderable: false},
            {data: 'grade', name: 'people.grade', orderable: true},
            {data: 'school_name', name: 'schools.name', orderable: true},
            {data: 'media_consent', name: 'people.media_consent', orderable: true},
            {data: 'wwc_exp2', name: 'people.wwc_exp', visible: false, orderable: true},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'firstname', name: 'people.firstname', visible: false},
            {data: 'lastname', name: 'people.lastname', visible: false},
        ],
        order: [
            [1, "asc"]
        ]
    });

    datatable1.on('select', function (e, dt, type, indexes) {
                var rowData = datatable1.rows(indexes).data().toArray();
                //events.prepend('<div><b>' + type + ' selection</b> - ' + JSON.stringify(rowData) + '</div>');
                //alert(JSON.stringify(rowData));
                var id = rowData[0][Object.keys(rowData[0])[0]];
                window.location.href = "/people/" + id;
            })
            .on('deselect', function (e, dt, type, indexes) {
                var rowData = datatable1.rows(indexes).data().toArray();
                //events.prepend('<div><b>' + type + ' <i>de</i>selection</b> - ' + JSON.stringify(rowData) + '</div>');
            });

    datatable1.on('click', '.btn-delete[data-remote]', function (e) {
        e.preventDefault();
        var url = $(this).data('remote');
        var name = $(this).data('name');

        swal({
            title: "Are you sure?",
            html: "You will not be able to recover this profile!<br><b>" + name + "</b>",
            cancelButtonText: "Cancel!",
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass: "btn btn-danger",
            showCancelButton: true,
            reverseButtons: true,
            allowOutsideClick: true,
        }, function () {
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                data: {method: '_DELETE', submit: true},
                success: function (data) {
                    toastr.error('Deleted document');
                },
            }).always(function (data) {
                $('#table1').DataTable().draw(false);
            });
        });
    });

    /*
     $("#m_sweetalert_demo_9").click(function(e){swal({title:"Are you sure ? ",text:"Youwon't be able to revert this!",type:"warning",showCancelButton:!0,confirmButtonText:"Yes, deleteit!",
     cancelButtonText:"No, cancel!",
     reverseButtons:!0}).then(function(e){e.value?swal("Deleted!",
     "Your file has been deleted.",
     "Success"):"cancel"===e.dismiss&&swal("Cancelled","Your imaginary file is safe:)",
     "error")})})
     */
    $("#type_all").click(function () {
        $("#type").val('');
        datatable1.column(2).visible(true);  // Type
        datatable1.column(6).visible(true);  // Grade
        datatable1.column(7).visible(true);  // School
        datatable1.column(8).visible(true);  // Media
        datatable1.column(9).visible(false);   // WWC Exp
        datatable1.ajax.reload();
    });
    $("#type_student").click(function () {
        $("#type").val('Student');
        datatable1.column(2).visible(false);  // Type
        datatable1.column(6).visible(true);   // Grade
        datatable1.column(7).visible(true);   // School
        datatable1.column(8).visible(true);   // Media
        datatable1.column(9).visible(false);  // WWC Exp
        datatable1.ajax.reload();
    });
    $("#type_parent").click(function () {
        $("#type").val('Parent');
        datatable1.column(2).visible(false);  // Type
        datatable1.column(6).visible(false);  // Grade
        datatable1.column(7).visible(false);  // School
        datatable1.column(8).visible(false);  // Media
        datatable1.column(9).visible(false);  // WWC Exp
        datatable1.ajax.reload();
    });
    $("#type_volunteer").click(function () {
        $("#type").val('Volunteer');
        datatable1.column(2).visible(false);  // Type
        datatable1.column(6).visible(false);  // Grade
        datatable1.column(7).visible(false);  // School
        datatable1.column(8).visible(false);  // Media
        datatable1.column(9).visible(true);   // WWC Exp
        datatable1.ajax.reload();
    });

    jQuery(document).ready(function () {
        datatable1.init()
    });


    var DatatablesExtensionButtons = {
        init: function () {
            var t;
            $("#m_table_1").DataTable({
                responsive: !0,
                dom: "<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>\n\t\t\t<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
                buttons: ["print", "copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
                columnDefs: [{
                    targets: 6, render: function (t, e, a, n) {
                        var s = {
                            1: {title: "Pending", class: "m-badge--brand"},
                            2: {title: "Delivered", class: " m-badge--metal"},
                            3: {title: "Canceled", class: " m-badge--primary"},
                            4: {title: "Success", class: " m-badge--success"},
                            5: {title: "Info", class: " m-badge--info"},
                            6: {title: "Danger", class: " m-badge--danger"},
                            7: {title: "Warning", class: " m-badge--warning"}
                        };
                        return void 0 === s[t] ? t : '<span class="m-badge ' + s[t].class + ' m-badge--wide">' + s[t].title + "</span>"
                    }
                }, {
                    targets: 7, render: function (t, e, a, n) {
                        var s = {1: {title: "Online", state: "danger"}, 2: {title: "Retail", state: "primary"}, 3: {title: "Direct", state: "accent"}};
                        return void 0 === s[t] ? t : '<span class="m-badge m-badge--' + s[t].state + ' m-badge--dot"></span>&nbsp;<span class="m--font-bold m--font-' + s[t].state + '">' + s[t].title + "</span>"
                    }
                }]
            }), t = $("#m_table_2").DataTable({
                responsive: !0,
                buttons: ["print", "copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
                processing: !0,
                serverSide: !0,
                ajax: {url: "https://keenthemes.com/metronic/themes/themes/metronic/dist/preview/inc/api/datatables/demos/server.php", type: "POST", data: {columnsDef: ["OrderID", "Country", "ShipCity", "ShipAddress", "CompanyAgent", "CompanyName", "Status", "Type"]}},
                columns: [{data: "OrderID"}, {data: "Country"}, {data: "ShipCity"}, {data: "ShipAddress"}, {data: "CompanyAgent"}, {data: "CompanyName"}, {data: "Status"}, {data: "Type"}],
                columnDefs: [{
                    targets: 6, render: function (t, e, a, n) {
                        var s = {
                            1: {title: "Pending", class: "m-badge--brand"},
                            2: {title: "Delivered", class: " m-badge--metal"},
                            3: {title: "Canceled", class: " m-badge--primary"},
                            4: {title: "Success", class: " m-badge--success"},
                            5: {title: "Info", class: " m-badge--info"},
                            6: {title: "Danger", class: " m-badge--danger"},
                            7: {title: "Warning", class: " m-badge--warning"}
                        };
                        return void 0 === s[t] ? t : '<span class="m-badge ' + s[t].class + ' m-badge--wide">' + s[t].title + "</span>"
                    }
                }, {
                    targets: 7, render: function (t, e, a, n) {
                        var s = {1: {title: "Online", state: "danger"}, 2: {title: "Retail", state: "primary"}, 3: {title: "Direct", state: "accent"}};
                        return void 0 === s[t] ? t : '<span class="m-badge m-badge--' + s[t].state + ' m-badge--dot"></span>&nbsp;<span class="m--font-bold m--font-' + s[t].state + '">' + s[t].title + "</span>"
                    }
                }]
            }), $("#export_print").on("click", function (e) {
                e.preventDefault(), t.button(0).trigger()
            }), $("#export_copy").on("click", function (e) {
                e.preventDefault(), t.button(1).trigger()
            }), $("#export_excel").on("click", function (e) {
                e.preventDefault(), t.button(2).trigger()
            }), $("#export_csv").on("click", function (e) {
                e.preventDefault(), t.button(3).trigger()
            }), $("#export_pdf").on("click", function (e) {
                e.preventDefault(), t.button(4).trigger()
            })
        }
    };
    jQuery(document).ready(function () {
        DatatablesExtensionButtons.init()
    });


</script>
@stop

