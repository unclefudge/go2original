@inject('timezones', 'App\Http\Utilities\Timezones')
{{-- Edit Account Modal --}}
<div class="modal fade" id="modal_account" tabindex="-1" role="dialog" aria-labelledby="Account" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: #F7F7F7">
            {!! Form::model($account, ['method' => 'PATCH', 'action' => ['Account\AccountController@update', $account->id]]) !!}
            <div class="modal-header" style="background: #32c5d2">
                <h5 class="modal-title text-white" id="ModalLabel">Edit Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Name + Slug --}}
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group m-form__group {!! fieldHasError('name', $errors) !!}">
                            {!! Form::label('name', 'Account Name', ['class' => 'form-control-label']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                            {!! fieldErrorMessage('name', $errors) !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group {!! fieldHasError('slug', $errors) !!}">
                            {!! Form::label('slug', 'Slug', ['class' => 'control-label']) !!}
                            {!! Form::text('slug', null, ['class' => 'form-control', 'required']) !!}
                            {!! fieldErrorMessage('slug', $errors) !!}
                        </div>
                    </div>
                </div>
                {{-- Banner --}}
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group {!! fieldHasError('banner', $errors) !!}">
                            {!! Form::label('banner', 'Banner', ['class' => 'control-label']) !!}
                            {!! Form::text('banner', null, ['class' => 'form-control']) !!}
                            {!! fieldErrorMessage('banner', $errors) !!}
                        </div>
                    </div>
                </div>

                <h4>Localisation</h4>
                {{-- Timezone --}}
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group {!! fieldHasError('timezone', $errors) !!}">
                            {!! Form::label('timezone', 'Timezone', ['class' => 'control-label']) !!}
                            {!! Form::select('timezone',$timezones::all(), null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                            {!! fieldErrorMessage('timezone', $errors) !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group {!! fieldHasError('dateformat', $errors) !!}">
                            {!! Form::label('dateformat', 'Date Format', ['class' => 'control-label']) !!}
                            {!! Form::select('dateformat', ['d/m/Y' => 'Day/Month/Year', 'm/d/Y' => 'Month/Day/Year'], null, ['class' => 'form-control m-bootstrap-select m_selectpicker',]) !!}
                            {!! fieldErrorMessage('dateformat', $errors) !!}
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


