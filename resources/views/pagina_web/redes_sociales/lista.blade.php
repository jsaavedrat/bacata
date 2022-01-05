@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-facebook"> </i> REDES SOCIALES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; LISTA</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
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
    <div id="texto-titulo"> <i class="icon-list"> </i> REDES SOCIALES MOSTRADAS EN PÁGINA WEB</div>

    <div id="content-mensaje">
        @if($estatus=="actualizada")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Red social actualizada con Éxito.</div>@endif
        @if($estatus=="inactivada")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Red social inactivada con éxito en página web.</div>@endif
        @if($estatus=="activada")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Red social activada con éxito en página web.</div>@endif
    </div>
        
    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th style="width: 20px;">N°</th>
                <th>Red Social</th>
                <th>Acceso / URL</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            @foreach($redes as $red)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$red->nombre_red}}</td>
                    <td>{{$red->acceso_red}} {{$red->texto_extra_red}}</td>
                    <td @if($red->estado_red == "inactivo") style="color:red!important;" @endif>{{$red->estado_red}}</td>
                    <td class="td-acciones">

                    @if($red->id_red == 1)
                        @php
                            $red->acceso_red = "https://api.whatsapp.com/send?phone=57" . $red->acceso_red . "&text=" . str_replace(" ", "%20", $red->texto_extra_red);
                        @endphp
                    @endif
                        <div class="iconos-acciones">
                            <div class="content-acciones">
                                <p class="dropdown-content"><i class="icon-forward"> </i> IR A RED</p>
                                <a href="{{$red->acceso_red}}" target="_blank"><i class="icon-forward i-acciones"> </i> &nbsp;</a>
                            </div>
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-pencil"> </i> EDITAR</a>
                                <i onclick="editar('{{$red->id_red}}');" class="icon-pencil i-acciones"> </i> &nbsp;
                            </div>
                            @if($red->estado_red == "activo")
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="fa fa-ban"> </i> &nbsp; INACTIVAR</a>
                                <i onclick="eliminar('{{$red->id_red}}','{{$red->nombre_red}}');" class="fa fa-ban i-acciones"></i>
                            </div>
                            @else
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="fa fa-check"> </i> &nbsp; ACTIVAR</a>
                                <i onclick="eliminar('{{$red->id_red}}','{{$red->nombre_red}}');" class="fa fa-check i-acciones"></i>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr> 
            @endforeach

        </tbody>
    </table>
</div>

<form method="post" action="{{ route('pagina.redes.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>
<div id="modal-eliminar">
    <form method="post" action="{{ route('pagina.redes.inactivar') }}">
        @csrf
        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">¿Realmente deseas cambiar de estado la red: <x id="nombre-eliminar"></x>?</div>
                <div id="content-botones-modal-eliminar">
                    <div class="content-boton-modal-eliminar">
                        <button class="boton-modal-eliminar"><i class="icon-forward"> </i> Cambiar estado</button>
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
                    filename: 'Logos Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Logos Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Logos Optica Angeles',
                    filename: 'Logos Optica Angeles',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Logos Optica Angeles',
                },
            ],
            filename: 'Data export',
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Red&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ Redes",
                info:           "Mostrando Redes del _START_ al _END_ de un total de _TOTAL_ Redes",
                infoEmpty:      "Mostrando Redes del 0 al 0 de un total de 0 Redes",
                infoFiltered:   "(filtrado de un total de _MAX_ Redes)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron Redes",
                emptyTable:     "Ninguna Red disponible en esta tabla",
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
    #td-imagen{
        padding: 0px!important;
        width: 200px;
    }

    .content-imagen-td{
        width: 100%;
        height: 100px;
        float: left;
        display: flex;
        justify-content: center;
        padding-top:20px;
        padding-bottom: 20px;
    }
</style>
@endsection




