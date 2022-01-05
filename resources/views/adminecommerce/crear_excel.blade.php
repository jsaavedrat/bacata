@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-shop"> </i> TIENDA VIRTUAL&nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CREAR EXCEL PARA SUBIR PRODUCCTOS</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('adminecommerce.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">INVENTARIO TIENDA</p></div></a></div>
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

    <div id="texto-titulo"> <i class="fa fa-file-excel-o"> </i> Crear Formato Excel Para Subir Productos</div>

    <div class="label-campo">
        <div id="error-tipo-producto-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Seleccione que tipo de producto es</div>
        <div class="label-admin" id="label_tipo_producto"><i id="lista" class="icon-dot-single"></i>Generar formato EXCEL de productos<i id="cont-icon" class="fa fa-file-excel-o"></i></div>
        <select class="campo-admin" name="tipo_producto" id="tipo_producto" required onchange="marcasTipoProductos(this.value);">
            <option value="">Seleccione producto para generar EXCEL</option>
             @foreach($tipo_productos as $tipo_producto)
                <option value="{{$tipo_producto->id_tipo_producto}}">{{$tipo_producto->nombre_tipo_producto}}</option>
            @endforeach
        </select>
    </div>
</div>
<div id="content-tabla-excel">

</div>

<button class="boton-admin" id="crear-excel"> Descargar Excel</button>
<style type="text/css">

    #agregar-mas{
        float: left;
        padding:10px;
        font-size: 13px;
        background-color: rgba(0,200,200,1);
        color:white;
        font-weight: 500;
        line-height: 12px;
        margin-left: 20px;
        text-shadow: 0px 0px 2px rgba(50,50,50,0.5);
        cursor:pointer;
        display: none;
    }

   #error-tipo-producto-vacio,#error-marca-vacio{
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
        cursor: pointer;
        width: 100%;
    }
    option{
        font-size:14px;
    }
    .label-modelo{
        width: calc(100% - 38px);
    }
    .cerrar-campo{
        height: 36px;
        width: 38px;
        color:rgba(200,50,50,1);
        float: right;
        text-align: center;
        z-index:1000!important;
        padding-top:8px;
        //background-color: rgba(255,255,255,0.8);
        background-color: rgba(220,220,180,0.5);
        border-right: 1px solid rgba(215,215,200,0.7);
        border-top: 1px solid rgba(215,215,200,0.7);
        border-bottom: 1px solid rgba(215,215,200,0.7);
        cursor: pointer;
    }.cerrar-campo:hover{
        color:rgba(200,50,50,1);
        font-size:17px;
    }

    #content-tabla-excel{
        width: 100%;
        padding:0px 20px 20px 20px;
        float: left;
    }

    #clonar-hijo{
        width: 100%;
        float: left;
        display: none;
    }
    tr{
        background-color:white!important;
        font-size:calc(4px + 1.3vh)!important;
        padding:calc(3px + 1.3vh)!important;
    }
    #tablaexcel{
        border-collapse: collapse!important;
        width:100%;
        float: left;
    }
    th{
        border: 1px solid rgba(215,215,215,0.6)!important;
        padding:10px 2px 10px 2px!important;
        font-size:calc(4px + 1.3vh)!important;
        padding:calc(3px + 1.3vh)!important;
    }

    td{
        border: 1px solid rgba(215,215,215,0.6)!important;
    }

    #crear-excel{
        display: none;
        float: none;
        margin-right: auto;
        margin-left: auto;
        left:0;
        right: 0;
        width: 100%;
        max-width: 300px;
        overflow: hidden;
        margin-bottom:20px;
    }
    .vista-previa{
        margin:0px 0px 20px 0px;
    }

    #tablaexcel caption button{
        display: none!important;
    }

</style>


<!--<script type="text/javascript" src="{{ asset('public/js/jquery.table2excel.min.js') }}"></script>-->
<script src="{{ asset('public/js/excel/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('public/js/excel/FileSaver.min.js') }}"></script>
<script src="{{ asset('public/js/excel/Blob.min.js') }}"></script>
<script src="{{ asset('public/js/excel/xls.core.min.js') }}"></script>
<script src="{{ asset('public/js/excel/dist/js/tableexport.js') }}"></script>

<script type="text/javascript">

    function marcasTipoProductos(id){

        document.getElementById("crear-excel").style.display = "none";

        var tipoX = document.getElementById("tipo_producto");
        var tipo = tipoX.options[tipoX.selectedIndex].innerText;
        
        var url="{{route('ingresos.sucursal.excel')}}";
        var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id_tipo_producto": id
        };
        $.ajax({
            type: 'GET',
            url: url,
            data: datos,
            success: function(data) {

                console.log("success");
                var datos = JSON.parse(data);


                $('#content-tabla-excel').empty();
                $('#content-tabla-excel').append(`
                    <div id="texto-titulo" class="vista-previa"> <i class="fa fa-file-excel-o"> </i>&nbsp; Vista Previa EXCEL</div>
                    <table id="tablaexcel">
                        <thead>
                            <tr id="trtabla">
                            </tr>
                        </thead>
                        <tbody id="tbodyexcel">
                        </tbody>
                    </table>
                `);

                for (var i = 0; i < datos.titulos.length; i++) {
                    $("#trtabla").append(`
                            <th>`+datos.titulos[i]+`</th>
                    `);
                }


                if(datos.marcas.length != 0){

                    for (var i = 0; i < datos.marcas.length; i++) {

                            // $("#tbodyexcel").append(`
                            //     <tr id="tr-`+i+`"></tr>
                            // `);

                            // for(var j = 0; j < datos.titulos.length; j++){

                            //         if(j == 1){
                            //             $("#tr-"+i).append(`
                            //                     <td>`+datos.marcas[i].nombre_marca+`</td>
                            //             `);
                            //         }else if(j==0 && i==0){
                            //             $("#tr-"+i).append(`
                            //                     <td>`+datos.tipo_producto+`</td>
                            //             `);
                            //         }else{
                            //             $("#tr-"+i).append(`
                            //                 <td></td>
                            //             `);
                            //         }
                            // }
                    }
                }else{
                    $("#tbodyexcel").append(`
                        <tr id="tr-unico"></tr>
                    `);

                    for (var i = 0; i < datos.titulos.length; i++) {
                        if(i==0){
                            $("#tr-unico").append(`
                                    <td>`+datos.tipo_producto+`</td>
                            `);
                        }else{
                            $("#tr-unico").append(`
                                <td></td>
                            `);
                        }
                    }
                }
                document.getElementById("crear-excel").style.display = "block";
                $("#tablaexcel").tableExport({
                    formats: ["xlsx"], //Tipo de archivos a exportar ("xlsx","txt", "csv", "xls")
                    position: 'button',  // Posicion que se muestran los botones puedes ser: (top, bottom)
                    bootstrap: false,//Usar lo estilos de css de bootstrap para los botones (true, false)
                    fileName: tipo,    //Nombre del archivo 
                });
            },
            error: function(data) {
                console.log("error"); 
            }
        });
    }


    $("#crear-excel").click(function(){

        document.getElementById("error-tipo-producto-vacio").style.display = "none";

        var valid = true;
        var tipo_producto = document.getElementById("tipo_producto").value;

        if (tipo_producto == "") {
            document.getElementById("error-tipo-producto-vacio").style.display = "block";
            valid = false;
        }



        if(valid == true){/*
            $("#tablaexcel").table2excel({
                exclude: ".excludeThisClass",
                name: "Worksheet Name",
                filename: "products.xls", // do include extension
                preserveColors: false // set to true if you want background colors and font colors preserved
            });*/

            document.getElementsByTagName("button")[0].click();
            //alert(cant.length);
        }

        return valid;


    });

</script>

@endsection


