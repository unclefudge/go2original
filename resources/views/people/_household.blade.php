{{-- Household Info --}}
<div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head kt-portlet__head--noborder">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                @if ($user->households->count())
                    {{ $user->households->sortBy('name')->first()->name }}
                @else
                    Household
                @endif
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <a href="#" class="btn btn-light btn-icon-sm" data-toggle="modal" data-target="#modal_household">Edit</a>
        </div>
    </div>
    <div class="kt-portlet__body">
        @if ($user->households->count())
            @foreach ($user->households->sortBy('name') as $household)
                {{-- Household Name --}}
                @if (!$loop->first)
                    <hr>
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col"><h4>{{ $household->name }}</h4></div>
                    </div>
                @endif

                {{-- Adults --}}
                @if ($household->adults()->count())
                    <table class="table table-hover m-table" width="100%">
                        @foreach ($household->adults()->sortby('firstname') as $member)
                            <tr class="household-link" style="cursor: pointer" id="h{{ $household->id }}-u{{ $member->id }}">
                                <td>
                                    <div style="font-size: 1.1rem">{{ $member->name }} {!! ($member->id == $household->head->id) ? "<i class='fa fa-star' style='font-size: 11px; color: #32c5d2'></i>" : '' !!}</div>
                                    <div> {!! ($member->dob) ? "<span style='color:#999'>$member->age years </span>" : '' !!}</div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif

                {{-- Students --}}
                @if ($household->students()->count())
                    <div class="row">
                        <div class="col" style='color:#999'>CHILDREN</div>
                    </div>
                    <table class="table table-hover m-table" width="100%">
                        @foreach ($household->students()->sortby('firstname') as $member)
                            <tr class="household-link" style="cursor: pointer" id="h{{ $household->id }}-u{{ $member->id }}">
                                <td>
                                    <div style="font-size: 1.1rem">{{ $member->name }} {!! ($member->id == $household->head->id) ? "<i class='fa fa-star' style='font-size: 11px; color: #32c5d2'></i>" : '' !!}</div>
                                    <div>
                                        {!! ($member->dob) ? "<span style='color:#999'>$member->age years </span>" : '' !!}
                                        {!! ($member->dob && $member->grade) ? "<span style='color:#999'> - </span>" : '' !!}
                                        {!! ($member->grade_id) ? "<span style='color:#999'> $member->grade_name</span>" : '' !!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            @endforeach
        @else
            <div class="row justify-content-md-center">
                <div class="col-8 text-center">
                    <br>{{ $user->firstname }} doesn't belong to any household<br><br>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Edit House Modal --}}
<div class="modal fade" id="modal_household" tabindex="-1" role="dialog" aria-labelledby="Profile" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: #F7F7F7">
            <input v-model="xx.member_search" type="hidden" value="false">
            <input v-model="xx.household_search" type="hidden" value="false">
            {{--}}<input v-model="xx.join_household" type="hidden" value="false">--}}
            <div class="modal-header" style="background: #32c5d2">
                <h5 class="modal-title text-white" id="ModalLabel">
                    <span v-if="!xx.join_household_with.name">@{{ xx.person.firstname }}'s Households</span>
                    <span v-if="xx.join_household_with.name">Join a Household of @{{ xx.join_household_with.name }}</span>
                </h5>
                <button v-on:click="closeModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{--              --}}
                {{-- No Household --}}
                {{--              --}}
                <div v-if="xx.state_now == 'NoHousehold'" class="row">
                    <div class="col text-center">
                        <i class="fa fa-home fa-4x"></i><br>
                        <h3>No Household</h3>
                        {{ $user->firstname }} has not been added to a household yet.<br><br>
                        <button v-if="!xx.household_search" v-on:click="xx.household_search = !xx.household_search" type="button" class="btn btn-primary">Add household</button>
                        <div v-if="xx.household_search">
                            <div class="input-group">
                                <input v-model="xx.searchQuery" type="search" class="form-control" placeholder="Search for someone" name="query">
                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                            </div>
                            {{-- Search for household --}}
                            <search-member :data="xx.people" :filter-key="xx.searchQuery"></search-member>
                        </div>
                        <br><br>
                    </div>
                </div>

                {{--                  --}}
                {{-- Single Household --}}
                {{--                  --}}
                <div v-if="xx.state_now == 'SingleHousehold'">
                    <div class="form-group row">
                        {!! Form::label('name', 'Household', ['class' => 'col-2 col-form-label']) !!}
                        <div class="col-10">
                            <input v-model="xx.household.name" type="text" class="form-control">
                            {!! fieldErrorMessage('name', $errors) !!}
                        </div>
                    </div>

                    {{-- Members Grid --}}
                    <div class="row">
                        <div class="col">
                            <members-table></members-table>
                        </div>
                    </div>
                    <br>

                    {{-- Add Member --}}
                    <div v-if="!xx.member_search" class="row">
                        <div class="col">
                            <button v-on:click="xx.member_search = !xx.member_search" type="button" class="btn btn-primary btn-sm">Add person</button>
                            <button v-on:click="xx.state_now = 'MultiHousehold'" type="button" class="btn btn-outline-primary btn-sm primary pull-right">Other households</button>
                        </div>
                    </div>

                    {{-- Search for member --}}
                    <div v-if="xx.member_search" class="row">
                        <div class="col">
                            <div class="input-group">
                                <input v-model="xx.searchQuery" type="search" class="form-control" placeholder="Search for someone" name="query">
                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                            </div>
                            <search-member :data="xx.people" :filter-key="xx.searchQuery"></search-member>
                        </div>
                    </div>
                </div>


                {{--                     --}}
                {{-- Multiple Households --}}
                {{--                     --}}
                <div v-if="xx.state_now == 'MultiHousehold'">
                    <div class="row">
                        <div class="col">
                            <households-table :households="xx.households" :members="xx.members"></households-table>
                        </div>
                    </div>


                    {{-- Add Household --}}
                    <div class="row">
                        <div v-if="!xx.household_search" class="col">
                            <button v-on:click="xx.household_search = !xx.household_search" type="button" class="btn btn-primary btn-sm">Add household</button>
                        </div>
                        <div v-if="xx.household_search" class="col">
                            <div class="input-group">
                                <input v-model="xx.searchQuery" type="search" class="form-control" placeholder="Search for someone" name="query">
                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                            </div>
                            {{-- Search for household --}}
                            <search-member :data="xx.people" :filter-key="xx.searchQuery"></search-member>
                        </div>
                    </div>

                </div>


                {{-- Join Household Table --}}
                <div v-if="xx.state_now == 'JoinHousehold'" class="row">
                    <div v-if="xx.households2.length" class="col">
                        <households-table :households="xx.households2" :members="xx.members2"></households-table>
                    </div>
                    <div v-if="!xx.households2.length" class="col text-center">
                        <button v-on:click="createHousehold" type="button" class="btn btn-outline-primary btn-sm">Create new household with @{{ xx.person.name }} and @{{ xx.join_household_with.name }}</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background: #fff">
                <button v-on:click="closeModal" type="button" class="btn btn-secondary" style="border: 0;">Close</button>
                <button v-if="xx.state_now == 'SingleHousehold'" v-on:click="saveHousehold" type="submit" class="btn btn-primary">Save</button>
            </div>

            <!--<pre>@{{ $data }}</pre>
            -->
        </div>
    </div>
</div>

{{-- Members template --}}
<script type="text/x-template" id="members-template">
    <table width="100%" style="background: #fff">
        <tbody>
        <template v-for="person in xx.household.members">
            <tr style="height: 60px; border: solid 1px #eee;">
                <td width="60px" style="padding: 10px"><img :src="person.photo" height="45px" style="border-radius: 50%"></td>
                <td>
                    <span style="font-size: 1.1rem">@{{ person.name }}</span><br>
                    <span v-if="person.phone" style='color:#999; margin-right:15px'><i class="fa fa-phone" style="padding-right: 5px"></i> @{{ person.phone }} </span>
                    <span v-if="person.email" style='color:#999'><i class="fa fa-envelope" style="padding-right: 5px"></i>@{{ person.email }} </span>
                </td>
                <td width="40px" class="text-center" style="padding: 0px; margin: 0px">
                    <div v-if="xx.household.uid == person.uid" style="height: 30px"><i class="fa fa-star member-star-head"></i></div> {{-- head member--}}
                    <div v-if="xx.household.uid != person.uid" style="height: 30px"><i v-on:click="headMember(person)" class="fa fa-star member-star"></i></div> {{-- regular member --}}
                    <div style="height: 30px;"><i v-on:click="deleteMember(person)" class="fa fa-trash-alt member-delete" style="padding: 5px;"></i></div>
                </td>
            </tr>
        </template>
        </tbody>
    </table>
</script>

{{-- Households template --}}
<script type="text/x-template" id="households-template">
    <ul style="list-style-type: none; margin: 0; padding: 0;">
        <li v-for="household in households" style="background: #fff; padding: 20px; margin-bottom: 20px">
            <h4>@{{ household.name }} <span v-if="xx.state_now == 'MultiHousehold'" v-on:click="selectHousehold(household)" class="household-edit">Edit</span></h4>
            <table width="100%" style="background: #fff">
                <tbody>
                <template v-for="person in members">
                    <tr v-if="person.hid == household.id">
                        <td width="60px" style="padding: 0px 15px 5px 20px"><img :src="person.photo" height="25px" style="border-radius: 50%"></td>
                        <td>@{{ person.name }}</td>
                    </tr>
                </template>
                </tbody>
            </table>
            <br>
            <button v-if="xx.state_now == 'JoinHousehold'" v-on:click="joinHousehold(household)" type="button" class="btn btn-primary btn-sm">Join Household</button>
        </li>
        <li>
            <button v-if="xx.state_now == 'JoinHousehold'" v-on:click="createHousehold" type="button" class="btn btn-outline-primary btn-sm">Create new household with @{{ xx.person.name }} and @{{ xx.join_household_with.name }}</button>
        </li>
    </ul>
</script>

{{--  Search template --}}
<script type="text/x-template" id="search-template">
    <table width="100%" style="background: #fff">
        <tbody>
        <template v-for="person in filteredData">
            <tr v-on:click="searchSelect(person)" class="search-row" style="height: 60px; border: solid 1px #eee;">
                <td width="40px" style="padding: 10px"><img :src="person.photo" height="30px" style="border-radius: 50%"></td>
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
        </template>
        </tbody>
    </table>
</script>
