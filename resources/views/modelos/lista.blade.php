@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-bookmark"> </i> MODELOS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; LISTA</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('modelos.crear') }}"><i class="fa fa-plus herramientas"></i><div class="content-texto"><p class="texto-icono">CREAR MODELO</p></div></a></div>
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
    <div id="texto-titulo"> <i class="icon-list"> </i> Lista de Modelos</div>

    <div id="content-mensaje">
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Modelo Eliminado con Éxito.</div>@endif
        @if($estatus=="actualizado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Modelo Actualizado con Éxito.</div>@endif
        @if($estatus=="erroractualizar")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Error, el Modelo no existe o ya existe un Modelo con ese nombre.</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; No se pudo eliminar el Modelo.</div>@endif
    </div>

    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th style="width: 20px!important; text-align: center!important;">N°</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Producto</th>
                <th style="max-width: 100px!important;">Acción</th>
            </tr>
        </thead>
    </table>
</div>

<div id="modal-eliminar">
    <form method="post" action="{{ route('modelos.inactivar') }}">
        @csrf
        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">¿Realmente deseas eliminar el Modelo: <x id="nombre-eliminar"></x></div>
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

<form method="post" action="{{ route('modelos.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
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
                        "url": "{{ route('modelos.buscar') }}"
                },
                "columns": [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nombre_modelo'},
                    {data: 'nombre_marca', name: 'marcas.nombre_marca'},
                    {data: 'nombre_tipo_producto', name: 'tipo_productos.nombre_tipo_producto'},
                    {data: 'acciones', orderable: false, searchable: false},
                ],
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        filename: 'Modelos',
                        title: '',
                        header: false
                    },
                    {
                        extend: 'csv',
                        filename: 'Modelos',
                        title: '',
                        header: false
                    },
                    {
                        extend: 'pdf',
                        title: 'Modelos',
                        filename: 'Modelos',
                    },
                    {
                        extend: 'copy',
                    },
                    {
                        extend: 'print',
                        title: 'Modelos',
                    },
                ],
                filename: 'Data export',
                language: {
                    searchPlaceholder: "Buscar",
                    processing:     "Buscando...",
                    search:         "Buscar Modelos&nbsp;:",
                    lengthMenu:     "Mostrar _MENU_ Modelos",
                    info:           "Mostrando Modelos del _START_ al _END_ de un total de _TOTAL_ Modelos",
                    infoEmpty:      "Mostrando Modelos del 0 al 0 de un total de 0 Modelos",
                    infoFiltered:   "(filtrado de un total de _MAX_ Modelos)",
                    infoPostFix:    "",
                    loadingRecords: "Cargando...",
                    zeroRecords:    "No se encontraron Modelos",
                    emptyTable:     "Ninguna Marca disponible en esta tabla",
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

@endsection




