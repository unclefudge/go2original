{{-- Household Info --}}
<div class="m-portlet">
    <div class="m-portlet__body">
        <div class="row" style="padding-bottom: 10px">
            <div class="col-8"><h4>Household</h4></div>
            <div class="col-4"><a href="#" class="pull-right" data-toggle="modal" data-target="#modal_household"> Edit</a></div>
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

{{-- Edit House Modal --}}
<div class="modal fade" id="modal_household" tabindex="-1" role="dialog" aria-labelledby="Profile" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: #F7F7F7">
            {!! Form::model($people, ['method' => 'PATCH', 'action' => ['People\PeopleController@update', $people->id]]) !!}
            <div class="modal-header" style="background: #32c5d2">
                <h5 class="modal-title text-white" id="ModalLabel">Edit Household</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- First + Last Name --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group {!! fieldHasError('firstname', $errors) !!}">
                            {!! Form::label('firstname', 'First Name', ['class' => 'form-control-label']) !!}
                            {!! Form::text('firstname', null, ['class' => 'form-control', 'required']) !!}
                            {!! fieldErrorMessage('firstname', $errors) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! fieldHasError('lastname', $errors) !!}">
                            {!! Form::label('lastname', 'Last Name', ['class' => 'control-label']) !!}
                            {!! Form::text('lastname', null, ['class' => 'form-control', 'required']) !!}
                            {!! fieldErrorMessage('lastname', $errors) !!}
                        </div>
                    </div>
                </div>
                {{-- Email --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {!! fieldHasError('email', $errors) !!}">
                            {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
                            {!! Form::text('email', null, ['class' => 'form-control']) !!}
                            {!! fieldErrorMessage('email', $errors) !!}
                        </div>
                    </div>
                </div>
                {{-- Phone --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {!! fieldHasError('phone', $errors) !!}">
                            {!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}
                            {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                            {!! fieldErrorMessage('phone', $errors) !!}
                        </div>
                    </div>
                </div>

                {{-- Adddress --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {!! fieldHasError('address', $errors) !!}">
                            {!! Form::label('address', 'Address', ['class' => 'control-label']) !!}
                            {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'street address']) !!}
                            {!! fieldErrorMessage('address', $errors) !!}
                        </div>
                    </div>
                </div>
                {{-- Suburb + State + Postcode --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! fieldHasError('suburb', $errors) !!}">
                            {!! Form::text('suburb', null, ['class' => 'form-control', 'placeholder' => 'suburb']) !!}
                            {!! fieldErrorMessage('suburb', $errors) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {!! fieldHasError('state', $errors) !!}">
                            {!! Form::select('state', $ozstates::all(), 'TAS', ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                            {!! fieldErrorMessage('state', $errors) !!}
                        </div>
                    </div>
                    {{-- Postcode --}}
                    <div class="col-md-3">
                        <div class="form-group {!! fieldHasError('postcode', $errors) !!}">
                            {!! Form::text('postcode', null, ['class' => 'form-control', 'placeholder' => 'postcode']) !!}
                            {!! fieldErrorMessage('postcode', $errors) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

