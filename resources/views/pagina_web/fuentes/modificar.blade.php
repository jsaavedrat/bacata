@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-language"> </i> SECCIÓN EQUIPO DE TRABAJO &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; MODIFICAR IMAGEN</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('pagina.equipo.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">IMAGENES EQUIPO DE TRABAJO</p></div></a></div>
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
    <div id="content-mensaje">
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Imagen agregada exitosamente</div>@endif
    </div>

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> EDITAR IMAGEN EQUIPO</div>

    <form method="post" data-parsley-validate action="{{ route('pagina.fuentes.modificar') }}" enctype="multipart/form-data">
        @csrf
        <div style="width: 100%;float: left;">
            <div class="label-campo">
                <div id="error-nombre-marca" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Ingrese titulo imagen</div>
                <label class="label-admin" for="titulo_imagen_equipo"><i id="lista" class="icon-dot-single"></i>Titulo Imagen<i id="cont-icon" class="icon-bookmark"></i></label>
                <input type="text" name="titulo_imagen_equipo" id="titulo_imagen_equipo" class="campo-admin" placeholder="Titulo imagen" spellcheck="false" autocomplete="off" maxlength="50" required value="{{$imagen_equipo->titulo_imagen_equipo}}">
            </div>

            <div class="label-campo">
                <div id="error-imagen" style="display: none;" onclick="this.style.display='none'">Seleccione la imagen</div>
                <label class="label-admin" for="nombre_imagen_equipo"><i id="lista" class="icon-dot-single"></i>Seleccione imagen<i id="cont-icon" class="icon-image"></i></label>
                <input type="file" class="campo-admin" name="nombre_imagen_equipo" id="nombre_imagen_equipo">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="orden"><i id="lista" class="icon-dot-single"></i>Orden en el que aparecerá<i id="cont-icon" class="icon-image"></i></label>
                <input type="number" class="campo-admin" name="orden" id="orden" placeholder="Ingresa el numero" value="{{$imagen_equipo->orden}}">
            </div>
        </div>
        <input type="hidden" name="id_imagen_equipo" value="{{$imagen_equipo->id_imagen_equipo}}">

        <button class="boton-admin" id="crear-logo-marca"> Editar Imagen EQUIPO</button>
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



@endsection


