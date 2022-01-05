@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-globe"> </i> ECOMMERCE &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CARGAR PRODUCTOS</div>
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
    <div id="content-mensaje">
        @if($mensaje=="exito")<div id="mensaje-exito"> <i class="fa fa-check-circle"></i> Productos Ingresados Exitosamente</div>@endif
        @if($mensaje=="error")<div id="mensaje-error"> <i class="fa fa-times-circle"></i>&nbsp; ERROR PRODUCTOS</div>@endif
    </div>
    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Cargar Productos a <b>E-Commerce</b></div>

    <form method="post" id="formulario" data-parsley-validate action="{{ route('adminecommerce.guardar') }}" enctype="multipart/form-data">
        @csrf

        <div class="content-bodega-sucursal">
            
            <div id="error-proveedor" onclick="this.style.display='none'">Seleccione El Proveedor</div>
            
            <div class="label-campo label-ingreso">
                <div class="label-admin" id="label_proveedor"><i id="lista" class="icon-dot-single"></i>Proveedor de estos Productos:<i id="cont-icon" class="icon-location"></i></div>
                <select class="campo-admin" name="proveedor" id="proveedor" required>
                    <option value="">Seleccione Proveedor</option>
                    @foreach($proveedores as $proveedor)
                        <option value="{{$proveedor->id_proveedor}}">{{$proveedor->nombre_proveedor}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="error-vacio" onclick="this.style.display='none'">Seleccione al menos un Producto</div>
        <input type="hidden" name="todo" id="todo">
        


        <div id="modal-confirmar">
            <div id="content-modal-confirmar">
                <div id="titulo-modal">Verificar Ingreso de Productos</div>
                <div id="establecimiento-entrada">
                    Ingresando Productos a: Ecommerce
                </div>
                <div id="informacion-cantidades">
                    Productos diferentes:22 Total de Productos: 25
                </div>
                <div id="content-tabla-modal">
                    
                </div>
                <div id="content-botones-modal">
                    <div class="content-boton-modal">
                        <button class="boton-modal" id="crear-productos" style="padding-top:0px!important;"><i class="fa fa-check"></i> Ingresar</button>
                    </div>
                    <div class="content-boton-modal">
                        <div class="boton-modal" onclick="modificar();"><i class="fa fa-close"></i> Modificar</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div id="agregar" onclick="agregar(cantidad)"> + Agregar</div>
    <div id="total">Total Productos: <div id="cantidad-total">0</div></div>
</div>

<div id="confirmar" onclick="confirmar();">Verificar Ingreso</div>



<script type="text/javascript">

    function resultado(numero){

        var tipo = document.getElementById("tipo_producto-"+numero);
        tipo = tipo.options[tipo.selectedIndex].innerText;
        //console.log("el tipo es:"+tipo);


        var marca = document.getElementById("marca-"+numero).value;
        if(marca!=""){
            marcaX = document.getElementById("marca-"+numero);
            marca = " - "+marcaX.options[marcaX.selectedIndex].innerText;
        }
        //console.log("la marca es:"+marca);


        var modelo = document.getElementById("modelo-"+numero).value;
        if(modelo!=""){
            modeloX = document.getElementById("modelo-"+numero);
            modelo = " - "+modeloX.options[modeloX.selectedIndex].innerText;
        }
        //console.log("el modelo es:"+modelo);


        var especificaciones = document.getElementById("especificaciones-"+numero).value;
        if(especificaciones=="x"){
            especificaciones="";
        }
        if(especificaciones!=""){
            especificacionesX = document.getElementById("especificaciones-"+numero);
            especificaciones = " - "+especificacionesX.options[especificacionesX.selectedIndex].innerText;
        }
        //console.log("especificaciones:"+especificaciones);



        document.getElementById("resultado-"+numero).innerText=tipo+marca+modelo+especificaciones;

    }

    function cantidades(numero,valor){

        var out = '';
        var filtro = '1234567890';//Caracteres validos
        
        
        for (var i=0; i<valor.length; i++){
            if (filtro.indexOf(valor.charAt(i)) != -1){ 
                out += valor.charAt(i);
            }
        }
        
        //console.log(out);

        //out = parseInt(out);


        document.getElementById("cantidad_producto-"+numero).value = out;

        if(out==""){
            out = 0;
        }

        document.getElementById("cantidades-"+numero).innerText="Cantidad: "+out;
        var n = document.getElementsByClassName('campo-cantidad');

        var total = 0;

        for (i = 0; i < n.length; i++) {
            var val = document.getElementsByClassName("campo-cantidad")[i].value;
            //console.log("encontro: "+val);
            if(val!=""){
                total = total + parseInt(val);
                //console.log("entro");
            }
        }

        document.getElementById("cantidad-total").innerHTML = total;
    }



    


    function marcasTipoProductos(id,marca,modelo,especificaciones,numero){

        
        
        document.getElementById("todo").value ="";
        document.getElementById(marca).value="";
        document.getElementById(modelo).value="";
        document.getElementById(especificaciones).value="";
        document.getElementById("vector-especificaciones-"+numero).value=null;
        document.getElementById("vector-auxiliar-especificaciones-"+numero).value="";
        document.getElementById("matriz-clasificaciones-"+numero).value=null;
        document.getElementById("codigo_producto-"+numero).value = "";
        document.getElementById("codigo_producto-"+numero).disabled = false;
        document.getElementById("precio_producto-"+numero).value = "";
        document.getElementById("precio_producto-"+numero).disabled = false;


        var n = document.getElementsByClassName('content-especificaciones');
        for (i = 0; i < n.length; i) {
            document.getElementsByClassName("content-especificaciones")[i].remove();
        }

                
        marca2="#"+marca;
        $(marca2).empty();
        $(marca2).append('<option value="">-----------</option>');

        modelo2="#"+modelo;
        $(modelo2).empty();
        $(modelo2).append('<option value="">-----------</option>');

        especificaciones2="#"+especificaciones;
        $(especificaciones2).empty();
        $(especificaciones2).append('<option value="">-----------</option>');



        var url="{{route('productos.marca_tipo_productos')}}";
        var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id_tipo_producto": id
        };
        $.ajax({
            type: 'GET',
            url: url,
            data: datos,
            success: function(data) {
                //console.log("success");
                //console.log(data);
                $(marca2).empty();
                if(id!=""){
                    $(marca2).append('<option value="">Seleccione Marca</option>');
                }else{
                    $(marca2).append('<option value="">-----------</option>');
                }
                
                for (var i = 0; i < data.length; i++) {
                    
                    $(marca2).append('<option value="'+data[i].id_marca+'">'+data[i].nombre_marca+'</option>');
                    if(i==0){
                        document.getElementById("iva-"+numero).value = data[i].iva;
                    }
                }

            },
            error: function(data) {
                console.log("error");
                
            }
        });

        resultado(numero);
    }

    function modeloMarcas(tipo_producto,id,modelo,especificaciones,numero){

        
        document.getElementById("todo").value ="";
        var id_tipo_producto = document.getElementById(tipo_producto).value;
        document.getElementById(modelo).value="";
        document.getElementById(especificaciones).value="";
        document.getElementById("vector-especificaciones-"+numero).value=null;
        document.getElementById("vector-auxiliar-especificaciones-"+numero).value="";
        document.getElementById("matriz-clasificaciones-"+numero).value=null;
        document.getElementById("codigo_producto-"+numero).value = "";
        document.getElementById("codigo_producto-"+numero).disabled = false;
        document.getElementById("precio_producto-"+numero).value = "";
        document.getElementById("precio_producto-"+numero).disabled = false;
        var n = document.getElementsByClassName('content-especificaciones');
        for (i = 0; i < n.length; i) {
            document.getElementsByClassName("content-especificaciones")[i].remove();
        }

        modelo2="#"+modelo;
        $(modelo2).empty();
        $(modelo2).append('<option value="">-----------</option>');

        especificaciones2="#"+especificaciones;
        $(especificaciones2).empty();
        $(especificaciones2).append('<option value="">-----------</option>');

        var url="{{route('productos.modelo_marcas')}}";
        var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id_tipo_producto": id_tipo_producto,
            "id_marca": id
        };
        $.ajax({
            type: 'GET',
            url: url,
            data: datos,
            success: function(data) {
                //console.log("success");
                //console.log(data);
                $(modelo2).empty();
                if(id!=""){
                    $(modelo2).append('<option value="">Seleccione Marca</option>');
                }else{
                    $(modelo2).append('<option value="">-----------</option>');
                }

                for (var i = 0; i < data.length; i++) {
                    $(modelo2).append('<option value="'+data[i].id_modelo+'">'+data[i].nombre_modelo+'</option>');
                }

            },
            error: function(data) {
                console.log("error");
                
            }
        });

        resultado(numero);
    }

    function clasificacionesTipoProductoNuevo(tipo_producto,id,especificaciones,elementos,numero){

        //alert("aqui");

        document.getElementById("todo").value ="";
        var id_tipo_producto = document.getElementById(tipo_producto).value;
        var id_modelo = document.getElementById("modelo-"+numero).value;
        especificaciones2="#"+especificaciones;
        document.getElementById("vector-especificaciones-"+numero).value=null;
        document.getElementById("vector-auxiliar-especificaciones-"+numero).value="";
        document.getElementById("codigo_producto-"+numero).value = "";
        document.getElementById("codigo_producto-"+numero).disabled = false;
        document.getElementById("precio_producto-"+numero).value = "";
        document.getElementById("precio_producto-"+numero).disabled = false;

        var url="{{route('productos.clasificaciones_tipo_productos')}}";
        var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id_tipo_producto": id_tipo_producto,
            "id_modelo": id_modelo
        };
        $.ajax({
            type: 'GET',
            url: url,
            data: datos,
            success: function(data) {
                //console.log("success");
                //console.log(data);
                var clasificaciones = JSON.stringify(data[0]);
                var productos = data[1];
                //console.log("productos:");
                //console.log(productos[0]);
                //console.log(elementos);
                document.getElementById(elementos).value = clasificaciones;

                //:::::::::::::::::::::::::::::::::::::::::::GUARDAR LAS CLASIFICACIONES EN UN INPUT PARA CADA LINEA DE PRODUCTO::::::::::::::::::::::::::::::::::::::::::::::::
                $(especificaciones2).empty();

                $(especificaciones2).append('<option value="">Seleccione Especificaciones</option>');

                matriz_auxiliar = [];
                vector_auxiliar = [];

                for (var i = 0; i < productos.length; i++) {
                    var aux = "";
                    vector_auxiliar = [];
                    for (var j = 0; j < productos[i].especificaciones.length; j++){
                        if(j != productos[i].especificaciones.length - 1){
                            aux = aux + productos[i].especificaciones[j].nombre_especificacion + ", ";
                        }else{
                            aux = aux + productos[i].especificaciones[j].nombre_especificacion;
                        }
                        vector_auxiliar[j] = productos[i].especificaciones[j].id_especificacion;
                    }
                    matriz_auxiliar[i] = vector_auxiliar;
                    $(especificaciones2).append('<option value="'+productos[i].id_producto+'">'+aux+'</option>');
                }
                //console.log("la matriz de especificaciones es:");
                
                matriz_auxiliar = JSON.stringify(matriz_auxiliar);

                //console.log(matriz_auxiliar);
                document.getElementById("matriz-auxiliar-"+numero).value = matriz_auxiliar;
                $(especificaciones2).append('<option id="agg-'+numero+'" value="x">Agregar Especificaciones</option>');

            },
            error: function(data) {
                console.log("error");
                
            }
        });
        resultado(numero);
    }

    function validarEspecificaciones(id,nuevas,elementos,numero){

        
        //console.log("numero: "+numero);
        //console.log(id);
        //console.log("el id del elemento es: "+elementos);
        document.getElementById("todo").value ="";
        var nuevas2 = "#"+nuevas;

        var n = document.getElementsByClassName('content-especificaciones');
        for (i = 0; i < n.length; i) {
            document.getElementsByClassName("content-especificaciones")[i].remove();
        }

        if(id=="x"){

            document.getElementById("codigo_producto-"+numero).value = "";
            document.getElementById("codigo_producto-"+numero).disabled = false;
            document.getElementById("precio_producto-"+numero).value = "";
            document.getElementById("precio_producto-"+numero).disabled = false;

            $(nuevas2).append('<div id="content-especificaciones" class="content-especificaciones"></div>');
            //console.log("el array:");
            //console.log(especificaciones);
            var clasificaciones2 = document.getElementById(elementos).value;
            clasificaciones = JSON.parse(clasificaciones2);
            //console.log("en la funcion=x: ");
            //console.log(clasificaciones);
            c = 0;
            for (var i = 0; i < clasificaciones.length; i++) {
                $('#content-especificaciones').append('<div class="titulo-clasificacion">'+clasificaciones[i].nombre_clasificacion+':'+'</div>');

                for(var j = 0; j < clasificaciones[i].especificaciones.length; j++){

                    $('#content-especificaciones').append(
                        '<div class="elemento-especificacion esp-'+numero+'-'+i+' esp-'+numero+'-'+i+'-'+j+'" onclick="seleccionarEspecificacion(`chk-'+numero+'-'+i+'`,`chk-'+numero+'-'+i+'-'+j+'`,`esp-'+numero+'-'+i+'`,`esp-'+numero+'-'+i+'-'+j+'`)">'+clasificaciones[i].especificaciones[j].nombre_valor+'</div>');

                    $('#content-especificaciones').append('<input value="'+clasificaciones[i].especificaciones[j].id_valor+'" type="checkbox" class="chk-especificacion chk-'+numero+' chk-'+numero+'-'+i+' chk-'+numero+'-'+i+'-'+j+'">');
                }
                c = i;
            }
            //console.log("el numero es: "+numero);
            $('#content-especificaciones').append('<div class="ok" onclick="agregarEspecificaciones(`chk-'+numero+'`,'+c+','+numero+')">Ok</div>');
            $('#content-especificaciones').append('<div id="selecciona-todas">Selecciona las especificaciones</div>');
            $('#content-especificaciones').append('<div id="elementos-repetidos">Combinación ya existe en la lista</div>');
            $('#content-especificaciones').append('<div id="elementos-existen">Ya has creado esta combinación</div>');
        }else if(id !="" && id !=null){
            
            var url="{{route('productos.codigoPrecio')}}";
            var datos = {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "id_producto": id
            };
            $.ajax({
                type: 'GET',
                url: url,
                data: datos,
                success: function(data) {
                    console.log("success");
                    console.log(data);

                    document.getElementById("codigo_producto-"+numero).value = data[0].codigo_producto;
                    document.getElementById("codigo_producto-"+numero).disabled = true;
                    document.getElementById("precio_producto-"+numero).value = data[0].precio_base;
                    document.getElementById("precio_producto-"+numero).disabled = true;
                    
                },
                error: function(data) {
                    console.log("error"); 
                }
            });


        }else{
            document.getElementById("codigo_producto-"+numero).value = "";
            document.getElementById("codigo_producto-"+numero).disabled = false;
            document.getElementById("precio_producto-"+numero).value = "";
            document.getElementById("precio_producto-"+numero).disabled = false;
        }
        resultado(numero);
    }


    function seleccionarEspecificacion(chkClass,chkActual,espClass,espActual){

        /*console.log(chkClass);
        console.log(chkActual);
        console.log(espActual);*/
        document.getElementById("todo").value ="";
        var n = document.getElementsByClassName(chkClass);
        //console.log(n.length);

        var aux = document.getElementsByClassName(chkActual)[0].checked;

        for (var i = 0; i < n.length; i++) {

            document.getElementsByClassName(chkClass)[i].checked = false;
            document.getElementsByClassName(espClass)[i].style.backgroundColor = "rgba(0,0,0,0)";
            document.getElementsByClassName(espClass)[i].style.color = "rgba(52,58,64,1)";
        }

        if(aux == false){
            document.getElementsByClassName(chkActual)[0].checked = true;
            document.getElementsByClassName(espActual)[0].style.backgroundColor = "rgba(50,50,50,1)";
            document.getElementsByClassName(espActual)[0].style.color = "rgba(240,240,240,1)";
        }

    }

    function agregarEspecificaciones(chkValidar,n,numero){

        //alert("aqui 3");
        //console.log("validar: "+chkValidar);
        //console.log("i: "+n);
        //console.log("el numero es: "+numero);
        //alert("aqui");
        document.getElementById("todo").value ="";
        var vectorNuevasEsp = [];

        

        var x = [];
        var nombresEsp = "";
        var m = document.getElementsByClassName(chkValidar);

        var vector_validacion = [];

        var verificar = true;

        for (var i = 0; i <= n; i++) {
            var valid = true;
            var l = document.getElementsByClassName(chkValidar+'-'+i);

            //console.log(l.length);
            var c = 0;
            for(var j = 0; j < l.length; j++){
                var chk = document.getElementsByClassName(chkValidar+'-'+i)[j].checked;
                if(chk==true){
                    c = c + 1;
                    var v = document.getElementsByClassName(chkValidar+'-'+i)[j].value;
                    var esp = document.getElementsByClassName('esp-'+numero+'-'+i)[j].innerHTML;
                    nombresEsp = nombresEsp+esp+", ";
                    x = {
                        'id_especificacion':v
                    };
                    vectorNuevasEsp.push(x);

                    v = parseInt(v);
                    vector_validacion.push(v);
                }
            }
            if(c != 1){
                valid = false;
                verificar = false;
            }
        }

        var matriz_validacion = document.getElementById("matriz-auxiliar-"+numero).value;
        matriz_validacion = JSON.parse(matriz_validacion);

        var repetidos = true;
        for(i = 0; i < matriz_validacion.length; i++){

            var c = 0;    
            for(k = 0; k < vector_validacion.length; k++){

                var band = matriz_validacion[i].indexOf(vector_validacion[k]);
                if(band != -1){
                    c = c + 1;
                }
            }

            if(c == matriz_validacion[i].length && vector_validacion.length==c){
                repetidos=false;
                console.log("esta combinacion ya existe");
            }
        }        





        var buscar = document.getElementsByClassName("elemento-numero");

        var modelo_aux = document.getElementById("modelo-"+numero).value;
        console.log("modelo_aux: "+modelo_aux);

        vector_validacion = JSON.stringify(vector_validacion);
        console.log(vector_validacion);

        var cont = 0;
        var existen = true;
        for(i = 0; i < buscar.length; i++){

            var x = document.getElementsByClassName("elemento-numero")[i].value;
            var modelo = document.getElementById("modelo-"+x).value;

            if(modelo_aux == modelo && numero!=x){
                cont = cont + 1;

                var aux =  document.getElementById("vector-auxiliar-especificaciones-"+x).value;
                if(aux == vector_validacion){
                    //console.log("ESTAS ESPECIFICACIONES YA EXISTEN: "+aux);
                    existen = false;
                }
            }

        }
        //console.log("exiten "+cont+" modelos duplicados");


        

        if(verificar==true && repetidos==true && existen==true){
            var d = document.getElementsByClassName('content-especificaciones');
            for (i = 0; i < d.length; i) {
                document.getElementsByClassName("content-especificaciones")[i].remove();
            }
            agg = "#especificaciones-"+numero;

            var a = document.getElementsByClassName('agregados-'+numero);
            for (i = 0; i < a.length; i) {
                document.getElementsByClassName('agregados-'+numero)[i].remove();
            }

            //$(agg).append('<option class="agregados-'+numero+'" value="'+vectorNuevasEsp+'">'+nombresEsp+'</option>');
            $(agg).append('<option class="agregados-'+numero+'" value="'+null+'">'+nombresEsp+'</option>');

            document.getElementById("vector-auxiliar-especificaciones-"+numero).value=vector_validacion;
            vectorNuevasEsp = JSON.stringify(vectorNuevasEsp);
            document.getElementById("vector-especificaciones-"+numero).value = vectorNuevasEsp;

            document.getElementsByClassName("agregados-"+numero)[0].selected = true;

            resultado(numero);
        }else{
            if(verificar == false){
                document.getElementById("selecciona-todas").style.display = "block";
            }else{
                document.getElementById("selecciona-todas").style.display = "none";
            }
            if(repetidos == false){
                document.getElementById("elementos-repetidos").style.display = "block";
            }else{
                document.getElementById("elementos-repetidos").style.display = "none";
            }
            if(existen == false){
                document.getElementById("elementos-existen").style.display = "block";
            }else{
                document.getElementById("elementos-existen").style.display = "none";
            }
        }
    }


    function eliminar_producto_ecommerce(elemento){
        document.getElementById("todo").value ="";
        document.getElementsByClassName(elemento)[0].remove();
        var n = document.getElementsByClassName("numeracion");
        for (var i = 0; i < n.length; i++) {
            document.getElementsByClassName("numeracion")[i].innerHTML = i+1;
        }
        var n = document.getElementsByClassName('campo-cantidad');

        var total = 0;

        for (i = 0; i < n.length; i++) {
            var val = document.getElementsByClassName("campo-cantidad")[i].value;
            //console.log("encontro: "+val);
            if(val!=""){
                total = total + parseInt(val);
                //console.log("entro");
            }
        }

        document.getElementById("cantidad-total").innerHTML = total;
    }
    

    function agregar(numero){
        document.getElementById("todo").value ="";
        var n = document.getElementsByClassName("numeracion");
        n = n.length + 1;

        crear = `
            <div class="numeracion">`+n+`</div><div class="resultado" id="resultado-`+numero+`"></div><div class="cantidades" id="cantidades-`+numero+`"></div>
            <div class="eliminar" onclick="eliminar_producto_ecommerce('informacion-producto-`+numero+`')"><i class="icon-cross"></i></div>
            <div class="borde-error" id="borde-error-`+numero+`">
            <div class="label-campo">
                <div class="label-admin" id="label_tipo_producto"><i id="lista" class="icon-dot-single"></i>Tipo de Producto<i id="cont-icon"></i></div>
                <select class="campo-admin" id="tipo_producto-`+numero+`" required onchange="marcasTipoProductos(this.value,'marca-`+numero+`','modelo-`+numero+`','especificaciones-`+numero+`',`+numero+`);">
                    <option value="">Seleccione</option>
                     @foreach($tipo_productos as $tipo_producto)
                        <option value="{{$tipo_producto->id_tipo_producto}}">{{$tipo_producto->nombre_tipo_producto}}</option>
                    @endforeach
                </select>
            </div>

            <div class="label-campo">
                <div class="label-admin" id="label_marca"><i id="lista" class="icon-dot-single"></i>Marca</div>
                <select class="campo-admin" id="marca-`+numero+`" required onchange="modeloMarcas('tipo_producto-`+numero+`',this.value,'modelo-`+numero+`','especificaciones-`+numero+`',`+numero+`);">
                    <option value="">-----------</option>
                </select>
            </div>

            <div class="label-campo">
                <div class="label-admin" id="label_marca"><i id="lista" class="icon-dot-single"></i>Modelo</div>
                <select class="campo-admin" id="modelo-`+numero+`" required onchange="clasificacionesTipoProductoNuevo('tipo_producto-`+numero+`',this.value,'especificaciones-`+numero+`','matriz-clasificaciones-`+numero+`',`+numero+`);">
                    <option value="">-----------</option>
                </select>
            </div>

            <div class="label-campo">
                <div class="label-admin" id="label_marca"><i id="lista" class="icon-dot-single"></i>Especificaciones</div>
                <select class="campo-admin" id="especificaciones-`+numero+`" required onchange="validarEspecificaciones(this.value,'nuevas-clasificaciones-`+numero+`','matriz-clasificaciones-`+numero+`','`+numero+`');">
                    <option value="">-----------</option>
                </select>
                <div id="nuevas-clasificaciones-`+numero+`" class="content-clasificaciones">
                </div>
            </div>
            <input type="hidden" id="matriz-auxiliar-`+numero+`" value="">

            <div class="label-campo label-campo-valores">
                <label class="label-admin"><i id="lista" class="icon-dot-single"></i>Código</label>
                <input class="campo-admin" type="text" id="codigo_producto-`+numero+`" placeholder="Código" spellcheck="false" autocomplete="off" maxlength="16" required onkeyup="this.value=codigo(this.value)">
            </div>

            <div class="label-campo label-campo-valores">
                <label class="label-admin"><i id="lista" class="icon-dot-single"></i>Cantidad</label>
                <input class="campo-admin campo-cantidad" type="text" id="cantidad_producto-`+numero+`" placeholder="Cantidad" spellcheck="false" autocomplete="off" maxlength="5" required onkeyup="cantidades(`+numero+`,this.value)">
            </div>

            <div class="label-campo label-campo-valores">
                <label class="label-admin"><i id="lista" class="icon-dot-single"></i>Precio</label>
                <input class="campo-admin" type="text" id="precio_producto-`+numero+`" placeholder="Precio" spellcheck="false" autocomplete="off" maxlength="11" required onkeyup="this.value=format(this.value)" onchange="format(this)" style="text-align:right!important;">
            </div>

            <input type="hidden" class="campo-iva" id="iva-`+numero+`">

            <label class="label-campo label-campo-valores" for="imagen-`+numero+`">
                <div class="label-admin" id="label_marca"><i id="lista" class="icon-plus"></i>Imágenes</div>
                <input type="file" required name="imagen`+numero+`[]" class="campo-imagen" id="imagen-`+numero+`" style="display:none;" multiple="multiple" accept=".png,.jpg" onchange="nombresArchivos(`+numero+`)">
                <div class="content-imagenes" id="content-imagen-`+numero+`">Seleccionar Imagen</div>
            </label>
            <input type="hidden" class="elemento-numero elemento-numero-`+numero+`" id="elemento-`+numero+`" value="`+numero+`">
            
            <input type="hidden" class="matriz-clasificaciones matriz-clasificaciones-`+numero+`" id="matriz-clasificaciones-`+numero+`">

            <input type="hidden" class="vector-especificaciones vector-especificaciones-`+numero+`" id="vector-especificaciones-`+numero+`">
            <input type="hidden" class="vector-auxiliar-especificaciones vector-auxiliar-especificaciones-`+numero+`" id="vector-auxiliar-especificaciones-`+numero+`">
            </div>
            `;

            $('#formulario').append('<div class="informacion informacion-producto-'+numero+'"></div>');

            document.getElementsByClassName("informacion-producto-"+numero+"")[0].innerHTML = crear;

        cantidad = cantidad + 1;
        //console.log(cantidad);
    }

    cantidad = 1;

    agregar(cantidad);




    function format(input){
        {{--document.getElementById("todo").value ="";
        var num = input.value.replace(/\./g,'');
        if(!isNaN(num)){
            num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
            num = num.split('').reverse().join('').replace(/^[\.]/,'');
            input.value = num;
        }
         
        else{ alert('Solo se permiten numeros');
            input.value = input.value.replace(/[^\d\.]*/g,'');
        }--}}

        var out = '';
        var filtro = '1234567890';//Caracteres validos
        
        
        for (var i=0; i<input.length; i++)
            if (filtro.indexOf(input.charAt(i)) != -1) 
                out += input.charAt(i);

        return out;
    }

    function codigo(string){//Solo letras
        document.getElementById("todo").value ="";
        string = string.toUpperCase();

        var out = '';
        var filtro = '1234567890+-#abcdefghijklmn_opqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//Caracteres validos
        
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }

    function confirmar(){
        document.getElementById("todo").value ="";
        var n = document.getElementsByClassName("elemento-numero");
        var valid = true;
        
        document.getElementById('error-vacio').style.display="none";
        var vector = [];

        for (i = 0; i < n.length; i++) {
            x = document.getElementsByClassName("elemento-numero")[i].value;
            document.getElementById("borde-error-"+x).style.border="0px solid rgba(200,50,50,1)";
            //console.log("tabla "+x+":");

            var id_producto = document.getElementById("especificaciones-"+x).value;
            if(id_producto=="x"){
                document.getElementById("borde-error-"+x).style.border="1px solid rgba(200,50,50,1)";
                valid = false;
            }
            console.log("id producto "+i+" es: "+id_producto);

            var modelo = document.getElementById("modelo-"+x).value;
            //console.log("modelo "+i+" es: "+modelo);

            var especificaciones = document.getElementById("vector-especificaciones-"+x).value;
            if(especificaciones!=null && especificaciones!="" && especificaciones!="null"){
                especificaciones=JSON.parse(especificaciones);
            }
            if((especificaciones=="" || especificaciones==null || especificaciones=="null") && id_producto==""){
                document.getElementById("borde-error-"+x).style.border="1px solid rgba(200,50,50,1)";
                valid = false;
            }

            var imagen = document.getElementById("imagen-"+x).files;
            if(imagen.length==0){
                valid = false;
            }
            var imagenes = [];
            var name = {};
            for(j = 0; j < imagen.length; j++){
                var names = document.getElementById("imagen-"+x).files[j].name;
                name = {'nombre_imagen':names};
                imagenes.push(name);
            }
            //console.log(imagenes);
            
            
            //console.log("ids de especificaciones "+i+" es: "+especificaciones);

            var codigo = document.getElementById("codigo_producto-"+x).value;
            //console.log("codigo "+i+" es: "+codigo);

            var cantidad = document.getElementById("cantidad_producto-"+x).value;
            //console.log("cantidad "+i+" es: "+cantidad);

            var precio_producto = document.getElementById("precio_producto-"+x).value;
            //console.log("precio producto "+i+" es: "+precio_producto);

            var iva = document.getElementById("iva-"+x).value;
            //console.log("declara iva? "+i+" "+iva);
            if(codigo=="" || cantidad=="" || precio_producto=="" || imagen.length==0){
                document.getElementById("borde-error-"+x).style.border="1px solid rgba(200,50,50,1)";
                valid = false;
            }
                     
            var productos = {
                            'id_producto':id_producto,
                            'modelo':modelo,
                            'especificaciones':especificaciones,
                            'codigo':codigo,
                            'cantidad':cantidad,
                            'precio':precio_producto,
                            'iva':iva,
                            'imagenes': imagenes,
                            'numero': 'imagen'+x
                            }

            vector.push(productos);
        }
        console.log(vector);
        vector = JSON.stringify(vector); 
        //console.log(vector);

        if(vector=="[]"){
            valid=false;
            document.getElementById('error-vacio').style.display="block";
        }

        /*var bodega = document.getElementById("bodega").value;
        if (bodega=="" || bodega==null) {
            valid=false;
            document.getElementById("error-bodega").style.display="block";
        }else{
            document.getElementById("error-bodega").style.display="none";
        }*/

        var proveedor = document.getElementById("proveedor").value;
        if (proveedor=="" || proveedor==null) {
            valid=false;
            document.getElementById("error-proveedor").style.display="block";
        }else{
            document.getElementById("error-proveedor").style.display="none";
        }


        //console.log("valid: "+valid);
        if(valid==true){
            document.getElementById("todo").value = vector;

            mostrar();

            $("#modal-confirmar").fadeIn();
        }
    }




    function mostrar(){

        json = document.getElementById("todo").value;
        json = JSON.parse(json);
        //console.log("mostrando json:");
        //console.log(json);


        $('#content-tabla-modal').append('<div class="columna par"><div class="info-izquierda" style="font-weight:bold;font-size:15px;">Detalle</div> <div class="info-derecha" style="font-weight:bold;font-size:15px;">Cantidad</div></div>');
        var n = document.getElementsByClassName("elemento-numero");
        for (i = 0; i < n.length; i++) {
            x = document.getElementsByClassName("elemento-numero")[i].value;
            var res  = document.getElementById("resultado-"+x).innerText;
            var res2 = document.getElementById("cantidad_producto-"+x).value;
            //console.log(res);

            if(i % 2 == 0){
                $('#content-tabla-modal').append('<div class="columna impar"><div class="info-izquierda">'+res+'</div> <div class="info-derecha">'+res2+'</div></div>');
            }else{
                $('#content-tabla-modal').append('<div class="columna   par"><div class="info-izquierda">'+res+'</div> <div class="info-derecha">'+res2+'</div></div>');
            }            
        }
        var cant = document.getElementById("cantidad-total").innerText;

        $('#content-tabla-modal').append('<div id="total-modal">Total Productos: '+cant+'</div>');


    }

    function modificar(){
        $("#modal-confirmar").fadeOut();
        document.getElementById('content-tabla-modal').innerHTML="";
    }

    function nombresArchivos(numero){
        var imagen = document.getElementById("imagen-"+numero).files;
        console.log("imagenes del producto "+numero+":");
        for(j = 0; j < imagen.length; j++){
            var names = document.getElementById("imagen-"+numero).files[j].name;
            console.log("nombre imagen "+j+": "+names);
        }

        if(imagen.length > 1){
            document.getElementById("content-imagen-"+numero).innerHTML =imagen.length+" Imagenes seleccionadas";
        }else if(imagen.length == 0){
            document.getElementById("content-imagen-"+numero).innerHTML ="Seleccionar Imagen";
        }else if(imagen.length == 1){
            document.getElementById("content-imagen-"+numero).innerHTML =imagen.length+" Imagen seleccionada";
        }

    }
    
</script>


<style type="text/css">

    #elemento-admin{
        width: calc(100% - 10px);
        margin-left:5px;
        height: 100%;
        float: left;
        //border:1px solid red;
    }

    .content-bodega-sucursal{
        width: 100%;
        float: left;
        //border:1px solid green;
        margin:20px 0px 20px 0px;
    }


    .label-campo{
        width: 15%;
        float: left;
        padding: 0px;
    }
    .label-ingreso{
        width:33.3%;
        padding:0px 10px 0px 10px;
    }

    .label-campo-valores{
        width: 10%;
    }

    .label-campo-iva{
        width: 8%;
    }

    .label-admin{
        font-size:13.5px;
        margin-bottom:0px;
        padding: 10px 10px 10px 2px;
    }
    #lista{
        margin-right: 2px;
    }
    .content-imagenes{
        float: left;
        width: 100%;
        height: 38px;
        background-color: rgba(255,255,255,0.8);
        padding: 5px;
        border:1px solid rgba(215,215,200,0.7);
        border-top:0px;
        font-size:12px;
        text-align: center;
        line-height: 12px;
        cursor: pointer;
    }

    .informacion{
        width: 100%;
        float: left;
        //border:1px solid blue;
        margin-top:20px;
        margin-bottom: 20px;
    }

    .borde-error{
        border:0px solid rgba(0,0,0,0);
        float: left;
    }
    
    #nuevas-clasificaciones{
        //margin-top: 5px;
        width: 100%;
        float: left;
        //border:1px solid red;
        background-color: white;

    }

    .content-clasificaciones{
        overflow: hidden;

    }

    #content-especificaciones{
        display: block;
        position: absolute;
        z-index: 1;
        overflow: hidden;
        border:1px solid rgba(215,215,200,0.7);
        width: calc(17.5% - 10px);
        margin-left: -10px;
        background-color: rgba(255,255,255,1);
        box-shadow: 0px 0px 10px rgba(50,50,50,0.6);
    }
    .titulo-clasificacion{
        float: left;
        color: white;
        text-decoration: none;
        display: block;
        text-align: center;
        font-weight: normal;
        width: 100%;
        color: rgba(52,58,64,1);
        background-color: rgba(220,220,180,0.7);
        font-size: 14px;
        padding:5px 0px 5px 0px;
    }
    .elemento-especificacion{
        float: left;
        color: white;
        text-decoration: none;
        display: block;
        text-align: center;
        font-weight: normal;
        width: 50%;
        padding: 3px 0px 3px 0px;
        color: rgba(52,58,64,1);
        font-size: 13px;
        cursor: pointer;
    }

    .chk-especificacion{
        display: none;
    }


    #agregar{
        border:1px solid rgba(215,215,200,0.7);
        float: left;
        font-weight: 500;
        width: 13%;
        background-color: white;
        font-size: 14px;
        text-align: center;
        padding: 8px;
        cursor: pointer;
        height: 38px;
    }

    #total{
        height: 38px;
        float:right;
        font-size: 20px;
        text-align: right;
        max-width: 300px;
        width: 100%;
        //border:1px solid red;
        letter-spacing: -1px;
        font-weight: 500;
    }
    #cantidad-total{
        float: right;
        height: 100%;
        //border:1px solid blue;
        padding:0px 10px 0px 10px;
        font-weight: bold;
    }

    .ok{
        float: left;
        width: 100%;
        background-color: rgba(0,200,200,1);
        color:white;
        font-size: 14px;
        text-align: center;
        padding: 5px 0px 5px 0px;
        cursor:pointer;
    }.ok:hover{
        font-size: 16px;
        padding: 3.5px 0px 3.5px 0px;
    }

    .numeracion{
        width: 20px;
        margin-right: 5px;
        margin-bottom: 2px;
        height: 20px;
        float: left;
        border:1px solid #343a40;
        border-radius: 50%;
        background-image: linear-gradient(-20deg, #2b5876 0%, #4e4376 100%) !important;
        color:white;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
    }
    .eliminar{
        width: 22px;
        float: right;
        padding-top: 3px;
        height: 22px;
        text-align: center;
        font-size: 13px;
        letter-spacing: -0.5px;
        color:white;
        background-color: rgba(200,50,50,1);
        cursor:pointer;
    }
    .resultado{
        font-size: 16px;
        font-weight: bold;
        letter-spacing: -1px;
        width: calc(80% - 50px);
        float: left;
        //border:1px solid red;
        height: 22px;
    }
    .cantidades{
        font-size: 16px;
        font-weight: bold;
        letter-spacing: -1px;
        //border:1px solid blue;
        width: 20%;
        float: left;
        height: 22px;
        text-align: right;
        padding-right: 20px;
    }

    #confirmar{
        width: 100%;
        max-width: 400px;
        background-color: rgba(0,200,200,1);
        color:white;
        margin-right: auto;
        margin-left: auto;
        left:0;
        right: 0;
        padding: 10px;
        font-size: 16px;
        letter-spacing: -0.5px;
        text-align: center;
        overflow: hidden;
        cursor: pointer;
    }
    #selecciona-todas,#elementos-repetidos,#elementos-existen{
        display: none;
        width: 100%;
        background-color: rgba(200,50,50,1);
        color: rgba(240,240,240,1);
        float: left;
        padding:5px 2px 5px 2px;
        letter-spacing: -0.5px;
        font-size: 13px;
        line-height: 12px;
        text-align: center;
    }

    #modal-confirmar{
        width: calc(100% - 220px);
        height: 100vh;
        position: fixed;
        z-index: 0!important;
        top: 0;
        background-color: rgba(50,50,50,0.7);
        margin-left: -5px;
        display: none;
    }
    #content-modal-confirmar{
        width: 100%;
        max-width: 1000px;
        height: 80vh;
        margin-top:10vh;
        margin-left: auto;
        margin-right: auto;
        left:0;
        right: 0;
        background-color: rgba(245,245,245,1);
        //overflow-y: scroll;
        box-shadow: inset 0 0 5px 0 rgba(50,50,50,0.5);
    }
    #titulo-modal{
        padding-top:10px;
        width: 100%;
        height: 35px;
        font-size: 20px;
        float: left;
        text-align: center;
        letter-spacing: -0.6px;
        font-weight: 600;
        //border: 1px solid red;
    }
    #establecimiento-entrada{
        width: 100%;
        height: 20px;
        float: left;
        font-size: 13px;
        //border: 1px solid blue;
        padding-left: 10px;
    }
    #informacion-cantidades{
        width: 100%;
        height: 20px;
        float: left;
        font-size: 13px;
        //border: 1px solid green;
        //text-align: right;
        padding-left: 10px;
    }
    #content-tabla-modal{
        width: calc(100% - 10px);
        margin-left: 5px;
        float: left;
        height: calc(80vh - 140px);
        border: 1px solid rgba(200,200,200,0.7);
        overflow-y: scroll;
        //overflow-y:hidden;
        background-color:rgba(250,250,250,1);
        padding-top:20px;
    }
    #content-botones-modal{
        width: 100%;
        height: 65px;
        float: left;
        //border:1px solid blue;
        padding: 5px 40px 5px 40px;
    }
    .content-boton-modal{
        width: 50%;
        float: left;
        height: 100%;
        //border:1px solid red;
        padding: 10px 50px 10px 50px;
    }
    .boton-modal{
        width: 100%;
        height: 100%;
        float: left;
        background-color:  rgba(0,200,200,1);
        color:white;
        text-align: center;
        font-weight: 600;
        font-size: 17px;
        padding-top:5px;
        letter-spacing: -0.5px;
        cursor:pointer;
        border:0px;
    }.boton-modal:hover{
        padding-top:3px;
        background-color: rgba(0,0,0,0);
        color:rgba(0,200,200,1);
        border:2px solid rgba(0,200,200,1);
    }
    .columna{
        width: 100%;
        float: left;
        letter-spacing: -0.5px;
        font-size: 13px;
        padding:3px 10px 3px 10px;
    }
    .impar{
        background-color: rgba(230,230,230,1);
    }
    .info-izquierda{
        float: left;
    }
    .info-derecha{
        float: right;
    }
    #total-modal{
        width: 100%;
        text-align: right;
        float: left;
        font-size: 20px;
        font-weight: 600;
        letter-spacing: -0.6px;
        padding-right: 5px;
    }
    #error-bodega,#error-vacio,#error-proveedor{
        padding: 8px;
        background-color:rgba(230,0,0,0.8);
        color:white;
        font-weight: 600;
        letter-spacing: -0.5px;
        border-radius: 2px;
        margin-bottom: 2px;
        font-size:15px;
        float: left;
        width: 100%;
        text-align: center;
        display: none;
        cursor: pointer;
    }

</style>



@endsection


<!--

[{
"id_clasificacion":6,
"nombre_clasificacion":"Tipo De Cristal",
"especificaciones":
    [   {"id_valor":14, "nombre_valor":"Monofocal"},
        {"id_valor":15,"nombre_valor":"Monofocal Lectura"},
        {"id_valor":16,"nombre_valor":"Bifocal"},
        {"id_valor":17,"nombre_valor":"Progresivo"}
    ]
},

{
"id_clasificacion":7,
"nombre_clasificacion":"Material De Cristal",
"especificaciones":
    [   {"id_valor":18,"nombre_valor":"CR 39"}
    ]
},

{
"id_clasificacion":8,
"nombre_clasificacion":"Filtro De Cristal",
"especificaciones":
    [   {"id_valor":19,"nombre_valor":"Filtro 1"},
        {"id_valor":20,"nombre_valor":"Filtro 2"}
    ]
},

{"id_clasificacion":9,
"nombre_clasificacion":"Fotocromatico",
"especificaciones":
    [   {"id_valor":21,"nombre_valor":"Transitions"}
    ]
}]

-->