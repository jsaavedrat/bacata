@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-dollar"> </i> PROMOCIONES PAGINA WEB &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; AGREGAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('pagina.promociones_pagina.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE PROMOCIONES PAGINA WEB</p></div></a></div>
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

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> AGREGAR PROMOCIÓN EN PÁGINA WEB</div>

    <form method="post" data-parsley-validate action="{{ route('pagina.promociones_pagina.guardar') }}" enctype="multipart/form-data">
        @csrf
        <div style="width: 100%;float: left;">
            <div class="label-campo">
                <div id="error-nombre-marca" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Ingrese nombre promoción</div>
                <label class="label-admin" for="nombre_promocion_pagina"><i id="lista" class="icon-dot-single"></i>Nombre Promoción<i id="cont-icon" class="icon-bookmark"></i></label>
                <input type="text" name="nombre_promocion_pagina" id="nombre_promocion_pagina" class="campo-admin" placeholder="Nombre de la promoción" spellcheck="false" autocomplete="off" maxlength="50" required>
            </div>

            <div class="label-campo">
                <div id="error-imagen" style="display: none;" onclick="this.style.display='none'">Seleccione la imagen de la promoción</div>
                <label class="label-admin" for="imagen_promocion_pagina"><i id="lista" class="icon-dot-single"></i>Seleccione imagen Promoción<i id="cont-icon" class="icon-image"></i></label>
                <input type="file" class="campo-admin" name="imagen_promocion_pagina" id="imagen_promocion_pagina" required accept=".jpg,.jpeg,.png">
            </div>

            <div class="label-campo">
                <label class="label-admin" for="resultado_promocion"><i id="lista" class="icon-dot-single"></i>Resultado de ir a la promoción<i id="cont-icon" class="icon-bookmark"></i></label>
                <input type="text" name="resultado_promocion" id="resultado_promocion" class="campo-admin" placeholder="Ejemplo: Ganaste una montura" spellcheck="false" autocomplete="off" maxlength="50" required>
            </div>

            <div class="label-campo" id="label-qr">
                <div id="error-imagen" style="display: none;" onclick="this.style.display='none'">Seleccione si desea mostrar QR</div>
                <label class="label-admin" for="mostrar_qr"><i id="lista" class="icon-dot-single"></i>Desea mostrar QR?<i id="cont-icon" class="icon-image"></i></label>
                <select class="campo-admin" name="mostrar_qr" id="mostrar_qr" required onchange="seleccionoQr(this.value)">
                    <option value="">Seleccione</option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>

            <div class="label-campo" id="label-banner">
                <div id="error-imagen" style="display: none;" onclick="this.style.display='none'">Seleccione si desea mostrar un banner</div>
                <label class="label-admin" for="mostrar_banner"><i id="lista" class="icon-dot-single"></i>Desea mostrar Banner adicional?<i id="cont-icon" class="icon-image"></i></label>
                <select class="campo-admin" name="mostrar_banner" id="mostrar_banner" required onchange="seleccionoBanner(this.value)">
                    <option value="">Seleccione</option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
            
        </div>

        <button class="boton-admin" id="crear-promocion-pagina"> Agregar promoción</button>
    </form>
</div>

<script type="text/javascript">

    function seleccionoQr(opcion_qr){
        console.log("selecciono: "+opcion_qr);
        if(opcion_qr == "SI"){
            $("#label-qr").after(`
                <div class="label-campo" id="label-ubicacion-qr">
                    <div id="error-ubicacion" style="display: none;" onclick="this.style.display='none'">Ubicación del QR sobre la imagen</div>
                    <label class="label-admin" for="ubicacion_qr"><i id="lista" class="icon-dot-single"></i>Ubicación del QR sobre la imagen<i id="cont-icon" class="icon-image"></i></label>
                    <select class="campo-admin" name="ubicacion_qr" id="ubicacion_qr" required>
                        <option value="">Seleccione</option>
                        <option value="align-items-start justify-content-start">Superior - Izquierda</option>
                        <option value="align-items-center justify-content-start">Centrado - Izquierda</option>
                        <option value="align-items-end justify-content-start">Inferior - Izquierda</option>
                        <option value="align-items-start justify-content-center">Superior - Centrado</option>
                        <option value="align-items-center justify-content-center">Al centro de la Imagen</option>
                        <option value="align-items-end justify-content-center">Inferior - Centrado</option>
                        <option value="align-items-start justify-content-end">Superior - Derecha</option>
                        <option value="align-items-center justify-content-end">Centrado - Derecha</option>
                        <option value="align-items-end justify-content-end">Inferior - Derecha</option>
                    </select>
                </div>
                <div class="label-campo" id="label-texto-qr">
                    <label class="label-admin" for="texto_qr"><i id="lista" class="icon-dot-single"></i>Texto que describe el QR<i id="cont-icon" class="icon-image"></i></label>
                    <input class="campo-admin" name="texto_qr" id="texto_qr" maxlength="60" placeholder="Ejemplo: Escanéa el QR y gana un premio." required>
                </div>
                <div class="label-campo" id="label-color-qr">
                    <label class="label-admin" for="color_texto_qr"><i id="lista" class="icon-dot-single"></i>Color del texto que describe el QR<i id="cont-icon" class="icon-image"></i></label>
                    <input type="color" class="campo-admin" name="color_texto_qr" id="color_texto_qr" required>
                </div>
            `);
        }else{
            if ($("#label-ubicacion-qr").length){
                $("#label-ubicacion-qr").remove();
                $("#label-texto-qr").remove();
                $("#label-color-qr").remove();
            }
        }
    }

    function seleccionoBanner(opcion_banner){
        console.log("selecciono: "+opcion_banner);
        if(opcion_banner == "SI"){
            $("#label-banner").after(`
                <div class="label-campo" id="label-ubicacion-banner">
                    <div id="error-ubicacion-banner" style="display: none;" onclick="this.style.display='none'">Ubicación del Banner sobre la imagen</div>
                    <label class="label-admin" for="ubicacion_banner"><i id="lista" class="icon-dot-single"></i>Ubicación del Banner sobre la imagen<i id="cont-icon" class="icon-image"></i></label>
                    <select class="campo-admin" name="ubicacion_banner" id="ubicacion_banner" required>
                        <option value="">Seleccione</option>
                        <option value="align-items-start justify-content-start">Superior - Izquierda</option>
                        <option value="align-items-center justify-content-start">Centrado - Izquierda</option>
                        <option value="align-items-end justify-content-start">Inferior - Izquierda</option>
                        <option value="align-items-start justify-content-center">Superior - Centrado</option>
                        <option value="align-items-center justify-content-center">Al centro de la Imagen</option>
                        <option value="align-items-end justify-content-center">Inferior - Centrado</option>
                        <option value="align-items-start justify-content-end">Superior - Derecha</option>
                        <option value="align-items-center justify-content-end">Centrado - Derecha</option>
                        <option value="align-items-end justify-content-end">Inferior - Derecha</option>
                    </select>
                </div>
                <div class="label-campo" id="label-texto-banner">
                    <label class="label-admin" for="texto_banner"><i id="lista" class="icon-dot-single"></i>Texto que describe el Banner<i id="cont-icon" class="icon-image"></i></label>
                    <input class="campo-admin" name="texto_banner" id="texto_banner" maxlength="50" placeholder="Ejemplo: Promoción en tienda física" required>
                </div>
                <div class="label-campo" id="label-texto-banner-2">
                    <label class="label-admin" for="texto_banner_2"><i id="lista" class="icon-dot-single"></i>Texto 2 que describe el Banner<i id="cont-icon" class="icon-image"></i></label>
                    <input class="campo-admin" name="texto_banner_2" id="texto_banner_2" maxlength="50" placeholder="Ejemplo: Válido desde:_ hasta:_" required>
                </div>
                <div class="label-campo" id="label-color-banner">
                    <label class="label-admin" for="color_texto_banners"><i id="lista" class="icon-dot-single"></i>Color de los textos del banner<i id="cont-icon" class="icon-image"></i></label>
                    <input type="color" class="campo-admin" name="color_texto_banners" id="color_texto_banners" required>
                </div>
            `);
        }else{
            if ($("#label-ubicacion-banner").length){
                $("#label-ubicacion-banner").remove();
                $("#label-texto-banner").remove();
                $("#label-texto-banner-2").remove();
                $("#label-color-banner").remove();
            }
        }
    }

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


