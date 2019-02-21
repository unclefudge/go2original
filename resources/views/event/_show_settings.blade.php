<div class="m-portlet">
    <div class="m-portlet__body">
        {!! Form::model($event, ['method' => 'PATCH', 'action' => ['Event\EventController@update', $event->id]]) !!}
        {{-- Event Settings --}}
        <div class="row" style="padding-bottom: 10px">
            <div class="col-10">
                <h4>Event Settings
                    <small style="color: #9eacb4">
                        @if ($event->recur)
                            <i class="fa fa-redo" style="padding-left: 10px"></i> Recurring
                        @else
                            <i class="fa fa-calendar" style="padding-left: 10px"></i> One-time
                        @endif
                    </small>
                </h4>
            </div>
        </div>
        {{-- Name --}}
        <div class="form-group m-form__group row {!! fieldHasError('name', $errors) !!}">
            {!! Form::label('name', 'Event Name', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                {!! fieldErrorMessage('name', $errors) !!}
            </div>
        </div>

        {{-- Grades --}}
        <div class="form-group m-form__group row {!! fieldHasError('name', $errors) !!}">
            {!! Form::label('grades', 'Grades', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-10">
                <div class="m-checkbox-inline">
                    <?php $grade_list = explode('<>', $event->grades) ?>
                    @foreach ([6, 7, 8, 9, 10, 11, 12] as $grade)
                        <label class="m-checkbox">
                            <input type="checkbox" name="grades[]" value="{{ $grade }}" {{ (in_array($grade, $grade_list)) ? 'checked' : '' }}> {{ $grade }} <span></span>
                        </label>
                    @endforeach
                </div>
                <span class="m-form__help">Grades displayed during check-in</span>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                @if ($event->status)
                    <button type="submit" class="btn btn-primary">Save</button>
                @endif
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

