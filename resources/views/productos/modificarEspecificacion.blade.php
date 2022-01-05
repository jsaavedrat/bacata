@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-tags"> </i> PRODUCTOS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; EDITAR</div>
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

            <div id="texto-titulo"> <i class="fa fa-plus"> </i> Editar Producto</div>

            <div id="contenedor-producto">
                <div class="titulo-venta">Producto</div>
                <div id="resultado-producto">
                    <div id="contenedor-imagen-resultado-producto">
                        <img id="imagen-resultado-producto" src="{{ asset('public/imagenes/sistema/productos') }}/{{$producto_especificacion->imagen}}">
                    </div>
                    <div id="estrellas-producto">
                        <i class="fa fa-star-o fa-fade"> </i>
                        <i class="fa fa-star-o fa-fade"> </i>
                        <i class="fa fa-star-o fa-fade"> </i>
                        <i class="fa fa-star-o fa-fade"> </i>
                        <i class="fa fa-star-o fa-fade"> </i>
                    </div>
                    <div id="nombre-producto">
                       {{$producto_especificacion->nombre_tipo_producto}} {{$producto_especificacion->nombre_marca}} {{$producto_especificacion->nombre_modelo}} {{$producto_especificacion->especificaciones}}
                    </div>
                    <div id="content-precios">

                        @if(isset($producto_especificacion->nombre_promocion))
                            <div class="precios" id="precio-oferta">
                                {{$producto_especificacion->precio_promocion}}
                            </div>
                            <div class="precios" id="precio-real">
                                {{$producto_especificacion->precio_base}}
                            </div>
                            <div id="nombre-promocion">
                                {{$producto_especificacion->porcentaje_descuento}} % {{$producto_especificacion->nombre_promocion}}
                            </div>
                        @else
                            <div class="precios" id="precio-oferta">
                                {{$producto_especificacion->precio_base}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            <div id="contenedor-informacion-cliente">
                <div id="caja-informacion-cliente">
                    <div class="titulo-venta">
                        Editar {{$producto_especificacion->nombre_clasificacion}} de producto
                    </div>
                    <div id="informacion-cliente">
                        <form method="post" action="{{ route('productos.especificacion.modificar') }}">
                            @csrf
                            <div class="label-campo">
                                <label class="label-admin" for="nombre_especificacion"><i id="lista" class="icon-dot-single"></i>Nombre {{$producto_especificacion->nombre_clasificacion}}<i id="cont-icon" class="fa fa-language"></i></label>
                                <input type="text" name="nombre_especificacion" id="nombre_especificacion" class="campo-admin" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="35" value="{{$producto_especificacion->nombre_especificacion}}" required onkeyup="this.value=letras(this.value)" autofocus>
                            </div>

                            <input type="hidden" name="id_clasificacion" value="{{$producto_especificacion->id_clasificacion}}">

                            <input type="hidden" name="id_producto_especificaciones" value="{{$producto_especificacion->id_producto_especificaciones}}">

                            <button class="boton-admin"> Editar {{$producto_especificacion->nombre_clasificacion}}</button>
                        </form>
                    </div>
                </div>

            </div>


</div>

<form method="post" action="{{ route('productos.especificacion.editar') }}">
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
</script>

<style type="text/css">
    #contenedor-producto{
        width: 30%;
        float: left;
        border: 1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 30px rgba(100,100,120,0.3);
        //height: 100%;
        background-color: rgba(255,255,255,0.8);
        //margin-top: 20px;
        //border:1px solid red;
    }

    .titulo-venta{
        width: calc(100% - 40px);
        margin-left: 20px;
        border-bottom:1px solid rgba(215,215,215,0.6);
        font-size:15px;
        color: rgba(52,58,64,1);
        text-transform: uppercase;
        font-weight: 500;
        text-align: center;
        padding:8px;
    }
    #resultado-producto{
        width: 100%;
        //height: 210px;
        float: left;
        //border:1px solid red;
        padding:5px 0px 15px 0px;
    }
    #contenedor-imagen-resultado-producto{
        width: 100%;
        float: left;
        height: 100px;
        //border:1px solid blue;
        display: flex;
        justify-content: center;
    }
    #imagen-resultado-producto{
        height: 100%;
    }
    #estrellas-producto{
        float: left;
        width: 100%;
        font-size: 18px;
        height: 25px;
        line-height: 12px;
        color:rgba(250,200,0,1);
        display: flex;
        justify-content: center;
        //border:1px solid blue;
    }
    #estrellas-producto i{
        padding: 2px;
    }
    #nombre-producto{
        width: 100%;
        height: 60px;
        float: left;
        padding:10px 20px 10px 20px;
        color: rgba(52,58,64,1);
        font-size: 14px;
        letter-spacing: -0.4px;
        font-weight: 500;
        text-align: center;
    }
    #content-precios{
        width: 100%;
        //height: 40px;
        float: left;
        //border:1px solid pink;
        padding:5px 15px 0px 15px;
    }
    .precios{
        width: 50%;
        float: right;
        text-align: right;
        font-weight: bold;
        font-size: 20px;
        letter-spacing: -1px!important;
        //border:1px solid blue;
        padding:0px;
        line-height: 18px;
    }
    #precio-real{
        text-decoration:line-through;
        font-weight: 400;
        text-align: left;
        color: rgba(50,50,50,0.7);
    }

    #nombre-promocion{
        width: 100%;
        float: left;
        font-size: 12px;
        //border:1px solid orange;
        background-color: white;
        line-height: 14px;
        letter-spacing: -0.5px!important;
        color: rgba(50,50,50,0.7);
    }



    #contenedor-informacion-cliente{
        width: calc(70% - 20px);
        float: left;
        margin-left: 20px;
        //margin-top:20px;
        //border:1px solid blue;
    }
    #caja-informacion-cliente{
        float: left;
        border: 1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 30px rgba(100,100,120,0.3);
        background-color: rgba(255,255,255,0.8);
        margin-bottom:20px;
        width: 100%;
        //border:1px solid purple;
    }

    #informacion-cliente{
        width: calc(100% - 40px);
        float: left;
        margin-left: 20px;
        margin-bottom: 20px;
        padding-top: 20px;
    }
</style>

@endsection




