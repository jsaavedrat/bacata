@extends('layouts.landing')
@section('content')
    <div class="page-top-info">
        <div class="section-title">
            <h3>PERFIL DEL CLIENTE</h3>
        </div>

        <form method="post" action="{{ route('cliente.modificar') }}">
            @csrf
            <div class="page-top-info">
                <div class="container" style="overflow: hidden!important;">
                    <div id="content-mensaje">
                        @if($estatus=="actualizado")<div id="mensaje-exito" onclick="this.style.display='none'"> <i class="fa fa-check-circle"></i>&nbsp; Usuario Actualizado con Éxito.</div>@endif
                        @if($estatus=="erroractualizar")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Error, ya existe un Usuario con esa identificación y / o Email.</div>@endif
                        @if($estatus=="error")<div id="mensaje-error" onclick="this.style.display='none'"> <i class="fa fa-times-circle"></i>&nbsp; Error, no se pudo actualizar, el Usuario no existe.</div>@endif
                    </div>                

                    <div id="content-formulario-pagar">
                        <div id="formulario-pagar">

                            <div id="texto-pagar">Debe completar todos los campos si desea efectuar compras en linea</div>
                           
                            <div class="label-campo">
                                <label class="label-pagar" id="label_nombre_cliente" for="nombre_cliente"><i id="lista"
                                        class="icon-dot-single"></i>Nombres<i id="cont-icon"
                                        class="icon-user"></i></label>
                                <input type="text" name="nombre_cliente" id="nombre_cliente" class="campo-pagos"
                                    placeholder="Nombre del cliente" spellcheck="false" autocomplete="off" maxlength="35"
                                    required onkeyup="this.value=letras(this.value)" value="{{ $cliente->name }}">

                            </div>

                            <div class="label-campo">
                                <label class="label-pagar" id="label_apellido_cliente" for="apellido_cliente"><i
                                        id="lista" class="icon-dot-single"></i>Apellidos<i id="cont-icon"
                                        class="icon-add-user"></i></label>
                                <input type="text" name="apellido_cliente" id="apellido_cliente" class="campo-pagos"
                                    placeholder="Apellido del cliente" spellcheck="false" autocomplete="off" maxlength="35"
                                    required onkeyup="this.value=letras(this.value)" value="{{ $cliente->apellido }}">
                            </div>
                            <div class="label-campo">
                                <label class="label-pagar" id="label_telefono_cliente" for="telefono_cliente"><i
                                        id="lista" class="icon-dot-single"></i>Teléfono<i id="cont-icon"
                                        class="icon-mobile"></i></label>
                                <input type="text" name="telefono_cliente" id="telefono_cliente" class="campo-pagos"
                                    placeholder="Teléfono del cliente" spellcheck="false" autocomplete="off" maxlength="20"
                                    required onkeyup="this.value=telefono(this.value)" value="{{ $cliente->telefono }}">
                            </div>
                            <div class="label-campo">
                                <div class="label-pagar" id="label_tipo_identificacion_cliente"><i id="lista"
                                        class="icon-dot-single"></i>Tipo de identificación<i id="cont-icon"
                                        class="icon-credit-card"></i></div>
                                <select style="height: 40px;" class="campo-pagos" name="tipo_identificacion_cliente"
                                    id="tipo_identificacion_cliente" required>
                                    <option value="{{ $cliente->id_tipo_identificacion }}">
                                        {{ $cliente->nombre_tipo_identificacion }}
                                    </option>
                                    @foreach ($tipos_identificacion as $tipo_identificacion)
                                        @if ($tipo_identificacion->id_tipo_identificacion != $cliente->id_tipo_identificacion)
                                            <option value="{{ $tipo_identificacion->id_tipo_identificacion }}">
                                                {{ $tipo_identificacion->nombre_tipo_identificacion }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="label-campo">
                                <label class="label-pagar" id="label_identificacion_cliente"
                                    for="identificacion_cliente"><i id="lista" class="icon-dot-single"></i>Identificación<i
                                        id="cont-icon" class="icon-credit-card"></i></label>
                                <input type="text" name="identificacion_cliente" id="identificacion_cliente"
                                    class="campo-pagos" placeholder="N° de identificación" spellcheck="false"
                                    autocomplete="off" maxlength="25" required
                                    onkeyup="this.value=identificacion(this.value)"
                                    value="{{ $cliente->identificacion }}">
                            </div>
                            <div class="label-campo">
                                <label class="label-pagar" id="label_correo_cliente" for="correo_cliente"><i id="lista"
                                        class="icon-dot-single"></i>Correo Electrónico<i id="cont-icon"
                                        class="icon-mail"></i></label>
                                <input type="text" name="correo_cliente" id="correo_cliente" class="campo-pagos"
                                    placeholder="Correo cliente" spellcheck="false" autocomplete="off" maxlength="35"
                                    required value="{{ $cliente->email }}">
                            </div>
                            <div class="label-campo" style="width: 66.6%;">
                                <label class="label-pagar" id="label_direccion_cliente" for="direccion_cliente"><i
                                        id="lista" class="icon-dot-single"></i>Dirección<i id="cont-icon"
                                        class="icon-location"></i></label>
                                <textarea name="direccion_cliente" id="direccion_cliente" class="campo-pagos"
                                    placeholder="Dirección cliente" spellcheck="false" autocomplete="off" maxlength="120"
                                    style="margin-bottom:-5px!important;"
                                    onkeyup="this.value=direccion(this.value)">{{ $cliente->direccion }}</textarea>
                            </div>
                            <input type="hidden" name="id_cliente" value="{{ $cliente->id }}">
                            <button class="boton-pagar" id="crear-cliente" type="submit">Editar Cliente</button>

                        </div>

                    </div>

                </div>
        </form>




    </div>





    <style type="text/css">
        .label-campo {
            width: 33.3%;
            float: left;
            //border:1px solid red;
        }

        #salario {
            //width: 40px;
            margin-bottom: 0px;
            float: right;
            //border:1px solid rgba(215,215,215,0.5);
            height: 38px;
            margin-top: -38px;
            font-size: 12px;
            text-align: right;
            color: rgba(205, 205, 205, 1) !important;
            cursor: pointer;
            padding: 26px 5px 10px 0px;
            letter-spacing: -0.5px;
            border-top: 0px;
            line-height: 12px;
        }

        #cont-icon {
            float: right !important;
            color: rgba(80, 70, 90, 0.4);
        }

        #lista {
            color: rgba(80, 70, 90, 0.5);
        }

        #mensaje-exito {
            padding: 8px;
            background-color: rgba(20, 160, 20, 1);
            color: white;
            font-weight: 600;
            letter-spacing: -0.5px;
            border-radius: 2px;
            margin-bottom: 2px;
            font-size: 15px;
        }

        .content-sucursal {
            width: 33.33%;
            height: 76px;
            float: left;
            border: 1px solid rgba(215, 215, 215, 0.9);
        }

        .nombre-sucursal {
            width: 100%;
            //border:1px solid blue;
            float: left;
            //height: 30px;
            font-size: 15px;
            color: rgba(80, 70, 90, 1);
            letter-spacing: -0.5px;
            padding: 20px 20px 0px 30px;
            //text-align: center;
            font-weight: 500;
        }

        #check {
            float: right;
            //margin-right: -10px;
        }

        .content-imagen-sucursal {
            width: 100%;
            height: 46px;
            float: left;
            //border: 1px solid black;
            display: flex;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(215, 215, 215, 0.9);
            border-top: 0px;
        }

        .label-sucursal {
            cursor: pointer;
        }

    </style>
    <script src="{{ asset('public/ecommerce/js/jquery-3.2.1.min.js') }}"></script>

    <script type="text/javascript">
        function obtenerSucursales() {
            var checkboxs = document.getElementsByClassName('checkSucursal');
            sucursales = [];
            var estatus_sucursal = [];
            for (i = 0; i < checkboxs.length; i++) {

                var checkId = document.getElementsByClassName('checkSucursal')[i].checked;
                //console.log(values);

                //var checks = document.getElementsByClassName(nombreModulo)[i].checked;
                //console.log(checks);
                var idSucursal = document.getElementsByClassName('checkSucursal')[i].value;

                if (checkId == true) {
                    estatus_sucursal = {
                        'id_sucursal': idSucursal,
                        'estado': 'activo'
                    };

                } else {
                    estatus_sucursal = {
                        'id_sucursal': idSucursal,
                        'estado': 'inactivo'
                    };
                }
                sucursales.push(estatus_sucursal);
            }
            sucursales = JSON.stringify(sucursales);
            //console.log(sucursales);
            document.getElementById('vector-sucursales').value = sucursales;
        }


        $("#crear-cliente").click(function() {


            var labels = document.getElementsByClassName('label-pagar');
            for (i = 0; i < labels.length; i++) {

                document.getElementsByClassName('label-pagar')[i].style.color = "rgba(80,70,90,1)";
                document.getElementsByClassName('label-pagar')[i].style.fontWeight = "450";
            }
        

            var expr1 =
                /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g; /*solo letras mayusculas, minusculas, espacios y ñ*/
            var expr11 =
                /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g; /*solo letras mayusculas, minusculas, espacios y ñ*/
            var expr2 = /^[0-9\+-\s]+$/;
            var expr3 = /^[0-9\-\s]+$/;
            var expr4 = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
            var expr5 = /^[a-zA-Z0-9À-ÿ\.\#-\s]+$/;
            var expr6 = /^[0-9+]+$/;

            var valid = true;

            var nombre = $("#nombre_cliente").val();
            var apellido = $("#apellido_cliente").val();
            var telefono = $("#telefono_cliente").val();
            var tipoIdentificacion = $("#tipo_identificacion_cliente").val();
            var identificacion = $("#identificacion_cliente").val();
            var correo = $("#correo_cliente").val();
            var direccion = $("#direccion_cliente").val();
            //var salario = $("#salario_cliente").val();
            //var fecha = $("#fecha_contratacion_cliente").val();
            var rol = $("#rol_cliente").val();
            //var contrato = $("#contrato_cliente").val();

       

            if (nombre == "" || nombre.length < 3) {
                document.getElementById("label_nombre_cliente").style.color = "red";
                document.getElementById("label_nombre_cliente").style.fontWeight = "500";
                document.getElementById("label_nombre_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe mas letras<i id='cont-icon' class='icon-user'></i>";
                document.getElementById('nombre_cliente').focus();
                valid = false;
            } else if (!expr1.test(nombre)) {
                document.getElementById("label_nombre_cliente").style.color = "red";
                document.getElementById("label_nombre_cliente").style.fontWeight = "500";
                document.getElementById("label_nombre_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe solo texto<i id='cont-icon' class='icon-user'></i>";
                document.getElementById('nombre_cliente').value = "";
                document.getElementById('nombre_cliente').focus();
                valid = false;
            }

            if (apellido == "" || apellido.length < 3) {
                document.getElementById("label_apellido_cliente").style.color = "red";
                document.getElementById("label_apellido_cliente").style.fontWeight = "500";
                document.getElementById("label_apellido_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe mas letras<i id='cont-icon' class='icon-add-user'></i>";
                document.getElementById('apellido_cliente').focus();
                valid = false;
            } else if (!expr11.test(apellido)) {
                //alert(apellido);
                document.getElementById("label_apellido_cliente").style.color = "red";
                document.getElementById("label_apellido_cliente").style.fontWeight = "500";
                document.getElementById("label_apellido_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe solo texto<i id='cont-icon' class='icon-add-user'></i>";
                document.getElementById('apellido_cliente').value = "";
                document.getElementById('apellido_cliente').focus();
                valid = false;
                //console.log("entro");
            }

            if (telefono == "" || telefono.length < 7) {
                document.getElementById("label_telefono_cliente").style.color = "red";
                document.getElementById("label_telefono_cliente").style.fontWeight = "500";
                document.getElementById("label_telefono_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe mas números<i id='cont-icon' class='icon-mobile'></i>";
                document.getElementById('telefono_cliente').focus();
                valid = false;
            } else if (!expr2.test(telefono)) {
                document.getElementById("label_telefono_cliente").style.color = "red";
                document.getElementById("label_telefono_cliente").style.fontWeight = "500";
                document.getElementById("label_telefono_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe solo números<i id='cont-icon' class='icon-mobile'></i>";
                document.getElementById('telefono_cliente').value = "";
                document.getElementById('telefono_cliente').focus();
                valid = false;
            }

            if (tipoIdentificacion == "") {
                document.getElementById("label_tipo_identificacion_cliente").style.color = "red";
                document.getElementById("label_tipo_identificacion_cliente").style.fontWeight = "500";
                document.getElementById("label_tipo_identificacion_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Seleccione tipo de identificación<i id='cont-icon' class='icon-credit-card'></i>";
                document.getElementById('tipo_identificacion_cliente').focus();
                valid = false;
            }

            if (identificacion == "" || identificacion.length < 7) {
                document.getElementById("label_identificacion_cliente").style.color = "red";
                document.getElementById("label_identificacion_cliente").style.fontWeight = "500";
                document.getElementById("label_identificacion_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe más números<i id='cont-icon' class='icon-credit-card'></i>";
                document.getElementById('identificacion_cliente').focus();
                valid = false;
            } else if (!expr3.test(identificacion)) {
                document.getElementById("label_identificacion_cliente").style.color = "red";
                document.getElementById("label_identificacion_cliente").style.fontWeight = "500";
                document.getElementById("label_identificacion_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe solo números<i id='cont-icon' class='icon-credit-card'></i>";
                document.getElementById('identificacion_cliente').value = "";
                document.getElementById('identificacion_cliente').focus();
                valid = false;
            }

            if (correo == "" || correo.length < 14) {
                document.getElementById("label_correo_cliente").style.color = "red";
                document.getElementById("label_correo_cliente").style.fontWeight = "500";
                document.getElementById("label_correo_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe más carácteres<i id='cont-icon' class='icon-mail'></i>";
                document.getElementById('correo_cliente').focus();
                valid = false;
            } else if (!expr4.test(correo)) {
                document.getElementById("label_correo_cliente").style.color = "red";
                document.getElementById("label_correo_cliente").style.fontWeight = "500";
                document.getElementById("label_correo_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Formato incorrecto de e-mail<i id='cont-icon' class='icon-mail'></i>";
                //document.getElementById('correo_cliente').value="";
                document.getElementById('correo_cliente').focus();
                valid = false;
            }

            if (direccion == "" || direccion.length < 20) {
                document.getElementById("label_direccion_cliente").style.color = "red";
                document.getElementById("label_direccion_cliente").style.fontWeight = "500";
                document.getElementById("label_direccion_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe más texto<i id='cont-icon' class='icon-location'></i>";
                document.getElementById('direccion_cliente').focus();
                valid = false;
            } else if (!expr5.test(direccion)) {
                document.getElementById("label_direccion_cliente").style.color = "red";
                document.getElementById("label_direccion_cliente").style.fontWeight = "500";
                document.getElementById("label_direccion_cliente").innerHTML =
                    "<i id='lista' class='icon-dot-single'></i>Escribe letras numeros - . #<i id='cont-icon' class='icon-location'></i>";
                //document.getElementById('correo_cliente').value="";
                document.getElementById('direccion_cliente').focus();
                valid = false;
            }

           



        });

        function letras(string) { //Solo letras
            var out = '';
            var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ '; //Caracteres validos

            for (var i = 0; i < string.length; i++)
                if (filtro.indexOf(string.charAt(i)) != -1)
                    out += string.charAt(i);

            var str = "";

            for (i = 0; i < out.length; i++) {
                if (i == 0) {
                    if (out[i] != " ") {
                        str = str + out[i].toUpperCase();
                    } else {
                        str = "";
                    }
                } else {
                    if (out[i - 1] == " ") {
                        str = str + out[i].toUpperCase();
                    } else {
                        str = str + out[i];
                    }
                }
            }
            out = str;

            return out;
        }

        function telefono(string) { //Solo letras
            var out = '';
            var filtro = '1234567890+ '; //Caracteres validos


            for (var i = 0; i < string.length; i++)
                if (filtro.indexOf(string.charAt(i)) != -1)
                    out += string.charAt(i);
            return out;
        }

        function identificacion(string) { //Solo letras
            var out = '';
            var filtro = '1234567890-'; //Caracteres validos


            for (var i = 0; i < string.length; i++)
                if (filtro.indexOf(string.charAt(i)) != -1)
                    out += string.charAt(i);
            return out;
        }

       

        function direccion(string) { //Solo letras
            var out = '';
            var filtro =
                'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ 1234567890-+#/.,'; //Caracteres validos

            for (var i = 0; i < string.length; i++)
                if (filtro.indexOf(string.charAt(i)) != -1)
                    out += string.charAt(i);

            var str = "";

            for (i = 0; i < out.length; i++) {
                if (i == 0) {
                    if (out[i] != " ") {
                        str = str + out[i].toUpperCase();
                    } else {
                        str = "";
                    }
                } else {
                    if (out[i - 1] == " ") {
                        str = str + out[i].toUpperCase();
                    } else {
                        str = str + out[i];
                    }
                }
            }
            out = str;

            return out;
        }

      
    </script>
@endsection
