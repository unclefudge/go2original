<div class="m-portlet">
    <div class="m-portlet__body">
        {{-- Account Info --}}
        <div class="row" style="padding-bottom: 10px">
            <div class="col-10"><h4>Account Info</h4></div>
            <div class="col-2"><a href="#" class="pull-right" data-toggle="modal" data-target="#modal_account">Edit</a></div>
        </div>
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

