@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-image"> </i> CARRUSEL &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; LISTA</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>
<link rel="stylesheet" href="{{ asset('public/ecommerce/css/bootstrap.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/ecommerce/css/style.css') }}"/>
<script src="{{ asset('public/ecommerce/js/bootstrap.min.js') }}"></script>
<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('pagina.carrusel.crear') }}"><i class="fa fa-plus herramientas"></i><div class="content-texto"><p class="texto-icono">AGREGAR IMAGEN A CARRUSEL</p></div></a></div>
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
    <div id="texto-titulo"> <i class="icon-list"> </i> LISTA DE IMAGENES CARRUSEL</div>

    <div id="content-mensaje">
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Imágen agregada con Éxito.</div>@endif
        @if($estatus=="eliminado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Imágen eliminada con Éxito.</div>@endif
        @if($estatus=="actualizado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Imágen Actualizada con Éxito.</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; No se pudo eliminar la Imágen.</div>@endif
    </div>

     <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Imagen</th>
                <th>Título</th>
                <th>Subtítulo</th>
                <th>Descripción</th>
                <th>Orden</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            @foreach($imagenes_carrusel as $imagen_carrusel)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td id="td-imagen"><div class="content-imagen-td"><img src="{{ asset('public/imagenes/pagina/carrusel') }}/{{$imagen_carrusel->imagen_carrusel}}" style="height: 100%;"> </div></td>
                    <td>{{$imagen_carrusel->titulo_carrusel}}</td>
                    <td>{{$imagen_carrusel->subtitulo_carrusel}}</td>
                    <td>{{$imagen_carrusel->descripcion_carrusel}}</td>
                    <td>{{$imagen_carrusel->orden}}</td>
                    <td class="td-acciones">
                        <div class="iconos-acciones">
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-pencil"> </i> EDITAR</a>
                                <i onclick="editar('{{$imagen_carrusel->id_imagen_carrusel}}');" class="icon-pencil i-acciones"> </i> &nbsp;
                            </div>
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-trash"> </i> ELIMINAR</a>
                                <i onclick="eliminar('{{$imagen_carrusel->id_imagen_carrusel}}','{{$imagen_carrusel->titulo_carrusel}}');" class="icon-trash i-acciones"></i>
                            </div>
                        </div>
                    </td>
                </tr> 
            @endforeach

        </tbody>
    </table>

</div>

<div id="modal-eliminar">
    <form method="post" action="{{ route('pagina.carrusel.inactivar') }}">
        @csrf
        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">¿Realmente deseas eliminar la Imagen de carrusel: <x id="nombre-eliminar"></x></div>
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

<form method="post" action="{{ route('pagina.carrusel.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>

<script src="{{ asset('public/js/qrcode/qrcode.js') }}"></script>

<script type="text/javascript">
    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Imagenes Carrusel',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Imagenes Carrusel',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Imagenes Carrusel',
                    filename: 'Imagenes Carrusel',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Imagenes Carrusel',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Imagen&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ Imagenes",
                info:           "Mostrando Imagenes del _START_ al _END_ de un total de _TOTAL_ Imagenes",
                infoEmpty:      "Mostrando Imagenes del 0 al 0 de un total de 0 Imagenes",
                infoFiltered:   "(filtrado de un total de _MAX_ Imagenes)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron Imagenes",
                emptyTable:     "Ninguna Imagen disponible en esta tabla",
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
    .codigoQR{
        padding: 15px;
        background-color: white;
        border:1px solid black;
        margin-bottom: 5px;
    }
    .texto-qr{
        font-size: 18px;
        font-weight: bold;
        line-height: 16px;
    }
    .texto-banner{
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 0px;
    }
    .texto-banner-1{
        font-size: 20px;
        line-height: 25px;
    }
    .texto-banner-2{
        font-size: 25px;
        line-height: 30px;
    }
    .boton-promocion{
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        padding: 2px 30px 0px 30px;
        border-radius: 50px;
        text-transform: uppercase;
        color: white!important;
        background-color: rgba(50,50,50,0.95);
        line-height: 40px;
        height: 40px;
        cursor: pointer;
        text-align: center;
    }
    .boton-membresia {
        height: 30px;
        font-size: 14px;
        color: white;
        width: 100px;
        text-align: center;
        background-color: rgba(50,50,50,1);
        text-transform: uppercase;
        line-height: 30px;
        cursor: pointer;
    }
</style>
@endsection




