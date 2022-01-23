@extends('layouts.mr')

@section('content')
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <div class="well">
                <form id="login-form" action="{{ route('login') }}" method="post" accept-charset="UTF-8" class="form-horizontal">
                    @csrf

                    <fieldset>
                        <legend>

                            {{ config('app.name') }}
                            @if(request()->secure() === false)
                                <a href="{{ url('/', [], true) }}"><i data-i18n="[title]auth.insecure" title="Insecure connection, switch to secure" class="text-danger fa fa-unlock-alt pull-right"></i></a>
                            @else
                                <i data-i18n="[title]auth.secure" title="You're using a secure connection" class="text-success fa fa-lock pull-right"></i>
                            @endif

                        </legend>

                        @foreach($GLOBALS['alerts'] AS $type => $list)

                        @foreach($list AS $msg)

                        <p class="text-{{ $type }}">{{ $msg }}</p>

                        @endforeach

                        @endforeach


                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>



                        @if(config('_munkireport.recaptchaloginpublickey'))

                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-3">
                                <button class="btn btn-primary g-recaptcha" data-sitekey="{{ config('_munkireport.recaptchaloginpublickey') }}" data-callback="onSubmit" data-i18n="auth.signin"></button>
                            </div>
                        </div>

                        @else

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                        @endif

                    </fieldset>
                    @if (Str::contains(config('auth.methods'), 'SAML'))
                        <a class="btn btn-primary" href="{{ route('saml2_login', 'default') }}">Sign in with SAML</a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div><!-- /container -->
@endsection
