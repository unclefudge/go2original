{{-- Header --}}
<div class="row" style="height:70px; background-image: url(/img/head-purple.jpg); border-bottom: 1px solid rgba(255, 255, 255, 0.1)">
    <div class="col text-center">
        <a href="/checkin"><img src="/img/logo-med.png" style="float: left; padding:5px 0px 5px 20px"></a>
        {{--}}<h1 class="text-white" style="padding-top: 10px">{{ $instance->name }} <i class="fa fa-edit" style="color: rgb(158, 172, 180); font-size: 13px; padding: 7px 20px; vertical-align: top; cursor: pointer;"></i> <span class="pull-right" style="font-size: 14px; padding-right: 20px">{!! \Carbon\Carbon::now()->timezone(session('tz'))->format(session('df'). " g:i a") !!}</span></h1>
--}}
        <h1 v-if="!xx.edit_name" class="text-white">@{{ xx.instance.name }} <i v-if="xx.estatus != 0" v-on:click="toggleEditName" class="fa fa-edit" style="color: #9eacb4; font-size: 13px; padding: 7px 20px ; vertical-align: top; cursor: pointer"></i>
        <span class="pull-right" style="font-size: 14px; padding-right: 20px">{!! \Carbon\Carbon::now()->timezone(session('tz'))->format(session('df'). " g:i a") !!}</span></h1>

        {{-- Edit Instance Name --}}
        <div v-if="xx.edit_name" style="padding-left: 5px">
            <div class="input-group" style="width: 50%; margin-left: auto; margin-right: auto; padding-top: 5px">
                <input v-model="xx.instance.name" type="text" class="form-control">
                <div class="input-group-append">
                    <span v-on:click="saveName" class="input-group-text" style="color: #FFFFFF; background: #34bfa3; padding: 0px 20px; cursor: pointer">Save</span>
                </div>
                <span></span>
            </div>
        </div>
    </div>
</div>