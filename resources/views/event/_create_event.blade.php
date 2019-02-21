{{-- Create Event Modal --}}
<div class="modal fade" id="modal_create_event" tabindex="-1" role="dialog" aria-labelledby="Profile" aria-hidden="true">
    <div class="modal-dialog modal-med" role="document">
        <div class="modal-content">
            {!! Form::model('event', ['action' => ['Event\EventController@store']]) !!}
            <div class="modal-header" style="background: #32c5d2; padding: 20px;">
                <h5 class="modal-title text-white" id="ModalLabel">Create Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #F7F7F7; padding:20px;">
                {{-- Name --}}
                <div class="row">
                    <div class="col">
                        <div class="form-group m-form__group {!! fieldHasError('name', $errors) !!}">
                            {!! Form::label('name', "Event Name", ['class' => 'form-control-label']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Enter event name']) !!}
                            {!! fieldErrorMessage('name', $errors) !!}
                        </div>
                    </div>
                </div>
                {{-- Recurring --}}
                <div class="row">
                    <div class="col">
                        <div class="form-group m-form__group {!! fieldHasError('frequency', $errors) !!}">
                            <label for="frequency" class="form-control-label">Frequency *&nbsp;
                                <i class="fa fa-question-circle" data-container="body" data-toggle="m-tooltip" data-placement="right" title="" data-original-title="Will your event happen more than once?"></i>
                            </label>
                            {!! Form::select('frequency', ['' => 'Select frequency', 'recur' => 'Recurring Event', 'single' => 'One-Time Event'], null,
                            ['class' => 'form-control m-bootstrap-select m_selectpicker', 'id' => 'frequency']) !!}
                            {!! fieldErrorMessage('frequency', $errors) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 20px">
                <button type="button" class="btn btn-secondary" style="border: 0" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" style="display:none" id="but_create_event">Create</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


