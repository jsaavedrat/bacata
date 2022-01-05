@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-price-ribbon"> </i> MEMBRESÍAS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; LISTA</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>
<link rel="stylesheet" href="{{ asset('public/ecommerce/css/bootstrap.min.css') }}"/>
<script src="{{ asset('public/ecommerce/js/bootstrap.min.js') }}"></script>
<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('pagina.membresias.crear') }}"><i class="fa fa-plus herramientas"></i><div class="content-texto"><p class="texto-icono">CREAR MEMBRESÍA</p></div></a></div>
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
    <div id="texto-titulo"> <i class="icon-list"> </i> LISTA DE MEMBRESIAS EN PÁGINA WEB</div>

    <div id="content-mensaje">
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Membresía creada con Éxito.</div>@endif
        @if($estatus=="eliminado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Membresía eliminada con Éxito.</div>@endif
        @if($estatus=="actualizado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Membresía Actualizada con Éxito.</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; No se pudo eliminar la Membresía.</div>@endif
    </div>
        
    <div class="col-12">
        <div class="row">
            @if(isset($producto_membresias->id_tipo_producto_especial))
                <section class="product-filter-section">
                    <div class="container">
                        <div class="row">
                            @foreach($membresias as $membresia)

                                <div class="col-lg-4 col-sm-12 h-full">
                                    <div class="col-lg-12 col-sm-12 col-membresia">
                                        <div class="col-12">
                                            <h4 class="text-center"><b>{{$membresia->nombre_modelo}}</b></h4>
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
                                                <h3 class="p-price"><b>${{number_format($membresia->precio_base, 0, ',', '.')}}</b></h3>
                                            </div>
                                            <div class="col-12">
                                                <div class="row justify-content-center">
                                                    <div class="col-auto boton-membresia" onclick="editar('{{$membresia->id_producto}}');">Editar</div>
                                                    <div class="col-auto boton-membresia ml-2" onclick="eliminar('{{$membresia->id_producto}}','{{$membresia->nombre_modelo}}')">Eliminar</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
</div>

<div id="modal-eliminar">
    <form method="post" action="{{ route('pagina.membresias.inactivar') }}">
        @csrf
        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">¿Realmente deseas eliminar la Membresía: <x id="nombre-eliminar"></x></div>
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

<form method="post" action="{{ route('pagina.membresias.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>
<style type="text/css">
    .col-membresia {
        border:1px solid rgba(50,50,50,0.7);
        padding: 20px;
        margin-bottom: 20px;
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
    .boton-membresia {
        height: 30px;
        font-size: 15px;
        color: white;
        width: 100px;
        text-align: center;
        background-color: rgba(50,50,50,1);
        text-transform: uppercase;
        line-height: 30px;
        cursor: pointer;
    }

    .li-membresia{
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>

@endsection




