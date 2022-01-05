@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-truck"> </i> EMPRESAS DE ENVÍO &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CREAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('empresas.envio.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA EMPRESAS DE ENVÍO</p></div></a></div>
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
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; ERROR NO se pudo actualizar la empresa.</div>@endif
    </div>
    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Editar Empresa de Envío</div>
     
        <form method="post" data-parsley-validate action="{{ route('empresas.envio.modificar') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="id_empresa_envio" value="{{$empresa_envio->id_empresa_envio}}">

            <div class="label-campo">
                <label class="label-admin" for="nombre_empresa"><i id="lista" class="icon-dot-single"></i>Nombre Empresa<i id="cont-icon" class="fa fa-language"></i></label>
                <input type="text" name="nombre_empresa_envio" id="nombre_empresa" class="campo-admin" placeholder="Nombre Empresa" spellcheck="false" autocomplete="off" maxlength="35" value="{{$empresa_envio->nombre_empresa_envio}}" required onkeyup="this.value=letras(this.value)">
            </div>
            <div class="label-campo">
                <label class="label-admin" for="nombre_codigo"><i id="lista" class="icon-dot-single"></i>Nombre Código<i id="cont-icon" class="fa fa-language"></i></label>
                <input type="text" name="nombre_codigo" id="nombre_codigo" class="campo-admin" placeholder="Nombre identificador de envíos" spellcheck="false" autocomplete="off" maxlength="100" value="{{$empresa_envio->nombre_codigo}}" required onkeyup="this.value=letras(this.value)">
            </div>
            <div class="label-campo">
                <label class="label-admin" for="imagen_empresa_envio"><i id="lista" class="icon-dot-single"></i>Imágen Empresa de Envío<i id="cont-icon" class="fa fa-language"></i></label>
                <input type="file" name="imagen_empresa_envio" id="imagen_empresa_envio" class="campo-admin" placeholder="Seleccione imagen empresa de envio" accept=".png,.jpg">
            </div>

            <button class="boton-admin"> Registrar Empresa de Envío</button>
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
    #content-mensaje{
        width: calc(100% - 40px);
        margin-left: 20px;
    }
</style>

<script type="text/javascript">

    function letras(string){//Solo letras
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ ';//Caracteres validos
        
        
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

    $(document).on('change','input[type="file"]',function(){

        var fileName = this.files[0].name;
        var fileSize = this.files[0].size;

        if(fileSize > 2000000){
            alert('El archivo no debe superar 2MB, ajuste el archivo ó cargue otro.');
            this.value = '';
        }else{
            var ext = fileName.split('.').pop();
            ext = ext.toLowerCase();
            switch (ext) {
                case 'jpg':
                case 'jpeg':
                case 'png': break;
                default:
                    alert('El archivo no tiene la extensión adecuada');
                    this.value = ''; // reset del valor
                    this.files[0].name = '';
            }
        }
    });
    </script>
@endsection


