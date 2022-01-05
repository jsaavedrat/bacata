@extends('layouts.landing')
@section('content')
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::SLIDER PRINCIPAL::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->

@if($imagenes_carrusel != "[]")
<section class="hero-section">
    <div class="hero-slider owl-carousel">
        @foreach($imagenes_carrusel as $imagen_carrusel)
        <div class="hs-item set-bg" data-setbg="{{ asset('public/imagenes/pagina/carrusel') }}/{{$imagen_carrusel->imagen_carrusel}}">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-8 col-xl-6 col-lg-7 pt-4" style="border-radius: 30px;background-color: {{$imagen_carrusel->color_desvanecido_fondo}}">
                        <span class="spn" style="color: {{$imagen_carrusel->color_texto_carrusel}}!important;">{{$imagen_carrusel->subtitulo_carrusel}}</span>
                        <h2 class="h-2" style="color: {{$imagen_carrusel->color_texto_carrusel}}!important;">{{$imagen_carrusel->titulo_carrusel}}</h2>
                        <p class="b-txt" style="color: {{$imagen_carrusel->color_texto_carrusel}}!important;"><b>{{$imagen_carrusel->descripcion_carrusel}}</b></p>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-8 col-xl-6 col-lg-7 pt-4 tm-2">
                        <a href="{{ route('ecommerce.categorias') }}" class="site-btn sb-line">Comprar On-Line</a>
                        <a href="{{ route('ecommerce.carrito') }}" class="site-btn sb-white" style="background-color: {{$imagen_carrusel->color_desvanecido_fondo}}color:{{$imagen_carrusel->color_texto_carrusel}}!important;">Mi Carrito</a>
                    </div>
                </div>
                <div class="offer-card text-white">
                    <br>
                    <span><h4>Comprar</h4></span>
                    <h3>On-Line</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="container">
        <div class="slide-num-holder" id="snh-1"></div>
    </div>
</section>
@endif


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::INFORMACIÓN EXTRA:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


<section class="features-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 p-0 feature">
                <a href="{{route('pago')}}">
                <div class="feature-inner">
                    <div class="feature-icon">
                        <img src="{{ asset('public/ecommerce/img/icons/1.png') }}" alt="#">
                    </div>
                    <h2>Pago Online Seguro</h2>
                </div>
                </a>
            </div>
            <div class="col-md-3 p-0 pb-2 feature feature-act">
                <a href="{{route('welcome')}}/#seccion-membresias">
                <div class="feature-inner">
                    <div class="feature-icon">
                        <img src="{{ asset('public/ecommerce/img/icons/2.png') }}" alt="#">
                    </div>
                    <h2>PROMOCIONES</h2>
                </div>
                </a>
            </div>
            <div class="col-md-3 p-0 feature">
                <a href="{{route('welcome')}}/#seccion-promociones">
                <div class="feature-inner">
                    <div class="feature-icon">
                       <i class="icon-price-ribbon" style="font-size: 50px;"></i>
                    </div>
                    <h2>MEMBRESÍAS</h2>
                </div>
                </a>
            </div>
            <div class="col-md-3 p-0 feature feature-act">
                <a href="{{route('terminos_condiciones', ['termino' => 'Politica de Privacidad y Protección de Datos'])}}">
                <div class="feature-inner">
                    <div class="feature-icon">
                       <i class="icon-new" style="font-size: 50px; color:white;"></i>
                    </div>
                    <h2 style="color:white">POLÍTICAS DE BIOSEGURIDAD</h2>
                </div>
                </a>
            </div>
        </div>
    </div>
</section>


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::SECCION KIDS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


@if($imagenes_kids != "[]")
<section id="kids" style="background-image: url('{{ asset('public/imagenes/pagina/kids/fondo') }}/{{$fondo_kids->nombre_imagen_kids}}');">
    <div id="fondo-kids" style="padding-bottom: 50px; z-index: 1;background-color: rgba(255,255,255,0.6);">
        <div class="col-12" style="text-align: center; padding-top: 50px;">
            <h1 style="color: #2A3D6A; font-weight: bold;">SECCIÓN
                <x style="color: #DB802D;">K</x>
                <x style="color: #ADCB49;">I</x>
                <x style="color: #F3CA30;">D</x>
                <x style="color: #00ACEC;">S</x>
            </h1>
        </div>

        <div class="container">
            @foreach($imagenes_kids as $imagen_kids)
            <div class="container" style="margin-bottom: 5vw;">
                <div class="col-12 text-center titulo-imagen-kids" style="height:5vw; color:#2A3D6A; font-size:3vw; line-height:5vw; font-weight:bold;">
                    {{$imagen_kids->titulo_imagen_kids}}
                </div>
                <div class="col-12" style="/*border: 5vw solid #929496;*/ border-top:0px">
                    <div class="row">
                        <img src="{{ asset('public/imagenes/pagina/kids') }}/{{$imagen_kids->nombre_imagen_kids}}" style="width:100%;">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::LO MAS RECIENTES::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


@if($ultimos_productos_ecommerce != "[]")
<section class="top-letest-product-section" id="productos-recientes-ecommerce">
    <div class="container pt-4 pb-4">
        <div class="section-title">
            <p>LO MÁS RECIENTE</p>
        </div>
        <div class="product-slider owl-carousel pt-4 pb-4">

            @foreach($ultimos_productos_ecommerce as $producto_ecommerce)
                <!-- <div class="col-lg-3 col-sm-6"> -->
                    <div class="product-item caja-producto">
                        <div class="pi-pic">
                            <!-- <div class="tag-sale">Nuevo</div> -->
                            <div class="col-12 h-100">
                                <div class="row d-flex justify-content-center align-items-center h-100">
                                    <a href="{{ route('ecommerce.articulo',$producto_ecommerce->id_producto) }}">
                                        <img src="{{ asset('public/imagenes/sistema/productos') }}/{{$producto_ecommerce->nombre_imagen}}" class="imagen-producto">
                                    </a>
                                </div>
                            </div>
                            <div class="pi-links">
                                <a onclick="agregarCarrito({{$producto_ecommerce->id_producto}},1,`{{$producto_ecommerce->nombre_producto}}`,`{{$producto_ecommerce->nombre_imagen}}`,{{$producto_ecommerce->precio_base}});" class="add-card"><i class="flaticon-bag"></i><span>AGREGAR AL CARRITO</span></a>
                                <a href="{{ route('ecommerce.articulo',$producto_ecommerce->id_producto) }}" class="add-card"><i class="flaticon-add"></i><span>Ver Detalle</span></a>
                            </div>
                        </div>
                        <div class="pi-text col-12">
                            <div class="row">
                                <div class="col-12 pl-0 precio-producto text-center">$ {{number_format($producto_ecommerce->precio_base, 2, ',', '.')}}</div>
                                <div class="col-12 pr-0 pl-0 nombre-producto" title="{{$producto_ecommerce->nombre_producto}}">{{$producto_ecommerce->nombre_producto}}</div>
                            </div>
                        </div>
                    </div>
                <!-- </div> -->
            @endforeach
        </div>
        @if(count($ultimos_productos_ecommerce) == 0)
            <div class="col-12 d-flex justify-content-center mt-3">
                No se encontraron productos.
            </div>
        @endif
    </div>
</section>
@endif


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::MEMBRESIAS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


@if(isset($producto_membresias->id_tipo_producto_especial) && $membresias != "[]")
<section class="product-filter-section" id="seccion-membresias">
    <div class="container">
        <div class="section-title">
            <p>MEMBRESÍAS</p>
        </div>
        <div class="row">
            @foreach($membresias as $membresia)

                <div class="col-lg-4 col-sm-12 h-full">
                    <div class="col-lg-12 col-sm-12 col-membresia">
                        <div class="col-12">
                            <h4 class="text-center">{{$membresia->nombre_modelo}}</h4>
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <img src="{{ asset('public/imagenes/sistema/productos') }}/{{$membresia->nombre_imagen}}" style="max-width: 150px;height: 150px;">
                                </div>
                            </div>
                            @foreach($membresia->especificaciones as $especificacion)
                                @if($especificacion->nombre_clasificacion == "Estrellas Membresia")
                                    <div class="text-center" style="font-size: 20px;">
                                        @for($i=1; $i<=5; $i++)
                                            @if($i <= $especificacion->nombre_especificacion)
                                                <i class="fa fa-star-o estrella-gold"></i>
                                            @else
                                                <i class="fa fa-star-o estrella-silver"></i>
                                            @endif
                                        @endfor
                                    </div>
                                @endif
                            @endforeach
                            <ul>
                            @foreach($membresia->especificaciones as $especificacion)
                                @if($especificacion->nombre_clasificacion != "Estrellas Membresia")    
                                    <li class="li-membresia" title="{{$especificacion->nombre_especificacion}}">{{$especificacion->nombre_especificacion}}</li><br>
                                @endif
                            @endforeach
                            </ul>
                            <div class="text-center">
                                <h3 class="p-price">${{number_format($membresia->precio_base, 0, ',', '.')}}</h3>
                                <a onclick="mostrar_membresia({{$membresia->id_producto}},`Membresía {{$membresia->nombre_modelo}}`,`{{$membresia->nombre_imagen}}`,{{$membresia->precio_base}});" class="site-btn sb-dark" style="color:white;">Información</a>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>
</section>
<p id="abrir-modal-membresia" href="#modal-membresia" role="button" data-toggle="modal" target="#modal-membresia" style="display: none;">Consultar Membresia</p>
<script type="text/javascript">
    function mostrar_membresia(id_membresia,nombre_membresia,imagen_membresia,precio_membresia){
        console.log("en mostrar membresia");
        console.log(id_membresia);
        console.log(nombre_membresia);
        console.log(imagen_membresia);
        console.log(precio_membresia);
        document.getElementById('imagen-membresia-modal').src = "{{ asset('public/imagenes/sistema/productos') }}/"+imagen_membresia;
        document.getElementById('nombre-membresia-modal').innerText = nombre_membresia;
        document.getElementById('precio-membresia-modal').innerText = "$ "+precio_membresia;
        $("#contenedor-boton-membresia-modal").empty();
        $("#contenedor-boton-membresia-modal").append(`
            <a class="site-btn sb-dark h-auto" onclick="agregarCarrito(`+id_membresia+`,1,'`+nombre_membresia+`','`+imagen_membresia+`',`+precio_membresia+`)" href="{{ route('ecommerce.carrito') }}"> COMPRAR</a>
        `);

        var url="{{route('buscar.membresia')}}";
        var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id_membresia": id_membresia
        };
        $.ajax({
            type: 'POST',
            url: url,
            data: datos,
            success: function(data) {
                console.log("success");
                console.log(data);
                document.getElementById('abrir-modal-membresia').click();
                $("#especificaciones-membresia-modal").empty();
                for(var i=0; i<data.length; i++){
                    $("#especificaciones-membresia-modal").append(`
                        <li><x class="clasificacion-membresia">`+data[i].nombre_clasificacion+`:</x><br><x class="especificacion-membresia">`+data[i].nombre_especificacion+`</x><br></li>
                    `);
                }
            },
            error: function(data) {
                console.log("error");
                swal({
                    title: "Error",
                    text: "Ocurrió un problema al consultar la membresía",
                    icon: "error",
                    button: "Ok",
                });
            }
        });

    }
</script>
<div class="modal fade" id="modal-membresia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div id="opacar-fondo-modal-citas">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> &nbsp; MEMBRESIA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 h-100">
                            <div class="row h-100">
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 h-100 pt-2">
                                    <div class="row h-50">
                                        <div class="col-12">
                                            <div class="row d-flex justify-content-center">
                                                <img id="imagen-membresia-modal" style="max-width: 120px;height: 120px;">
                                                <div class="col-12 text-center text-uppercase" id="nombre-membresia-modal"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex align-items-end h-50">
                                        <div class="col-12">
                                            <div class="row d-flex justify-content-center mt-4">
                                                <div class="col-12 text-center font-weight-bold" id="precio-membresia-modal" style="font-size: 30px;"></div>
                                                <div class="col-12 d-flex justify-content-center pl-0 pr-0" id="contenedor-boton-membresia-modal"></div>
                                                <div class="col-12 text-center mt-2"><a href="{{route('terminos_condiciones', ['termino' => 'Membresias'])}}" target="_blank" style="font-size:12px;">Términos y condiciones.</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <div class="col-12 mb-3 text-center font-weight-bold">DESCRIPCIÓN MEMBRESÍA</div>
                                    <div class="col-12" style="background-color: rgba(200,200,200,0.6); overflow-y: scroll; max-height: calc(100vh - 200px)!important;">
                                        <div class="col-12">
                                            <ul id="especificaciones-membresia-modal" class="pt-4">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::PROMOCION DEL MES::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


@if($promociones_pagina != "[]")
<section class="banner-section" id="seccion-promociones">
    <div class="section-title">
        <p>PROMOCIONES<br></p><br>
    </div>
    @foreach($promociones_pagina as $promocion_pagina)
        
        <div class="container mb-4">
            <h4 style="text-transform: uppercase;">{{$promocion_pagina->nombre_promocion_pagina}}</h4>
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
                                <a href="{{ route('welcome') }}/promocion/{{$promocion_pagina->id_promocion_pagina}}" class="boton-promocion">VER PROMOCIÓN</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</section>
@endif


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::EQUIPO DE TRABAJO:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


@if($imagenes_equipo != "[]")
<section class="equipo" id="equipo-trabajo">
    <div class="section-title">
        <p>NUESTRO EQUIPO DE TRABAJO</p>
    </div>
    <div class="container">
        <div class="col-12">
            <div class="row">
                @foreach($imagenes_equipo as $imagen_equipo)
                    @if($loop->iteration == 1)
                        <div class="col-12">
                            <div class="col-12 imagen-equipo" style="background-image: url('{{ asset('public/imagenes/pagina/equipo/') }}/{{$imagen_equipo->nombre_imagen_equipo}}'); height: 400px;"></div>
                            <div class="col-12 text-center titulo-imagen-equipo" style="font-size: 24px;">{{$imagen_equipo->titulo_imagen_equipo}}</div>
                        </div>
                    @else
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="col-12 imagen-equipo" style="background-image: url('{{ asset('public/imagenes/pagina/equipo/') }}/{{$imagen_equipo->nombre_imagen_equipo}}');"></div>
                            <div class="col-12 text-center titulo-imagen-equipo">{{$imagen_equipo->titulo_imagen_equipo}}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif


<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::MARCAS SLICK:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


@if($logos_marcas != "[]")
<section class="top-letest-product-section" id="logosMarcas">
    <div class="section-title">
        <p>MARCAS</p>
    </div>
    <div class="container">
        <div class="product-slider owl-carousel">
            @foreach($logos_marcas as $logo_marca)
                <div class="product-item" style="float: left;">
                    @if($logo_marca->pagina_web != null && $logo_marca->pagina_web != "")
                    <a href="http://{{$logo_marca->pagina_web}}" target="_blank">
                    @else
                    <a>
                    @endif
                        <div class="pi-pic" style="float: left;height: 150px!important;">
                            <img src="{{ asset('public/imagenes/pagina/logos_marcas') }}/{{$logo_marca->imagen_logo_marca}}" style="width: 100%;max-height: 100%;">
                        </div>
                    </a>
                    <div class="pi-text text-center" style="float: left;width: 100%;">
                        @if($logo_marca->pagina_web != null && $logo_marca->pagina_web != "")
                        <a href="http://{{$logo_marca->pagina_web}}" target="_blank"><p style="text-align: center; font-weight: bold; text-transform: uppercase;"><b>{{$logo_marca->nombre_marca}}</b></p></a>
                        @else
                        <p style="text-align: center; font-weight: bold; text-transform: uppercase;"><b>{{$logo_marca->nombre_marca}}</b></p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif


<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::INFOS SLICK:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


@if($infos != "[]")
<section class="top-letest-product-section" id="infos">
    <div class="section-title">
        <p>INFOS</p>
    </div>
    <div class="container">
        <div class="product-slider owl-carousel">
            @foreach($infos as $info)
                <div class="product-item" style="float: left;">
                    @if($info->pagina_web != null && $info->pagina_web != "")
                    <a href="http://{{$info->pagina_web}}" target="_blank">
                    @else
                    <a>
                    @endif
                        <div class="pi-pic" style="float: left;height: 150px!important;">
                            <img src="{{ asset('public/imagenes/pagina/infos') }}/{{$info->imagen_info}}" style="width: 100%;max-height: 100%;">
                        </div>
                        <h2 class="text-center">{{$info->nombre_info}}</h2>
                    </a>
                    <div class="pi-text text-center" style="float: left;width: 100%;">
                        @if($info->pagina_web != null && $info->pagina_web != "")
                        <a href="http://{{$info->pagina_web}}" target="_blank"><p style="text-align: center; font-weight: bold;"><b>{{$info->detalle_info}}</b></p></a>
                        @else
                        <p style="text-align: center; font-weight: bold;"><b>{{$info->detalle_info}}</b></p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<script type="text/javascript" src="{{ asset('public/js/jquery.js') }}"></script>
<script src="{{ asset('public/js/qrcode/qrcode.js') }}"></script>
<script type="text/javascript">
    /*:::::::::TITULO DE LA PAGINA:::::::::*/
    $(document).ready(function(){
        console.log("el titulo es "+titulo);
        document.getElementsByTagName("title")[0].innerHTML = titulo;
    });
    

    /*::::::::::::::::::::CREAR QR a cada promocion:::::::::::::::::::*/
    @foreach($promociones_pagina as $promocion_pagina)

        var miCodigoQR_{{$promocion_pagina->id_promocion_pagina}} = new QRCode("codigoQR-{{$promocion_pagina->id_promocion_pagina}}");
        var cadena = "{{ route('welcome') }}/promocion/{{$promocion_pagina->id_promocion_pagina}}";
        miCodigoQR_{{$promocion_pagina->id_promocion_pagina}}.makeCode(cadena);

    @endforeach
</script>
<style type="text/css">
    .col-membresia {
        border:1px solid rgba(50,50,50,0.7);
        padding: 20px;
        margin-bottom: 20px;
        background-color: white;
    }
    .col-membresia:hover{
        box-shadow: 0px 0px 20px rgba(50,50,50,0.8);
    }
    .estrella-gold {
        color:rgba(250,200,0,1);
    }
    .estrella-silver {
        color:rgba(200,200,200,1);
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

    img {
      pointer-events: none;
    }

    .imagen-equipo {
        height: 300px;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    #kids {
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    
    .imagen-equipo {
        height: 300px;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .sidenav a{
        background-color: #2A3D6A!important;
    }

    .titulo-imagen-equipo{
        text-transform: uppercase;
        font-size: 18px;
        height: 30px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .li-membresia{
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
@endsection