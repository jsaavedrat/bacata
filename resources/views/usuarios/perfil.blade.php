@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-user"> </i> PERFIL &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; VER</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}">         <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><i class="fa fa-question herramientas">                                   </i><div class="content-texto"><p class="texto-icono">AYUDA</p></div></div>
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
<!--:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::VISTA ACTUAL::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<div id="elemento-admin">

        @if($estatus=="exito")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Sucural Principal actualizada con exito</div>@endif
        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; No se pudo actualizar la sucursal</div>@endif
    
    <div id="seccion-perfil">
        <div id="content-imagen-perfil">
            @if(Auth::user()->nombre_imagen_user =="" || Auth::user()->nombre_imagen_user == null)
                <img src="{{ asset('public/imagenes/sistema/users') }}/user_default.png" style="width: 100%;">
            @else
                <img src="{{ asset('public/imagenes/sistema/users') }}/{{ Auth::user()->nombre_imagen_user }}" style="width: 100%;">
            @endif

        </div>

        <form method="post" data-parsley-validate action="{{ route('usuarios.imagen') }}" enctype="multipart/form-data">
            @csrf

            <label for="imagen_usuario" style="float:right;margin-top:-20px;border:0px; width: 40px;text-align: center;background-color: rgba(0,0,0,0);cursor: pointer;"><i class="icon-camera"></i></label>
            <input type="file" name="imagen_usuario" id="imagen_usuario" style="display: none;" required onchange="cambiarImagen();" accept=".png,.jpg">
            <button type="submit" id="boton_imagen" style="float:right;margin-top:-20px;border:0px; width: 40px;text-align: center;background-color: rgba(0,0,0,0);cursor: pointer;display: none;"><i class="icon-upload"></i></button>
        </form>

        <div class="nombre-perfil">{{ Auth::user()->name }} {{ Auth::user()->apellido }}</div>
        <div id="content-clave-valor-perfil">
            <div class="clave-perfil">
                Rol / Cargo
            </div>
            <div class="valor-perfil">
                {{ Auth::user()->getRoleNames()[0] }}
            </div>
            <div class="clave-perfil">
                Identificación
            </div>
            <div class="valor-perfil">
                {{$usuario->identificacion}}
            </div>
            <div class="clave-perfil">
                Correo Electrónico
            </div>
            <div class="valor-perfil">
                {{$usuario->email}}
            </div>
            <div class="clave-perfil">
                Teléfono
            </div>
            <div class="valor-perfil">
                {{$usuario->telefono}}
            </div>
            <div class="clave-perfil">
                Dirección
            </div>
            <div class="valor-perfil">
                Calle 22 A {{$usuario->direccion}} torre 9 apartamento 233 Zipaquirá Cundinamarca
            </div>
        </div>
    </div>

    <div id="seccion-contrasena">
        <div class="content-icono-perfil" id="icono-contrasena">
            <i class="icon-lock"></i>
        </div>
        <div class="nombre-perfil" id="titulo-contrasena">Cambiar contraseña</div>
        <div id="content-clave-valor-perfil">
            <div class="label-campo">
                <label class="label-admin" id="actual-c"><i id="lista" class="icon-dot-single"></i>Contraseña Actual<i id="cont-icon" class="icon-lock-open"></i></label>
                <input type="text" name="contrasena_actual" class="campo-admin" placeholder="Contraseña Actual" spellcheck="false" autocomplete="off" maxlength="50" id="contrasena_actual">
            </div>
            <div class="label-campo">
                <label class="label-admin" id="nueva-c"><i id="lista" class="icon-dot-single"></i>Nueva Contraseña<i id="cont-icon" class="icon-key"></i></label>
                <input type="text" name="nueva_contrasena" class="campo-admin" placeholder="Ingresa la contraseña" spellcheck="false" autocomplete="off" maxlength="50" id="nueva_contrasena">
                <input type="text" name="repetir_contrasena" class="campo-admin" placeholder="Repite la nueva contraseña" spellcheck="false" autocomplete="off" maxlength="50" id="repetir_contrasena">
            </div>
            <div class="boton-admin" id="cambiar-contrasena" onclick="cambiarContrasena();"> Cambiar contraseña</div>
        </div>
    </div>

<style type="text/css">
/*::::::::::::::::::::SECCION PERFIL::::::::::::::::::*/
    #seccion-perfil{
        margin-top:20px;
        border:1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 60px rgba(100,100,120,0.3);
        padding:20px;
        background-color: rgba(225,225,235,0.2);
        width:100%;
        max-width: 350px;
        float: left;
        //height:504px;
    }

    #content-imagen-perfil{
        margin-left: auto;
        margin-right: auto;
        left: 0;
        right: 0;
        overflow: hidden;
        width: 100px;
        height: 100px;
        border:2px solid rgba(255,255,255,0.4);
        border-radius: 50%;
        margin-bottom:5px;
    }

    .nombre-perfil{
        width: 100%;
        float: left;
        text-align:center;
        font-weight: 500;
        padding:10px 5px 10px 5px;
    }

    #content-clave-valor-perfil{
        width:100%;
        float: left;
        border:1px solid rgba(215,215,215,0.6);
        height: 316px;
        background-color:rgba(50,50,50,0.05);
    }

    .clave-perfil{
        width:100%;
        float: left;
        //border:1px solid blue;
        //height: 30px;
        padding:10px 0px 0px 0px;
        font-size:14px;
        text-align: center;
        font-weight: 500;
    }
    .valor-perfil{
        width:100%;
        float: left;
        //border:1px solid blue;
        //height: 30px;
        padding:0px 0px 10px 0px;
        font-size:13px;
        text-align: center;
        font-weight: 400;
    }

/*::::::::::::::::::::SECCION CONTRASEÑA::::::::::::::::::*/
    #seccion-contrasena{
        margin-top:20px;
        border:1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 60px rgba(100,100,120,0.3);
        padding:20px;
        background-color: rgba(225,225,235,0.2);
        width:100%;
        max-width: 350px;
        float: left;
        margin-left: 20px;
    }
    .content-icono-perfil{
        width: 100px;
        float: none;
        height: 100px;
        margin-bottom:5px;
        text-align: center;
        font-size:50px;
        color:gray;
        padding-top:15px;
        border:3px solid gray;
        margin-left: auto;
        margin-right: auto;
        left:0;
        right: 0;
        border-radius: 50%;
    }

/*::::::::::::::::::::SECCION SUCURSAL::::::::::::::::::*/
    #seccion-sucursal{
        margin-top:20px;
        border:1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 60px rgba(100,100,120,0.3);
        padding:20px;
        background-color: rgba(225,225,235,0.2);
        width:calc(100% - 740px);
        float: left;
        margin-left: 20px;
    }

    #sucursal-perfil{
        width:100%;
        float: left;
        height: 100px;
        padding:10px;
        display: flex;
        justify-content: center;
        margin-bottom: 5px;
    }

    .imagen-sucursal-perfil{
        height:100%;
    }

    #content-sucursal-cambiar{
        width: 100%;
        float: left;
        height: 85px;
        //border:1px solid red;
        padding:10px;
        margin:5px 0px 10px 0px;
        display: flex;
        justify-content: center;
    }

    #seccion-tablas{
        width:100%;
        float:left;
        //border:1px solid orange;
        margin-top:20px;
        margin-bottom: 20px;
    }

    #tabla-asistencias{
        width: 350px;
        float: left;
        padding:20px;
        border:1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 60px rgba(100,100,120,0.3);
    }

    #tabla-ventas{
        width: calc(100% - 370px);
        float: left;
        margin-left:20px;
        padding:20px;
        border:1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 60px rgba(100,100,120,0.3);
    }
    
    .label-campo{
        width:100%;
    }

   .boton-admin{
        margin-bottom: 20px;
        width:calc(100% - 40px);
        margin-left:20px;
        float: left;
    }
    #iconos-titulo{
        box-shadow: 0px 0px 0px white;
    }
    .td-numero{
        width:20px;
        text-align: center;
    }

</style>

<script type="text/javascript">

    sucursales_p = {!! json_encode($usuario->sucursales) !!};

    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Ventas {{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Ventas {{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Ventas {{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                    filename: 'Ventas {{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Ventas {{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Venta&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ Ventas",
                info:           "Mostrando ventas del _START_ al _END_ de un total de _TOTAL_ ventas",
                infoEmpty:      "Mostrando ventas del 0 al 0 de un total de 0 ventas",
                infoFiltered:   "(filtrado de un total de _MAX_ ventas)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron ventas",
                emptyTable:     "Ningúna venta disponible en esta tabla",
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

        $('#tabla2').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Asistencias {{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Asistencias {{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Asistencias {{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                    filename: 'Asistencias {{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Asistencias {{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "",
                lengthMenu:     " _MENU_ Asist.",
                info:           "Mostrando Asistencias del _START_ al _END_ de un total de _TOTAL_",
                infoEmpty:      "Mostrando Asistencias del 0 al 0 de un total de 0",
                infoFiltered:   "(filtrado de un total de _MAX_ Asistencias)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron Asistencias",
                emptyTable:     "Ningúna Asistencias disponible en esta tabla",
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
    function clickElemento(elemento){
        document.getElementsByClassName('dt-button')[elemento].click();
    }

    function cambiarContrasena(){

        document.getElementById("actual-c").innerHTML = `<i id="lista" class="icon-dot-single"></i>Contraseña Actual<i id="cont-icon" class="icon-lock-open"></i>`;
        document.getElementById("actual-c").style.color = "rgba(52,58,64,1)";
        document.getElementById("nueva-c").innerHTML = `<i id="lista" class="icon-dot-single"></i>Nueva Contraseña<i id="cont-icon" class="icon-key"></i>`;
        document.getElementById("nueva-c").style.color = "rgba(52,58,64,1)";

        var valid = true;
        var contrasena_actual = document.getElementById("contrasena_actual").value;


        var nueva_contrasena = document.getElementById("nueva_contrasena").value;

        var repetir_contrasena = document.getElementById("repetir_contrasena").value;

        if (contrasena_actual.length < 8) {
            valid = false;
            document.getElementById("actual-c").innerHTML = `<i id="lista" class="icon-dot-single"></i>Escribe más caracteres<i id="cont-icon" class="icon-lock-open"></i>`;
            document.getElementById("actual-c").style.color = "red";
        }

        if (nueva_contrasena.length < 8 || repetir_contrasena.length < 8){
            valid = false;
            document.getElementById("nueva-c").innerHTML = `<i id="lista" class="icon-dot-single"></i>Escribe más caracteres<i id="cont-icon" class="icon-key"></i>`;
            document.getElementById("nueva-c").style.color = "red";
        }else{
            if (nueva_contrasena != repetir_contrasena){
                valid = false;
                document.getElementById("nueva-c").innerHTML = `<i id="lista" class="icon-dot-single"></i>Las contraseñas no coinciden<i id="cont-icon" class="icon-key"></i>`;
                document.getElementById("nueva-c").style.color = "red";
            }
        }

        if(valid==true){
            confirmarContrasena(contrasena_actual,nueva_contrasena);
            document.getElementById("cambiar-contrasena").innerHTML = "Espere...";
        }
    }

    function confirmarContrasena(actual_c,nueva_c){

        var url="{{route('usuarios.contrasena')}}";
        var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "actual_c": actual_c,
            "nueva_c": nueva_c
        };
        $.ajax({
            type: 'POST',
            url: url,
            data: datos,
            success: function(data) {
                console.log("success");
                console.log(data);
                document.getElementById("cambiar-contrasena").innerHTML = "CAMBIAR CONTRASEÑA";
                if (data == "actualizado") {
                    document.getElementById("icono-contrasena").innerHTML = `<i class="icon-check"></i>`;
                    document.getElementById("icono-contrasena").style.borderColor = "green";
                    document.getElementById("icono-contrasena").style.color = "green";
                    document.getElementById("titulo-contrasena").innerHTML = "Contraseña Actualizada";
                    document.getElementById("titulo-contrasena").style.color = "green";
                }else{
                    document.getElementById("icono-contrasena").innerHTML = `<i class="icon-cross"></i>`;
                    document.getElementById("icono-contrasena").style.borderColor = "red";
                    document.getElementById("icono-contrasena").style.color = "red";
                    document.getElementById("titulo-contrasena").innerHTML = "Contraseña NO coincide";
                    document.getElementById("titulo-contrasena").style.color = "red";
                }
                
            },
            error: function(data) {
                console.log("error");
            }
        });
    }

    function sucursalPreferencia(valor_s){
        if(valor_s==""){
            document.getElementById("imagen-cambiar-perfil").src = "{{ asset('public/imagenes/sistema/sucursales') }}/default.png";
        }else{
            for(var i=0; i<sucursales_p.length; i++){
                if(sucursales_p[i].id_sucursal == valor_s){
                    document.getElementById("imagen-cambiar-perfil").src = "{{ asset('public/imagenes/sistema/sucursales') }}/"+sucursales_p[i].nombre_imagen_sucursal;
                }
            }
        }
    }

    $("#cambiar-sucursal").click(function(){
        var valid = true;

        var sucursal_c = document.getElementById("id_sucursal_p").value;
        if (sucursal_c=="") {
            valid=false;
            document.getElementById("titulo-sucursal").innerHTML = "Seleccione una sucursal";
            document.getElementById("titulo-sucursal").style.color = "red";
        }

        return valid;
    });

    function cambiarImagen(){
        var img_u = document.getElementById("imagen_usuario").value;
        if (img_u != "") {
            document.getElementById("boton_imagen").style.display = "block";
        }else{
            document.getElementById("boton-imagen").style.display = "none";
        }
    }

    function seleccionarFirma(){
        console.log("entro");
        var firma_u = document.getElementById("imagen_firma").value;
        if (firma_u != "") {
            document.getElementById("boton_firma").style.display = "block";
        }else{
            document.getElementById("boton-firma").style.display = "none";
        }
    }

    $("#boton-imagen").click(function(){

        var valid = true;

        var img_u = document.getElementById("imagen_usuario").value;

        if (img_u == "") {
            var valid = false;
        }

        return valid;
    });
</script>

@endsection


