{{-- Household Vue Code --}}
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    var xx = {
        state_default: '', state_now: '',
        member_search: false, household_search: false, //join_household: false,
        person: {
            id: "{{ $user->id }}",
            name: "{{ $user->name }}",
            firstname: "{{ $user->firstname }}",
            lastname: "{{ $user->lastname }}",
            households: "{{ $user->households->count() }}"
        },
        household: {
            id: "{{ ($user->households->count()) ? $user->households->first()->id : 0 }}",
            name: "{{ ($user->households->count()) ? $user->households->first()->name : $user->lastname.' household' }}",
            uid: "{{ ($user->households->count()) ? $user->households->first()->head->id : 0 }}",
            members: [],
        },
        join_household_with: {
            uid: '',
            name: '',
        },
        searchQuery: "{!! app('request')->input('query') !!}",
        households: [], households2: [], members: [], members2: [], people: []
    };

    // register the members component
    Vue.component('members-table', {
        template: '#members-template',
        data: function () {
            return {xx: xx}
        },
        methods: {
            deleteMember: function (person) {
                // get index of object to delete
                var removeIndex = this.xx.household.members.map(function (item) {
                    return item.uid;
                }).indexOf(person.uid);

                // Prompt if person is current profile
                if (person.uid == this.xx.person.id) {
                    swal({
                        title: "Are you sure?",
                        html: "You are able to remove <b>" + this.xx.person.name + "</b> from this household.</b>",
                        cancelButtonText: "Cancel!",
                        confirmButtonText: "Yes, delete",
                        confirmButtonClass: "btn btn-danger",
                        showCancelButton: true,
                        reverseButtons: true,
                        allowOutsideClick: true
                    }).then(function (result) {
                        if (result.value)
                            this.xx.household.members.splice(removeIndex, 1); // delete object
                    });
                } else
                    this.xx.household.members.splice(removeIndex, 1); // delete object
            },
            headMember: function (person) {
                this.xx.household.uid = person.uid;
            },
        }
    })

    // register the search member component
    Vue.component('search-member', {
        template: '#search-template',
        props: {data: Array, filterKey: String},
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
                // Add single member to current household
                if (this.xx.state_now == 'SingleHousehold') {
                    this.xx.member_search = false;
                    var exists = objectFindByKey(this.xx.household.members, 'uid', person.uid);
                    if (!exists) {
                        person.hid = this.xx.household.id;
                        xx.household.members.push(person);
                    } else
                        toastr.error(person.name + ' is already a member of the household');
                }
                // Join a household
                if (this.xx.state_now == 'NoHousehold' || this.xx.state_now == 'MultiHousehold') {
                    $.getJSON('/data/household/members/' + person.uid, function (data) {
                        this.xx.households2 = data[0];
                        this.xx.members2 = data[2];
                        this.xx.search_household = false;
                        this.xx.state_now = 'JoinHousehold';
                        this.xx.join_household_with.uid = person.uid;
                        this.xx.join_household_with.name = person.name;
                    }.bind(this));
                }
            },
        }
    })

    // register the households component
    Vue.component('households-table', {
        template: '#households-template',
        props: {households: Array, members: Array},
        data: function () {
            return {xx: xx}
        },
        methods: {
            selectHousehold: function (household) {
                this.updateHouseholdArray(household, this.xx.members);
                this.xx.state_now = "SingleHousehold";
            },
            joinHousehold: function (household) {
                this.updateHouseholdArray(household, this.xx.members2);
                // Add current profile to household too
                this.xx.household.members.push({hid: household.id, uid: this.xx.person.id, name: this.xx.person.name})
                // Update database
                updateHouseholdDB(this.xx.household).then(function (result) {
                    if (result) window.location.href = "/people/" + this.xx.person.id;
                }.bind(this));
            },
            createHousehold: function () {
                createHousehold();
            },
            updateHouseholdArray: function (household, members) {
                // Create household record
                this.xx.household.id = household.id;
                this.xx.household.name = household.name;
                this.xx.household.uid = household.uid;
                // Add members
                this.xx.household.members = [];
                $.each(members, function (key, val) {
                    if (val.hid == this.xx.household.id) this.xx.household.members.push(val);
                }.bind(this));
            }
        }
    })

    var vue_household = new Vue({
        el: '#modal_household',
        data: {xx: xx,},
        created: function () {
            this.getHouseholds();
        },
        methods: {
            getHouseholds: function () {
                $.getJSON('/data/household/members/' + this.xx.person.id, function (data) {
                    this.xx.households = data[0];
                    this.xx.household.members = data[1];
                    this.xx.members = data[2];
                    this.xx.people = data[3];
                    // Set current state
                    if (this.xx.person.households == 0) this.xx.state_now = this.xx.state_default = 'NoHousehold';
                    if (this.xx.person.households == 1) this.xx.state_now = this.xx.state_default = 'SingleHousehold';
                    if (this.xx.person.households > 1) this.xx.state_now = this.xx.state_default = 'MultiHousehold';
                }.bind(this));
            },
            saveHousehold: function () {
                // Ensure if you delete members and leave only one remaining then delete whole household
                if (this.xx.household.members.length < 2) this.xx.household.members = [];

                updateHouseholdDB(this.xx.household).then(function (result) {
                    if (result) window.location.href = "/people/" + this.xx.person.id;
                }.bind(this));
            },
            createHousehold: function () {
                createHousehold();
            },
            closeModal: function () {
                this.xx.member_search = false;
                this.xx.household_search = false;
                this.xx.state_now = this.xx.state_default;
                this.xx.join_household_with.uid = '';
                this.xx.join_household_with.name = '';
                $("#modal_household").modal('hide');
            }
        },
    });

    //
    // Create household
    //
    function createHousehold() {
        this.xx.household.uid = this.xx.person.id; // set current profile as head of household
        this.xx.household.name = this.xx.person.lastname + ' household'; // set current profile as head of household
        this.xx.household.members = [];
        this.xx.household.members.push({uid: this.xx.person.id});
        this.xx.household.members.push({uid: this.xx.join_household_with.uid});

        // Store household to atabase
        createHouseholdDB(this.xx.household).then(function (result) {
            if (result) window.location.href = "/people/" + this.xx.person.id;
        }.bind(this));
    }

    //
    // Create household in Database Attendance and return a 'promise'
    //
    function createHouseholdDB(household) {
        //console.log('Creating household'+ household.name);
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: '/household',
                type: 'POST',
                data: household,
                success: function (result) {
                    //console.log('DB create household:[' + result.id + '] ' + household.name);
                    resolve(household);
                },
                error: function (result) {
                    alert("Something went wrong creating household " + household.name + '. Please refresh the page to resync household');
                    //console.log('DB created household FAILED:[' + result.id + '] ' + household.name);
                    reject(false);
                }
            });
        });
    }

    //
    // Update household in Database Attendance and return a 'promise'
    //
    function updateHouseholdDB(household) {
        //console.log('Updating household:[' + household.id + '] ' + household.name);
        return new Promise(function (resolve, reject) {
            household._method = 'patch';
            $.ajax({
                url: '/household/' + household.id,
                type: 'POST',
                data: household,
                success: function (result) {
                    delete household._method;
                    //console.log('DB updated household:[' + household.id + '] ' + household.name);
                    resolve(household);
                },
                error: function (result) {
                    alert("Something went wrong updating household " + household.name + '. Please refresh the page to resync household');
                    //console.log('DB updated household FAILED:[' + household.id + '] ' + household.name);
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