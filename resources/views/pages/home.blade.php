@extends('layout/main')

@section('bodystyle')
    style="background-image: url(/img/head-purple.jpg)"
@endsection

@section('subheader')
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Dashboard</h3>
            <h4 class="kt-subheader__desc">Event & attendance summary</h4>
        </div>
    </div>
    @include('event/_create_event')
@endsection

@section('content')
    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="container-fluid">
            {{-- Dashboard --}}
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h4 class="kt-portlet__head-title">Dashboard</h4>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div>
                        aid: {{ session('aid') }}<br>
                        tz: {{ session('tz') }}<br>
                        df: {{ session('df') }}<br>
                        df-datepicker: {{ session('df-datepicker') }}<br>
                        df-moment: {{ session('df-moment') }}<br>
                        username: {{ Auth::user()->username }}<br>
                        events: {{ session('show_inactive_events') }}<br>
                        people: {{ session('show_inactive_people') }}

                        <br><br>
                        <?php

                        $utc = \Carbon\Carbon::createFromDate('2019', '03', '11', 'UTC');
                        $tas = \Carbon\Carbon::createFromDate('2019', '03', '11', 'Australia/Hobart');
                        $today = \Carbon\Carbon::today();
                        $todayEnd = \Carbon\Carbon::today(session('tz'))->endOfDay()->timezone('UTC');
                        $now = \Carbon\Carbon::now();
                        $utcTodayStart = \Carbon\Carbon::today(session('tz'))->timezone('UTC');
                        $utcTodayEnd = \Carbon\Carbon::today(session('tz'))->endOfDay()->timezone('UTC');

                        echo "UTC: " . $utc->toDateTimeString() . "<br>";
                        echo "TAS: " . $tas->toDateTimeString() . "<br>";
                        echo "today: " . $today->toDateTimeString() . "<br>";
                        echo "todayEnd: " . $todayEnd->toDateTimeString() . "<br>";
                        echo "now: " . $now->toDateTimeString() . "<br>";
                        echo "nowTAS: " . $now->timezone(session('tz'))->toDateTimeString() . "<br>";
                        echo "utcTodayStart: " . $utcTodayStart->toDateTimeString() . "<br>";
                        echo "utcTodayEnd: " . $utcTodayEnd->toDateTimeString() . "<br>";
                        ?>

                        <br><br>Testing1<br>
                        @foreach (\App\Models\Event\EventInstance::whereDate('start', '2019-03-11')->get() as $instance)
                            {{ $instance->name }} - {{ $instance->start }} - {{ $instance->start->timezone(session('tz'))->toDateTimeString() }}<br>
                        @endforeach

                        <br><br>Testing2<br>
                        @foreach (\App\Models\Event\EventInstance::whereBetween('start', [$utcTodayStart, $utcTodayEnd])->get() as $instance)
                            {{ $instance->name }} - {{ $instance->start }} - {{ $instance->start->timezone(session('tz'))->toDateTimeString() }}<br>
                        @endforeach
                    </div>

                </div>
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
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        $(document).ready(function () {

        });
    </script>
@endsection
