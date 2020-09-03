@extends('layout/main')

@section('bodystyle')
    @if ($user->status)
        style="background-image: url(/img/head-purple.jpg)"
    @else
        style="background-image: url(/img/head-darkgrey.jpg)"
    @endif
@endsection

@section('subheader')
    @include('people/_header')
@endsection

@section('content')
    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="container-fluid">
            <div class="row">
                @include('people/_sidebar')
                {{-- Main Content --}}
                <div class="col">
                    @include('people/_sidebar-mobile')
                    <div class="row">
                        <div class="col">
                            {{-- Overview --}}
                            <div class="kt-portlet">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">Medical</h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="row" style="padding-bottom: 10px">
                                        <div class="col">An unrealised dream...<br><br><br><br></div>
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
            {!! $user->displayUpdatedBy() !!}
        </div>
    </div>
@endsection

@section('page-styles')
    <link href="/css/slim.min.css" rel="stylesheet">
@endsection

@section('vendor-scripts')
    <script src="/js/slim.kickstart.min.js"></script>
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
    <script src="/js/people-shared-functions.js"></script>
    <script type="text/javascript">

    </script>
@endsection
