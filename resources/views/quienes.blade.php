@extends('layouts.landing')
@section('content')
<div class="page-top-info">
	<div class="container">
		@foreach($quienes as $quien)
			<br><br>
			<h3 style="color:#414141;">{!! $quien->nombre_texto_web !!}</h3>
			<div class="site-pagination">
				{!! $quien->descripcion_texto_web !!}
			</div>
		@endforeach
	</div>
</div>

<script type="text/javascript">
/*:::::::::TITULO DE LA PAGINA:::::::::*/
document.getElementsByTagName("title")[0].innerHTML = "Optica Angeles | Quienes";
</script>
@endsection