<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="rgba(44,75,108,1)" />
	<title>Optica Angeles</title>
	<link rel="shortcut icon" href="{{ asset('public/imagenes/icono.png') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('public/css/font-awesome/css/font-awesome.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/css/fontawesome/css/font-awesome.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/css/fonts/style.css') }}">
	<!--slider slick-->
	<link href="{{ asset('public/css/bootstrap.css') }}" rel="stylesheet">   
	<link rel="stylesheet" type="text/css" href="{{ asset('public/css/slick.css') }}">
	<!--fin slick-->
	<link rel="stylesheet" type="text/css" href="{{ asset('public/css/estilosoptica.css') }}">
	<script src="{{ asset('public/js/jquery.js') }}"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="{{ asset('public/js/bootstrap.js') }}"></script> 
	<script src="https://cdn.jsdelivr.net/gsap/1.19.1/TweenMax.min.js"></script>
</head>
<body>
	<!--:::::::::::::::::::::::::::-->
	<div class="loader">
		<div id="content-cerrar-loader">
			<div id="cerrar-loader" onclick="cerrarLoader();">
				<i class="icon-cross"></i>
			</div>
		</div>
		<div id="content-imagen-loader">
			<img src="{{ asset('public/imagenes/logo-principal.png') }}" style="width: 100%;float: left;">
			<div id="content-cargando-loader">
				<div class="cargando-loader"></div>
				<p id="texto-cargando-loader">Cargando App Óptica Ángeles...</p>
			</div>
		</div>
		<div id="content-aplicaciones">
			<div id="titulo-aplicaciones"><i class="icon-tablet" style="font-size:4vh!important;color:rgba(230,230,230,1)!important;"></i><br>Servicios y Aplicaciones en construcción</div>
		</div>
		<div id="aplicaciones">
				<div class="aplicaciones-loader">
					
					<div class="content-aplicacion">
						<div class="aplicacion">
							<i class="icon-credit"></i>
							<p id="texto-aplicacion">Cotización Online</p>
						</div>
					</div>
					<div class="content-aplicacion">
						<div class="aplicacion">
							<i class="icon-calendar"></i>
							<p id="texto-aplicacion">Agendar<br>Citas</p>
						</div>
					</div>
					<div class="content-aplicacion">
						<div class="aplicacion">
							<i class="icon-chat"></i>
							<p id="texto-aplicacion">Chat<br>Online</p>
						</div>
					</div>
					<div class="content-aplicacion">
						<div class="aplicacion">
							<i class="icon-magnifying-glass"></i>
							<p id="texto-aplicacion">Consulta de Compras</p>
						</div>
					</div>
					<div class="content-aplicacion">
						<div class="aplicacion">
							<i class="icon-shopping-cart"></i>
							<p id="texto-aplicacion">Compras<br>en línea</p>
						</div>
					</div>
					

				</div>
		</div>
		<div id="aplicaciones">
				<div class="aplicaciones-loader">
					<div class="content-aplicacion">
						<div class="aplicacion">
							<i class="icon-documents"></i>
							<p id="texto-aplicacion">Planes<br>Empresariales</p>
						</div>
					</div>
					<div class="content-aplicacion">
						<div class="aplicacion">
							<i class="icon-documents"></i>
							<p id="texto-aplicacion">Tamizaje<br>Escolar</p>
						</div>
					</div>
					<div class="content-aplicacion">
						<div class="aplicacion">
							<i class="icon-documents"></i>
							<p id="texto-aplicacion">Examen<br>Gratis</p>
						</div>
					</div>
					<div class="content-aplicacion">
						<div class="aplicacion">
							<i class="icon-documents"></i>
							<p id="texto-aplicacion">Somos<br>IPS</p>
						</div>
					</div>
					<div class="content-aplicacion">
						<div class="aplicacion">
							<i class="icon-documents"></i>
							<p id="texto-aplicacion">Mejoramos<br>Cotización</p>
						</div>
					</div>
				</div>
		</div>
		
	</div>










	<style type="text/css">
		.loader{
		    position: fixed;
		    width: 100%;
		    height: 100vh;
		    z-index: 9999;
		    background-color: rgba(44,75,108,1);
		}
		#content-imagen-loader{
			//border: 1px solid green;
			width: 100%;
			max-width: 1000px;
			margin-right: auto;
			margin-left: auto;
			right:0;
			left:0;
			padding:4vh 20% 4vh 20%;
			overflow: hidden;
		}
		#content-cargando-loader{
			width: 100%;
			//border:1px solid red;
			float: left;
		}

		.cargando-loader {
		  border: 1vh solid rgba(220,220,240,1); /* Light grey */
		  border-top: 1vh solid rgba(0,165,230,1);
		  border-radius: 50%;
		  width: 10vh;
		  height: 10vh;
		  animation: spin 1.8s linear infinite;
		  margin-right: auto;
		  margin-left: auto;
		  right:0;
	      left:0;
		}
		#texto-cargando-loader{
			text-align: center;
			color: rgba(240,240,240,1); 
			font-family: Raleway;
			font-weight: bold;
			font-size: 2.5vh;
			margin-top:1vh;
		}
		#content-aplicaciones{
			width: 100%;
			max-width: 75vh;
			margin-right: auto;
			margin-left: auto;
			right:0;
			left:0;
  			//border:1px solid red;
  			overflow: hidden;
  			padding:2px 4px 0px 4px;
		}

		#titulo-aplicaciones{
			text-align: center;
			color: rgba(220,220,240,1);
			font-size: 2.5vh;
			font-family: Raleway;
			//font-weight: bold;
			width: 100%;
			float: left;
			padding:1.5vh;
			background-color: rgba(20,20,20,1);
		}

		#aplicaciones{
			display: flex;
  			justify-content: center;
  			//float: left
		}
		
		.aplicaciones-loader{
			width: 100%;
			max-width: 75vh;
			height: 15vh;
			//border:1px solid white;
			display: inline-table;
			padding:2px 2px 0px 2px;
		}

		.content-aplicacion{
			width: 20%;
			height: 100%;
			float: left;
			//border: 1px solid blue;
			padding:2px 2px 0px 2px;
		}

		.aplicacion{
			width: 100%;
			height: 100%;
			float: left;
			background-color: rgba(20,20,20,1);
			text-align: center;
			padding-top:2.7vh;
			transition: 0.5s;
			cursor:pointer;
		}.aplicacion:hover{
			background-color: rgba(10,10,10,1);
			color:white;
		}

		.aplicacion i{
			font-size:4vh;
			color:rgba(230,230,230,1);
			margin-bottom: 1vh;
		}

		#texto-aplicacion{
			font-size:1.8vh;
			//font-weight: bold;
			//font-family: Raleway!important;
			line-height: 2.1vh;
			color:rgba(200,200,200,1);
		}

		#content-cerrar-loader{
			width:100%;
			float: left;
		}

		#cerrar-loader{
			width: 5vh;
			height: 5vh;
			float: right;
			text-align: right;
			font-size: 3vh;
			color:white;
			background-color: rgba(230,80,80,1);
			padding-top: 0.6vh;
			padding-right: 1vh;
			cursor: pointer;
		}
	</style>












	<script type="text/javascript">
		$(window).load(function() {
		    $(".loader").fadeOut("slow");
		});

		function cerrarLoader(){
			$(".loader").fadeOut("slow");
		}
	</script>	
  <!--:::::::::::::::::::::::::::::::::::::HEADER::::::::::::::::::::::::::::::::::::::::::-->
  <div id="header">

  	<div id="content-logo-principal">
  		<a href="/">
  			<div id="content-centrar-logo">
	  			<img src="{{ asset('public/imagenes/logo-principal.png') }}" style="width: 100%;">
	  		</div>
  		</a>
  	</div>

  	<div id="content-menu">
  		<!--<div class="menu">
  			<p class="texto-menu"><i class="fa fa-user-plus"></i><br> REGÍSTRO</p>
  		</div>-->
  		<a href="">
	  		<div class="menu" style="margin-right: 5%;padding-top:14px!important;">
	  			<p class="texto-menu"><i class="icon-login"></i><br>INICIAR SESIÓN</p>
	  		</div>
	  	</a>
  		<div class="menu" onclick="abrirModal('cotizar')">
  			<p class="texto-menu"><i class="fa fa-dollar"></i><br>COTIZAR ONLINE</p>
  		</div>
  		<a href="">
	  		<div class="menu">
	  			<p class="texto-menu"><i class="fa fa-shopping-cart"></i><br>ECOMMERCE</p>
	  		</div>
	  	</a>
  		<a href="/#seccion-membresia">
	  		<div class="menu" style="padding-top:14px!important;">
	  			<p class="texto-menu"><i class="icon-star"></i><br>MEMBRESÍA</p>
	  		</div>
  		</a>
  		
  		<div class="menu" id="menu-inicio-hover">
  			<a href="/" style="color:inherit;"><p class="texto-menu" id="menu-inicio-hover-boton"><i class="fa fa-home"></i><br>INICIO</p></a>
  			<div id="content-menu-inicio-hover">
  				<a href="/#seccion-productos"><div class="inicio-hover"><i class="icon-images"></i> GALERIA DE PRODUCTOS</div></a>
  				<!--<a href="index.html#seccion-servicios"><div class="inicio-hover"><i class="icon-creative-commons-attribution"></i> SERVICIOS</div></a>-->
  				<a href="/#seccion-contacto"><div class="inicio-hover"><i class="icon-phone"></i> CONTACTO</div></a>
  				<a href="/#seccion-aliados"><div class="inicio-hover"><i class="icon-address"></i> ALIADOS</div></a>
  				<a href="/quienes"><div class="inicio-hover"><i class="icon-help"></i> QUIENES SOMOS</div></a>
  				<!--<a href="promociones.html"><div class="inicio-hover"><i class="icon-price-tag"></i> PROMOCIONES Y DESCUENTOS</div></a>-->
  			</div>
  		</div>
  		

  	</div>
  </div>

  <div id="header-servicios">

  	<div id="idioma-hover">
  		<div id="idioma-hover-boton">ES <i class="fa fa-chevron-down"></i></div>
  		<div id="content-idiomas">
  			<div class="idioma">EN</div>
  		</div>
  	</div>
  	<div id="content-fecha">
  		<p id="dia"></p>
        <p id="fecha"><x id="day"></x>/<x id="month"></x>/<x id="year"></x></p>
        <p id="HoraActual"></p>
  	</div>

  	<div class="menu-servicio" id="servicio-hover">
  		<div id="servicio-hover-boton">SERVICIOS <i class="icon-chevron-down" style="font-size: 14px;"></i></div>
  		<div id="content-servicio-hover">
  			<div class="servicios-hover" onclick="abrirModal('planes');">+ PLANES EMPRESARIALES</div>
  			<div class="servicios-hover" onclick="abrirModal('tamizaje');">+ TAMIZAJE ESCOLAR</div>
  			<div class="servicios-hover" onclick="abrirModal('examen');">+ EXAMEN VISUAL GRATIS</div>
  			<div class="servicios-hover" onclick="abrirModal('cotizacion');">+ MEJORAMOS COTIZACIÓN</div>
  			<div class="servicios-hover" onclick="abrirModal('ips');">+ SOMOS IPS</div>
  		</div>
  	</div>

  	
  	<div class="menu-servicio" id="laboratorios-hover">
  		<div id="laboratorios-hover-boton">LABORATORIOS <i class="icon-chevron-down" style="font-size: 14px;"></i></div>
  		<div id="content-laboratorios-hover">
  			<div class="laboratorio-hover">
  				<div class="nombre-marca">MEGALENS</div>
	  			<div class="content-imagen-marca"><img src="{{ asset('public/imagenes/slick/megalens1.png') }}" style="width:100%;"> </div>
  			</div>
  			<div class="laboratorio-hover">
  				<div class="nombre-marca">PRATS</div>
	  			<div class="content-imagen-marca"><img src="{{ asset('public/imagenes/slick/prats1.png') }}" style="width:100%;"> </div>
  			</div>
  			<div class="laboratorio-hover">
  				<div class="nombre-marca">SERVIOPTICA</div>
	  			<div class="content-imagen-marca"><img src="{{ asset('public/imagenes/slick/servioptica1.png') }}" style="width:100%;"> </div>
  			</div>
  			<div class="laboratorio-hover">
  				<div class="nombre-marca">ZEISS</div>
	  			<div class="content-imagen-marca"><img src="{{ asset('public/imagenes/slick/zeiss2.png') }}" style="width:80%;margin-left: 10%;"> </div>
  			</div>
  		</div>  		
  	</div>

  	<div class="menu-servicio">
  		AGENDAR CITA
  	</div>

  	<div class="menu-servicio" onclick="mostrarConsultaCarrito('consulta');">
  		CONSULTAR COMPRA
  	</div>

  	<div class="menu-servicio" onclick="mostrarConsultaCarrito('carrito');">
  		MI CARRITO <i class="fa fa-shopping-cart" style="font-size: 14px;"> </i>
  	</div>
  </div>

  
    <script>
    	function mostrarConsultaCarrito(item){
    		if(item=="consulta"){
    			$("#carrito").fadeOut("slow");
    			$("#seccion-consulta").fadeIn();
    		}else if(item="carrito"){
    			$("#seccion-consulta").fadeOut("slow");
    			$("#carrito").fadeIn();
    		}
    	}







        function showTime(){
            myDate = new Date();
            hours = myDate.getHours();
            minutes = myDate.getMinutes();
            seconds = myDate.getSeconds();
            if (hours < 10) hours = 0 + hours;
            if (minutes < 10) minutes = "0" + minutes;
            if (seconds < 10) seconds = "0" + seconds;
            $("#HoraActual").text(hours+ ":" +minutes+ ":" +seconds);
            setTimeout("showTime()", 1000);
            
            var d = new Date();
            var dia = d.getDay();
            if(dia==0){
                dia="Domingo";
            }else if(dia==1){
                dia="Lunes";
            }else if(dia==2){
                dia="Martes";
            }else if(dia==3){
                dia="Miercoles";
            }else if(dia==4){
                dia="Jueves";
            }else if(dia==5){
                dia="Viernes";
            }else if(dia==6){
                dia="Sabado";
            }
            document.getElementById("dia").innerHTML=dia;
            
            var day = d.getDate();
            document.getElementById("day").innerHTML=day;
            
            var month = d.getMonth();
            document.getElementById("month").innerHTML=month+1;
            
            var year = d.getFullYear();
            document.getElementById("year").innerHTML=year;
        }
        setTimeout("showTime()", 1000);
        
        function mostrarMenu(){
            /*alert("hola");*/
            $("#content-barras-menu").fadeOut();
            document.getElementById("content-barras-menu").style.display="none";
            $("#content-cerrar-menu").fadeIn();
            $("#menu-completo").fadeIn();
        }
        
        function quitarMenu(){
            /*alert("hola");*/
            $("#content-cerrar-menu").fadeOut();
            document.getElementById("content-cerrar-menu").style.display="none";
            $("#content-barras-menu").fadeIn();
            $("#menu-completo").fadeOut();
        }
    </script>











		<div id="seccion-consulta">
			<div id="header-consulta">
				<div id="texto-consulta">Consulta tu compra aquí</div>
			</div>
			<div id="content-consulta">
				<div id="consulta">
					<div class="label-consulta">Ingresa el número de factura de tu compra</div>
					<input type="text" name="consulta" class="campo-consulta" id="campo-factura" placeholder="N° de factura." minlength="4" maxlength="20">
					<div id="boton-consulta" onclick="consultarFactura();"><i class="icon-magnifying-glass"></i> Consultar</div>
					<div id="content-resultado-consulta">
						<div id="cerrar-consulta"><i class="icon-ccw" onclick="cerrarConsulta();"></i></div>
						<div id="titulo-resultado-consulta">Resultado de la consulta</div>
						<div id="estatus-resultado"></div>
						<div id="ubicacion-resultado"></div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		function consultarFactura(){

			document.getElementById('content-resultado-consulta').style.display="none";
			document.getElementById('estatus-resultado').style.display="none";
			document.getElementById('ubicacion-resultado').style.display="none";
			document.getElementById('estatus-resultado').innerHTML="";
			document.getElementById('ubicacion-resultado').innerHTML="";


			var factura = document.getElementById('campo-factura').value;
			if(factura=="1234"){
				document.getElementById('content-resultado-consulta').style.display="inline";
				document.getElementById('estatus-resultado').style.display="inline";
				document.getElementById('estatus-resultado').innerHTML="El Producto con factura "+factura+" se encuentra en nuestra sucursal <a href='#angeles'> angeles 1</a>.";
			}else if(factura=="12345"){
				document.getElementById('content-resultado-consulta').style.display="inline";
				document.getElementById('estatus-resultado').style.display="inline";
				document.getElementById('estatus-resultado').innerHTML="El Producto con factura "+factura+" ha sido enviado.";
				document.getElementById('ubicacion-resultado').style.display="inline";
				document.getElementById('ubicacion-resultado').innerHTML="El producto con factura "+factura+" se envió por ServiEntrega con el numero de guia 00001.";
			}else{
				document.getElementById('content-resultado-consulta').style.display="inline";
				document.getElementById('estatus-resultado').style.display="inline";
				document.getElementById('estatus-resultado').innerHTML="El Producto con factura "+factura+" no existe en nuestros regístros.";
			}
		}

		function cerrarConsulta(){
			document.getElementById('content-resultado-consulta').style.display="none";
			document.getElementById('estatus-resultado').style.display="none";
			document.getElementById('ubicacion-resultado').style.display="none";
			document.getElementById('estatus-resultado').innerHTML="";
			document.getElementById('ubicacion-resultado').innerHTML="";
			document.getElementById("campo-factura").value="";
			document.getElementById("campo-factura").focus();
		}
	</script>


	<div id="carrito">
		<div id="cerrar-carrito" onclick="mostrarCarrito();">
			<i class="icon-cross"></i>
		</div>
		<div id="content-carrito">
			<div id="content-titulo-carrito">
				<div id="mi-carrito">
					Mi carrito de Compras
				</div>

				<div id="numero-productos">
					0
				</div>
				<div id="content-icono-carrito">
					<i class="fa fa-shopping-cart"></i>
				</div>
			</div>
			<div id="content-secciones-carrito">
				<div id="borde-carrito">
					<div class="secciones-carrito" id="ver-carrito">
						ARTICULOS EN EL CARRITO <i class="icon-shopping-bag"></i>
					</div>
					<div class="secciones-carrito" id="ver-tienda">
						TIENDA VIRTUAL <i class="icon-shop"></i>
					</div>
					<div class="secciones-carrito" id="ver-promociones">
						PROMOCIONES Y DESCUENTOS <i class="icon-price-tag"></i>
					</div>
					<div class="secciones-carrito" id="carrito-cotizar">
						COTIZACIÓN ONLINE <i class="icon-documents"></i>
					</div>
					<div class="secciones-carrito" id="pagar-articulos">
						PAGAR ARTICULOS <i class="icon-credit-card"></i>
					</div>
				</div>
			</div>
		</div>
	</div>





















  <!--<div id="seccion-principal">
  	
	<div id="seccion-consulta">
		<div id="header-consulta">
			<div id="texto-consulta">¿QUIERES SABER SI TU COMPRA YA LLEGÓ?</div>
		</div>
		<div id="content-consulta">
			<div id="consulta">
				<div class="label-consulta">Fácil!!! Ingresa el número de factura</div>
				<input type="text" name="consulta" class="campo-consulta" id="campo-factura" placeholder="N° de factura." minlength="4" maxlength="20">
				<div id="boton-consulta" onclick="consultarFactura();">Consultar <i class="icon-magnifying-glass"></i></div>
				<div id="content-resultado-consulta">
					<div id="cerrar-consulta"><i class="icon-ccw" onclick="cerrarConsulta();"></i></div>
					<div id="titulo-resultado-consulta">Resultado de la consulta</div>
					<div id="estatus-resultado"></div>
					<div id="ubicacion-resultado"></div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function consultarFactura(){

			document.getElementById('content-resultado-consulta').style.display="none";
			document.getElementById('estatus-resultado').style.display="none";
			document.getElementById('ubicacion-resultado').style.display="none";
			document.getElementById('estatus-resultado').innerHTML="";
			document.getElementById('ubicacion-resultado').innerHTML="";


			var factura = document.getElementById('campo-factura').value;
			if(factura=="1234"){
				document.getElementById('content-resultado-consulta').style.display="inline";
				document.getElementById('estatus-resultado').style.display="inline";
				document.getElementById('estatus-resultado').innerHTML="El Producto con factura "+factura+" se encuentra en nuestra sucursal <a href='#angeles'> angeles 1</a>.";
			}else if(factura=="12345"){
				document.getElementById('content-resultado-consulta').style.display="inline";
				document.getElementById('estatus-resultado').style.display="inline";
				document.getElementById('estatus-resultado').innerHTML="El Producto con factura "+factura+" ha sido enviado.";
				document.getElementById('ubicacion-resultado').style.display="inline";
				document.getElementById('ubicacion-resultado').innerHTML="El producto con factura "+factura+" se envió por efecty con el numero de guia 00001.";
			}else{
				document.getElementById('content-resultado-consulta').style.display="inline";
				document.getElementById('estatus-resultado').style.display="inline";
				document.getElementById('estatus-resultado').innerHTML="El Producto con factura "+factura+" no existe en nuestros regístros.";
			}
		}

		function cerrarConsulta(){
			document.getElementById('content-resultado-consulta').style.display="none";
			document.getElementById('estatus-resultado').style.display="none";
			document.getElementById('ubicacion-resultado').style.display="none";
			document.getElementById('estatus-resultado').innerHTML="";
			document.getElementById('ubicacion-resultado').innerHTML="";
			document.getElementById("campo-factura").value="";
			document.getElementById("campo-factura").focus();
		}
	</script>




	<div id="carrito">
		<div id="cerrar-carrito" onclick="mostrarCarrito();">
			<i class="icon-cross"></i>
		</div>
		<div id="content-carrito">
			<div id="content-titulo-carrito">
				<div id="mi-carrito">
					Mi carrito de Compras
				</div>

				<div id="numero-productos">
					0
				</div>
				<div id="content-icono-carrito">
					<i class="fa fa-shopping-cart"></i>
				</div>
			</div>
			<div class="secciones-carrito" id="ver-carrito">
				Ver Artículos en el carrito <i class="icon-shopping-bag"></i>
			</div>
			<div class="secciones-carrito" id="ver-tienda">
				Comprar en tienda Virtual <i class="icon-shop"></i>
			</div>
			<div class="secciones-carrito" id="ver-promociones">
				Promociones y descuentos <i class="icon-price-tag"></i>
			</div>
			<div class="secciones-carrito" id="carrito-cotizar">
				Mi Cotización de gafas online <i class="icon-documents"></i>
			</div>
			<div class="secciones-carrito" id="pagar-articulos">
				Pagar mis Artículos <i class="icon-credit-card"></i>
			</div>
		</div>
	</div>





	<div id="qr">
		<div id="content-qr">
			<img src="imagenes/qr.png" width="100%">
		</div>
		<div class="textoQR">Escanea el código QR y obtén una excelente promoción de descuento.</div>
	</div>
  </div>-->





  <div id="seccion-chat">
  	<div id="header-chat" onclick="mostrarChat();">
  		<div id="estatus-chat"></div>
  		<div id="titulo-chat">Asesor Óptica Ángeles</div>
  		<div id="icono-cerrar-chat" onclick="cerrarChat();"><i class="icon-cross"></i></div>
  		<div id="icono-minimizar-chat"><i class="icon-minus"></i></div>
  	</div>
  	<div id="content-chat">
  		<div id="identificar-chat">
  			<div id="titulo-identificar">
  				Bienvenido a nuestro sistema de mensajeía directa, escribe tu nombre y correo electrónico para identificarte y continuar. Respondemos tus dudas.
  			</div>
  			<div class="label-identificar" id="label-nombre">Nombre y Apellido</div>
  			<input class="campo-identificar" type="text" spellcheck="false" placeholder="Escribe tu nombre aquí." maxlength="20" minlength="3" id="nombre-chat">

  			<div class="label-identificar" id="label-correo">Correo Electrónico</div>
  			<input class="campo-identificar" type="text" spellcheck="false" placeholder="Escribe tu correo aquí." maxlength="40" minlength="3" id="correo-chat">
  			<div id="texto-aceptar">Al continuar acepto los términos y condiciones</div>
  			<div class="boton-identificar" id="boton-identificar">Aceptar y Continuar</div>
  		</div>
  		<div id="espera-chat">
  			<div id="content-circulo-espera-chat">
  				<div class="circulo-espera-chat"></div>
  			</div>
  			<div id="procesando-chat">Procesando Información<br>Espere...</div>
  		</div>
  		<div id="chat-completo">
  			<div id="chat-user">
	  			<div class="caja-texto">
		  			<div class="chat-emisor">
		  				Hola
		  			</div>
	  			</div>
	  			<div class="caja-texto">
		  			<div class="chat-emisor">
		  				Buenas Tardes quiero saber si mis gafas ya llegaron.
		  			</div>
	  			</div>
	  			<div class="caja-texto">
		  			<div class="chat-receptor">
		  				Por favor, indiqueme el numero de factura
		  			</div>
	  			</div>
	  			<div class="caja-texto">
		  			<div class="chat-emisor">
		  				el numero de factura es: 123456789
		  			</div>
	  			</div>
	  			<div class="caja-texto">
		  			<div class="chat-receptor">
		  				Sus gafas se encuentran en nuestra sede optica angeles.
		  			</div>
	  			</div>
	  			<div id="ultima-hora"> 05:16 - 29/03/2020</div>
  			</div>
  			<div id="content-chat-texto">
  				<textarea id="campo-enviar" placeholder="Escribe un mensaje"></textarea>
  				<div id="content-icono-enviar">
  					<i class="icon-paper-plane"></i>
  				</div>
  			</div>
  		</div>
  	</div>
  </div>
  <script type="text/javascript">

  	var exprN= /^[a-zA-Z0-9À-ÿ\.\#-\s]+$/;
  	var exprC= /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
  	
  	$("#boton-identificar").click(function(){

  		document.getElementById('label-nombre').style.color="rgba(0,70,70,1)";
	    document.getElementById('label-nombre').innerHTML="Nombre y Apellido";
	    document.getElementById('label-correo').style.color="rgba(0,70,70,1)";
	    document.getElementById('label-correo').innerHTML="Correo Electrónico";

  		var valid = true;
  		var nombreChat = $("#nombre-chat").val();
  		var correoChat = $("#correo-chat").val();

  		if(nombreChat=="" || !exprN.test(nombreChat) || nombreChat.length<3 || nombreChat.length>20){
	        document.getElementById('label-nombre').style.color="red";
	        document.getElementById('label-nombre').innerHTML="Escribe Nombre y Apellido válido";
	        valid=false;
	    }
  		
	    if(correoChat=="" || !exprN.test(correoChat) || correoChat.length<3 || correoChat.length>20){
	        document.getElementById('label-correo').style.color="red";
	        document.getElementById('label-correo').innerHTML="Escribe Correo Electrónico válido";
	        valid=false;
	    }



	    if(valid==true){
	    	document.getElementById('identificar-chat').style.display="none";
	    	/*::::ajax::::*/
	    	/*::::mientras espera::::*/
	    	document.getElementById('espera-chat').style.display="inline";

	    	mostrarHistorialChat();
	    }


  		return valid;

    });

  	function mostrarHistorialChat(){
  		setTimeout(function(){
  			document.getElementById('espera-chat').style.display="none";
  			$("#chat-completo").fadeIn();
  			var objDiv = document.getElementById("chat-user");
    		objDiv.scrollTop = objDiv.scrollHeight;
    		document.getElementById("campo-enviar").focus();
  		}, 1000);
  	}

  	chat = "minimizado";
  	function mostrarChat(){
  		if(chat=="maximizado"){
  			document.getElementById('content-chat').style.display="none";
  			chat="minimizado";
  		}else if(chat=="minimizado"){
  			document.getElementById('content-chat').style.display="inline";
  			chat="maximizado";
  		}
  	}

  	estatusChat = "abierto";
  	function cerrarChat(){
  		if(estatusChat=="abierto"){
  			document.getElementById('seccion-chat').style.display="none";
  			estatusChat="cerrado";
  		}else if(estatusChat=="cerrado"){
  			document.getElementById('seccion-chat').style.display="block";
  			document.getElementById('content-chat').style.display="inline";
  			chat="maximizado";
  			estatusChat="abierto";
  		}
  	}

  </script>




  <!--fin-seccion-principal-->





<div id="seccion-videos" style="background-color: rgba(150,150,140,1)!important;">
    
  	<div id="seccion-status">
  	    <div id="content-imagen-transaccion">
            <img src="{{ asset('public/imagenes/logo-principal.png') }}" style="height: 100%;">
        </div>
        <div id="content-fecha-transaccion">
            <b>factura: </b><p style="color:red;font-weight:bold;">{{$request->referenceCode}}</p>
        </div>
  	    <div id="header-status">RESULTADO DE TRANSACCIÓN:</div>
        <div class="content-elemento-resultado">
  	        <div class="titulo-resultado">Estado: </div>
  	        <div class="resultado">
  	            @if($request->transactionState=="4")
  	                <p style="color:green;font-weight:bold;">APROBADA</p>
  	            @elseif($request->transactionState=="6")
  	                <p style="color:red;font-weight:bold;">RECHAZADA</p>
  	            @elseif($request->transactionState=="104")
  	                <p style="color:red;font-weight:bold;">ERROR</p>
  	            @elseif($request->transactionState=="7")
  	                <p style="color:orange;font-weight:bold;">PENDIENTE</p>
  	            @else
  	                <p style="color:orange;font-weight:bold;">{{$request->message}}</p>
  	            @endif
  	        </div>
  	    </div>
        
  	    <div class="content-elemento-resultado">
  	        <div class="titulo-resultado">Referencia: </div><div class="resultado">{{$request->referenceCode}}</div>
  	    </div>
  	    
  	    <div class="content-elemento-resultado">
  	        <div class="titulo-resultado">Cliente: </div><div class="resultado">{{$request->extra1}}</div>
  	    </div>
  	    
  	    <div class="content-elemento-resultado">
  	        <div class="titulo-resultado">Telefono: </div><div class="resultado">{{$request->extra2}}</div>
  	    </div>
  	    
  	    <div class="content-elemento-resultado">
  	        <div class="titulo-resultado">Direccion: </div><div class="resultado">{{$request->extra3}}</div>
  	    </div>
  	    
  	    <div class="content-elemento-resultado">
  	        <div class="titulo-resultado">E-Mail: </div><div class="resultado">{{$request->buyerEmail}}</div>
  	    </div>
  	    
  	    <div class="content-elemento-resultado">
  	        <div class="titulo-resultado">Fecha de transacción: </div><div class="resultado">{{$request->processingDate}}</div>
  	    </div>
  	    
  	    <div class="content-elemento-resultado">
  	        <div class="resultado" style="float:right!important;font-weight:bold!important;"><i class="icon-credit"></i> {{$request->TX_VALUE}} {{$request->currency}}</div>
  	        <div class="titulo-resultado" style="float:right!important;text-align:right!important;padding-right:10px!important;">Monto: </div>
  	    </div>
  	    <div id="footer-resultado">Óptica Ángeles - Calle 4 #7-41 Zipaquirá, Cundinamarca, Colombia<br>Teléfono: 3185571463</div>
  	</div>
  	
</div>


<style>
    #seccion-status{
        width:calc(100% - 40px);
        margin-left:20px;
        //height:400px;
        overflow:hidden;
        max-width: 800px;
        margin-left:auto;
        margin-right:auto;
        left:0;
        right:0;
        border:1px solid rgba(44,75,108,0.2);
        margin-top:20px;
        background-color:white;
        box-shadow:0px 0px 10px rgba(0,0,0,0.5);
        padding-bottom:10px;
    }
    
    #content-imagen-transaccion{
        width:70%;
        height:70px;
        float:left;
        //border:1px solid red;
    }
    
    #content-fecha-transaccion{
        width:30%;
        height:70px;
        float:left;
        color: rgba(80,70,90,1);
        text-align:center;
        //border:1px solid red;
        padding-top:15px;
    }
    
    #header-status{
        width:100%;
        //background-color:rgba(44,75,108,1);
        padding:5px 0px 25px 0px;
        font-size:18px;
        color: rgba(80,70,90,1);
        text-align:center;
        font-weight:600;
        letter-spacing:-0.5px;
        float:left;
    }
    .content-elemento-resultado{
        width:calc(100% - 40px);
        margin-left:20px;
        float:left;
        border-top:1px solid rgba(44,75,108,0.1);
    }
    .titulo-resultado{
        float:left;
        width:170px;
        height:35px;
        padding-top:8px;
        //border:1px solid blue;
        font-weight:600;
        font-size:15px;
        color: rgba(80,70,90,1);
        letter-spacing:-0.5px;
    }
    .resultado{
        border-left:1px solid rgba(44,75,108,0.1);
        float:left;
        height:35px;
        padding-top:8px;
        padding-left:8px;
        font-weight:400;
        font-size:15px;
        color: rgba(80,70,90,1);
        letter-spacing:-0.5px;
    }
    #footer-resultado{
        border-top:1px solid rgba(44,75,108,0.1);
        width:100%;
        color: rgba(80,70,90,0.4);
        float:left;
        text-align:center;
        line-height:16px;
        letter-spacing:-0.4px;
        padding-top:12px;
    }
</style>





<div id="content-iconos-redes">
	
	<div id="mySidenav" class="sidenav">
	  <a href="https://api.whatsapp.com/send?phone=573054192323&text=Hola%2C%20estoy%20interesado%20en" target="blank" id="about">
	  	<div style="width:25px;float:left;margin-right: 10px;"><img src="{{ asset('public/imagenes/Wblanco.png') }}" style="width:100%;"></div> whatsapp
	  </a>
	  <a href="https://www.facebook.com/opticaangeleszipa" target="blank" id="blog"><i class="icon-facebook"> </i>&nbsp;facebook</a>
	  <a href="https://www.instagram.com/optica.angeles?igshid=c9axo180brnp" target="blank" id="projects"><i class="icon-instagram">&nbsp; </i>instagram</a>
	  <a id="contact" onclick="cerrarChat();"><i class="icon-chat"> </i>&nbsp;Chat</a>
	  <a id="car" onclick="mostrarCarrito();"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp; Carrito</a>
	</div>

</div>
<script type="text/javascript">
	carrito="absoluto";
	function mostrarCarrito(){
		if(carrito=="absoluto"){
			document.getElementById('carrito').style.position="fixed";
			document.getElementById('cerrar-carrito').style.display="inline";
			carrito="fijo";
		}else{
			document.getElementById('carrito').style.position="absolute";
			document.getElementById('cerrar-carrito').style.display="none";
			carrito="absoluto";
		}
	}
</script>


<style type="text/css">
	#content-iconos-redes{

		position: fixed;
		margin-top: 25vh;
		z-index: 9;
		right: 0;
		top: 0;
	}


		#mySidenav a {
		  position: absolute; /* Position them relative to the browser window */
		  right: -95px; /* Position them outside of the screen */
		  transition: 0.3s; /* Add transition on hover */
		  padding: 12px 12px 9px 12px; /* 15px padding */
		  width: 135px; /* Set a specific width */
		  text-decoration: none; /* Remove underline */
		  font-size: 16px; /* Increase font size */
		  color: white; /* White text color */
		  border-radius: 6px 0 0 6px; /* Rounded corners on the top right and bottom right side */
		}

		#mySidenav a:hover {
		  right: 0; /* On mouse-over, make the elements appear as they should */
		}

		/* The about link: 20px from the top with a green background */
		
		#about {
		  padding: 8px 15px 9px 8px!important;
		  top: 21px;
		  background-color: #4CAF50;
		}

		#blog {
		  top: 66px;
		  background-color: #2C4B6C; /* Blue */
		}

		#projects {
		  top: 112px;
		  background-color: #502C6C; /* Red */
		}

		#contact {
		  top: 158px;
		  background-color: #555;
		  cursor: pointer;
		}

		#car {
		  top: 204px;
		  background-color: rgba(0,200,200,1);
		  cursor: pointer;
		}
	</style>



	<div id="modal-completo">
		<div id="cerrar-modal-ayuda"  onclick="cerrarModal();"></div>
		<div id="content-cerrar-modal" onclick="cerrarModal();">
			<i class="icon-cross"></i>
		</div>
<!--:::::::::::::::::::::::::::::servicios:::::::::::::::::::::::::::::::.-->

<!--planes empresariales-->
			
		<div class="contenedor-servicios-modal">
				<div class="content-descipcion-servicios-modal" id="descripcion-modal">
					<div class="titulo-servicio-modal" id="titulo-servicio-modal"></div>
					<div class="content-servicio-modal">
						<div class="content-texto-servicio-modal">
							<div class="subtitulo-servicio-modal" id="subtitulo-servicio-modal">
								
							</div>
							<div class="texto-servicio-modal" id="texto-servicio-modal">
								
							</div>
						</div>
					</div>
				</div>
				
				<div class="content-imagen-servicio-modal" id="imagen-modal">
					<div class="fondo-color-color-modal">
						<div class="content-logo-modal">
							<img src="{{ asset('public/imagenes/logo-principal.png') }}" style="width:100%;">
						</div>
						<div class="titulo-imagen-servicio-modal" id="titulo-imagen-servicio-modal"></div>
					</div>
					<div class="content-acciones-modal">
						<div class="content-iconos-modal">
							<a href="" target="blank" id="whatsapp-modal">
								<div class="caja-iconos-modal">
									<div class="content-imagen-whatsapp-modal">
										<img src="{{ asset('public/imagenes/Wblanco.png') }}" style="width:4vh;">
									</div>
									<p>Solicitar vía WhatsApp</p>
								</div>
							</a>
							<a href="tel:0318510215">
								<div class="caja-iconos-modal">
									<i class="icon-phone"></i><br>
									<p>Llamar a un asesor</p>
								</div>
							</a>
						</div>
					</div>
				</div>


<!--:::::::::::::::::::::::::::::cotizacion:::::::::::::::::::::::::::::::.-->
				<div id="content-cotizacion-modal">
					<div id="titulo-cotizacion">
						<div id="texto-titulo-cotizacion">Cotizar Online</div>
					</div>
					<img src="{{ asset('public/imagenes/logo-principal.png') }}" style="height:8vh;margin-top: -8vh;float: left;">

					<div id="seccion-cotizacion-modal">
						<div id="todo-cotizacion">

							<div id="titulos-cotizacion">Información de usuario</div>
							<div id="content-usuario-cotizacion">
								<div class="elemento-usuario">
									<div class="label-usuario">Nombre o Razon Social</div>
									<input type="text" class="campo-usuario" placeholder="Nombre o razon social">
								</div>
								<div class="elemento-usuario">
									<div class="label-usuario">N° Documento</div>
									<input type="text" class="campo-usuario" placeholder="N° Documento">
								</div>
								<div class="elemento-usuario">
									<div class="label-usuario">Correo Electrónico</div>
									<input type="text" class="campo-usuario" placeholder="Correo Electrónico">
								</div>
								<div class="elemento-usuario">
									<div class="label-usuario">Teléfono</div>
									<input type="text" class="campo-usuario" placeholder="Teléfono">
								</div>

							</div>

							<div id="titulos-cotizacion">Fórmula</div>
							<div id="content-formula">
								<div class="formula-ojo">
									<div class="elemento-formula">
										<div class="nombre-ojo">Ojo Derecho</div>
									</div>
									<div class="elemento-formula">
										<select class="campo-formula">
											<option>Esfera derecho</option>
											<option>0.00</option>
										</select>
									</div>
									<div class="elemento-formula">
										<select class="campo-formula">
											<option>Cilindro derecho</option>
											<option>0.00</option>
										</select>
									</div>
									<div class="elemento-formula">
										<select class="campo-formula">
											<option>Eje derecho</option>
											<option>0.00</option>
										</select>
									</div>
									<div class="elemento-formula">
										<select class="campo-formula">
											<option>Adicion derecho</option>
											<option>0.00</option>
										</select>
									</div>
								</div>
								<div class="formula-ojo">
									<div class="elemento-formula">
										<div class="nombre-ojo">Ojo Izquierdo</div>
									</div>
									<div class="elemento-formula">
										<select class="campo-formula">
											<option>Esfera izquierdo</option>
											<option>0.00</option>
										</select>
									</div>
									<div class="elemento-formula">
										<select class="campo-formula">
											<option>Cilindro izquierdo</option>
											<option>0.00</option>
										</select>
									</div>
									<div class="elemento-formula">
										<select class="campo-formula">
											<option>Eje izquierdo</option>
											<option>0.00</option>
										</select>
									</div>
									<div class="elemento-formula">
										<select class="campo-formula">
											<option>Adición izquierdo</option>
											<option>0.00</option>
										</select>
									</div>
								</div>
							</div>

							<div id="titulos-cotizacion" style="width: 70%!important;">Cristal</div>
							<div id="titulos-cotizacion"  style="width: 30%!important;float: right!important;">Montura</div>

							<div id="content-cristal">
								<div class="elemento-cristal">
									<div class="label-cristal">Tipo de Cristal</div>
									<select class="campo-cristal">
											<option>Seleccione</option>
											<option>Monofocal Lectura</option>
									</select>
								</div>
								<div class="elemento-cristal">
									<div class="label-cristal">Material</div>
									<select class="campo-cristal">
											<option>Seleccione</option>
											<option>CR 39</option>
									</select>
								</div>
								<div class="elemento-cristal">
									<div class="label-cristal">Filtro</div>
									<select class="campo-cristal">
											<option>Seleccione</option>
											<option>Estándar</option>
									</select>
								</div>
								<div class="elemento-cristal">
									<div class="label-cristal">Fotocromático</div>
									<select class="campo-cristal">
											<option>Seleccione</option>
											<option>Transition</option>
									</select>
								</div>
							</div>

							
							<div id="seccion-imagen-cotizacion">
								<div id="content-imagen-cotizacion">
									<div id="mover-izquierda" onclick="cambiarMontura('izquierda');">
										<i class="icon-chevron-with-circle-left"></i>
									</div>
									<div id="content-imagen-montura">
										<img src="{{ asset('public/imagenes/4.jpg') }}" id="imagen-montura">
									</div>
									<div id="mover-derecha" onclick="cambiarMontura('derecha');">
										<i class="icon-chevron-with-circle-right"></i>
									</div>
								</div>
								<div id="precio-montura-modal">$<br>300.000</div>
							</div>

							
							<div id="content-dnp">
								<div class="elemento-dnp">
									<div class="label-dnp">DNP Derecho</div>
									<input type="text" class="campo-dnp" placeholder="ingrese DNP">
								</div>
								<div class="elemento-dnp">
									<div class="label-dnp">DNP Izquierdo</div>
									<input type="text" class="campo-dnp" placeholder="ingrese DNP">
								</div>
								<div class="elemento-dnp">
									<div class="label-dnp">Distancia Pupilar</div>
									<input type="text" class="campo-dnp" placeholder="ingrese DP">
								</div>

							</div>

							<div id="content-resultado-cotizacion">
								<div id="boton-cotizar" onclick="cotizar();">Cotizar</div>
								<div id="resultado-cotizacion">Total Cotización: $ 300.000</div>
							
								<div id="imprimir-cotizacion">
									Imprimir <i class="icon-print"></i>
								</div>

								<div id="agregar-cotizacion">
									Agregar al carrito <i class="icon-shopping-cart"></i> 
								</div>

							</div>

						</div>
					</div>

				</div>
				<script type="text/javascript">
					function cambiarMontura(direccion){
						if (direccion=="derecha") {
							document.getElementById('imagen-montura').src="imagenes/1.jpg";
							document.getElementById('precio-montura-modal').innerHTML="$<br>100.000";
							document.getElementById('resultado-cotizacion').innerHTML="Total Cotización: $ 100.000";
						}
						if (direccion=="izquierda") {
							document.getElementById('imagen-montura').src="imagenes/3.jpg";
							document.getElementById('precio-montura-modal').innerHTML="$<br>50.000";
							document.getElementById('resultado-cotizacion').innerHTML="Total Cotización: $ 50.000";
						}
					}

					function cotizar(){
						document.getElementById('boton-cotizar').style.display="none";
						document.getElementById('resultado-cotizacion').style.display="inline";
						document.getElementById('imprimir-cotizacion').style.display="inline";
						document.getElementById('agregar-cotizacion').style.display="inline";
					}
				</script>






		</div>






		
	</div>
  	
  	<script type="text/javascript">
  		function abrirModal(servicio){
  			//alert(servicio);
  			if (servicio=="planes") {
  				document.getElementById('descripcion-modal').style.display="inline";
  				document.getElementById('imagen-modal').style.display="inline";
  				document.getElementById('titulo-servicio-modal').innerHTML="Planes Empresariales";
  				document.getElementById('subtitulo-servicio-modal').innerHTML="Descripción del Servicio";
  				document.getElementById('texto-servicio-modal').innerHTML="Óptica Ángeles ofrece paquetes promocionales, descuentos y planes de pago a empresas que deseen realizar examenes de rutina a sus trabajadores. Dirígete a nuestras sedes o contactanos por chat, whatsApp, o llámanos en la parte posterior de este post.";
  				document.getElementById('titulo-imagen-servicio-modal').innerHTML="Planes<br>Empresariales";
  				document.getElementById('imagen-modal').style.backgroundImage="url(public/img/servicios/planes.jpg)";
  				document.getElementById('whatsapp-modal').href="https://api.whatsapp.com/send?phone=573054192323&text=Hola%2C%20estoy%20interesado%20en%20el%20servicio%20%20de%20PLANES%20EMPRESARIALES";
  				document.getElementById('content-cotizacion-modal').style.display="none";
  			}else if(servicio=="tamizaje"){
  				document.getElementById('descripcion-modal').style.display="inline";
  				document.getElementById('imagen-modal').style.display="inline";
  				document.getElementById('titulo-servicio-modal').innerHTML="Tamizaje Escolar";
  				document.getElementById('subtitulo-servicio-modal').innerHTML="Descripción del Servicio";
  				document.getElementById('texto-servicio-modal').innerHTML="Realizamos tamizaje escolar a niños que cursen preescolar y escolar para detectar enfermedades visuales que requieran atención oftalmológica";
  				document.getElementById('titulo-imagen-servicio-modal').innerHTML="Tamizaje<br>Escolar";
  				document.getElementById('imagen-modal').style.backgroundImage="url(public/img/servicios/tamizaje-escolar.jpeg)";
  				document.getElementById('whatsapp-modal').href="https://api.whatsapp.com/send?phone=573054192323&text=Hola%2C%20estoy%20interesado%20en%20el%20servicio%20%20de%20TAMIZAJE%20ESCOLAR";
  				document.getElementById('content-cotizacion-modal').style.display="none";
  			}else if(servicio=="examen"){
  				document.getElementById('descripcion-modal').style.display="inline";
  				document.getElementById('imagen-modal').style.display="inline";
  				document.getElementById('titulo-servicio-modal').innerHTML="Examen visual gratis";
  				document.getElementById('subtitulo-servicio-modal').innerHTML="Descripción del Servicio";
  				document.getElementById('texto-servicio-modal').innerHTML="Dirígete a uno de nuestros laboratorios y te atendera nuestro personal califcado para realizarte el examen de la vista totalmente gratis, recibe promociones, ofertas y regalos. No esperes más.";
  				document.getElementById('titulo-imagen-servicio-modal').innerHTML="Examen<br>visual gratis";
  				document.getElementById('imagen-modal').style.backgroundImage="url(public/img/servicios/examen-visual.jpg)";
  				document.getElementById('whatsapp-modal').href="https://api.whatsapp.com/send?phone=573054192323&text=Hola%2C%20estoy%20interesado%20en%20el%20servicio%20%20de%20EXAMEN%20VISUAL";
  				document.getElementById('content-cotizacion-modal').style.display="none";
  			}else if(servicio=="cotizacion"){
  				document.getElementById('descripcion-modal').style.display="inline";
  				document.getElementById('imagen-modal').style.display="inline";
  				document.getElementById('titulo-servicio-modal').innerHTML="Mejoramos cotizaciones";
  				document.getElementById('subtitulo-servicio-modal').innerHTML="Descripción del Servicio";
  				document.getElementById('texto-servicio-modal').innerHTML="Traenos la formula o cotización de otro establecimiento y te mejoramos el precio.";
  				document.getElementById('titulo-imagen-servicio-modal').innerHTML="Mejoramos<br>tu cotización";
  				document.getElementById('imagen-modal').style.backgroundImage="url(public/img/servicios/cotizacion.jpg)";
  				document.getElementById('whatsapp-modal').href="https://api.whatsapp.com/send?phone=573054192323&text=Hola%2C%20estoy%20interesado%20en%20el%20servicio%20%20de%20MEJORAR%20COTIZACION";
  				document.getElementById('content-cotizacion-modal').style.display="none";
  			}else if(servicio=="ips"){
  				document.getElementById('descripcion-modal').style.display="inline";
  				document.getElementById('imagen-modal').style.display="inline";
  				document.getElementById('titulo-servicio-modal').innerHTML="Ahora somos IPS";
  				document.getElementById('subtitulo-servicio-modal').innerHTML="Descripción del Servicio";
  				document.getElementById('texto-servicio-modal').innerHTML="Solicita información acerca de nuestro servicio de atención IPS.";
  				document.getElementById('titulo-imagen-servicio-modal').innerHTML="Ahora<br>Somos IPS";
  				document.getElementById('imagen-modal').style.backgroundImage="url(public/img/servicios/planes.jpg)";
  				document.getElementById('whatsapp-modal').href="https://api.whatsapp.com/send?phone=573054192323&text=Hola%2C%20estoy%20interesado%20en%20el%20servicio%20%20de%20IPS";
  				document.getElementById('content-cotizacion-modal').style.display="none";
  			}


  			if (servicio=="cotizar") {
  				document.getElementById('descripcion-modal').style.display="none";
  				document.getElementById('imagen-modal').style.display="none";
  				document.getElementById('content-cotizacion-modal').style.display="inline";
  			}



  			$("#modal-completo").fadeIn();
  		}

  		function cerrarModal(){
  			$("#modal-completo").fadeOut();
  		}
  	</script>



<!--:::::::::::::::::::::::::::::::::::::::::::::::::::MODAL CAMARA::::::::::::::::::::::::::::::::::::::::::::::::::-->
<!--
<div id="modal-camara">
	<div id="content-lente-transparente">
			<img src="imagenes/lentes/lente1.png" style="width: 100%;">
	</div>
	<div id="content-camara">
		
		<div id="seccion-camara">
		  		
			<div class="video-wrap">
			    <video id="video" autoplay></video>
			    <div class="controller">
				    <div id="snap"><i class="icon-camera"></i></div>
				</div>
			</div>

			

			<canvas id="canvas" width="300" height="250"></canvas>

			
		</div>
		

	</div>

</div>


<style type="text/css">
	#content-lente-transparente{
		position: absolute;
		width: 100%;
		max-width: 300px;
		height: 250px;
		//background-color: red;
		z-index: 14!important;
		margin-left: auto;
		margin-right: auto;
		left: 0;
		right:0;
		padding:80px;
	}
	#seccion-camara{
		//margin-top:-150px;
		width:300px;
		height:250px;
		float: left;
		background-color: rgba(230,230,230,1);
		//border:1px solid red;
		z-index: 12!important;
	}
	#snap{
		width: 100%;
		height: 100%;
		float: left;
		color: white;
		background-color: rgba(0,200,200,0.7);
		text-align: center;
		padding-top:5px;
		font-size: 30px;
	}
	.video-wrap{
		//float: left;
	}
	.controller{
		width: 100%;
		height: 50px;
		float: left;
		//border:1px solid blue;
		cursor: pointer;
		margin-top:-5px;
	}
	#canvas{
		//margin-top:50px;
		float: left;
		//border:1px solid red;
	}

	



</style>

<script type="text/javascript">
	'use strict';

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const snap = document.getElementById("snap");
const errorMsgElement = document.querySelector('span#errorMsg');

const constraints = {
  audio: false,
  video: {
    width: 300, height: 250
  }
};

// Access webcam
async function init() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia(constraints);
    handleSuccess(stream);
  } catch (e) {
    errorMsgElement.innerHTML = `navigator.getUserMedia error:${e.toString()}`;
  }
}

// Success
function handleSuccess(stream) {
  window.stream = stream;
  video.srcObject = stream;
}

// Load init
//init();

// Draw image
var context = canvas.getContext('2d');
snap.addEventListener("click", function() {
        context.drawImage(video, 0, 0, 300, 250);
});










function mostrarCamara(){
	$("#modal-camara").fadeIn();
	init();
}
</script>



-->





















<div id="footer">
	<div id="oracion">
		<div id="titulo-oracion">
			ORACIÓN DE FÉ
		</div>
		<div id="texto-oracion">
			<x style="font-weight: 600;color:white!important;">Señor Jesús. </x> Reconozcoque soy pecador, te pido me perdones y que entres en mi corazón, te acepto como mi señor y sabvador.<br>
			Lléname con tu espíritu santo, gracias por la vida eterna que hoy me das. En el numbre de Jesús amén.
		</div>
	</div>
	<div id="content-appweb">
		<img src="{{ asset('public/imagenes/colombiantrading1.png') }}" style="width: 100%;">
	</div>
	<div id="texto-appweb">COLOMBIAN TRADING S.A.S. Derechos Reservados © 2020</div>
	<div id="derechos">
		<img src="{{ asset('public/imagenes/appwebcctv33.png') }}" style="width: 130px;display: block;margin-left: 10px;">
		<p>Empresa de desarrollo de software</p>
	</div>
</div>


</body>
</html>