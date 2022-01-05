@extends('layouts.landing')
@section('content')
<div class="page-top-info">
	<div class="container">
		<h4>{{$nombre_tipo_producto->nombre_tipo_producto}}</h4>
		<div class="site-pagination">
			<a href="{{ route('welcome') }}">Inicio</a> /
			<a href="{{ route('ecommerce.categorias') }}">Categorías /</a>
			<a>{{$nombre_tipo_producto->nombre_tipo_producto}}</a>
		</div>
	</div>
</div>

<section class="category-section spad">
	<div class="container">
		<div class="row">
			<div class="col-lg-12  order-1 order-lg-2 mb-5 mb-lg-0">
				<div class="row">
					@foreach($producto_bodegas as $producto_bodega)
						<div class="col-lg-3 col-sm-6 caja-producto">
							<div class="product-item">
								<div class="pi-pic">
									<!-- <div class="tag-sale">Nuevo</div> -->
									<div class="col-12 h-100">
										<div class="row d-flex justify-content-center align-items-center h-100">
											<a href="{{ route('ecommerce.articulo',$producto_bodega->id_producto) }}" class="add-card">
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
document.getElementsByTagName("title")[0].innerHTML = "Optica Angeles | Categoria";
</script>
@endsection
