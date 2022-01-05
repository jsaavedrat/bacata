@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-bookmarks"> </i> MARCAS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CREAR</div>
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
<div id="elemento-admin">
    <div id="content-mensaje">
        @if($mensaje=="exito")<div id="mensaje-exito"> <i class="fa fa-check-circle"></i> Marca creada Exitosamente</div>@endif
        @if($mensaje=="error")<div id="mensaje-error"> <i class="fa fa-times-circle"></i>&nbsp; Nombre de Marca ya existe</div>@endif
    </div>

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Crear Marca de Productos</div>

    <form method="post" data-parsley-validate action="{{ route('marcas.guardar') }}">
        @csrf
        <div class="label-campo">
            <label class="label-admin" for="nombre_marca"><i id="lista" class="icon-dot-single"></i>Nombre Marca Producto<i id="cont-icon" class="fa fa-language"></i></label>
            <input type="text" name="nombre_marca" class="campo-admin" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="25" id="nombre_marca" onkeyup="this.value=letras(this.value)">
        </div>
        <div id="error-nombre-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Escribe más letras en el Nombre</div>


        <div class="label-campo"  style="cursor: initial!important;">
            <label class="label-admin" id="aux" style="cursor: initial!important;margin-bottom:1%;"><i id="lista" class="icon-dot-single"></i>Esta Marca Vende: <i id="cont-icon" class="icon-check"></i></label>
             @foreach($tipo_productos as $tipo_producto)
                <label class="label-check" id="label-check{{$loop->iteration}}">
                    <p><i class="icon-check icon-ch" id="icon-check{{$loop->iteration}}"></i>{{$tipo_producto->nombre_tipo_producto}}</p>
                    <input type="checkbox" id="campo-check{{$loop->iteration}}" class="campo-check" value="{{$tipo_producto->id_tipo_producto}}" onclick="seleccionar('label-check{{$loop->iteration}}','campo-check{{$loop->iteration}}','icon-check{{$loop->iteration}}');">
                </label>
            @endforeach
        </div>

        <div id="error-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Seleccione al menos un tipo de Producto</div>

        <input type="hidden" name="tipo_productos" id="tipo_productos" multiple="multiple">

        <button class="boton-admin" id="crear-marca"> Crear Marca</button>
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
            document.getElementById("aux").innerHTML = "<i id='lista' class='icon-dot-single'></i>"+out+" vende los tipos de productos:"+"<i id='cont-icon' class='icon-check'></i>";
            //document.getElementById("label_categoria_tipo_producto").innerHTML = "<i id='lista' class='icon-dot-single'></i>"+out+" pertenece a la Categoría:"+"<i id='cont-icon' class='icon-colours'></i>";
        }else{
            //document.getElementById("label_categoria_tipo_producto").innerHTML = "<i id='lista' class='icon-dot-single'></i>Categoría<i id='cont-icon' class='icon-credit-card'></i>";
            document.getElementById("aux").innerHTML = "<i id='lista' class='icon-dot-single'></i>Esta Marca Vende:<i id='cont-icon' class='icon-check'></i>";
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
        if(nombre.length<3){
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
            document.getElementById("error-vacio").style.display = "inline";
            valid = false;
        }else{
            vector = JSON.stringify(tipo_productos);
            document.getElementById('tipo_productos').value=vector;
        }

        


        return valid;
    });

</script>

@endsection


