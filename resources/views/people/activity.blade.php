@extends('layouts/main')

@section('content')
    @include('people/_header')

    <div class="row">
        <div class="col-lg-8">
            <div class="m-portlet">
                <div class="m-portlet__body">
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-12"><h4>Activity</h4></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="m-accordion m-accordion--bordered" id="m_accordion" role="tablist">
                                <?php $x = 1 ?>
                                @foreach($people->activity() as $act)
                                    <?php $x ++ ?>
                                    <div class="m-accordion__item">
                                        <div class="m-accordion__item-head collapsed"  role="tab" id="m_accordion_item_{{ $x }}_head" data-toggle="collapse" href="#m_accordion_item_{{ $x }}_body" aria-expanded=" false">
                                            <span class="m-accordion__item-icon">{!! $act->icon !!}</span>
                                        <span class="m-accordion__item-title">
                                            <div style="font-weight: 500">{!! $act->title !!}</div>
                                            <div>
                                                <small>{{ $act->date }}</small>
                                            </div>
                                        </span>
                                            <span class="m-accordion__item-mode"></span>
                                        </div>

                                        <div class="m-accordion__item-body collapse" id="m_accordion_item_{{ $x }}_body" role="tabpanel" aria-labelledby="m_accordion_item_{{ $x }}_head" data-parent="#m_accordion">
                                            <div class="m-accordion__item-content">
                                                <p>{!! $act->data !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $people->displayUpdatedBy() !!}
        </div>
    </div>
@stop


@section('vendor-scripts')
@stop

@section('page-styles')
    <link href="/css/slim.min.css" rel="stylesheet">
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script type="text/javascript">


    $("#avatar").click(function () {
        $("#modal_avatar_edit").modal('show');
    });

    $("#avatar-edit").click(function (e) {
        e.stopPropagation();
        $("#modal_avatar_edit").modal('show');
    });


</script>

@stop
