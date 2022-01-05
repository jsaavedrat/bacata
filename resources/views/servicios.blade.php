@extends('layouts.landing')
@section('content')
<div class="page-top-info">
	<div class="container">
		<h2><i class="fa fa-info"></i> Información del Servicio:</h2>	
		<div class="row imagen-servicio">
			<div class="col-12 site-pagination align-self-end" style="background-color: rgba(0,0,0,0.5);padding: 20px;">
				<h2 style="color:white;text-shadow: 0px 0px 5px rgba(0,0,0,0.5);">{{$servicio->nombre_servicio}}<br></h2>
			</div>
		</div>
		<h5>{{$servicio->descripcion_servicio}}</h5>
	</div>
</div>
<style type="text/css">
	.imagen-servicio{
		height: 60vh;
		background-repeat: no-repeat;
    	background-position: center;
    	background-size: 100%;
    	background-image:url('{{ asset('public/imagenes/pagina/servicios') }}/{{$servicio->imagen_servicio}}');
    	margin-bottom: 20px;
	}
</style>


<script type="text/javascript">
/*:::::::::TITULO DE LA PAGINA:::::::::*/
document.getElementsByTagName("title")[0].innerHTML = "Optica Angeles | {{$servicio->nombre_servicio}}";
</script>
@endsection