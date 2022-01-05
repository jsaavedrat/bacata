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
                        <img id="imagen-resultado-producto" src="{{ asset('public/imagenes/sistema/productos') }}/{{$producto->imagen}}">
                    </div>
                    <div id="estrellas-producto">
                        <i class="fa fa-star-o fa-fade"> </i>
                        <i class="fa fa-star-o fa-fade"> </i>
                        <i class="fa fa-star-o fa-fade"> </i>
                        <i class="fa fa-star-o fa-fade"> </i>
                        <i class="fa fa-star-o fa-fade"> </i>
                    </div>
                    <div id="nombre-producto">
                       {{$producto->nombre_tipo_producto}} {{$producto->nombre_marca}} {{$producto->nombre_modelo}} {{$producto->especificaciones}}
                    </div>

                    
                    <div id="content-precios">
                        @if(isset($producto->nombre_promocion))
                            <div class="precios precio-oferta" id="precio-oferta">
                                {{number_format($producto->precio_promocion, 2, ',', '.')}} &nbsp;$
                                <div id="nombre-promocion">
                                    {{$producto->porcentaje_descuento}} % {{$producto->nombre_promocion}}
                                </div> 
                            </div>
                            <div class="precios" id="precio-real">
                                <div id="opcion-precio" onclick="editarPrecio();"><i class="icon-pencil"> </i></div>
                                <div id="cambiar-precio" onclick="actualizarPrecio();">OK</div>
                                <div id="circulo-espera" class="circulo-espera"><i class="fa fa-repeat"></i></div>
                                <input type="text" class="tachado" name="precio_producto" id="precio_producto" value="{{number_format($producto->precio_base, 2, ',', '.')}}" disabled>
                                <div id="pesos" class="tachado">$</div>
                            </div>
                        @else
                            <div class="precios" id="precio-oferta">
                                <div id="opcion-precio" onclick="editarPrecio();"><i class="icon-pencil"> </i></div>
                                <div id="cambiar-precio" onclick="actualizarPrecio();">OK</div>
                                <div id="circulo-espera" class="circulo-espera"><i class="fa fa-repeat"></i></div>
                                <input type="text" name="precio_producto" id="precio_producto" value="{{number_format($producto->precio_base, 2, ',', '.')}}" disabled>
                                <div id="pesos">$</div>
                            </div>
                        @endif
                        <div id="mensaje-exito" onclick="this.style.display='none'" style="float: left;width: 100%;text-align: center;cursor: pointer;"> <i class="fa fa-check-circle"></i> Precio Actualizado con Éxito</div>
                        <div id="mensaje-exito-marca" class="mensaje-exito" onclick="this.style.display='none'" style="float: left;width: 100%;text-align: center;cursor: pointer;"> <i class="fa fa-check-circle"></i> Marca Actualizada con Éxito</div>
                        <div id="mensaje-exito-modelo" class="mensaje-exito" onclick="this.style.display='none'" style="float: left;width: 100%;text-align: center;cursor: pointer;"> <i class="fa fa-check-circle"></i> Modelo Actualizado con Éxito</div>

                        <div class="marca-modelo">
                            <div class="titulo-marca-modelo">Marca: </div>
                            <input id="input-marca" class="input-marca-modelo" value="{{$producto->nombre_marca}}" autocomplete="off" spellcheck="false" disabled id="input-marca">
                            <input type="hidden" name="id_marca" value="{{$producto->id_marca}}">
                        </div>
                        <div class="opcion-marca-modelo" onclick="editarMarca();" id="opcion-marca"><i class="icon-pencil"> </i></div>
                        <div id="ok-marca" class="ok-marca-modelo" onclick="actualizarMarca();">OK</div>
                        <div id="circulo-espera-marca" class="circulo-espera"><i class="fa fa-repeat"></i></div>

                        <div class="marca-modelo">
                            <div class="titulo-marca-modelo">Modelo: </div>
                            <input id="input-modelo" class="input-marca-modelo" value="{{$producto->nombre_modelo}}" autocomplete="off" spellcheck="false" disabled id="input-modelo">
                            <input type="hidden" name="id_modelo" value="{{$producto->id_modelo}}">
                        </div>
                        <div class="opcion-marca-modelo" onclick="editarModelo();" id="opcion-modelo"><i class="icon-pencil"> </i></div>
                        <div id="ok-modelo" class="ok-marca-modelo" onclick="actualizarModelo();">OK</div>
                        <div id="circulo-espera-modelo" class="circulo-espera"><i class="fa fa-repeat"></i></div>
                    </div>
                    <div style="width: 100%;float: left;font-size: 14px;padding-top: 10px;">
                        <form method="post" data-parsley-validate action="{{ route('productos.imagen.cargar') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="label-campo" style="width: 100%;">
                                <label class="label-admin">Subir Imagen <i class="icon-camera"></i></label>
                                <input class="campo-admin" type="file" name="imagen_producto" required accept=".jpg,.jpeg,.png">
                            </div>
                            <div style="width: 100%;float: left;padding: 0px 20px 0px 20px;">
                                <input type="hidden" name="id_producto" value="{{$producto->id_producto}}">
                                <div style="float: left;width: 100%;margin-bottom: 10px;">
                                    <button class="boton-admin">Subir imágen</button>
                                </div>
                            </div>
                        </form>
                        <div style="width: 100%;float: left;margin-bottom: 10px;padding-left: 20px;font-weight: bold;">Imágenes del producto:</div>
                        <div style="padding:20px;">
                            @foreach($imagenes_producto as $imagen_producto)
                                <div class="imagenes-producto" style="background-image: url({{ asset('public/imagenes/sistema/productos') }}/{{$imagen_producto->nombre_imagen}});">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            <div id="contenedor-informacion-cliente">
                <div id="caja-informacion-cliente">
                    <div class="titulo-venta">
                        Lista De Especificaciones
                    </div>
                    <div id="informacion-cliente">
                        <table id="tabla" class="display compact cell-border stripe" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 20px;text-align: center;">N°</th>
                                    <th>Clasificación</th>
                                    <th>Especificación</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($producto_especificaciones as $producto_especificacion)
                                    <tr>
                                        <td style="width: 20px;text-align: center;">{{$loop->iteration}}</td>
                                        <td>{{$producto_especificacion->nombre_clasificacion}}</td>
                                        <td>{{$producto_especificacion->nombre_especificacion}}</td>
                                        <td class="td-acciones">
                                            <div class="iconos-acciones">
                                                <div class="content-acciones">
                                                    <a class="dropdown-content"><i class="icon-pencil"> </i> CAMBIAR</a>
                                                    <i onclick="editar('{{$producto_especificacion->id_producto_especificaciones}}');" class="icon-pencil i-acciones"> </i> &nbsp;
                                                </div>
                                            </div>
                                        </td>
                                    </tr> 
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="padding-left: 20px;float: left;width: 100%;">Enlace para promocionar este producto en redes sociales: <br><br><b>{{ route('ecommerce.articulo2') }}/{{$producto->id_producto}}</b></div>
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

    function precioNumero(string){
        var out = '';
        var filtro = '1234567890,';//Caracteres validos
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }

    function editarPrecio(){
        document.getElementById("precio_producto").disabled = false;
        var monto_precio = document.getElementById("precio_producto").value;
        monto_precio = precioNumero(monto_precio);
        document.getElementById("precio_producto").value = monto_precio;
        document.getElementById("precio_producto").focus();
        document.getElementById("opcion-precio").style.display = "none";
        document.getElementById("cambiar-precio").style.display = "block";
        document.getElementById("mensaje-exito").style.display = "none";
    }

    function actualizarPrecio(){

        document.getElementById("cambiar-precio").style.display = "none";
        document.getElementById("circulo-espera").style.display = "block";

        var monto_precio = document.getElementById("precio_producto").value;
        monto_precio = parseFloat(monto_precio);

        var url="{{route('productos.editar.precio')}}";
        var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id_producto": {{$producto->id_producto}},
            "precio": monto_precio
        };
        $.ajax({
            type: 'POST',
            url: url,
            data: datos,
            success: function(data) {
                console.log("success");
                console.log(data);
                document.getElementById("circulo-espera").style.display = "none";
                document.getElementById("opcion-precio").style.display = "block";
                document.getElementById("mensaje-exito").style.display = "block";
                document.getElementById("precio_producto").disabled = true;
            },
            error: function(data) {
                console.log("error");
            }
        });
    }

    function editarMarca(){
        console.log("editar marca");
        document.getElementById("input-marca").disabled = false;
        document.getElementById("opcion-marca").style.display = "none";
        document.getElementById("ok-marca").style.display = "block";
        document.getElementById("input-marca").focus();
        document.getElementById("mensaje-exito-marca").style.display = "none";
    }

    function actualizarMarca(){
        
        document.getElementById("ok-marca").style.display = "none";
        document.getElementById("circulo-espera-marca").style.display = "block";

        var marca = document.getElementById("input-marca").value;
        
        var url="{{route('productos.editar.marca')}}";
        var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id_producto": {{$producto->id_producto}},
            "id_marca": {{$producto->id_marca}},
            "nombre_marca": marca,
            "id_tipo_producto": {{$producto->id_tipo_producto}},
            "id_modelo": {{$producto->id_modelo}}
        };
        $.ajax({
            type: 'POST',
            url: url,
            data: datos,
            success: function(data) {
                console.log("success");
                console.log(data);
                document.getElementById("circulo-espera-marca").style.display = "none";
                document.getElementById("opcion-marca").style.display = "block";
                document.getElementById("mensaje-exito-marca").style.display = "block";
                document.getElementById("input-marca").disabled = true;
            },
            error: function(data) {
                console.log("error");
            }
        });
    }



    function editarModelo(){
        console.log("editar modelo");
        document.getElementById("input-modelo").disabled = false;
        document.getElementById("opcion-modelo").style.display = "none";
        document.getElementById("ok-modelo").style.display = "block";
        document.getElementById("input-modelo").focus();
        document.getElementById("mensaje-exito-modelo").style.display = "none";
    }

    function actualizarModelo(){
        
        document.getElementById("ok-modelo").style.display = "none";
        document.getElementById("circulo-espera-modelo").style.display = "block";

        var modelo = document.getElementById("input-modelo").value;
        
        var url="{{route('productos.editar.modelo')}}";
        var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id_producto": {{$producto->id_producto}},
            "id_marca": {{$producto->id_marca}},
            "id_modelo": {{$producto->id_modelo}},
            "nombre_modelo": modelo,
            "id_tipo_producto": {{$producto->id_tipo_producto}}
        };
        $.ajax({
            type: 'POST',
            url: url,
            data: datos,
            success: function(data) {
                console.log("success");
                console.log(data);
                document.getElementById("circulo-espera-modelo").style.display = "none";
                document.getElementById("opcion-modelo").style.display = "block";
                document.getElementById("mensaje-exito-modelo").style.display = "block";
                document.getElementById("input-modelo").disabled = true;
            },
            error: function(data) {
                console.log("error");
            }
        });
    }

    
</script>

<style type="text/css">
    #contenedor-producto{
        width: 350px;
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
        margin-bottom: 15px;
    }
    #content-precios{
        width: 100%;
        //height: 40px;
        float: left;
        /*border:1px solid pink;*/
        padding:0px 20px 0px 20px;
    }
    .precios{
        width: 100%;
        float: right;
        text-align: right;
        font-weight: bold;
        font-size: 20px;
        letter-spacing: -0.5px!important;
        /*border:1px solid blue;*/
        padding:0px;
        line-height: 18px;
        height: 40px;
    }
    #precio-real{
        text-decoration:line-through;
        font-weight: 400;
        text-align: right;
        color: rgba(50,50,50,0.7);
    }

    #nombre-promocion{
        width: 100%;
        float: right;
        font-size: 12px;
        /*border:1px solid orange;*/
        background-color: white;
        line-height: 14px;
        letter-spacing: -0.5px!important;
        color: rgba(50,50,50,0.7);
        text-align: right;
    }

    #opcion-precio{
        width: 30px;
        float: left;
        /*border: 1px solid green;*/
        height: 30px;
        text-align: center;
        padding-top: 6px;
        cursor: pointer;
        color: rgba(52,58,64,1);
    }

    #cambiar-precio{
        width: 30px;
        float: left;
        display: none;
        font-size: 16px;
        height: 30px;
        text-align: center;
        padding-top: 6px;
        cursor: pointer;
        color: rgba(52,58,64,1);
    }

    #contenedor-informacion-cliente{
        width: calc(100% - 370px);
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

    #precio_producto{
        float: left;
        width: calc(100% - 45px);
        font-size: 20px;
        font-weight: bold;
        padding-right: 5px;
        color: rgba(52,58,64,1);
        border:1px solid white;
        text-align: right;
        letter-spacing: -0.5px!important;
        background-color: white;
    }
    #pesos{
        width: 15px;
        float: left;
        text-align: center;
        color: rgba(52,58,64,1);
        /*border: 1px solid red;*/
        padding-top: 5px;
    }

    .precio-oferta{
        padding-top: 5px;
    }

    .tachado{
        text-decoration:line-through;
        color: rgba(180,5,0,0.5)!important;
    }

    /*input:focus{
       border:0px solid rgba(52,58,64,0.5)!important;
    }*/

    .circulo-espera{
        width: 30px;
        float: left;
        display: none;
        text-align: center;
        color:rgba(13,180,61,1);
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #mensaje-exito,#mensaje-exito-marca,#mensaje-exito-modelo{
        display: none;
    }

    .marca-modelo{
        width: calc(100% - 30px);
        float: left;
        border-bottom: 1px solid rgba(52,58,64,0.4);
        height: 35px;
    }

    .opcion-marca-modelo{
        width: 30px;
        float: left;
        text-align: center;
        border-bottom: 1px solid rgba(52,58,64,0.4);
        height: 35px;
        padding-top: 8px;
        cursor: pointer;
    }

    .titulo-marca-modelo{
        width: 70px;
        float: left;
        font-weight: bold;
        /*border:1px solid red;*/
        height: 35px;
        padding-top: 5px;
    }

    .input-marca-modelo{
        float: left;
        width: calc(100% - 70px);
        font-size: 16px;
        padding-left: 5px;
        color: rgba(52,58,64,1);
        border:1px solid white;
        text-align: left;
        letter-spacing: -0.5px!important;
        background-color: white;
        height: 34px;
    }

    .ok-marca-modelo{
        width: 30px;
        float: left;
        display: none;
        font-size: 16px;
        height: 35px;
        text-align: center;
        padding-top: 6px;
        cursor: pointer;
        color: rgba(52,58,64,1);
        font-weight: bold;
        border-bottom: 1px solid rgba(52,58,64,0.4);
        letter-spacing: -0.5px!important;
    }

    .imagenes-producto{
        width: 50%;
        float: left;
        height: 150px;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 80%;
        border:1px solid rgba(215,215,215,0.9);
    }
</style>

@endsection




