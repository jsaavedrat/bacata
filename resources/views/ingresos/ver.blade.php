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

    <div id="nombre-detalle">Ingreso a {{$ingreso->nombre_bodega}}</div>
    <div id="detalle-elementos">
        <b>Fecha: </b> {{$ingreso->fecha_ingreso}}<br>
        <b>Proveedor: </b> {{$ingreso->nombre_proveedor}}<br>
        <b>Productos Ingresados: </b> {{$ingreso->total}}<br>
        @can('Codigo_Barras')<b id="agregar-codigo" onclick="codigoBarraMultiple();">Agregar a la lista impresión códigos de barra <i class="fa fa-barcode"> </i></b><br>@endcan
    </div>

    <div id="texto-titulo"> <i class="icon-arrow-with-circle-down"> </i> Productos Ingresados</div>

    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Producto</th>
                <th>Unidades</th>
                <th style="display: none;">Code128</th>
                <th>Código Barras</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

            @foreach($producto_ingresos as $producto_ingreso)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$producto_ingreso->nombre_tipo_producto}} {{$producto_ingreso->nombre_marca}} {{$producto_ingreso->nombre_modelo}} {{$producto_ingreso->especificaciones}} id: {{$producto_ingreso->id_producto}}</td>
                    <td><input class="input-cantidad unidades" id="input-unidades-{{$producto_ingreso->id_producto}}" value="{{$producto_ingreso->cantidad}}" disabled autocomplete="off" onfocusout="cambiarUnidades({{$producto_ingreso->id_producto}})"></td>
                    <td style="display: none;">{{$producto_ingreso->code128}}</td>
                    <td>
                        @can('Codigo_Barras')
                        <input class="input-cantidad" id="input-cantidad-{{$producto_ingreso->id_producto}}" onfocusout="cambiarCodigo('{{$producto_ingreso->id_producto}}',this.value,'{{$producto_ingreso->nombre_tipo_producto}} {{$producto_ingreso->nombre_marca}} {{$producto_ingreso->nombre_modelo}} {{$producto_ingreso->especificaciones}}','{{$producto_ingreso->code128}}','{{$producto_ingreso->precio_base}}');">
                        @endcan
                    </td>
                    <td class="td-acciones">
                        @can('Editar_Ingreso')
                        <div class="iconos-acciones">
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-pencil"> </i> MODIFICAR</a>
                                <i onclick="modificarCantidad('{{$producto_ingreso->id_producto}}');" class="icon-pencil i-acciones"></i>
                            </div>
                        </div>
                        @endcan
                    </td>
                </tr> 
            @endforeach

        </tbody>
    </table>

</div>

<div id="modal-eliminar">

        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">Modificar la cantidad de un ingreso actualiza las unidades en existencia del producto.<x id="nombre-eliminar"></x></div>
                <div id="content-botones-modal-eliminar">
                    <div class="content-boton-modal-eliminar">
                        <button class="boton-modal-eliminar"><i class="icon-trash"> </i> Ok</button>
                    </div>
                    <div class="content-boton-modal-eliminar">
                        <div class="boton-modal-eliminar" style="padding-top:1vh;" onclick="cerrarModalOk();"><i class="fa fa-close"> </i> Atrás</div>
                    </div>
                </div>
            </div>
        </div>
        <input type="number" name="eliminar" id="eliminar" style="display: none;">

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

<script type="text/javascript">

    confirmacion = false;
    id_producto_confirmacion = 0;
    cantidad_aux = "";

    productos_barcode = {!! json_encode($producto_ingresos) !!};
    for(var i=0; i < productos_barcode.length; i++){
        productos_barcode[i].nombre = productos_barcode[i].nombre_tipo_producto +" "+ productos_barcode[i].nombre_marca +" "+ productos_barcode[i].nombre_modelo +" "+ productos_barcode[i].especificaciones;
    }

    $(document).ready( function () {

        codigoBarras = localStorage.getItem('codigo');
        if(codigoBarras != null){
            codigoBarras = JSON.parse(codigoBarras);
        }else{
            codigoBarras = [];
        }

        for (var i = 0; i < codigoBarras.length; i++) {
                var input = document.getElementById("input-cantidad-"+codigoBarras[i].id_producto);
                if(input != null){
                        document.getElementById("input-cantidad-"+codigoBarras[i].id_producto).value = codigoBarras[i].cantidad;
                }
        }

        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Ingreso Bodega {{$ingreso->nombre_bodega}} {{$ingreso->fecha_ingreso}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Ingreso Bodega {{$ingreso->nombre_bodega}} {{$ingreso->fecha_ingreso}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Ingreso Bodega {{$ingreso->nombre_bodega}} {{$ingreso->fecha_ingreso}}',
                    filename: 'Ingreso Bodega {{$ingreso->nombre_bodega}} {{$ingreso->fecha_ingreso}}',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Ingreso Bodega {{$ingreso->nombre_bodega}} {{$ingreso->fecha_ingreso}}',
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

    function clickElemento(elemento){
        document.getElementsByClassName('dt-button')[elemento].click();
    }
    
    function modificarCantidad(id_producto){
        
        if(confirmacion == false){
            $("#modal-eliminar").fadeIn();
            id_producto_confirmacion = id_producto;
        }else{
            document.getElementById("input-unidades-"+id_producto).style.backgroundColor = "rgba(255,255,255,0.8)";
            document.getElementById("input-unidades-"+id_producto).disabled = false;
            document.getElementById("input-unidades-"+id_producto).focus();
        }
        cantidad_aux = document.getElementById("input-unidades-"+id_producto).value;
    }

    function cambiarUnidades(id_producto){

        var cantidad = document.getElementById("input-unidades-"+id_producto).value;
        document.getElementById("input-unidades-"+id_producto).disabled = true;
        
        if((cantidad_aux != cantidad) && (cantidad_aux != "Espere..")){

            document.getElementById("input-unidades-"+id_producto).value = "Espere..";
            var bodega = '{{$ingreso->id_bodega}}';
            var ingreso = '{{$ingreso->id_ingreso}}';

            var url="{{route('ingresos.editar.cantidades')}}";
            var datos = {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "id_bodega": bodega,
                "id_ingreso": ingreso,
                "id_producto": id_producto,
                "cantidad": cantidad,
                "cantidad_original": cantidad_aux
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

    }

    function cerrarModalOk(){
        confirmacion = true;
        $("#modal-eliminar").fadeOut();
        document.getElementById("input-unidades-"+id_producto_confirmacion).style.backgroundColor = "rgba(255,255,255,0.8)";
        document.getElementById("input-unidades-"+id_producto_confirmacion).disabled = false;
        document.getElementById("input-unidades-"+id_producto_confirmacion).focus(); 
    }

</script>

@endsection


