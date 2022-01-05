@extends('layouts.landing')
@section('content')
<div class="page-top-info">
	<div class="container">
		<br><br>
		<h3 style="color:#414141;text-transform: uppercase;">
			@if($termino->nombre_texto_web != "Politica de Privacidad y Protección de Datos")
				Términos y condiciones de
			@endif
			{{$termino->nombre_texto_web}}
		</h3>
		<div class="site-pagination">
			{!!$termino->descripcion_texto_web!!}
		</div>

		<br><br>
		@if($privacidad != null)
			<h5 style="color:#414141;text-transform: uppercase;">{{$privacidad->nombre_texto_web}}</h5>
			<div class="site-pagination">
				{!!$privacidad->descripcion_texto_web!!}
			</div>
		@endif
	</div>
</div>












































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
document.getElementsByTagName("title")[0].innerHTML = "Optica Angeles | Quienes";
</script>
@endsection