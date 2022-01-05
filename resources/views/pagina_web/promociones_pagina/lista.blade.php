@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-dollar"> </i> PROMOCIONES PAGINA WEB &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; LISTA</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>
<link rel="stylesheet" href="{{ asset('public/ecommerce/css/bootstrap.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/ecommerce/css/style.css') }}"/>
<script src="{{ asset('public/ecommerce/js/bootstrap.min.js') }}"></script>
<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('pagina.promociones_pagina.crear') }}"><i class="fa fa-plus herramientas"></i><div class="content-texto"><p class="texto-icono">AGREGAR PROMOCIÓN PAGINA WEB</p></div></a></div>
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
    <div id="texto-titulo"> <i class="icon-list"> </i> PROMOCIONES MOSTRADAS EN PÁGINA WEB</div>

    <div id="content-mensaje">
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Promoción agregada con Éxito.</div>@endif
        @if($estatus=="eliminado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Promoción eliminada con Éxito.</div>@endif
        @if($estatus=="actualizado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Promoción Actualizada con Éxito.</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; No se pudo eliminar la Promoción.</div>@endif
    </div>
        
    <section class="banner-section">
        @foreach($promociones_pagina as $promocion_pagina)
            
            <div class="container mb-5">
                <div class="col-12 mb-1">
                    <div class="row">
                        <div class="col"><h4 style="text-transform: uppercase;">{{$promocion_pagina->nombre_promocion_pagina}}</h4></div>
                        <div class="col-auto">
                            <div class="row justify-content-center">
                                <div class="col-auto boton-membresia" onclick="editar('{{$promocion_pagina->id_promocion_pagina}}');">Editar</div>
                                <div class="col-auto boton-membresia ml-2" onclick="eliminar('{{$promocion_pagina->id_promocion_pagina}}','{{$promocion_pagina->nombre_promocion_pagina}}')">Eliminar</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row position-relative">
                    <img class="col-12" src="{{ asset('public/imagenes/pagina/promociones') }}/{{$promocion_pagina->imagen_promocion_pagina}}">
                    @if($promocion_pagina->mostrar_qr == "SI")
                        <div class="col-12 position-absolute h-100">
                            <div class="row h-100 d-flex {{$promocion_pagina->ubicacion_qr}}">
                                <div class="col-2 ml-4 mr-4 mt-2 mb-2">
                                    <div class="row d-flex justify-content-center">
                                        <div id="codigoQR-{{$promocion_pagina->id_promocion_pagina}}" class="codigoQR"></div>
                                        <div class="col-auto text-center texto-qr" style="color: {{$promocion_pagina->color_texto_qr}}">{{$promocion_pagina->texto_qr}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($promocion_pagina->mostrar_banner == "SI")
                        <div class="col-12 position-absolute h-100">
                            <div class="row h-100 d-flex {{$promocion_pagina->ubicacion_banner}}">
                                <div class="col-auto ml-2 mr-2 mt-2 mb-2">
                                    <p class="texto-banner texto-banner-1" style="color: {{$promocion_pagina->color_texto_banners}}">{{$promocion_pagina->texto_banner}}</p>
                                    <p class="texto-banner texto-banner-2" style="color: {{$promocion_pagina->color_texto_banners}}">{{$promocion_pagina->texto_banner_2}}</p>
                                    <a href="{{ route('welcome') }}/promocion/{{$promocion_pagina->id_promocion_pagina}}" target="_blank" class="boton-promocion">VER PROMOCIÓN</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </section>
</div>

<div id="modal-eliminar">
    <form method="post" action="{{ route('pagina.promociones_pagina.inactivar') }}">
        @csrf
        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">¿Realmente deseas eliminar la Promoción en Página web: <x id="nombre-eliminar"></x></div>
                <div id="content-botones-modal-eliminar">
                    <div class="content-boton-modal-eliminar">
                        <button class="boton-modal-eliminar"><i class="icon-trash"> </i> Eliminar</button>
                    </div>
                    <div class="content-boton-modal-eliminar">
                        <div class="boton-modal-eliminar" style="padding-top:1vh;" onclick="cerrarModal();"><i class="fa fa-close"> </i> Atrás</div>
                    </div>
                </div>
            </div>
        </div>
        <input type="number" name="eliminar" id="eliminar" style="display: none;">
    </form>
</div>

<form method="post" action="{{ route('pagina.promociones_pagina.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>

<script src="{{ asset('public/js/qrcode/qrcode.js') }}"></script>
<script type="text/javascript">
    /*::::::::::::::::::::CREAR QR a cada promocion:::::::::::::::::::*/
    @foreach($promociones_pagina as $promocion_pagina)

        var miCodigoQR_{{$promocion_pagina->id_promocion_pagina}} = new QRCode("codigoQR-{{$promocion_pagina->id_promocion_pagina}}");
        var cadena = "{{ route('welcome') }}/promocion/{{$promocion_pagina->id_promocion_pagina}}";
        miCodigoQR_{{$promocion_pagina->id_promocion_pagina}}.makeCode(cadena);

    @endforeach
</script>

<style type="text/css">
    #td-imagen{
        padding: 0px!important;
        width: 200px;
    }

    .content-imagen-td{
        width: 100%;
        height: 100px;
        float: left;
        display: flex;
        justify-content: center;
        padding-top:20px;
        padding-bottom: 20px;
    }
    .codigoQR{
        padding: 15px;
        background-color: white;
        border:1px solid black;
        margin-bottom: 5px;
    }
    .texto-qr{
        font-size: 18px;
        font-weight: bold;
        line-height: 16px;
    }
    .texto-banner{
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 0px;
    }
    .texto-banner-1{
        font-size: 20px;
        line-height: 25px;
    }
    .texto-banner-2{
        font-size: 25px;
        line-height: 30px;
    }
    .boton-promocion{
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        padding: 2px 30px 0px 30px;
        border-radius: 50px;
        text-transform: uppercase;
        color: white!important;
        background-color: rgba(50,50,50,0.95);
        line-height: 40px;
        height: 40px;
        cursor: pointer;
        text-align: center;
    }
    .boton-membresia {
        height: 30px;
        font-size: 14px;
        color: white;
        width: 100px;
        text-align: center;
        background-color: rgba(50,50,50,1);
        text-transform: uppercase;
        line-height: 30px;
        cursor: pointer;
    }
</style>
@endsection




