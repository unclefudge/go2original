{{-- Household Info --}}
<div class="m-portlet">
    <div class="m-portlet__body">
        @if ($people->households->count())
            @foreach ($people->households as $household)
                {{-- Household Name --}}
                @if ($loop->first)
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-8"><h4>{{ $household->name }}</h4></div>
                        <div class="col-4"><a href="#" class="pull-right" data-toggle="modal" data-target="#modal_household"> Edit</a></div>
                    </div>
                @else
                    <hr>
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col"><h4>{{ $household->name }}</h4></div>
                    </div>
                @endif

                {{-- Adults --}}
                @if ($household->adults()->count())
                    <table class="table table-hover m-table" width="100%">
                        @foreach ($household->adults()->sortby('firstname') as $member)
                            <tr>
                                <td>
                            <span>
                                <span style="font-size: 1.1rem">{{ $member->name }}</span><br>
                                {!! ($member->dob) ? "<span style='color:#999'>$member->age years </span>" : '' !!}
                            </span>
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
                            <tr>
                                <td>
                                    <span style="font-size: 1.1rem">{{ $member->name }}</span><br>
                                    {!! ($member->dob) ? "<span style='color:#999'>$member->age years </span>" : '' !!}
                                    {!! ($member->dob && $member->grade) ? "<span style='color:#999'> - </span>" : '' !!}
                                    {!! ($member->grade) ? "<span style='color:#999'> Grade $member->grade</span>" : '' !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            @endforeach
        @else
            {{-- No Household --}}
            <div class="row" style="padding-bottom: 10px">
                <div class="col-8"><h4>Household</h4></div>
                <div class="col-4"><a href="#" class="pull-right" data-toggle="modal" data-target="#modal_household"> Edit</a></div>
            </div>
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

