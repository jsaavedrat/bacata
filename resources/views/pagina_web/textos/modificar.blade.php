@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-language"> </i> TEXTOS PAGINA WEB &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; EDITAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/trumbowyg.min.css') }}">
<script type="text/javascript" src="{{ asset('public/js/trumbowyg.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.24.0/trumbowyg.min.js" integrity="sha512-1grPXW6pB3WKyOH6zbXrYrYf+SHKeky6JTpUVtjDfz4NZ6uIu0HLRNSmXnv5rjn7iLJXrWLoVFR3XBAuaG3IRg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{route('home')}}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{route('pagina.textos.lista')}}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">TEXTOS PAGINA WEB</p></div></a></div>
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
    
    <div id="texto-titulo"> <i class="fa fa-pencil"> </i> MODIFICAR 
        @if($texto->tipo_texto_web)
            TÉRMINOS Y CONDICIONES DE
        @endif
        {{$texto->nombre_texto_web}}</div>

    <form method="post" data-parsley-validate action="{{route('pagina.textos.modificar')}}" enctype="multipart/form-data">
        @csrf
        <div style="width: 100%;float: left;">

            <div class="label-campo" style="width: 100%!important;margin-bottom: 0px!important;padding-bottom: 0px!important;">
                <label class="label-admin" style="text-transform: uppercase;"><i id="lista" class="icon-dot-single"></i>
                    @if($texto->tipo_texto_web)
                        TÉRMINOS Y CONDICIONES DE
                    @endif
                    {{$texto->nombre_texto_web}}
                    <i id="cont-icon" class="fa fa-language"></i>
                </label>
            </div>

            <div class="label-campo" style="width: 100%!important;margin-top:0px!important;padding-top: 0px!important;">
                <textarea type="text" name="descripcion_texto_web" id="descripcion_texto_web" class="campo-admin" placeholder="Acceso a la red" spellcheck="false" autocomplete="off" maxlength="1000" style="height: 200px!important;">{!!$texto->descripcion_texto_web!!}</textarea>
            </div>

            <input type="hidden" name="id_texto_web" value="{{$texto->id_texto_web}}">

        </div>

        <button class="boton-admin" id="crear-servicio"> Editar {{$texto->nombre_texto_web}}</button>
    </form>
</div>

<style type="text/css">

    .trumbowyg-editor{
        background-color: rgba(255,255,255,0.8);
        font-size: 13px;
    }

    #elemento-admin{
        margin-top:20px;
        border:1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 60px rgba(100,100,120,0.3);
        padding-bottom:20px;
        background-color: rgba(225,225,235,0.2);
    }
    .label-campo{
        /*width: 100%;*/
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

    $('#descripcion_texto_web').trumbowyg();

</script>

@endsection


