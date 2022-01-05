@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-tags"> </i> PRODUCTOS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CODIGOS DE BARRRA</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('productos.crear') }}"><i class="fa fa-plus herramientas"></i><div class="content-texto"><p class="texto-icono">CREAR PRODUCTO</p></div></a></div>
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
    <div id="texto-titulo"> <i class="icon-list"> </i> Lista de Productos</div>
        <div id="content-tabla-stickers">
            
        </div>

        <div id="content-totales-stickers">
            <div id="total-stickers">Total Stickers: </div>
            <a href="{{ route('productos.codigos.imprimir') }}" target="_blank"><div id="proceder-imprimir"><i class="fa fa-print"></i> Proceder a Imprimir</div></a>

            <div id="vaciar-codigos" onclick="vaciarCodigos();"><i class="fa fa-trash-o"></i> Vaciar códigos agregados</div>
        </div>
        
</div>
<script type="text/javascript">
    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Productos',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Productos',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Productos',
                    filename: 'Productos',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Productos',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Producto&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ Productos",
                info:           "Mostrando Productos del _START_ al _END_ de un total de _TOTAL_ Productos",
                infoEmpty:      "Mostrando Productos del 0 al 0 de un total de 0 Productos",
                infoFiltered:   "(filtrado de un total de _MAX_ Productos)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron Productos",
                emptyTable:     "Ningún Producto disponible en esta tabla",
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

    codigoBarras = localStorage.getItem('codigo');
    if(codigoBarras != null){
        codigoBarras = JSON.parse(codigoBarras);
    }else{
        codigoBarras = [];
    }

    $("#content-tabla-stickers").append(`
        <table id="tabla" class="display compact cell-border stripe" style="width:100%">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Producto</th>
                    <th>Stickers</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="t-body">
            </tbody>
        </table>
    `);

    var cant = 0;
    for (var i = 0; i < codigoBarras.length; i++) {
        ii=i+1;
        $("#t-body").append(`
            <tr>
                <td>`+ii+`</td>
                <td>`+codigoBarras[i].nombre+`</td>
                <td><input type="number" value="`+codigoBarras[i].cantidad+`" class="input-cantidad" onfocusout="actualizarCodigo(`+codigoBarras[i].id_producto+`,this.value);"></td>
                <td>Accion</td>
            </tr>
        `);
        cant = parseInt (cant) + parseInt (codigoBarras[i].cantidad);
    }

    document.getElementById("total-stickers").innerHTML = "Total Stickers: "+parseInt(cant);


    function actualizarCodigo(id, cantidad){

        if (cantidad=="") {
            cantidad = 0;
        }

        codigoBarras = localStorage.getItem('codigo');
        if(codigoBarras != null){
            codigoBarras = JSON.parse(codigoBarras);
        }else{
            codigoBarras = [];
        }

        var cant = 0;
        for(var i=0; i<codigoBarras.length; i++){
            if (codigoBarras[i].id_producto == id) {

                codigoBarras[i].cantidad = parseInt(cantidad);
            }
            cant = parseInt (cant) + parseInt (codigoBarras[i].cantidad);
        }
        document.getElementById("total-stickers").innerHTML = "Total Stickers: "+cant;
        codigoBarras = JSON.stringify(codigoBarras);
        localStorage.setItem('codigo', codigoBarras);
    }

    function vaciarCodigos(){
        localStorage.removeItem('codigo');
        window.location.reload();
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
    #content-totales-stickers{
        width: 100%;
        float: left;
        padding-bottom: 20px;
    }
    #total-stickers{
        width: 100%;
        float:left;
        padding:10px;
    }
    #proceder-imprimir{
        width:200px;
        float: left;
        padding:6px;
        background-color: rgba(0,200,200,1);
        color:white;
        text-align: center;
        cursor:pointer;
        font-size: 15px;
    }

    #vaciar-codigos{
        float: right;
        padding:6px;
        background-color: rgba(0,200,200,1);
        color:white;
        text-align: center;
        cursor:pointer;
        font-size: 15px;
    }

</style>


@endsection




