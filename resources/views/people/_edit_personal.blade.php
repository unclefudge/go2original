{{-- Edit Profile Modal --}}
<div class="modal fade" id="modal_personal" tabindex="-1" role="dialog" aria-labelledby="Profile" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {!! Form::model($people, ['method' => 'PATCH', 'action' => ['People\PeopleController@update', $people->id]]) !!}
            <div class="modal-header" style="background: #32c5d2">
                <h5 class="modal-title text-white" id="ModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #F7F7F7; padding:20px; border-bottom: 1px solid #ddd">
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
                {{-- Phone + Email --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {!! fieldHasError('phone', $errors) !!}">
                            {!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}
                            {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                            {!! fieldErrorMessage('phone', $errors) !!}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group {!! fieldHasError('email', $errors) !!}">
                            {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
                            {!! Form::text('email', null, ['class' => 'form-control']) !!}
                            {!! fieldErrorMessage('email', $errors) !!}
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
                            {!! Form::select('state', $ozstates::all(), 'TAS', ['class' => 'form-control m-bootstrap-select m_selectpicker',]) !!}
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
            <div class="modal-body" style="padding:20px; border-bottom: 1px solid #ddd">
                {{-- Additional Info --}}
                <div class="row">
                    {{-- Gender --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group {!! fieldHasError('gender', $errors) !!}">
                            {!! Form::label('gender', 'Gender', ['class' => 'control-label']) !!}
                            {!! Form::select('gender', ['' => 'Gender', 'Male' => 'Male', 'Female' => 'Female'], null, ['class' => 'form-control m-bootstrap-select m_selectpicker',]) !!}
                            {!! fieldErrorMessage('gender', $errors) !!}
                        </div>
                    </div>
                    {{-- Birthday --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group {!! fieldHasError('dob', $errors) !!}">
                            {!! Form::label('dob', 'Birthday', ['class' => 'control-label']) !!}
                            <div class="input-group date">
                                {!! Form::text('dob', ($people->dob) ? $people->dob->format('d/m/Y') : '', ['class' => 'form-control m-input', 'style' => 'background:#FFF', 'readonly', 'id' => 'm_datepicker_2']) !!}
                                        <!--<input type="text" name="dob" class="form-control m-input" readonly="" style="background-color: #fff" placeholder="Select date" id="m_datepicker_2"> -->
                            </div>
                        </div>
                    </div>

                    {{-- Spacer --}}
                    <div class="col-lg-2">
                    </div>

                    {{-- Type --}}
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group {!! fieldHasError('type', $errors) !!}">
                            {!! Form::label('type', 'Type', ['class' => 'control-label']) !!}
                            {!! Form::select('type', $people_types, null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                            {!! fieldErrorMessage('type', $errors) !!}
                        </div>
                    </div>
                </div>

                {{-- Student Info --}}
                <div id="fields_student">
                    <hr>
                    <div class="row">
                        <div class="col-lg-3 col-md-12">
                            <br><h6>Student Details</h6>
                        </div>
                        {{-- Grade --}}
                        <div class="col-lg-2 col-md-3">
                            <div class="form-group {!! fieldHasError('grade', $errors) !!}">
                                {!! Form::label('grade', 'Grade', ['class' => 'control-label']) !!}
                                {!! Form::select('grade', ['' => 'Grade', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12'], null, ['class' => 'form-control m-bootstrap-select m_selectpicker',]) !!}
                                {!! fieldErrorMessage('grade', $errors) !!}
                            </div>
                        </div>
                        {{-- School --}}
                        <div class="col-lg-5 col-md-9">
                            {{--}}
                            <div class="form-group {!! fieldHasError('school_id', $errors) !!}">
                                {!! Form::label('school_id', 'School', ['class' => 'control-label']) !!}
                                {!! Form::select('school_id', \App\Models\Account\Account::find(1)->schoolsSelect(), null, ['class' => 'form-control m-bootstrap-select m_selectpicker', 'id' => 'school_id']) !!}
                                {!! fieldErrorMessage('school_id', $errors) !!}
                            </div>--}}
                            <div class="form-group">
                                <label for="school_id" class="control-label">School <span id="loader" style="visibility: hidden"><i class="fa fa-spinner fa-spin"></i></span></label>
                                <select name="school_id" class="form-control select2" id="school_id">
                                    @foreach (\App\Models\Account\Account::find(1)->schools->sortBy('name') as $key => $value)
                                        <option value="{{ $key }}" {{ ($people->school_id == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- Media Consent --}}
                        <div class="col-lg-2 col-md-3">
                            <div class="form-group {!! fieldHasError('media_consent', $errors) !!}">
                                {!! Form::label('media_consent', 'Media Consent', ['class' => 'control-label']) !!}
                                {!! Form::select('media_consent', ['1' => 'Yes', '0' => 'No'], ($people->media_consent) ? 1 : 0, ['class' => 'form-control m-bootstrap-select m_selectpicker',]) !!}
                                {!! fieldErrorMessage('media_consent', $errors) !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Volunteer Info --}}
                <div id="fields_volunteer">
                    <hr>
                    <div class="row">
                        <div class="col-lg-3 col-md-12">
                            <br><h6>WWC Registration</h6>
                        </div>
                        {{-- WWC No. --}}
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group {!! fieldHasError('wwc_no', $errors) !!}">
                                {!! Form::label('wwc_no', 'No.', ['class' => 'control-label']) !!}
                                {!! Form::text('wwc_no', null, ['class' => 'form-control']) !!}
                                {!! fieldErrorMessage('wwc_no', $errors) !!}
                            </div>
                        </div>
                        {{-- WWC Expiry --}}
                        <div class="col-lg-3 col-md-6">
                            <!--
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" placeholder="Select date" id="m_datetimepicker_6">
                                <div class="input-group-append">
							<span class="input-group-text">
							<i class="la la-calendar glyphicon-th"></i>
							</span>
                                </div>
                            </div>-->

                            <div class="form-group {!! fieldHasError('wwc_exp', $errors) !!}">
                                {!! Form::label('wwc_exp', 'Expiry', ['class' => 'control-label']) !!}
                                <div class="input-group date">
                                    {!! Form::text('wwc_exp', ($people->wwc_exp) ? $people->wwc_exp->format('d/m/Y') : '', ['class' => 'form-control m-input', 'style' => 'background:#FFF', 'readonly', 'id' => 'm_datepicker_2']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer" style="background-color: #F7F7F7; padding: 20px">
                <button type="button" class="btn btn-secondary" style="border: 0; background: #F7F7F7" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


