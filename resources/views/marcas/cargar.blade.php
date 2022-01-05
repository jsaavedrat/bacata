@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-bookmarks"> </i> MARCAS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CARGAR</div>
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
        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Marcas creadas Exitosamente</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Nombre de Marca ya existe</div>@endif
    </div>

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Crear Múltiples Marcas</div>

    <form method="post" data-parsley-validate action="{{ route('marcas.guardar.carga') }}">
        @csrf
        <div style="width: 100%;float: left;">
            <div class="label-campo">
                <div id="error-tipo-producto-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Seleccione que tipo de producto es</div>
                <div class="label-admin" id="label_tipo_producto"><i id="lista" class="icon-dot-single"></i>Tipo de Producto<i id="cont-icon" class="icon-colours"></i></div>
                <select class="campo-admin" name="tipo_producto" id="tipo_producto" required onchange="marcasTipoProductos(this.value);">
                    <option value="">Seleccione un tipo de Producto</option>
                     @foreach($tipo_productos as $tipo_producto)
                        <option value="{{$tipo_producto->id_tipo_producto}}">{{$tipo_producto->nombre_tipo_producto}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div id="texto-titulo"> <i class="fa fa-plus"> </i> Marcas <x id="tipo_producto_marca"></x></div>

        <div id="clonar-hijo">
            <div id="error-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Existen marcas vacías</div>
            <div id="error-repetido" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Existen marcas Repetidas</div>
            <div id="error-repetido-db" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Existen marcas registradas anteriormente</div>

            <div class="label-campo campo-clonar">
                <label class="label-admin label-marca" for="nombre_modelo"><i id="lista" class="icon-dot-single"></i>Nombre Marca<i id="cont-icon" class="fa fa-language"></i></label>
                <div class="cerrar-campo" onclick="eliminarMarca(this.parentElement)"><i class="icon-cross"></i></div>
                <input type="text" class="campo-admin campo-marca" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="25" onkeyup="this.value=letras(this.value)">
            </div>

        </div>

        <input type="hidden" name="marcas" id="marcas">

        <div id="agregar-mas" onclick="clonar();">Agregar</div>

        <button class="boton-admin" id="crear-marca"> Cargar Marcas</button>
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

    #error-vacio,#error-repetido,#error-repetido-db,#error-nombre-vacio,#error-tipo-producto-vacio,#error-marca-vacio,#error-descripcion-vacio{
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
    .label-marca{
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

    #clonar-hijo{
        width: 100%;
        float: left;
        display: none;
    }

</style>

<script type="text/javascript">

    activas = [];

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

    function marcasTipoProductos(id){

        var n = document.getElementsByClassName("campo-marca");
        for (i = 0; i < n.length; i++) {
            n[i].value = "";
        }

        document.getElementById("clonar-hijo").style.display = "none";
        document.getElementById("agregar-mas").style.display = "none";

        var tipoX = document.getElementById("tipo_producto");
        var tipo = tipoX.options[tipoX.selectedIndex].innerText;
        
        var url="{{route('modelos.marca_tipo_productos')}}";
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
                console.log(data);
                document.getElementById("clonar-hijo").style.display = "block";
                document.getElementById("agregar-mas").style.display = "block";
                activas = data;

            },
            error: function(data) {
                console.log("error"); 
            }
        });
    }



    function eliminarMarca(pariente){
        var n = document.getElementsByClassName("label-modelo");
        if(n.length != 1){
            pariente.remove();
        }
    }


    function clonar(){
        console.log("entro");
        var campo = document.getElementsByClassName("campo-clonar")[0];
        var clone = campo.cloneNode(true);
        clone.children[2].value="";
        clone.value="";
        document.getElementById("clonar-hijo").appendChild(clone);

        var n = document.getElementsByClassName("campo-marca");
        document.getElementsByClassName("campo-marca")[n.length-1].focus();
    }


    $("#crear-marca").click(function(){

        document.getElementById("error-tipo-producto-vacio").style.display = "none";
        document.getElementById("error-vacio").style.display = "none";        
        document.getElementById("error-repetido").style.display = "none";
        document.getElementById("error-repetido-db").style.display = "none";

        var valid = true;
        var marcas=[];
        var nombre_marcas_nuevas = [];

        var n = document.getElementsByClassName("campo-marca");

        for (i = 0; i < n.length; i++) {
            var x = document.getElementsByClassName("campo-marca")[i].value;
            if(x == ""){
                document.getElementById("error-vacio").style.display = "inline";
                valid = false;
            }else{
                vector = {
                    'nombre_marca': x
                }
                marcas.push(vector);
                nombre_marcas_nuevas.push(x);
            }
           
        }

        var verifica = nombre_marcas_nuevas.filter(function(item, index, array) {
          return array.indexOf(item) === index;
        })

        if(verifica.length != nombre_marcas_nuevas.length){
            document.getElementById("error-repetido").style.display = "inline";
            valid = false;
        }

        console.log(activas);
        console.log(nombre_marcas_nuevas);
        for(i = 0;i < nombre_marcas_nuevas.length; i++){
            for(j = 0; j < activas.length; j++){
                if (nombre_marcas_nuevas[i] == activas[j].nombre_marca) {
                    valid = false;
                    document.getElementById("error-repetido-db").style.display = "inline";
                }
            }
        }

        var tipo_producto = document.getElementById("tipo_producto").value;
        if(tipo_producto==""){
            document.getElementById("error-tipo-producto-vacio").style.display = "inline";
            valid = false;
        }

        
        console.log("marcas a guardar");
        console.log(marcas);
        if(valid==true){
            marcas = JSON.stringify(marcas);
            document.getElementById("marcas").value = marcas;
        }

        return valid;
    });

</script>

@endsection


