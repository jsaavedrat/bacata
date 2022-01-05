@php

$sucursales = DB::table('sucursals')
->where('estado_sucursal','=','activo')
->where('id_sucursal','!=',0)
->get();

$servicios = DB::table('servicios')
->where('estado_servicio','=','activo')
->get();

$tipos_identificacion = DB::table('tipos_identificacion')
->where('estado_tipo_identificacion','=','activo')
->get();

$whatsapp = DB::table('redes_sociales')
->where('id_red',"=",1)
->where('estado_red','=','activo')
->first();
if(isset($whatsapp)){
    $whatsapp->acceso_red = "https://api.whatsapp.com/send?phone=57" . $whatsapp->acceso_red . "&text=" . str_replace(" ", "%20", $whatsapp->texto_extra_red);
}

$facebook = DB::table('redes_sociales')
->where('id_red',"=",2)
->where('estado_red','=','activo')
->first();

$instagram = DB::table('redes_sociales')
->where('id_red',"=",3)
->where('estado_red','=','activo')
->first();

$fuentes_actual = DB::table('fuentes_pagina')
->where('estado_fuente','=','actual')
->get();

$imagenes_kids = DB::table('imagenes_kids')
->where('estado_imagen_kids','=','activo')
->where('id_imagen_kids','!=',0)
->orderBy('orden')
->get();

$promociones_pagina = DB::table('promociones_pagina')
->where('estado_promocion_pagina','=','activo')
->get();

$colores_pagina = DB::table('colores_pagina')
->where('estado_color_pagina','=','activo')
->get();

$id_monturas = DB::table('tipo_productos')
->where('nombre_tipo_producto','=','Monturas')
->where('estado_tipo_producto','=','activo')
->first();

$especificaciones_monturas = DB::table('tipo_productos')
->where('tipo_productos.id_tipo_producto','=',$id_monturas->id_tipo_producto)
->where('tipo_productos.estado_tipo_producto','=','activo')
->leftJoin('clasificacion_tipo_productos','clasificacion_tipo_productos.id_tipo_producto','=','tipo_productos.id_tipo_producto')
->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
->where('estado_clasificacion_tipo_producto','activo')
->leftJoin('especificaciones','especificaciones.id_clasificacion','=','clasificaciones.id_clasificacion')
->where('estado_especificacion','=','activo')
->where('especificaciones.mostrar_landing','=','activo')
->select('especificaciones.id_especificacion','especificaciones.nombre_especificacion','especificaciones.id_clasificacion')
->orderBy('especificaciones.id_clasificacion','desc')
->distinct()
->get();

$marcas_monturas = DB::table('tipo_productos')
->where('tipo_productos.id_tipo_producto','=',$id_monturas->id_tipo_producto)
->where('estado_tipo_producto','=','activo')
->leftJoin('tipo_producto_marcas','tipo_producto_marcas.id_tipo_producto','=','tipo_productos.id_tipo_producto')
->where('mostrar_landing','=','inactivo')
->leftJoin('marcas','marcas.id_marca','=','tipo_producto_marcas.id_marca')
->where('estado_marca','=','activo')
->select('marcas.id_marca','marcas.nombre_marca','tipo_productos.nombre_tipo_producto')
->get();

$id_gafas_sol = DB::table('tipo_productos')
->where('nombre_tipo_producto','=','Gafas De Sol')
->where('estado_tipo_producto','=','activo')
->first();

$especificaciones_gafas_sol = DB::table('tipo_productos')
->where('tipo_productos.id_tipo_producto','=',$id_gafas_sol->id_tipo_producto)
->where('tipo_productos.estado_tipo_producto','=','activo')
->leftJoin('clasificacion_tipo_productos','clasificacion_tipo_productos.id_tipo_producto','=','tipo_productos.id_tipo_producto')
->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
->where('estado_clasificacion_tipo_producto','activo')
->leftJoin('especificaciones','especificaciones.id_clasificacion','=','clasificaciones.id_clasificacion')
->where('estado_especificacion','=','activo')
->where('especificaciones.mostrar_landing','=','activo')
->select('especificaciones.id_especificacion','especificaciones.nombre_especificacion','especificaciones.id_clasificacion')
->orderBy('especificaciones.id_clasificacion','desc')
->distinct()
->get();

$marcas_gafas_sol = DB::table('tipo_productos')
->where('tipo_productos.id_tipo_producto','=',$id_gafas_sol->id_tipo_producto)
->where('estado_tipo_producto','=','activo')
->leftJoin('tipo_producto_marcas','tipo_producto_marcas.id_tipo_producto','=','tipo_productos.id_tipo_producto')
->where('mostrar_landing','=','inactivo')
->leftJoin('marcas','marcas.id_marca','=','tipo_producto_marcas.id_marca')
->where('estado_marca','=','activo')
->select('marcas.id_marca','marcas.nombre_marca','tipo_productos.nombre_tipo_producto')
->get();


$id_usuario = Auth::id();
if(isset($id_usuario)){
    $usuario = DB::table('users')
    ->where('id','=',$id_usuario)
    ->first();
}

$cliente_appweb = DB::table('cliente_appweb')
->where('estado_cliente_appweb','=','activo')
->first();

@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <title>{{$cliente_appweb->titulo_pagina}}</title>
    <meta charset="UTF-8">
    <meta name="description" content=" Divisima | eCommerce Template">
    <meta name="keywords" content="divisima, eCommerce, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link href="{{ asset('public/imagenes/sistema/cliente_empresa') }}/{{$cliente_appweb->nombre_imagen_icono}}" rel="shortcut icon"/>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,300i,400,400i,700,700i" rel="stylesheet">


    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('public/ecommerce/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/ecommerce/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/ecommerce/css/flaticon.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/ecommerce/css/slicknav.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/ecommerce/css/jquery-ui.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/ecommerce/css/owl.carousel.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/ecommerce/css/animate.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/ecommerce/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/ecommerce/css/estilosoptica.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/css/fonts/style.css') }}">

<style type="text/css">

    @foreach($fuentes_actual as $fuente_actual)

        @font-face {
            font-family: '{{$fuente_actual->clase_fuente}}'; 
            src: url('{{ route('welcome') }}/public/fuentes/{{$fuente_actual->nombre_archivo}}');                      
        }
        body {
            font-family: '{{$fuente_actual->clase_fuente}}';
        }
    @endforeach

    .social-links-warp {
        padding-bottom: 0px;
    }

    #car {
        top: 159px;
    }

    h4{
        padding: 0.2rem;
    }
    #content-paciente{
        border: 1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 30px rgba(100,100,120,0.3);
        background-color: rgba(255,255,255,0.8);
        margin: 2rem;
        background-color: white;
        padding: 1rem;
    }
    .content-input-50{
        width: 50%;
        padding:0px 10px 0px 10px;
        float: left;
        box-sizing: border-box;
        margin-right: 50%;
        height: 100px;
    }
    .content-input{
        width: 33.3%;
        padding:0px 10px 0px 10px;
        float: left;
        box-sizing: border-box;
        /*border:1px solid red;*/
        height: 100px;
    }
    .label{
        width: 100%;
        height: 35px;
        color: black;
        margin:0px;
        font-size: 18px;
        text-shadow: 0px 0px 2px white;
        box-sizing: border-box;
        padding-top:10px;
    }
    .input{
        width: 100%;
        height: 35px;
        border:1px solid rgb(115,115,115);
        padding:0.3rem;
        background-color: white;
        background-color: #f0f0f0;
        box-sizing: border-box;
    }

    .modal-header{
        background-color:#282828;
    }
    .modal-header span{
        color:white;
    }
    #exampleModalLabel{
        color:white;
    }
    .modal-content{
        background-image: url("{{ asset('public/imagenes/sistema/cliente_empresa') }}/{{$cliente_appweb->nombre_imagen_cliente}}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 60%;
    }
    #opacar-fondo-modal-citas{
        background-color: rgba(255,255,255,0.8);
        border-radius: .3rem;
    }

    #cookies{
        height: 200px;
        bottom: 0;
        z-index: 10000;
        background-color: rgba(255,255,255,0.7);
        display: none;
    }

    .caja-cookies{
        background-color: rgba(50,50,50,0.8);
        width: 100%;
        max-width: 300px;
        min-width: 200px;
    }

    .titulo-cookies{
        font-size: 30px;
        font-weight: bold;
        color:white;
    }

    .botones-cookies{
        background-color: rgba(255,255,255,0.7);
        font-weight: bold;
        cursor: pointer;
    }.botones-cookies:hover{
        background-color: rgba(255,255,255,1);
    }

    .caja-informacion-cookies{
        border-top: 5px solid rgba(50,50,50,0.8);
        overflow-y: scroll;
    }

    .caja-informacion-cookies::-webkit-scrollbar {
        width: 10px;
        height: 10px;
        background-color: rgba(0,0,0,0);
    }
    .caja-informacion-cookies::-webkit-scrollbar-thumb {
        background: rgba(100,100,100,1);
    }
    .caja-informacion-cookies::-webkit-scrollbar-thumb:hover {
        background: rgba(160,160,160,1);
    }
    .caja-informacion-cookies::-webkit-scrollbar-track {
        background: rgba(0,0,0,0);
    }

    .informacion-cookies{
        color: rgba(50,50,50,1);
        font-weight: bold;
    }

    .columna-categoria-marcas{
        /*border: 1px solid rgba(220,220,220,1);*/
        padding-top: 10px;
    }

    .titulo-categoria-marcas{
        color: rgba(80,80,80,1);
        font-weight: 300!important;
        padding-bottom: 15px;
        /*border-bottom: 1px solid rgba(220,220,220,1);*/
        font-size: 14px;
    }

    .elementos-categoria-marcas{
        overflow-y: scroll;
        max-height: calc(100vh - 200px);
    }
    .elementos-categoria-marcas::-webkit-scrollbar {
        width: 6px;
        height: 6px;
        background-color: rgba(0,0,0,0);
    }
    .elementos-categoria-marcas::-webkit-scrollbar-thumb {
        background: rgba(200,200,200,1);
    }
    .elementos-categoria-marcas::-webkit-scrollbar-thumb:hover {
        background: rgba(160,160,160,1);
    }
    .elementos-categoria-marcas::-webkit-scrollbar-track {
        background: rgba(0,0,0,0);
    }

    .elemento-categoria-marca{
        padding:0px!important;
    }
</style>
</head>
<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <div class="col-12 position-fixed" id="cookies">
        <div class="row h-100">
            <div class="col caja-informacion-cookies h-100">
                <div class="col-12 titulo-cookies text-center mt-3" style="color: rgba(50,50,50,1);">ACEPTAR POLITICA DE USO DE COOKIES</div>
                <div class="col-12 informacion-cookies">
                    EN {{$cliente_appweb->nombre_cliente_sas}}, {{$cliente_appweb->dominio}} utilizamos recopilación de cookies propias para mejorar la experiencia y uso de nuestro sistema web. mientras utilices nuestro sitio, se dará por entendido que estás de acuerdo con nuestra política de uso de cookies de igual forma, deberás aceptar ó rechazar las cookies de nuestro sitio para omitir este mensaje.<br>
                    <a href="{{route('terminos_condiciones', ['termino' => 'Cookies'])}}" target="_blank" style="font-size:14px;">Términos y condiciones.</a>
                </div>
            </div>
            <div class="col-auto d-inline-block h-100 caja-cookies">
                <div class="row mt-3">
                    <div class="col-12 titulo-cookies text-center">COOKIES</div>
                </div>
                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="col-12 botones-cookies pt-2 pb-1 text-center" onclick="seleccionarCookies('aceptadas')">ACEPTAR TODAS</div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="col-12 botones-cookies pt-2 pb-1 text-center" onclick="seleccionarCookies('rechazadas')">RECHAZAR TODAS</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::HEADER::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
    <!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


    <header class="header-section">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class=" col-12 col-lg-3 text-center text-lg-left" style="height:70px;">
                        <a href="{{route('welcome')}}" class="site-logo d-flex justify-content-center">
                            <img src="{{ asset('public/imagenes/sistema/cliente_empresa') }}/{{$cliente_appweb->nombre_imagen_cliente}}" alt="" style="height:70px;">
                        </a>
                    </div>
                    <div class="col-12 col-xl-9 col-lg-9 pt-3">
                        <div class="user-panel float-right col-12">
                            @guest
                                <div class="up-item float-right">
                                    <a href="{{route('login')}}">
                                        <div class="shopping-card">
                                            <i class="flaticon-profile"></i>
                                        </div> INICIAR SESIÓN
                                    </a>
                                </div>
                            @else
                                <div class="up-item float-right">
                                    <a style="text-transform: uppercase; cursor: pointer;" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <div class="shopping-card">
                                            <i class="flaticon-logout"></i>
                                        </div> SALIR
                                    </a>
                                </div>
                                <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                                    @csrf
                                </form>  
                                @if($usuario->tipo_usuario == "" || $usuario->tipo_usuario == null || $usuario->tipo_usuario == "cliente")
                                    <div class="up-item float-right">
                                        <a href="{{route('ecommerce.cliente')}}" style="text-transform: uppercase;">
                                            <div class="shopping-card">
                                                <i class="flaticon-profile"></i>
                                            </div> {{$usuario->name}} 
                                        </a>
                                    </div>
                                @else
                                    <div class="up-item float-right">
                                        <a href="{{route('home')}}" style="text-transform: uppercase;">
                                            <div class="shopping-card">
                                                <i class="flaticon-profile"></i>
                                            </div> {{$usuario->name}} 
                                        </a>
                                    </div>
                                @endif
                                <!-- <div class="up-item float-right">
                                    <a style="text-transform: uppercase;">
                                        <div class="shopping-card">
                                            <i class="flaticon-profile"></i>
                                        </div> 
                                    </a>
                                </div> -->
                            @endguest
                            <div class="up-item float-right">
                                <a href="#citas_modal" role="button" data-toggle="modal" target="#citas_modal">
                                    <div class="shopping-card">
                                        <i class="flaticon-calendar"></i>
                                    </div> AGENDAR CITA
                                </a>
                            </div>
                            <div class="up-item float-right">
                                <a href="{{ route('ecommerce.carrito') }}">
                                    <div class="shopping-card">
                                        <i class="flaticon-shopping-cart"></i>
                                        <span id="cantidad_articulos">0</span>
                                    </div> MI CARRITO
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="main-navbar">
            <div class="container col-12 d-flex justify-content-md-center justify-content-lg-center justify-content-xl-center" style="max-width: 100%!important">
                <ul class="main-menu">

                    @if($especificaciones_gafas_sol != "[]" && $marcas_gafas_sol != "[]")
                    <li><a href="{{route('ecommerce.categoria',$id_gafas_sol->id_tipo_producto)}}">Gafas de Sol</a>
                        <ul class="sub-menu" style="width: 400px;">
                            <li>
                                <div class="col-12" style="border:1px solid white;">
                                    <div class="row">
                                        @if($especificaciones_gafas_sol != "[]")
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 columna-categoria-marcas">
                                            <div class="row">
                                                <div class="col-12 titulo-categoria-marcas"><a><i class="icon-colours"> </i> CATEGORIAS</a></div>
                                                <div class="col-12 elementos-categoria-marcas">
                                                    <div class="row">
                                                    @foreach($especificaciones_gafas_sol as $especificacion_gafas_sol)
                                                        <div class="col-12 elemento-categoria-marca">
                                                            <a href="{{route('ecommerce.categoria_producto', ['id_tipo_producto' => $id_gafas_sol->id_tipo_producto, 'id_especificacion' => $especificacion_gafas_sol->id_especificacion])}}">
                                                                <i class="icon-dot-single"></i>{{$especificacion_gafas_sol->nombre_especificacion}}
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                        @endif
                                        @if($especificaciones_gafas_sol != "[]")
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 columna-categoria-marcas">
                                            <div class="row">
                                                <div class="col-12 titulo-categoria-marcas"><a><i class="icon-price-tag"> </i> MARCAS</a></div>
                                                <div class="col-12 elementos-categoria-marcas">
                                                    <div class="row">
                                                    @foreach($marcas_gafas_sol as $marca_gafas_sol)
                                                        <div class="col-12 elemento-categoria-marca"><a href="{{route('ecommerce.marca_producto', ['id_tipo_producto' => $id_gafas_sol->id_tipo_producto, 'id_marca' => $marca_gafas_sol->id_marca])}}"><i class="icon-dot-single"></i>{{$marca_gafas_sol->nombre_marca}}</a></div>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                        @endif
                                    </div>
                                </div>
                            </li>
                           
                        </ul>
                    </li>
                    @endif

                    @if($especificaciones_monturas != "[]" && $marcas_monturas != "[]")
                    <li><a href="{{route('ecommerce.categoria',$id_monturas->id_tipo_producto)}}">Monturas</a>
                        <ul class="sub-menu" style="width: 400px;">
                            <li>
                                <div class="col-12" style="border:1px solid white;">
                                    <div class="row">
                                        @if($especificaciones_monturas != "[]")
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 columna-categoria-marcas">
                                            <div class="row">
                                                <div class="col-12 titulo-categoria-marcas"><a><i class="icon-colours"> </i> CATEGORIAS</a></div>
                                                <div class="col-12 elementos-categoria-marcas">
                                                    <div class="row">
                                                    @foreach($especificaciones_monturas as $especificacion_montura)
                                                        <div class="col-12 elemento-categoria-marca">
                                                            <a href="{{route('ecommerce.categoria_producto', ['id_tipo_producto' => $id_monturas->id_tipo_producto, 'id_especificacion' => $especificacion_montura->id_especificacion])}}">
                                                                <i class="icon-dot-single"></i>{{$especificacion_montura->nombre_especificacion}}
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                        @endif
                                        @if($especificaciones_monturas != "[]")
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 columna-categoria-marcas">
                                            <div class="row">
                                                <div class="col-12 titulo-categoria-marcas"><a><i class="icon-price-tag"> </i> MARCAS</a></div>
                                                <div class="col-12 elementos-categoria-marcas">
                                                    <div class="row">
                                                    @foreach($marcas_monturas as $marca_montura)
                                                        <div class="col-12 elemento-categoria-marca"><a href="{{route('ecommerce.marca_producto', ['id_tipo_producto' => $id_monturas->id_tipo_producto, 'id_marca' => $marca_montura->id_marca])}}"><i class="icon-dot-single"></i>{{$marca_montura->nombre_marca}}</a></div>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                        @endif
                                    </div>
                                </div>
                            </li>
                           
                        </ul>
                    </li>
                    @endif

                    @if($servicios != "[]")
                    <li><a href="{{route('welcome')}}/#servicios-label" id="servicios-label">Servicios</a>
                        <ul class="sub-menu">
                            @foreach($servicios as $servicio)
                                <li><a href="{{route('servicio')}}/{{$servicio->id_servicio}}">{{$servicio->nombre_servicio}}</a></li>
                            @endforeach
                            <li><a href="{{route('quienes')}}">NOSOTROS</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($imagenes_kids != "[]")
                    <li><a href="{{route('welcome')}}/#kids">Sección Kids</a></li>
                    @endif

                    @if(isset($producto_membresias->id_tipo_producto_especial) && $membresias != "[]")
                    <li><a href="{{route('welcome')}}/#seccion-membresias">Membresías</a></li>
                    @endif

                    @if($promociones_pagina != "[]")
                    <li><a href="{{route('welcome')}}/#seccion-promociones">Promociones</a></li>
                    @endif

                    @guest
                    @else
                    <li><a href="{{route('pago')}}">Pago o abono PayU</a></li>
                    @endguest

                    <li><a href="{{route('ecommerce.categorias')}}">TIENDA ONLINE</a>
                        <ul class="sub-menu">
                            <li><a href="{{ route('ecommerce.categorias') }}">E-Commerce</a></li>
                            <li><a href="{{ route('ecommerce.carrito') }}">Articulos en Carrito</a></li>
                            <li><a href="{{ route('ecommerce.pagar') }}">Pagar Artículos</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>


    @yield('content')


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::BOTONES DERECHA:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


    <div id="content-iconos-redes">
        <div id="mySidenav" class="sidenav">
 
            @if($whatsapp != null)
                <a href="{{$whatsapp->acceso_red}}" target="blank" id="about">
                    <div style="width:25px;float:left;margin-right: 10px;"><img src="{{ asset('public/imagenes/Wblanco.png') }}" style="width:100%;"></div> Whatsapp
                </a>
            @endif
            @if($facebook != null)
                <a href="{{$facebook->acceso_red}}" target="blank" id="blog"><i class="icon-facebook"> </i>&nbsp;Facebook</a>
            @endif
            @if($instagram != null)
                <a href="{{$instagram->acceso_red}}" target="blank" id="projects"><i class="icon-instagram">&nbsp; </i>Instagram</a>
            @endif
            
            {{--<a id="contact" onclick="cerrarChat();"><i class="icon-chat"> </i>&nbsp;Chat</a>--}}
            <a href="{{ route('ecommerce.carrito') }}" id="car"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp; Carrito</a>
        </div>
    </div>


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::CHAT::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


    <div class="col-auto" id="seccion-chat">
        <div class="row position-relative">
            <div class="col-12 h-100 d-flex align-items-center justify-content-center" id="fondo-chat" style="background-image: url({{ asset('public/imagenes/sistema/cliente_empresa') }}/{{$cliente_appweb->nombre_imagen_cliente}});"></div>
            <div class="col-12 main-navbar" id="head-chat" onclick="abrirCerrarChat();">
                <div class="row h-100">
                    <div class="col-auto h-100 d-flex align-items-center contenedor-icono-estado-chat"><i class="icono-estado-chat icon-controller-record"></i></div>
                    <div class="col h-100 d-flex align-items-center nombre-usuario-chat">Asesor {{$cliente_appweb->titulo_pagina}}</div>
                    <div class="col-auto h-100 d-flex align-items-center" id="cerrar-chat"></div>
                </div>
            </div>
            <div class="col-12" id="cuerpo-chat">
                
                <div class="row h-100 align-items-center justify-content-center" id="espera-chat">
                    Por favor, espere...
                </div>
                <div class="row" id="formulario-correo">
                    <div class="col-12 mt-2" id="titulo-identificar">
                        Bienvenido a nuestro sistema de mensajería on line, escribe tu correo electrónico y nombre para identificarte y continuar. Respondemos tus dudas.
                    </div>
                    <div class="col-12">
                        <label class="col-12 mt-4 mb-0 label-identificar" id="label-correo" for="correo-chat">Correo Electrónico</label>
                        <input class="col-12 input input-chat" type="email" spellcheck="false" placeholder="Ingresa correo electrónico aquí." maxlength="100" id="correo-chat">
                    </div>
                    <div class="col-12">
                        <label class="col-12 mt-3 mb-0 label-identificar" id="label-nombre" for="nombre-chat">Nombre y Apellido</label>
                        <input class="col-12 input input-chat" type="email" spellcheck="false" placeholder="Ingresa nombre y apellido aquí." maxlength="100" id="nombre-chat">
                    </div>
                    <div class="col-12 mt-2">
                        <div class="col-auto d-flex justify-content-center main-navbar boton-chat" id="boton-correo">ENVIAR</div>
                    </div>
                </div>
                <div class="row pt-1" id="contenedor-chat">
                    <div class="col-12 mt-1 mb-1">
                        <div class="row ml-0 mr-0">
                            <div class="col-auto pt-2 pb-1 chat-receptor">Bienvenido a nuestro chat virtual, en qué podemos ayudarte?.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="contenedor-enviar">
                <div class="row row-contenedor-chat">
                    <textarea class="col input-chat" id="campo-mensaje" placeholder="Mensaje" maxlength="10000" onkeyup="validarEnter(event);"></textarea>
                    <div class="col-auto contenedor-boton-enviar">
                        <div class="main-navbar col-auto" id="boton-enviar" onclick="enviarMensaje(13);">
                            <i class="icon-paper-plane"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::FOOTER::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


    <section class="footer-section">
        @if($sucursales != "[]")
        <div class="col-12">
            <div class="row" id="seccion-sucursales">
                <div class="col-12">
                    <div class="section-title">
                        <p>SUCURSALES</p>
                    </div>
                </div>
                @foreach($sucursales as $sucursal)
                <div class="col-lg-6 col-sm-12">
                    <div class="footer-widget contact-widget">

                        <div class="footer-logo text-center">
                            <a href=""><img src="{{ asset('public/imagenes/sistema/sucursales') }}/{{$sucursal->nombre_imagen_sucursal}}" alt="" style="height: 150px;"></a>
                        </div>

                        <h2>Sucursal {{$sucursal->nombre_sucursal}}</h2>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="con-info">
                                    <span>Correo Electrónico</span><br>
                                    <p>{{$sucursal->correo_sucursal}}</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="con-info">
                                    <span>Telefono.</span><br>
                                    <p>{{$sucursal->telefono_sucursal}}</p>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12">
                                <div class="con-info">
                                    <span>Direccion.</span><br>
                                    <p>{{$sucursal->direccion_sucursal}}</p>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12">
                                {!! $sucursal->mapa_sucursal !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="footer-widget about-widget">
                   {{-- <h2>Oración de Fé</h2>
                    <p style="color:#2A3D6A;"><b>Señor Jesús</b>. Reconozco que soy pecador, te pido me perdones y que entres en mi corazón, te acepto como mi señor y salvador. Lléname con tu espíritu santo, gracias por la vida eterna que hoy me das. En el nombre de Jesús amén.</p>--}}
                </div>
            </div>
        </div>
        @endif
        <div class="col-12 seccion-footer" style="padding-top: 40px;">
            <div class="row">
                <div class="col-12 col-lg-6 col-sm-6" style="padding-left: 60px;">
                    <div class="footer-widget about-widget">
                        <h2 style="color:#1C2A49!important; font-size: 25px!important;">&nbsp;&nbsp;&nbsp;&nbsp;Acerca de Nosotros</h2>
                        <ul>
                            <li><a href="{{ route('quienes') }}">Quiénes Somos</a></li>
                            {{--<li><a href="#servicios-label">Servicios</a></li>--}}
                            {{--<li><a href="#consulta_compra" role="button" data-toggle="modal" target="#consulta_compra">Consultar Compra</a></li>--}}
                            <li><a href="{{ route('ecommerce.carrito') }}">Carrito de compras</a></li>
                            <li><a href="#citas_modal" role="button" data-toggle="modal" target="#citas_modal">Agendar Cita</a></li>
                            @if($imagenes_kids != "[]")
                            <li><a href="{{route('welcome')}}/#kids">Sección Kids</a></li>
                            @endif
                            @if($promociones_pagina != "[]")
                            <li><a href="{{route('welcome')}}/#seccion-promociones">Promociones</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-sm-6" style="padding-left: 60px;">
                    <div class="footer-widget about-widget">
                        <h2 style="color:#1C2A49!important; font-size: 25px!important;">&nbsp;&nbsp;&nbsp;&nbsp;Servicio al Cliente</h2>
                        <ul>
                            @foreach($servicios as $servicio)
                            <li><a href="{{ route('servicio') }}/{{$servicio->id_servicio}}">{{$servicio->nombre_servicio}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="social-links-warp seccion-footer" style="padding-bottom: 70px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 text-center">
                        <img src="{{ asset('public/imagenes/payu2x.png') }}" style="height: 60px;">
                        <br>
                        <p style="color:#2A3D6A!important;">Pagos OnLine</p>
                    </div>
                    <div class="col-lg-3 col-sm-6 text-center">
                        <div class="social-links">
                            <a href="" class="facebook"><i class="fa fa-facebook" style="font-size: 50px;color:#2A3D6A;"></i></a>
                        </div>
                        <p style="color:#2A3D6A!important;">facebook</p>
                    </div>
                    <div class="col-lg-3 col-sm-6 text-center">
                        <img src="{{ asset('public/imagenes/sistema/cliente_empresa') }}/{{$cliente_appweb->nombre_imagen_cliente}}" style="height: 60px;">
                        <br>
                        <p style="color:#2A3D6A!important;">{{$cliente_appweb->nombre_cliente_sas}}</p>
                    </div>
                    
                    <div class="col-lg-3 col-sm-6 text-center">
                        <a href="http://www.appwebca.com" target="_blank">
                            <img src="{{ asset('public/imagenes/blanco-gris.png') }}" style="height: 60px;">
                            <br>
                            <p style="color:#2A3D6A!important;">Empresa de desarrollo</p>
                        </a>
                    </div>
                </div>

<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --> 
<p class="text-white text-center mt-5" style="color: rgba(150,150,150,0.4)!important;">Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" style="color: rgba(150,150,150,0.4)!important;">Colorlib</a></p>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </div>
        </div>
    </section>


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::MODAL AGENDAR CITAS:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


    <div class="modal fade" id="citas_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div id="opacar-fondo-modal-citas">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> <i class="fa fa-calendar"> </i> &nbsp; Agendar Cita</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="mensaje-error" hidden>
                        </div>
                        <h4>Información Paciente</h4>
                        <div class="content-input">
                            <label for="sucursal" class="label" id="label-sucursal">Sucursal</label>
                            <select class="input" name="sucursal" id="sucursal" required>
                                <option value="">Seleccione Sucursal</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{$sucursal->id_sucursal}}">{{$sucursal->nombre_sucursal}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="content-input">
                            <label for="tipo_identificacion" class="label" id="label-tipo-identificacion">Tipo de Identificación</label>
                            <select class="input" name="tipo_identificacion" id="tipo_identificacion" required>
                                <option value="">Seleccione</option>
                                @foreach($tipos_identificacion as $tipo_identificacion)
                                    <option value={{$tipo_identificacion->id_tipo_identificacion}}>{{$tipo_identificacion->nombre_tipo_identificacion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="content-input">
                            <label for="identificacion" class="label" id="label-identificacion">Identificación</label>
                            <input type=number class="input input-citas" name="identificacion" id="identificacion" required placeholder="N° de Documento">
                        </div>
                        <div class="content-input">
                            <label for="nombres_paciente" class="label" id="label-nombres-paciente">Nombres</label>
                            <input type="text" class="input input-citas" name="nombres_paciente" id="nombres_paciente" required maxlength="50" required placeholder="Nombres Paciente">
                        </div>
                        <div class="content-input">
                            <label for="apellidos_paciente" class="label" id="label-apellidos-paciente">Apellidos</label>
                            <input type="text" class="input input-citas" name="apellidos_paciente" id="apellidos_paciente" required maxlength="50" required placeholder="Apellidos Paciente">
                        </div>
                        <div class="content-input">
                            <label for="telefono_paciente" class="label" id="label-telefono-paciente">Teléfono</label>
                            <input type="tel" class="input input-citas" name="telefono_paciente" id="telefono_paciente" maxlength="40" required placeholder="Teléfono">
                        </div>
                        <div class="content-input">
                            <label for="correo_paciente" class="label" id="label-correo-paciente">Correo Electrónico</label>
                            <input type="email" class="input input-citas" name="correo_paciente" id="correo_paciente" placeholder="Correo Electronico" maxlength="40" required>
                        </div>
                        <div class="content-input">
                            <label for="fecha_nacimiento" class="label" id="label-fecha-nacimiento">Fecha Nacimiénto</label>
                            <input type="date" class="input input-citas" id="fecha_nacimiento" name="fecha_nacimiento" id="fecha_nacimiento" type="date" required>
                        </div>
                        <div class="content-input">
                            <label for="" class="label" id="label-genero">Género</label>
                            <select class="input" name="genero" id="genero" required>
                                <option value="">Seleccione</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Masculino">Masculino</option>
                            </select>
                        </div>
                        <br>
                        <div class="content-input-50">
                            <label for="direccion_paciente" class="label" id="label-direccion-paciente">Dirección</label>
                            <textarea class="input input-citas" name="direccion_paciente" id="direccion_paciente" placeholder="Direccion" style="height: 60px;"></textarea>
                        </div>
                        <br><h4>Fecha de Cita</h4>
                        <div class="content-input">
                            <label for="fecha_cita" class="label" id="label-fecha-cita">Fecha</label>
                            <input type="date" class="input input-citas" id="fecha_cita" name="fecha_cita" id="fecha_cita" type="date" required>
                        </div>
                        <div class="content-input">
                            <label for="hora_cita" class="label" id="label-hora-cita">Hora</label>
                            <input type="time" class="input input-citas" id="hora_cita" name="hora_cita" id="hora_cita" type="date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="boton-admin site-btn sb-dark" id="crear-cita">Agendar Cita</button>
                    </div>

                </div>

                    <div class="col-12 mb-2"><a href="{{route('terminos_condiciones', ['termino' => 'Citas'])}}" target="_blank" style="font-size:14px;">Términos y condiciones.</a></div>
            </div>
        </div>
    </div>


<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::MODAL EXPLICAR CONSULTA COMPRA:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


     <div class="modal fade" id="consulta_compra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div id="opacar-fondo-modal-citas">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> <i class="fa fa-search"> </i> &nbsp; Consultar Estatus de lentes</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="height: 70vh;">

                        <br><p style="color: rgba(60,60,60,1);font-size: 2.5vh;font-weight: bold;line-height: 2.5vh;">{{$cliente_appweb->nombre_cliente_sas}} tenemos a disposición la consulta del estatus de tus gafas para que sepas si ya estan listas, escanéa el código QR ubicado en el recibo de compra de tus gafas y te diremos si ya puedes retirarlas o solicitar envío.</p><br>
                        <div class="col-12" style="text-align: center;color: rgba(60,60,60,1);">
                            <i class="fa fa-qrcode" style="font-size: 20vh;"></i><br>
                            <p style="color: rgba(60,60,60,1);font-size: 5vh;font-weight: bold;">Escanéa el QR</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--====== Javascripts & Jquery ======-->
    <script src="{{ asset('public/ecommerce/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('public/ecommerce/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/ecommerce/js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('public/ecommerce/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/ecommerce/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('public/ecommerce/js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('public/ecommerce/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/ecommerce/js/main.js') }}"></script>
    <script src="{{ asset('public/js/sweetalert.min.js') }}"></script>

    <script>
        crear_cita = true;
        $("#crear-cita").click(function(){
            var valid = true;

            var v_sucursal = document.getElementById("sucursal").value;
            var v_tipo_identificacion = document.getElementById("tipo_identificacion").value;
            var v_identificacion = document.getElementById("identificacion").value;
            var v_nombres = document.getElementById("nombres_paciente").value;
            var v_apellidos = document.getElementById("apellidos_paciente").value;
            var v_telefono = document.getElementById("telefono_paciente").value;
            var v_correo = document.getElementById("correo_paciente").value;
            var v_fecha_nacimiento = document.getElementById("fecha_nacimiento").value;
            var v_genero = document.getElementById("genero").value;
            var v_direccion = document.getElementById("direccion_paciente").value;
            var v_fecha_cita = document.getElementById("fecha_cita").value;
            var v_hora_cita = document.getElementById("hora_cita").value;

            if(v_sucursal == ""){
                valid = false;
                document.getElementById("label-sucursal").style.color = "red";
                document.getElementById("label-sucursal").innerHTML = "Seleccione Sucursal";
            }else{
                document.getElementById("label-sucursal").style.color = "black";
                document.getElementById("label-sucursal").innerHTML = "Sucursal";
            }

            if(v_tipo_identificacion == ""){
                valid = false;
                document.getElementById("label-tipo-identificacion").style.color = "red";
                document.getElementById("label-tipo-identificacion").innerHTML = "Seleccione Tipo Identificación";
            }else{
                document.getElementById("label-tipo-identificacion").style.color = "black";
                document.getElementById("label-tipo-identificacion").innerHTML = "Tipo de Identificación";
            }

            if(v_identificacion == ""){
                valid = false;
                document.getElementById("label-identificacion").style.color = "red";
                document.getElementById("label-identificacion").innerHTML = "Ingrese Identificación";
            }else{
                document.getElementById("label-identificacion").style.color = "black";
                 document.getElementById("label-identificacion").innerHTML = "Identificación";
            }

            if(v_nombres == ""){
                valid = false;
                document.getElementById("label-nombres-paciente").style.color = "red";
                document.getElementById("label-nombres-paciente").innerHTML = "Ingrese Nombres";
            }else{
                document.getElementById("label-nombres-paciente").style.color = "black";
                document.getElementById("label-nombres-paciente").innerHTML = "Nombres";
            }

            if(v_apellidos == ""){
                valid = false;
                document.getElementById("label-apellidos-paciente").style.color = "red";
                document.getElementById("label-apellidos-paciente").innerHTML = "Ingrese Apellidos";
            }else{
                document.getElementById("label-apellidos-paciente").style.color = "black";
                document.getElementById("label-apellidos-paciente").innerHTML = "Apellidos";
            }

            if(v_telefono == ""){
                valid = false;
                document.getElementById("label-telefono-paciente").style.color = "red";
                document.getElementById("label-telefono-paciente").innerHTML = "Ingrese Teléfono";
            }else{
                document.getElementById("label-telefono-paciente").style.color = "black";
                document.getElementById("label-telefono-paciente").innerHTML = "Teléfono";
            }

            if(v_correo == ""){
                valid = false;
                document.getElementById("label-correo-paciente").style.color = "red";
                document.getElementById("label-correo-paciente").innerHTML = "Ingrese Correo";
            }else{
                document.getElementById("label-correo-paciente").style.color = "black";
                document.getElementById("label-correo-paciente").innerHTML = "Correo Electrónico";
            }

            if(v_fecha_nacimiento == ""){
                valid = false;
                document.getElementById("label-fecha-nacimiento").style.color = "red";
                document.getElementById("label-fecha-nacimiento").innerHTML = "Ingrese fecha nacimiénto";
            }else{
                document.getElementById("label-fecha-nacimiento").style.color = "black";
                document.getElementById("label-fecha-nacimiento").innerHTML = "Fecha Nacimiénto";
            }

            if(v_genero == ""){
                valid = false;
                document.getElementById("label-genero").style.color = "red";
                document.getElementById("label-genero").innerHTML = "Seleccione Género";
            }else{
                document.getElementById("label-genero").style.color = "black";
                document.getElementById("label-genero").innerHTML = "Género";
            }

            if(v_direccion == ""){
                valid = false;
                document.getElementById("label-direccion-paciente").style.color = "red";
                document.getElementById("label-direccion-paciente").innerHTML = "Ingrese Dirección";
            }else{
                document.getElementById("label-direccion-paciente").style.color = "black";
                document.getElementById("label-direccion-paciente").innerHTML = "Dirección";
            }

            if(v_fecha_cita == ""){
                valid = false;
                document.getElementById("label-fecha-cita").style.color = "red";
                document.getElementById("label-fecha-cita").innerHTML = "Seleccione Fecha";
            }else{
                document.getElementById("label-fecha-cita").style.color = "black";
                document.getElementById("label-fecha-cita").innerHTML = "Fecha";
            }

            if(v_hora_cita == ""){
                valid = false;
                document.getElementById("label-hora-cita").style.color = "red";
                document.getElementById("label-hora-cita").innerHTML = "Seleccione Hora";
            }else{
                document.getElementById("label-hora-cita").style.color = "black";
                document.getElementById("label-hora-cita").innerHTML = "Hora";
            }
            if(valid == true && crear_cita == true){
                crear_cita = false;
                var url="{{route('citas.crear.externa')}}";
                var datos = {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "sucursal": v_sucursal,
                    "nombres_paciente": v_nombres,
                    "apellidos_paciente": v_apellidos,
                    "tipo_identificacion": v_tipo_identificacion,
                    "identificacion": v_identificacion,
                    "telefono_paciente": v_telefono,
                    "direccion_paciente": v_direccion,
                    "correo_paciente": v_correo,
                    "genero": v_genero,
                    "fecha_nacimiento": v_fecha_nacimiento,
                    "fecha_cita": v_fecha_cita,
                    "hora_cita": v_hora_cita
                };
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: datos,
                    success: function(data) {
                        crear_cita = true;
                        console.log("success");
                        console.log(data);
                        if (data == "exito") {
                            swal({
                                title: "Cita registrada",
                                text: "La cita ha sido registrada el "+v_fecha_cita+" "+v_hora_cita+":00",
                                icon: "success",
                                button: "Ok",
                            });
                            var input_citas = document.getElementsByClassName("input-citas");
                            for(var i=0; i<input_citas.length; i++){
                                input_citas[i].value = "";
                            }
                            var close = document.getElementsByClassName("close");
                            close[0].click();
                        }else{
                            swal({
                                title: "Cita No registrada",
                                text: "Seleccione una fecha y hora dentro del horario de atención",
                                icon: "error",
                                button: "Ok",
                            });
                            var close = document.getElementsByClassName("close");
                            close[0].click();
                        }
                    },
                    error: function(data) {
                        crear_cita = true;
                        swal({
                            title: "Cita No registrada",
                            text: "Ocurrio un error interno del servidor",
                            icon: "error",
                            button: "Ok",
                        });
                        console.log("error");
                    }
                });
            }
            
        });

/*:::::::::::::::::::AJUSTAR EL TAMAÑO DE LOS MAPAS::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

        var iframes = document.getElementsByTagName("iframe");
        for(var i=0; i<iframes.length; i++){
            iframes[i].width = "100%";
            iframes[i].height = "100%";
            iframes[i].frameborder = "0";
            iframes[i].allowfullscreen = "";
        }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::CARRITO DE COMPRAS:::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::SE OBTIENE LO QUE HAY EN CARRITO:::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


        carrito = localStorage.getItem('carrito');


/*:::::::::::::::MOSTRAR LO QUE HAY EN EL CARRITO:::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


        function mostrarCarrito(){

            if((carrito == null) || (carrito == "") || (carrito == []) || (carrito == "[]")){

            }else{
                var total_precio = 0;
                productos_carrito = JSON.parse(carrito);
                $('#productos_carrito').empty();
                for(i = 0; i < productos_carrito.length; i++){
                    if(productos_carrito[i].cantidad > 0){
                        $('#productos_carrito').append(`
                            <tr>
                                <td class="product-col">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-auto">
                                                <img src="../public/imagenes/sistema/productos/`+productos_carrito[i].imagen+`">
                                            </div>
                                            <div class="col">
                                                <div class="pc-title">
                                                    <h4>`+productos_carrito[i].nombre+`</h4>
                                                    <p>$ `+productos_carrito[i].precio+`</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="quy-col">
                                    <div class="quantity">
                                        <div class="pro-qty">
                                            <span class="dec qtybtn" onclick="restar(`+productos_carrito[i].id+`)">-</span>
                                            <input type="text" id="cantidad-`+productos_carrito[i].id+`" value="`+productos_carrito[i].cantidad+`" onkeyup="actualizarCantidad(this.value,`+productos_carrito[i].id+`)">
                                            <span class="inc qtybtn" onclick="sumar(`+productos_carrito[i].id+`)">+</span>
                                        </div>
                                    </div>
                                </td>
                                {{--<td class="size-col"><h4>Size M</h4></td>--}}
                                <td class="total-col"><h4>$ `+productos_carrito[i].precio+`</h4></td>
                            </tr>
                        `);
                        total_precio = total_precio + (productos_carrito[i].precio * productos_carrito[i].cantidad);
                    }
                }
                document.getElementById('total_precio').innerHTML="$ "+total_precio;
            }
            cantidadArticulos();
        }


/*:::::::::::::::EJECUTAR MOSTRAR CARRITO SEGUN LA PAG::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


        urlactual = location.pathname;
        if((urlactual.indexOf("ecommerce/carrito") != -1 ) || (urlactual.indexOf("ecommerce/pagar") != -1 )){
            mostrarCarrito();
        }


/*:::::::::::::::::AGREGAR UN PRODUCTO AL CARRITO:::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


        function agregarCarrito(id_producto,cantidad_producto,nombre_producto,imagen_producto,precio_producto){

            if((carrito == null) || (carrito == "") || (carrito == []) || (carrito == "[]")){
                carrito = [];
                var producto = {
                    'id': id_producto,
                    'cantidad': cantidad_producto,
                    'nombre': nombre_producto,
                    'imagen': imagen_producto,
                    'precio': precio_producto
                }
                carrito.push(producto);
                carrito = JSON.stringify(carrito);
                localStorage.setItem('carrito', carrito);

            }else{
                carrito = JSON.parse(carrito);
                var encontrado = false;
                for(i = 0; i < carrito.length; i++){
                    if(carrito[i].id == id_producto){
                        carrito[i].cantidad = carrito[i].cantidad + cantidad_producto;
                        encontrado = true;
                    }
                }
                if(encontrado == false){
                    var producto = {
                        'id': id_producto,
                        'cantidad': cantidad_producto,
                        'nombre': nombre_producto,
                        'imagen': imagen_producto,
                        'precio': precio_producto
                    }
                    carrito.push(producto);
                }
                carrito = JSON.stringify(carrito);
                localStorage.setItem('carrito', carrito);
            }
            if((urlactual=="/admin/ecommerce/carrito")||(urlactual=="/ecommerce/carrito")||(urlactual=="/admin/ecommerce/pagar")||(urlactual=="/ecommerce/pagar")){
                mostrarCarrito();
            }
            cantidadArticulos();
        }


/*:::::::::::::::ACTUALIZAR PRECIO CANTIDAD TOTAL:::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


        function actualizarCantidad(cantidad,id_producto){
            if(cantidad==null || cantidad==""){
                cantidad=0;
            }
            productos_carrito = JSON.parse(carrito);
            var total_precio = 0;
            for(i = 0; i < productos_carrito.length; i++){
                if(productos_carrito[i].id == id_producto){
                    productos_carrito[i].cantidad = parseInt(cantidad);
                }
                total_precio = total_precio + (productos_carrito[i].precio * productos_carrito[i].cantidad);
            }
            document.getElementById('total_precio').innerHTML="$ "+total_precio;
            carrito = JSON.stringify(productos_carrito);
            productos_carrito = JSON.stringify(productos_carrito);
            localStorage.setItem('carrito', productos_carrito);
            cantidadArticulos();
        }


/*:::::::::::::::::MOSTRAR LA CANTIDAD EN CARRITO:::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


        function cantidadArticulos(){

            articulos = localStorage.getItem('carrito');
            articulos = JSON.parse(articulos);
            var cantidad = 0;
            if ((articulos == null) || (articulos == "")) {
                document.getElementById("cantidad_articulos").innerHTML="0";
            }else{
                for(i = 0; i < articulos.length; i++){
                    cantidad = cantidad + articulos[i].cantidad;
                }
                document.getElementById("cantidad_articulos").innerHTML=cantidad;
            }
        }
        cantidadArticulos();


/*:::::::::::::::::::::::::VACIAR EL CARRITO::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


        function vaciarCarrito(){
            localStorage.removeItem('carrito');
            mostrarCarrito();
            descripciones = [];
            $('#productos_carrito').empty();
            document.getElementById('total_precio').innerHTML="$ 0";
            cantidadArticulos();
        }

        function PruebaVaciar(){
          console.log('prueba')
        }

/*:::::::::::::::::::::::::SUMAR UN PRODUCTO::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


        function sumar(id){
            var cant = parseInt(document.getElementById('cantidad-'+id).value) + 1;
            document.getElementById('cantidad-'+id).value = cant;
            actualizarCantidad(cant,id);
        }


/*:::::::::::::::::::::::::RESTAR UN PRODUCTO:::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


        function restar(id){
            var cant = parseInt(document.getElementById('cantidad-'+id).value) - 1;
            if(cant >= 0){
                document.getElementById('cantidad-'+id).value = cant;
                actualizarCantidad(cant,id);
            }
        }




    /*:::::::::::::::::CHATS:::::::::::::::*/
    id_correo_chat = sessionStorage.getItem('correo_chat');
    console.log(id_correo_chat);

    function mostrarChat(id_correo_chat){
        if(id_correo_chat != null){
            $('#formulario-correo').css("display", "none");
            $('#espera-chat').css("display", "flex");
            var url="{{route('chat.mostrar')}}";
            var datos = {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "id_correo_chat": id_correo_chat
            };
            $.ajax({
                type: 'POST',
                url: url,
                data: datos,
                dataType: "json",
                success: function(data){
                    console.log("success");
                    for(var i=0; i<data.length; i++){
                        if(data[i].tipo_usuario_chat == "cliente"){
                            $('#contenedor-chat').append(`
                                <div class="col-12 mt-1 mb-1">
                                    <div class="row ml-0 mr-0 d-flex justify-content-end">
                                        <div class="col-auto pt-2 pb-1 chat-emisor main-navbar">`+data[i].chat+`<br><x class="fecha-emisor">`+data[i].fecha_chat+`</x></div>
                                    </div>
                                </div>
                            `);
                        }else{
                            $('#contenedor-chat').append(`
                                <div class="col-12 mt-1 mb-1">
                                    <div class="row ml-0 mr-0">
                                        <div class="col-auto pt-2 pb-1 chat-receptor">`+data[i].chat+`<br><x class="fecha-receptor">`+data[i].fecha_chat+`</x></div>
                                    </div>
                                </div>
                            `);
                        }
                    }
                    $("#cuerpo-chat").animate({ scrollTop: $("#cuerpo-chat").height() }, 50);
                    $('#espera-chat').css("display", "none");
                    $('#contenedor-chat').css("display", "flex");
                    $('#contenedor-enviar').css("display", "block");
                },
                error: function(data){
                    console.log("error");
                }
            });
        }
    }
    mostrarChat(id_correo_chat);

    exprN = /^[a-zA-Z0-9À-ÿ\.\#-\s]+$/;
    exprC = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
    validando_chat = false;

    $("#boton-correo").click(function(){

        $('#label-nombre').css("color", "#212529");
        $('#label-nombre').html("Nombre y apellido");
        $('#label-correo').css("color", "#212529");
        $('#label-correo').html("Correo electrónico");

        var valid = true;
        var nombreChat = $("#nombre-chat").val();
        var correoChat = $("#correo-chat").val();
        console.log("el nombre escrito es: "+nombreChat);
        console.log("el correo escrito es: "+correoChat);

        if(nombreChat == "" || !exprN.test(nombreChat) || nombreChat.length < 3){
            $('#label-nombre').css("color", "red");
            $('#label-nombre').html("Nombre y apellido NO válido");
            valid=false;
        }
        if(correoChat == "" || !exprC.test(correoChat) || correoChat.length < 10){
            $('#label-correo').css("color", "red");
            $('#label-correo').html("Correo electrónico NO válido");
            valid = false;
        }

        if(valid == true && validando_chat == false){
            validando_chat = true;
            $('#formulario-correo').css("display", "none");
            $('#espera-chat').css("display", "flex");
            var url="{{route('chat.verificar.correo')}}";
            var datos = {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "nombre": nombreChat,
                "correo": correoChat
            };
            $.ajax({
                type: 'POST',
                url: url,
                data: datos,
                dataType: "json",
                success: function(data){
                    console.log("success");
                    for(var i=0; i<data.chats.length; i++){
                        $('#contenedor-chat').append(`
                            <div class="col-12 mt-1 mb-1">
                                <div class="row ml-0 mr-0 d-flex justify-content-end">
                                    <div class="col-auto pt-2 pb-1 chat-emisor main-navbar">`+data.chats[i].chat+`<br><x class="fecha-emisor">`+data.chats[i].fecha_chat+`</x></div>
                                </div>
                            </div>
                        `);
                    }
                    $("#cuerpo-chat").animate({ scrollTop: $("#cuerpo-chat").height() }, 50);
                    sessionStorage.setItem('correo_chat',data.id_correo_chat);
                    id_correo_chat = data.id_correo_chat;
                    $('#espera-chat').css("display", "none");
                    $('#contenedor-chat').css("display", "flex");
                    $('#contenedor-enviar').css("display", "block");
                    validando_chat = false;
                },
                error: function(data){
                    console.log("error");
                    validando_chat = false;
                }
            });
        }
        return valid;
    });


    function validarEnter(e){
        var tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 13) {
            enviarMensaje(tecla);
        }
    }

    function enviarMensaje(tecla){
        var mensaje_enviar = $("#campo-mensaje").val();
        mensaje_enviar.trim();
        if(tecla == 13 && mensaje_enviar.length > 1){
            if(mensaje_enviar != "" && mensaje_enviar != " " && mensaje_enviar != "  " && mensaje_enviar != "   "){
                $("#campo-mensaje").val("");
                $("#campo-mensaje").attr("placeholder", "Enviando mensaje...");
                var url="{{route('chat.enviar')}}";
                var datos = {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "id_correo_chat": id_correo_chat,
                    "chat": mensaje_enviar
                };
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: datos,
                    dataType: "json",
                    success: function(data){
                        console.log("success");
                        $("#campo-mensaje").attr("placeholder", "Mensaje");
                        if(data != null && data != ""){
                            $('#contenedor-chat').append(`
                                <div class="col-12 mt-1 mb-1">
                                    <div class="row ml-0 mr-0 d-flex justify-content-end">
                                        <div class="col-auto pt-2 pb-1 chat-emisor main-navbar">`+mensaje_enviar+`<br><x class="fecha-emisor">`+data.fecha_chat+`</x></div>
                                    </div>
                                </div>
                            `);
                        }
                        $("#cuerpo-chat").animate({ scrollTop: $("#cuerpo-chat").height() }, 50);
                    },
                    error: function(data){
                        console.log("error");
                        $("#campo-mensaje").attr("placeholder", "Ocurrió un error al enviar el mensaje.");
                    }
                });
            }
        }else{
            $("#campo-mensaje").val("");
            console.log("limpio un enter vacio");
        }
    }

    estado_chat = "cerrado";
    function abrirCerrarChat(){
        $('#cerrar-chat').empty();
        if(estado_chat == "abierto"){
            estado_chat = "cerrado";
            $('#cerrar-chat').append(`<i class="icon-align-bottom"></i>`);
            $('#seccion-chat').css("height", "500px");
        }else{
            estado_chat = "abierto";
            $('#cerrar-chat').append(`<i class="icon-align-top"></i>`);
            $('#seccion-chat').css("height", "30px");
        }
    }
    abrirCerrarChat();



    /*:::::::::::::::::::::::::::::::::COOKIES::::::::::::::::::::::::::::::::::*/
    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


        aceptar_cookies = localStorage.getItem('cookies');
        console.log(aceptar_cookies);

        if((aceptar_cookies == null) || (aceptar_cookies == "")){
            document.getElementById("cookies").style.display = "block";
        }else{
            if(aceptar_cookies == "aceptadas"){
                document.getElementById("cookies").style.display = "none";
            }else{
                document.getElementById("cookies").style.display = "block";
            }
        }

        function seleccionarCookies(opcion_cookies){
            localStorage.setItem('cookies',opcion_cookies);
            $("#cookies").fadeOut();
        }

        titulo = "{{$cliente_appweb->titulo_pagina}}";

    </script>
    <style type="text/css">
        @foreach($colores_pagina as $color_pagina)
            {{$color_pagina->elemento_pagina}} {
                {{$color_pagina->estilo_pagina}}: {{$color_pagina->color_pagina}}!important;
            }
        @endforeach
    </style>

</body>
</html>
