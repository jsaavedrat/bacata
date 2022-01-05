@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-institution"> </i> SUCURSALES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; VER</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('sucursales.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE SUCURSALES</p></div></a></div>
    <div class="icono-titulo"><i class="fa fa-question herramientas">                                   </i><div class="content-texto"><p class="texto-icono">AYUDA</p></div></div>
    <div class="icono-titulo"><i class="icon-chat herramientas">                                        </i><div class="content-texto"><p class="texto-icono">CHAT</p></div></div>
    <div class="icono-titulo"><i class="icon-bell herramientas">                                        </i><div class="content-texto"><p class="texto-icono">NOTIFICACIONES</p></div></div>
    <div class="icono-titulo"><i class="fa fa-user herramientas">                                       </i><div class="content-texto"><p class="texto-icono">MI PERFIL</p></div></div>
    <div class="icono-titulo" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-power-off herramientas"></i><div class="content-texto"><p class="texto-icono">CERRAR SESIÓN</p></div></div>

    <div id="content-logout">
        <div id="nombre-user"> {{ Auth::user()->name }} <i class="icon-chevron-down"> </i></div>
        <div id="content-opciones-user">
            <div class="opcion-user"> <i class="icon-key"> </i> CONTRASEÑA </div>
            <div class="opcion-user"> <i class="fa fa-user"> &nbsp;</i> PERFIL </div>
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

    <div id="nombre-detalle">
        {{$sucursal->nombre_sucursal}}
        <img src="{{ asset('public/imagenes/sistema/sucursales') }}/{{$sucursal->nombre_imagen_sucursal}}" style="height:80px;display: block;">
    </div>
    <div id="detalle-elementos">
        <b>Dirección: </b> {{$sucursal->direccion_sucursal}}<br>
        <b>Teléfono: </b> {{$sucursal->telefono_sucursal}}<br>
        <b>Cantidad de Productos: </b> {{$sucursal->total}}<br>
        <b>Productos Diferentes: </b> {{$sucursal->cantidad}}<br>
    </div>

    @foreach($disponibles as $disponible)
    <div class="content-modulo" onclick="accion('{{$sucursal->id_sucursal}}','{{$disponible->url_lista}}');" style="cursor: pointer;">
        <div class="caja-modulo">
            <div class="content-icono">
                <i class="icono-modulo-optica {{$disponible->icono}}"></i>
            </div>
            <div class="content-textos">
                <div class="titulo-modulo">{{$disponible->nombre_modulo}}</div>
                <div class="cantidades-modulo">{{$disponible->cantidad_modulo}}</div>
                <div class="opciones-modulo">
                    <a class="icono-url"><i class="icon-list"></i></a>
                </div>
            </div>
        </div>
    </div>
    @endforeach


</div>

<form method="post" action="" id="form-accion">
    @csrf
    <input type="number" name="ver" id="ver" style="display: none;">
    <button id="boton-ver"></button>
</form>



<script type="text/javascript">

    function accion(id,ruta){
        document.getElementById("form-accion").action = ruta;
        ver(id);
    }

    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Ingresos Sucursal {{$sucursal->nombre_sucursal}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Ingresos Sucursal {{$sucursal->nombre_sucursal}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Ingresos Sucursal {{$sucursal->nombre_sucursal}}',
                    filename: 'Ingresos Sucursal {{$sucursal->nombre_sucursal}}',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Ingresos Sucursal {{$sucursal->nombre_sucursal}}',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Bodegas&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ ingreso",
                info:           "Mostrando ingresos del _START_ al _END_ de un total de _TOTAL_ ingresos",
                infoEmpty:      "Mostrando ingresos del 0 al 0 de un total de 0 ingresos",
                infoFiltered:   "(filtrado de un total de _MAX_ ingresos)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron ingresos",
                emptyTable:     "Ningún ingreso disponible en esta tabla",
                paginate: {
                    first:      "Primer",
                    previous:   "Anterior",
                    next:       "Siguiénte",
                    last:       "Último"
                },
                aria: {
                    sortAscending:  ": Activar para ordenar la columna de manera ascendente",
                    sortDescending: ": Activar para ordenar la columna de manera descendente"
                },
                buttons: {
                    copyTitle: 'Copiado en el portapapeles',
                    copyKeys: 'Presione <i>ctrl</i> ou <i>\u2318</i> + <i>C</i> para copiar los datos de la tabla a su portapapeles. <br><br>Para cancelar, haga clic en este mensaje o presione Esc.',
                    copySuccess: {
                        _: '%d lineas copiadas',
                        1: '1 linea copiada'
                    }
                }
            }
        } );
    } );
</script>

@endsection


