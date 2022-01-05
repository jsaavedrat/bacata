<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Optica Angeles - Regístro</title>
    <link rel="shortcut icon" href="public/imagenes/icono.png" />
    <link rel="stylesheet" type="text/css" href="public/css/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="public/css/fontawesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="public/css/fonts/style.css">
    <link href="{{ asset('public/css/estiloslogin.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('public/js/jquery.js') }}"></script>
</head>
<body style="background-color: rgba(0,0,0,0)!important;">
    <div id="header">

        <div id="content-logo-principal">
            <a href="{{ url('/') }}">
                <div id="content-centrar-logo">
                    <img src="public/imagenes/logo-principal.png" style="width: 100%;">
                </div>
            </a>
        </div>

        
    </div>



<div id="content-imagen-fondo">
    <div id="imagen-fondo">
    </div>
</div>

<div id="seccion-admin">
    <div id="contenido-admin">
        <div class="caja-admin">
            <div id="texto-titulo"> 
                <i class="fa fa-plus"> </i> Regístro de Clientes
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="label-campo" style="width: 50%;padding-right: 10px;">
                    <label for="name" class="label-admin"><i id="lista" class="icon-dot-single"></i>{{ __('Nombre') }}<i id="cont-icon" class="fa fa-language"></i></label>
                    <input id="name" type="text" class="campo-admin @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Nombre" required autofocus spellcheck="false" autocomplete="off" maxlength="30" onkeyup="this.value=letras(this.value)">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="label-campo" style="width: 50%;padding-left: 10px;">
                    <label for="apellido" class="label-admin"><i id="lista" class="icon-dot-single"></i>{{ __('Apellido') }}<i id="cont-icon" class="fa fa-language"></i></label>
                    <input id="apellido" type="text" class="campo-admin" name="apellido" value="{{ old('apellido') }}" placeholder="Apellido" required spellcheck="false" autocomplete="off"  maxlength="30" onkeyup="this.value=letras(this.value)">
                </div>
                
                <div class="label-campo">                                                         
                    <label for="email" class="label-admin"><i id="lista" class="icon-dot-single"></i>{{ __('Correo Electrónico') }}<i id="cont-icon" class="icon-mail"></i></label>
                    <input id="email" type="email" class="campo-admin @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Correo Electrónico" required spellcheck="false" autocomplete="off"  maxlength="50">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="label-campo" style="width: 50%;padding-right: 10px;">                      
                    <label for="password" class="label-admin"><i id="lista" class="icon-dot-single"></i>{{ __('Contraseña') }}<i id="cont-icon" class="icon-key"></i></label>
                    <input id="password" type="password" class="campo-admin @error('password') is-invalid @enderror" name="password" placeholder="Contraseña" required autocomplete="new-password" >
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="label-campo" style="width: 50%;padding-left: 10px;">                             
                    <label for="password-confirm" class="label-admin"><i id="lista" class="icon-dot-single"></i>{{ __('Confirmar Contraseña') }}<i id="cont-icon" class="icon-key"></i></label>
                    <input id="password-confirm" type="password" class="campo-admin" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar contraseña">
                </div>
                <div class="label-campo" id="error-contrasena" style="width: 100%;display: none;color:red;">
                    Las contraseñas deben coincidir.<br>
                    Debe tener al menos una mayúscula, minúscula y un número.
                </div>
               

                <button type="submit" class="boton-admin" id="boton-registro">
                    {{ __('Registrarme') }}
                </button>
                
                <a href="{{ route('login') }}">
                    <div id="registrate">
                        Ya posees cuenta? Inicia sesión Aquí.
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


<script type="text/javascript">

    function letras(string){//Solo letras
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóú ';//Caracteres validos
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);

        var str = "";

        for(i = 0; i < out.length; i++){
            if(i == 0){
                if(out[i] != " "){
                    str = str + out[i].toUpperCase();
                }else{
                    str = "";
                }
            }else{
                if(out[i-1] == " "){
                    str = str + out[i].toUpperCase();
                }else{
                    str = str + out[i];
                }
            }
        }
        out = str;

        return out;
    }

    $("#boton-registro").click(function(){
        var valid = true;
        var contrasenna = document.getElementById('password').value;
        var contrasenna2 = document.getElementById('password-confirm').value;
        document.getElementById("error-contrasena").style.display = "none";

        if(contrasenna.length > 0 && contrasenna2.length > 0){
            if(contrasenna.length >= 8){      
                var mayuscula = false;
                var minuscula = false;
                var numero = false;
                var caracter_raro = false;
                
                for(var i = 0;i<contrasenna.length;i++){
                    if(contrasenna.charCodeAt(i) >= 65 && contrasenna.charCodeAt(i) <= 90){
                        mayuscula = true;
                    }else if(contrasenna.charCodeAt(i) >= 97 && contrasenna.charCodeAt(i) <= 122){
                        minuscula = true;
                    }else if(contrasenna.charCodeAt(i) >= 48 && contrasenna.charCodeAt(i) <= 57){
                        numero = true;
                    }else{
                        caracter_raro = true;
                    }
                }if(mayuscula == true && minuscula == true /*&& caracter_raro == true*/ && numero == true){
                    valid = true;
                }
            }else{
                valid = false;
            }
        }else{
            valid = false;
        }
        if(valid == false){
            document.getElementById("error-contrasena").style.display = "block";
        }
        console.log(valid);
    });
    </script>       
                    

</body>
</html>
a4F$dddd