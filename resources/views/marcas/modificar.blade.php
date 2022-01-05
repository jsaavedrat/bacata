@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-bookmarks"> </i> MARCAS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; EDITAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('marcas.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE MARCAS</p></div></a></div>
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
<div id="elemento-admin" class="editar-marca">

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Editar Marca {{$marca->nombre_marca}}</div>

    <form method="post" data-parsley-validate action="{{ route('marcas.modificar') }}">
        @csrf
        <div class="label-campo">
            <label class="label-admin" for="nombre_marca"><i id="lista" class="icon-dot-single"></i>Nombre Marca Producto<i id="cont-icon" class="fa fa-language"></i></label>
            <input type="text" name="nombre_marca" class="campo-admin" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="25" id="nombre_marca" onkeyup="this.value=letras(this.value)" value="{{$marca->nombre_marca}}">
        </div>
        <div id="error-nombre-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Escribe más letras en el Nombre</div>


        <div class="label-campo"  style="cursor: initial!important;">
            <label class="label-admin" id="aux" style="cursor: initial!important;margin-bottom:1%;"><i id="lista" class="icon-dot-single"></i>Agregar productos: <i id="cont-icon" class="icon-check"></i></label>
             @foreach($productos as $producto)
                <label class="label-check" id="label-check{{$loop->iteration}}">
                    <p><i class="icon-check icon-ch" id="icon-check{{$loop->iteration}}"></i>{{$producto->nombre_tipo_producto}}</p>
                    <input type="checkbox" id="campo-check{{$loop->iteration}}" class="campo-check" value="{{$producto->id_tipo_producto}}" onclick="seleccionar('label-check{{$loop->iteration}}','campo-check{{$loop->iteration}}','icon-check{{$loop->iteration}}');">
                </label>
            @endforeach
        </div>

        <div id="error-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Seleccione al menos un tipo de Producto</div>

        <input type="hidden" name="id_marca" value="{{$marca->id_marca}}">

        <input type="hidden" name="tipo_productos" id="tipo_productos" multiple="multiple">

        <button class="boton-admin" id="crear-marca"> Editar Marca</button>
    </form>
</div>

<div id="elemento-admin" class="lista-marca">
    <div id="texto-titulo"> <i class="icon-list"> </i> Productos de {{$marca->nombre_marca}}</div>
    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

            @foreach($tipo_producto_marcas as $tipo_producto_marca)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$tipo_producto_marca->nombre_tipo_producto}}</td>
                    <td class="td-acciones">
                        <div class="iconos-acciones">
                            <div class="content-acciones">
                                <a href="" class="dropdown-content"><i class="icon-forward"> </i> VER</a>
                                <i onclick="ver('{{$tipo_producto_marca->id_tipo_producto}}');" class="icon-forward i-acciones"> </i> &nbsp;
                            </div>
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-pencil"> </i> EDITAR</a>
                                <i onclick="editar('{{$tipo_producto_marca->id_tipo_producto_marcas}}');" class="icon-pencil i-acciones"> </i> &nbsp;
                            </div>
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-trash"> </i> ELIMINAR</a>
                                <i onclick="eliminar('{{$tipo_producto_marca->id_tipo_producto_marcas}}','{{$tipo_producto_marca->nombre_tipo_producto}}');" class="icon-trash i-acciones"></i>
                            </div>
                        </div>
                    </td>
                </tr> 
            @endforeach

        </tbody>
    </table>
</div>

<div id="modal-eliminar">
    <form method="post" action="{{ route('tipo_productos.inactivar') }}">
        @csrf
        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">¿Realmente deseas eliminar el tipo de producto: <x id="nombre-eliminar"></x></div>
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

<form method="post" action="{{ route('tipo_productos.editar') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>

<form method="post" action="{{ route('tipo_productos.productos') }}">
    @csrf
    <input type="number" name="ver" id="ver" style="display: none;">
    <button id="boton-ver"></button>
</form>

<style type="text/css">
    .editar-marca{
        width: 33.3%!important;
        //border:1px solid red;
        margin-top: 20px!important;
        margin-left:0px!important;
        padding-left: 10px!important;
        /*margin-top:16vh!important;
        float: none;
        max-width: 450px;
        margin-left: auto;
        margin-right: auto;
        left:0;
        right: 0;
        overflow: hidden;
        border:1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 60px rgba(100,100,120,0.3);
        padding-bottom:20px;
        background-color: rgba(225,225,235,0.2);*/
    }
    .lista-marca{
        width:66.7%!important;
        //border:1px solid blue;
        margin-top: 20px!important;
        margin-left:0px!important;
        padding-left: 20px!important;
        padding-right: 30px!important;
    }
    .label-campo{
        width: 100%;
    }
    .boton-admin{
        margin-top: 20px;
        width:calc(100% - 40px);
        margin-left:20px;
    }
    .label-campo{
        padding: 10px 20px 10px 20px;
    }
    #iconos-titulo{
        box-shadow: 0px 0px 0px white;
    }

    .label-check{
        width:31.3%;
        height: 35px;
        float: left;
        margin:1%;
        border:1px solid rgba(200,200,200,1);
        border-radius: 4px;
        //background-color: rgba(255,255,255,0.8);
        text-align: center;
        font-size: 13px;
        padding-top: 7px;
        cursor: pointer;
        letter-spacing: -0.4px;
    }

    .icon-ch{
        display: none;
    }

    .campo-check{
        display: none;
    }

    #error-vacio,#error-nombre-vacio,#error-categoria-vacio{
        display: none;
        padding: 8px;
        background-color:rgba(230,0,0,0.8);
        color:white;
        font-weight: 600;
        letter-spacing: -0.5px;
        border-radius: 2px;
        margin-bottom: 2px;
        font-size:15px;
        float: left;
        width: calc(100% - 40px);
        margin-left: 20px;
        cursor: pointer;
    }
    option{
        font-size:14px;
    }

</style>

<script type="text/javascript">

    function letras(string){//Solo letras
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ 1234567890-+#/.,';//Caracteres validos
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);

        var str = "";

        for(i = 0; i < out.length; i++){
            if(i == 0){
                if(out[i] != " "){
                    str = str + out[i].toUpperCase();
                }else{
                    str = "";
                }
            }else{
                if(out[i-1] == " "){
                    str = str + out[i].toUpperCase();
                }else{
                    str = str + out[i];
                }
            }
        }
        out = str;

        if(out!=""){
            document.getElementById("aux").innerHTML = "<i id='lista' class='icon-dot-single'></i>"+out+" Agregar:"+"<i id='cont-icon' class='icon-check'></i>";
            //document.getElementById("label_categoria_tipo_producto").innerHTML = "<i id='lista' class='icon-dot-single'></i>"+out+" pertenece a la Categoría:"+"<i id='cont-icon' class='icon-colours'></i>";
        }else{
            //document.getElementById("label_categoria_tipo_producto").innerHTML = "<i id='lista' class='icon-dot-single'></i>Categoría<i id='cont-icon' class='icon-credit-card'></i>";
            document.getElementById("aux").innerHTML = "<i id='lista' class='icon-dot-single'></i>Agregar Productos:<i id='cont-icon' class='icon-check'></i>";
        }
       
                
        return out;
    }

    function seleccionar(label,check,icon){
        var estatus = document.getElementById(check).checked;
        if(estatus==true){
            document.getElementById(label).style.backgroundColor="rgba(255,255,255,1)";
            document.getElementById(label).style.color="rgba(50,50,50,1)";
            document.getElementById(label).style.boxShadow="0px 0px 10px rgba(50,50,50,0.3)";
            document.getElementById(icon).style.display="inline";
        }else{
            document.getElementById(label).style.backgroundColor="rgba(0,0,0,0)";
            document.getElementById(label).style.color="rgba(50,50,50,1)";
            document.getElementById(label).style.boxShadow="0px 0px 0px rgba(50,50,50,0)";
            document.getElementById(icon).style.display="none";
        }
    }


    $("#crear-marca").click(function(){

        document.getElementById("error-nombre-vacio").style.display = "none";
        document.getElementById("error-vacio").style.display = "none";

        var valid = true;
        var tipo_productos=[];

        var nombre = document.getElementById("nombre_marca").value;
        if(nombre.length < 3){
            document.getElementById("error-nombre-vacio").style.display = "inline";
            document.getElementById('nombre_marca').focus();
            valid = false;
        }

        var n = document.getElementsByClassName('campo-check')

        var c = 0;
        for (i = 0; i < n.length; i++) {
            var x = document.getElementsByClassName("campo-check")[i].checked;
            if(x == true){
                var y = document.getElementsByClassName("campo-check")[i].value;
                tipo_productos.push(y);
                c = c + 1;
            }
        }

        if(c < 1){
            //document.getElementById("error-vacio").style.display = "inline";
            //valid = false;
        }else{
            vector = JSON.stringify(tipo_productos);
            document.getElementById('tipo_productos').value=vector;
        }

        


        return valid;
    });


    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Productos {{$marca->nombre_marca}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Productos {{$marca->nombre_marca}}',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Productos {{$marca->nombre_marca}}',
                    filename: 'Productos {{$marca->nombre_marca}}',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Productos {{$marca->nombre_marca}}',
                },
            ],
            filename: 'Data export',            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Productos&nbsp;:",
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

</script>

@endsection


