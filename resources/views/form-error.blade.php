@if (count($errors) > 0)
    <div class="alert alert-danger fade show" role="alert">
        <div class="alert-text">
            <i class="fa fa-exclamation-triangle"></i> <strong>An error has occured</strong><br>
            <ul>
                @foreach ($errors->all() as $key => $error)
                    @if (preg_match("/ /", $error))
                        <li>{{ $error }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
@endif
