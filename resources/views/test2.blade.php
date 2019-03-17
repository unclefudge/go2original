@extends('layouts/main')

@section('content')

    Testing Draggable
    <div id="app">
        <div class="row">
            <div class="col-3">
                <!-- Simple List -->
                <ul id="simpleList" class="list-group">
                    <li class="list-group-item">Cat</li>
                    <li class="list-group-item">Dog</li>
                    <li class="list-group-item">Cow</li>
                    <li class="list-group-item">Horse</li>
                </ul>
            </div>
            <div class="col-3">
                <ul>
                    <li data-id="1">order</li>
                    <li data-id="2">save</li>
                    <li data-id="3">restore</li>
                </ul>
            </div>
        </div>
        <br>
        <button id="save">Save</button>
    </div>

@stop


@section('vendor-scripts')

@stop

@section('page-styles')
    <link href="/css/peopleheader.css" rel="stylesheet" type="text/css"/>
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="http://SortableJS.github.io/Sortable/Sortable.js"></script>
<script>

    var xx = {
        items: [
            {id: 'cat', order: '0', name: 'Cat'},
            {id: 'dog', order: '1', name: 'Dog'},
            {id: 'cow', order: '2', name: 'Cow'}
        ]
    };
    $(document).ready(function () {

        // Simple list
        //const mylist = Sortable.create(simpleList, {
        //$("#simpleList").sortable({
        var mysortable = new Sortable(simpleList, {
            sort: true,  // sorting inside list
            /* options */
            update: function (evt) {
                //console.log(evt);
                sendRequest;
            }
        });


        function sendRequest() {
            console.log('saving now');
            //alert(mylist.sortable('serialize'));
            //console.log($("#simpleList").Sortable('serialize'));
            console.log(mysortable.Sortable('serialize'));
            /*
             $.ajax({
             type: 'POST',
             url: 'draggable.php',
             data: {
             sort1: $("#sortable1").sortable('serialize'),
             sort2: $("#sortable2").sortable('serialize')
             },
             success: function(html) {
             //$('.success').fadeIn(500);
             }
             });*/
        }

        $("#save").click(function() {
            sendRequest();
        })
    });

</script>
@stop
