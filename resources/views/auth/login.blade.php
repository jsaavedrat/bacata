<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Optica Angeles | Iniciar Sesión </title>
    <link rel="shortcut icon" href="public/imagenes/icono.png" />
    <link rel="stylesheet" type="text/css" href="public/css/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="public/css/fontawesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="public/css/fonts/style.css">
    <link href="{{ asset('public/css/estiloslogin.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="header">

        <div id="content-logo-principal">
            <a href="{{ url('/') }}">
                <div id="content-centrar-logo">
                    <img src="public/imagenes/logo-principal.png" style="width: 100%;">
                </div>
            </a>
        </div>

        <div id="content-menu">
            @guest
            {{--
            <a href="{{ route('register') }}">
                <div class="menu" style="margin-right: 5%;padding-top:14px!important;">
                    <p class="texto-menu"><i class="icon-add-user"></i><br>REGÍSTRO</p>
                </div>
            </a>
            --}}
            @endguest
        </div>
    </div>

<div id="content-imagen-fondo">
    <div id="imagen-fondo">
    </div>
</div>

<div id="seccion-admin">
    <div id="contenido-admin">
            
        <div class="caja-admin" style="max-width: 450px;">
           <div id="texto-titulo"> 
                <i class="fa fa-sign-in"> </i>&nbsp Iniciar Sesión
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="label-campo">
                    <label class="label-admin" for="email"><i id="lista" class="icon-dot-single"></i> {{ __('Correo Electrónico') }} <i id="cont-icon" class="icon-mail"></i></label>
                    <input type="email" name="email" id="email" class="campo-admin form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Correo Electrónico" spellcheck="false" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="label-campo">
                    <label class="label-admin" for="password"><i id="lista" class="icon-dot-single"></i> {{ __('Contraseña') }} <i id="cont-icon" class="icon-key"></i></label>
                    <input type="password" name="password" id="password" class="campo-admin form-control @error('password') is-invalid @enderror" placeholder="Contraseña" spellcheck="false" autocomplete="off" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{--<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>--}}



                <button type="submit" class="boton-admin">
                    {{ __('Iniciar Sesión') }}
                </button>

                {{--@if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Olvidaste tu contrasela?') }}
                    </a>
                @endif--}}

                <a href="{{ route('register') }}">
                    <div id="registrate">
                        No posees cuenta? regístrate Aquí.
                    </div>
                </a>
            </form>
        </div>
                    
    </div>
    <div id="footer-admin">
        <div id="texto-footer">
            Óptica Ángeles<br>
            COLOMBIAN TRADING S.A.S. Derechos Reservados © 2020<br>AppWeb Colombia ©
        </div>
        <div id="nombre-version"><b>Nombre Sistema</b> Versión 2.0</div>
    </div>        
</div>
                
                    

</body>
</html>
