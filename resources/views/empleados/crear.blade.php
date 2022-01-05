@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-users"> </i> USUARIOS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CREAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('empleados.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE USUARIOS</p></div></a></div>
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
    
    <div id="content-mensaje" onclick="this.style.display='none'">
        @if($estatus=="exito")<div id="mensaje-exito"> <i class="fa fa-check-circle"></i> Usuario creado con Éxito</div>@endif
        @if($estatus=="errorEmail" || $estatus=="errorEmailIdentificacion")<div id="mensaje-error"> <i class="fa fa-times-circle"></i>&nbsp; Correo Electrónico de Usuario ya existe.</div>@endif
        @if($estatus=="errorIdentificacion" || $estatus=="errorEmailIdentificacion")<div id="mensaje-error"> <i class="fa fa-times-circle"></i>&nbsp; N° de Identificación de Usuario ya existe.</div>@endif
    </div>
    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Crear Usuario</div>

    <form method="post" action="{{ route('empleados.guardar') }}">
        @csrf
            <div class="label-campo">
                <label class="label-admin" id="label_nombre_empleado" for="nombre_empleado"><i id="lista" class="icon-dot-single"></i>Nombres<i id="cont-icon" class="icon-user"></i></label>
                <input type="text" name="nombre_empleado" id="nombre_empleado" class="campo-admin" placeholder="Nombres" spellcheck="false" autocomplete="off" maxlength="35" required onkeyup="this.value=letras(this.value)">
            </div>
            <div class="label-campo">
                <label class="label-admin" id="label_apellido_empleado" for="apellido_empleado"><i id="lista" class="icon-dot-single"></i>Apellidos<i id="cont-icon" class="icon-add-user"></i></label>
                <input type="text" name="apellido_empleado" id="apellido_empleado" class="campo-admin" placeholder="Apellidos" spellcheck="false" autocomplete="off" maxlength="35" required onkeyup="this.value=letras(this.value)">
            </div>

            <div class="label-campo">
                <label class="label-admin" id="label_telefono_empleado" for="telefono_empleado"><i id="lista" class="icon-dot-single"></i>Teléfono<i id="cont-icon" class="icon-mobile"></i></label>
                <input type="text" name="telefono_empleado" id="telefono_empleado"class="campo-admin" placeholder="Teléfono" spellcheck="false" autocomplete="off" maxlength="20" required onkeyup="this.value=telefono(this.value)">
            </div>

            <div class="label-campo">
                <div class="label-admin" id="label_tipo_identificacion_empleado"><i id="lista" class="icon-dot-single"></i>Tipo de identificación<i id="cont-icon" class="icon-credit-card"></i></div>
                <select class="campo-admin" name="tipo_identificacion_empleado" id="tipo_identificacion_empleado" required>
                    <option value="">Tipo de identificación</option>
                    @foreach($tipos_identificacion as $tipo_identificacion)
                        <option value="{{$tipo_identificacion->id_tipo_identificacion}}">{{$tipo_identificacion->nombre_tipo_identificacion}}</option>
                    @endforeach
                </select>
            </div>

            <div class="label-campo">
                <label class="label-admin" id="label_identificacion_empleado" for="identificacion_empleado"><i id="lista" class="icon-dot-single"></i>Identificación<i id="cont-icon" class="icon-credit-card"></i></label>
                <input type="text" name="identificacion_empleado" id="identificacion_empleado" class="campo-admin" placeholder="N° de identificación" spellcheck="false" autocomplete="off" maxlength="25" required onkeyup="this.value=identificacion(this.value)">
            </div>

            <div class="label-campo">
                <label class="label-admin" id="label_correo_empleado" for="correo_empleado"><i id="lista" class="icon-dot-single"></i>Correo Electrónico<i id="cont-icon" class="icon-mail"></i></label>
                <input type="email" name="correo_empleado" id="correo_empleado" class="campo-admin" placeholder="Correo Electrónico" spellcheck="false" autocomplete="off" maxlength="35" required>
            </div>

            <div class="label-campo" style="width: 66.6%;">
                 <label class="label-admin" id="label_direccion_empleado" for="direccion_empleado"><i id="lista" class="icon-dot-single"></i>Dirección<i id="cont-icon" class="icon-location"></i></label>
                <textarea name="direccion_empleado" id="direccion_empleado" class="campo-admin" placeholder="Dirección" spellcheck="false" autocomplete="off" maxlength="120" style="margin-bottom:-5px!important;" onkeyup="this.value=direccion(this.value)" required></textarea>
            </div>
{{--
            <div class="label-campo">
                <div class="label-admin" id="label_rol_empleado"><i id="lista" class="icon-dot-single"></i>Rol de empleado<i id="cont-icon" class="icon-creative-commons-attribution"></i></div>
                <select class="campo-admin" name="rol_empleado" id="rol_empleado" required>
                    <option value="">Rol de empleado</option>
                    @foreach($roles as $rol)
                        <option value="{{$rol->id}}">{{$rol->name}}</option>
                    @endforeach
                </select>
            </div>
--}}
        <div style="width: 100%; float: left;"></div>
        <button class="boton-admin" id="crear-empleado" style="max-width: 300px;float: left;margin-left: 20px;"> Crear Usuario</button>

    </form>

</div>


<style type="text/css">

    

    .label-campo{
        width: 33.3%;
        float: left;
        //border:1px solid red;
    }

    #salario{
        //width: 40px;
        margin-bottom:0px;
        float: right;
        //border:1px solid rgba(215,215,215,0.5);
        height:38px;
        margin-top:-38px;
        font-size: 12px;
        text-align: right;
        color:rgba(205,205,205,1)!important;
        cursor: pointer;
        padding: 26px 5px 10px 0px;
        letter-spacing: -0.5px;
        border-top:0px;
        line-height: 12px;
    }  

    #cont-icon{
        float: right!important;
        color:rgba(80,70,90,0.4);
    }

    #lista{
        color:rgba(80,70,90,0.5);
    }
    
    #mensaje-exito{
        padding: 8px;
        background-color:rgba(20,160,20,1);
        color:white;
        font-weight: 600;
        letter-spacing: -0.5px;
        border-radius: 2px;
        margin-bottom: 2px;
        font-size:15px;
    }

    .content-sucursal{
        width: 33.33%;
        height: 76px;
        float: left;
        border:1px solid rgba(215,215,215,0.9);
    }
    .nombre-sucursal{
        width: 100%;
        //border:1px solid blue;
        float: left;
        //height: 30px;
        font-size:15px;
        color:rgba(80,70,90,1);
        letter-spacing: -0.5px;
        padding:20px 20px 0px 30px;
        //text-align: center;
        font-weight: 500;
    }
    #check{
        float:right;
        //margin-right: -10px;
    }
    .content-imagen-sucursal{
        width: 100%;
        height: 46px;
        float: left;
        //border: 1px solid black;
        display: flex;
        justify-content: center;
        background-color: rgba(255,255,255,0.5);
        border:1px solid rgba(215,215,215,0.9);
        border-top:0px;
    }
    .label-sucursal{
        cursor: pointer;
    }
    
</style>
<script type="text/javascript">
    function obtenerSucursales(){
        var checkboxs = document.getElementsByClassName('checkSucursal');
        sucursales = [];
        for (i = 0; i < checkboxs.length; i++) {

            var checkId = document.getElementsByClassName('checkSucursal')[i].checked;
            //console.log(values);

            //var checks = document.getElementsByClassName(nombreModulo)[i].checked;
            //console.log(checks);

            if (checkId==true) {
                var idSucursal = document.getElementsByClassName('checkSucursal')[i].value;
                sucursales.push(idSucursal);
            }         
        }
        sucursales=JSON.stringify(sucursales);
        //console.log(sucursales);
        document.getElementById('vector-sucursales').value = sucursales;
    }


    $("#crear-empleado").click(function(){


        var labels = document.getElementsByClassName('label-admin');
        for (i = 0; i < labels.length; i++) {

            document.getElementsByClassName('label-admin')[i].style.color="rgba(80,70,90,1)";
            document.getElementsByClassName('label-admin')[i].style.fontWeight="450";
        }
        document.getElementById('nombre_sucursales').style.color="rgba(80,70,90,1)";
        document.getElementById('nombre_sucursales').style.fontWeight="450";
        document.getElementById('nombre_sucursales').innerHTML="<i id='lista' class='icon-dot-single'></i>Selecciona las sucursales que pertenece";
        document.getElementById("label_nombre_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Nombres<i id='cont-icon' class='icon-user'></i>";
        document.getElementById("label_apellido_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Apellidos<i id='cont-icon' class='icon-add-user'></i>";
        document.getElementById("label_telefono_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Teléfono<i id='cont-icon' class='icon-mobile'></i>";
        document.getElementById("label_tipo_identificacion_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Tipo de identificación<i id='cont-icon' class='icon-credit-card'></i>";
        document.getElementById("label_identificacion_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Identificación<i id='cont-icon' class='icon-credit-card'></i>";
        document.getElementById("label_correo_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Correo Electrónico<i id='cont-icon' class='icon-mail'></i>";
        document.getElementById("label_direccion_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Dirección<i id='cont-icon' class='icon-location'></i>";
        document.getElementById("label_salario_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Salario<i id='cont-icon' class='icon-credit'></i>";
        document.getElementById("label_fecha_contratacion_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Fecha de Contratación<i id='cont-icon' class='icon-calendar'></i>";
        document.getElementById("label_rol_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Rol de empleado<i id='cont-icon' class='icon-creative-commons-attribution'></i>";
        document.getElementById("label_contrato_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Tipo de Contrato<i id='cont-icon' class='icon-book'></i>";


        var expr1=/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g;/*solo letras mayusculas, minusculas, espacios y ñ*/
        var expr11=/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g;/*solo letras mayusculas, minusculas, espacios y ñ*/
        var expr2= /^[0-9\+-\s]+$/;
        var expr3= /^[0-9\-\s]+$/;
        var expr4= /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
        var expr5= /^[a-zA-Z0-9À-ÿ\.\#-\s]+$/;
        var expr6= /^[0-9+]+$/;

        var valid = true;

        var nombre = $("#nombre_empleado").val();
        var apellido = $("#apellido_empleado").val();
        var telefono = $("#telefono_empleado").val();
        var tipoIdentificacion = $("#tipo_identificacion_empleado").val();
        var identificacion = $("#identificacion_empleado").val();
        var correo = $("#correo_empleado").val();
        var direccion = $("#direccion_empleado").val();
        var salario = $("#salario_empleado").val();
        var fecha = $("#fecha_contratacion_empleado").val();
        var rol = $("#rol_empleado").val();
        var contrato = $("#contrato_empleado").val();

        obtenerSucursales();
        var listaSucursales = document.getElementById('vector-sucursales').value;

        if (nombre=="" || nombre.length<3){
            document.getElementById("label_nombre_empleado").style.color="red";
            document.getElementById("label_nombre_empleado").style.fontWeight="500";
            document.getElementById("label_nombre_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe mas letras<i id='cont-icon' class='icon-user'></i>";
            document.getElementById('nombre_empleado').focus();
            valid=false;
        }else if(!expr1.test(nombre)){
            document.getElementById("label_nombre_empleado").style.color="red";
            document.getElementById("label_nombre_empleado").style.fontWeight="500";
            document.getElementById("label_nombre_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe solo texto<i id='cont-icon' class='icon-user'></i>";
            document.getElementById('nombre_empleado').value="";
            document.getElementById('nombre_empleado').focus();
            valid=false;
        }

        if (apellido=="" || apellido.length<3){
            document.getElementById("label_apellido_empleado").style.color="red";
            document.getElementById("label_apellido_empleado").style.fontWeight="500";
            document.getElementById("label_apellido_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe mas letras<i id='cont-icon' class='icon-add-user'></i>";
            document.getElementById('apellido_empleado').focus();
            valid=false;
        }else if(!expr11.test(apellido)){
            //alert(apellido);
            document.getElementById("label_apellido_empleado").style.color="red";
            document.getElementById("label_apellido_empleado").style.fontWeight="500";
            document.getElementById("label_apellido_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe solo texto<i id='cont-icon' class='icon-add-user'></i>";
            document.getElementById('apellido_empleado').value="";
            document.getElementById('apellido_empleado').focus();
            valid=false;
            //console.log("entro");
        }

        if (telefono=="" || telefono.length<7){
            document.getElementById("label_telefono_empleado").style.color="red";
            document.getElementById("label_telefono_empleado").style.fontWeight="500";
            document.getElementById("label_telefono_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe mas números<i id='cont-icon' class='icon-mobile'></i>";
            document.getElementById('telefono_empleado').focus();
            valid=false;
        }else if(!expr2.test(telefono)){
            document.getElementById("label_telefono_empleado").style.color="red";
            document.getElementById("label_telefono_empleado").style.fontWeight="500";
            document.getElementById("label_telefono_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe solo números<i id='cont-icon' class='icon-mobile'></i>";
            document.getElementById('telefono_empleado').value="";
            document.getElementById('telefono_empleado').focus();
            valid=false;
        }

        if (tipoIdentificacion==""){
            document.getElementById("label_tipo_identificacion_empleado").style.color="red";
            document.getElementById("label_tipo_identificacion_empleado").style.fontWeight="500";
            document.getElementById("label_tipo_identificacion_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Seleccione tipo de identificación<i id='cont-icon' class='icon-credit-card'></i>";
            document.getElementById('tipo_identificacion_empleado').focus();
            valid=false;
        }

        if (identificacion=="" || identificacion.length<7){
            document.getElementById("label_identificacion_empleado").style.color="red";
            document.getElementById("label_identificacion_empleado").style.fontWeight="500";
            document.getElementById("label_identificacion_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe más números<i id='cont-icon' class='icon-credit-card'></i>";
            document.getElementById('identificacion_empleado').focus();
            valid=false;
        }else if(!expr3.test(identificacion)){
            document.getElementById("label_identificacion_empleado").style.color="red";
            document.getElementById("label_identificacion_empleado").style.fontWeight="500";
            document.getElementById("label_identificacion_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe solo números<i id='cont-icon' class='icon-credit-card'></i>";
            document.getElementById('identificacion_empleado').value="";
            document.getElementById('identificacion_empleado').focus();
            valid=false;
        }

        if (correo=="" || correo.length<14){
            document.getElementById("label_correo_empleado").style.color="red";
            document.getElementById("label_correo_empleado").style.fontWeight="500";
            document.getElementById("label_correo_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe más carácteres<i id='cont-icon' class='icon-mail'></i>";
            document.getElementById('correo_empleado').focus();
            valid=false;
        }else if(!expr4.test(correo)){
            document.getElementById("label_correo_empleado").style.color="red";
            document.getElementById("label_correo_empleado").style.fontWeight="500";
            document.getElementById("label_correo_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Formato incorrecto de e-mail<i id='cont-icon' class='icon-mail'></i>";
            //document.getElementById('correo_empleado').value="";
            document.getElementById('correo_empleado').focus();
            valid=false;
        }

        if (direccion=="" || direccion.length<20){
            document.getElementById("label_direccion_empleado").style.color="red";
            document.getElementById("label_direccion_empleado").style.fontWeight="500";
            document.getElementById("label_direccion_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe más texto<i id='cont-icon' class='icon-location'></i>";
            document.getElementById('direccion_empleado').focus();
            valid=false;
        }else if(!expr5.test(direccion)){
            document.getElementById("label_direccion_empleado").style.color="red";
            document.getElementById("label_direccion_empleado").style.fontWeight="500";
            document.getElementById("label_direccion_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe letras numeros - . #<i id='cont-icon' class='icon-location'></i>";
            //document.getElementById('correo_empleado').value="";
            document.getElementById('direccion_empleado').focus();
            valid=false;
        }

        if (salario=="" || salario.length<5){
            document.getElementById("label_salario_empleado").style.color="red";
            document.getElementById("label_salario_empleado").style.fontWeight="500";
            document.getElementById("label_salario_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Escribe más números<i id='cont-icon' class='icon-credit'></i>";
            document.getElementById('salario_empleado').focus();
            valid=false;
        }else if(!expr6.test(salario)){
            document.getElementById("label_salario_empleado").style.color="red";
            document.getElementById("label_salario_empleado").style.fontWeight="500";
            document.getElementById("label_salario_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Ingresa solo números<i id='cont-icon' class='icon-credit'></i>";
            //document.getElementById('correo_empleado').value="";
            document.getElementById('salario_empleado').focus();
            valid=false;
        }

        if (fecha==""){
            document.getElementById("label_fecha_contratacion_empleado").style.color="red";
            document.getElementById("label_fecha_contratacion_empleado").style.fontWeight="500";
            document.getElementById("label_fecha_contratacion_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Ingresa la fecha de contratación<i id='cont-icon' class='icon-calendar'></i>";
            document.getElementById('fecha_contratacion_empleado').focus();
            valid=false;
        }

        if (rol==""){
            document.getElementById("label_rol_empleado").style.color="red";
            document.getElementById("label_rol_empleado").style.fontWeight="500";
            document.getElementById("label_rol_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Selecciona Rol de empleado<i id='cont-icon' class='icon-creative-commons-attribution'></i>";
            document.getElementById('rol_empleado').focus();
            valid=false;
        }

        if (contrato==""){
            document.getElementById("label_contrato_empleado").style.color="red";
            document.getElementById("label_contrato_empleado").style.fontWeight="500";
            document.getElementById("label_contrato_empleado").innerHTML="<i id='lista' class='icon-dot-single'></i>Selecciona tipo de Contrato<i id='cont-icon' class='icon-book'></i>";
            document.getElementById('contrato_empleado').focus();
            valid=false;
        }

        if (listaSucursales=="[]"){
            document.getElementById("nombre_sucursales").style.color="red";
            document.getElementById("nombre_sucursales").style.fontWeight="500";
            document.getElementById("nombre_sucursales").innerHTML="Selecciona al menos una sucursal";
            valid=false;
        }

        return valid;
    });

    function letras(string){//Solo letras
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ ';//Caracteres validos
        
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
        
        return out;
    }

    function telefono(string){//Solo letras
        var out = '';
        var filtro = '1234567890+ ';//Caracteres validos
        
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }

    function identificacion(string){//Solo letras
        var out = '';
        var filtro = '1234567890-';//Caracteres validos
        
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }

    function salario(string){//Solo letras
        var out = '';
        var filtro = '1234567890.';//Caracteres validos
        
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }

    function direccion(string){//Solo letras
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ 1234567890-+#/.,';//Caracteres validos
        
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

        return out;
    }

    function salarioBase(){
        document.getElementById('salario_empleado').value="877803";
    }


</script>
@endsection


