<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <div class="fondo"></div>

    <header>
        <div class="contenedor-imagen-sucursal">
            <img src="imagenes/sistema/cliente_empresa/{{$cliente_appweb->nombre_imagen_cliente}}" class="imagen-sucursal">
        </div>
        <div class="contenedor-numero-examen">
            <img src="imagenes/sistema/empresas_envio/{{$empresa_envio->imagen_empresa_envio}}" class="imagen-sucursal" style="width: 5cm;">
            <div style="width: 100%;">{{$empresa_envio->nombre_empresa_envio}}</div>
        </div>
        <div class="titulos-examen" style="text-align: left!important;margin-left: 1cm!important;">{{$cliente_appweb->nombre_cliente_sas}}</div>
    </header>

    <main>
        <div class="titulos-examen" style="text-align: center; margin-top: 1cm!important;">
            <b>
                <h3>ENVÍO</h3>
            </b>
        </div>
        <div class="titulos-examen"><b>RECEPTOR:</b></div>
        <table>
            <tr>
                <td>
                    <div class="label-celda">NOMBRES Y APELLIDOS</div>
                    <div class="valor-celda">{{$request->nombre_cliente}}</div>
                </td>
                <td>
                    <div class="label-celda">IDENTIFICACIÓN</div>
                    <div class="valor-celda">{{$request->identificacion_cliente}}</div>
                </td>
                <td>
                    <div class="label-celda">TELÉFONO</div>
                    <div class="valor-celda">{{$request->telefono_cliente}}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label-celda">DIRECCIÓN DE ENTREGA</div>
                    <div class="valor-celda">{{$request->direccion_cliente}}</div>
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br>
        <div class="titulos-examen"><b>QUIEN ENVÍA:</b></div>
        <table>
            <tr>
                <td>
                    <div class="label-celda">EMPRESA</div>
                    <div class="valor-celda">{{$cliente_appweb->nombre_cliente_sas}}</div>
                </td>
                <td>
                    <div class="label-celda">NIT</div>
                    <div class="valor-celda">{{$cliente_appweb->nit_empresa}}</div>
                </td>
                <td>
                    <div class="label-celda">TELÉFONO</div>
                    <div class="valor-celda">{{$cliente_appweb->telefono_contacto}}</div>
                </td>
            </tr>
        </table>
    </main>

    <footer>
        <br><br>
        www.{{$cliente_appweb->dominio}}<br>{{$cliente_appweb->nombre_cliente_sas}} {{$cliente_appweb->nit_empresa}}<br> {{$cliente_appweb->pie_pagina}}
    </footer>
</body>
<style>

    /*::::::::::::::::::::generales::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::::::*/

    @page {
        margin: 0cm 0cm;
        font-family: "sans-serif";
    }

    * {
        font-family: "sans-serif";
        box-sizing: border-box;
        color: #282828;
    }

    body {
        margin: 3cm 1cm 2cm 1cm;
        padding:0px;
        z-index: 1;
        background-image: url('imagenes/sistema/cliente_empresa/{{$cliente_appweb->nombre_imagen_cliente}}');
        background-repeat: no-repeat;
        background-position: center;
        background-size: 50%;
        z-index: 1;
    }

    .fondo {
        width: 100%;
        background-color: rgba(255,255,255,0.8);
        margin:0px;
        padding:0px;
        position: fixed;
        height: 200%;
        z-index: -1;
        /*border:2px solid green;*/
    }

    /*:::::::::::::::::::::header::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::::::*/

    header {
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 3cm;
        text-align: center;
        /*border:2px solid orange;*/
    }

    .contenedor-imagen-sucursal {
        width: 50%;
        height: 3cm;
        float: left;
        /*border: 2px solid gray;*/
    }

    .imagen-sucursal {
        height: 3cm;
        margin-top:0.5cm;
    }

    .contenedor-numero-examen {
        width: 50%;        float: left;
        height: 3cm;
    }

    .historia {
        width: 100%;
        height: 1cm;
        margin-top: 0.5cm;
        line-height: 0.9cm;
        padding-top: 0px;
        text-align: right;
    }

    .numero-examen {
        margin-top: 0.5cm;
        color: red;
        height: 1cm;
        text-align: right;
        padding-right: 1cm;
        font-size: 0.7cm;
        line-height: 1cm;
        width: 100%;
    }

    .fecha-examen {
        height: 1cm;
        /*border:1px solid pink;*/
        margin-bottom: 0.5cm;
        text-align: right;
        padding-right: 1cm;
        line-height: 1cm;
    }

    /*:::::::::::::::::::::cuerpo::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::::::*/

    main {
        z-index: 2;
        padding:0px;
        /*border: 1px solid #282828;*/
    }

    .contenedor-informacion-paciente {
        width: 100%;
        border-bottom: 1px solid red;
        height: 2.4cm;
    }

    .titulos-examen {
        text-transform: uppercase;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border:1px solid #282828;
    }

    td{
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .label-celda {
        width: 100%;
        /*border: 1px solid blue;*/
        font-size: 11px;
        padding-left: 2px;
    }

    .valor-celda {
        width: 100%;
        font-weight: bold;
        font-size: 13px;
        padding-left: 2px;
    }

    .content-resultado {
        width: 100%;
        /*border: 2px solid #282828;*/
    }

    #tabla-optometria {
        margin: -1px;
    }

    #tabla-ojos {
        margin: -1px;
        padding-left: 20px;
    }

    .td-optometria {
        width: 28%;
        border: 0px!important;
    }

    .td-ojos {
        width: 72%;
        border: 0px!important;
    }

    .td-ojo {
        width: 50%!important;
        height: 0.8cm;
    }

    .tabla-rx {
        margin: -1px;
        border: 0px;
    }

    .tabla-rx tr, .tabla-rx td{
        border: 0px;
        text-align: center;
        /*font-weight: bold;*/
    }

    .tabla-historia tr, .tabla-historia td {
        border: 0px;
    }

    .rx {
        font-weight: bold;
    }

    .td-firma {
        height: 3cm;
        width: 50%;
    }

    .firma {
        width: 60%;
        margin-left: 20%;
        border-bottom: 1px solid #282828;
    }

    .nombres {
        text-align: center;
        text-transform: uppercase;
        font-size: 12px;
        padding-top: 0.5cm;
    }

    .cedulas {
        text-align: center;
        font-size: 12px;
    }


    /*:::::::::::::::::::::footer::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::::::*/

    footer {
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        height: 2cm;
        text-align: center;
        font-size: 12px;
        line-height: 12px;
    }

</style>
</html>