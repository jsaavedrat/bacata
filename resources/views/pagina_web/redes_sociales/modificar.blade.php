@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-facebook"> </i> REDES SOCIALES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; EDITAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('pagina.redes.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE REDES</p></div></a></div>
    <div class="icono-titulo"><i class="fa fa-question herramientas">                                   </i><div class="content-texto"><p class="texto-icono">AYUDA</p></div></div>
    <div class="icono-titulo"><a href="{{route('usuarios.perfil')}}"><i class="fa fa-user herramientas"></i><div class="content-texto"><p class="texto-icono">MI PERFIL</p></div></div></a>
    <div class="icono-titulo" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-power-off herramientas"></i><div class="content-texto"><p class="texto-icono">CERRAR SESIÓN</p></div></div>
    <div id="content-logout">
        <div id="nombre-user"> {{ Auth::user()->name }} <i class="icon-chevron-down"> </i></div>
        <div id="content-opciones-user">
            <a href="{{route('usuarios.perfil')}}"><div class="opcion-user"> <i class="icon-key"> </i> CONTRASEÑA </div></a>
            <a href="{{route('usuarios.perfil')}}"><div class="opcion-user"> <i class="fa fa-user"> &nbsp;</i> PERFIL </div></a>
            @guest @else
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <div class="opcion-user"><i class="fa fa-power-off"> &nbsp;</i> SALIR </div>
                </a>
            @endguest
        </div>
    </div>
</div>
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::VISTA ACTUAL::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<div id="elemento-admin">
    
    <div id="texto-titulo"> <i class="fa fa-pencil"> </i> MODIFICAR RED SOCIAL EN PÁGINA WEB</div>

    <form method="post" data-parsley-validate action="{{ route('pagina.redes.modificar') }}" enctype="multipart/form-data">
        @csrf
        <div style="width: 100%;float: left;">
            <div class="label-campo">
                <label class="label-admin" for="nombre_marca"><i id="lista" class="icon-dot-single"></i>Nombre Red Social<i id="cont-icon" class="icon-bookmark"></i></label>
                <input type="text" class="campo-admin" placeholder="Nombre red" disabled value="{{$red->nombre_red}}">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="acceso_red"><i id="lista" class="icon-dot-single"></i>
                    @if($red->nombre_red == "WhatsApp")
                        Ingrese el numero del Whatsapp (sin espacios, + ni -)
                    @else
                        Ingrese la URL de la Red social: {{$red->nombre_red}}
                    @endif
                    <i id="cont-icon" class="fa fa-globe"></i>
                </label>
                <input type="text" name="acceso_red" id="acceso_red" class="campo-admin" placeholder="Acceso a la red" spellcheck="false" autocomplete="off" maxlength="1000" value="{{$red->acceso_red}}">
            </div>
            @if($red->nombre_red == "WhatsApp")
                <div class="label-campo">
                    <label class="label-admin" for="texto_extra_red"><i id="lista" class="icon-dot-single"></i>Texto por defecto que escribe el cliente<i id="cont-icon" class="fa fa-language"></i></label>
                    <input type="text" name="texto_extra_red" id="texto_extra_red" class="campo-admin" placeholder="Ej: Hola estoy interesado en" spellcheck="false" autocomplete="off" maxlength="1000" value="{{$red->texto_extra_red}}">
                </div>
            @endif
            <input type="hidden" name="id_red" value="{{$red->id_red}}">

        </div>

        <button class="boton-admin" id="crear-servicio"> Editar {{$red->nombre_red}}</button>
    </form>
</div>

<style type="text/css">

    #elemento-admin{
        margin-top:16vh!important;
        float: none;
        max-width: 450px;
        margin-left: auto;
        margin-right: auto;
        left:0;
        right: 0;
        overflow: hidden;
        border:1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 60px rgba(100,100,120,0.3);
        padding-bottom:20px;
        background-color: rgba(225,225,235,0.2);
    }
    .label-campo{
        width: 100%;
    }
    .boton-admin{
        margin-top: 20px;
        width:calc(100% - 40px);
        margin-left:20px;
    }

    #error-nombre-marca,#error-imagen{
        display: none;
        padding: 8px;
        background-color:rgba(230,0,0,0.8);
        color:white;
        font-weight: 600;
        letter-spacing: -0.5px;
        border-radius: 2px;
        margin-bottom: 2px;
        font-size:15px;
        float: left;
        width: 100%;
        cursor: pointer;
    }

</style>

<script type="text/javascript">

    $("#crear-servicio").click(function(){

        document.getElementById("error-nombre-marca").style.display = "none";
        document.getElementById("error-imagen").style.display = "none";
        var valid = true;
        var nombre = document.getElementById("nombre_marca").value;
        var imagen = document.getElementById("imagen_logo_marca").value;

        if(nombre.length < 2){
            valid = false;
            document.getElementById("error-nombre-marca").style.display = "inline";
        }

        return valid;
    });

</script>

@endsection


