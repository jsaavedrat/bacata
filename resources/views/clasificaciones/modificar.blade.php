@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-colours"> </i> CLASIFICACIONES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; EDITAR {{$clasificacion->nombre_clasificacion}}</div>
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
<div id="elemento-admin" class="editar-clasificacion">

    <div id="texto-titulo"> <i class="fa fa-plus"> </i> AGREGAR {{$clasificacion->nombre_clasificacion}}</div>

    <form method="post" data-parsley-validate action="{{ route('clasificaciones.modificar') }}">
        @csrf
        <div class="label-campo">
            <label class="label-admin" for="nombre_clasificacion"><i id="lista" class="icon-dot-single"></i>Nombre Clasificación<i id="cont-icon" class="fa fa-language"></i></label>
            <input type="text" name="nombre_clasificacion" class="campo-admin" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="25" id="nombre_clasificacion" onkeyup="this.value=letras(this.value)" value="{{$clasificacion->nombre_clasificacion}}">
        </div>
        <div id="error-nombre-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Escribe más letras en el Nombre</div>

        <div class="label-campo" id="clonar-hijo">
            <label class="label-admin" id="aux" onclick="clonar();"><i id="lista" class="icon-dot-single"></i>Agregar {{$clasificacion->nombre_clasificacion}}<i id="cont-icon" class="fa fa-plus mas"></i></label>
            <div class="campo-clonar">
                <input type="text" class="campo-admin campo-especificacion" placeholder="Nombre {{$clasificacion->nombre_clasificacion}}" spellcheck="false" autocomplete="off" maxlength="25" onkeyup="this.value=letras2(this.value)">
                <div class="cerrar-campo" onclick="eliminarCampo(this.parentElement)"><i class="icon-cross"></i></div>
            </div>
        </div>

        <div id="agregar-mas" onclick="clonar();">Agregar {{$clasificacion->nombre_clasificacion}}</div>

        <div id="error-vacio" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Existen especificaciones vacías</div>
        <div id="error-repetido" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Existen especificaciones Repetidas</div>

        <input type="hidden" name="id_clasificacion" value="{{$clasificacion->id_clasificacion}}">

        <input type="hidden" name="especificaciones" id="especificaciones" multiple="multiple">

        <button class="boton-admin" id="crear-clasificacion"> Actualizar {{$clasificacion->nombre_clasificacion}}</button>
    </form>
</div>

<div id="elemento-admin" class="lista-clasificacion">
    <div id="texto-titulo"> <i class="icon-list"> </i> Lista de {{$clasificacion->nombre_clasificacion}}</div>
    <table id="tabla" class="display compact cell-border stripe" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

            @foreach($especificaciones as $especificacion)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$especificacion->nombre_especificacion}}</td>
                    <td class="td-acciones">
                        <div class="iconos-acciones">
                            @can('Editar_Especificacion')
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-pencil"> </i> EDITAR</a>
                                <i onclick="editar('{{$especificacion->id_especificacion}}');" class="icon-pencil i-acciones"> </i> &nbsp;
                            </div>
                            @endcan
                            @can('Eliminar_Especificacion')
                            <div class="content-acciones">
                                <a class="dropdown-content"><i class="icon-trash"> </i> ELIMINAR</a>
                                <i onclick="eliminar('{{$especificacion->id_especificacion}}','{{$especificacion->nombre_especificacion}}');" class="icon-trash i-acciones"></i>
                            </div>
                            @endcan
                        </div>
                    </td>
                </tr> 
            @endforeach

        </tbody>
    </table>
</div>

<div id="modal-eliminar">
    <form method="post" action="{{ route('clasificaciones.inactivar.especificacion') }}">
        @csrf
        <div id="content-modal-eliminar">
            <div id="imagen-modal-eliminar">
                <div id="titulo-modal-eliminar"><i class="fa fa-exclamation-triangle"></i></div>
                <div id="mensaje-confirmacion-eliminar">¿Realmente deseas eliminar {{$clasificacion->nombre_clasificacion}} <x id="nombre-eliminar"></x></div>
                <div id="content-botones-modal-eliminar">
                    <div class="content-boton-modal-eliminar">
                        <button class="boton-modal-eliminar"><i class="icon-trash"> </i> Eliminar</button>
                    </div>
                    <div class="content-boton-modal-eliminar">
                        <div class="boton-modal-eliminar" style="padding-top:1vh;" onclick="cerrarModal();"><i class="fa fa-close"> </i> Atrás</div>
                    </div>
                </div>
            </div>
        </div>
        <input type="number" name="eliminar" id="eliminar" style="display: none;">
    </form>
</div>

<form method="post" action="{{ route('clasificaciones.editar.especificacion') }}">
    @csrf
    <input type="number" name="editar" id="editar" style="display: none;">
    <button id="boton-editar"></button>
</form>

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

    .editar-clasificacion{
        width: 33.3%!important;
        //border:1px solid red;
        margin-top: 20px!important;
        margin-left:0px!important;
        padding-left: 10px!important;
        /*margin-top:16vh!important;
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
        background-color: rgba(225,225,235,0.2);*/
    }
    .lista-clasificacion{
        width:66.7%!important;
        //border:1px solid blue;
        margin-top: 20px!important;
        margin-left:0px!important;
        padding-left: 20px!important;
        padding-right: 30px!important;
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
</style>

<script type="text/javascript">

    existentes = {!! json_encode($especificaciones) !!}
    nombres=[];
    for(i=0; i < existentes.length; i++){
        console.log(existentes[i].nombre_especificacion);
        nombres.push(existentes[i].nombre_especificacion);
    }




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
            aux = out;
            aux2 = "Nombre de "+out+":";
        }else{
            aux = "";
            aux2 = "Nombre";
        }
        document.getElementById("aux").innerHTML = "<i id='lista' class='icon-dot-single'></i>Agregar "+aux+"<i id='cont-icon' class='fa fa-plus mas'></i>";
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
        //console.log("entro");
        var campo = document.getElementsByClassName("campo-clonar")[0];
        var clone = campo.cloneNode(true);
        clone.children[0].value="";
        clone.value="";
        document.getElementById("clonar-hijo").appendChild(clone);

        var n = document.getElementsByClassName("campo-especificacion");
        document.getElementsByClassName("campo-especificacion")[n.length-1].focus();

    }

    function eliminarCampo(pariente){
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
        var especificacionesN=[];
        var especificacionesF=[];
        //especificaciones = nombres;
        
        var n = document.getElementsByClassName("campo-especificacion");

        for (i = 0; i < n.length; i++) {
            var x = document.getElementsByClassName("campo-especificacion")[i].value;
            if(x == ""){
                //document.getElementById("error-vacio").style.display = "inline";
                //valid = false;
            }else{
                especificaciones.push(x);
                especificacionesN.push(x);
            }
        }

        for (i = 0; i < nombres.length; i++) {
            especificaciones.push(nombres[i]);
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


        for(i = 0; i < especificacionesN.length; i++){
            var encontro = false;
            for(j = 0; j < nombres.length; j++){
                if(especificacionesN[i] == nombres[j]){
                    encontro = true;
                }
            }
            if(encontro == false){
                especificacionesF.push(especificacionesN[i]);
            }
        }

        if (valid==true) {
            vector=JSON.stringify(especificacionesF);
            document.getElementById('especificaciones').value=vector;
        }

        return valid;
    });


    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: '{{$clasificacion->nombre_clasificacion}} Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: '{{$clasificacion->nombre_clasificacion}} Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: '{{$clasificacion->nombre_clasificacion}} Optica Angeles',
                    filename: '{{$clasificacion->nombre_clasificacion}} Optica Angeles',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: '{{$clasificacion->nombre_clasificacion}} Optica Angeles',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar {{$clasificacion->nombre_clasificacion}}&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ {{$clasificacion->nombre_clasificacion}}",
                info:           "Mostrando {{$clasificacion->nombre_clasificacion}} del _START_ al _END_ de un total de _TOTAL_ {{$clasificacion->nombre_clasificacion}}",
                infoEmpty:      "Mostrando {{$clasificacion->nombre_clasificacion}} del 0 al 0 de un total de 0 {{$clasificacion->nombre_clasificacion}}",
                infoFiltered:   "(filtrado de un total de _MAX_ {{$clasificacion->nombre_clasificacion}})",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron {{$clasificacion->nombre_clasificacion}}",
                emptyTable:     "Ningún {{$clasificacion->nombre_clasificacion}} disponible en esta tabla",
                paginate: {
                    first:      "Primer",
                    previous:   "Anterior",
                    next:       "Siguiénte",
                    last:       "Último"
                },
                aria: {
                    sortAscending:  ": Activar para ordenar la columna de manera ascendente",
                    sortDescending: ": Activar para ordenar la columna de manera descendente"
                },
                buttons: {
                    copyTitle: 'Copiado en el portapapeles',
                    copyKeys: 'Presione <i>ctrl</i> ou <i>\u2318</i> + <i>C</i> para copiar los datos de la tabla a su portapapeles. <br><br>Para cancelar, haga clic en este mensaje o presione Esc.',
                    copySuccess: {
                        _: '%d lineas copiadas',
                        1: '1 linea copiada'
                    }
                }
            }
        } );
    } );

</script>

@endsection


