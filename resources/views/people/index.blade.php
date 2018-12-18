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
                                <i class="fa fa-user-graduate"></i> Students &nbsp; ({{ count($people->where('type', 'Student')) }})
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_parent">
                                <i class="fa fa-user-tie"></i> Parents &nbsp; ({{ count($people->whereIn('type', ['P', 'PV'])) }})
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_volunteer">
                                <i class="fa fa-user-friends"></i> Volunteers ({{ count($people->whereIn('type', ['V', 'PV'])) }})
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
            <table class="table table-hover table-sm table-checkable table-responsive m-table--head-bg-brand m-table" id="datatable1">
                <thead>
                <tr>
                    <th width="5%"> #</th>
                    <th> Name</th>
                    <th> Phone</th>
                    <th> Email</th>
                    <th> Address</th>
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
            {data: 'id', name: 'people.id', orderable: false, searchable: false},
            {data: 'full_name', name: 'full_name', orderable: true, searchable: false},
            {data: 'phone', name: 'people.phone', orderable: false},
            {data: 'email', name: 'people.email', orderable: false},
            {data: 'address', name: 'people.address', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'firstname', name: 'people.firstname', visible: false},
            {data: 'lastname', name: 'people.lastname', visible: false},
        ],
        order: [
            [1, "asc"]
        ]
    });

    datatable1.on('click', '.btn-delete[data-remote]', function (e) {
        e.preventDefault();
        var url = $(this).data('remote');
        var name = $(this).data('name');

        swal({
            title: "Are you sure?",
            html: "You will not be able to recover this profile!<br><b>" + name + "</b>",
            cancelButtonText:"Cancel!",
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass:"btn btn-danger",
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
        datatable1.ajax.reload();
    });
    $("#type_student").click(function () {
        $("#type").val('Student');
        datatable1.ajax.reload();
    });
    $("#type_parent").click(function () {
        $("#type").val('Parent');
        datatable1.ajax.reload();
    });
    $("#type_volunteer").click(function () {
        $("#type").val('Volunteer');
        datatable1.ajax.reload();
    });

    jQuery(document).ready(function () {
        datatable1.init()
    });

/*
    var SweetAlert2Demo = {
        init: function () {
            $("#m_sweetalert_demo_1").click(function (e) {
                swal("Good job!")
            }), $("#m_sweetalert_demo_2").click(function (e) {
                swal("Here's the title!", "...and here's the text!")
            }), $("#m_sweetalert_demo_3_1").click(function (e) {
                swal("Good job!", "You clicked the button!", "warning")
            }), $("#m_sweetalert_demo_3_2").click(function (e) {
                swal("Good job!", "You clicked the button!", "error")
            }), $("#m_sweetalert_demo_3_3").click(function (e) {
                swal("Good job!", "You clicked the button!", "success")
            }), $("#m_sweetalert_demo_3_4").click(function (e) {
                swal("Good job!", "You clicked the button!", "info")
            }), $("#m_sweetalert_demo_3_5").click(function (e) {
                swal("Good job!", "You clicked the button!", "question")
            }), $("#m_sweetalert_demo_4").click(function (e) {
                swal({title: "Good job!", text: "You clicked the button!", icon: "success", confirmButtonText: "Confirm me!", confirmButtonClass: "btn btn-focus m-btn m-btn--pill m-btn--air"})
            }), $("#m_sweetalert_demo_5").click(function (e) {
                swal({
                    title: "Good job!", text: "You clicked the button!", icon: "success", confirmButtonText: "
                    < span > < i class = 'la la-headphones' > < / i > < span > I
                am
                game
                !< / span >
                < / span > ",confirmButtonClass:"
                btn
                btn - danger
                m - btn
                m - btn--
                pill
                m - btn--
                air
                m - btn--
                icon
                ",showCancelButton:!0,cancelButtonText:"
                < span >
                    < i
                            class
                                    ='la la-thumbs-down'>
                        <
                        / i >
                        < span >No, thanks
                            <
                            / span >
                            <
                            / span >",cancelButtonClass:"
                            btn
                            btn - secondary
                            m - btn
                            m - btn--
                            pill
                            m - btn--
                            icon
                            "})}),$("#m_sweetalert_demo_6
                            ").click(function(e){swal({
                                position: "
                                top -right
                                ",type:"
                                success
                                ",title:"
                                Your
                                work
                                has
                                been
                                saved
                                ",showConfirmButton:!1,timer:1500})}),$("#m_sweetalert_demo_7
                            ").click(function(e){swal({
                                title: "
                                jQuery
                                HTML
                                example
                                ",html:$("
                                < div > ").addClass("
                                some -class
                                ").text("
                                jQuery
                                is
                                everywhere.
                                "),animation:!1,customClass:"
                                animated
                                tada
                                        "})}),$("#m_sweetalert_demo_8
                            ").click(function(e){swal({
                                title: "
                                Are
                                you
                                sure ? ",text:"You
                                won
                                        't be able to revert this!",type:"warning",showCancelButton:!0,confirmButtonText:"Yes, delete
                                it
                                        !"}).then(function(e){e.value&&swal("
                                Deleted
                                        !","
                                Your
                                file
                                has
                                been
                                deleted.
                                ","
                                success
                                ")})}),$("#m_sweetalert_demo_9
                            ").click(function(e){swal({
                                title: "
                                Are
                                you
                                sure ? ",text:"You
                                won
                                        't be able to revert this!",type:"warning",showCancelButton:!0,confirmButtonText:"Yes, delete
                                it
                                        !",cancelButtonText:"
                                No, cancel
                                        !",reverseButtons:!0}).then(function(e){e.value?swal("
                                Deleted
                                        !","
                                Your
                                file
                                has
                                been
                                deleted.
                                ","
                                success
                                "):"
                                cancel
                                "===e.dismiss&&swal("
                                Cancelled
                                ","
                                Your
                                imaginary
                                file
                                is
                                safe
                                :)
                                ","
                                error
                                ")})}),$("#m_sweetalert_demo_10
                            ").click(function(e){swal({
                                title: "
                                Sweet
                                        !",text:"
                                Modal
                                with a custom
                                image.
                                ",imageUrl:"
                                https://unsplash.it/400/200",imageWidth:400,imageHeight:200,imageAlt:"Custom image",animation:!1})}),$("#m_sweetalert_demo_11").click(function(e){swal({
                                title: "Auto close
                                alert
                                        !",text:"
                                I
                                will
                                close in 5
                                seconds.
                                ",timer:5e3,onOpen:function(){swal.showLoading()}}).then(function(e){"
                                timer
                                "===e.dismiss&&console.log("
                                I
                                was
                                closed
                                by
                                the
                                timer
                                ")})})}};jQuery(document).ready(function(){SweetAlert2Demo.init()}); */
</script>
@stop

