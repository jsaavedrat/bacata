@extends('layouts.landing')
@section('content')

<div class="page-top-info">
	<div class="container">
		<h4>CATEGORÍAS</h4>
		<div class="site-pagination">
			<a href="{{ route('welcome') }}">Inicio</a> /
			<a>Categorías</a>
		</div>
	</div>
</div>


<section class="category-section spad">
	<div class="col-12">
		<div class="row">
			<div class="col-auto pl-5 float-left" style="max-width: 270px;">
				<div class="filter-widget">
					<h2 class="fw-title">Categorías</h2>
					<ul class="category-menu">
						@foreach($tipo_productos_marcas as $tipo_producto_marca)
						<li><a href="{{ route('ecommerce.categoria',$tipo_producto_marca->id_tipo_producto) }}"><b>{{$tipo_producto_marca->nombre_tipo_producto}}</b></a>
							<ul class="sub-menu">
								@foreach($tipo_producto_marca->marcas as $marca)
									<li><a href="{{ route('ecommerce.marca',$marca->id_marca) }}">{{$marca->nombre_marca}}</a></li>
								@endforeach
							</ul>
						</li>
						@endforeach
					</ul>
				</div>
			</div>

			<div class="col mb-5 mb-lg-0 float-left">
				<div class="row">
					@foreach($producto_bodegas as $producto_bodega)
							<div class="col-lg-3 col-sm-6 caja-producto">
								<div class="product-item">
									<div class="pi-pic">
										<!-- <div class="tag-sale">Nuevo</div> -->
										<div class="col-12 h-100">
											<div class="row d-flex justify-content-center align-items-center h-100">
												<a href="{{ route('ecommerce.articulo',$producto_bodega->id_producto) }}">
													<img src="{{ asset('public/imagenes/sistema/productos') }}/{{$producto_bodega->nombre_imagen}}" class="imagen-producto">
												</a>
											</div>
										</div>
										<div class="pi-links">
											<a onclick="agregarCarrito({{$producto_bodega->id_producto}},1,`{{$producto_bodega->nombre_producto}}`,`{{$producto_bodega->nombre_imagen}}`,{{$producto_bodega->precio_base}});" class="add-card"><i class="flaticon-bag"></i><span>AGREGAR AL CARRITO</span></a>
											<a href="{{ route('ecommerce.articulo',$producto_bodega->id_producto) }}" class="add-card"><i class="flaticon-add"></i><span>Ver Detalle</span></a>
										</div>
									</div>
									<div class="pi-text col-12">
										<div class="row">
											<div class="col-12 pl-0 precio-producto text-center">$ {{number_format($producto_bodega->precio_base, 2, ',', '.')}}</div>
											<div class="col-12 pr-0 pl-0 nombre-producto" title="{{$producto_bodega->nombre_producto}}">{{$producto_bodega->nombre_producto}}</div>
										</div>
									</div>
								</div>
							</div>
					@endforeach
					<div class="col-12 d-flex justify-content-center mt-3">
						{{$producto_bodegas->links()}}
						@if(count($producto_bodegas) == 0)
							No se encontraron productos.
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	/*:::::::::TITULO DE LA PAGINA:::::::::*/
	document.getElementsByTagName("title")[0].innerHTML = "Optica Angeles | Categorias";
</script>

@endsection
