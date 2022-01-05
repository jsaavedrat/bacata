@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-colours"> </i> CLASIFICACIONES &nbsp;<i class="fa fa-angle-right"> </i> ESPECIFICACIONES &nbsp; <i class="fa fa-angle-right"> </i>&nbsp; EDITAR {{$especificacion->nombre_clasificacion}} {{$especificacion->nombre_especificacion}}</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('clasificaciones.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE CLASIFICACIONES</p></div></a></div>
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
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::VISTA ACTUAL::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<div id="elemento-admin">
    <div id="texto-titulo"> <i class="icon-pencil"> </i> Editar {{$especificacion->nombre_clasificacion}}: {{$especificacion->nombre_especificacion}}</div>
     
        <form method="post" action="{{ route('clasificaciones.modificar.especificacion') }}">
            @csrf
            <div class="label-campo">
                <label class="label-admin" for="nombre_especificacion"><i id="lista" class="icon-dot-single"></i>Nombre {{$especificacion->nombre_clasificacion}}<i id="cont-icon" class="fa fa-language"></i></label>
                <input type="text" name="nombre_especificacion" id="nombre_especificacion" class="campo-admin" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="35" value="{{$especificacion->nombre_especificacion}}" required onkeyup="this.value=letras(this.value)" autofocus>
            </div>

            <input type="hidden" name="id_especificacion" value="{{$especificacion->id_especificacion}}">

            <button class="boton-admin"> Editar {{$especificacion->nombre_clasificacion}}</button>
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
    #iconos-titulo{
        box-shadow: 0px 0px 0px white;
    }
</style>

<script type="text/javascript">

    function letras(string){//Solo letras
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ 1234567890-+#/.,';//Caracteres validos
        
        
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
    </script>
@endsection


