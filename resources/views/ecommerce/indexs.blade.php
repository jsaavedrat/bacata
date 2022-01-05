<!DOCTYPE html>
<html lang="es">
<head>
	<title>Optica Angeles | Tienda On Line</title>
	<meta charset="UTF-8">
	<meta name="description" content=" Divisima | eCommerce Template">
	<meta name="keywords" content="divisima, eCommerce, creative, html">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Favicon -->
	<link href="{{ asset('imagenes/icono.png') }}" rel="shortcut icon"/>

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,300i,400,400i,700,700i" rel="stylesheet">


	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/font-awesome.min.css"/>
	<link rel="stylesheet" href="css/flaticon.css"/>
	<link rel="stylesheet" href="css/slicknav.min.css"/>
	<link rel="stylesheet" href="css/jquery-ui.min.css"/>
	<link rel="stylesheet" href="css/owl.carousel.min.css"/>
	<link rel="stylesheet" href="css/animate.css"/>
	<link rel="stylesheet" href="css/style.css"/>

</head>
<body>
	<!-- Page Preloder -->
	<div id="preloder">
		<div class="loader"></div>
	</div>

	<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::HEADER::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
	<header class="header-section">
		<div class="header-top">
			<div class="container">
				<div class="row">
					<div class="col-lg-2 text-center text-lg-left">
						<!-- logo -->
						<a href="{{ route('welcome') }}" class="site-logo">
							<img src="{{ asset('imagenes/logo-principal.png') }}" alt="" style="height:50px;">
						</a>
					</div>
					<div class="col-xl-6 col-lg-5">
						{{--<form class="header-search-form">
							<input type="text" placeholder="Buscar Producto">
							<button><i class="flaticon-search"></i></button>
						</form>--}}
					</div>
					<div class="col-xl-4 col-lg-5">
						<div class="user-panel">
							<div class="up-item">
								<i class="flaticon-profile"></i>
								<a href="{{ route('login') }}">Iniciar Sesión </a> / <a href="{{ route('register') }}">Crear Cuenta</a>
							</div>
							<div class="up-item">
								<a href="{{ route('ecommerce.carrito') }}">
								<div class="shopping-card">
									<i class="flaticon-shopping-cart"></i>
									<span id="cantidad_articulos">0</span>
								</div>
								Mi Carrito</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<nav class="main-navbar">
			<div class="container">
				<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::MENU::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
				<ul class="main-menu">

					<li>
						<a href="{{ route('ecommerce.marca',1) }}">Ray Ban</a>
					</li>

					<li>
						<a href="{{ route('ecommerce.marca',2) }}">Lacoste</a>
					</li>

					{{--<li><a href="#">Dia del Niño
							<span class="new">Promoción</span>
						</a>
					</li>--}}

					@foreach($tipos_productos_ecommerce as $tipo_producto_ecommerce)
						<li>
							@if($loop->iteration < 5)
							<a href="{{ route('ecommerce.categoria',$tipo_producto_ecommerce->id_tipo_producto) }}">{{$tipo_producto_ecommerce->nombre_tipo_producto}}</a>
							@endif
						</li>
					@endforeach

					@if($band > 5)
						<li>
							<a href="#">Más</a>
						</li>
					@endif

					<li>
						<a href="{{ route('ecommerce.categorias') }}">Todas las Categorías</a>
					</li>
					
				</ul>
			</div>
		</nav>
	</header>
	<!-- Header section end -->



	<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::SLIDER PRINCIPAL::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
	<section class="hero-section">
		<div class="hero-slider owl-carousel">
			<div class="hs-item set-bg" data-setbg="img/bg.jpg">
				<div class="container">
					<div class="row">
						<div class="col-xl-6 col-lg-7 text-white">
							<span>NUEVAS</span>
							<h2>MONTURAS</h2>
							<p>Ponte a la moda mejorando tu salud visual con nuestra gama de monturas que tenemos para ti, diseños y colores totalmente a la moda.</p>
							<a href="#" class="site-btn sb-line">Comprar MONTURAS</a>
							<a href="{{ route('ecommerce.carrito') }}" class="site-btn sb-white">Mi Carrito</a>
						</div>
					</div>
					<div class="offer-card text-white">
						<br>
						<span><h4>Desde</h4></span>
						<h3>$30000</h3>
						<p>Comprar</p>
					</div>
				</div>
			</div>
			<div class="hs-item set-bg" data-setbg="img/bg-2.jpg">
				<div class="container">
					<div class="row">
						<div class="col-xl-6 col-lg-7 text-white">
							<span>NUEVAS</span>
							<h2>GAFAS DE SOL</h2>
							<p>Descubre la variedad en distintas marcas y modelos de nuestro stock de gafas de sol totalmente a la moda y con excelente protección contra los rayos UV. Que esperas!!! compra la que mas te guste en nuestra sucursal Online</p>
							<a href="#" class="site-btn sb-line">Comprar Gafas de Sol</a>
							<a href="{{ route('ecommerce.carrito') }}" class="site-btn sb-white">Mi Carrito</a>
						</div>
					</div>
					<div class="offer-card text-white">
						<br>
						<span><h4>Desde</h4></span>
						<h3>$40000</h3>
						<p>Comprar</p>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="slide-num-holder" id="snh-1"></div>
		</div>
	</section>
	<!-- Hero section end -->



	<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::INFORMACIÓN EXTRA::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
	<section class="features-section">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4 p-0 feature">
					<div class="feature-inner">
						<div class="feature-icon">
							<img src="img/icons/1.png" alt="#">
						</div>
						<h2>Pago Online Seguro</h2>
					</div>
				</div>
				<div class="col-md-4 p-0 feature">
					<div class="feature-inner">
						<div class="feature-icon">
							<img src="img/icons/2.png" alt="#">
						</div>
						<h2>Productos en Promoción</h2>
					</div>
				</div>
				<div class="col-md-4 p-0 feature">
					<div class="feature-inner">
						<div class="feature-icon">
							<img src="img/icons/3.png" alt="#">
						</div>
						<h2>Envíos a todo el país</h2>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Features section end -->

	<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::MAS RECIENTES::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
	<section class="top-letest-product-section">
		<div class="container">
			<div class="section-title">
				<h2>LO MÁS RECIENTE</h2>
			</div>
			<div class="product-slider owl-carousel">

				@foreach($producto_bodegas as $producto_bodega)
					@if($loop->iteration < 10)
						<div class="product-item" style="float: left;">
							<div class="pi-pic" style="float: left;height: 200px!important;">
								{{--<div class="tag-new">Nuevo</div>--}}
								<div class="tag-sale">Nuevo</div>
								<img src="../imagenes/sistema/productos/{{$producto_bodega->imagen}}" style="width: 100%;max-height: 100%;">
								<div class="pi-links">
									<a style="cursor: pointer;" onclick="agregarCarrito({{$producto_bodega->id_producto}},1);" class="add-card"><i class="flaticon-bag"></i><span>AGREGAR AL CARRO</span></a>
									<a style="cursor: pointer;" class="add-card" href="{{ route('ecommerce.articulo',$producto_bodega->id_producto) }}"><i class="flaticon-add"></i><span>Ver Detalle</span></a>
								</div>
							</div>
							<div class="pi-text" style="float: left;">
								<h6>{{$producto_bodega->precio_base}}</h6>
								<p>
									{{$producto_bodega->nombre_tipo_producto}} {{$producto_bodega->nombre_marca}} {{$producto_bodega->nombre_modelo}} {{$producto_bodega->especificaciones}}
								</p>
							</div>
						</div>
					@endif
				@endforeach

			</div>
		</div>
	</section>
	<!-- letest product section end -->


{{--
	<!-- Product filter section -->
	<section class="product-filter-section">
		<div class="container">
			<div class="section-title">
				<h2>LOS MÁS VENDIDOS</h2>
			</div>
			<ul class="product-filter-menu">
				@foreach($tipos_productos_ecommerce as $tipo_producto_ecommerce)
					<li><a href="#">{{$tipo_producto_ecommerce->nombre_tipo_producto}}</a></li>	
				@endforeach
			</ul>
			<div class="row">
				<div class="col-lg-3 col-sm-6">
					<div class="product-item">
						<div class="pi-pic">
							<img src="./img/product/5.jpg" alt="">
							<div class="pi-links">
								<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
								<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
							</div>
						</div>
						<div class="pi-text">
							<h6>$35,00</h6>
							<p>Flamboyant Pink Top </p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="product-item">
						<div class="pi-pic">
							<div class="tag-sale">ON SALE</div>
							<img src="./img/product/6.jpg" alt="">
							<div class="pi-links">
								<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
								<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
							</div>
						</div>
						<div class="pi-text">
							<h6>$35,00</h6>
							<p>Black and White Stripes Dress</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="product-item">
						<div class="pi-pic">
							<img src="./img/product/7.jpg" alt="">
							<div class="pi-links">
								<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
								<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
							</div>
						</div>
						<div class="pi-text">
							<h6>$35,00</h6>
							<p>Flamboyant Pink Top </p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="product-item">
						<div class="pi-pic">
							<img src="./img/product/8.jpg" alt="">
							<div class="pi-links">
								<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
								<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
							</div>
						</div>
						<div class="pi-text">
							<h6>$35,00</h6>
							<p>Flamboyant Pink Top </p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="product-item">
						<div class="pi-pic">
							<img src="./img/product/9.jpg" alt="">
							<div class="pi-links">
								<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
								<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
							</div>
						</div>
						<div class="pi-text">
							<h6>$35,00</h6>
							<p>Flamboyant Pink Top </p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="product-item">
						<div class="pi-pic">
							<img src="./img/product/10.jpg" alt="">
							<div class="pi-links">
								<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
								<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
							</div>
						</div>
						<div class="pi-text">
							<h6>$35,00</h6>
							<p>Black and White Stripes Dress</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="product-item">
						<div class="pi-pic">
							<img src="./img/product/11.jpg" alt="">
							<div class="pi-links">
								<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
								<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
							</div>
						</div>
						<div class="pi-text">
							<h6>$35,00</h6>
							<p>Flamboyant Pink Top </p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="product-item">
						<div class="pi-pic">
							<img src="./img/product/12.jpg" alt="">
							<div class="pi-links">
								<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
								<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
							</div>
						</div>
						<div class="pi-text">
							<h6>$35,00</h6>
							<p>Flamboyant Pink Top </p>
						</div>
					</div>
				</div>
			</div>
			<div class="text-center pt-5">
				<button class="site-btn sb-line sb-dark">VER TODO</button>
			</div>
		</div>
	</section>
	<!-- Product filter section end -->

--}}

	<!-- Banner section -->
	<section class="banner-section">
		<div class="container">
			<div class="banner set-bg" data-setbg="img/banner-bg.jpg">
				<div class="tag-new">NUEVA</div>
				<span>Promoción en tienda física</span>
				<h2>Niños 2020</h2>
				<a href="#" class="site-btn">desde 01 Jul hasta 30 Jul 2020</a>
			</div>
		</div>
	</section>
	<!-- Banner section end  -->


	<!-- Footer section -->
	<section class="footer-section">
		<div class="container">
			<div class="footer-logo text-center">
				<a href="{{ route('welcome') }}"><img src="{{ asset('imagenes/logo-principal.png') }}" alt="" style="width: 250px;"></a>
			</div>
			<div class="row">
				<div class="col-lg-3 col-sm-6">
					<div class="footer-widget about-widget">
						<h2>Acerca</h2>
						<p>Optica Angeles y Opticas de la Sabana somos las mas prestigiosas Opticas de la Sabana de Bogotá y alrededores; contamos con un equipo excelente de profesionales de la salud para que te asesoren en tu salud visual.</p>
						{{--<img src="img/cards.png" alt="">--}}
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="footer-widget about-widget">
						<h2>Nosotros</h2>
						<ul>
							<li><a href="">Quienes Somos</a></li>
							<li><a href="">Servicios</a></li>
							<li><a href="">Cotizar</a></li>
							<li><a href="">Chat</a></li>
							<li><a href="">Promociones</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="footer-widget about-widget">
						<h2>Atención</h2>
						<ul>
							<li><a href="">Solicitud de cita</a></li>
							<li><a href="">Examen Visual Gratis</a></li>
							<li><a href="">Sistema de Opticas</a></li>
							<li><a href="">Carrito de compras</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="footer-widget contact-widget">
						<h2>Contacto</h2>
						<div class="con-info">
							<span>C.</span>
							<p>Optica Angeles </p>
						</div>
						<div class="con-info">
							<span>B.</span>
							<p>Calle 4 #7-41 Zipaquirá, Cundinamarca – Colombia</p>
						</div>
						<div class="con-info">
							<span>T.</span>
							<p>+57 (1) 8510215</p>
						</div>
						<div class="con-info">
							<span>E.</span>
							<p>contactanos@opticaangeles.com</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="social-links-warp">
			<div class="container">
				<div class="social-links">
					{{--<a href="" class="instagram"><i class="fa fa-instagram"></i><span>instagram</span></a>--}}
					{{--<a href="" class="google-plus"><i class="fa fa-google-plus"></i><span>g+plus</span></a>--}}
					{{--<a href="" class="pinterest"><i class="fa fa-pinterest"></i><span>pinterest</span></a>--}}
					<a href="" class="facebook"><i class="fa fa-facebook"></i><span>facebook</span></a>
					{{--<a href="" class="twitter"><i class="fa fa-twitter"></i><span>twitter</span></a>--}}
					{{--<a href="" class="youtube"><i class="fa fa-youtube"></i><span>youtube</span></a>--}}
					{{--<a href="" class="tumblr"><i class="fa fa-tumblr-square"></i><span>tumblr</span></a>--}}
					<div id="imagen-app-web" style="float: right;">
						<img src="{{ asset('imagenes/blanco-gris.png') }}" style="width: 250px;">
					</div>
				</div>

<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --> 
<p class="text-white text-center mt-5">Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a></p>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->

			</div>
		</div>
	</section>
	<!-- Footer section end -->



	<!--====== Javascripts & Jquery ======-->
	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.slicknav.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/jquery.nicescroll.min.js"></script>
	<script src="js/jquery.zoom.min.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/main.js"></script>


	<script type="text/javascript">

		carrito = localStorage.getItem('carrito');

		function agregarCarrito(id_producto,cantidad_producto){

			if((carrito == null) || (carrito == "") || (carrito == []) || (carrito == "[]")){
				console.log("El Carrito estaba Vacío");

				carrito = [];
				var producto = {
					'id': id_producto,
					'cantidad': cantidad_producto
				}
				carrito.push(producto);
				carrito = JSON.stringify(carrito);
				localStorage.setItem('carrito', carrito);


			}else{
				console.log("El Carrito está Lleno:");
				carrito = JSON.parse(carrito);

				var encontrado = false;
				for(i = 0; i < carrito.length; i++){
					if(carrito[i].id == id_producto){
						carrito[i].cantidad = carrito[i].cantidad + cantidad_producto;
						encontrado = true;
					}
				}
				if(encontrado == false){
					var producto = {
						'id': id_producto,
						'cantidad': cantidad_producto
					}
					carrito.push(producto);
				}
				carrito = JSON.stringify(carrito);
				localStorage.setItem('carrito', carrito);

			}

			cantidadArticulos();
		}

		function cantidadArticulos(){

			articulos = localStorage.getItem('carrito');
			articulos = JSON.parse(articulos);
			var cantidad = 0;

			if ((articulos == null) || (articulos == "")) {
				document.getElementById("cantidad_articulos").innerHTML="vacio";
			}else{
				for(i = 0; i < articulos.length; i++){
					cantidad = cantidad + articulos[i].cantidad;
				}
				document.getElementById("cantidad_articulos").innerHTML=cantidad;
			}
		}
		cantidadArticulos();


		function vaciarCarrito(){
			localStorage.removeItem('carrito');
		}
	</script>


	</body>
</html>
