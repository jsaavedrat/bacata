@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-truck"> </i> EMPRESAS DE ENVÍO &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; LISTA</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('empresas.envio.crear') }}"><i class="fa fa-plus herramientas"></i><div class="content-texto"><p class="texto-icono">CREAR EMPRESA DE ENVÍO</p></div></a></div>
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
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::VISTA ACTUAL:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<div id="elemento-admin">
    <div id="texto-titulo"> <i class="icon-list"> </i> Lista empresas de envío</div>

    <div id="content-mensaje">
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Empresa de envío eliminada con Éxito.</div>@endif
        @if($estatus=="actualizado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Empresa de envío actualizada con Éxito.</div>@endif
        @if($estatus=="erroractualizar")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Error, la Empresa de envío no existe o ya existe una Empresa con ese nombre.</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; No se pudo eliminar la Empresa de envío.</div>@endif
    </div>

    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th style="width: 20px;text-align: center;">N°</th>
                <th>Imagen</th>
                <th>Empresa Envío</th>
                <th>Código de envío</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

            @foreach($empresas_envio as $empresa_envio)
                <tr>
                    <td style="width: 20px;text-align: center;">{{$loop->iteration}}</td>
                    <td id="td-imagen">
                        <div class="content-imagen-td">
                            <img src="{{ asset('public/imagenes/sistema/empresas_envio') }}/{{$empresa_envio->imagen_empresa_envio}}" style="height: 100%;">
                        </div>
                    </td>
                    <td>{{$empresa_envio->nombre_empresa_envio}}</td>
                    <td>{{$empresa_envio->nombre_codigo}}</td>
                    <td class="td-acciones">
                        <div class="iconos-acciones">
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-pencil"> </i> EDITAR</a>
                                <i onclick="editar('{{$empresa_envio->id_empresa_envio}}');" class="icon-pencil i-acciones"> </i> &nbsp;
                            </div>
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-trash"> </i> ELIMINAR</a>
                                <i onclick="eliminar('{{$empresa_envio->id_empresa_envio}}','{{$empresa_envio->nombre_empresa_envio}}');" class="icon-trash i-acciones"></i>
                            </div>
                        </div>
                    </td>
                </tr> 
            @endforeach

        </tbody>
    </table>
</div>

<form method="post" action="{{ route('empresas.envio.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>
<div id="modal-eliminar">
    <form method="post" action="{{ route('empresas.envio.inactivar') }}">
        @csrf
        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">¿Realmente deseas eliminar la Cirugía: <x id="nombre-eliminar"></x></div>
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

<script type="text/javascript">

    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Empresas de Envío',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Empresas de Envío',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Empresas de envío',
                    filename: 'Empresas de Envío',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Empresas de Envío',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Empresa de envío&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ Empresas de envío",
                info:           "Mostrando empresas de envío del _START_ al _END_ de un total de _TOTAL_ empresas de envío",
                infoEmpty:      "Mostrando empresas de envío del 0 al 0 de un total de 0 empresas de envío",
                infoFiltered:   "(filtrado de un total de _MAX_ empresas de envío)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron empresas de envío",
                emptyTable:     "Ninguna empresa de envío disponible en esta tabla",
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
</script>
@endsection


