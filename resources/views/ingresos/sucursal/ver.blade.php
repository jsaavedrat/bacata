@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-arrow-with-circle-down"> </i> INGRESOS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; VER</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('ingresos.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE INGRESOS</p></div></a></div>
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

    <div id="nombre-detalle">Ingreso a {{$ingreso->nombre_sucursal}}</div>
    <div id="detalle-elementos">
        <b>Fecha: </b> {{$ingreso->fecha_ingreso_sucursal}}<br>
        <b>Productos Ingresados: </b> {{$ingreso->total}}<br>
    </div>

    <div id="texto-titulo"> <i class="icon-arrow-with-circle-down"> </i> Productos Ingresados</div>

    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Producto</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>

            @foreach($producto_ingresos as $producto_ingreso)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$producto_ingreso->nombre_tipo_producto}} {{$producto_ingreso->nombre_marca}} {{$producto_ingreso->nombre_modelo}} {{$producto_ingreso->especificaciones}}</td>
                    <td>{{$producto_ingreso->cantidad}}</td>
                </tr> 
            @endforeach

        </tbody>
    </table>

</div>

<form method="post" action="{{ route('welcome') }}">
    @csrf
    <input type="number" name="ver" id="ver" style="display: none;">
    <button id="boton-ver"></button>
</form>

<style type="text/css">
    #nombre-detalle{
        width: 100%;
        padding:10px;
        float: left;
        font-size: 20px;
        font-weight: bold;
        letter-spacing: -0.5px;
        color:rgba(52,58,74,1);
    }
    #detalle-elementos{
        width: 100%;
        float: left;
        font-size: 15px;
        padding:10px 10px 10px 20px;
        letter-spacing: -0.3px;
        color:rgba(52,58,74,1);
    }
</style>

<script type="text/javascript">
    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Ingreso Sucursal {{$ingreso->nombre_sucursal}} {{$ingreso->fecha_ingreso_sucursal}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Ingreso Sucursal {{$ingreso->nombre_sucursal}} {{$ingreso->fecha_ingreso_sucursal}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Ingreso Sucursal {{$ingreso->nombre_sucursal}} {{$ingreso->fecha_ingreso_sucursal}}',
                    filename: 'Ingreso Sucursal {{$ingreso->nombre_sucursal}} {{$ingreso->fecha_ingreso_sucursal}}',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Ingreso Sucursal {{$ingreso->nombre_sucursal}} {{$ingreso->fecha_ingreso_sucursal}}',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Bodegas&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ ingreso",
                info:           "Mostrando productos del _START_ al _END_ de un total de _TOTAL_ productos",
                infoEmpty:      "Mostrando productos del 0 al 0 de un total de 0 productos",
                infoFiltered:   "(filtrado de un total de _MAX_ productos)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron productos",
                emptyTable:     "Ningún productos disponible en esta tabla",
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


