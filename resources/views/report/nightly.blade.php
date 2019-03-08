@extends('layouts/main')

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__body">
            <div class="row" style="padding-bottom: 10px">
                <div class="col-12"><h4>Nightly Loge <small>12:05am daily</small></h4></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-bordered table-hover order-column" id="table_list">
                        <thead>
                        <tr class="mytable-header">
                            <th width="5%"> #</th>
                            <th> Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files as $file)
                            <?php
                            $pass = false;
                            if (strpos(file_get_contents(storage_path("/app/log/nightly/$file")), 'ALL DONE - NIGHTLY COMPLETE') !== false)
                                $pass = true;
                            ?>
                            <tr>
                                <td>
                                    <div class="text-center">
                                        @if ($pass)
                                            <i class="fa fa-check font-green"></i>
                                        @else
                                            <i class="fa fa-times font-red"></i>
                                        @endif
                                    </div>
                                </td>
                                <td><a href="/log/1/nightly/{{ $file }}" target="_blank">{!! substr($file, 6, 2) !!}/{!! substr($file, 4, 2) !!}/{!! substr($file, 2, 2) !!}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('vendor-scripts')
@stop

@section('page-styles')
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
@stop