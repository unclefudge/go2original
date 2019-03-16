@extends('layouts/main')

@section('content')

    <?php $people = \App\User::find(67) ?>

    {{-- Member bar --}}
    <div class="mbar {{ (!$people->status) ? 'mbar-inactive' : '' }}">
        <!--<i class="iicon-user-member-bar hidden-xs-down"></i>-->

        <style>


        </style>
        <div class="mbar-name">
            {{-- Avatar --}}
            <div class="avatar-container" id="avatar">
                <img class="avatar-image" src="{{ $people->photoSmPath }}?<?=rand(1, 32000)?>" alt="Avatar">
                <div class="avatar-overlay">
                    <a href="#" class="avatar-edit" title="Edit" id="avatar-edit"><img class="avatar-icon" src="/img/icon-edit-avatar.png" height="35px"></a>
                </div>
            </div>
            <div class="mbar-fullname">{{ $people->firstname }} {{ $people->lastname }}</div>
            <span class="mbar-type">{{ $people->type }}</span>
            <span class="mbar-split">&nbsp;|&nbsp;</span>

            {{-- Status --}}
            <span class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" style="padding: 1px 1px 1px 8px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ ($people->status) ? 'Active' : 'Inactive' }}</button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ ($people->status) ? '#' : "/people/$people->id/status/1" }}">Active</a>
                    <a class="dropdown-item" href="{{ (!$people->status) ? '#' : "/people/$people->id/status/0" }}">Inactive</a>
                    @if (!$people->status)
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="but_del_person">Delete</a>
                    @endif
                </div>
            </span>
        </div>

        {{-- Righthand --}}
        <div class="mbar-menu">
            {{-- Status --}}
            <div class="mbar-actions">
                <span class="dropdown">
                <button class="btn  btn-secondary dropdown-toggle" type="button" style="padding: 5px 10px 5px 10px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ ($people->status) ? 'Active ' : 'Inactive ' }}</button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ ($people->status) ? '#' : "/people/$people->id/status/1" }}">Active</a>
                    <a class="dropdown-item" href="{{ (!$people->status) ? '#' : "/people/$people->id/status/0" }}">Inactive</a>
                    @if (!$people->status)
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="but_del_person">Delete</a>
                    @endif
                </div>
                </span>
            </div>
            <div class="mbar-info">
                <span>{{ $people->gender }} | {{ $people->age }}</span>
            </div>
        </div>
    </div>

    <div>
        tester
    </div>

@stop


@section('vendor-scripts')

@stop

@section('page-styles')
    <link href="/css/peopleheader.css" rel="stylesheet" type="text/css"/>
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
@stop
