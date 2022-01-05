@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-creative-commons-attribution"> </i> ROLES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; LISTA</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('roles.crear') }}"><i class="fa fa-plus herramientas">  </i><div class="content-texto"><p class="texto-icono">CREAR ROL</p></div></a></div>
    <div class="icono-titulo"><i class="fa fa-question herramientas">                                   </i><div class="content-texto"><p class="texto-icono">AYUDA</p></div></div>
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
    <div id="texto-titulo"> <i class="icon-list"> </i> Roles o cargo de Empleados</div>

    <table id="tabla" class="display compact cell-border stripe" style="width:100%;">
        <thead>
            <tr>
                <th>Rol o Cargo <i class="fa fa-angle-down"> </i></th>
                <th>Cantidad de Empleados <i class="fa fa-angle-down"> </i></th>
                <th>Permisos <i class="fa fa-angle-down"> </i></th>
                <th>Acciones <i class="fa fa-angle-down"> </i></th>
            </tr>
        </thead>
        <tbody>

            @foreach($roles as $rol)
                <tr>
                    <td>{{$rol->name}}</td>
                    <td>{{$rol->cantidad_usuarios}} Empleados</td>
                    <td>{{$rol->cantidad_permisos}} Permisos</td>
                    <td class="td-acciones">
                        <div class="iconos-acciones">
                            @can('Editar_Rol')
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-pencil"> </i> EDITAR</a>
                                <i onclick="editar('{{$rol->id}}');" class="icon-pencil i-acciones"> </i> &nbsp;
                            </div>
                            @endcan
                        </div>
                    </td>
                </tr> 
            @endforeach

        </tbody>
    </table>
</div>

<form method="post" action="{{ route('roles.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>

<script type="text/javascript">
    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            "order": [[ 1, "asc" ]],
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Roles Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Roles Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Roles Optica Angeles',
                    filename: 'Roles Optica Angeles',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Roles Optica Angeles',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Rol o Cargo&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ Roles o Cargos",
                info:           "Mostrando roles del _START_ al _END_ de un total de _TOTAL_ roles o cargos",
                infoEmpty:      "Mostrando roles del 0 al 0 de un total de 0 roles o cargos",
                infoFiltered:   "(filtrado de un total de _MAX_ roles)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron roles",
                emptyTable:     "Ningún rol disponible en esta tabla",
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

<style type="text/css">
    
</style>
@endsection


