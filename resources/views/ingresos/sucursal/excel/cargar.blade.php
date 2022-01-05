@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-arrow-with-circle-down"> </i> INGRESOS &nbsp;<i class="fa fa-angle-right"> </i> SUCURSAL &nbsp;<i class="fa fa-angle-right"> </i> EXCEL &nbsp;<i class="fa fa-angle-right"> </i> CARGAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('ingresos.sucursal.excel.crear') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">CARGAR EXCEL</p></div></a></div>
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

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Cargar Excel para Ingreso Masivo a Sucursal</div>
    <form method="post" id="formulario" data-parsley-validate  action="{{ route('ingresos.sucursal.excel.guardar') }}" enctype="multipart/form-data">
        @csrf

        <div class="label-campo">
            <div id="error-sucursalS" class="errores" onclick="this.style.display='none'">Seleccione Sucursal de Ingreso</div>
            <div class="label-admin"><i id="lista" class="icon-dot-single"></i>Sucursal a ingresar productos:<i id="cont-icon" class="fa fa-home"></i></div>
            <select class="campo-admin" name="id_sucursal" id="id_sucursal" required>
                <option value="">Seleccione Sucursal</option>
                @foreach($sucursales as $sucursal)
                    <option value="{{$sucursal->id_sucursal}}">{{$sucursal->nombre_sucursal}}</option>
                @endforeach
            </select>
        </div>

        <div class="label-campo">
            <div id="error-tipo-producto-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Seleccione que tipo de producto es</div>
            <div class="label-admin" id="label_tipo_producto"><i id="lista" class="icon-dot-single"></i>Seleccione producto a cargar<i id="cont-icon" class="fa fa-tags"></i></div>
            <select class="campo-admin" name="tipo_producto" id="tipo_producto" required onchange="marcasTipoProductos(this.value);">
                <option value="">Seleccione qué productos va a cargar.</option>
                 @foreach($tipo_productos as $tipo_producto)
                    <option value="{{$tipo_producto->id_tipo_producto}}">{{$tipo_producto->nombre_tipo_producto}}</option>
                @endforeach
            </select>
        </div>

        <div class="label-campo">
            <div id="error-excel" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Seleccione un archivo EXCEL</div>
            <div class="label-admin" id="label_tipo_producto"><i id="lista" class="icon-dot-single"></i>Seleccionar archivo EXCEL<i id="cont-icon" class="fa fa-file-excel-o"></i></div>
            <input class="campo-admin" type="file" id="excel" name="excel" required="required" accept=".xls,.xlsx" onchange="ExportToTable()">
        </div>

        <div id="content-tabla-errores">
        </div>

        <div id="content-tabla-marcas">
        </div>

        <div id="content-exceltable">
            <table id="exceltable">
            </table>
        </div>
        
        <button class="boton-admin" id="crear-excel" style="display: none;"> Cargar Excel</button>
    </form>
</div>



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

    #content-exceltable{
        display: none;
        float: left;
        margin-bottom: 20px;
        width: 100%;
        height: 100px;
        border:1px solid red;
    }

    #content-tabla-marcas{
        width: 100%;
        float: left;
        margin-bottom: 20px;
    }

    #tablaexcel,#exceltable,#tabla-errores{
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

    .td-center{
        text-align: center!important;
        width:10px!important;
    }

    .vista-previa{
        margin:0px 0px 20px 0px;
    }

    #errores-encontrados{
        width: 100%;
        float: left;
        padding:20px;
        letter-spacing: -0.5px;
        font-size:calc(6px + 1.5vh)!important;
        color: rgba(230,0,0,0.8);
        font-weight: 500;
        text-transform: uppercase;
    }

    #crear-excel{
        width:100%;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
        left:0;
        right:0;
        margin-top:20px!important;
        overflow: hidden!important;
        float: none;
        margin-bottom: 20px;
    }

</style>

<!--
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
-->

<script src="{{ asset('public/js/xlsx.core.min.js') }}"></script>
<script src="{{ asset('public/js/xls.core.min.js') }}"></script>

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
                tipos = JSON.parse(data);

                document.getElementById("crear-excel").style.display = "block";
            },
            error: function(data) {
                console.log("error"); 
            }
        });
    }


    /*::::::::::::::::VERIFICAR EXCEL:::::::::::::::::*/

    function ExportToTable() {

        tipos = [];
        errores = [];
        filas = [];
        columnas = [];
        valores = [];

        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;

        if(regex.test($("#excel").val().toLowerCase())) {
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
                alert("Sorry! Your browser does not support HTML5!");
            }
        }
        else{
            alert("Please upload a valid Excel file!");
        }
    }


    function BindTable(jsondata, tableid) {

        var matriz = new Array();
        var vector = new Array();

        var columns = BindTableHeader(jsondata, tableid);

        var codigo = false;
        var cantidad = false;
        var precio = false;
        for(var i=0; i<columns.length; i++){
            if(columns[i] == "Codigo"){       codigo = true;   }
            if(columns[i] == "Cantidad"){     cantidad = true; }
            if(columns[i] == "Precio Venta"){ precio = true;   }
        }
        if(precio == false){  columns.push("Precio Venta");                      }
        if(cantidad == false){columns.splice((columns.length - 1),0,"Cantidad"); }
        if(codigo == false){  columns.splice((columns.length - 2),0,"Codigo");   }


        for (var i = 0; i < jsondata.length; i++) {
            var row$ = $('<tr/>');
            vector = [];
            for (var colIndex = 0; colIndex < columns.length; colIndex++) {
                var cellValue = jsondata[i][columns[colIndex]];
                if (cellValue == null)
                    cellValue = "";
                row$.append($('<td/>').html(cellValue));
                vector.push(cellValue);
            }
            matriz.push(vector);
            $(tableid).append(row$);
        }


        // console.log(columns);
        // console.log(matriz);

        var matrizString = new Array();

        var valid = true;

        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ,.- 1234567890()/"';
        var filtroNum = '1234567890.,';

        for(var i=0; i<matriz.length; i++){
                var auxString = "";
                for(var j=0; j<columns.length; j++){


                        /*::::::::::::::::::::::::::::::::::::::::::::*/
                        /*:::::::::::::::CELDAS VACIAS::::::::::::::::*/


                        if((matriz[i][j] == "") && (j != 0) && (j != (columns.length - 3) )){
                                var ii = i + 2;
                                var l = letra(j);
                                console.log("Existe una celda vacia en: "+ii+" y "+l);
                                errores.push("Celda vacía");
                                valores.push(matriz[i][j]);
                                filas.push(ii);
                                columnas.push(l);
                                valid = false;
                        }


                        /*::::::::::::::::::::::::::::::::::::::::::::*/
                        /*::::::::::::::ESPACIOS VACIOS:::::::::::::::*/


                        if((j != 0) && (j != (columns.length - 3) )){

                                var string = matriz[i][j];
                                var l = letra(j);


                                if(string[0] == " "){
                                        var ii = i + 2;
                                        console.log("Existe un espacio vacio al inicio en "+matriz[i][j]+", fila: "+ii+", columna: "+l);
                                        errores.push("Espacio vacío al inicio");
                                        valores.push(matriz[i][j]);
                                        filas.push(ii);
                                        columnas.push(l);
                                        valid = false;
                                }


                                var n = string.length - 1;
                                if(string[n] == " "){
                                        var ii = i + 2;
                                        console.log("Existe un espacio vacio al final en "+matriz[i][j]+", fila: "+ii+", columna: "+l);
                                        errores.push("Espacio vacío al final");
                                        valores.push(matriz[i][j]);
                                        filas.push(ii);
                                        columnas.push(l);
                                        valid = false;
                                }
                        }


                        /*::::::::::::::::::::::::::::::::::::::::::::*/
                        /*::::::::::::::UN SOLO CARACTER::::::::::::::*/


                        if(matriz[i][j].length < 2){
                                if((j == 1) || (j == 2) || (j > (columns.length - 1))){
                                        var ii = i + 2;
                                        var l = letra(j);
                                        console.log("El nombre de "+columns[j]+", "+matriz[i][j]+",no puede tener una sola letra fila: "+ii+", columna: "+l);
                                        errores.push("Escribe más letras");
                                        valores.push(matriz[i][j]);
                                        filas.push(ii);
                                        columnas.push(l);
                                        valid = false;
                                }
                        }


                        /*::::::::::::::::::::::::::::::::::::::::::::*/
                        /*:::::::::::CARACTERES NO VALIDOS::::::::::::*/


                        for (var k=0; k < matriz[i][j].length; k++){
                                if (filtro.indexOf(matriz[i][j].charAt(k)) != -1){

                                }else{
                                        var l = letra(j);
                                        var ii = i + 2;
                                        console.log("Existe un caracter errado en "+matriz[i][j]+", fila: "+ii+", columna: "+l);
                                        errores.push("Carácter no permitido");
                                        valores.push(matriz[i][j]);
                                        filas.push(ii);
                                        columnas.push(l);
                                        valid = false;
                                }
                        }


                        /*::::::::::::::::::::::::::::::::::::::::::::*/
                        /*:::::::::::::VALIDACION PRECIO::::::::::::::*/


                        if(j == (columns.length - 1)){
                                var errorNumero = false;
                                var errorPrecio = false;
                                for (var k=0; k < matriz[i][j].length; k++){
                                        if (filtroNum.indexOf(matriz[i][j].charAt(k)) != -1){

                                        }else{
                                                errorNumero = true;
                                        }
                                        if(matriz[i][j] < 1000){
                                                errorPrecio = true;
                                        }
                                }
                                if (errorNumero == true) {
                                        var l = letra(j);
                                        var ii = i + 2;
                                        console.log("Ingresa Numeros, Punto(.) ó Coma(,) en precio: "+matriz[i][j]+", fila: "+ii+", columna: "+l);
                                        errores.push("Escribe un precio válido");
                                        valores.push(matriz[i][j]);
                                        filas.push(ii);
                                        columnas.push(l);
                                        valid = false;
                                }
                                if (errorPrecio == true) {
                                        var l = letra(j);
                                        var ii = i + 2;
                                        console.log("Ingresa un Precio mayor en: "+matriz[i][j]+", fila: "+ii+", columna: "+l);
                                        errores.push("Escribe un precio más alto");
                                        valores.push(matriz[i][j]);
                                        filas.push(ii);
                                        columnas.push(l);
                                        valid = false;
                                }
                        }

                        /*::::::::::::::::::::::::::::::::::::::::::::*/
                        /*::::::::::::VALIDACION REPETIDOS::::::::::::*/

                        if(j != 0 && j < (columns.length - 3)){
                            auxString = auxString+"*"+String(matriz[i][j]);
                        }
                }
                matrizString.push(auxString);
        }


        // console.log("matrizString");
        // console.log(matrizString);

        var duplicados = false;
        var  vectorEncontrados = [];
        for(var i=0; i<matrizString.length; i++){

                var aux_vector = [];

                for(var j=0; j<matrizString.length; j++){

                        if((matrizString[i] == matrizString[j]) && (i != j)){
                            valid = false;
                            duplicados = true;
                            aux_vector.push(i + 2);
                            aux_vector.push(j + 2);
                        }
                }
                if (aux_vector.length > 0) {
                    var uniqs = aux_vector.filter(function(item, index, array) {
                        return array.indexOf(item) === index;
                    });

                    uniqs.sort(function(a,b){return a - b;});
                    uniqs = JSON.stringify(uniqs);
                    vectorEncontrados.push(uniqs);
                }
        }

        var uniqs = vectorEncontrados.filter(function(item, index, array) {
            return array.indexOf(item) === index;
        })

        vectorEncontrados = uniqs;

        if(duplicados == true){
                for(var i=0; i<vectorEncontrados.length; i++){
                        vectorEncontrados[i] = JSON.parse(vectorEncontrados[i]);
                        var posicion = "";
                        for(j=0; j<vectorEncontrados[i].length; j++){
                                if(j == (vectorEncontrados[i].length - 1)){
                                        posicion = posicion + vectorEncontrados[i][j];
                                }else{
                                        posicion = posicion + vectorEncontrados[i][j] + ", ";
                                }
                        }
                        errores.push("Existen productos duplicados en "+posicion);
                        valores.push("");
                        filas.push("");
                        columnas.push("");
                }
        }

        

        // console.log("encontrados:");
        // console.log(vectorEncontrados);


        $("#content-tabla-errores").empty();
        if(valid==false){
            $("#content-tabla-errores").append(`
                <div id="errores-encontrados"> <i class="fa fa-times"> </i> Errores Encontrados</div>
                <table id="tabla-errores">
                    <thead>
                        <tr>
                            <th>Fila</th>
                            <th>Columna</th>
                            <th>Error</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody id="t-body">
                    </tbody>
                </table>
            `);

            for(var i=0; i < errores.length; i++){
                $("#t-body").append(`
                    <tr>
                        <td class="td-center">`+filas[i]+`</td>
                        <td class="td-center">`+columnas[i]+`</td>
                        <td style="font-weight:500!important;">`+errores[i]+`</td>
                        <td>`+valores[i]+`</td>
                    </tr>
                `);
            }


            document.getElementById('crear-excel').style.display = "none";
        }else if(valid==true){

            $("#content-tabla-errores").append(`
                <div id="errores-encontrados" style="color:rgba(52,58,74,1)!important;"> <i class="fa fa-check"> </i> Lista de Elementos encontrados, verifique que no existan valores duplicados</div>
                <table id="tabla-errores">
                    <thead>
                        <tr id="t-head-tr">
                        </tr>
                    </thead>
                    <tbody id="t-body">
                    </tbody>
                </table>
            `);


            arreglo = [];
            v = [];
            var tamano = 0;

            for(var i=1; i < (columns.length - 3); i++){
                $("#t-head-tr").append(`
                        <th class="td-center">`+columns[i]+`</th>
                `);
            }


            for(var i=1; i < (matriz[0].length - 3); i++){
                v = [];
                for(j=0;j < matriz.length; j++){
                    v.push(matriz[j][i]);
                }

                var organiza = v.filter(function(item, index, array) {
                    return array.indexOf(item) === index;
                })
                organiza.sort();
                arreglo.push(organiza);

                if(tamano < organiza.length){
                    tamano = organiza.length;
                }
            }


            for(var i=0; i < tamano; i++){

                $("#t-body").append(`
                    <tr id="tr-`+i+`">  </tr>
                `);

                for(var j=0; j < arreglo.length; j++){

                    if(arreglo[j][i] != undefined){
                        $("#tr-"+i).append(`
                            <td id="tr-`+i+`">`+arreglo[j][i]+`  </td>
                        `);
                    }else{
                        $("#tr-"+i).append(`
                            <td id="tr-`+i+`"> </td>
                        `);
                    }
                }
            }


            document.getElementById('crear-excel').style.display = "block";
        }
    }

    function BindTableHeader(jsondata, tableid) {
        var columnSet = [];
        var headerTr$ = $('<tr/>');
        for (var i = 0; i < jsondata.length; i++) {
            var rowHash = jsondata[i];
            for (var key in rowHash) {
                if (rowHash.hasOwnProperty(key)) {  
                    if ($.inArray(key, columnSet) == -1) {
                         columnSet.push(key);
                         headerTr$.append($('<th/>').html(key));
                    }
                }
            }
        }
        $(tableid).append(headerTr$);

        return columnSet;
    }





    function letra(j){
        if(j == 0){ j = "A"}
        if(j == 1){ j = "B"}
        if(j == 2){ j = "C"}
        if(j == 3){ j = "D"}
        if(j == 4){ j = "E"}
        if(j == 5){ j = "F"}
        if(j == 6){ j = "G"}
        if(j == 7){ j = "H"}
        if(j == 8){ j = "I"}
        if(j == 9){ j = "J"}
        if(j == 10){ j = "K"}
        if(j == 11){ j = "L"}
        if(j == 12){ j = "M"}
        if(j == 13){ j = "N"}
        if(j == 14){ j = "O"}
        if(j == 15){ j = "P"}
        if(j == 16){ j = "Q"}
        if(j == 17){ j = "R"}
        if(j == 18){ j = "S"}
        if(j == 19){ j = "T"}
        if(j == 21){ j = "V"}
        if(j == 20){ j = "U"}
        if(j == 22){ j = "W"}
        if(j == 23){ j = "X"}
        if(j == 24){ j = "Y"}
        if(j == 25){ j = "Z"}

        return j;
    }


    $("#crear-excel").click(function(){

        var valid = true;

        return valid;

    });

</script>

@endsection


