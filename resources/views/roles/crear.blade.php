@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="icon-creative-commons-attribution"> </i> ROLES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CREAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('roles.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE ROLES</p></div></a></div>
    <div class="icono-titulo"><i class="fa fa-question herramientas">                                   </i><div class="content-texto"><p class="texto-icono">AYUDA</p></div></div>
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
        @if($mensaje=="exito")<div id="mensaje-exito"> <i class="fa fa-check-circle"></i> ROL creado Exitosamente</div>@endif
        @if($mensaje=="error")<div id="mensaje-error"> <i class="fa fa-times-circle"></i>&nbsp; Nombre del ROL ya existe</div>@endif
    </div>
    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Crear ROL EMPLEADO</div>

    <form method="post" action="{{ route('roles.guardar') }}">
        @csrf
        

        <div class="label-campo">
            <div class="label-admin" id="label_nombre"><i id="lista" class="icon-dot-single"></i>Nombre ROL de empleado<i id="cont-icon" class="icon-creative-commons-attribution"></i></div>
            <input type="text" name="nombre_rol" class="campo-admin" id="nombre_rol" placeholder="Nombre rol empleado" spellcheck="false" autocomplete="off" maxlength="30" onkeyup="this.value=letras(this.value)">
        </div>

        
        <div id="texto-permisos">A continuación se muestran los permisos o accesos que tendrá el nuevo ROL de empleado dentro del sistema. Por favor seleccione los accesos que tendra dentro del sistema.</div>

        <div id="content-permisos">
        @foreach($modulos as $modulo)
            <div class="content-permiso">
                <div class="modulo-permiso">
                    {{$modulo->nombre_modulo}} 
                    <input type="checkbox" class="check-modulo-referencia" id="id-check-{{$modulo->nombre_modulo}}" onclick="seleccionarPermisos('check-{{$modulo->nombre_modulo}}',this.id);">
                </div> 
                <div class="permisos-modulo">
                    @foreach($permisos_modulos as $permisos_modulo)
                        @if($modulo->id_modulo == $permisos_modulo->id_modulo)
                            <div class="permiso-modulo"><i class="icon-dot-single"></i>
                                {{$permisos_modulo->name}}
                                <input type="checkbox" value="{{$permisos_modulo->id_permiso}}" class="check-{{$modulo->nombre_modulo}} checkPermisos" id="check-permiso" onclick="verificarTodos('check-{{$modulo->nombre_modulo}}');">
                            </div>
                        @endif
                     @endforeach
                </div>
            </div>
        @endforeach
        </div>

        <input type="hidden" id="vector_permisos" name="vector_permisos[]" multiple="multiple">
        <div id="error-permisos"><i class="fa fa-times-circle"></i> Seleccione al menos 2 permisos para asignarle al Rol</div>
        
        <button class="boton-admin" id="crear-rol"> Crear Rol de Empleados</button>
    </form>
</div>


<style type="text/css">
    .caja-admin{
        box-shadow: 0px 0px 0px rgba(0,0,0,1);
        border:0px;
        padding: 0px 10px 15px 10px;
        max-width: 450px;
    }

    .label-admin{
        font-weight: 600;
    }

    .boton-admin{
        margin-top:30px;
    }
    .label-campo{
        width: 100%;
        margin-top:20px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
        right:0;
        left:0;
        float:none;
        overflow: hidden;
    }
    
    #texto-permisos{
        width: 100%;
        //color:rgba(78,67,118,1);
        color:rgba(80,70,90,1);
        font-size: 13px;
        padding:10px;
        font-weight: 500;
        letter-spacing: -0.5px;
        margin-bottom:10px;
        //border: 1px solid red;
        float: left;
    }

    #content-permisos{
        width: 100%;
        float: left;
        //border:1px solid green;
    }

    .content-permiso{
        width: 25%;
        display: inline-grid;
        overflow:hidden;
        padding:10px;
        //border:1px solid red;
        margin:-2px!important;
    }

    .modulo-permiso{
        width: 100%;
        padding: 10px 10px 10px 10px;
        font-size: 14px;
        letter-spacing: -0.3px;
        font-weight: 400;
        text-transform: uppercase;
        cursor: pointer;
        text-shadow: 0px 0px 1px rgba(100,100,100,0.3);
        outline: none;
        transition: 0.4s;
        //background-image: linear-gradient(-20deg, #2b5876 0%, #4e4376 100%) !important;
        //background-color: rgba(90,110,150,1);
        background-color: rgba(220,220,180,0.8);
        border:1px solid rgba(215,215,200,0.9);
        color:rgba(70,80,90,1);
        border-radius: 2px;
    }.modulo-permiso:hover{
        text-shadow: 0px 0px 2px rgba(50,50,50,0.5);
        color:rgba(50,50,50,1);
        letter-spacing: 0px;
        box-shadow: 0px 0px 10px rgba(50,50,50,0.4);
    }

    .modulo-activo{
        //background-color: rgba(20,20,20,0.6);
    }

    .permisos-modulo{
        transition: 0.4s;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease-out;
        border:1px solid rgba(215,215,200,0.9);
        margin-top:-2px;
        background-color: rgba(255,255,255,0.7);
    }

    .permiso-modulo{
        font-size: 14px;
        letter-spacing: -0.5px;
        color:rgba(80,70,90,1);
        padding:6px 0px 6px 10px;
    }.permiso-modulo:hover{
        //letter-spacing: -0.2px;
    }

    .check-modulo-referencia{
        float: right;
        margin-top:3px;
    }

    #check-permiso{
        float: right;
        margin-top:3px;
        margin-right:10px;
    }

    #error-permisos{
        display: none;
        padding: 10px;
        //background-color:rgba(249,249,250,0.7);
        color:red;
        font-weight: 600;
        letter-spacing: -0.5px;
        border-radius: 2px;
        font-size: 14px;
        text-align: center;
    }
</style>

<script type="text/javascript">
    var acc = document.getElementsByClassName("modulo-permiso");
    var i;
    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("modulo-activo");
        var permisos = this.nextElementSibling;
        if (permisos.style.maxHeight) {
          permisos.style.maxHeight = null;
        } else {
          permisos.style.maxHeight = permisos.scrollHeight + "px";
        }
      });
    }


    function seleccionarPermisos(nombreModulo,idCheck) {

        var checkboxs = document.getElementsByClassName(nombreModulo);
        var checkId = document.getElementById(idCheck).checked;

        for (i = 0; i < checkboxs.length; i++) {

            var values = document.getElementsByClassName(nombreModulo)[i].value;
            //console.log(values);

            //var checks = document.getElementsByClassName(nombreModulo)[i].checked;
            //console.log(checks);

            if (checkId==true) {
                document.getElementsByClassName(nombreModulo)[i].checked = true;
            }else if(checkId==false){
                document.getElementsByClassName(nombreModulo)[i].checked = false;
            }           
        }
    }

    function verificarTodos(check){
        
        var checks = document.getElementsByClassName(check);
        var c = 0;
        for (i = 0; i < checks.length; i++) {

            var values = document.getElementsByClassName(check)[i].checked;
            if(values==false){
                c = c + 1;
            }
            console.log(values);
        }
        if(c==0){
            document.getElementById('id-'+check).checked = true;
        }else{
            document.getElementById('id-'+check).checked = false;
        }
        console.log("falsos: "+c)
    }

    $("#crear-rol").click(function(){

        var expr1=/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g;/*solo letras mayusculas, minusculas, espacios y ñ*/
        var nombreRol = $("#nombre_rol").val();
        permisos = [];

        var valid = true;

        if(nombreRol.length<5){
                document.getElementById('label_nombre').innerHTML="Nombre de ROL debe llevar más texto";
                document.getElementById('label_nombre').style.color="red";
                document.getElementById('nombre_rol').value="";
                document.getElementById('nombre_rol').focus();
                valid=false;
        }else{
            document.getElementById('label_nombre').innerHTML="Nuevo ROL de empleado";
            document.getElementById('label_nombre').style.color="rgba(78,67,118,1)";
        }
        if(!expr1.test(nombreRol) && nombreRol.length>=5){
                document.getElementById('label_nombre').innerHTML="Escribe solo texto";
                document.getElementById('label_nombre').style.color="red";
                document.getElementById('nombre_rol').value="";
                document.getElementById('nombre_rol').focus();
                valid=false;
        }

        var checkPermisos = document.getElementsByClassName('checkPermisos');
        for (i = 0; i < checkPermisos.length; i++) {
            var check = document.getElementsByClassName('checkPermisos')[i].checked;
            if (check==true) {
                var valu = document.getElementsByClassName('checkPermisos')[i].value;
                //console.log(valu);
                permisos.push(valu);
            }
        }
        if(permisos.length<2){
            document.getElementById("error-permisos").style.display="block";
            valid=false;

        }else{
            document.getElementById("error-permisos").style.display="none";
            permisos=JSON.stringify(permisos);
            console.log(permisos);
            document.getElementById('vector_permisos').value=permisos;
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
</script>
@endsection


