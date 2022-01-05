@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-bookmark"> </i> MODELOS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; EDITAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('modelos.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE MODELOS</p></div></a></div>
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

    <div id="texto-titulo"> <i class="icon-pencil"> </i> EDITAR MODELO {{$modelo->nombre_modelo}}</div>

    <form method="post" data-parsley-validate action="{{ route('modelos.modificar') }}">
        @csrf
        <div id="error-nombre-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Escribe más letras en el Nombre</div>
        <div class="label-campo">
            <label class="label-admin" for="nombre_modelo"><i id="lista" class="icon-dot-single"></i>Nombre Modelo<i id="cont-icon" class="fa fa-language"></i></label>
            <input type="text" name="nombre_modelo" class="campo-admin" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="25" id="nombre_modelo" onkeyup="this.value=letras(this.value)" value="{{$modelo->nombre_modelo}}">
        </div>
        
        <div id="error-descripcion-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Escribe una descripción más larga</div>
        <div class="label-campo">
            <label class="label-admin" for="descripcion_modelo"><i id="lista" class="icon-dot-single"></i>Descripción Modelo<i id="cont-icon" class="icon-location"></i></label>
            <textarea name="descripcion_modelo" class="campo-admin" placeholder="Descripcion" spellcheck="false" autocomplete="off" maxlength="100" style="height: 56px;" id="descripcion_modelo" onkeyup="this.value=descripcion(this.value)">{{$modelo->descripcion_modelo}}</textarea>
        </div>

        <input type="hidden" name="id_modelo" value="{{$modelo->id_modelo}}">

        <input type="hidden" name="id_marca" value="{{$modelo->id_marca}}">
        

        <button class="boton-admin" id="crear-modelo"> Editar Modelo</button>
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

    #error-nombre-vacio,#error-tipo-producto-vacio,#error-marca-vacio,#error-descripcion-vacio{
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
                
        return out;
    }

    function descripcion(string){//Solo letras
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

    $("#crear-modelo").click(function(){

        document.getElementById("error-nombre-vacio").style.display = "none";
        document.getElementById("error-descripcion-vacio").style.display = "none";

        var valid = true;

        var nombre = document.getElementById("nombre_modelo").value;
        if(nombre.length<3){
            document.getElementById("error-nombre-vacio").style.display = "inline";
            document.getElementById('nombre_modelo').focus();
            valid = false;
        }

        var descripcion = document.getElementById("descripcion_modelo").value;
        if(descripcion.length<11){
            document.getElementById("error-descripcion-vacio").style.display = "inline";
            document.getElementById('descripcion_modelo').focus();
            valid = false;
        }

        return valid;
    });

</script>

@endsection


