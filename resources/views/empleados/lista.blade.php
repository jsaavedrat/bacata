@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-users"> </i> USUARIOS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; LISTA</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('empleados.crear') }}"><i class="fa fa-plus herramientas"></i><div class="content-texto"><p class="texto-icono">CREAR USUARIOS</p></div></a></div>
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
    <div id="texto-titulo"> <i class="icon-list"> </i> Lista de USUARIOS</div>
    <div id="content-mensaje">
        @if($estatus=="actualizado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Usuario Actualizado con Éxito.</div>@endif
        @if($estatus=="erroractualizar")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Error, ya existe un Usuario con esa identificación y / o Email.</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Error, no se pudo actualizar, el Usuario no existe.</div>@endif
    </div>

    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th style="width: 20px;">N°</th>
                <th>Nombre</th>
                <th>Identificación</th>
                <th>Correo Electrónico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            @foreach($empleados as $empleado)
                <tr>
                    <td style="width: 20px;">{{$loop->iteration}}</td>
                    <td>{{$empleado->name}} {{$empleado->apellido}}</td>
                    <td>{{$empleado->identificacion}}</td>
                    <td>{{$empleado->email}}</td>
                    <td class="td-acciones">
                        <div class="iconos-acciones">
                            {{--
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-suitcase"> </i> LABORAL</a>
                                <i onclick="ver('{{$empleado->id}}');" class="icon-suitcase i-acciones"> </i> &nbsp;
                            </div>
                            --}}
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-pencil"> </i> EDITAR</a>
                                <i onclick="editar('{{$empleado->id}}');" class="icon-pencil i-acciones"> </i> &nbsp;
                            </div>
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="fa fa-times-circle"> </i> INACTIVAR</a>
                                <i onclick="eliminar('{{$empleado->id}}','{{$empleado->name}} {{$empleado->apellido}}');" class="fa fa-times-circle i-acciones"></i>
                            </div>
                        </div>
                    </td>
                </tr> 
            @endforeach

        </tbody>
    </table>  
</div>

<form method="post" action="{{ route('empleados.ver') }}">
    @csrf
    <input type="number" name="ver" id="ver" style="display: none;">
    <button id="boton-ver"></button>
</form>

<form method="post" action="{{ route('empleados.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>

<div id="modal-eliminar">
    <form method="post" action="{{ route('empleados.inactivar') }}">
        @csrf
        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">¿Realmente deseas inactivar el USUARIO: <x id="nombre-eliminar"></x>?</div>
                <div id="content-botones-modal-eliminar">
                    <div class="content-boton-modal-eliminar">
                        <button class="boton-modal-eliminar"><i class="fa fa-times-circle"> </i> Inactivar</button>
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
                    filename: 'Empleados Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Empleados Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Empleados Optica Angeles',
                    filename: 'Empleados Optica Angeles',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Empleados Optica Angeles',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Usuario&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ Usuarios",
                info:           "Mostrando Usuarios del _START_ al _END_ de un total de _TOTAL_ Usuarios",
                infoEmpty:      "Mostrando Usuarios del 0 al 0 de un total de 0 Usuarios",
                infoFiltered:   "(filtrado de un total de _MAX_ Usuarios)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron Usuarios",
                emptyTable:     "Ningún Usuario disponible en esta tabla",
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




