@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-truck"> </i> PACIENTES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; VER</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}"> <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('pacientes.crear') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE PACIENTES</p></div></a></div>
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


        <div id="content-examen">

                <div id="content-paciente">
                        <div class="titulo-examen">Paciente</div>

                        <div id="caja-paciente">
                            <div class="clave-paciente">Nombres: </div>
                            <div class="valor-paciente">{{$paciente->nombres_paciente}} {{$paciente->apellidos_paciente}} </div>
                            <div class="clave-paciente">Identificación: </div>
                            <div class="valor-paciente">{{$paciente->identificacion}} </div>
                            <div class="clave-paciente">Género: </div>
                            <div class="valor-paciente">{{$paciente->genero}} </div>
                            <div class="clave-paciente">Fecha Nacimiénto: </div>
                            <div class="valor-paciente">{{$paciente->fecha_nacimiento}}, {{$paciente->edad_paciente}} años </div>
                            <div class="clave-paciente">Teléfono: </div>
                            <div class="valor-paciente">{{$paciente->telefono_paciente}} </div>
                            <div class="clave-paciente">Correo Electrónico: </div>
                            <div class="valor-paciente">Sin Correo </div>
                            <div class="clave-paciente">Diagnósticos: </div>
                            <div class="valor-paciente">{{$paciente->diagnosticos}} </div>
                            <div class="clave-paciente">Cirugías: </div>
                            <div class="valor-paciente">{{$paciente->cirugias}} </div>
                        </div>
                </div>
            

                <div id="caja-examenes">
                    <div class="titulo-examen">Exámenes</div>
                    <div id="content-tabla">
                            <table id="tabla" class="display compact cell-border stripe" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Fecha</th>
                                        <th>Sucursal</th>
                                        <th>Doctor</th>
                                        <th style="display: none;">examen</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($examenes as $examen)
                                        <tr>
                                            <td style="width:10px;text-align: center;">{{$loop->iteration}}</td>
                                            <td>{{date("d-m-Y", strtotime($examen->fecha_examen))}}</td>
                                            <td>{{$examen->nombre_sucursal}}</td>
                                            <td>{{$examen->name}} {{$examen->apellido}}</td>
                                            <td style="display: none;">{{$examen->id_examen}}</td>
                                            <td class="td-acciones">
                                                <div class="iconos-acciones">
                                                    <div class="content-acciones">
                                                        <a class="dropdown-content"><i class="icon-forward"> </i> VER</a>
                                                        <i onclick="ver('{{$examen->id_examen}}');" class="icon-forward i-acciones"> </i> &nbsp;
                                                    </div>
                                                </div>
                                            </td>
                                        </tr> 
                                    @endforeach

                                </tbody>
                            </table>
                    </div>
                </div>
        </div>

</div>
<form method="post" action="{{ route('examenes.ver') }}">
    @csrf
    <input type="number" name="ver" id="ver" style="display: none;">
    <button id="boton-ver"></button>
</form>

<style type="text/css">
    #elemento-admin{
        margin-top:20px;
    }

    #content-examen{
        width: 100%;
        float: left;
        //border:1px solid orange;
    }

    #crear-paciente{
        width: 100%;
        max-width: 300px;
        margin-right: auto;
        margin-left: auto;
        left:0;
        right:0;
        padding:7px;
        color:white;
        font-size: 15px;
        letter-spacing: -0.4px;
        background-color: rgba(0,200,200,1);
        display: block;
        float: none;
        border:0px;
        margin-bottom:20px;
        cursor: pointer;
    }

/*::::::::::::::CONTENEDOR INFORMACION PACIENTE:::::::::::::::*/

    #content-informacion-paciente{
        width: 100%;
        float: left;
        margin-right: 20px;
    }

    .titulo-examen{
        width: calc(100% - 40px);
        margin-left: 20px;
        border-bottom:1px solid rgba(215,215,215,0.6);
        font-size:15px;
        color: rgba(52,58,64,1);
        text-transform: uppercase;
        font-weight: 500;
        text-align: center;
        padding:8px 0px 8px 0px;
        float: left;
    }

    #content-paciente{
        float: left;
        border: 1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 30px rgba(100,100,120,0.3);
        background-color: rgba(255,255,255,0.8);
        margin-bottom:20px;
        width: 280px;
        //border:1px solid purple;
        background-color: white;
        height: 500px;
    }

    #caja-paciente{
        width: 100%;
        float: left;
        margin-bottom: 20px;
        //border:1px solid blue;
        padding:20px;
    }
    
    .clave-paciente{
        width: 100%;
        float: left;
        padding: 5px 0px 0px 0px;
        font-size: 13px;
        font-weight: 500;
        letter-spacing: -0.3px;
        //border:1px solid red;
    }

    .valor-paciente{
        width: 100%;
        float: left;
        padding: 0px 0px 10px 0px;
        font-size: 13px;
        font-weight: 400;
        letter-spacing: -0.3px;
        //border:1px solid red;
        display: flex;
        align-items: center;
    }
/*CAJA EXAMENES*/

    #caja-examenes{
        width: calc(100% - 300px);
        float: left;
        margin-left: 20px;
        border:1px solid green;
        border: 1px solid rgba(215,215,215,0.6);
        box-shadow: 0px 0px 30px rgba(100,100,120,0.3);
        background-color: white;
        margin-bottom:20px;
    }

    #content-tabla{
        padding:20px;
        float: left;
        width: 100%;
    }

</style>

<script type="text/javascript">

    $(document).ready( function () {
        $('#tabla').DataTable( {
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Modulos Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'csv',
                    filename: 'Modulos Optica Angeles',
                    title: '',
                    header: false
                },
                {
                    extend: 'pdf',
                    title: 'Módulos Optica Angeles',
                    filename: 'Modulos Optica Angeles',
                },
                {
                    extend: 'copy',
                },
                {
                    extend: 'print',
                    title: 'Módulos Optica Angeles',
                },
            ],
            filename: 'Data export',
            select: true,
            language: {
                searchPlaceholder: "Buscar",
                processing:     "Procesando...",
                search:         "Buscar Módulo&nbsp;:",
                lengthMenu:     "Mostrar _MENU_ módulos",
                info:           "Mostrando módulos del _START_ al _END_ de un total de _TOTAL_ módulos",
                infoEmpty:      "Mostrando módulos del 0 al 0 de un total de 0 módulos",
                infoFiltered:   "(filtrado de un total de _MAX_ módulos)",
                infoPostFix:    "",
                loadingRecords: "Cargando...",
                zeroRecords:    "No se encontraron módulos",
                emptyTable:     "Ningún módulo disponible en esta tabla",
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
</script>




@endsection


