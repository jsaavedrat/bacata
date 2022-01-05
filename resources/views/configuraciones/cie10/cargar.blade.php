@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-arrow-with-circle-down"> </i> CODIGOS CIE 10 &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CARGAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
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
    <div id="content-mensaje">
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Productos Ingresados con Éxito</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Existen inconsistencias en el archivo excel</div>@endif
    </div>

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Cargar Excel para ingresar Codigos CIE10</div>
    <form method="post" id="formulario" data-parsley-validate  action="{{ route('configuraciones.codigoscie10.guardar') }}" enctype="multipart/form-data">
        @csrf

        <div class="label-campo">
            <div id="error-excel" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Seleccione un archivo EXCEL</div>
            <div class="label-admin" id="label_tipo_producto"><i id="lista" class="icon-dot-single"></i>Tipo de Producto<i id="cont-icon" class="icon-colours"></i></div>
            <input class="campo-admin" type="file" id="excel" required="required" accept=".xls,.xlsx" onchange="ExportToTable()">
        </div>
        <input type="hidden" id="codigos" name="codigos">
        <button class="boton-admin" id="cargar-codigos"> Cargar Excel</button>
    </form>
</div>



<style type="text/css">
    #elemento-admin{
        margin-top:16vh!important;
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
        background-color: rgba(225,225,235,0.2);
    }
    .label-campo{
        width: 100%;
    }
    .boton-admin{
        margin-top: 20px;
        width:calc(100% - 40px);
        margin-left:20px;
    }
    #iconos-titulo{
        box-shadow: 0px 0px 0px white;
    }
    #content-mensaje{
        width: calc(100% - 40px);
        margin-left: 20px;
    }
    #error-tipo-producto-vacio,#error-excel{
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
</style>


<script src="{{ asset('public/js/xlsx.core.min.js') }}"></script>
<script src="{{ asset('public/js/xls.core.min.js') }}"></script>

<script type="text/javascript">



    /*::::::::::::::::VERIFICAR EXCEL:::::::::::::::::*/

    function ExportToTable() {

        document.getElementById("codigos").value = "";
        var xlsxflag = false;
        if($("#excel").val().toLowerCase().indexOf(".xlsx") > 0) {
            xlsxflag = true;
        }

        if(typeof(FileReader) != "undefined") {
            var reader = new FileReader();
            reader.onload = function (e) {
                var data = e.target.result;
                if(xlsxflag) {
                    var workbook = XLSX.read(data, { type: 'binary' });
                }
                else{
                    var workbook = XLS.read(data, { type: 'binary' });
                }

                var sheet_name_list = workbook.SheetNames;
                var cnt = 0;
                sheet_name_list.forEach(function (y) {

                    if(xlsxflag) {
                        var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                    }
                    else{
                        var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                    }
                    if(exceljson.length > 0 && cnt == 0) {
                        BindTable(exceljson, '#exceltable');
                        cnt++;
                    }
                });
                $('#exceltable').show();
            }
            if(xlsxflag) {
                reader.readAsArrayBuffer($("#excel")[0].files[0]);
            }
            else{
                reader.readAsBinaryString($("#excel")[0].files[0]);
            }
        }
        else{
            alert("Ups! Tu navegador no soporta HTML5!");
        }
        
    }


    function BindTable(jsondata, tableid) {

        var codigos = JSON.stringify(jsondata);
        document.getElementById("codigos").value = codigos;

    }

    $("#cargar-codigos").click(function(){

        var valid = true;
        var codigos_cie10 = document.getElementById("codigos").value;

        if (codigos_cie10 == "" || codigos_cie10 == null) {
            valid = false;
            document.getElementById("error-excel").style.display = "block";
        }

        return valid;

    });


</script>

@endsection


