@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-home"> </i> SUCURSALES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; EDITAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('sucursales.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE SUCURSALES</p></div></a></div>
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
    <div id="texto-titulo"> <i class="icon-pencil"> </i> Editar Sucursal</div>

    <form method="post" data-parsley-validate action="{{ route('sucursales.modificar') }}" enctype="multipart/form-data">
        @csrf
        <div class="label-campo">
            <div id="error-nombre" style="display: none;" onclick="this.style.display='none'">Escribe mas letras en el nombre</div>
            <label class="label-admin" for="nombre_sucursal"><i id="lista" class="icon-dot-single"></i>Nombre Sucursal<i id="cont-icon" class="fa fa-language"></i></label>
            <input type="text" name="nombre_sucursal" id="nombre_sucursal" class="campo-admin" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="25" onkeyup="this.value=letras(this.value)" value="{{$sucursal->nombre_sucursal}}" required>
        </div>

        <div class="label-campo">
            <div id="error-correo" style="display: none;" onclick="this.style.display='none'">Escribe mas letras en el correo</div>
            <label class="label-admin" for="correo_sucursal"><i id="lista" class="icon-dot-single"></i>Correo Electrónico<i id="cont-icon" class="icon-mail"></i></label>
            <input type="text" name="correo_sucursal" id="correo_sucursal" class="campo-admin" placeholder="Correo" spellcheck="false" autocomplete="off" maxlength="100" value="{{$sucursal->correo_sucursal}}" required>
        </div>

        <div class="label-campo">
            <div id="error-telefono" style="display: none;" onclick="this.style.display='none'">Escribe mas letras en el teléfono</div>
            <label class="label-admin" for="telefono_sucursal"><i id="lista" class="icon-dot-single"></i>Teléfono<i id="cont-icon" class="icon-mobile"></i></label>
            <input type="text" name="telefono_sucursal" id="telefono_sucursal" class="campo-admin" placeholder="Teléfono" spellcheck="false" autocomplete="off" maxlength="25" onkeyup="this.value=telefono(this.value)" value="{{$sucursal->telefono_sucursal}}" required>
        </div>

        <div class="label-campo">
            <div id="error-imagen" style="display: none;" onclick="this.style.display='none'">Selecciona una Imagen</div>
            <label class="label-admin" for="imagen_sucursal"><i id="lista" class="icon-dot-single"></i>Imagen Sucursal<i id="cont-icon" class="icon-image"></i></label>
            <input type="file" class="campo-admin" name="imagen_sucursal" id="imagen_sucursal">
        </div>

        <div class="label-campo">
            <div id="error-direccion" style="display: none;" onclick="this.style.display='none'">Escribe mas letras en la dirección</div>
            <label class="label-admin" for="direccion_sucursal"><i id="lista" class="icon-dot-single"></i>Dirección Sucursal<i id="cont-icon" class="icon-location"></i></label>
            <textarea name="direccion_sucursal" id="direccion_sucursal" class="campo-admin" placeholder="Dirección" spellcheck="false" autocomplete="off" maxlength="120" onkeyup="this.value=direccion(this.value)" required>{{$sucursal->direccion_sucursal}}</textarea>
        </div>

        <div class="label-campo">
            <div id="error-mapa" style="display: none;" onclick="this.style.display='none'">Ingrese el mapa de google Maps.</div>
            <label class="label-admin" for="mapa_sucursal"><i id="lista" class="icon-dot-single"></i>Mapa de sucursal en google Maps<i id="cont-icon" class="icon-location-pin"></i></label>
            <textarea name="mapa_sucursal" id="mapa_sucursal" class="campo-admin" placeholder="Pegue aquí el codigo del mapa" spellcheck="false" autocomplete="off" maxlength="2000" required>{{$sucursal->mapa_sucursal}}</textarea>
        </div>

        <input type="number" name="id_sucursal" value="{{$sucursal->id_sucursal}}" style="display: none;">

        <button class="boton-admin" id="crear-sucursal"> Editar Sucursal</button>
    </form>
</div>

<style type="text/css">

    .texto-color {
        float: left;
        height: 38px;
        width: 25%;
        font-size: 12px;
        padding-top: 8px;
    }

    #error-nombre,#error-direccion,#error-telefono,#error-imagen,#error-mapa,#error-correo,#error-colores{
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

        return out;
    }

    function direccion(string){//Solo letras
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

    function telefono(string){//Solo letras
        var out = '';
        var filtro = '1234567890+ ';//Caracteres validos
        
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }

    $("#crear-sucursal").click(function(){
        document.getElementById("error-nombre").style.display = "none";
        document.getElementById("error-direccion").style.display = "none";
        document.getElementById("error-telefono").style.display = "none";
        document.getElementById("error-imagen").style.display = "none";
        document.getElementById("error-colores").style.display = "none";
        document.getElementById("error-correo").style.display = "none";
        document.getElementById("error-mapa").style.display = "none";
        var valid = true;

        var nombre = document.getElementById("nombre_sucursal").value;
        var direccion = document.getElementById("direccion_sucursal").value;
        var telefono = document.getElementById("telefono_sucursal").value;
        var imagen = document.getElementById("imagen_sucursal").value;
        var color_1 = document.getElementById("color_1").value;
        console.log("color1: "+color_1);
        var color_2 = document.getElementById("color_2").value;
        console.log("color2: "+color_2);
        var mapa_sucursal = document.getElementById("mapa_sucursal").value;
        var correo_sucursal = document.getElementById("correo_sucursal").value;

        if(nombre.length < 5){
            valid = false;
            document.getElementById("error-nombre").style.display = "inline";
        }

        if(correo_sucursal.length < 5){
            valid = false;
            document.getElementById("error-correo").style.display = "inline";
        }

        if(direccion.length < 10){
            valid = false;
            document.getElementById("error-direccion").style.display = "inline";
        }

        if(telefono.length < 7){
            valid = false;
            document.getElementById("error-telefono").style.display = "inline";
        }

        // if( color_1 == "#000000" && color_2 == "#000000"){
        //     valid = false;
        //     document.getElementById("error-colores").style.display = "inline";
        // }

        if(mapa_sucursal.length < 5){
            valid = false;
            document.getElementById("error-mapa").style.display = "inline";
        }

        return valid;
    });

    $(document).on('change','input[type="file"]',function(){

        var fileName = this.files[0].name;
        var fileSize = this.files[0].size;

        if(fileSize > 2000000){
            alert('El archivo no debe superar 2MB, ajuste el archivo ó cargue otro.');
            this.value = '';
        }else{
            var ext = fileName.split('.').pop();
            ext = ext.toLowerCase();
            switch (ext) {
                case 'jpg':
                case 'jpeg':
                case 'png': break;
                default:
                    alert('El archivo no tiene la extensión adecuada');
                    this.value = ''; // reset del valor
                    this.files[0].name = '';
            }
        }
    });

</script>

@endsection


