@if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <i class="fa fa-warning"></i><strong>An error has occured</strong>
        <ul>
            @foreach ($errors->all() as $key => $error)
                @if (preg_match("/ /", $error))
                    <li>{{ $error }}</li>
                @endif
            @endforeach
        </ul>
    </div>
@endif