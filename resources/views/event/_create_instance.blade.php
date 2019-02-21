{{-- Create Event Instance Modal --}}
<div class="modal fade" id="modal_create_instance" tabindex="-1" role="dialog" aria-labelledby="Attendance" aria-hidden="true">
    <div class="modal-dialog modal-med" role="document">
        <div class="modal-content">
            {!! Form::model('instance', ['action' => ['Event\EventInstanceController@store']]) !!}
            {!! Form::hidden('eid', $event->id) !!}
            {!! Form::hidden('cdate', $date) !!}
            <div class="modal-header" style="background: #32c5d2; padding: 20px;">
                <h5 class="modal-title text-white" id="ModalLabel">Create Past Ckeck-ins</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #F7F7F7; padding:20px;">
                <div class="alert m-alert m-alert--default text-center" role="alert">
                    This allows you to create a past event of a <b>{{ $event->name }}</b> so you can manually check-in students/volunteers.
                </div>
                {{-- Date --}}
                <div class="row">
                    <div class="col">
                        <div class="form-group {!! fieldHasError('dob', $errors) !!}">
                            {!! Form::label('pastdate', 'Date', ['class' => 'control-label']) !!}
                            <div class="input-group date">
                                {!! Form::text('pastdate', null, ['class' => 'form-control m-input datepicker', 'style' => 'background:#FFF', 'readonly', 'required', 'id' => 'pastdate']) !!}
                            </div>
                        </div>
                    </div>
                    </div>

                {{-- Name --}}
                <div class="row">
                    <div class="col">
                        <div class="form-group m-form__group {!! fieldHasError('name', $errors) !!}">
                            {!! Form::label('name', 'Event Name', ['class' => 'form-control-label']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => $event->name]) !!}
                            {!! fieldErrorMessage('name', $errors) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 20px">
                <button type="button" class="btn btn-secondary" style="border: 0" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" style="display: none" id="but_create_event">Create</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


{{-- Delete Event Instance Modal --}}
<div class="modal fade" id="modal_delete_instance" tabindex="-1" role="dialog" aria-labelledby="Attendance" aria-hidden="true">
    <div class="modal-dialog modal-med" role="document">
        <div class="modal-content">
            {!! Form::model('instance', ['action' => ['Event\EventInstanceController@store']]) !!}
            {!! Form::hidden('eid', $event->id) !!}
            <div class="modal-header" style="background: #32c5d2; padding: 20px;">
                <h5 class="modal-title text-white" id="ModalLabel">Create Past Ckeck-ins</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #F7F7F7; padding:20px;">
                <div class="alert m-alert m-alert--default text-center" role="alert">
                    This allows you to create a past event of a <b>{{ $event->name }}</b> so you can manually check-in students/volunteers.
                </div>
                {{-- Date --}}
                <div class="row">
                    <div class="col">
                        <div class="form-group {!! fieldHasError('dob', $errors) !!}">
                            {!! Form::label('pastdate', 'Date', ['class' => 'control-label']) !!}
                            <div class="input-group date">
                                {!! Form::text('pastdate', null, ['class' => 'form-control m-input datepicker', 'style' => 'background:#FFF', 'readonly', 'required', 'id' => 'pastdate']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Name --}}
                <div class="row">
                    <div class="col">
                        <div class="form-group m-form__group {!! fieldHasError('name', $errors) !!}">
                            {!! Form::label('name', 'Event Name', ['class' => 'form-control-label']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => $event->name]) !!}
                            {!! fieldErrorMessage('name', $errors) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 20px">
                <button type="button" class="btn btn-secondary" style="border: 0" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" style="display: none" id="but_save_event">Save</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

