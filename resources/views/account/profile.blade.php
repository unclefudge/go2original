@inject('ozstates', 'App\Http\Utilities\Ozstates')
@inject('timezones', 'App\Http\Utilities\Timezones')
@extends('layout/main')

@section('bodystyle')
    style="background-image: url(/img/head-purple.jpg)"
@endsection

@section('subheader')
    @include('account/_header')
@endsection

@section('content')
    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="container-fluid">
            <div class="row">
                @include('account/_sidebar')
                {{-- Main Content --}}
                <div class="col" style="height: 100% !important; min-height: 100% !important;">
                    @include('account/_sidebar-mobile')
                    <div class="row">
                        <div class="col">
                            {{-- Account Info --}}
                            <div class="kt-portlet">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Organisation Details</h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        @if ($account->status)
                                            <a href="#" class="btn btn-light btn-icon-sm" data-toggle="modal" data-target="#modal_account">Edit</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="kt-portlet__body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row" style="padding: 5px 0px">
                                                <div class="col-3">Name</div>
                                                <div class="col">{!! $account->name !!}</div>
                                            </div>
                                            <div class="row" style="padding: 5px 0px">
                                                <div class="col-3">Slug</div>
                                                <div class="col">{!! $account->slug !!}</div>
                                            </div>
                                            <div class="row" style="padding: 5px 0px">
                                                <div class="col-3">Banner</div>
                                                <div class="col">{!! $account->banner !!}</div>
                                            </div>
                                            <hr class="field-hr">

                                            {{-- Localisation Info --}}
                                            <div class="row" style="padding-bottom: 10px">
                                                <div class="col-10"><h4>Localisation</h4></div>
                                            </div>
                                            <div class="row" style="padding: 5px 0px">
                                                <div class="col-3">Timezone</div>
                                                <div class="col">
                                                    @if ($account->timezone)
                                                        {!! $timezones::name($account->timezone)  !!}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row" style="padding: 5px 0px">
                                                <div class="col-3">Date format</div>
                                                <div class="col">{!! ($account->dateformat == 'd/m/Y') ? 'Day/Month/Year' : 'Month/Day/Year' !!}</div>
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

    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $account->displayUpdatedBy() !!}
        </div>
    </div>

    {{-- Edit Account Modal --}}
    <div class="modal fade" id="modal_account" tabindex="-1" role="dialog" aria-labelledby="Account" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                {!! Form::model($account, ['method' => 'PATCH', 'action' => ['Account\AccountController@update', $account->id]]) !!}
                <div class="modal-header" style="background: #32c5d2; padding: 20px;">
                    <h5 class="modal-title text-white" id="ModalLabel">Edit Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #F7F7F7; padding:20px;">
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
                                {!! Form::select('timezone',$timezones::all(), null, ['class' => 'form-control kt-selectpicker']) !!}
                                {!! fieldErrorMessage('timezone', $errors) !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group {!! fieldHasError('dateformat', $errors) !!}">
                                {!! Form::label('dateformat', 'Date Format', ['class' => 'control-label']) !!}
                                {!! Form::select('dateformat', ['d/m/Y' => 'Day/Month/Year', 'm/d/Y' => 'Month/Day/Year'], null, ['class' => 'form-control kt-selectpicker']) !!}
                                {!! fieldErrorMessage('dateformat', $errors) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px">
                    <button type="button" class="btn btn-secondary" style="border: 0" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('page-styles')
@endsection

@section('vendor-scripts')
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
    <script type="text/javascript">

    </script>
@endsection
