@extends('layouts.landing')
@section('content')
    <div class="page-top-info">
        <div class="col-12">
            <h4>USUARIO</h4>
            <div class="site-pagination">
                <a href="{{ route('welcome') }}">Inicio</a> /
                <a>{{ $usuario->name }} {{ $usuario->apellido }}</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-auto">
                <div class="row">
                    <div class="col-12 filter-widget text-center">
                        <h2 class="fw-title">MENÚ</h2>
                    </div>
                    <div class="col-12">
                        <ul class="category-menu">
                            <li><a><b>MIS COMPRAS</b></a></li>
                            <li><a href="{{ route('ecommerce.cliente_perfil') }}"><b>PERFIL</b></a></li>
                            <li><a href="{{ route('pago') }}"><b>PAGO / ABONO ONLINE</b></a></li>
                            <li><a href="{{ route('ecommerce.carrito') }}"><b>MI CARRITO</b></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-12">
                        <div class="cart-table">
                            <h3>Compras</h3>
                            <div id="div4" class="cart-table-warp">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="product-th"><b>N°</b></th>
                                            {{-- <th class="product-th"><b>Cantidad Productos</b></th> --}} <th class="quy-th"><b>Monto</b></th>
                                            <th class="total-th"><b>referencia</b></th>
                                            <th class="total-th"><b>Resultado</b></th>

                                            <th class="total-th"><b>Ver Productos</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($compras as $compra)
                                            <tr style="text-align: center;">
                                                <td>{{ $loop->iteration }}</td>
                                                {{-- <td>{{$compra->cantidad_productos}}</td> --}}
                                                <td>{{ $compra->monto }}</td>
                                                <td>{{ $compra->referencia }}</td>
                                                <td>
                                                    @if ($compra->transactionState == '4')
                                                        <p style="color:green;font-weight:bold;">APROBADA</p>
                                                    @elseif($compra->transactionState=="6")
                                                        <p style="color:red;font-weight:bold;">RECHAZADA</p>
                                                    @elseif($compra->transactionState=="104")
                                                        <p style="color:red;font-weight:bold;">ERROR</p>
                                                    @elseif($compra->transactionState=="7")
                                                        <p style="color:orange;font-weight:bold;">PENDIENTE</p>
                                                    @else
                                                        <p style="color:orange;font-weight:bold;">{{ $request->message }}
                                                        </p>
                                                    @endif
                                                </td>
                                                <td class="td-acciones">
                                                    <div class="iconos-acciones">
                                                        <div class="content-acciones">
                                                            <i onclick="ver('{{ $compra->referencia }}');"
                                                                class="icon-eye i-acciones"> </i> &nbsp;
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="total-cost"> 
                            </div>
                            <form method="post" action="{{ route('cliente.productos_compras') }}">
                                @csrf
                                <input type="text" name="ver" id="ver" style="display: none;">
                                <button style="display: none;" id="boton-ver">h</button>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="category-section spad">
        <div class="container pb-4">
            <div class="section-title">
                <p>TE PUEDEN INTERESAR</p>
            </div>
            <div class="row">
                <div class="col-lg-12  order-1 order-lg-2 mb-5 mb-lg-0">
                    <div class="row">
                        @foreach ($producto_bodegas as $producto_bodega)
                            <div class="col-lg-3 col-sm-6 caja-producto">
                                <div class="product-item">
                                    <div class="pi-pic">
                                        <!-- <div class="tag-sale">Nuevo</div> -->
                                        <div class="col-12 h-100">
                                            <div class="row d-flex justify-content-center align-items-center h-100">
                                                <a
                                                    href="{{ route('ecommerce.articulo', $producto_bodega->id_producto) }}">
                                                    <img src="{{ asset('public/imagenes/sistema/productos') }}/{{ $producto_bodega->nombre_imagen }}"
                                                        class="imagen-producto">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="pi-links">
                                            <a onclick="agregarCarrito({{ $producto_bodega->id_producto }},1,`{{ $producto_bodega->nombre_producto }}`,`{{ $producto_bodega->nombre_imagen }}`,{{ $producto_bodega->precio_base }});"
                                                class="add-card"><i class="flaticon-bag"></i><span>AGREGAR AL
                                                    CARRITO</span></a>
                                            <a href="{{ route('ecommerce.articulo', $producto_bodega->id_producto) }}"
                                                class="add-card"><i class="flaticon-add"></i><span>Ver
                                                    Detalle</span></a>
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
                            </div>
                        @endforeach
                        <div class="col-12 d-flex justify-content-center mt-3">
                            {{ $producto_bodegas->links() }}
                            @if (count($producto_bodegas) == 0)
                                No se encontraron productos.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style type="text/css">
        #div4 {
            height: 280px;
        }

    </style>

    <script type="text/javascript">
        /*:::::::::TITULO DE LA PAGINA:::::::::*/
        document.getElementsByTagName("title")[0].innerHTML =
            "Optica Angeles | {{ $usuario->name }} {{ $usuario->apellido }}";


        function clickElemento(elemento) {
            document.getElementsByClassName('dt-button')[elemento].click();
        }

        function ver(id) {
            document.getElementById("ver").value = id;
            console.log(id);
            document.getElementById("boton-ver").click();
        }
    </script>
@endsection
