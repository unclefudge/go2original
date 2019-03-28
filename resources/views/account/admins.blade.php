@extends('layout/main')

@section('bodystyle')
    style="background-image: url(/img/head-purple.jpg)"
@endsection

@section('subheader')
    @include('account/_header')
@endsection

@section('content')
    <style>
        .search-input {
            position: relative;
            display: inline-block;
            width: 100%
        }

        .search-dropdown {
            position: absolute;
            z-index: 1;
            padding-right: 30px;
        }

        .icon-admin {
            padding: 5px;
            /*color: #999;*/
        }

        .perm-yes {
            width: 50px;
            cursor: pointer;
        }

        .perm-no {
            width: 50px;
            cursor: pointer;
            opacity: .2;
        }
    </style>
    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="vue-app">
        <div class="container-fluid">
            <div class="row">
                @include('account/_sidebar')
                {{-- Main Content --}}
                <div class="col" style="height: 100% !important; min-height: 100% !important;">
                    @include('account/_sidebar-mobile')
                    <div class="row">
                        <div class="col">
                            {{-- Account Info --}}
                            <div class="kt-portlet">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Administrators</h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                    </div>
                                </div>

                                <div class="kt-portlet__body">
                                    {{-- Organisation Admins --}}
                                    <div class="row">
                                        <div class="col-lg-7 order-sm-first order-lg-last">
                                            <div class="alert alert-secondary" role="alert">
                                                <div class="alert-text">
                                                    <h5>Permissions</h5>
                                                    <hr>
                                                    <p>Understanding your account security is important to ensure your information is only accessed or updated by trusted people.</p>
                                                    <h6>Permission types</h6>
                                                    <div class="row" style="padding: 5px">
                                                        <div class="col-1"><i class="fa fa-check" style="font-size: 18px"></i></div>
                                                        <div class="col">Allowed to check-in students only</div>
                                                    </div>
                                                    <div class="row" style="padding: 5px">
                                                        <div class="col-1"><i class="fa fa-eye" style="font-size: 18px"></i></div>
                                                        <div class="col">View access only to your information</div>
                                                    </div>
                                                    <div class="row" style="padding: 5px">
                                                        <div class="col-1"><i class="fa fa-pen" style="font-size: 18px"></i></div>
                                                        <div class="col">Able to edit/modify your information but unable to delete full records. They can make people or events inactive.</div>
                                                    </div>
                                                    <div class="row" style="padding: 5px">
                                                        <div class="col-1"><i class="fa fa-user-cog" style="font-size: 18px"></i></div>
                                                        <div class="col">Full access for senior volunteers/staff</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5 order-sm-last order-lg-first" style="margin-bottom: 30px">
                                            <h5>Organisation Administrators &nbsp;
                                                <i class="flaticon2-information" data-container="body" data-toggle="kt-popover" data-placement="bottom"
                                                   data-content="As an organisation administrator, you can access and control nearly every aspect of the Go2Youth. Every organisation needs at least one organisation admin. These should be trusted staff members of your organisation. Note: Organisation admins have automatic access to every permission."></i>
                                            </h5>
                                            These people can access and control nearly every part of your account.<br><br>

                                            {{-- Admin Grid --}}
                                            <div class="row">
                                                <div class="col">
                                                    <div style="padding-bottom: 10px;">
                                                        <input v-model="xx.searchAdminQuery" type="search" class="form-control search-input" placeholder="Add new organisation administrator" name="query">
                                                        <search-admin :data="xx.people" :list="xx.admin" :filter-key="xx.searchAdminQuery"></search-admin>
                                                    </div>
                                                    <admin-table :members="xx.admin"></admin-table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="padding: 30px"></div>

                                    {{-- Regular Admins --}}
                                    <div class="row">
                                        <div class="col">
                                            <h5>Administrators &nbsp;
                                                <i class="flaticon2-information" data-container="body" data-toggle="kt-popover" data-placement="bottom"
                                                   data-content="These people have basic access to allocated areas of Go2Youth."></i>
                                            </h5>
                                            These people have access to certain areas of Go2Youth to either view or edit things.<br><br>

                                            {{-- Regular Grid --}}
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div style="padding-bottom: 10px;">
                                                        <input v-model="xx.searchQuery" type="search" class="form-control search-input" placeholder="Add new administrator" name="query">
                                                        <search-regular :data="xx.people" :list="xx.others" :filter-key="xx.searchQuery"></search-regular>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <regular-table :members="xx.others"></regular-table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<pre>@{{ $data }}</pre>
        -->

        {{-- Edit Permission Modal --}}
        <div class="modal fade" id="modal_permissions" tabindex="-1" role="dialog" aria-labelledby="Permissions" aria-hidden="true">
            <div class="modal-dialog modal-med" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: #32c5d2; padding: 20px;">
                        <h5 class="modal-title text-white" id="ModalLabel">@{{ xx.perms.firstname }}'s Permissions</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #F7F7F7; padding:20px;">
                        <div class="row">
                            <div class="col">
                                <div v-if="xx.perms.admin" class="alert alert-danger" role="alert">
                                    <div class="alert-text">You are unable to edit a Organisation Administrators permissions.</div>
                                </div>
                                <div v-else>You are about to edit @{{ xx.perms.name }}'s permissions<br><br></div>
                            </div>
                        </div>
                        {{-- Check-in --}}
                        <div class="row">
                            <label class="col-3 col-form-label"><b>Check-in</b></label>
                            <div class="col">
                                <i @click="togglePerm(xx.perms, 'checkin', 0)" v-if="xx.perms.checkin == 10 || xx.perms.admin" class="fa fa-2x fa-check perm-yes"></i>
                                <i @click="togglePerm(xx.perms, 'checkin', 10)" v-else class="fa fa-2x fa-check perm-no"></i>
                            </div>
                        </div>
                        {{-- People --}}
                        <div class="row">
                            <label class="col-3 col-form-label"><b>People</b></label>
                            <div class="col">
                                <i v-on:click="togglePerm(xx.perms, 'people', 0)" v-if="xx.perms.people == 10 && !xx.perms.admin" class="fa fa-2x fa-eye perm-yes"></i>
                                <i v-on:click="togglePerm(xx.perms, 'people', 10)" v-else class="fa fa-2x fa-eye perm-no"></i>
                                <i v-on:click="togglePerm(xx.perms, 'people', 0)" v-if="xx.perms.people == 20 && !xx.perms.admin" class="fa fa-2x fa-pen perm-yes"></i>
                                <i v-on:click="togglePerm(xx.perms, 'people', 20)" v-else class="fa fa-2x fa-pen perm-no"></i>
                                <i v-on:click="togglePerm(xx.perms, 'people', 0)" v-if="xx.perms.people == 30 || xx.perms.admin" class="fa fa-2x fa-user-cog perm-yes"></i>
                                <i v-on:click="togglePerm(xx.perms, 'people', 30)" v-else class="fa fa-2x fa-user-cog perm-no"></i>
                            </div>
                        </div>
                        {{-- Events --}}
                        <div class="row">
                            <label class="col-3 col-form-label"><b>Events</b></label>
                            <div class="col">
                                <i v-on:click="togglePerm(xx.perms, 'events', 0)" v-if="xx.perms.events == 10 && !xx.perms.admin" class="fa fa-2x fa-eye perm-yes"></i>
                                <i v-on:click="togglePerm(xx.perms, 'events', 10)" v-else class="fa fa-2x fa-eye perm-no"></i>
                                <i v-on:click="togglePerm(xx.perms, 'events', 0)" v-if="xx.perms.events == 20 && !xx.perms.admin" class="fa fa-2x fa-pen perm-yes"></i>
                                <i v-on:click="togglePerm(xx.perms, 'events', 20)" v-else class="fa fa-2x fa-pen perm-no" style="width: 50px"></i>
                                <i v-on:click="togglePerm(xx.perms, 'events', 0)" v-if="xx.perms.events == 30 || xx.perms.admin" class="fa fa-2x fa-user-cog perm-yes"></i>
                                <i v-on:click="togglePerm(xx.perms, 'events', 30)" v-else class="fa fa-2x fa-user-cog perm-no" style="width: 50px"></i>
                            </div>
                        </div>
                        {{-- Groups --}}
                        <div class="row">
                            <label class="col-3 col-form-label"><b>Groups</b></label>
                            <div class="col">
                                <i v-on:click="togglePerm(xx.perms, 'groups', 0)" v-if="xx.perms.groups == 10 && !xx.perms.admin" class="fa fa-2x fa-eye perm-yes"></i>
                                <i v-on:click="togglePerm(xx.perms, 'groups', 10)" v-else class="fa fa-2x fa-eye perm-no" style="width: 50px"></i>
                                <i v-on:click="togglePerm(xx.perms, 'groups', 0)" v-if="xx.perms.groups == 20 && !xx.perms.admin" class="fa fa-2x fa-pen perm-yes"></i>
                                <i v-on:click="togglePerm(xx.perms, 'groups', 20)" v-else class="fa fa-2x fa-pen perm-no" style="width: 50px"></i>
                                <i v-on:click="togglePerm(xx.perms, 'groups', 0)" v-if="xx.perms.groups == 30 || xx.perms.admin" class="fa fa-2x fa-user-cog perm-yes"></i>
                                <i v-on:click="togglePerm(xx.perms, 'groups', 30)" v-else class="fa fa-2x fa-user-cog perm-no" style="width: 50px"></i>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 20px">
                        <button type="button" class="btn btn-secondary" style="border: 0" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" v-on:click="savePermission">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $account->displayUpdatedBy() !!}
        </div>
    </div>


    {{-- Admin template --}}
    <script type="text/x-template" id="admin-template">
        <table width="100%" style="background: #fff;">
            <tbody>
            <template v-for="person in members">
                <tr style="height: 60px; border: solid 1px #eee;">
                    <td width="60px" style="padding: 10px"><img :src="person.photo" height="45px" style="border-radius: 50%"></td>
                    <td>
                        <span style="font-size: 1.1rem">@{{ person.name }}</span><br>
                        <span v-if="person.email" style='color:#999'><i class="fa fa-envelope" style="padding-right: 5px"></i>@{{ person.email }} </span>
                    </td>
                    <td width="40px" class="text-center" style="padding: 0px; margin: 0px">
                        <div style="height: 30px;"><i v-if="person.uid != xx.person.id" v-on:click="deleteMember(person)" class="fa fa-times-circle fa-2x member-delete font-metal" style="padding: 5px;"></i></div>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </script>

    {{-- Regular template --}}
    <script type="text/x-template" id="regular-template">
        <div class="table-responsive">
            <table style="background: #fff; min-width: 600px; width:99%">
                <thead v-if="members.length">
                <tr class="text-center">
                    <th width="60px" class="d-none d-md-block"></th>
                    <th width="30%"></th>
                    <th width="90px">Check-in</th>
                    <th width="90px">People</th>
                    <th width="90px">Events</th>
                    <th width="90px">Groups</th>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                <template v-for="person in members">
                    <tr v-on:click="updateMember(person)" style="height: 60px; border: solid 1px #eee;">
                        <td width="60px" class="d-none d-md-table-cell" style="padding: 10px 0px 0px 10px"><img :src="person.photo" height="45px" width="45px" style="border-radius: 50%"></td>
                        <td style="padding: 10px">
                            <span style="font-size: 1.1rem">@{{ person.name }}</span> <span v-if="person.admin"> (Org Admin)</span><br>
                            <span v-if="person.email" style='color:#999'><i class="fa fa-envelope" style="padding-right: 5px"></i>@{{ person.email }} </span>
                        </td>
                        <td class="text-center" style="padding: 0px; margin: 0px">
                            <i v-if="person.checkin == 10 || person.admin" class="fa fa-2x fa-check icon-admin"></i>
                        </td>
                        <td class="text-center" style="padding: 0px; margin: 0px">
                            <i v-if="person.people == 10 && !person.admin" class="fa fa-2x fa-eye icon-admin"></i>
                            <i v-if="person.people == 20 && !person.admin" class="fa fa-2x fa-pen icon-admin"></i>
                            <i v-if="person.people == 30 || person.admin" class="fa fa-2x fa-user-cog icon-admin"></i>
                        </td>
                        <td class="text-center" style="padding: 0px; margin: 0px">
                            <i v-if="person.events == 10 && !person.admin" class="fa fa-2x fa-eye icon-admin"></i>
                            <i v-if="person.events == 20 && !person.admin" class="fa fa-2x fa-pen icon-admin"></i>
                            <i v-if="person.events == 30 || person.admin" class="fa fa-2x fa-user-cog icon-admin"></i>
                        </td>
                        <td class="text-center" style="padding: 0px; margin: 0px">
                            <i v-if="person.groups == 10 && !person.admin" class="fa fa-2x fa-eye icon-admin"></i>
                            <i v-if="person.groups == 20 && !person.admin" class="fa fa-2x fa-pen icon-admin"></i>
                            <i v-if="person.groups == 30 || person.admin" class="fa fa-2x fa-user-cog icon-admin"></i>
                        </td>
                        <td></td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </script>

    {{--  Search template --}}
    <script type="text/x-template" id="search-template">
        <table width="100%" style="background: #fff" class="search-dropdown">
            <tbody>
            <template v-for="person in filteredData">
                <tr v-if="person.email" v-on:click="searchSelect(person)" class="search-row" style="height: 60px; border: solid 1px #eee;">
                    <td width="40px" style="padding: 10px;"><img :src="person.photo" height="30px" style="border-radius: 50%"></td>
                    <td>
                        <div class="search-title">@{{ person.name }}</div>
                        <div v-if="person.phone" class="search-info">@{{ person.phone }}</div>
                        <div v-if="person.email" class="search-info">@{{ person.email }}</div>
                    </td>
                    <td style="padding-right: 15px">
                        <div class="search-title">&nbsp;</div>
                        <div class="search-info text-right">@{{ person.type }}</div>
                        <div class="search-info text-right">
                            <span v-if="person.suburb">@{{ person.suburb }}</span>
                            <span v-if="person.suburb && person.state">, </span>
                            <span v-if="person.state">@{{ person.state }}</span>
                        </div>
                    </td>
                </tr>
                <tr v-if="!person.email" style="height: 60px; border: solid 1px #eee;">
                    <td width="40px" style="padding: 10px; opacity: .3"><img :src="person.photo" height="30px" style="border-radius: 50%"></td>
                    <td>
                        <div class="search-title"><span style="opacity: .3">@{{ person.name }}</span></div>
                        <div v-if="person.phone" class="search-info" style="opacity: .3">@{{ person.phone }}</div>
                        <div class="search-info text-danger">No Email - unable to add person</div>
                    </td>
                    <td style="padding-right: 15px; opacity: .3">
                        <div class="search-title">&nbsp;</div>
                        <div class="search-info text-right">@{{ person.type }}</div>
                        <div class="search-info text-right">
                            <span v-if="person.suburb">@{{ person.suburb }}</span>
                            <span v-if="person.suburb && person.state">, </span>
                            <span v-if="person.state">@{{ person.state }}</span>
                        </div>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </script>

@endsection

@section('page-styles')
    <link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css"/>
@endsection

@section('vendor-scripts')
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
    <script src="/js/vue.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});


        var xx = {
            searchAdminQuery: "{!! app('request')->input('query') !!}",
            searchQuery: "{!! app('request')->input('query') !!}",
            person: {id: "{{ Auth::user()->id }}", name: "{{ Auth::user()->name }}",},
            perms: {},
            admin: [], billing: [], others: [], people: [],
        };


        //
        // Organisation Admins
        //
        Vue.component('admin-table', {
            template: '#admin-template',
            props: ['members'],
            data: function () {
                return {xx: xx}
            },
            methods: {
                deleteMember: function (person) {
                    // get index of object to delete
                    var removeIndex = this.members.map(function (item) {
                        return item.uid;
                    }).indexOf(person.uid);
                    // Prompt to verify deletion
                    Swal.fire({
                        title: "Are you sure?",
                        html: "You are about to remove <b>" + person.name + "</b> as a organisation administrator.</b>",
                        cancelButtonText: "Cancel!",
                        confirmButtonText: "Yes, delete!",
                        showCancelButton: true,
                        reverseButtons: true,
                        allowOutsideClick: true,
                        animation: false,
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            cancelButton: 'btn btn-secondary',
                            popup: 'animated tada'
                        }
                    }).then(function (result) {
                        if (result.value) {
                            delAdminDB(person).then(function (result) {
                                if (result) {
                                    toastr.success('Deleted Organisation Administrator');
                                    window.location.href = "/account/admins/";
                                    this.xx.admin.splice(removeIndex, 1); // delete object
                                }
                            }.bind(this));
                        }
                    });
                },
            }
        })

        // register the search member component
        Vue.component('search-admin', {
            template: '#search-template',
            props: {data: Array, list: Array, filterKey: String},
            data: function () {
                return {xx: xx}
            },
            computed: {
                filteredData: function () {
                    var filterKey = this.filterKey && this.filterKey.toLowerCase()
                    var data = this.data
                    if (filterKey) {
                        data = data.filter(function (row) {
                            return Object.keys(row).some(function (key) {
                                return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                            })
                        })
                        return data.slice(0, 10); // Return first x records only
                    }
                    return []; // Return no records unless search string contains characters
                }
            },
            methods: {
                searchSelect: function (person) {
                    if (this.list && this.list.length) {
                        var exists = objectFindByKey(this.list, 'uid', person.uid);
                        if (!exists)
                            createAdmin(person);
                        else
                            toastr.error(person.name + ' is already an admin');
                    } else {
                        createAdmin(person);
                    }
                    this.xx.searchAdminQuery = '';
                },
            }
        })


        //
        // Regular Admins
        //
        Vue.component('regular-table', {
            template: '#regular-template',
            props: ['members'],
            data: function () {
                return {xx: xx}
            },
            methods: {
                updateMember: function (person) {
                    this.xx.perms = JSON.parse(JSON.stringify(person));
                    $('#modal_permissions').modal('show');
                },
            },
        })

        // register the search member component
        Vue.component('search-regular', {
            template: '#search-template',
            props: {data: Array, list: Array, filterKey: String},
            data: function () {
                return {xx: xx}
            },
            computed: {
                filteredData: function () {
                    var filterKey = this.filterKey && this.filterKey.toLowerCase()
                    var data = this.data
                    if (filterKey) {
                        data = data.filter(function (row) {
                            return Object.keys(row).some(function (key) {
                                return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                            })
                        })
                        return data.slice(0, 10); // Return first x records only
                    }
                    return []; // Return no records unless search string contains characters
                }
            },
            methods: {
                searchSelect: function (person) {
                    if (this.list && this.list.length) {
                        var exists = objectFindByKey(this.list, 'uid', person.uid);
                        if (!exists) {
                            this.list.push(person);
                            this.xx.perms = JSON.parse(JSON.stringify(person));
                            this.xx.perms.checkin = null;
                            this.xx.perms.events = null;
                            this.xx.perms.groups = null;
                            $('#modal_permissions').modal('show');
                        }
                        else
                            toastr.error(person.name + ' is already an admin');
                    } else {
                        this.list.push(person);
                        this.xx.perms = JSON.parse(JSON.stringify(person));
                        this.xx.perms.checkin = null;
                        this.xx.perms.events = null;
                        this.xx.perms.groups = null;
                        $('#modal_permissions').modal('show');
                    }
                    this.xx.searchQuery = '';
                },
            }
        })

        var vue_admin = new Vue({
            el: '#vue-app',
            data: {xx: xx},
            created: function () {
                this.getAdmins();
            },
            methods: {
                getAdmins: function () {
                    $.getJSON('/data/admins', function (data) {
                        this.xx.others = data[0];
                        this.xx.admin = data[1];
                        this.xx.billing = data[2];
                        this.xx.people = data[3];
                    }.bind(this));
                },
                togglePerm: function (obj, key, val) {
                    this.xx.perms = {};  // empty object
                    this.xx.perms = obj; // copy from modal object
                    //alert(key + ':' + val + ' obj:' + obj.name);
                    if (key == 'checkin')
                        obj.checkin = val;
                    if (key == 'people')
                        this.xx.perms.people = val;
                    if (key == 'events')
                        this.xx.perms.events = val;
                    if (key == 'groups')
                        this.xx.perms.groups = val;

                },
                savePermission: function () {
                    updateAdminDB(this.xx.perms).then(function (result) {
                        if (result) {
                            window.location.href = "/account/admins/";
                        }
                    }.bind(this));
                    $('#modal_permissions').modal('hide');
                }
            },
        });

        //render: function (h) {
        //    return h(DraggableList)
        // }
        //render: (h) => h(DraggableList),

        function createAdmin(person) {
            // Add admin + show modal
            createAdminDB(person).then(function (result) {
                if (result) {
                    window.location.href = "/account/admins/";
                }
            }.bind(this));
        }
        //
        // Create permission in Database and return a 'promise'
        //
        function createAdminDB(person) {
            //console.log('Creating admin'+ person.name);
            return new Promise(function (resolve, reject) {
                $.ajax({
                    url: '/account/admin/add-admin',
                    type: 'POST',
                    data: person,
                    success: function (result) {
                        //console.log('DB create admin:[' + result.id + '] ' + person.name);
                        resolve(person);
                    },
                    error: function (result) {
                        alert("Something went wrong creating admin " + person.name + '. Please refresh the page to resync.');
                        //console.log('DB created person FAILED:[' + result.id + '] ' + person.name);
                        reject(false);
                    }
                });
            });
        }

        //
        // Update Admin in Database and return a 'promise'
        //
        function updateAdminDB(permission) {
            //console.log('Updating permission:[' + permission.id + '] ' + permission.name);
            return new Promise(function (resolve, reject) {
                $.ajax({
                    url: '/account/admin/update-permissions/' + permission.id,
                    type: 'POST',
                    data: permission,
                    success: function (result) {
                        delete permission._method;
                        //console.log('DB updated permission:[' + permission.id + '] ' + permission.name);
                        resolve(permission);
                    },
                    error: function (result) {
                        alert("Something went wrong updating " + permission.name + '. Please refresh the page to resync.');
                        //console.log('DB updated permission FAILED:[' + permission.id + '] ' + permission.name);
                        reject(false);
                    }
                });
            });
        }

        //
        // Delete Admin in Database and return a 'promise'
        //
        function delAdminDB(person) {
            //console.log('Deleting person:[' + person.id + '] ' + person.name);
            return new Promise(function (resolve, reject) {
                person._method = 'delete';
                $.ajax({
                    url: '/account/admin/' + person.id,
                    type: 'POST',
                    data: person,
                    success: function (result) {
                        delete person._method;
                        //console.log('DB deleted admin:[' + person.id + '] ' + person.name);
                        resolve(person);
                    },
                    error: function (result) {
                        alert("Something went wrong. Please refresh the page to resync.");
                        //console.log('DB deleted admin FAILED:[' + person.id + '] ' + person.name);
                        reject(false);
                    }
                });
            });
        }

        // Search through array of object with given 'key' and 'value'
        function objectFindByKey(array, key, value) {
            for (var i = 0; i < array.length; i++) {
                if (array[i][key] == value) {
                    return array[i];
                }
            }
            return null;
        }
    </script>
@endsection
