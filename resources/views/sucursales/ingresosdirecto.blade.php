@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-home"> </i> SUCURSAL &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; INGRESOS</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('sucursales.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE SUCURSALES</p></div></a></div>
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
        <b>Dirección: </b> {{$sucursal->direccion_sucursal}}<br>
        <b>Teléfono: </b> {{$sucursal->telefono_sucursal}}<br>
    </div>

    <div id="texto-titulo"> <i class="fa fa-icon-arrow-with-circle-down"> </i> Ingresos directos de {{$sucursal->nombre_sucursal}}</div>
        <table id="tabla" class="display compact cell-border stripe" style="width:100%">
            <thead>
            <tr>
                <th>N°</th>
                <th>Fecha de ingreso</th>
                <th>Productos Ingresados</th>
                <th>Cantidad Total Ingresada</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

            @foreach($ingresos_directo as $ingreso)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{date("d-m-Y h:i:s a", strtotime($ingreso->fecha_ingreso_sucursal))}}</td>
                    <td>{{$ingreso->distintos}}</td>
                    <td>{{$ingreso->total}}</td>
                    <td class="td-acciones">
                        <div class="iconos-acciones">
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-forward"> </i> VER</a>
                                <i onclick="ver('{{$ingreso->id_ingreso_sucursal}}');" class="icon-forward i-acciones"> </i> &nbsp;
                            </div>
                        </div>
                    </td>
                </tr> 
            @endforeach

        </tbody>
        </table>
</div>
<form method="post" action="{{ route('sucursales.ver.ingreso.directo') }}">
    @csrf
    <input type="number" name="ver" id="ver" style="display: none;">
    <button id="boton-ver"></button>
</form>


<script type="text/javascript">

    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Ingresos {{$sucursal->nombre_sucursal}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Ingresos {{$sucursal->nombre_sucursal}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Ingresos {{$sucursal->nombre_sucursal}}',
                    filename: 'Ingresos {{$sucursal->nombre_sucursal}}',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Ingresos {{$sucursal->nombre_sucursal}}',
                },
            ],
            filename: 'Data export',
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Ingreso&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ Ingresos",
                info:           "Mostrando Ingresos del _START_ al _END_ de un total de _TOTAL_ Ingresos",
                infoEmpty:      "Mostrando Ingresos del 0 al 0 de un total de 0 Ingresos",
                infoFiltered:   "(filtrado de un total de _MAX_ Ingresos)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron Ingresos",
                emptyTable:     "Ningún Ingreso disponible en esta tabla",
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




    function numeros(string){//Solo letras
        var out = '';
        var filtro = '1234567890';//Caracteres validos
        
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }
</script>
<style type="text/css">
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

    #content-detalles{
        width:50%;
        float: left;
        //border: 1px solid red;
    }
    .label-campo{
        width: 50%;
    }
    #texto-permisos{
        width: 100%;
        color:rgba(52,58,74,1);
        font-size: 13px;
        padding:10px;
        font-weight: 500;
        letter-spacing: -0.5px;
        margin-bottom:10px;
        float: left;
    }

    .estado-pendiente{
        color:orange!important;
    }

    .estado-ingresado{
        color:green!important;
    }
</style>
@endsection


