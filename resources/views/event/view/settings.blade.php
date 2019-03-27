@extends('layout/main')

@section('bodystyle')
    @if ($event->status)
        style="background-image: url(/img/head-purple.jpg)"
    @else
        style="background-image: url(/img/head-darkgrey.jpg)"
    @endif
@endsection

@section('subheader')
    @include('event/view/_header')
@endsection
@section('content')
    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="vue-app">
        <div class="container-fluid">
            <div class="row">
                @include('event/view/_sidebar')
                {{-- Main Content --}}
                <div class="col" style="height: 100% !important; min-height: 100% !important;">
                    @include('event/view/_sidebar-mobile')
                    <div class="row">
                        <div class="col-md-9">
                            {{-- Settings --}}
                            <div class="kt-portlet">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Event Settings
                                            <small>
                                                @if ($event->recur)
                                                    <i class="fa fa-redo" style="padding-left: 10px"></i> Recurring
                                                @else
                                                    <i class="fa fa-calendar" style="padding-left: 10px"></i> One-time
                                                @endif
                                            </small>
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="row">
                                        <div class="col">
                                            {!! Form::model($event, ['method' => 'PATCH', 'action' => ['Event\EventController@update', $event->id]]) !!}
                                            {{-- Name --}}
                                            <div class="form-group row {!! fieldHasError('name', $errors) !!}">
                                                {!! Form::label('name', 'Event Name', ['class' => 'col-md-2 control-label']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                                    {!! fieldErrorMessage('name', $errors) !!}
                                                </div>
                                            </div>

                                            {{-- Grades --}}
                                            <div class="form-group row {!! fieldHasError('grades', $errors) !!}">
                                                {!! Form::label('grades', 'Grades', ['class' => 'col-md-2 control-label']) !!}
                                                <div class="col-md-10">
                                                    <div class="kt-checkbox-inline">
                                                        <?php $grade_list = explode(',', $event->grades) ?>
                                                        @foreach (Auth::user()->account->grades->where('status', 1)->pluck('name', 'id')->toArray() as $id => $name)
                                                            <label class="kt-checkbox">
                                                                <input type="checkbox" name="grades[]" value="{{ $id }}" {{ (in_array($id, $grade_list)) ? 'checked' : '' }}> {{ $name }} <span></span>
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
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @include('event/view/_show_background')
                        </div>
                    </div>

                    {{-- Attendance --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="kt-portlet">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Registration Form</h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        @if ($event->status)
                                            <a href="#" class="btn btn-light btn-icon-sm" data-toggle="modal" data-target="#modal_create_instance">Edit</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="alert alert-secondary" role="alert">
                                                <div class="alert-text">
                                                    This is what your first time students will fill out the first time they enter your system. You'll notice the headers "1st Check-In", "2nd Check-In", "3rd Check-In", etc. We developed this system to allow you to collect different data across
                                                    multiple check-ins if you choose. This
                                                    was meant to speed up the registration process by allowing you to collect only the most important data you want quickly the first day, and collect the rest on a different day. But of course, you can collect everything at once if you want to. It's
                                                    up to you!
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-styles')
    <link href="/css/slim.min.css" rel="stylesheet">
@endsection

@section('vendor-scripts')
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
    <script src="/js/slim.kickstart.min.js"></script>
    <script src="/js/event-shared-functions.js" type="text/javascript"></script>
    <script type="text/javascript">
        // Form errors - show modal
        if ($('#formerrors').val() == 'event') {
            toastr.error('Failed to save event', 'Errors!', {timeOut: 9000})
        }

        $("#bg_image").click(function () {
            $("#modal_background").modal('show');
        });

    </script>
@endsection
