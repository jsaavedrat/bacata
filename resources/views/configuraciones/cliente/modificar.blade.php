@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-cog"> </i>&nbsp; CONFIGURACINES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; EMPRESA &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; EDITAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{route('configuraciones.cliente.crear')}}"><i class="icon-pencil herramientas"> </i><div class="content-texto"><p class="texto-icono">CREAR</p></div></a></div>
    <div class="icono-titulo"><i class="fa fa-question herramientas">                                   </i><div class="content-texto"><p class="texto-icono">AYUDA</p></div></div>
    <div class="icono-titulo"><i class="icon-chat herramientas">                                        </i><div class="content-texto"><p class="texto-icono">CHAT</p></div></div>
    <div class="icono-titulo"><i class="icon-bell herramientas">                                        </i><div class="content-texto"><p class="texto-icono">NOTIFICACIONES</p></div></div>
    <div class="icono-titulo"><i class="fa fa-user herramientas">                                       </i><div class="content-texto"><p class="texto-icono">MI PERFIL</p></div></div>
    <div class="icono-titulo" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-power-off herramientas"></i><div class="content-texto"><p class="texto-icono">CERRAR SESIÓN</p></div></div>

    <div id="content-logout">
        <div id="nombre-user"> {{ Auth::user()->name }} <i class="icon-chevron-down"> </i></div>
        <div id="content-opciones-user">
            <div class="opcion-user"> <i class="icon-key"> </i> CONTRASEÑA </div>
            <div class="opcion-user"> <i class="fa fa-user"> &nbsp;</i> PERFIL </div>
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

    <div id="texto-titulo"> <i class="icon-pencil"> </i> Configurar Información Básica {{$cliente_appweb->nombre_cliente_sas}}</div>

        <form method="post" data-parsley-validate action="{{ route('configuraciones.cliente.modificar') }}" enctype="multipart/form-data">
            @csrf
            <div class="label-campo">
                <label class="label-admin" for="nombre_cliente"><i id="lista" class="icon-dot-single"></i>Nombre SAS<i id="cont-icon" class="fa fa-language"></i></label>
                <input type="text" name="nombre_cliente" id="nombre_cliente" class="campo-admin" placeholder="Nombre Cliente" spellcheck="false" autocomplete="off" maxlength="50" required value="{{$cliente_appweb->nombre_cliente_sas}}">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="nit_empresa"><i id="lista" class="icon-dot-single"></i>NIT Empresa<i id="cont-icon" class="fa fa-credit-card"></i></label>
                <input type="text" name="nit_empresa" id="nit_empresa" class="campo-admin" placeholder="NIT" spellcheck="false" autocomplete="off" maxlength="50" required value="{{$cliente_appweb->nit_empresa}}">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="persona_cliente"><i id="lista" class="icon-dot-single"></i>Persona Cliente<i id="cont-icon" class="fa fa-user"></i></label>
                <input type="text" name="persona_cliente" id="persona_cliente" class="campo-admin" placeholder="Nombre persona" spellcheck="false" autocomplete="off" maxlength="50" required value="{{$cliente_appweb->persona_cliente}}">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="telefono_contacto"><i id="lista" class="icon-dot-single"></i>Teléfono Empresa<i id="cont-icon" class="fa fa-phone"></i></label>
                <input type="text" name="telefono_contacto" id="telefono_contacto" class="campo-admin" placeholder="Teléfono" spellcheck="false" autocomplete="off" maxlength="50" required value="{{$cliente_appweb->telefono_contacto}}">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="correo_electronico"><i id="lista" class="icon-dot-single"></i>Correo Electrónico<i id="cont-icon" class="icon-mail"></i></label>
                <input type="text" name="correo_electronico" id="correo_electronico" class="campo-admin" placeholder="Correo Empresa ó contacto" spellcheck="false" autocomplete="off" maxlength="50" required value="{{$cliente_appweb->correo_electronico}}">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="dominio"><i id="lista" class="icon-dot-single"></i>Dominio<i id="cont-icon" class="fa fa-globe"></i></label>
                <input type="text" name="dominio" id="dominio" class="campo-admin" placeholder="Dominio sin 'http' ni 'www'" spellcheck="false" autocomplete="off" maxlength="50" required value="{{$cliente_appweb->dominio}}">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="direccion"><i id="lista" class="icon-dot-single"></i>Dirección Principal<i id="cont-icon" class="icon-location"></i></label>
                <textarea type="text" name="direccion" id="direccion" class="campo-admin" placeholder="Dirección Principal" spellcheck="false" autocomplete="off" maxlength="50" required>{{$cliente_appweb->direccion}}</textarea>
            </div>
            
            <div class="label-campo">
                <label class="label-admin" for="pie pagina"><i id="lista" class="icon-dot-single"></i>Pie de página Documentos<i id="cont-icon" class="fa fa-language"></i></label>
                <input type="text" name="pie pagina" id="pie pagina" class="campo-admin" placeholder="Pie de página que se verá en los documentos" spellcheck="false" autocomplete="off" maxlength="50" required value="{{$cliente_appweb->pie_pagina}}">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="logo_empresa"><i id="lista" class="icon-dot-single"></i>Imagen Principal Empresa<i id="cont-icon" class="icon-image"></i></label>
                <input type="file" class="campo-admin" name="logo_empresa" id="logo_empresa">
            </div>

            <button class="boton-admin"> Actualizar Información</button>
        </form>
</div>


<style type="text/css">
   #elemento-admin{
        margin-top:20px!important;
        /*float: none;
        max-width: 450px;
        margin-left: auto;
        margin-right: auto;
        left:0;
        right: 0;
        overflow: hidden;*/
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
    #iconos-titulo{
        box-shadow: 0px 0px 0px white;
    }
    #content-mensaje{
        width: calc(100% - 40px);
        margin-left: 20px;
    }
</style>

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
    </script>
@endsection


