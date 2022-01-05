@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-colours"> </i> CLASIFICACIONES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CREAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('clasificaciones.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE CLASIFICACIONES</p></div></a></div>
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
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Clasificación creada Exitosamente</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Nombre de Clasificación ya existe</div>@endif
    </div>

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Crear Clasificación</div>

    <form method="post" data-parsley-validate action="{{ route('clasificaciones.guardar') }}">
        @csrf
        <div class="label-campo">
            <label class="label-admin" for="nombre_clasificacion"><i id="lista" class="icon-dot-single"></i>Nombre Clasificación<i id="cont-icon" class="fa fa-language"></i></label>
            <input type="text" name="nombre_clasificacion" class="campo-admin" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="50" id="nombre_clasificacion" onkeyup="this.value=letras(this.value)">
        </div>
        <div id="error-nombre-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Escribe más letras en el Nombre</div>

        <div class="label-campo" id="clonar-hijo">
            <label class="label-admin" id="aux" onclick="clonar();"><i id="lista" class="icon-dot-single"></i>Especificaciones <i id="cont-icon" class="fa fa-plus mas"></i></label>
            <div class="campo-clonar">
                <input type="text" class="campo-admin campo-especificacion" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="50" onkeyup="this.value=letras2(this.value)">
                <div class="cerrar-campo" onclick="eliminarPariente(this.parentElement)"><i class="icon-cross"></i></div>
            </div>
        </div>

        <div id="agregar-mas" onclick="clonar();">Agregar</div>

        <div id="error-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Existen especificaciones vacías</div>
        <div id="error-repetido" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Existen especificaciones Repetidas</div>
        <!--<input type="hidden" name="estado_clasificacion" value="activo">-->

        <input type="hidden" name="especificaciones" id="especificaciones" multiple="multiple">

        <button class="boton-admin" id="crear-clasificacion"> Crear Clasificación</button>
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
    }

    .campo-clonar{
        float: left;
        //border:1px solid red;
        width: 100%;
    }

    .cerrar-campo{
        height: 38px;
        width: 38px;
        color:rgba(60,60,60,1);
        float: right;
        text-align: center;
        //margin-bottom:-38px;
        z-index:1000!important;
        padding-top:8px;
        background-color: rgba(255,255,255,0.8);
        border-right: 1px solid rgba(215,215,200,0.7);
        border-bottom: 1px solid rgba(215,215,200,0.7);
        cursor: pointer;
    }.cerrar-campo:hover{
        color:rgba(200,50,50,1);
        font-size:17px;
    }

    .campo-especificacion{
        width: calc(100% - 38px);
    }

    .mas:hover{
        color:rgba(0,200,200,1);
        font-size:17px;
    }

    #error-vacio,#error-repetido,#error-nombre-vacio{
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
    .label-campo{
        padding: 10px 20px 10px 20px;
    }
    #iconos-titulo{
        box-shadow: 0px 0px 0px white;
    }
    #content-mensaje{
        width: calc(100% - 40px);
        margin-left: 20px;
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
            aux = "de: "+out;
            aux2 = "Nombre de "+out+":";
        }else{
            aux = "";
            aux2 = "Nombre";
        }
        document.getElementById("aux").innerHTML = "<i id='lista' class='icon-dot-single'></i>Especificaciones "+aux+"<i id='cont-icon' class='fa fa-plus mas'></i>";
        document.getElementById("agregar-mas").innerHTML = "Agregar "+out;
        var n = document.getElementsByClassName("campo-especificacion");

        for (i = 0; i < n.length; i++) {
            document.getElementsByClassName("campo-especificacion")[i].placeholder = aux2;
        }


        return out;
    }

    function letras2(string){//Solo letras
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

        return out;
    }

    function clonar(){
        console.log("entro");
        var campo = document.getElementsByClassName("campo-clonar")[0];
        var clone = campo.cloneNode(true);
        clone.children[0].value="";
        clone.value="";
        document.getElementById("clonar-hijo").appendChild(clone);

        var n = document.getElementsByClassName("campo-especificacion");
        document.getElementsByClassName("campo-especificacion")[n.length-1].focus();

    }

    function eliminarPariente(pariente){
        var n = document.getElementsByClassName("campo-especificacion");
        if(n.length != 1){
            pariente.remove();
        }
    }

    $("#crear-clasificacion").click(function(){

        document.getElementById("error-vacio").style.display = "none";
        document.getElementById("error-repetido").style.display = "none";
        document.getElementById("error-nombre-vacio").style.display = "none";
        var valid = true;
        var especificaciones=[];
        
        var n = document.getElementsByClassName("campo-especificacion");

        for (i = 0; i < n.length; i++) {
            var x = document.getElementsByClassName("campo-especificacion")[i].value;
            if(x == ""){
                document.getElementById("error-vacio").style.display = "inline";
                valid = false;
            }else{
                especificaciones.push(x);
            }
        }
       
        var verifica = especificaciones.filter(function(item, index, array) {
          return array.indexOf(item) === index;
        })

        if(verifica.length != especificaciones.length){
            document.getElementById("error-repetido").style.display = "inline";
            valid = false;
        }

        var nombre = document.getElementById("nombre_clasificacion").value;
        if(nombre.length<3){
            document.getElementById("error-nombre-vacio").style.display = "inline";
            document.getElementById('nombre_clasificacion').focus();
            valid = false;
        }


        if (valid==true) {
            vector=JSON.stringify(verifica);
            document.getElementById('especificaciones').value=vector;
        }

        return valid;
    });

</script>

@endsection


