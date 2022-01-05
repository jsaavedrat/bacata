@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-layers"> </i> CLIENTES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; LISTA</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('clientes.crear') }}"><i class="fa fa-plus herramientas"></i><div class="content-texto"><p class="texto-icono">CREAR PACIENTE</p></div></a></div>
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
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::VISTA ACTUAL:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<div id="elemento-admin">
    <div id="texto-titulo"> <i class="icon-list"> </i> Lista de Clientes</div>

    <div id="content-mensaje">
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Cliente Eliminado con Éxito.</div>@endif
        @if($estatus=="actualizado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Modulo Actualizado con Éxito.</div>@endif
        @if($estatus=="erroractualizar")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Error, el Módulo no existe o ya existe un Módulo con ese nombre.</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; No se pudo eliminar el Módulo.</div>@endif
    </div>

    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Paciente</th>
                <th>Documento</th>
                <th>Teléfono</th>
                <th>Género</th>
                {{--<th>Acción</th>--}}
            </tr>
        </thead>
        <tbody>

            @foreach($clientes as $cliente)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$cliente->nombres}} {{$cliente->apellidos}}</td>
                    <td>{{$cliente->identificacion}}</td>
                    <td>{{$cliente->telefono}}</td>
                    <td>{{$cliente->email}}</td>
                    {{--<td class="td-acciones">
                        <div class="iconos-acciones">
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-forward"> </i> VER</a>
                                <i onclick="ver('{{$cliente->id_cliente}}');" class="icon-forward i-acciones"> </i> &nbsp;
                            </div>
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-pencil"> </i> EDITAR</a>
                                <i onclick="editar('{{$cliente->id_cliente}}');" class="icon-pencil i-acciones"> </i> &nbsp;
                            </div>
                        </div>
                    </td>--}}
                </tr> 
            @endforeach

        </tbody>
    </table>
</div>

<form method="post" action="{{ route('clientes.ver') }}">
    @csrf
    <input type="number" name="ver" id="ver" style="display: none;">
    <button id="boton-ver"></button>
</form>
<form method="post" action="{{ route('pacientes.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>

<script type="text/javascript">

    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Modulos Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Modulos Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Módulos Optica Angeles',
                    filename: 'Modulos Optica Angeles',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Módulos Optica Angeles',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Módulo&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ módulos",
                info:           "Mostrando módulos del _START_ al _END_ de un total de _TOTAL_ módulos",
                infoEmpty:      "Mostrando módulos del 0 al 0 de un total de 0 módulos",
                infoFiltered:   "(filtrado de un total de _MAX_ módulos)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron módulos",
                emptyTable:     "Ningún módulo disponible en esta tabla",
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


