@extends('layouts.app')
@section('content')
    <div id="titulo-admin">
        <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
        <div id="migajas-titulo"><i class="icon-shop"> </i> TIENDA VIRTUAL&nbsp;<i class="fa fa-angle-right"> </i>&nbsp;
            LISTA DE PRODUCTOS</div>
        <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
    </div>

    <div id="iconos-titulo">
        <div class="icono-titulo"><a href="{{ route('home') }}"> <i class="fa fa-home herramientas"></i>
                <div class="content-texto">
                    <p class="texto-icono">INICIO</p>
                </div>
            </a></div>
        
        <div class="icono-titulo"><i class="fa fa-question herramientas"> </i>
            <div class="content-texto">
                <p class="texto-icono">AYUDA</p>
            </div>
        </div>
        <div class="icono-titulo" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                class="fa fa-power-off herramientas"></i>
            <div class="content-texto">
                <p class="texto-icono">CERRAR SESIÓN</p>
            </div>
        </div>
        <div id="content-logout">
            <div id="nombre-user"> {{ Auth::user()->name }} <i class="icon-chevron-down"> </i></div>
            <div id="content-opciones-user">
                <a href="{{ route('usuarios.perfil') }}">
                    <div class="opcion-user"> <i class="icon-key"> </i> CONTRASEÑA </div>
                </a>
                <a href="{{ route('usuarios.perfil') }}">
                    <div class="opcion-user"> <i class="fa fa-user"> &nbsp;</i> PERFIL </div>
                </a>
            @guest @else
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <div class="opcion-user"><i class="fa fa-power-off"> &nbsp;</i> SALIR </div>
                </a>
            @endguest
        </div>
    </div>
</div>
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::VISTA ACTUAL::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<div id="elemento-admin">
    <div id="texto-titulo"> <i class="icon-list"> </i> Ventas en <b>E-Commerce</b></div>

    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Referencia</th>
                <th>Nombre Cliente</th>
                <th>Correo</th>
                <th>Monto</th>
                <th>Resultado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($ventas as $venta)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{$venta->referencia}}</td>
                    <td>{{ $venta->nombre }}</td>
                    <td>{{ $venta->correo }}</td>
                    <td style="text-align: center;">{{ $venta->monto }}</td>
                    <td>
                        @if ($venta->transactionState == '4')
                            <p style="color:green;font-weight:bold;">APROBADA</p>
                        @elseif($venta->transactionState=="6")
                            <p style="color:red;font-weight:bold;">RECHAZADA</p>
                        @elseif($venta->transactionState=="104")
                            <p style="color:red;font-weight:bold;">ERROR</p>
                        @elseif($venta->transactionState=="7")
                            <p style="color:orange;font-weight:bold;">PENDIENTE</p>
                        @else
                            <p style="color:orange;font-weight:bold;">Error</p>
                        @endif
                    </td>
                    <td class="td-acciones">
                        <div class="iconos-acciones">
                            <div class="content-acciones">
                                <a href="" class="dropdown-content"><i class="icon-forward"> </i> VER</a>
                                <i onclick="ver('{{$venta->referencia}}');" class="icon-forward i-acciones"> </i> &nbsp;
                            </div>
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-pencil"> </i> EDITAR</a>
                                <i  class="icon-pencil i-acciones">
                                </i> &nbsp;
                            </div> 
                            {{-- <div class="content-acciones">
                                    <a class="dropdown-content"><i class="icon-trash"> </i> ELIMINAR</a>
                                    <i onclick="eliminar('{{$producto->id_producto}}','{{$producto->nombre_producto}}');" class="icon-trash i-acciones"></i>
                                </div> --}}
                        </div>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div> 
{{--  <form method="post" action="{{ route('productos.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>  --}}
<form method="post" action="{{ route('adminecommerce.ventas.productos') }}">
    @csrf
    <input type="text" name="ver" id="ver" style="display: none;">
    <button id="boton-ver"></button>
</form>
<script type="text/javascript">


    $(document).ready(function() {
        $('#tabla').DataTable({
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'excel',
                    filename: 'Productos Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Productos Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Productos Optica Angeles',
                    filename: 'Productos Optica Angeles',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Productos Optica Angeles',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing: "Procesando...",
                search: "Buscar Venta&nbsp;:",
                lengthMenu: "Mostrar _MENU_ Ventas",
                info: "Mostrando Ventas del _START_ al _END_ de un total de _TOTAL_ Ventas",
                infoEmpty: "Mostrando Ventas del 0 al 0 de un total de 0 Ventas",
                infoFiltered: "(filtrado de un total de _MAX_ Ventas)",
                infoPostFix: "",
                loadingRecords: "Cargando...",
                zeroRecords: "No se encontraron Ventas",
                emptyTable: "Ninguna Venta disponible en esta tabla",
                paginate: {
                    first: "Primer",
                    previous: "Anterior",
                    next: "Siguiénte",
                    last: "Último"
                },
                aria: {
                    sortAscending: ": Activar para ordenar la columna de manera ascendente",
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
    });

    function clickElemento(elemento) {
        document.getElementsByClassName('dt-button')[elemento].click();
    }
</script>

@endsection
