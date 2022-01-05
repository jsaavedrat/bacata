@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-truck"> </i> &nbsp; ENVÍOS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CREAR REPORTE</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('empresas.envio.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">HISTORIAL REPORTES DE ENVÍO</p></div></a></div>
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
    <div id="content-mensaje">
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Empresa registrada con Éxito</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Nombre de empresa ya existe</div>@endif
    </div>
    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Crear Reporte de envio a productos</div>
     
        <form method="post" data-parsley-validate action="{{ route('empresas.envio.guardar.reporte') }}" enctype="multipart/form-data">
            @csrf
            <div class="label-campo">
                <label class="label-admin" for="nombre_cliente"><i id="lista" class="icon-dot-single"></i>Nombre Cliente<i id="cont-icon" class="fa fa-language"></i></label>
                <input type="text" name="nombre_cliente" id="nombre_cliente" class="campo-admin" placeholder="Nombre Cliente" spellcheck="false" autocomplete="off" maxlength="100" required onkeyup="this.value=letras(this.value)">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="identificacion_cliente"><i id="lista" class="icon-dot-single"></i>N° Identificacion<i id="cont-icon" class="icon-credit-card"></i></label>
                <input type="text" name="identificacion_cliente" id="identificacion_cliente" class="campo-admin" placeholder="N° Identificacón" spellcheck="false" autocomplete="off" maxlength="35" required>
            </div>

            <div class="label-campo">
                <label class="label-admin" for="telefono_cliente"><i id="lista" class="icon-dot-single"></i>Teléfono Cliente<i id="cont-icon" class="icon-phone"></i></label>
                <input type="text" name="telefono_cliente" id="telefono_cliente" class="campo-admin" placeholder="Teléfono" spellcheck="false" autocomplete="off" maxlength="100" required>
            </div>

            <div class="label-campo" style="width: 66.6%!important;">
                <label class="label-admin" for="direccion_cliente"><i id="lista" class="icon-dot-single"></i>Dirección de envío<i id="cont-icon" class="icon-location-pin"></i></label>
                <textarea type="text" name="direccion_cliente" id="direccion_cliente" class="campo-admin" placeholder="Dirección" spellcheck="false" autocomplete="off" maxlength="100" required style="margin-bottom: -5px;"></textarea>
            </div>

            <div class="label-campo">
                <label class="label-admin" for="identificacion_cliente"><i id="lista" class="icon-dot-single"></i>Empresa de envío<i id="cont-icon" class="fa fa-truck"></i></label>
                <select class="campo-admin" name="id_empresa_envio" required>
                    <option value="">Seleccione</option>
                    @foreach($empresas_envio as $empresa_envio)
                        <option value="{{$empresa_envio->id_empresa_envio}}">{{$empresa_envio->nombre_empresa_envio}}</option>
                    @endforeach
                </select>
            </div>

            <div class="label-campo" style="width: 100%!important;">
                <label class="label-admin" for="productos_venta"><i id="lista" class="icon-dot-single"></i>Productos<i id="cont-icon" class="fa fa-tags"></i></label>
                <textarea type="text" name="productos_venta" id="productos_venta" class="campo-admin" placeholder="Escriba las descripciones de los productos que envía" spellcheck="false" autocomplete="off" maxlength="10000" required style="margin-bottom: -5px;"></textarea>
            </div>

            <button class="boton-admin"> Generar reporte para envío de productos</button>
        </form>
</div>


<style type="text/css">
   #elemento-admin{
        margin-top:16vh!important;
        float: none;
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
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ áéíóúÁÉÍÓÚ';//Caracteres validos
        
        
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


