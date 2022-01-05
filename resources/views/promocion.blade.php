@extends('layouts.landing')
@section('content')
@php

$tipos_identificacion = DB::table('tipos_identificacion')
->where('estado_tipo_identificacion','=','activo')
->get();

@endphp

<section class="banner-section" id="seccion-promociones">
    <div class="section-title" style="text-transform: uppercase;">
        <h2>FELICIDADES !!!<br></h2><br>
        <h1>{{$promocion_pagina->resultado_promocion}}</h1>
    </div>
    
    <div class="container mb-4">
    	<div class="col-12 mb-4 mt-3 seccion-usuario">
    		<form method="post" data-parsley-validate action="{{ route('promocion.redimir') }}">
    			@csrf
	    		<div class="row">
	    			<input type="hidden" name="id_promocion_pagina" value="{{$promocion_pagina->id_promocion_pagina}}">
	    			<div class="col-12 mb-4">
	    				<h4 class="titulo-informacion-redimir">Ingresa tu información para redimir la promoción</h4>
	    			</div>
	    			<div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
	    				<label class="col-12 label-pagina">Tipo Identificación</label>
	    				<select class="col-12 campo-pagina" name="id_tipo_identificacion" required>
	    					<option value="">Seleccione</option>
	    					@foreach($tipos_identificacion as $tipo_identificacion)
	                            <option value="{{$tipo_identificacion->id_tipo_identificacion}}">{{$tipo_identificacion->nombre_tipo_identificacion}}</option>
	                        @endforeach
	    				</select>
	    			</div>
	    			<div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
	    				<label class="col-12 label-pagina">Identificación</label>
	    				<input class="col-12 campo-pagina" name="identificacion" placeholder="N° Identificación" maxlength="30" required>
	    			</div>
	    			<div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
	    				<label class="col-12 label-pagina">Nombres</label>
	    				<input class="col-12 campo-pagina" name="nombres" placeholder="Ingresa nombres" maxlength="50" required>
	    			</div>
	    			<div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
	    				<label class="col-12 label-pagina">Apellidos</label>
	    				<input class="col-12 campo-pagina" name="apellidos" placeholder="Ingresa apellidos" maxlength="50" required>
	    			</div>
	    			<div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
	    				<label class="col-12 label-pagina">Teléfono</label>
	    				<input class="col-12 campo-pagina" name="telefono" placeholder="Ingresa teléfono" maxlength="50">
	    			</div>
	    			<div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
	    				<label class="col-12 label-pagina">Correo Electrónico</label>
	    				<input class="col-12 campo-pagina" name="email" placeholder="Ingresa E-Mail" maxlength="100" type="email">
	    			</div>
	    			<div class="col-12 mt-4">
	    				<button class="btn btn-info">REDIMIR PROMOCIÓN</button>
	    			</div>
	    		

	    			<div class="col-12 mt-4">
                        Acércate a cualquiera de nuestras sucursales y solicita tu premio.<br>APLICAN TÉRMINOS Y CONDICIONES.<br>
                        <a href="{{route('terminos_condiciones', ['termino' => 'Promociones'])}}" target="_blank" style="font-size:12px;">Términos y condiciones.</a>
                    </div>
	    		</div>
	    	</form>
    	</div>
        <h4 style="text-transform: uppercase;">{{$promocion_pagina->nombre_promocion_pagina}}</h4>
        <div class="row position-relative">
            <img class="col-12" src="{{ asset('public/imagenes/pagina/promociones') }}/{{$promocion_pagina->imagen_promocion_pagina}}">
            @if($promocion_pagina->mostrar_banner == "SI")
                <div class="col-12 position-absolute h-100">
                    <div class="row h-100 d-flex {{$promocion_pagina->ubicacion_banner}}">
                        <div class="col-auto ml-2 mr-2 mt-2 mb-2">
                            <p class="texto-banner texto-banner-1" style="color: {{$promocion_pagina->color_texto_banners}}">{{$promocion_pagina->texto_banner}}</p>
                            <p class="texto-banner texto-banner-2" style="color: {{$promocion_pagina->color_texto_banners}}">{{$promocion_pagina->texto_banner_2}}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<script type="text/javascript">
/*:::::::::TITULO DE LA PAGINA:::::::::*/
document.getElementsByTagName("title")[0].innerHTML = "Optica Angeles | Promoción {{$promocion_pagina->nombre_promocion_pagina}}";
</script>

<style type="text/css">

	#seccion-promociones{
		margin-top: 50px;
	}
 
    .texto-banner{
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 0px;
    }
    .texto-banner-1{
        font-size: 20px;
        line-height: 25px;
    }
    .texto-banner-2{
        font-size: 25px;
        line-height: 30px;
    }
    .boton-promocion{
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        padding: 2px 30px 0px 30px;
        border-radius: 50px;
        text-transform: uppercase;
        color: white!important;
        background-color: rgba(50,50,50,0.95);
        line-height: 40px;
        height: 40px;
        cursor: pointer;
        text-align: center;
    }

    img {
      pointer-events: none;
    }

    .seccion-usuario{
    	border: 1px solid rgb(115,115,115);
    	padding-top: 30px;
    	padding-bottom: 20px;
    }

    .titulo-informacion-redimir{
    	text-transform: uppercase;
    	color: rgba(50,50,50,1);
    }

    .campo-pagina {
	    height: 35px;
	    border: 1px solid rgb(115,115,115);
	    background-color: #f0f0f0;
	    margin: 0px;
	}

	.label-pagina {
		height: 35px;
		white-space: nowrap;
		text-overflow: ellipsis;
		margin: 0px;
		padding-top:10px;
	}

	.btn {
		height: 35px;

	}

</style>
@endsection