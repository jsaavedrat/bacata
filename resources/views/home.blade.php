@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-home"> </i> RESUMEN</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
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
<div id="elemento-admin" style="padding-top: 20px;">

    <div id="content-mensaje">
        @if($estatus=="exito_cliente_appweb")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Se creó la información con éxito</div>@endif
        @if($estatus=="exito_actualizar_cliente_appweb")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Se actualizó la información con Éxito</div>@endif
        @if($estatus=="ya_existe_cliente")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; La información ya se configuró, puede modificar la información</div>@endif
        @if($estatus=="no_existe_cliente")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Primero se debe agregar la información</div>@endif
    </div>


    <div id="mensaje-bienvenida">Bienvenido(a), {{ Auth::user()->name }} {{ Auth::user()->apellido }}</div>

    @foreach($disponibles as $disponible)
    <div class="content-modulo">
        <div class="caja-modulo">
            <div class="content-icono">
                <i class="icono-modulo-optica {{$disponible->icono}}"></i>
            </div>
            <div class="content-textos">
                <div class="titulo-modulo">{{$disponible->nombre_modulo}}</div>
                <div class="cantidades-modulo">{{$disponible->cantidad_modulo}}</div>
                <div class="opciones-modulo">
                    <a href="{{$disponible->url_lista}}" class="icono-url"><i class="icon-list"></i></a>
                    <a href="{{$disponible->url_crear}}" class="icono-url"><i class="icon-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>






<script type="text/javascript">
    
</script>

@endsection
