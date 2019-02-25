@inject('ozstates', 'App\Http\Utilities\Ozstates')
@extends('layouts/main')

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
                    <button type="button" class="btn btn-sm m-btn--pill btn-brand pull-right" data-toggle="modal" data-target="#modal_create_person">Add</button>
                    <hr class="d-none d-md-block" style="padding-top: 20px; margin-top: 48px">
                </div>
            </div>
            {!! Form::hidden('show_type', null, ['id' => 'show_type']) !!}
            {!! Form::hidden('pagelength', ($agent->isMobile() ? 100 : 25), ['id' => 'pagelength']) !!}
            {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

            <style>
                #datatable1 tbody td.selected {
                    color: black;
                    background-color: #F8F9FB;
                }
            </style>
            <table class="table table-hover table-checkable table-bordered table-responsive m-table--head-bg-brand m-table" id="datatable1" width="100%">
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

    @include('people/_create_personal')

    {{-- Create Person Modal --}}
    <div class="modal fade" id="modal_create_person2" tabindex="-1" role="dialog" aria-labelledby="Profile" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                {!! Form::model('people', ['action' => ['People\PeopleController@store']]) !!}
                <div class="modal-header" style="background: #32c5d2; padding: 20px">
                    <h3 class="modal-title text-white" id="ModalLabel" style="font-size: 20px">Add a Person</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <style>
                    .twitter-typeahead,
                    .tt-hint,
                    .tt-input,
                    .tt-menu{
                       /* width: auto ! important;
                        font-weight: normal; */
                    }
                    .tt-suggestion, .tt-selectable {
                        width: 100% !important;
                    }
                </style>
                <div class="modal-body" style="background-color: #F7F7F7">
                    {{-- Search Name --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group m-form__group row m--margin-top-20">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="m-typeahead">
                                        <input class="form-control m-input" id="search" dir="ltr" type="text" placeholder="Search for someone" width="200px">
                                    </div>
                                    {{--}}
                                    <form id="form-user_v1" name="form-user_v1">
                                        <div class="typeahead__container">
                                            <div class="typeahead__field">
                                                <div class="typeahead__query">
                                                    <input class="js-typeahead-user_v1" name="user_v1[query]" type="search" placeholder="Search" autocomplete="off" id="search">
                                                </div>
                                                <div class="typeahead__button">
                                                    <button type="submit">
                                                        <i class="typeahead__search-icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div>
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
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script type="text/javascript">

    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    $(document).ready(function () {
        $('.dataTables_filter input[type="search"]').attr('placeholder', 'Search for something').css(
                {'width': '350px', 'height': '40px', 'font-size': '14px', 'margin-left': '0px', 'display': 'inline-block'});
    });

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
        language: { search: "" },
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
            },
                @endif
                @if (false)
            {
                text: "<i class='fa fa-cloud-upload-alt' style='padding-right: 5px'></i> Hide/Show Columns",
                action: function (e, dt, node, config) {
                    alert('Columns');
                }
            },
            {
                text: '<div class="dropdown"><button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown button </button> <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> <a class="dropdown-item" href="#" data-toggle="m-tooltip" title="Tooltip title" data-placement="right" data-skin="dark" 	data-container="body">Action</a> <a class="dropdown-item" href="#">Another action</a> <a class="dropdown-item" href="#" data-toggle="m-tooltip" title="Tooltip title" data-placement="left">Something else here</a></div></div>',
                action: function (e, dt, node, config) {
                    //alert('Columns');
                }
            },
            @endif
        ],
        ajax: {
            'url': '/data/people',
            'type': 'GET',
            'data': function (d) {
                d.show_type = $('#show_type').val();
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
    datatable1.on('click', '.btn-archive[data-remote]', function (e) {
        e.preventDefault();
        var url = $(this).data('remote');
        var name = $(this).data('name');

        swal({
            title: "Are you sure?",
            html: "All information and check-ins will be archived for<br><b>" + name + "</b><br>",
            cancelButtonText: "Cancel!",
            confirmButtonText: "Yes, archive it!",
            confirmButtonClass: "btn btn-accent",
            showCancelButton: true,
            reverseButtons: true,
            allowOutsideClick: true
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {submit: true},
                    success: function (data) {
                        toastr.error('Archived person');
                    },
                }).always(function (data) {
                    $('#datatable1').DataTable().draw(false);
                });
            }
        });
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
            html: "All information and check-ins will be deleted for<br><b>" + name + "</b><br><br><span class='m--font-danger'><i class='fa fa-exclamation-triangle'></i>You will not be able to recover this person!</span> ",
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
                        toastr.error('Deleted person');
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
        $("#show_type").val('');
        datatable1.column(2).visible(true);  // Type
        datatable1.column(6).visible(true);  // Grade
        datatable1.column(7).visible(true);  // School
        datatable1.column(8).visible(true);  // Media
        datatable1.column(9).visible(false);   // WWC Exp
        datatable1.ajax.reload();
    });
    $("#type_student").click(function () {
        $("#show_type").val('Student');
        datatable1.column(2).visible(false);  // Type
        datatable1.column(6).visible(true);   // Grade
        datatable1.column(7).visible(true);   // School
        datatable1.column(8).visible(true);   // Media
        datatable1.column(9).visible(false);  // WWC Exp
        datatable1.ajax.reload();
    });
    $("#type_parent").click(function () {
        $("#show_type").val('Parent');
        datatable1.column(2).visible(false);  // Type
        datatable1.column(6).visible(false);  // Grade
        datatable1.column(7).visible(false);  // School
        datatable1.column(8).visible(false);  // Media
        datatable1.column(9).visible(false);  // WWC Exp
        datatable1.ajax.reload();
    });
    $("#type_volunteer").click(function () {
        $("#show_type").val('Volunteer');
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
<script type="text/javascript">
    // Form errors - show modal
    if ($('#formerrors').val() == 'personal')
        $('#modal_create_person').modal('show');

    $(document).ready(function () {

        display_fields();

        function display_fields() {
            var type = $("#type").val();
            $('#fields_student').hide();
            $('#fields_volunteer').hide();

            if (type == 'Student' || type == 'Student/Volunteer') {
                $('#fields_student').show();
            }
            if (type == 'Volunteer' || type == 'Student/Volunteer' || type == 'Parent/Volunteer') {
                $('#fields_volunteer').show();
            }

            // Dynamic School dropdown from Grade
            $("#school_id").select2({width: '100%', minimumResultsForSearch: -1});
            var grade = $("#grade").val();
            var school = $("#school_id").val();
            if (grade) {
                $.ajax({
                    url: '/data/schools-by-grade/' + grade,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function () {
                        $('#loader').css("visibility", "visible");
                    },

                    success: function (data) {
                        $("#school_id").empty();
                        $("#school_id").append('<option value="">Select school</option>');

                        var school_names = [];
                        $.each(data, function (key, value) {
                            school_names.push(value);
                        });
                        school_names.sort();
                        var other_key = 0;
                        for (var i = 0; i < school_names.length; i++) {
                            var val = school_names[i];
                            var key = Object.keys(data)[Object.values(data).indexOf(school_names[i])];
                            if (val == 'Other') {
                                other_key = key;
                            } else {
                                if (school == key)
                                    $("#school_id").append('<option value="' + key + '" selected>' + val + '</option>');
                                else
                                    $("#school_id").append('<option value="' + key + '">' + val + '</option>');
                            }
                        }
                        // Append Other to end of list
                        if (school == 'Other')
                            $("#school_id").append('<option value="' + other_key + '" selected>Other</option>');
                        else
                            $("#school_id").append('<option value="' + other_key + '">Other</option>');
                    },
                    complete: function () {
                        $('#loader').css("visibility", "hidden");
                    }
                });
            } else {
                $("#school_id").empty();
                $("#school_id").append('<option value="">Select grade first</option>');
            }
        }

        $("#type").change(function () {
            display_fields();
        });

        $("#grade").change(function () {
            display_fields();
        });
    });

    /*
     var Typeahead = function () {
     var e = ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"];
     return {
     init: function () {
     var a, n, o, t, i, s;
     $("#m_typeahead_1, #m_typeahead_1_modal, #m_typeahead_1_validate, #m_typeahead_2_validate, #m_typeahead_3_validate").typeahead({hint: !0, highlight: !0, minLength: 1}, {
     name: "states", source: (a = e, function (e, n) {
     var o;
     o = [], substrRegex = new RegExp(e, "i"), $.each(a, function (e, a) {
     substrRegex.test(a) && o.push(a)
     }), n(o)
     })
     }), n = new Bloodhound({datumTokenizer: Bloodhound.tokenizers.whitespace, queryTokenizer: Bloodhound.tokenizers.whitespace, local: e}), $("#m_typeahead_2, #m_typeahead_2_modal").typeahead({hint: !0, highlight: !0, minLength: 1}, {
     name: "states",
     source: n
     }), o = new Bloodhound({
     datumTokenizer: Bloodhound.tokenizers.whitespace,
     queryTokenizer: Bloodhound.tokenizers.whitespace,
     prefetch: "https://keenthemes.com/metronic/themes/themes/metronic/dist/preview/inc/api/typeahead/countries.json"
     }), $("#m_typeahead_3, #m_typeahead_3_modal").typeahead(null, {name: "countries", source: o}), t = new Bloodhound({
     datumTokenizer: Bloodhound.tokenizers.obj.whitespace("value"),
     queryTokenizer: Bloodhound.tokenizers.whitespace,
     prefetch: "inc/api/typeahead/movies.json"
     }), $("#m_typeahead_4").typeahead(null, {
     name: "best-pictures",
     display: "value",
     source: t,
     templates: {empty: ['<div class="empty-message" style="padding: 10px 15px; text-align: center;">', "unable to find any Best Picture winners that match the current query", "</div>"].join("\n"), suggestion: Handlebars.compile("<div><strong>{value}</strong> â€“ {year}</div>")}
     }), i = new Bloodhound({datumTokenizer: Bloodhound.tokenizers.obj.whitespace("team"), queryTokenizer: Bloodhound.tokenizers.whitespace, prefetch: "inc/api/typeahead/nba.json"}), s = new Bloodhound({
     datumTokenizer: Bloodhound.tokenizers.obj.whitespace("team"),
     queryTokenizer: Bloodhound.tokenizers.whitespace,
     prefetch: "inc/api/typeahead/nhl.json"
     }), $("#m_typeahead_5").typeahead({highlight: !0}, {name: "nba-teams", display: "team", source: i, templates: {header: '<h3 class="league-name" style="padding: 5px 15px; font-size: 1.2rem; margin:0;">NBA Teams</h3>'}}, {
     name: "nhl-teams",
     display: "team",
     source: s,
     templates: {header: '<h3 class="league-name" style="padding: 5px 15px; font-size: 1.2rem; margin:0;">NHL Teams</h3>'}
     })
     }
     }
     }();
     jQuery(document).ready(function () {
     Typeahead.init()
     });
     */



        var bloodhound = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '/user/find?q=%QUERY%',
                wildcard: '%QUERY%'
            },
        });

        $('#search').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'users',
            source: bloodhound,
            display: function(data) {
                return data.firstname  //Input value to be set when you select a suggestion.
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    //return '<div>' + data.firstname + ' ' + data.lastname + '</div></div>'
                    var color = "#777";
                    if (data.status === "owner") {
                        color = "#ff1493";
                    }
                    data.avatar = '/img/avatar-user.jpg'
                    return '<span class="row">' +
                            '<span class="avatar">' + '<img src="'+data.avatar+'">' + "</span>" +
                            '<span class="username">'+data.firstname+' '+data.lastname+'</span><br><br>' +
                            '<span class="id">('+data.type+')</span>' +
                            "</span>"
                }
            }
        });

/*
    $.typeahead({
        input: '.js-typeahead-user_v1',
        minLength: 1,
        order: "asc",
        dynamic: true,
        delay: 500,
        backdrop: {
            "background-color": "#fff"
        },
        template: function (query, item) {

            var color = "#777";
            if (item.status === "owner") {
                color = "#ff1493";
            }

            return '<span class="row">' +
                    '<span class="avatar">' +
                    '<img src="'+item.avatar+'">' +
                    "</span>" +
                    '<span class="username">'+item.username+' <small style="color: ' + color + ';">('+item.status+')</small></span>' +
                    '<span class="id">('+item.id+')</span>' +
                    "</span>"
        },
        emptyTemplate: "no result for "+query,
        source: {
            user: {
                display: "username",
                href: "https://www.github.com/"+item.username,
                data: [{
                    "id": 415849,
                    "username": "an inserted user that is not inside the database",
                    "avatar": "https://avatars3.githubusercontent.com/u/415849",
                    "status": "contributor"
                }],
                ajax: function (query) {
                    return {
                        type: "GET",
                        //url: "/jquerytypeahead/user_v1.json",
                        url: "/data/people/search-add",
                        path: "data.user",
                        data: {
                            q: query
                        },
                        callback: {
                            done: function (data) {
                                for (var i = 0; i < data.data.user.length; i++) {
                                    if (data.data.user[i].username === 'running-coder') {
                                        data.data.user[i].status = 'owner';
                                    } else {
                                        data.data.user[i].status = 'contributor';
                                    }
                                }
                                return data;
                            }
                        }
                    }
                }

            },
            project: {
                display: "project",
                href: function (item) {
                    return '/' + item.project.replace(/\s+/g, '').toLowerCase() + '/documentation/'
                },
                ajax: [{
                    type: "GET",
                    //url: "/jquerytypeahead/user_v1.json",
                    url: "/data/people/search-add",
                    data: {
                        q: query
                    }
                }, "data.project"],
                template: '<span>' +
                '<span class="project-logo">' +
                '<img src="'+item.image+'">' +
                '</span>' +
                '<span class="project-information">' +
                '<span class="project">'+item.project+' <small>'+item.version+'</small></span>' +
                '<ul>' +
                '<li>'+item.demo+' Demos</li>' +
                '<li>'+item.option+'+ Options</li>' +
                '<li>'+item.callback+'+ Callbacks</li>' +
                '</ul>' +
                '</span>' +
                '</span>'
            }
        },
        callback: {
            onClick: function (node, a, item, event) {

                // You can do a simple window.location of the item.href
                alert(JSON.stringify(item));

            },
            onSendRequest: function (node, query) {
                console.log('request is sent')
            },
            onReceiveRequest: function (node, query) {
                console.log('request is received')
            }
        },
        debug: true
    });
    */

    {{--
    $.typeahead({
        input: '.js-typeahead-user_v1',
        minLength: 1,
        order: "asc",
        dynamic: true,
        delay: 500,
        backdrop: {
            "background-color": "#fff"
        },
        template: function (query, item) {

            var color = "#777";
            if (item.status === "owner") {
                color = "#ff1493";
            }

            return '<span class="row">' +
                    '<span class="avatar">' +
                    '<img src="{{avatar}}">' +
                    "</span>" +
                    '<span class="username">{{username}} <small style="color: ' + color + ';">({{status}})</small></span>' +
                    '<span class="id">({{id}})</span>' +
                    "</span>"
        },
        emptyTemplate: "no result for {{query}}",
        source: {
            user: {
                display: "username",
                href: "https://www.github.com/{{username|slugify}}",
                data: [{
                    "id": 415849,
                    "username": "an inserted user that is not inside the database",
                    "avatar": "https://avatars3.githubusercontent.com/u/415849",
                    "status": "contributor"
                }],
                ajax: function (query) {
                    return {
                        type: "GET",
                        //url: "/jquerytypeahead/user_v1.json",
                        url: "/data/people/search-add",
                        path: "data.user",
                        data: {
                            q: "{{query}}"
                        },
                        callback: {
                            done: function (data) {
                                for (var i = 0; i < data.data.user.length; i++) {
                                    if (data.data.user[i].username === 'running-coder') {
                                        data.data.user[i].status = 'owner';
                                    } else {
                                        data.data.user[i].status = 'contributor';
                                    }
                                }
                                return data;
                            }
                        }
                    }
                }

            },
            project: {
                display: "project",
                href: function (item) {
                    return '/' + item.project.replace(/\s+/g, '').toLowerCase() + '/documentation/'
                },
                ajax: [{
                    type: "GET",
                    //url: "/jquerytypeahead/user_v1.json",
                    url: "/data/people/search-add",
                    data: {
                        q: "{{query}}"
                    }
                }, "data.project"],
                template: '<span>' +
                '<span class="project-logo">' +
                '<img src="{{image}}">' +
                '</span>' +
                '<span class="project-information">' +
                '<span class="project">{{project}} <small>{{version}}</small></span>' +
                '<ul>' +
                '<li>{{demo}} Demos</li>' +
                '<li>{{option}}+ Options</li>' +
                '<li>{{callback}}+ Callbacks</li>' +
                '</ul>' +
                '</span>' +
                '</span>'
            }
        },
        callback: {
            onClick: function (node, a, item, event) {

                // You can do a simple window.location of the item.href
                alert(JSON.stringify(item));

            },
            onSendRequest: function (node, query) {
                console.log('request is sent')
            },
            onReceiveRequest: function (node, query) {
                console.log('request is received')
            }
        },
        debug: true
    });
--}}
</script>
@stop

