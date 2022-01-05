@extends('layouts.landing')
@section('content')
  
<br>
<br>
    <div class="container">
        <div class="row"> 
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
                                            <th class="quy-th"><b>Nombre</b></th>
                                            <th class="quy-th"><b>Cantidad</b></th>
                                            <th class="quy-th"><b>Precio</b></th>
                                            <th class="quy-th"><b>Monto</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($productos as $producto)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> 
                                                <td>{{ $producto->nombre }}</td>
                                                <td>{{ $producto->cantidad }}</td>
                                                <td>${{ $producto->precio }}</td> 
                                                <td>${{ $producto->precio * $producto->cantidad }}</td>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> 
                            <div class="total-cost"> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        #div4 {
            height: 250px;
        }

    </style>

    <script type="text/javascript">
       

    </script>
@endsection
