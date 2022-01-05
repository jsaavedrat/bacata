@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-share-square-o"> </i> PERMISOS &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; CREAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('permisos.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE PERMISOS</p></div></a></div>
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
<div id="elemento-admin">
            
    <div id="content-mensaje">
        @if($mensaje=="exito")<div id="mensaje-exito"> <i class="fa fa-check-circle"></i> Permiso creado Exitosamente</div>@endif
        @if($mensaje=="error")<div id="mensaje-error"> <i class="fa fa-times-circle"></i>&nbsp; Nombre del permiso ya existe</div>@endif
    </div>
    <div id="texto-titulo"> <i class="fa fa-plus"> </i> Crear Permiso</div>

    <form method="post" action="{{ route('permisos.guardar') }}">
        @csrf
        <div class="label-campo">
            <div class="label-admin"><i id="lista" class="icon-dot-single"></i>Nombre Permiso<i id="cont-icon" class="fa fa-share-square-o"></i></div>
            <input type="text" name="nombre_permiso" class="campo-admin" placeholder="Nombre" spellcheck="false" autocomplete="off" maxlength="35" required onkeyup="this.value=letras(this.value)">
        </div>

        <div class="label-campo">
            <div class="label-admin"><i id="lista" class="icon-dot-single"></i>Modulo Permiso<i id="cont-icon" class="icon-layers"></i></div>
            <select class="campo-admin" id="select-admin" name="modulo_permiso" required>
                <option value="">Seleccione modulo al que pertenece</option>
                @foreach($modulos as $modulo)
                    <option value="{{$modulo->id_modulo}}">{{$modulo->nombre_modulo}}</option>
                @endforeach
            </select>
        </div>

        <button class="boton-admin"> Crear Permiso</button>
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
#iconos-titulo{
    box-shadow: 0px 0px 0px white;
}
</style>
<script type="text/javascript">
    function letras(string){//Solo letras
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ_';//Caracteres validos
        
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


