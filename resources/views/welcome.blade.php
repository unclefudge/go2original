@extends('layouts/checkin')

@section('content')
    <style>
        body, html {
            background-image: linear-gradient(rgba(0,0,0,.3), rgba(0,0,0,.3)), url('/img/home-bg.jpg') !important;
            height: 100%; /* set height */

            /* Create the parallax scrolling effect */
            background-attachment: fixed !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
        }

        h1 {
            font-family: 'Open Sans', sans-serif !important;
        }


    </style>

    <div class="padding-all">
        <div class="header">
            <h1>Login</h1>
        </div>
        <!---728x90--->

        <div class="design-w3l">
            <div class="mail-form-agile">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    @if(session()->has('login_error'))
                        <div class="alert alert-success">
                            {{ session()->get('login_error') }}
                        </div>
                    @endif
                    <input id="identity" type="text" class="{{ $errors->has('identity') ? ' is-invalid' : '' }}" name="identity" value="{{ old('identity') }}" placeholder="Username or Email" required autofocus>
                    <input type="password"  name="password" class="padding" placeholder="Password" required=""/>
                    <input type="submit" value="login">
                </form>
            </div>
            <div class="clear"> </div>
        </div>
        <!---728x90--->

        <div class="footer">
            <p>
                {{--}}
                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif --}}
            </p>
        </div>
    </div>


    {{--}}
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">

            <div class="col-md-4">
                <div class="card" style="background: rgb(255, 255, 255); background: rgba(255, 255, 255, 0.9);">
                    <div class="card-header" style="background: #fff">{{ __('Login') }}</div>

                    <div class="card-body" style="">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            @if(session()->has('login_error'))
                                <div class="alert alert-success">
                                    {{ session()->get('login_error') }}
                                </div>
                            @endif

                            <div class="row form-group {{ $errors->has('identity') ? ' has-error' : '' }}">

                                <div class="col">
                                    <input id="identity" type="identity" class="form-control {{ $errors->has('identity') ? ' is-invalid' : '' }}" name="identity" value="{{ old('identity') }}" placeholder="Email or Username" required autofocus>

                                    @if ($errors->has('identity'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('identity') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="password" required>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-5">
                                    <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                                </div>
                                <div class="col-7 text-right">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12 text-center">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    --}}

@stop


@section('vendor-scripts')
@stop

@section('page-styles')
    <link href="/css/login.css" rel="stylesheet" type="text/css">
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
    <script>
        function checkInput(input) {
            if (input.value.length > 0) {
                input.className = 'active';
            } else {
                input.className = '';
            }
        }
    </script>
@stop