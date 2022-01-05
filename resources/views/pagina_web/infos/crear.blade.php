@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-creative-commons-attribution"> </i> Infos &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; AGREGAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('pagina.infos.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE INFOS</p></div></a></div>
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
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Logo agregado exitosamente</div>@endif
    </div>

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> AGREGAR INFO EN PÁGINA WEB</div>

    <form method="post" data-parsley-validate action="{{ route('pagina.infos.guardar') }}" enctype="multipart/form-data">
        @csrf
        <div style="width: 100%;float: left;">
            <div class="label-campo">
                <div id="error-nombre-info" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Ingrese nombre info</div>
                <label class="label-admin" for="nombre_info"><i id="lista" class="icon-dot-single"></i>Nombre Info<i id="cont-icon" class="icon-bookmark"></i></label>
                <input type="text" name="nombre_info" id="nombre_info" class="campo-admin" placeholder="Nombre de la info" spellcheck="false" autocomplete="off" maxlength="50" required>
            </div>

            <div class="label-campo">
                <div id="error-detalle-info" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Ingrese detalle info</div>
                <label class="label-admin" for="detalle_info"><i id="lista" class="icon-dot-single"></i>Detalle Info<i id="cont-icon" class="icon-bookmark"></i></label>
                <input type="text" name="detalle_info" id="detalle_info" class="campo-admin" placeholder="Detalle de la info" spellcheck="false" autocomplete="off" maxlength="50" required>
            </div>

            <div class="label-campo">
                <label class="label-admin" for="pagina_web"><i id="lista" class="icon-dot-single"></i>Ingrese URL página web<i id="cont-icon" class="icon-bookmark"></i></label>
                <input type="text" name="pagina_web" id="pagina_web" class="campo-admin" placeholder="Dirección web" spellcheck="false" autocomplete="off" maxlength="50">
            </div>

            <div class="label-campo">
                <div id="error-imagen" style="display: none;" onclick="this.style.display='none'">Seleccione la imagen de la info</div>
                <label class="label-admin" for="imagen_info"><i id="lista" class="icon-dot-single"></i>Seleccione imagen Info<i id="cont-icon" class="icon-image"></i></label>
                <input type="file" class="campo-admin" name="imagen_info" id="imagen_info" required>
            </div>
        </div>

        <button class="boton-admin" id="crear-info"> Agregar info</button>
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

    #error-nombre-info,#error-detalle-info,#error-imagen{
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

    $("#crear-info").click(function(){

        document.getElementById("error-nombre-info").style.display = "none";
        document.getElementById("error-detalle-info").style.display = "none";
        document.getElementById("error-imagen").style.display = "none";
        var valid = true;
        var nombre = document.getElementById("nombre_info").value;
        var detalle = document.getElementById("detalle_info").value;
        var imagen = document.getElementById("imagen_info").value;

        if(nombre.length < 2){
            valid = false;
            document.getElementById("error-nombre-info").style.display = "inline";
        }

        if(detalle.length < 10 || detalle.length > 20){
            valid = false;
            document.getElementById("error-detalle-info").style.display = "inline";
        }

        if(imagen.length == ""){
            valid = false;
            document.getElementById("error-imagen").style.display = "inline";
        }

        return valid;
    });

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


