@extends('layouts.landing')
@section('content')
    <div class="page-top-info">
        <div class="container">
            <h4>Mi Carrito</h4>
            <div class="site-pagination">
                <a href="{{ route('welcome') }}">Inicio</a> /
                <a>Pagar</a>
            </div>
        </div>
    </div>

    <div id="formulario-pagar">

    </div>


    <section class="cart-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-table">
                        <h3>Resumen del Pedido</h3>
                        <div class="cart-table-warp">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="product-th"><b>Producto</b></th>
                                        <th class="quy-th"><b>Cantidad</b></th>
                                        {{-- <th class="size-th"><b>SizeSize<b/></th> --}}
                                        <th class="total-th"><b>Precio</b></th>
                                    </tr>
                                </thead>
                                <tbody id="productos_carrito">

                                </tbody>
                            </table>
                        </div>
                        <div class="total-cost">
                            <h6>Total <span id="total_precio">$ 0</span></h6>
                        </div>
                    </div>
                    <div class="site-btn sb-dark" style="margin:10px 0px 10px 0px;" onclick="realizarPago();"><img
                            src="{{ asset('public/imagenes/payu2x.png') }}" style="height: 30px;"> Pagar con PayU</div>
                </div>
                <div class="col-lg-4 card-right">
                    <a href="{{ route('ecommerce.categorias') }}" class="site-btn sb-dark">Seguir Comprando</a>
                </div>
            </div>
        </div>
    </section>

    {{--  <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">


                    <div class="card-body">
                        <form action="{{ route('pay') }}" method="POST" id="paymentForm">
                            @csrf

                            <div class="row">
                                <div class="col-auto">
                                    <label>How much you want to pay?</label>
                                    <input type="number" min="5" step="0.01" class="form-control" name="value"
                                        value="{{ mt_rand(500, 100000) / 100 }}" required>
                                    <small class="form-text text-muted">
                                        Use values with up to two decimal positions, using dot "."
                                    </small>
                                </div>
                                <div class="col-auto">
                                    <label>Currency</label>
                                    <select class="custom-select" name="currency" required>
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->iso }}">
                                                {{ strtoupper($currency->iso) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- START ACCORDION & CAROUSEL-->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
                                            <div id="accordion">

                                                <div class="card card-danger">
                                                    @foreach ($paymentPlatforms as $paymentPlatform)
                                                        <!--  <input data-toggle="collapse"
                     href="#{{ $paymentPlatform->name }}"
                     class="form-check-input" type="radio"
                     name="payment_platform"
                     value="{{ $paymentPlatform->id }}" required>  -->
                                                        <div class="card-header">
                                                            <h4 class="card-title w-100">
                                                                <div class="form-group">
                                                                    <div class="form-check">

                                                                        <label
                                                                            class="btn btn-outline-secondary rounded m-2 p-1"
                                                                            href="#{{ $paymentPlatform->name }}"
                                                                            data-toggle="collapse">


                                                                            <img class="img-thumbnail"
                                                                                src="{{ asset('public/') }}/{{ $paymentPlatform->image }}">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </h4>
                                                        </div>
                                                        <div id="{{ $paymentPlatform->name }}" class="collapse"
                                                            data-parent="#accordion">
                                                            <div class="card-body">
                                                                @includeIf('components.' .
                                                                strtolower($paymentPlatform->name) . '-collapse')
                                                                <div class="text-center mt-3">
                                                                    <button type="submit" id="payButton"
                                                                        class="btn btn-primary btn-lg">Pagar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>
                            <!-- /.row -->
                            <!-- END ACCORDION & CAROUSEL-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  --}}

    <script type="text/javascript">
        function realizarPago() {

            carrito = localStorage.getItem('carrito');
            var url = "{{ route('ecommerce.pagarPayu') }}";
            var datos = {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "email": '{{ Auth::user()->email }}',
                "productos": carrito,
                "descripcion": 'Compras Online',
                "nombre": '{{ Auth::user()->name }} {{ Auth::user()->apellido }}',
                "telefono": '{{ Auth::user()->telefono }}',
                "direccion": '{{ Auth::user()->direccion }}'
            };
            $.ajax({
                type: 'GET',
                url: url,
                data: datos,
                success: function(data) {

                    console.log("success");
                    var x = JSON.parse(data);

                    var f = x.f;
                    var id = x.id;
                    //console.log(x.existe);
                    document.getElementById("formulario-pagar").innerHTML = f;
                    if ((x.existe != false) && (id != undefined)) {
                        document.getElementById(id).click();
                    } else {
                        document.getElementById("formulario-pagar").innerHTML =
                            "<h2 style='text-align:center'><i class='flaticon-cancel-1'></i> Ocurrio un error con los productos solicitados</h2>";
                    }
                },
                error: function(data) {
                    console.log("error");
                }
            });
        }
    </script>
    <script type="text/javascript">
        /*:::::::::TITULO DE LA PAGINA:::::::::*/
        document.getElementsByTagName("title")[0].innerHTML = "Optica Angeles | Pagar";
    </script>
    <style type="text/css">
        #espera-transaccion {
            width: 100%;
            margin-top: 40px;
            color: rgba(17, 17, 17, 1);
            text-align: center;
            font-weight: 500;
            float: left;
        }

    </style>
@endsection
