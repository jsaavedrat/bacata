<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Optica Angeles</title>
    <link rel="shortcut icon" href="{{ asset('public/imagenes/icono.png') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/fontawesome/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/fonts/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/DataTables/datatables.min.css') }}">
    <script type="text/javascript" src="{{ asset('public/js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/DataTables/datatables.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/estilosadmin.css') }}">

</head>
<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>


<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::HEADER MENU SUPERIOR::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->

    <div id="menu-superior">
            <div id="content-logo-principal">
                <img src="{{ asset('public/imagenes/logo-principal.png') }}" style="height:100%;float: left;">
            </div>
            <div id="content-logout">
                <div id="icono-opciones-user"> <i class="icon-chevron-down" style="font-size: 18px;"> </i> </div> <div id="nombre-user"> Administrador</div>
                <div id="content-opciones-user">
                    <div class="opcion-user">
                        <i class="icon-key"> </i> Cambiar Contraseña
                    </div>
                    <div class="opcion-user">
                        <i class="fa fa-user"> &nbsp;</i> Perfil e información
                    </div>
                    @guest
                    @else
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <div class="opcion-user">
                                <i class="fa fa-power-off"> &nbsp;</i> Cerrar Sesión
                            </div>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>  
                    @endguest
                </div>
            </div>
            <div id="content-notificaciones">
                <div class="notificacion-header"><i class="icon-chat"></i></div>
                <div class="notificacion-header"><i class="icon-bell"></i></div>
            </div>
    </div>
    <div id="migajas"><div style="width: 250px;float: left;height: 60px;"></div> Modulos <i class="icon-chevron-right"></i> Lista</div>


<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::MENU IZQUIERDA:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


<div id="menu-izquierda">
    <div id="icono-cerrar-menu"><i class="icon-cross"></i></div>
    <div id="content-imagen-usuario">
        @if(Auth::user()->nombre_imagen_user =="" || Auth::user()->nombre_imagen_user == null)
            <img src="{{ asset('public/imagenes/sistema/users') }}/user_default.png" style="width: 100%;">
        @else
            <img src="{{ asset('public/imagenes/sistema/users') }}/{{ Auth::user()->nombre_imagen_user }}" style="width: 100%;">
        @endif
    </div>
    <div id="titulo-nombre-user">{{ Auth::user()->name }} {{ Auth::user()->apellido }}</div>

    <div id="titulo-rol-user">
        @if(isset(Auth::user()->getRoleNames()[0]))
            {{ Auth::user()->getRoleNames()[0] }}
        @else
            cliente
        @endif
    </div>
    <div id="content-modulos">


<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::PAGINA WEB::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


        <div class="modulo 
            {{route('pagina.carrusel.crear')}} {{route('pagina.carrusel.lista')}} {{route('pagina.carrusel.editar')}}
            {{route('pagina.membresias.crear')}} {{route('pagina.membresias.lista')}} {{route('pagina.membresias.editar')}}
            {{route('pagina.promociones_pagina.crear')}} {{route('pagina.promociones_pagina.lista')}} {{route('pagina.promociones_pagina.editar')}}
            {{route('pagina.logos_marcas.crear')}} {{route('pagina.logos_marcas.lista')}} {{route('pagina.logos_marcas.editar')}}
            {{route('pagina.infos.crear')}} {{route('pagina.infos.lista')}} {{route('pagina.infos.editar')}}
            {{route('pagina.servicios.crear')}} {{route('pagina.servicios.lista')}} {{route('pagina.servicios.editar')}}
            {{route('pagina.redes.lista')}} {{route('pagina.redes.editar')}}
            {{route('sucursales.crear')}} {{route('sucursales.lista')}} {{route('sucursales.editar')}}
            {{route('pagina.kids.crear')}} {{route('pagina.kids.lista')}} {{route('pagina.kids.editar')}} {{route('pagina.kids.fondo.crear')}}
            {{route('pagina.equipo.crear')}} {{route('pagina.equipo.lista')}} {{route('pagina.equipo.editar')}}
            {{route('pagina.fuentes.cargar')}} {{route('pagina.fuentes.lista')}} {{route('pagina.colores.configurar')}}
        ">
            <i class="fa fa-globe icono-modulo"> </i> PÁGINA WEB <i class="icon-chevron-down icono-flechas"></i>
        </div>
            <div class="submodulos">
                
                <p class="submodulo {{route('pagina.carrusel.crear')}} {{route('pagina.carrusel.lista')}} {{route('pagina.carrusel.editar')}}"><i class="icon-dot-single"></i> CARRUSEL DE IMAGENES</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('pagina.carrusel.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Agregar Imágen</p></a>
                        <a href="{{route('pagina.carrusel.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista Imágenes</p></a>
                    </div>

                <p class="submodulo {{route('pagina.membresias.crear')}} {{route('pagina.membresias.lista')}} {{route('pagina.membresias.editar')}}"><i class="icon-dot-single"></i> MEMBRESÍAS</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('pagina.membresias.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Agregar Membresía</p></a>
                        <a href="{{route('pagina.membresias.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de Membresias</p></a>
                    </div>

                <p class="submodulo {{route('pagina.promociones_pagina.crear')}} {{route('pagina.promociones_pagina.lista')}} {{route('pagina.promociones_pagina.editar')}}"><i class="icon-dot-single"></i> PROMOCIONES EN PÁGINA</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('pagina.promociones_pagina.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Agregar Promoción</p></a>
                        <a href="{{route('pagina.promociones_pagina.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de Promociones</p></a>
                    </div>

                <p class="submodulo {{route('pagina.logos_marcas.crear')}} {{route('pagina.logos_marcas.lista')}} {{route('pagina.logos_marcas.editar')}}"><i class="icon-dot-single"></i> LOGOS DE MARCAS</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('pagina.logos_marcas.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Agregar logos de marca</p></a>
                        <a href="{{route('pagina.logos_marcas.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista logos de marcas</p></a>
                    </div>

                    <p class="submodulo {{route('pagina.infos.crear')}} {{route('pagina.infos.lista')}} {{route('pagina.infos.editar')}}"><i class="icon-dot-single"></i> INFOS</p>
                        <div class="content-sub-submodulos">
                            <a href="{{route('pagina.infos.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Agregar infos</p></a>
                            <a href="{{route('pagina.infos.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista infos</p></a>
                        </div>

                <p class="submodulo {{route('pagina.servicios.crear')}} {{route('pagina.servicios.lista')}} {{route('pagina.servicios.editar')}}"><i class="icon-dot-single"></i> SERVICIOS</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('pagina.servicios.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Crear Servicio</p></a>
                        <a href="{{route('pagina.servicios.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de Servicios</p></a>
                    </div>

                <p class="submodulo {{route('pagina.redes.lista')}} {{route('pagina.redes.editar')}}"><i class="icon-dot-single"></i> REDES SOCIALES</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('pagina.redes.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de redes sociales</p></a>
                    </div>

                <p class="submodulo {{route('sucursales.crear')}} {{route('sucursales.lista')}} {{route('sucursales.editar')}}"><i class="icon-dot-single"></i> SUCURSALES</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('sucursales.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Crear Sucursal</p></a>
                        <a href="{{route('sucursales.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de Sucursales</p></a>
                    </div>

                <p class="submodulo {{route('pagina.kids.crear')}} {{route('pagina.kids.lista')}} {{route('pagina.kids.editar')}} {{route('pagina.kids.fondo.crear')}}"><i class="icon-dot-single"></i> SECCIÓN KIDS</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('pagina.kids.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Agregar imagen kids</p></a>
                        <a href="{{route('pagina.kids.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista imagenes KIDS</p></a>
                        <a href="{{route('pagina.kids.fondo.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Cambiar Fondo KIDS</p></a>
                    </div>

                <p class="submodulo {{route('pagina.equipo.crear')}} {{route('pagina.equipo.lista')}} {{route('pagina.equipo.editar')}}"><i class="icon-dot-single"></i> EQUIPO DE TRABAJO</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('pagina.equipo.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Agregar imagen equipo</p></a>
                        <a href="{{route('pagina.equipo.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Imagenes equipo trabajo</p></a>
                    </div>

                <p class="submodulo {{route('pagina.fuentes.cargar')}} {{route('pagina.fuentes.lista')}} {{route('pagina.colores.configurar')}}"><i class="icon-dot-single"></i> CONFIGURACIÓN ESTILO</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('pagina.fuentes.cargar')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Cambiar Fuente</p></a>
                        <a href="{{route('pagina.fuentes.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Fuentes</p></a>
                        <a href="{{route('pagina.colores.configurar')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Colores Pagina</p></a>
                    </div>

                <p class="submodulo "><i class="icon-dot-single"></i> COMENTARIOS</p>
                    <div class="content-sub-submodulos">
                        <a href=""><p class="sub-submodulo"><i class="icon-dot-single"></i> Comentarios Productos</p></a>
                    </div>
            </div>


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::TIENDA VIRTUAL:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


        <div class="modulo {{route('adminecommerce.cargar')}} {{route('adminecommerce.lista')}} {{route('adminecommerce.ventas')}} {{route('adminecommerce.crear.excel')}} {{route('adminecommerce.cargar.excel')}}">
            <i class="icon-shop icono-modulo"> </i> TIENDA VIRTUAL <i class="icon-chevron-down icono-flechas"></i>
        </div>
            <div class="submodulos">
                <a href="{{route('adminecommerce.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Inventario de Tienda</p></a>
                <a href="{{route('adminecommerce.ventas')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Ventas de la Tienda</p></a>
                <a href="{{route('adminecommerce.crear.excel')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Crear Excel Subida Productos</p></a>
                <a href="{{route('adminecommerce.cargar.excel')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Subir Excel Productos</p></a>
            </div>


<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::PRODUCTOS:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


        <div class="modulo 
            {{route('productos.crear')}} {{route('productos.lista')}} {{route('productos.codigos')}}
            {{route('categorias.crear')}} {{route('categorias.lista')}} {{route('categorias.editar')}} {{route('categorias.tipoproductos')}}
            {{route('clasificaciones.crear')}} {{route('clasificaciones.lista')}} {{route('clasificaciones.editar')}} {{route('clasificaciones.ver')}} {{route('clasificaciones.editar.especificacion')}}
            {{route('tipo_productos.crear')}} {{route('tipo_productos.lista')}} {{route('tipo_productos.editar')}} {{route('tipo_productos.productos')}}
            {{route('marcas.crear')}} {{route('marcas.lista')}} {{route('marcas.editar')}} {{route('marcas.cargar')}}
            {{route('modelos.crear')}} {{route('modelos.lista')}} {{route('modelos.editar')}}
            {{route('promociones.crear')}} {{route('promociones.lista')}}
            ">
                <i class="fa fa-tags icono-modulo"> </i> PRODUCTOS <i class="icon-chevron-down icono-flechas"></i>
        </div>
            <div class="submodulos">
                <p class="submodulo {{route('categorias.crear')}} {{route('categorias.lista')}} {{route('categorias.editar')}} {{route('categorias.tipoproductos')}}"><i class="icon-dot-single"></i>CATEGORIAS</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('categorias.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Crear categorias</p></a>
                        <a href="{{route('categorias.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de Categorias</p></a>
                    </div>

                <p class="submodulo {{route('clasificaciones.crear')}} {{route('clasificaciones.lista')}} {{route('clasificaciones.editar')}} {{route('clasificaciones.ver')}} {{route('clasificaciones.editar.especificacion')}}"><i class="icon-dot-single"></i>CLASIFICACIONES</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('clasificaciones.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Crear Clasificación de Producto</p></a>
                        <a href="{{route('clasificaciones.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de Clasificación de Productos</p></a>
                    </div>

                <p class="submodulo {{route('tipo_productos.crear')}} {{route('tipo_productos.lista')}} {{route('tipo_productos.editar')}}"><i class="icon-dot-single"></i>TIPOS DE PRODUCTO</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('tipo_productos.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Crear tipo deProducto</p></a>
                        <a href="{{route('tipo_productos.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de tipos de productos</p></a>
                    </div>
            </div>


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::CITAS:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


        <div class="modulo {{route('citas.crear')}} {{route('citas.lista')}} {{route('citas.lista.hoy')}}">
                <i class="fa fa-calendar icono-modulo"> </i> CITAS <i class="icon-chevron-down icono-flechas"></i>
        </div>
            <div class="submodulos">
                <a href="{{route('citas.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Registrar Cita</p></a>
                <a href="{{route('citas.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de Citas</p></a>
                <a href="{{route('citas.lista.hoy')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Citas del día</p></a>
            </div>


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::EMPRESAS ENVIO::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


        <div class="modulo {{route('empresas.envio.crear')}} {{route('empresas.envio.lista')}} {{route('empresas.envio.editar')}} {{route('empresas.envio.crear.reporte')}}">
                <i class="fa fa-truck icono-modulo"> </i> ENVÍOS <i class="icon-chevron-down icono-flechas"></i>
        </div>
            <div class="submodulos">
                <a href="{{route('empresas.envio.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Crear Empresa de Envío</p></a>
                <a href="{{route('empresas.envio.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de Empresas de envío</p></a>
                <a href="{{route('empresas.envio.crear.reporte')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Crear Reporte de Envío</p></a>
                {{--<a href="{{route('empresas.envio.historial.reportes')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Historial reportes envíos</p></a>--}}
            </div>


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::USUARIOS:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


        <div class="modulo {{route('empleados.lista')}} {{route('empleados.crear')}} {{route('empleados.editar')}}"> <i class="fa fa-users icono-modulo" > </i> USUARIOS <i class="icon-chevron-down icono-flechas"></i></div>
            <div class="submodulos">
                <a href="{{route('empleados.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Crear Usuario</p></a>
                <a href="{{route('empleados.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Lista de Usuarios</p></a>
            </div>


<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ACCESOS:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->

{{--
        @canany(['Crear_Rol','Lista_Roles','Crear_Permiso','Lista_Permisos'])
        <div class="modulo {{route('roles.crear')}} {{route('roles.lista')}} {{route('roles.editar')}} {{route('permisos.crear')}} {{route('permisos.lista')}}">
            <i class="fa fa-share-square-o icono-modulo"> </i> Accesos <i class="icon-chevron-down icono-flechas"></i></div>
            <div class="submodulos">
                @can('Crear_Rol')<a href="{{ route('roles.crear') }}"><p class="submodulo"><i class="icon-dot-single"></i> Crear ROL de empleado</p></a>@endcan
                @can('Lista_Roles')<a href="{{ route('roles.lista') }}"><p class="submodulo"><i class="icon-dot-single"></i> Lista de Roles de Empleados</p></a>@endcan
                @can('Crear_Permiso')<a href="{{ route('permisos.crear') }}"><p class="submodulo"><i class="icon-dot-single"></i> Crear Permisos </p></a>@endcan
                @can('Lista_Permisos')<a href="{{ route('permisos.lista') }}"><p class="submodulo"><i class="icon-dot-single"></i> Lista de Permisos</p></a>@endcan
            </div>
        @endcanany
--}}


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::CONFIGURACIONES::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->


        <div class="modulo {{route('configuraciones.cliente.crear')}} {{route('configuraciones.cliente.editar')}} {{route('pagina.textos.lista')}} {{route('pagina.textos.editar')}}">
                <i class="fa fa-cog icono-modulo"> </i> &nbsp;CONFIGURACIONES <i class="icon-chevron-down icono-flechas"></i>
        </div>
            <div class="submodulos">
                <p class="submodulo {{route('configuraciones.cliente.crear')}} {{route('configuraciones.cliente.editar')}}"><i class="icon-dot-single"></i>INFORMACIÓN BÁSICA</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('configuraciones.cliente.crear')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Crear Información</p></a>
                        <a href="{{route('configuraciones.cliente.editar')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i> Editar Información</p></a>
                    </div>

                <p class="submodulo {{route('pagina.textos.lista')}} {{route('pagina.textos.editar')}}"><i class="icon-dot-single"></i>TEXTOS POLITICAS Y OTROS</p>
                    <div class="content-sub-submodulos">
                        <a href="{{route('pagina.textos.lista')}}"><p class="sub-submodulo"><i class="icon-dot-single"></i>Textos Página Web</p></a>
                    </div>
            </div>



        <br>
        <br>
        <br>
        <br>
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::FIN MENU::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
    </div>




<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::BOTONES POSTERIORES:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->









</div>

<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::SECCION ADMIN CENTRAL::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
    
    <div id="content-imagen-fondo">
        <div id="imagen-fondo">
        </div>
    </div>

    <div id="seccion-admin">
        
            <div id="admin">
                @yield('content')
            </div>

            <div id="footer-admin">
                <div id="texto-footer">

                    @php
                        $cliente_appweb = DB::table('cliente_appweb')
                        ->where('estado_cliente_appweb','=','activo')
                        ->first();
                        if (isset($cliente_appweb)) {
                            echo "www." . $cliente_appweb->dominio . "<br>";
                            echo $cliente_appweb->nombre_cliente_sas . " Licencia y Derechos Reservados © " . date("Y") . "<br>AppWebCa Colombia ©";
                        } else {
                            echo "<div style='color: red; font-weight: bold;'>INGRESAR NOMBRE CLIENTE SAS</div><script type='text/javascript'>alert('Agregar Nombre Cliente SAS.');</script>";
                        }
                    @endphp

                </div>
                <div id="nombre-version"><p style="font-weight: 500;float: left;margin-right: 5px;">AppWebCa - Sistema de Ópticas</p> Versión 1.1</div>
            </div>
    </div>
        

<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<script type="text/javascript">
    var acc = document.getElementsByClassName("modulo");
    var i;
    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {

            /*var ocultar = document.getElementsByClassName("submodulos");

            for (j = 0; j < ocultar.length; j++){
                document.getElementsByClassName("submodulos")[j].style.maxHeight = null;
            }*/
            var act = document.getElementsByClassName("active");
            //alert(act.length);

            /*if(act.length==0){
                this.classList.toggle("activeP");
            }*/

            if(act.length==1){
                document.getElementsByClassName("active")[0].click();
            }

            

            this.classList.toggle("active");
            var submodulos = this.nextElementSibling;
            if (submodulos.style.maxHeight) {
                submodulos.style.maxHeight = null;
            } else {
                submodulos.style.maxHeight = submodulos.scrollHeight + "px";
            }
        });
    }

    
//tercer nivel menu
    var act = document.getElementsByClassName("submodulo");
    var ii;
    for (ii = 0; ii < act.length; ii++) {
        act[ii].addEventListener("click", function() {


            var actsub = document.getElementsByClassName("submodulo-activo");
            //alert(act.length);
            if(actsub.length!=0){
                document.getElementsByClassName("submodulo-activo")[0].click();
            }

            this.classList.toggle("submodulo-activo");
            var sub = this.nextElementSibling;
            if (sub.style.maxHeight) {
                sub.style.maxHeight = null;
            } else {
                sub.style.maxHeight = sub.scrollHeight + "px";
            }

            var padre = this.parentElement;
            if (padre.style.maxHeight) {
                    padre.style.maxHeight = ((padre.scrollHeight + sub.scrollHeight) + "px");
            } else {
                    padre.style.maxHeight = null;
            }

            //console.log(actsub.length);
        });
    }

    URLactual = window.location.protocol+"//"+window.location.host+window.location.pathname;
    // console.log(URLactual);
    url = document.getElementsByClassName(URLactual);
    // console.log("cantidad de click: "+url.length);
    for(i = 0; i < url.length; i++){
        document.getElementsByClassName(URLactual)[i].click();
    }

    function ver(id){
        document.getElementById("ver").value = id;
        document.getElementById("boton-ver").click();
    }

    function editar(id){
        document.getElementById("editar").value = id;
        document.getElementById("boton-editar").click();
    }

    function eliminar(id,nombre){
        $("#modal-eliminar").fadeIn();
        document.getElementById("eliminar").value = id;
        document.getElementById("nombre-eliminar").innerHTML = nombre;
    }
    function cerrarModal(){
        $("#modal-eliminar").fadeOut();
        document.getElementById("eliminar").value = "";
    }

    function codigoBarra(id, cantidad, nombre, code128,precio_base){
        id = parseInt(id);
        cantidad = parseInt(cantidad);
        codigoBarras = localStorage.getItem('codigo');
        if(codigoBarras != null){
            codigoBarras = JSON.parse(codigoBarras);
        }else{
            codigoBarras = [];
        }
        buscarCodigo(id, cantidad, nombre, code128, precio_base);
        codigoBarras = JSON.stringify(codigoBarras);
        localStorage.setItem('codigo', codigoBarras);
    }

    function codigoBarraMultiple(){
        codigoBarras = localStorage.getItem('codigo');
        if(codigoBarras != null){
            codigoBarras = JSON.parse(codigoBarras);
        }else{
            codigoBarras = [];
        }
        for(var i=0; i < productos_barcode.length; i++){
            if(productos_barcode[i].nombre == undefined){
                productos_barcode[i].nombre = productos_barcode[i].nombre_producto;
            }
            buscarCodigo(productos_barcode[i].id_producto,productos_barcode[i].cantidad,productos_barcode[i].nombre,productos_barcode[i].code128,productos_barcode[i].precio_base);
        }
        
        for (var i = 0; i < codigoBarras.length; i++) {
            var input = document.getElementById("input-cantidad-"+codigoBarras[i].id_producto);
            if(input != null && codigoBarras[i].cantidad != 0){
                    document.getElementById("input-cantidad-"+codigoBarras[i].id_producto).value = codigoBarras[i].cantidad;
            }
        }
        codigoBarras = JSON.stringify(codigoBarras);
        localStorage.setItem('codigo', codigoBarras);
    }

    function buscarCodigo(id, cantidad, nombre, code128,precio_base){
        var encontro = false;
        for(var i=0; i < codigoBarras.length; i++){
                if (codigoBarras[i].id_producto == id) {
                    codigoBarras[i].cantidad = codigoBarras[i].cantidad + cantidad;
                    encontro = true;
                }
        }
        if(encontro == false){
                var producto_barcode = {
                    'id_producto': id,
                    'cantidad': cantidad,
                    'nombre': nombre,
                    'code128': code128,
                    'precio': precio_base
                }
                codigoBarras.push(producto_barcode);
        }
    }

    function cambiarCodigo(id, cantidad, nombre, code128,precio_base){

        console.log(id, cantidad, nombre, code128,precio_base);
        if(cantidad==""){ cantidad = 0; }
        cantidad = parseInt(cantidad);

        codigoBarras = localStorage.getItem('codigo');
        if(codigoBarras != null){
            codigoBarras = JSON.parse(codigoBarras);
        }else{
            codigoBarras = [];
        }

        var encontro = false;
        for(var i=0; i < codigoBarras.length; i++){
                if (codigoBarras[i].id_producto == id) {
                        codigoBarras[i].cantidad = cantidad;
                        encontro = true;
                        item = i;
                }
        }
        if(encontro == false){
                var producto_barcode = {
                    'id_producto': id,
                    'cantidad': cantidad,
                    'nombre': nombre,
                    'code128': code128,
                    'precio': precio_base
                }
                codigoBarras.push(producto_barcode);
        }else{
            if(cantidad==0){
                console.log("item: "+item);
            }
        }

        codigoBarras = JSON.stringify(codigoBarras);
        localStorage.setItem('codigo', codigoBarras);
    }

    var imagenFondoSucursal = "logo-principal.png";
    var backgroundColor = 'linear-gradient(-20deg, #2a3d6a 0%, #0B96CA 100%)';

    document.getElementById("imagen-fondo").style.backgroundImage = `url("{{ asset('public/imagenes/`+imagenFondoSucursal+`') }}")`;
    document.getElementById("imagen-principal").src = "{{ asset('public/imagenes/') }}/"+imagenFondoSucursal;
    document.getElementById("titulo-admin").style.background = backgroundColor;

    $(window).on('load', function() {
        /*------------------
            Preloder
        --------------------*/
        $(".loader").fadeOut();
        $("#preloder").delay(0).fadeOut("slow");

    });

    $("#icono-menu-responsive").click(function(){
        document.getElementById("menu-izquierda").style.display = "block";
        document.getElementById("icono-cerrar-menu").style.display = "block";
    });

    $("#icono-cerrar-menu").click(function(){
        document.getElementById("menu-izquierda").style.display = "none";
        document.getElementById("icono-cerrar-menu").style.display = "none";
    });
</script>                    
                    

</body>
</html>
