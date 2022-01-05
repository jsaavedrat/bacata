@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-creative-commons-attribution"> </i> SERVICIOS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CREAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('pagina.servicios.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE SERVICIOS</p></div></a></div>
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
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Servicio creado Exitosamente</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Nombre de Servicio ya existe</div>@endif
    </div>

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Crear Servicio en página Web</div>

    <form method="post" data-parsley-validate action="{{ route('pagina.servicios.guardar') }}" enctype="multipart/form-data">
        @csrf
        <div style="width: 100%;float: left;">
            <div class="label-campo" style="width: 50%;">
                <div id="error-nombre-servicio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Seleccione nombre del servicio</div>
                <label class="label-admin" for="nombre_servicio"><i id="lista" class="icon-dot-single"></i>Nombre del servicio<i id="cont-icon" class="icon-bookmark"></i></label>
                <input type="text" name="nombre_servicio" id="nombre_servicio" class="campo-admin" placeholder="Nombre del servicio" spellcheck="false" autocomplete="off" maxlength="50" required>
            </div>

            <div class="label-campo" style="width: 50%;">
                <div id="error-imagen" style="display: none;" onclick="this.style.display='none'">Selecciona una Imagen</div>
                <label class="label-admin" for="imagen_servicio"><i id="lista" class="icon-dot-single"></i>seleccione la imagen del Servicio<i id="cont-icon" class="icon-image"></i></label>
                <input type="file" class="campo-admin" name="imagen_servicio" id="imagen_servicio" required>
            </div>

            <div class="label-campo" style="width: 100%;">
                <div id="error-descripcion-servicio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Seleccione descripción del servicio</div>
                <label class="label-admin" for="descripcion_servicio"><i id="lista" class="icon-dot-single"></i>Texto ó descripción del servicio<i id="cont-icon" class="icon-bookmarks"></i></label>
                <textarea name="descripcion_servicio" id="descripcion_servicio" class="campo-admin" placeholder="Descripción del servicio" spellcheck="false" autocomplete="off" maxlength="3000" style="height: 70px;"></textarea>
            </div>

        </div>

        <button class="boton-admin" id="crear-servicio"> Crear Servicio</button>
    </form>
</div>

<style type="text/css">

    #error-nombre-servicio,#error-descripcion-servicio,#error-imagen{
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

    .boton-admin {
        max-width: 300px;
        margin-left: 20px;
    }

</style>

<script type="text/javascript">

    $("#crear-servicio").click(function(){

        document.getElementById("error-nombre-servicio").style.display = "none";
        document.getElementById("error-descripcion-servicio").style.display = "none";
        document.getElementById("error-imagen").style.display = "none";
        var valid = true;

        var nombre = document.getElementById("nombre_servicio").value;
        var descripcion = document.getElementById("descripcion_servicio").value;
        var imagen = document.getElementById("imagen_servicio").value;

        if(nombre.length < 3){
            valid = false;
            document.getElementById("error-nombre-servicio").style.display = "inline";
        }

        if(descripcion.length < 7){
            valid = false;
            document.getElementById("error-descripcion-servicio").style.display = "inline";
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


