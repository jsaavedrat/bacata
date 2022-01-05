@extends('layouts.landing')
@section('content')
    <div class="page-top-info">
        <div class="container">
            <h4><i class="flaticon-bag"></i> Artículos</h4>
            <div class="site-pagination">
                <a href="{{ route('welcome') }}">Inicio</a> /
                <a>Artículo</a>
            </div>
        </div>
    </div>

    <section class="product-section">
        <div class="container">
            <div class="row">

                <div class="col-lg-6">
                    <div class="product-pic-zoom">
                        <img class="product-big-img"
                            src="{{ asset('public/imagenes/sistema/productos') }}/{{ $articulo->imagenes[0]->nombre_imagen }}"
                            alt="">
                    </div>
                    <div class="product-thumbs" tabindex="1" style="overflow: hidden; outline: none;">
                        <div class="product-thumbs-track">
                            @foreach ($articulo->imagenes as $imagen)
                                @if ($loop->iteration == 1)
                                    <div class="pt active"
                                        data-imgbigurl="{{ asset('public/imagenes/sistema/productos') }}/{{ $imagen->nombre_imagen }}">
                                        <img src="{{ asset('public/imagenes/sistema/productos') }}/{{ $imagen->nombre_imagen }}"
                                            alt=""></div>
                                @else
                                    <div class="pt"
                                        data-imgbigurl="{{ asset('public/imagenes/sistema/productos') }}/{{ $imagen->nombre_imagen }}">
                                        <img src="{{ asset('public/imagenes/sistema/productos') }}/{{ $imagen->nombre_imagen }}"
                                            alt=""></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <style type="text/css">
                    .zoomImg {
                        background-color: white !important;
                    }

                </style>

                <div class="col-lg-6 product-details">
                    <h2 class="p-title">{{ $articulo->nombre_tipo_producto }} {{ $articulo->nombre_marca }}
                        {{ $articulo->nombre_modelo }}</h2>
                    <h3 class="p-price">$ {{ $articulo->precio_base }}</h3>
                    <h4 class="p-stock">Disponible: <span>En Stock On Line</span></h4>
                    <div class="p-rating">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star-o fa-fade"></i>
                    </div>
                    <div class="p-review">
                        {{-- <a href="">3 reviews</a>|<a href="">Add your review</a> --}}
                    </div>
                    <div class="fw-size-choose">
                        <h2 class="p-title">Descripción</h2>
                        @foreach ($articulo->especificaciones as $especificacion)
                            <div style="width: 50%;float: left;">
                                <p>{{ $especificacion->nombre_clasificacion }}</p>
                                <div class="color-especificacion-articulo" style="margin-top:8px;">
                                    {{ $especificacion->nombre_especificacion }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($colores != '[]')
                        <div class="col-12 float-left p-title mt-4 pl-0">Colores:</div>
                        <div class="col-12 float-left pl-0">
                            <div class="col-12">
                                <div class="row">
                                    @foreach ($colores as $color)
                                        <div class="col-auto">
                                            <a href="{{ route('ecommerce.articulo', $color->id_producto) }}"
                                                class="texto-color">{{ $color->nombre_especificacion }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-12 float-left d-flex justify-content-end">
                        <a onclick="agregarCarrito({{ $articulo->id_producto }},1,`{{ $articulo->nombre_producto }}`,`{{ $articulo->imagenes[0]->nombre_imagen }}`,{{ $articulo->precio_base }});"
                            class="site-btn" style="margin-top: 20px;color:white;"><i
                                class="flaticon-shopping-cart"></i> Agregar al carrito</a>
                    </div>

                    <div id="accordion" class="accordion-area">
                        <div class="panel">
                            <div class="panel-header" id="headingOne">
                                <button class="panel-link active" data-toggle="collapse" data-target="#collapse1"
                                    aria-expanded="true" aria-controls="collapse1">Información</button>
                            </div>
                            <div id="collapse1" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordion">
                                <div class="panel-body">
                                    <p>{{ $articulo->descripcion_modelo }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-header" id="headingTwo">
                                <button class="panel-link active" data-toggle="collapse" data-target="#collapse2"
                                    aria-expanded="true" aria-controls="collapse2">Comentarios</button>
                            </div>
                            <div id="collapse2" class="collapse show" aria-labelledby="headingTwo"
                                data-parent="#accordion">
                                <div class="panel-body">
									@foreach ($comentarios as $comentario)
                                    <dl class="row">
                                        <dt class="col-sm-5 color-especificacion-articulo">{{$comentario->nombre}} <small>{{$comentario->fecha}}</small></dt>
                                        <dd class="col-sm-7">
                                            {{$comentario->comentario}}
                                        </dd>
                                    </dl> 
									@endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section style="">
        <div class="container">
			@if (\Session::has('success'))
			<div class="alert alert-success">
				<ul>
					{!! \Session::get('success') !!}
				</ul>
			</div>
		@endif
            <div class="justify-content-md-center" style=" justify-content: center;">
                <form method="post" action="{{ route('ecommerce.articulo_comentario') }}">
					@csrf
                    <div class="mb-3">
						<input type="hidden" id="id_producto" name="id_producto" value="{{ $articulo->id_producto  }}">

                        <label for="nombrecliente" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" id="nombrecliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Comentario</label>
                        <textarea class="form-control"   maxlength="150"  name="comentario" id="comentario" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="background-color: #2a3d6a; border-color: #2a3d6a;">Enviar Comentario</button>
                </form>

            </div>
        </div>
    </section>

    <section class="top-letest-product-section" id="productos-recientes-ecommerce">
        <div class="container pt-4 pb-4">
            <div class="section-title">
                <p>ARTÍCULOS SIMILARES</p>
            </div>
            <div class="product-slider owl-carousel pt-4 pb-4">

                @foreach ($producto_bodegas as $producto_bodega)
                    <div class="product-item caja-producto">
                        <div class="pi-pic">
                            <!-- <div class="tag-sale">Nuevo</div> -->
                            <div class="col-12 h-100">
                                <div class="row d-flex justify-content-center align-items-center h-100">
                                    <a href="{{ route('ecommerce.articulo', $producto_bodega->id_producto) }}">
                                        <img src="{{ asset('public/imagenes/sistema/productos') }}/{{ $producto_bodega->nombre_imagen }}"
                                            class="imagen-producto">
                                    </a>
                                </div>
                            </div>
                            <div class="pi-links">
                                <a onclick="agregarCarrito({{ $producto_bodega->id_producto }},1,`{{ $producto_bodega->nombre_producto }}`,`{{ $producto_bodega->nombre_imagen }}`,{{ $producto_bodega->precio_base }});"
                                    class="add-card"><i class="flaticon-bag"></i><span>AGREGAR AL CARRITO</span></a>
                                <a href="{{ route('ecommerce.articulo', $producto_bodega->id_producto) }}"
                                    class="add-card"><i class="flaticon-add"></i><span>Ver Detalle</span></a>
                            </div>
                        </div>
                        <div class="pi-text col-12">
                            <div class="row">
                                <div class="col-12 pl-0 precio-producto text-center">$
                                    {{ number_format($producto_bodega->precio_base, 2, ',', '.') }}</div>
                                <div class="col-12 pr-0 pl-0 nombre-producto"
                                    title="{{ $producto_bodega->nombre_producto }}">
                                    {{ $producto_bodega->nombre_producto }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if (count($producto_bodegas) == 0)
                <div class="col-12 d-flex justify-content-center mt-3">
                    No se encontraron productos.
                </div>
            @endif
        </div>
    </section>

    <script type="text/javascript">
        /*:::::::::TITULO DE LA PAGINA:::::::::*/
        document.getElementsByTagName("title")[0].innerHTML = "Optica Angeles | Articulo";
    </script>
    <style type="text/css">
        .texto-color {
            color: #414141;
        }

    </style>
@endsection
