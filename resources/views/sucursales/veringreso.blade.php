@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-home"> </i> SUCURSAL &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; INGRESO</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('sucursales.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE SUCURSALES</p></div></a></div>
    <div class="icono-titulo"><i class="fa fa-file-excel-o herramientas" onclick="clickElemento(0);">   </i><div class="content-texto"><p class="texto-icono">EXPORTAR EN EXCEL</p></div></div>
    <div class="icono-titulo"><i class="fa fa-file-archive-o herramientas" onclick="clickElemento(1);"> </i><div class="content-texto"><p class="texto-icono">EXPORTAR EN CSV</p></div></div>
    <div class="icono-titulo"><i class="fa fa-file-pdf-o herramientas" onclick="clickElemento(2);">     </i><div class="content-texto"><p class="texto-icono">EXPORTAR EN PDF</p></div></div>
    <div class="icono-titulo"><i class="fa fa-copy herramientas" onclick="clickElemento(3);">           </i><div class="content-texto"><p class="texto-icono">COPIAR DATOS</p></div></div>
    <div class="icono-titulo"><i class="icon-print herramientas" onclick="clickElemento(4);">           </i><div class="content-texto"><p class="texto-icono">IMPRIMIR DATOS</p></div></div>
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

    <div id="content-detalles">
        <div id="nombre-detalle">
            Salida de {{$traslado->nombre_establecimiento}} a {{$traslado->nombre_sucursal_llegada}}
        </div>
        <div id="detalle-elementos">
            <b>Fecha: </b> {{$traslado->fecha_salida_traslado}}<br>
            <b>Usuario Registro: </b> {{$traslado->user_registro}}<br>
            <b>Usuario Traslado: </b> {{$traslado->name}} {{$traslado->apellido}}<br>
            <b>Total Productos: </b> {{$traslado->cantidad}}<br>
            @can('Codigo_Barras')<b id="agregar-codigo" onclick="codigoBarraMultiple();">Agregar a la lista impresión códigos de barra <i class="fa fa-barcode"> </i></b><br>@endcan
            <b>Estado: </b> @if($traslado->estado_traslado == 'ingresado')<b style="color:green;"> @else <b style="color:orange;">@endif  {{$traslado->estado_traslado}} </b>
            @if($traslado->estado_traslado == 'pendiente')
                <b onclick="ver('{{$traslado->id_traslado}}');" style="cursor: pointer;">INGRESAR <i class="icon-arrow-with-circle-down"> </i></b> 
            @endif
        </div>
    </div>

        <div id="texto-titulo"> <i class="icon-list"> </i> DETALLE DE INGRESO</div>
        <table id="tabla" class="display compact cell-border stripe" style="width:100%">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Producto</th>
                    <th>Cantidad de Salida</th>
                    <th>Cantidad de Ingreso</th>
                    <th>Código Barras</th>
                </tr>
            </thead>
            <tbody>
                @foreach($producto_traslados as $producto_traslado)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$producto_traslado->nombre_tipo_producto}} {{$producto_traslado->nombre_marca}} {{$producto_traslado->nombre_modelo}} {{$producto_traslado->especificaciones}}</td>
                        <td style="text-align: center;"><b>{{$producto_traslado->cantidad_salida}}</b></td>
                        <td style="text-align: center;
                            @if($producto_traslado->cantidad_salida != $producto_traslado->cantidad_llegada)
                                color:orange;
                            @else
                                color:green;
                            @endif
                        "><b>{{$producto_traslado->cantidad_llegada}}</b></td>
                        <td class="td-acciones">
                            @can('Codigo_Barras')
                            <div class="iconos-acciones">
                                <div class="content-acciones">
                                    <a class="dropdown-content">AGREGAR 1</a>
                                    <i onclick="codigoBarra('{{$producto_traslado->id_producto}}','1','{{$producto_traslado->nombre_tipo_producto}} {{$producto_traslado->nombre_marca}} {{$producto_traslado->nombre_modelo}} {{$producto_traslado->especificaciones}}','{{$producto_traslado->code128}}','{{$producto_traslado->precio_base}}');" class="fa fa-barcode i-acciones"></i>
                                </div>
                            </div>
                            @endcan
                        </td>
                    </tr> 
                @endforeach
            </tbody>
        </table>
</div>

<form method="post" action="@if($traslado->establecimiento_salida == 'bodega') {{ route('traslados.bodegasucursal.crearentrada') }} @else {{ route('traslados.sucursalsucursal.crearentrada') }} @endif">
    @csrf
    <input type="number" name="ver" id="ver" style="display: none;">
    <button id="boton-ver"></button>
</form>

<script type="text/javascript" id="productos-ingreso">
    productos_barcode = {!! json_encode($producto_traslados) !!};
    for(var i=0; i < productos_barcode.length; i++){
        productos_barcode[i].cantidad = productos_barcode[i].cantidad_llegada;
        productos_barcode[i].nombre = productos_barcode[i].nombre_tipo_producto +" "+ productos_barcode[i].nombre_marca +" "+ productos_barcode[i].nombre_modelo +" "+ productos_barcode[i].especificaciones;
    }
    //console.log(productos_barcode);
</script>
<script type="text/javascript">

    $("#productos-ingreso").empty();

    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Entrada {{$traslado->fecha_salida_traslado}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Entrada {{$traslado->fecha_salida_traslado}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Entrada {{$traslado->fecha_salida_traslado}}',
                    filename: 'Entrada {{$traslado->fecha_salida_traslado}}',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Entrada {{$traslado->fecha_salida_traslado}}',
                },
            ],
            filename: 'Data export',
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Productos&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ Productos",
                info:           "Mostrando productos del _START_ al _END_ de un total de _TOTAL_ productos",
                infoEmpty:      "Mostrando productos del 0 al 0 de un total de 0 productos",
                infoFiltered:   "(filtrado de un total de _MAX_ productos)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron productos",
                emptyTable:     "Ningún producto disponible en esta tabla",
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

    function clickElemento(elemento){
        document.getElementsByClassName('dt-button')[elemento].click();
    }


    function numeros(string){//Solo letras
        var out = '';
        var filtro = '1234567890';//Caracteres validos
        
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }
</script>
<style type="text/css">
    .input-cantidad{
        width: 100%;
        height: calc(16px + 1.3vh)!important;
        margin-top: -8px;
        margin-bottom: -8px;
        float: left;
        background-color: rgba(255,255,255,0.8);
        padding: 5px;
        border:1px solid rgba(215,215,200,0.7);
        font-weight: bold;
        max-width: 100px;
        text-align: center;
    }
    .input-cantidad::placeholder {
        color:rgba(180,180,180,1);
        font-weight: normal;
        letter-spacing: -0.5px;
    }

    .input-cantidad:focus{
        border:1px solid rgba(215,215,200,0.7)!important;
    }

    #content-detalles{
        width:50%;
        float: left;
        //border: 1px solid red;
    }
    .label-campo{
        width: 50%;
    }
    #texto-permisos{
        width: 100%;
        color:rgba(52,58,74,1);
        font-size: 13px;
        padding:10px;
        font-weight: 500;
        letter-spacing: -0.5px;
        margin-bottom:10px;
        float: left;
    }
    #agregar-codigo{
        cursor: pointer;
    }
</style>
@endsection


