@extends('layouts/main')

@section('subheader')
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">People</h3>
            </div>
            <div>
            <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
            </div>
        </div>
    </div>
@stop

@section('content')
<div>
    <h1>People -</h1>
</div>


@stop


@section('vendor-scripts')
@stop

@section('page-styles')
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
@stop
