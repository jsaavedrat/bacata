@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-image"> </i> CARRUSEL &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; AGREGAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('pagina.carrusel.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA / VISTA PREVIA DE CARRUSEL</p></div></a></div>
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

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> AGREGAR IMAGEN CARRUSEL</div>

    <form method="post" data-parsley-validate action="{{ route('pagina.carrusel.guardar') }}" enctype="multipart/form-data">
        @csrf
        <div style="width: 100%;float: left;">

            <div class="label-campo">
                <div id="error-imagen" style="display: none;" onclick="this.style.display='none'">Seleccione la imagen del carrusel</div>
                <label class="label-admin" for="imagen_carrusel"><i id="lista" class="icon-dot-single"></i>Seleccione imagen Carrusel (Menos de 1MB)<i id="cont-icon" class="icon-image"></i></label>
                <input type="file" class="campo-admin" name="imagen_carrusel" id="imagen_carrusel" required accept=".jpg,.jpeg,.png" autocomplete="off" spellcheck="false">
            </div>

            <div class="label-campo" id="label-texto-banner-2">
                <label class="label-admin" for="titulo_carrusel"><i id="lista" class="icon-dot-single"></i>Título principal carrusel<i id="cont-icon" class="icon-image"></i></label>
                <input class="campo-admin" name="titulo_carrusel" id="titulo_carrusel" maxlength="100" placeholder="Texto principal del carrusel" autocomplete="off" spellcheck="false">
            </div>

            <div class="label-campo" id="label-texto-banner">
                <label class="label-admin" for="subtitulo_carrusel"><i id="lista" class="icon-dot-single"></i>Subtítulo Carrusel<i id="cont-icon" class="icon-image"></i></label>
                <input class="campo-admin" name="subtitulo_carrusel" id="subtitulo_carrusel" maxlength="100" placeholder="Texto superior del banner en carrusel" autocomplete="off" spellcheck="false">
            </div>

            <div class="label-campo" id="label-texto-banner-3">
                <label class="label-admin" for="descripcion_carrusel"><i id="lista" class="icon-dot-single"></i>Descripción Carrusel<i id="cont-icon" class="icon-image"></i></label>
                <textarea class="campo-admin" name="descripcion_carrusel" id="descripcion_carrusel" maxlength="100" placeholder="Texto descriptivo del carrusel" style="margin-bottom: -5px!important;" autocomplete="off" spellcheck="false"></textarea>
            </div>

            <div class="label-campo" id="label-color-banner">
                <label class="label-admin" for="color_texto_carrusel"><i id="lista" class="icon-dot-single"></i>Color de los textos del bánner<i id="cont-icon" class="icon-image"></i></label>
                <input type="color" class="campo-admin" name="color_texto_carrusel" id="color_texto_carrusel">
            </div>

            <div class="label-campo" id="label-color-banner">
                <label class="label-admin" for="color_texto_carrusel"><i id="lista" class="icon-dot-single"></i>¿Colocar color desvanecido detras del texto?<i id="cont-icon" class="icon-image"></i></label>
                <select class="campo-admin" name="color_desvanecido_fondo" id="color_desvanecido_fondo">
                    <option value="">Seleccione (Opcional)</option>
                    <option value="rgba(255,255,255,0.5);">Blanco</option>
                    <option value="rgba(42,61,106,0.5);">Azul</option>
                    <option value="rgba(50,50,50,0.5);">Negro</option>
                    <option value="rgba(170,170,170,0.5);">Gris</option>
                </select>
            </div>

            <div class="label-campo">
                <label class="label-admin" for="orden"><i id="lista" class="icon-dot-single"></i>Orden en el que aparecerá<i id="cont-icon" class="icon-image"></i></label>
                <input type="number" class="campo-admin" name="orden" id="orden" placeholder="Ingresa el numero">
            </div>

        </div>

        <button class="boton-admin" id="crear-promocion-pagina"> Agregar Imagen</button>
    </form>
</div>

<script type="text/javascript">

    $("#crear-logo-marca").click(function(){

        document.getElementById("error-nombre-marca").style.display = "none";
        document.getElementById("error-imagen").style.display = "none";
        var valid = true;
        var nombre = document.getElementById("nombre_marca").value;
        var imagen = document.getElementById("imagen_logo_marca").value;

        if(nombre.length < 2){
            valid = false;
            document.getElementById("error-nombre-marca").style.display = "inline";
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

<style type="text/css">

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

    #crear-promocion-pagina{
        width: 300px;
    }
</style>
@endsection


