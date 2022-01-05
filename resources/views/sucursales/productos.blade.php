@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-home"> </i> SUCURSALES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; PRODUCTOS</div>
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

    <div id="nombre-detalle">{{$sucursal->nombre_sucursal}}</div>
    <div id="detalle-elementos">
        <b>Cantidad de Productos: </b> {{$sucursal->total}}<br>
        <b>Productos Diferentes: </b> {{$sucursal->cantidad}}<br>
        @can('Codigo_Barras')<b id="agregar-codigo" onclick="codigoBarraMultiple();">Agregar TODOS a la lista impresión códigos de barra <i class="fa fa-barcode"> </i></b><br>@endcan
    </div>

    <div id="texto-titulo"> <i class="fa fa-tags"> </i> Productos en {{$sucursal->nombre_sucursal}}</div>

    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Producto</th>
                <th>Precio</th>
                <th style="white-space:nowrap;">En Inventario&nbsp;</th>
                <th>Stickers&nbsp;&nbsp;&nbsp;</th>
                <th style="white-space:nowrap;">Codigo Barras&nbsp;</th>
                <th>Acción</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript" id="productos-ingreso">
    productos_barcode = {!! json_encode($productos) !!};
    // console.log(productos_barcode);
</script>
<script type="text/javascript">
    $("#productos-ingreso").empty();

    $(document).ready( function () {

        if ($('#tabla').length) {
            $.fn.dataTable.ext.errMode = 'throw';//evitar alert error

            $('#tabla').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('sucursales.buscar.producto') }}",
                    "data" : {
                        'sucursal' : {{$sucursal->id_sucursal}},
                        'lentes': {{$configuracion_cotizacion->id_tipo_producto_lentes}},
                        'examen': {{$configuracion_cotizacion->id_tipo_producto_examenes}}
                    }
                },
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                "columns": [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nombre_producto', name: 'productos.nombre_producto'},
                    {data: 'precio', name: 'productos.precio_base'},
                    {data: 'unidades', name: 'producto_sucursales.cantidad'},
                    {data: 'agregar-codigo', orderable: false},
                    {data: 'code128', name: 'productos.code128'},
                    {data: 'acciones', orderable: false, searchable: false},
                ],
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        filename: 'Productos {{$sucursal->nombre_sucursal}}',
                        title: '',
                        header: true,
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 5 ]
                        }
                    },
                    {
                        extend: 'csv',
                        filename: 'Productos {{$sucursal->nombre_sucursal}}',
                        title: '',
                        header: false,
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 5 ]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'Productos en {{$sucursal->nombre_sucursal}}',
                        filename: 'Productos en {{$sucursal->nombre_sucursal}}',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 5 ]
                        }
                    },
                    {
                        extend: 'copy',
                    },
                    {
                        extend: 'print',
                        title: 'Productos {{$sucursal->nombre_sucursal}}',
                    },
                ],
                filename: 'Data export',
                language: {
                    searchPlaceholder: "Buscar",
                    processing:     "Buscando...",
                    search:         "Buscar Productos&nbsp;:",
                    lengthMenu:     "Mostrar _MENU_ Productos",
                    info:           "Mostrando Productos del _START_ al _END_ de un total de _TOTAL_ Productos",
                    infoEmpty:      "Mostrando Productos del 0 al 0 de un total de 0 Productos",
                    infoFiltered:   "(filtrado de un total de _MAX_ Productos)",
                    infoPostFix:    "",
                    loadingRecords: "Cargando...",
                    zeroRecords:    "No se encontraron Productos",
                    emptyTable:     "Ningúna Producto disponible en esta tabla",
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
            });        
            $('#tabla').DataTable().on("draw", function(){

                codigoBarras = localStorage.getItem('codigo');
                if(codigoBarras != null){
                    codigoBarras = JSON.parse(codigoBarras);
                }else{
                    codigoBarras = [];
                }
                for (var i = 0; i < codigoBarras.length; i++) {
                    var input = document.getElementById("input-cantidad-"+codigoBarras[i].id_producto);
                    if(input != null && codigoBarras[i].cantidad != 0){
                            document.getElementById("input-cantidad-"+codigoBarras[i].id_producto).value = codigoBarras[i].cantidad;
                    }
                }
            });
        }
    });
    function clickElemento(elemento){
        document.getElementsByClassName('dt-button')[elemento].click();
    }

    function modificarCantidad(id_producto){

        document.getElementById("input-unidades-"+id_producto).style.backgroundColor = "rgba(255,255,255,0.8)";
        document.getElementById("input-unidades-"+id_producto).disabled = false;
        document.getElementById("input-unidades-"+id_producto).focus();
    }

    function cambiarUnidades(id_producto){

        document.getElementById("input-unidades-"+id_producto).disabled = true;
        var cantidad = document.getElementById("input-unidades-"+id_producto).value;
        document.getElementById("input-unidades-"+id_producto).value = "Espere..";

        var sucursal = '{{$sucursal->id_sucursal}}';

        console.log("AJUSTAR:");
        console.log("sucursal: "+sucursal+", cantidad: "+cantidad+", producto: "+id_producto)

        var url="{{route('sucursales.productos.cantidades')}}";
        var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id_sucursal": sucursal,
            "id_producto": id_producto,
            "cantidad": cantidad
        };
        $.ajax({
            type: 'POST',
            url: url,
            data: datos,
            success: function(data) {
                console.log("success");
                console.log(data);
                document.getElementById("input-unidades-"+id_producto).style.backgroundColor = "rgba(0,255,0,0.2)";
                document.getElementById("input-unidades-"+id_producto).value = cantidad;

            },
            error: function(data) {
                console.log("error");
            }
        });

    }

</script>
<style type="text/css">
    #agregar-codigo{
        cursor: pointer;
    }

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

    .unidades{
        background-color: rgba(0,0,0,0);
        border:1px solid rgba(0,0,0,0);
    }

    .unidades:focus{
        border:1px solid rgba(215,215,200,0.7)!important;
        background-color: rgba(255,255,255,0.8);
    }

</style>
@endsection


