@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-calendar"> </i> CITAS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CREAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('citas.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE CITAS</p></div></a></div>
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
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::VISTA ACTUAL::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<div id="elemento-admin">
    <div id="content-mensaje" onclick="this.style='display:none'">
        @if($estatus=="exito")<div id="mensaje-exito"> <i class="fa fa-check-circle"></i> Cita registrada con éxito</div>@endif
        @if($estatus=="error_cita_posterior")<div id="mensaje-error"> <i class="fa fa-times-circle"></i>&nbsp; Hora de cita debe ser posterior a las 9:00 am</div>@endif
        @if($estatus=="error_cita_anterior_domingos")<div id="mensaje-error"> <i class="fa fa-times-circle"></i>&nbsp; Hora de cita debe ser anterior a las 5:00 pm los Domingos</div>@endif
        @if($estatus=="error_cita_posterior_l_s")<div id="mensaje-error"> <i class="fa fa-times-circle"></i>&nbsp; Hora de cita debe ser anterior a las 7:00 pm de Lunes a Sábado</div>@endif
    </div>
    
    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Registrar Cita</div>
     
    <form method="post" action="{{ route('citas.guardar') }}">
        @csrf

        <input type="hidden" name="id_paciente" id="id_paciente" value="">

        <div class="label-campo">
            <label class="label-admin" id="label-sucursal"><i id="lista" class="icon-dot-single"></i>Sucursal<i id="cont-icon" class="fa fa-home"></i></label>
            <select class="campo-admin" name="sucursal" id="sucursal">
                <option value="">Seleccione</option>
                @foreach($usuarios_sucursales as $usuario_sucursal)
                    <option value="{{$usuario_sucursal->id_sucursal}}">{{$usuario_sucursal->nombre_sucursal}}</option>
                @endforeach
            </select>
        </div>
        <div class="label-campo">
            <label class="label-admin" id="label-tipo-identificacion"><i id="lista" class="icon-dot-single"></i>Tipo de Identificación<i id="cont-icon" class="icon-credit-card"></i></label>
            <select class="campo-admin" name="tipo_identificacion" id="tipo_identificacion" onchange="tipo_documento();">
                <option value="">Seleccione</option>
                @foreach($tipos_identificacion as $tipo_identificacion)
                    <option value="{{$tipo_identificacion->id_tipo_identificacion}}">{{$tipo_identificacion->nombre_tipo_identificacion}}</option>
                @endforeach
            </select>
        </div>
        <div class="label-campo">
            <label class="label-admin" for="identificacion" id="label-identificacion"><i id="lista" class="icon-dot-single"></i>Identificación<i id="cont-icon" class="icon-documents"></i></label>
            <input type="text" name="identificacion" id="identificacion" class="campo-admin" placeholder="Identificación" spellcheck="false" autocomplete="off" maxlength="35" required disabled onkeyup="buscarIdentificacion(this.value);">
        </div>
        <div class="label-campo">
            <label class="label-admin" for="nombres_paciente" id="label-nombres-paciente"><i id="lista" class="icon-dot-single"></i>Nombres<i id="cont-icon" class="fa fa-language"></i></label>
            <input type="text" name="nombres_paciente" id="nombres_paciente" class="campo-admin" placeholder="Nombres" spellcheck="false" autocomplete="off" maxlength="35" required disabled onkeyup="this.value=letras(this.value)">
        </div>
        <div class="label-campo">
            <label class="label-admin" for="apellidos_paciente" id="label-apellidos-paciente"><i id="lista" class="icon-dot-single"></i>Apellidos<i id="cont-icon" class="fa fa-language"></i></label>
            <input type="text" name="apellidos_paciente" id="apellidos_paciente" class="campo-admin" placeholder="Apellidos" spellcheck="false" autocomplete="off" maxlength="35" required disabled onkeyup="this.value=letras(this.value)">
        </div>
        <div class="label-campo">
            <label class="label-admin" for="telefono_paciente" id="label-telefono-paciente"><i id="lista" class="icon-dot-single"></i>Teléfono<i id="cont-icon" class="icon-phone"></i></label>
            <input type="text" name="telefono_paciente" id="telefono_paciente" class="campo-admin" placeholder="Teléfono" spellcheck="false" autocomplete="off" maxlength="35" required disabled>
        </div>
        <div class="label-campo" style="width: 66.67%;">
            <label class="label-admin" for="correo_paciente" id="label-correo-paciente"><i id="lista" class="icon-dot-single"></i>Correo Electrónico<i id="cont-icon" class="icon-email"></i></label>
            <input type="text" name="correo_paciente" id="correo_paciente" class="campo-admin" placeholder="Correo Electrónico" spellcheck="false" autocomplete="off" maxlength="35" required disabled>
        </div>
        <div class="label-campo">
            <label class="label-admin" for="fecha_nacimiento" id="label-fecha-nacimiento"><i id="lista" class="icon-dot-single"></i>Fecha de Nacimiénto<i id="cont-icon" class="icon-calendar"></i></label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="campo-admin" placeholder="Fecha de Nacimiénto" spellcheck="false" autocomplete="off" maxlength="35" required disabled>
        </div>
        <div class="label-campo">
            <label class="label-admin" for="genero_paciente" id="label-genero"><i id="lista" class="icon-dot-single"></i>Género<i id="cont-icon" class="fa fa-venus-mars"></i></label>
            <select class="campo-admin" name="genero" id="genero_paciente" disabled>
                <option value="">Seleccione</option>
                <option value="Femenino">Femenino</option>
                <option value="Masculino">Masculino</option>
            </select>
        </div>
        <div class="label-campo" style="width: 66.67%;">
            <label class="label-admin" for="direccion_paciente" id="label-direccion-paciente"><i id="lista" class="icon-dot-single"></i>Dirección<i id="cont-icon" class="icon-location"></i></label>
            <textarea name="direccion_paciente" id="direccion_paciente" class="campo-admin" placeholder="Dirección" spellcheck="false" autocomplete="off" maxlength="60" required disabled></textarea>
        </div>
        <div class="label-campo">
            <label class="label-admin" for="fecha_cita" id="label-fecha-cita"><i id="lista" class="icon-dot-single"></i>Fecha Cita<i id="cont-icon" class="icon-calendar"></i></label>
            <input type="date" name="fecha_cita" id="fecha_cita" class="campo-admin" spellcheck="false" autocomplete="off" maxlength="35" required>
        </div>
        <div class="label-campo">
            <label class="label-admin" for="hora_cita" id="label-hora-cita"><i id="lista" class="icon-dot-single"></i>Hora<i id="cont-icon" class="icon-clock"></i></label>
            <input type="time" name="hora_cita" id="hora_cita" class="campo-admin" spellcheck="false" autocomplete="off" maxlength="35" required>
        </div>

        <div style="width: 100%;display: flex;justify-content: center;">
            <button class="boton-admin" id="crear-cita">Registrar Cita</button>
        </div>

        
    </form>
</div>


<style type="text/css">
   #elemento-admin{
        margin-top:20px!important;
        /*float: none;
        max-width: 450px;
        margin-left: auto;
        margin-right: auto;
        left:0;
        right: 0;
        overflow: hidden;*/
        border:1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 60px rgba(100,100,120,0.3);
        padding-bottom:20px;
        background-color: rgba(225,225,235,0.2);
   }
   .label-campo{
        /*width: 100%;*/
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
    #crear-cita {
        max-width: 300px;
        float: none;
    }
</style>

<script type="text/javascript">

    n = 0;
    buscar = false;

    window.setInterval(function(){
            n++;
            if(n == 3 && buscar == true){
                document.getElementById("identificacion").disabled = true;
                buscarPaciente();
            }
            if (n == 10){
                n = 4;
            }
    },1000);

    function tipo_documento(){
            document.getElementById("identificacion").disabled = false;
            document.getElementById("identificacion").focus();
    }


    function buscarIdentificacion(identificacion){

    		console.log("identificacion: "+identificacion);
        

            document.getElementById("id_paciente").value = "";
            document.getElementById("nombres_paciente").value = "";
            document.getElementById("apellidos_paciente").value = "";
            document.getElementById("telefono_paciente").value = "";
            document.getElementById("correo_paciente").value = "";
            document.getElementById("fecha_nacimiento").value = "";
            document.getElementById("genero_paciente").value = "";
            
            document.getElementById("nombres_paciente").disabled = true;
            document.getElementById("apellidos_paciente").disabled = true;
            document.getElementById("telefono_paciente").disabled = true;
            document.getElementById("correo_paciente").disabled = true;
            $("#genero_paciente").val($("#genero option:first").val());
            document.getElementById("direccion_paciente").disabled = true;
            document.getElementById("fecha_nacimiento").disabled = true;

            $("#historial-paciente").empty();

            if(identificacion.length >= 6){
                    buscar = true;
                    n = 0;
            }else{
                    buscar = false;
            }
    }

    function buscarPaciente(){
            var tipo_identificacion = document.getElementById("tipo_identificacion").value;
            var identificacion = document.getElementById("identificacion").value;

            console.log(tipo_identificacion);
            console.log(identificacion);

            var url="{{route('examenes.paciente')}}";
            var datos = {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "tipo_identificacion": tipo_identificacion,
                "identificacion": identificacion
            };
            $.ajax({
                type: 'POST',
                url: url,
                data: datos,
                success: function(data) {
                    console.log("success");
                    var paciente = JSON.parse(data);
                    
                    document.getElementById("identificacion").disabled = false;
                    document.getElementById("identificacion").focus();

                    if(paciente != null){
                            console.log(paciente);

                            document.getElementById("id_paciente").value = paciente.id_paciente;

                            document.getElementById("nombres_paciente").value = paciente.nombres_paciente;
                            document.getElementById("apellidos_paciente").value = paciente.apellidos_paciente;
                            document.getElementById("telefono_paciente").value = paciente.telefono_paciente;
                            document.getElementById("correo_paciente").value = paciente.correo_paciente;
                            document.getElementById("direccion_paciente").value = paciente.direccion_paciente;
                            document.getElementById("fecha_nacimiento").value = paciente.fecha_nacimiento;
                            document.getElementById("genero_paciente").value = paciente.genero;

                        }else{
                            console.log("el paciente no existe");
                            document.getElementById("id_paciente").value = "";
                            document.getElementById("nombres_paciente").disabled = false;
                            document.getElementById("apellidos_paciente").disabled = false;
                            document.getElementById("telefono_paciente").disabled = false;
                            document.getElementById("correo_paciente").disabled = false;
                            document.getElementById("direccion_paciente").disabled = false;
                            document.getElementById("fecha_nacimiento").disabled = false;
                            document.getElementById("genero_paciente").disabled = false;
                            
                        }
                    

                },
                error: function(data) {
                    console.log("error");
                }
            });

    }


        crear_cita = true;
        $("#crear-cita").click(function(){
            var valid = true;

            var v_sucursal = document.getElementById("sucursal").value;
            var v_tipo_identificacion = document.getElementById("tipo_identificacion").value;
            var v_identificacion = document.getElementById("identificacion").value;
            var v_nombres = document.getElementById("nombres_paciente").value;
            var v_apellidos = document.getElementById("apellidos_paciente").value;
            var v_telefono = document.getElementById("telefono_paciente").value;
            var v_correo = document.getElementById("correo_paciente").value;
            var v_fecha_nacimiento = document.getElementById("fecha_nacimiento").value;
            var v_genero = document.getElementById("genero_paciente").value;
            var v_direccion = document.getElementById("direccion_paciente").value;
            var v_fecha_cita = document.getElementById("fecha_cita").value;
            var v_hora_cita = document.getElementById("hora_cita").value;

            if(v_sucursal == ""){
                valid = false;
                document.getElementById("label-sucursal").style.color = "red";
                document.getElementById("label-sucursal").innerHTML = '<i id="lista" class="icon-dot-single"></i>Seleccione Sucursal<i id="cont-icon" class="fa fa-home"></i>';
            }else{
                document.getElementById("label-sucursal").style.color = "black";
                document.getElementById("label-sucursal").innerHTML = '<i id="lista" class="icon-dot-single"></i>Sucursal<i id="cont-icon" class="fa fa-home"></i>';
            }

            if(v_tipo_identificacion == ""){
                valid = false;
                document.getElementById("label-tipo-identificacion").style.color = "red";
                document.getElementById("label-tipo-identificacion").innerHTML = '<i id="lista" class="icon-dot-single"></i>Tipo de Identificación<i id="cont-icon" class="icon-credit-card"></i>';
            }else{
                document.getElementById("label-tipo-identificacion").style.color = "black";
                document.getElementById("label-tipo-identificacion").innerHTML = '<i id="lista" class="icon-dot-single"></i>Tipo de Identificación<i id="cont-icon" class="icon-credit-card"></i>';
            }

            if(v_identificacion == ""){
                valid = false;
                document.getElementById("label-identificacion").style.color = "red";
                document.getElementById("label-identificacion").innerHTML = '<i id="lista" class="icon-dot-single"></i>Ingrese Identificación<i id="cont-icon" class="icon-documents"></i>';
            }else{
                document.getElementById("label-identificacion").style.color = "black";
                 document.getElementById("label-identificacion").innerHTML = '<i id="lista" class="icon-dot-single"></i>Identificación<i id="cont-icon" class="icon-documents"></i>';
            }

            if(v_nombres == ""){
                valid = false;
                document.getElementById("label-nombres-paciente").style.color = "red";
                document.getElementById("label-nombres-paciente").innerHTML = '<i id="lista" class="icon-dot-single"></i>Ingrese Nombres<i id="cont-icon" class="fa fa-language"></i>';
            }else{
                document.getElementById("label-nombres-paciente").style.color = "black";
                document.getElementById("label-nombres-paciente").innerHTML = '<i id="lista" class="icon-dot-single"></i>Nombres<i id="cont-icon" class="fa fa-language"></i>';
            }

            if(v_apellidos == ""){
                valid = false;
                document.getElementById("label-apellidos-paciente").style.color = "red";
                document.getElementById("label-apellidos-paciente").innerHTML = '<i id="lista" class="icon-dot-single"></i>Ingrese Apellidos<i id="cont-icon" class="fa fa-language"></i>';
            }else{
                document.getElementById("label-apellidos-paciente").style.color = "black";
                document.getElementById("label-apellidos-paciente").innerHTML = '<i id="lista" class="icon-dot-single"></i>Apellidos<i id="cont-icon" class="fa fa-language"></i>';
            }

            if(v_telefono == ""){
                valid = false;
                document.getElementById("label-telefono-paciente").style.color = "red";
                document.getElementById("label-telefono-paciente").innerHTML = '<i id="lista" class="icon-dot-single"></i>Ingrese Teléfono<i id="cont-icon" class="icon-phone"></i>';
            }else{
                document.getElementById("label-telefono-paciente").style.color = "black";
                document.getElementById("label-telefono-paciente").innerHTML = '<i id="lista" class="icon-dot-single"></i>Teléfono<i id="cont-icon" class="icon-phone"></i>';
            }

            if(v_correo == ""){
                valid = false;
                document.getElementById("label-correo-paciente").style.color = "red";
                document.getElementById("label-correo-paciente").innerHTML = '<i id="lista" class="icon-dot-single"></i>Ingrese Correo Electrónico<i id="cont-icon" class="icon-email"></i>';
            }else{
                document.getElementById("label-correo-paciente").style.color = "black";
                document.getElementById("label-correo-paciente").innerHTML = '<i id="lista" class="icon-dot-single"></i>Correo Electrónico<i id="cont-icon" class="icon-email"></i>';
            }

            if(v_fecha_nacimiento == ""){
                valid = false;
                document.getElementById("label-fecha-nacimiento").style.color = "red";
                document.getElementById("label-fecha-nacimiento").innerHTML = '<i id="lista" class="icon-dot-single"></i>Ingrese Fecha de Nacimiénto<i id="cont-icon" class="icon-calendar"></i>';
            }else{
                document.getElementById("label-fecha-nacimiento").style.color = "black";
                document.getElementById("label-fecha-nacimiento").innerHTML = '<i id="lista" class="icon-dot-single"></i>Fecha de Nacimiénto<i id="cont-icon" class="icon-calendar"></i>';
            }

            if(v_genero == ""){
                valid = false;
                document.getElementById("label-genero").style.color = "red";
                document.getElementById("label-genero").innerHTML = '<i id="lista" class="icon-dot-single"></i>Seleccione Género<i id="cont-icon" class="fa fa-venus-mars"></i>';
            }else{
                document.getElementById("label-genero").style.color = "black";
                document.getElementById("label-genero").innerHTML = '<i id="lista" class="icon-dot-single"></i>Género<i id="cont-icon" class="fa fa-venus-mars"></i>';
            }

            if(v_direccion == ""){
                valid = false;
                document.getElementById("label-direccion-paciente").style.color = "red";
                document.getElementById("label-direccion-paciente").innerHTML = '<i id="lista" class="icon-dot-single"></i>Ingrese Dirección<i id="cont-icon" class="icon-location"></i>';
            }else{
                document.getElementById("label-direccion-paciente").style.color = "black";
                document.getElementById("label-direccion-paciente").innerHTML = '<i id="lista" class="icon-dot-single"></i>Dirección<i id="cont-icon" class="icon-location"></i>';
            }

            if(v_fecha_cita == ""){
                valid = false;
                document.getElementById("label-fecha-cita").style.color = "red";
                document.getElementById("label-fecha-cita").innerHTML = '<i id="lista" class="icon-dot-single"></i>Seleccione Fecha Cita<i id="cont-icon" class="icon-calendar"></i>';
            }else{
                document.getElementById("label-fecha-cita").style.color = "black";
                document.getElementById("label-fecha-cita").innerHTML = '<i id="lista" class="icon-dot-single"></i>Fecha Cita<i id="cont-icon" class="icon-calendar"></i>';
            }

            if(v_hora_cita == ""){
                valid = false;
                document.getElementById("label-hora-cita").style.color = "red";
                document.getElementById("label-hora-cita").innerHTML = '<i id="lista" class="icon-dot-single"></i>Ingrese Hora<i id="cont-icon" class="icon-clock"></i>';
            }else{
                document.getElementById("label-hora-cita").style.color = "black";
                document.getElementById("label-hora-cita").innerHTML = '<i id="lista" class="icon-dot-single"></i>Hora<i id="cont-icon" class="icon-clock"></i>';
            }

            if(valid == true){

                document.getElementById("nombres_paciente").disabled = false;
                document.getElementById("apellidos_paciente").disabled = false;
                document.getElementById("telefono_paciente").disabled = false;
                document.getElementById("correo_paciente").disabled = false;
                document.getElementById("direccion_paciente").disabled = false;
                document.getElementById("fecha_nacimiento").disabled = false;
                document.getElementById("genero_paciente").disabled = false;
            }

            return valid;
    });
























    function letras(string){//Solo letras
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ ';//Caracteres validos
        
        
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
    </script>
@endsection


