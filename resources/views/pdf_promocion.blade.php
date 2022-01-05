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
            <div class="fecha-examen"><br><br>Fecha de Obsequio: {{date("d-m-Y g:i a", strtotime($fecha_promocion))}}</div>
        </div>
        <br>
        <div class="titulos-examen" style="text-align: left!important;margin-left: 1cm!important;">{{$cliente_appweb->nombre_cliente_sas}}</div>
    </header>
    
    <main>
        <div class="titulos-examen" style="text-align: center; margin-top: 1cm!important;">
            <b>
                <h3>FELICIDADES !!!</h3>
                <h2>{{$promocion_pagina->resultado_promocion}}</h2>
            </b>
            <br>
        </div>
        <div class="titulos-examen"><b>PRESENTA ESTE COMPROBANTE Y TU DOCUMENTO PARA REDIMIR EL OBSEQUIO / PROMOCIÓN</b></div>
        <table>
            <tr>
                <td>
                    <div class="label-celda">NOMBRES Y APELLIDOS</div>
                    <div class="valor-celda">{{$cliente->nombres}} {{$cliente->apellidos}}</div>
                </td>
                <td style="width: 2.5cm;">
                    <div class="label-celda">IDENTIFICACIÓN</div>
                    <div class="valor-celda">{{$cliente->identificacion}}</div>
                </td>
                <td style="width: 2.5cm;">
                    <div class="label-celda">TELÉFONO</div>
                    <div class="valor-celda">{{$cliente->telefono}}</div>
                </td>
                <td style="width: 7cm;">
                    <div class="label-celda">CORREO ELECTRÓNICO</div>
                    <div class="valor-celda">{{$cliente->email}}</div>
                </td>
            </tr>
        </table>
        <h4>{{$promocion_pagina->texto_banner}} {{$promocion_pagina->texto_banner_2}}</h4>
        <img src="imagenes/pagina/promociones/{{$promocion_pagina->imagen_promocion_pagina}}" style="width: 100%;">
        <h5>Acércate a cualquiera de nuestras sucursales y solicita tu premio. &nbsp; APLICAN TÉRMINOS Y CONDICIONES.</h5>
    </main>

    <footer>
        <br><br>
        www.{{$cliente_appweb->dominio}} {{$cliente_appweb->pie_pagina}} <br> {{$cliente_appweb->nombre_cliente_sas}}<br> Ciertas condiciones aplican para obtener el obsequio / promoción.
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

    .contenedor-imagen-promocion{
        width: 100%;
        height: 15cm;
        background-image: url('imagenes/pagina/promociones/{{$promocion_pagina->imagen_promocion_pagina}}');
        background-repeat: no-repeat;
        background-position: top center;
        background-size: 80%;
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