<div class="m-portlet">
    <div class="m-portlet__body">
        {{-- Personal Info --}}
        <div class="row" style="padding-bottom: 10px">
            <div class="col-8"><h4>Household</h4></div>
            <div class="col-4"><a href="#" class="pull-right" data-toggle="modal" data-target="#modal_household"> <i class="fa fa-edit" style="padding-right: 3px"></i><span class="d-none d-lg-inline">Edit</span></a></div>
        </div>
        @if (false)
            <div class="row">
                <div class="col-md-6">
                    <div class="row" style="padding: 5px 0px">
                        <div class="col-1">
                            @if ($people->gender == 'Male')
                                <i class="fa fa-2x fa-male"></i>
                            @elseif ($people->gender == 'Male')
                                <i class="fa fa-2x fa-female" style="padding-right: 5px"></i>
                            @else
                                <i class="fa fa-user" style="padding-right: 5px"></i>
                            @endif
                        </div>
                        <div class="col col-md-11">
                            {{ $people->type }} <br>
                            {!! ($people->dob) ? "$people->age years old &nbsp; (".$people->dob->format('M d Y').") <br>" : '' !!}
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="row" style="padding: 5px 0px">
                        <div class="col-1 col-md-3"><i class="fa fa-envelope" style="padding-right: 5px"></i> <span class="d-none d-md-inline">Email</span></div>
                        <div class="col col-md-9">{!! ($people->email) ? "<a href='mailto:$people->email'> $people->email</a>" : '-' !!}</div>
                    </div>
                    <div class="row" style="padding: 5px 0px">
                        <div class="col-1 col-md-3"><i class="fa fa-phone" style="padding-right: 5px"></i> <span class="d-none d-md-inline">Phone</span></div>
                        <div class="col col-md-9">{!! ($people->phone) ? "<a href='tel:'".preg_replace("/[^0-9]/", "", $people->phone)."> $people->phone </a>" : '-' !!}</div>
                    </div>
                    <div class="row" style="padding: 5px 0px">
                        <div class="col-1 col-md-3"><i class="fa fa-map-marker-alt" style="padding-right: 5px"></i> <span class="d-none d-md-inline">Address</span></div>
                        <div class="col col-md-9">{!! $people->address_formatted !!}</div>
                    </div>

                </div>
            </div>
        @else
            <div class="row justify-content-md-center">
                <div class="col-8 text-center">
                <br>{{ $people->firstname }} doesn't belong to any household<br><br>
                </div>
            </div>
        @endif

    </div>
</div>
