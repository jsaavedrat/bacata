@extends('layouts.landing')
@section('content')
<div class="page-top-info">
	<div class="container" style="overflow: hidden!important;">
		
		<div class="section-title">
            <h3>PAGO O ABONO ONLINE</h3>
        </div>
        <div id="content-formulario-pagar">
            <div id="formulario-pagar">
                
                <div id="texto-pagar">Bienvenido al sistema de pagos On-Line de Óptica Ángeles, aqui podras realizar pagos o abonos, por favor ingresa la información solicitada para continuar con el pago. !Esta operacion <b>NO</b> está vinculada al pago de productos E-Commerce.</div>
                <label class="label-campo">
                    <div id="sombreado">
                        <div class="label-pagar" id="label-nombre-pagar"> <i id="lista" class="icon-dot-single"></i>Nombre y Apellido: <i id="cont-icon" class="icon-user"></i></div> 
                        <input type="text"   class="campo-pagos"  id="nombre-pagar"  placeholder="Nombre y Apellido" onkeyup="this.value=nombrePago(this.value)" maxlength="50" spellcheck="false" autocomplete="off" value="">
                    </div>
                </label>
                
                <label class="label-campo">
                    <div id="sombreado">
                        <div class="label-pagar" id="label-telefono-pagar"> <i id="lista" class="icon-dot-single"></i>Teléfono: <i id="cont-icon" class="icon-mobile"></i></div> 
                        <input type="text"   class="campo-pagos"  id="telefono-pagar"  placeholder="Teléfono" onkeyup="this.value=telefonoPago(this.value)" maxlength="50" spellcheck="false" autocomplete="off" value="">
                    </div>
                </label>
                
                <label class="label-campo">
                    <div id="sombreado">
                        <div class="label-pagar" id="label-direccion-pagar"> <i id="lista" class="icon-dot-single"></i>Dirección: <i id="cont-icon" class="icon-location-pin"></i></div> 
                        <textarea   class="textarea-pagos"  id="direccion-pagar"  placeholder="Dirección" onkeyup="this.value=direccionPago(this.value)" maxlength="100" spellcheck="false" autocomplete="off"></textarea>
                    </div>
                </label>
                
                <label class="label-campo">
                    <div id="sombreado">
                        <div class="label-pagar" id="label-descripcion-pagar"> <i id="lista" class="icon-dot-single"></i>Descripción: <i id="cont-icon" class="icon-text"></i></div> 
                        <textarea   class="textarea-pagos"  id="descripcion-pagar"  placeholder="¿Qué estas pagando o abonando?" onkeyup="this.value=descripcionPago(this.value)" maxlength="100" spellcheck="false" autocomplete="off"></textarea>
                    </div>
                </label>
                
                <label class="label-campo">
                    <div id="sombreado">
                        <div class="label-pagar" id="label-correo-pagar"> <i id="lista" class="icon-dot-single"></i>Correo Electrónico: <i id="cont-icon" class="icon-mail"> </i></div>
                        <input type="text"   class="campo-pagos"  id="correo-pagar" placeholder="Ingresa tu e-Mail" maxlength="50" onkeyup="this.value=correoPago(this.value)" spellcheck="false" autocomplete="off" value="">
                    </div>
                </label>
                
                
                <label class="label-campo">
                    <div id="sombreado">
                        <div class="label-pagar" id="label-monto-pagar"> <i id="lista" class="icon-dot-single"></i>Monto: <i id="cont-icon" class="icon-credit"> </i></div>
                        <input type="number" class="campo-pagos"  id="monto-pagar"  placeholder="Ingresa el Monto" value="">
                    </div>
                </label>
                
                    <div class="boton-pagar" id="pagar">Pagar con PayU<i class="icon-credit"> </i></div>
            </div>
            <div id="cont-payu"><img src="{{ asset('public/imagenes/payu2x.png') }}" style="height:100%;float:right;"></div>
        </div>

	</div>
</div>


<script src="{{ asset('public/ecommerce/js/jquery-3.2.1.min.js') }}"></script>
<script>
        
    $("#pagar").click(function(){
        
        document.getElementById("label-nombre-pagar").style.color="rgba(44,75,108,1)";
        document.getElementById("label-nombre-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Nombre y Apellido: <i id='cont-icon' class='icon-user'></i>";
        
        document.getElementById("label-telefono-pagar").style.color="rgba(44,75,108,1)";
        document.getElementById("label-telefono-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Teléfono: <i id='cont-icon' class='icon-mobile'></i>";
        
        document.getElementById("label-direccion-pagar").style.color="rgba(44,75,108,1)";
        document.getElementById("label-direccion-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Dirección: <i id='cont-icon' class='icon-location-pin'></i>";
        
        document.getElementById("label-descripcion-pagar").style.color="rgba(44,75,108,1)";
        document.getElementById("label-descripcion-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Descripción: <i id='cont-icon' class='icon-text'></i>";
        
        document.getElementById("label-correo-pagar").style.color="rgba(44,75,108,1)";
        document.getElementById("label-correo-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Correo Electrónico: <i id='cont-icon' class='icon-mail'> </i>";
        
        document.getElementById("label-monto-pagar").style.color="rgba(44,75,108,1)";
        document.getElementById("label-monto-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Monto: <i id='cont-icon' class='icon-credit'> </i>";
        
        var valid = true;
        var expr = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
        var exprNombre = /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g;
        var exprTelefono= /^[0-9+]+$/;
        var exprDireccion= /^[a-zA-Z0-9À-ÿ\.\#-\s]+$/;
        
        var nombre = document.getElementById('nombre-pagar').value;
        var telefono = document.getElementById('telefono-pagar').value;
        var direccion = document.getElementById('direccion-pagar').value;
        var descripcion = document.getElementById('descripcion-pagar').value;
        var email = document.getElementById('correo-pagar').value;
        var monto = document.getElementById('monto-pagar').value;
        /*console.log(nombre);
        console.log(telefono);
        console.log(direccion);
        console.log(descripcion);
        console.log(email);
        console.log(monto);*/

        if(nombre=="" || nombre.length<7){
            document.getElementById("label-nombre-pagar").style.color="red";
            document.getElementById("label-nombre-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Escribe mas letras: <i id='cont-icon' class='icon-user'></i>";
            document.getElementById('nombre-pagar').focus();
            valid = false;
        }else if(!exprNombre.test(nombre)){
            document.getElementById("label-nombre-pagar").style.color="red";
            document.getElementById("label-nombre-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Escribe letras: <i id='cont-icon' class='icon-user'></i>";
            document.getElementById('nombre-pagar').focus();
            valid = false;
        }else if(nombre.length>6){
            var espacio = nombre.indexOf(" ");
            if( espacio == -1 ) {
                document.getElementById("label-nombre-pagar").style.color="red";
                document.getElementById("label-nombre-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Escribe tu apellido: <i id='cont-icon' class='icon-user'></i>";
                document.getElementById('nombre-pagar').focus();
                valid = false;
            }
        }

        if(telefono=="" || telefono.length<7){
            document.getElementById("label-telefono-pagar").style.color="red";
            document.getElementById("label-telefono-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Escribe más numeros: <i id='cont-icon' class='icon-mobile'></i>";
            document.getElementById('telefono-pagar').focus();
            valid = false;
        }else if(!exprTelefono.test(telefono)){
            document.getElementById("label-telefono-pagar").style.color="red";
            document.getElementById("label-telefono-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Formato incorrecto telefono: <i id='cont-icon' class='icon-mobile'></i>";
            document.getElementById('telefono-pagar').focus();
            valid = false;
        }

        if(direccion=="" || direccion.length<20){
            document.getElementById("label-direccion-pagar").style.color="red";
            document.getElementById("label-direccion-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Escribe más letras: <i id='cont-icon' class='icon-location-pin'></i>";
            document.getElementById('direccion-pagar').focus();
            valid = false;
        }else if(!exprDireccion.test(direccion)){
            document.getElementById("label-direccion-pagar").style.color="red";
            document.getElementById("label-direccion-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Formato incorrecto E-mail: <i id='cont-icon' class='icon-location-pin'></i>";
            document.getElementById('direccion-pagar').focus();
            valid = false;
        }

        if(descripcion.length<15){
            document.getElementById("label-descripcion-pagar").style.color="red";
            document.getElementById("label-descripcion-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Escribe más texto: <i id='cont-icon' class='icon-list'> </i>";
            document.getElementById('descripcion-pagar').focus();
            valid = false;
        }

        if(email=="" || email.length<14){
            document.getElementById("label-correo-pagar").style.color="red";
            document.getElementById("label-correo-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Escribe más caracteres: <i id='cont-icon' class='icon-mail'> </i>";
            document.getElementById('correo-pagar').focus();
            valid = false;
        }else if(!expr.test(email)){
            document.getElementById("label-correo-pagar").style.color="red";
            document.getElementById("label-correo-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Formato incorrecto E-Mail: <i id='cont-icon' class='icon-mail'> </i>";
            document.getElementById('correo-pagar').focus();
            valid = false;
        }

        if(monto.length<5){
            document.getElementById("label-monto-pagar").style.color="red";
            document.getElementById("label-monto-pagar").innerHTML=" <i id='lista' class='icon-dot-single'></i>Minimo $ 10.000 COP: <i id='cont-icon' class='icon-credit'> </i>";
            document.getElementById('monto-pagar').focus();
            valid = false;
        }
        
        if(valid == true){
            
            document.getElementById("formulario-pagar").innerHTML = "<div id='espera-transaccion'> <div class='cargando-pago'></div>VERIFICANDO INFORMACIÓN</div>";
        
            var url="{{route('pagar')}}";
            var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),"email": email, "monto": monto, "descripcion": descripcion, "nombre": nombre, "telefono": telefono, "direccion": direccion
            };
            $.ajax({
                type: 'GET',
                url: url,
                data: datos,
                success: function(data) {
                        console.log("success");
                        var x = JSON.parse(data);
                        var f = x.f;
                        var id = x.id;
                        document.getElementById("formulario-pagar").innerHTML = f;
                        if(id != undefined){
                            document.getElementById(id).click();
                        }
                },
                error: function(data) {
                        console.log("error");
                }
            });
        }

    });
    
    function nombrePago(string){
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ ';
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }
    
    function telefonoPago(string){
        var out = '';
        var filtro = '0123456789+';
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }
    
    function direccionPago(string){
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890-#. ';
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }
    
    function descripcionPago(string){
        var out = '';
        var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890 ';
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }
    
    function correoPago(string){//Solo letras
        var out = '';
        var filtro = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ.!#$%@&'*/=?^_+-`{|}~1234567890";
        
        for (var i=0; i<string.length; i++)
            if (filtro.indexOf(string.charAt(i)) != -1) 
                out += string.charAt(i);
        return out;
    }
    
    function cerrarModalPagar(){
        $("#modal-pagar").fadeOut();
    }

    function realizarPago(){

        carrito = localStorage.getItem('carrito');
        

        var url="{{route('ecommerce.pagarPayu')}}";
            var datos = {
            "_token": $('meta[name="csrf-token"]').attr('content'),"email": '{{Auth::user()->email}}', "productos": carrito, "descripcion": 'Compras Online', "nombre": '{{Auth::user()->name}} {{Auth::user()->apellido}}', "telefono": '{{Auth::user()->telefono}}', "direccion": '{{Auth::user()->direccion}}'
            };
            $.ajax({
                type: 'GET',
                url: url,
                data: datos,
                success: function(data) {

                        console.log("success");
                        var x = JSON.parse(data);

                        var f = x.f;
                        var id = x.id;
                        //console.log(x.existe);
                        document.getElementById("formulario-pagar").innerHTML = f;
                        if((x.existe != false) && (id != undefined)){
                            document.getElementById(id).click();
                        }else{
                            document.getElementById("formulario-pagar").innerHTML = "<h2 style='text-align:center'><i class='flaticon-cancel-1'></i> Ocurrio un error con los productos solicitados</h2>";
                        }
                },
                error: function(data) {
                        console.log("error");
                }
            });
    }
    
</script>










































<!--<div id="seccion-nosotros">
	<div id="content-logo-fondo-nosotros">
		<img id="imagen-logo-fondo-nosotros" src="{{ asset('public/imagenes/logo-principal.png') }}">
		<div id="transparencia-logo-fondo-nosotros"></div>
	</div>
</div>

<div id="contenido-quienes">
		
	<div class="hoja-quienes">
		<div id="titulo-quienes">
			<div id="titulo-borde-quienes">¿QUIENES &nbsp; SOMOS?</div>
		</div>
		<div class="subtitulo-quienes">
			<i class="icon-dot-single"></i> MISIÓN
		</div>
		<div class="texto-quienes">
			Estamos a la vanguardia en innovación tecnológica y expansión de mercado, continuamente capacitamos a nuestro personal el cual ha sido previamente seleccionado por sus valores y virtudes en el área comercial desarrollando en ellos un alto porcentaje de compromiso y entrega. <br>Pensando en la población hemos desarrollado un canal de distribución a través de nuestro e- commerce el cual tendrá alcance a todos los rincones del mundo los cuales podrán disfrutar de nuestra calidad y servicio y ofertas del momento. <br>Óptica ángeles siempre pensando en la humanidad cuenta con un servicio gratuito para personas discapacitadas de bajos recursos permitiendo que dichas personas puedan tener una mejor calidad de vida. <br>En este paquete se entrega consulta, montura y lentes totalmente gratis.
		</div>

		<div class="subtitulo-quienes">
			<i class="icon-dot-single"></i> VISIÓN
		</div>
		<div class="texto-quienes">
			La expansión internacional es nuestro target mediante alianzas estratégicas con grupos líderes en el área de la salud, lideres humanitarios y líderes gubernamentales para así poder llegar a todos los rincones del mundo llevando la mejor calidad al mejor precio. <br>Todos los seres humanos tienen derecho a una mejor calidad de vida y óptica ángeles hace posible este reto.
		</div>

		<div class="subtitulo-quienes">
			<i class="icon-dot-single"></i> HISTORIA
		</div>
		<div class="texto-quienes">
			Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia Historia.
		</div>

		<div class="subtitulo-quienes">
			<i class="icon-dot-single"></i> PROPUESTA
		</div>
		<div class="texto-quienes">
			El cuidado de sus ojos es nuestra prioridad. Óptica Ángeles es una compañía líder en el mercado óptico en donde todos nuestros esfuerzos están enfocados en brindar la mejor calidad del mundo al menor precio del mercado, por lo tanto, nos caracterizamos por ofrecer garantía en todos nuestros productos y servicios siendo nuestro mayo orgullo la satisfacción del paciente. <br> Mantenemos una constante evolución en todos nuestros procesos siendo la calidad nuestro pilar principal por lo tanto contamos con amplios consultorios avalados por las entidades competentes asegurando tecnología de punta en todos nuestros dispositivos de salud y de la misma manera nuestros especialistas son altamente calificados bajo estándares de calidad y servicio.<br> Ofrecemos todo el año cupones de descuento y tarjetas regalo; contamos con un amplio surtido de monturas y gafas de sol, así como una gama completa en lentes de contacto para cubrir las necesidades del mercado en diseños,	tamaños, colores y moda itiendo al consumidor final comunicar su estilo personal llegando así a todos los diferentes modelos, gustos y culturas.
		</div>
	</div>
</div>
-->
<script type="text/javascript">
/*:::::::::TITULO DE LA PAGINA:::::::::*/
document.getElementsByTagName("title")[0].innerHTML = "Optica Angeles | Pago";
</script>
@endsection