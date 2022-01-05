@extends('layouts.app')
@section('content')
<div id="titulo-admin">
    <div id="icono-menu-responsive"><i class="icon-menu"></i></div>
    <div id="migajas-titulo"><i class="fa fa-truck"> </i> CLIENTES &nbsp;<i class="fa fa-angle-right"> </i>&nbsp; REGISTRAR</div>
    <div id="content-imagen-titulo"><img style="height:100%;float: right;" id="imagen-principal"></div>
</div>

<div id="iconos-titulo">
    <div class="icono-titulo"><a href="{{ route('home') }}"> <i class="fa fa-home herramientas"></i><div class="content-texto"><p class="texto-icono">INICIO</p></div></a></div>
    <div class="icono-titulo"><a href="{{ route('clientes.lista') }}"><i class="icon-list herramientas"> </i><div class="content-texto"><p class="texto-icono">LISTA DE PACIENTES</p></div></a></div>
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

    <form method="post" action="{{Route('clientes.guardar')}}">
        @csrf
        <div id="content-examen">

            <div id="content-informacion-paciente">

                    <div id="content-paciente">
                            <div class="titulo-examen">Información del cliente</div>

                            <div id="caja-paciente">

                                <div id="content-mensaje">
                                    @if($estatus=="exitoActualizar")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Cliente Actualizado con Éxito.</div>@endif
                                    @if($estatus=="exitoCrear")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i> Cliente Creado con Éxito.</div>@endif
                                </div>

                                    <input type="hidden" name="id_cliente" id="id_cliente" value="">

                                    <div class="content-input-paciente">
                                        <div class="label-paciente">Tipo Documento</div>
                                        <select class="input-paciente" name="tipo_identificacion" style="padding:0px 0px 0px 5px!important;" onchange="tipo_documento();" required>
                                            <option>Tipo</option>
                                            @foreach($tipos_identificacion as $tipo_identificacion)
                                                <option value="{{$tipo_identificacion->id_tipo_identificacion}}">{{$tipo_identificacion->nombre_tipo_identificacion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="content-input-paciente">
                                        <div class="label-paciente">Documento</div>
                                        <input class="input-paciente" name="identificacion" placeholder="Documento" onkeyup="buscarIdentificacion(this.value);" required maxlength="20"  disabled>
                                    </div>
                                    <div class="content-input-paciente">
                                        <div class="label-paciente">Nombres</div>
                                        <input class="input-paciente" name="nombre" placeholder="Ingrese el nombre" disabled onkeyup="this.value=letras(this.value)" required maxlength="50">
                                    </div>
                                    <div class="content-input-paciente">
                                        <div class="label-paciente">Apellidos</div>
                                        <input class="input-paciente" name="apellido" placeholder="Ingrese apellido" disabled onkeyup="this.value=letras(this.value)" required maxlength="50">
                                    </div>
                                    <div class="content-input-paciente">
                                        <div class="label-paciente">Telefono</div>
                                        <input class="input-paciente" name="telefono_cliente" placeholder="Teléfono" disabled onkeyup="this.value=telefono(this.value)" maxlength="40">
                                    </div>
                                    <div class="content-input-paciente">
                                        <div class="label-paciente">Correo Electrónico</div>
                                        <input class="input-paciente" name="correo_cliente" placeholder="Correo Electrónico" disabled maxlength="40">
                                    </div>
                            </div>
                    </div>
            </div>

        </div>

        <button id="crear-paciente">Registrar Cliente</button>

    </form>    

</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="{{ asset('public/js/select2.js') }}"></script>
<style type="text/css">
    #elemento-admin{
        margin-top:20px;
    }

    #content-examen{
        width: 100%;
        float: left;
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
        width: 100%;
        //border:1px solid purple;
        background-color: white;
    }

    #caja-paciente{
        width: calc(100% - 20px);
        float: left;
        margin-left: 10px;
        margin-bottom: 20px;
        //border:1px solid blue;
    }
    .caja-formula{
        width: calc(100% - 20px);
        float: left;
        margin-left: 10px;
        margin-bottom: 20px;
    }

    .content-input-observaciones{
        float: left;
        width: 20%;
        padding:0px 5px 0px 5px;
    }
    .content-input-formula textarea{
        padding:6px!important;
    }

    .content-input-formula{
        float: left;
        width: 50%;
        padding:0px 5px 0px 5px;
        //border:1px solid orange;
    }

    .content-input-paciente{
        float: left;
        width: 25%;
        padding:10px 10px 0px 10px;
        //border:1px solid red;
    }

    .label-paciente{
        width: 100%;
        height: 40px;
        float: left;
        color: rgba(50,50,50,1);
        font-size: 14px;
        letter-spacing: -0.4px;
        font-weight: 400;
        padding:18px 0px 7px 10px;
        text-shadow: 0px 0px 2px white;
    }
    .input-paciente{
        width: 100%;
        height: 35px;
        float: left;
        border:0px solid rgba(215,215,215,0.25);
        padding:10px;
        font-weight: 500;
        color: rgba(50,50,50,1);
        font-size: 12px;
        background-color: white;
        //box-shadow: 0px 0px 20px rgba(0,0,0,0.09);
        //background-color: rgba(250,250,250,1);
        background-color: rgba(250,250,250,1);
        border:1px solid rgba(215,215,215,0.2);
    }
    .input-paciente::placeholder {
        color:rgba(180,180,180,1);
        font-weight: 300;
    }
    textarea::-webkit-scrollbar {
        display: none;
    }


    .select2-hidden-accessible{
        width: 100%!important;
        border:0px solid rgba(215,215,215,0.9)!important;
        float: left!important;
        position: relative!important;
        overflow: hidden!important;
        margin-top:-1px!important;
        overflow-y: scroll!important;
    }

    .select2-selection, .select2-selection--multiple{
        border:1px solid rgba(215,215,215,0.9)!important;
        padding:2px!important;
        font-weight: 500!important;
        color: rgba(52,58,64,1)!important;
        font-size: 12px!important;
        border-radius: 0px!important;
        width: 100%!important;
        float: left!important;
        height: 32px!important;
        overflow-y: scroll!important;
    }

    .select2-selection::-webkit-scrollbar {
        display: none;
    }

    .select2-results__options li{
        font-size: 13px!important;
        line-height: 12px!important;
    }

    .select2-selection__choice{
        margin:1px 0px 0px 1px!important;
        padding: 0px!important;
        font-size: 9px!important;
    }

    .select2-results__option, .select2-results__message{
        
        font-size: 12px!important;
    }

    .select2-dropdown{
        border-color: rgba(215,215,215,0.9)!important;
    }
    .select2-search__field:focus{
        border:0px!important;
    }


</style>

<script type="text/javascript">

    n = 0;
    buscar = false;
    cirugias_db = {!! json_encode($cirugias) !!};
    diagnosticos_db = {!! json_encode($diagnosticos) !!};

    window.setInterval(function(){
            n++;
            if(n == 3 && buscar == true){
                document.getElementsByClassName("input-paciente")[1].disabled = true;
                buscarPaciente();
            }
            if (n == 10){
                n = 4;
            }
    },1000);

    function tipo_documento(){
            document.getElementsByClassName("input-paciente")[1].disabled = false;
            document.getElementsByClassName("input-paciente")[1].focus();
    }


    function buscarIdentificacion(identificacion){

            document.getElementById("id_cliente").value = "";
            document.getElementsByClassName("input-paciente")[2].value = "";
            document.getElementsByClassName("input-paciente")[3].value = "";
            document.getElementsByClassName("input-paciente")[4].value = "";
            document.getElementsByClassName("input-paciente")[5].value = "";
            
            document.getElementsByClassName("input-paciente")[2].disabled = true;
            document.getElementsByClassName("input-paciente")[3].disabled = true;
            document.getElementsByClassName("input-paciente")[4].disabled = true;
            document.getElementsByClassName("input-paciente")[5].disabled = true;
            $("#genero").val($("#genero option:first").val());

            document.getElementById("crear-paciente").innerHTML = "Registrar Cliente";

            if(identificacion.length >= 6){
                    buscar = true;
                    n = 0;
            }else{
                    buscar = false;
            } 
    }

    function buscarPaciente(){
            var tipo_identificacion = document.getElementsByClassName("input-paciente")[0].value;
            var identificacion = document.getElementsByClassName("input-paciente")[1].value;

            console.log(tipo_identificacion);
            console.log(identificacion);

            var url="{{route('clientes.cliente')}}";
            var datos = {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "tipo_identificacion": tipo_identificacion,
                "identificacion": identificacion
            };
            $.ajax({
                type: 'POST',
                url: url,
                data: datos,
                success: function(data) {
                    console.log("success");
                    var cliente = JSON.parse(data);
                    
                    document.getElementsByClassName("input-paciente")[1].disabled = false;
                    document.getElementsByClassName("input-paciente")[1].focus();

                    if(cliente != null){
                            console.log(cliente);

                            document.getElementById("id_cliente").value = cliente.id_cliente;

                            document.getElementsByClassName("input-paciente")[2].value = cliente.nombres;
                            document.getElementsByClassName("input-paciente")[3].value = cliente.apellidos;
                            document.getElementsByClassName("input-paciente")[4].value = cliente.telefono;
                            document.getElementsByClassName("input-paciente")[5].value = cliente.email;

                            document.getElementsByClassName("input-paciente")[2].disabled = true;
                            document.getElementsByClassName("input-paciente")[3].disabled = true;
                            document.getElementsByClassName("input-paciente")[4].disabled = false;
                            document.getElementsByClassName("input-paciente")[4].focus();
                            document.getElementsByClassName("input-paciente")[5].disabled = false;

                            document.getElementById("crear-paciente").innerHTML = "Actualizar Cliente";

                    }else{
                            console.log("el cliente no existe");
                            document.getElementById("id_cliente").value = "";

                            document.getElementsByClassName("input-paciente")[2].disabled = false;
                            document.getElementsByClassName("input-paciente")[3].disabled = false;
                            document.getElementsByClassName("input-paciente")[4].disabled = false;
                            document.getElementsByClassName("input-paciente")[5].disabled = false;
                    }


                },
                error: function(data) {
                    console.log("error");
                }
            });
    }





    function letras(string){
            var out = '';
            var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ ';//Caracteres validos
            
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


    function telefono(string){
            var out = '';
            var filtro = '1234567890+ ';
            
            
            for (var i=0; i<string.length; i++)
                if (filtro.indexOf(string.charAt(i)) != -1) 
                    out += string.charAt(i);
            return out;
    }


    function direccion(string){
            var out = '';
            var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ 1234567890-+#/.,';//Caracteres validos
            
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


    function numeros(string){
            var out = '';
            var filtro = '1234567890+-.,';
            
            
            for (var i=0; i<string.length; i++)
                if (filtro.indexOf(string.charAt(i)) != -1) 
                    out += string.charAt(i);
            return out;
    }


    $(document).ready( function() {
        $("input, textarea").attr('spellcheck',false);
        $('input').attr('autocomplete','off');
        $('textarea').attr('autocomplete','off');
        $('select').attr('autocomplete','off');

        $('.select2-cirugias').select2({
            placeholder: "Seleccione cirugías"
        });

        $('.select2-diagnostico').select2({
            placeholder: "Seleccione diagnósticos"
        });

        $('.select2-familiares').select2({
            placeholder: "Ant. Familiares"
        });

        $('.select2-formula').select2({
            placeholder: "Ant. Familiares"
        });
    });

    $("#crear-paciente").click(function(){

        var valid = true;

        var nombres = document.getElementsByClassName("input-paciente")[2].value;
        var apellidos = document.getElementsByClassName("input-paciente")[3].value;
        var telefono = document.getElementsByClassName("input-paciente")[4].value;
        var correo = document.getElementsByClassName("input-paciente")[5].value;

        if(nombres == ""){
            valid = false;
        }

        if(apellidos == ""){
            valid = false;
        }

        if(telefono == ""){
            valid = false;
        }

        if(correo == ""){
            valid = false;
        }

        return valid;
    });


</script>




@endsection


