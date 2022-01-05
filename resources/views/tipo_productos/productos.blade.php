@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-cubes"> </i> TIPOS DE PRODUCTO &nbsp;<i class="fa fa-angle-right"> </i> &nbsp; {{$tipo_producto->nombre_tipo_producto}}</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('tipo_productos.crear') }}"><i class="fa fa-plus herramientas"></i><div class="content-texto"><p class="texto-icono">CREAR TIPO DE PRODUCTO</p></div></a></div>
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
    <div id="texto-titulo"> <i class="fa fa-tags"> </i> {{$tipo_producto->nombre_tipo_producto}}</div>
        
    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Producto</th>
                    <th style="width: 100px!important;">Precio</th>
                    <th>Stickers&nbsp;&nbsp;&nbsp;</th>
                    <th style="width: 110px!important;">Codigo Barras</th>
                    <th>Acción</th>
                </tr>
            </thead>

        </table>
</div>

<div id="modal-eliminar">
    <form method="post" action="{{ route('tipo_productos.inactivar') }}">
        @csrf
        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">¿Realmente deseas eliminar el tipo de producto: <x id="nombre-eliminar"></x></div>
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

<form method="post" action="{{ route('productos.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>

<form method="post" action="{{ route('productos.ver') }}">
    @csrf
    <input type="number" name="ver" id="ver" style="display: none;">
    <button id="boton-ver"></button>
</form>

<script type="text/javascript">

    $(document).ready( function () {
        if ($('#tabla').length) {
            $.fn.dataTable.ext.errMode = 'throw';//evitar alert error

            $('#tabla').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,            
                "ajax": {
                        "url": "{{ route('tipo_productos.buscar') }}",
                        "data" : {
                            'id_tipo_producto' : {{$tipo_producto->id_tipo_producto}}
                        }
                },
                "columns": [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nombre_producto'},
                    {data: 'precio', searchable: true},
                    {data: 'agregar-codigo', orderable: false},
                    {data: 'code128'},
                    {data: 'acciones', orderable: false, searchable: false},
                ],
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        filename: '{{$tipo_producto->nombre_tipo_producto}}',
                        title: '',
                        header: false
                    },
                    {
                        extend: 'csv',
                        filename: '{{$tipo_producto->nombre_tipo_producto}}',
                        title: '',
                        header: false
                    },
                    {
                        extend: 'pdf',
                        title: '{{$tipo_producto->nombre_tipo_producto}}',
                        filename: '{{$tipo_producto->nombre_tipo_producto}}',
                    },
                    {
                        extend: 'copy',
                    },
                    {
                        extend: 'print',
                        title: '{{$tipo_producto->nombre_tipo_producto}}',
                    },
                ],
                filename: 'Data export',
                language: {
                    searchPlaceholder: "Buscar",
                    processing:     "Buscando...",
                    search:         "Buscar {{$tipo_producto->nombre_tipo_producto}}&nbsp;:",
                    lengthMenu:     "Mostrar _MENU_ {{$tipo_producto->nombre_tipo_producto}}",
                    info:           "Mostrando {{$tipo_producto->nombre_tipo_producto}} del _START_ al _END_ de un total de _TOTAL_ {{$tipo_producto->nombre_tipo_producto}}",
                    infoEmpty:      "Mostrando {{$tipo_producto->nombre_tipo_producto}} del 0 al 0 de un total de 0 {{$tipo_producto->nombre_tipo_producto}}",
                    infoFiltered:   "(filtrado de un total de _MAX_ {{$tipo_producto->nombre_tipo_producto}})",
                    infoPostFix:    "",
                    loadingRecords: "Cargando...",
                    zeroRecords:    "No se encontraron {{$tipo_producto->nombre_tipo_producto}}",
                    emptyTable:     "Ningún@ {{$tipo_producto->nombre_tipo_producto}} disponible en esta tabla",
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




