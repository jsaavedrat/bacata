@extends('layouts.landing')
@section('content')
<div class="page-top-info">
	<div class="container">
		<h3 style="color:#414141;">CONSULTA EL ESTATUS Y UBICACIÓN DE TUS GAFAS<br></h3>
		<div class="site-pagination">
			<h6 style="color:#414141;">En CIMM COLOMBIAN TRADING SAS, "Optica Angeles y Opticas de la Sabana" pensamos en tu comodidad. Por eso, colocamos a tu disposición la consulta on line del estatus y ubicación de tus gafas.</h6><br>
		</div>

		<h4 style="color:#414141;text-align: center;">ESTATUS DE TUS GAFAS:</h4><br>
		@foreach($ordenes as $orden)
			<div class="site-pagination" style="border-top:1px solid rgba(50,50,50,0.5);"><br>
				<div class="row">
					<div class="col-md-1"><h4 style="text-align: center;">{{$loop->iteration}}</h4></div>
					<div class="col-md-8">{{$orden->nombres_paciente}}<br>{{$orden->lente_orden}}<br>{{$orden->montura_orden}}</div>
					<div class="col-md-3">
						
						@if($orden->estado_orden == "ingresado" || $orden->estado_orden == "enviado" || $orden->estado_orden == "entregado")
						<h6 style="text-align: center;color:green" class="estado-orden">
							{{$orden->estado_orden}}
							@if(isset($orden->envio) && $orden->estado_orden == "enviado")
								por: {{$orden->envio->nombre_empresa_envio}} con {{$orden->envio->nombre_codigo}} # {{$orden->envio->numero_control}}
							@endif

							@if($orden->estado_orden == "ingresado")
								{{$orden->nombre_sucursal}}
							@endif
						</h6>
						@else
							<h5 style="text-align: center;color:orange" class="estado-orden">
								@if($orden->estado_orden == "calidad")
									control de 
								@endif

								{{$orden->estado_orden}}
								
								@if($orden->estado_orden == "rechazado")
									devuelto a producción
								@endif

								@if($orden->estado_orden == "apartado")
									- debes abonar el 50% para hacer las gafas
								@endif
							</h5>
						@endif
						
					</div>
				</div>
			</div>
		@endforeach
		

	</div>
</div>

<style type="text/css">
	.estado-orden{
		text-transform: uppercase;
	}
</style>

<script type="text/javascript">
/*:::::::::TITULO DE LA PAGINA:::::::::*/
document.getElementsByTagName("title")[0].innerHTML = "Optica Angeles | Consulta";
</script>
@endsection