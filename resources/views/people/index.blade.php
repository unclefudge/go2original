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
            {!! Form::hidden('pagelength', ($agent->isMobile() ? 100 : 25), ['id' => 'pagelength']) !!}

            <style>
                #datatable1 tbody td.selected {
                    color: black;
                    background-color: #F8F9FB;
                }
            </style>
            <table class="table table-hover table-sm table-checkable table-bordered table-responsive m-table--head-bg-brand m-table" id="datatable1">
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
                    <th width="10%"> WWC Expiry</th>
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

    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    //
    // Datatable
    //
    var datatable1 = $('#datatable1').DataTable({
        pageLength: $('#pagelength').val(),
        processing: true,
        serverSide: true,
        //bFilter: false,
        //bLengthChange: false,
        responsive: true,
        //select: true,
        select: {
            style: 'single',
            items: 'cell'
        },
        dom: "<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>\n\t\t\t<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        //buttons: ["print", "copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
        buttons: [
                @if ($agent->isDesktop())
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
            @endif
        ],
        ajax: {
            'url': '/data/people',
            'type': 'GET',
            'data': function (d) {
                d.type = $('#type').val();
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
        ],
    });


    //
    // Delete select profile on trashcan
    //
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
            allowOutsideClick: true
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {method: '_DELETE', submit: true},
                    success: function (data) {
                        toastr.error('Deleted profile');
                    },
                }).always(function (data) {
                    $('#datatable1').DataTable().draw(false);
                });
            }
        });
    });

    //
    // View selected profile on click
    //
    datatable1.on('select', function (e, dt, type, indexes) {
        var colnum = indexes[0].column;
        var rownum = indexes[0].row;
        var cell_data = datatable1.cell(indexes).data();
        //console.log('R:'+rownum+' C:'+colnum+' D:'+cell_data);
        //var rowData = datatable1.rows(indexes).data().toArray(); // For row select
        var rowData = datatable1.rows(rownum).data().toArray(); // for cell selection
        //console.log(rowData);
        var id = rowData[0][Object.keys(rowData[0])[0]];
        if (colnum != 10)
            window.location.href = "/people/" + id;
    });


    //
    //  View profiles of 'Type' + hide/show columns for select type
    //
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
</script>
@stop

