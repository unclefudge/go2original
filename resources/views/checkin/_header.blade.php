{{-- Header --}}
<div class="row" style="height:70px; background-image: url(/img/head-purple.jpg); border-bottom: 1px solid rgba(255, 255, 255, 0.1)">
    <div class="col text-center">
        <a href="/event"><img src="/img/logo-med.png" style="float: left; padding:5px 0px 5px 20px"></a>
        <h1 class="text-white" style="padding-top: 10px">{{ $instance->name }} <span class="pull-right" style="font-size: 14px; padding-right: 20px">{!! \Carbon\Carbon::now()->timezone(session('tz'))->format(session('df'). " g:i a") !!}</span></h1>
    </div>
</div>