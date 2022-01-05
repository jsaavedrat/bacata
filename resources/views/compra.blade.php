@extends('layouts.landing')
@section('content')

    <div class="page-top-info">
        <div class="container" style="overflow: hidden!important;">
            <div class="section-title">
                <h3>RESULTADO DE TRANSACCIÓN:</h3>
            </div>
            <div id="seccion-principal">
                <div id="seccion-videos">
                    <div id="seccion-status">
                        <div id="content-imagen-transaccion">
                            <img src="{{ asset('public/imagenes/logo-principal.png') }}" style="height: 100%;">
                        </div>
                        <div id="content-fecha-transaccion">
                            <b>factura: </b>
                            <p style="color:red;font-weight:bold;">{{ $request->referenceCode }}</p>
                        </div>
                        <div id="header-status">RESULTADO DE TRANSACCIÓN:</div>
                        <div class="content-elemento-resultado">
                            <div class="titulo-resultado">Estado: </div>
                            <div class="resultado">
                                @if ($request->transactionState == '4')
                                    <p style="color:green;font-weight:bold;">APROBADA</p>
                                @elseif($request->transactionState=="6")
                                    <p style="color:red;font-weight:bold;">RECHAZADA</p>
                                @elseif($request->transactionState=="104")
                                    <p style="color:red;font-weight:bold;">ERROR</p>
                                @elseif($request->transactionState=="7")
                                    <p style="color:orange;font-weight:bold;">PENDIENTE</p>
                                @else
                                    <p style="color:orange;font-weight:bold;">{{ $request->message }}</p>
                                @endif
                            </div>
                            <input type="hidden" id="resultado" name="resultado" value="{{ $request->transactionState }}">

                        </div>

                        <div class="content-elemento-resultado">
                            <div class="titulo-resultado">Referencia: </div>
                            <div class="resultado">{{ $request->referenceCode }}</div>
                        </div>

                        <div class="content-elemento-resultado">
                            <div class="titulo-resultado">Cliente: </div>
                            <div class="resultado">{{ $request->extra1 }}</div>
                        </div>

                        <div class="content-elemento-resultado">
                            <div class="titulo-resultado">Telefono: </div>
                            <div class="resultado">{{ $request->extra2 }}</div>
                        </div>

                        <div class="content-elemento-resultado">
                            <div class="titulo-resultado">Direccion: </div>
                            <div class="resultado">{{ $request->extra3 }}</div>
                        </div>

                        <div class="content-elemento-resultado">
                            <div class="titulo-resultado">E-Mail: </div>
                            <div class="resultado">{{ $request->buyerEmail }}</div>
                        </div>

                        <div class="content-elemento-resultado">
                            <div class="titulo-resultado">Fecha de transacción: </div>
                            <div class="resultado">{{ $request->processingDate }}</div>
                        </div>

                        <div class="content-elemento-resultado">
                            <div class="resultado" style="float:right!important;font-weight:bold!important;"><i
                                    class="icon-credit"></i> {{ $request->TX_VALUE }} {{ $request->currency }}
                            </div>
                            <div class="titulo-resultado"
                                style="float:right!important;text-align:right!important;padding-right:10px!important;">
                                Monto:
                            </div>
                        </div>
                        <div id="footer-resultado">Óptica Ángeles - Calle 4 #7-41 Zipaquirá, Cundinamarca,
                            Colombia<br>Teléfono:
                            3185571463</div>
                    </div>
                    <br>
                    <br>
                    <br>
                </div>


                <style>
                    #seccion-status {
                        width: calc(100% - 40px);
                        margin-left: 20px;
                        //height:400px;
                        overflow: hidden;
                        max-width: 800px;
                        margin-left: auto;
                        margin-right: auto;
                        left: 0;
                        right: 0;
                        border: 1px solid rgba(44, 75, 108, 0.2);
                        margin-top: 20px;
                        background-color: white;
                        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
                        padding-bottom: 10px;
                    }

                    #content-imagen-transaccion {
                        width: 70%;
                        height: 70px;
                        float: left;
                        //border:1px solid red;
                    }

                    #content-fecha-transaccion {
                        width: 30%;
                        height: 70px;
                        float: left;
                        color: rgba(80, 70, 90, 1);
                        text-align: center;
                        //border:1px solid red;
                        padding-top: 15px;
                    }

                    #header-status {
                        width: 100%;
                        //background-color:rgba(44,75,108,1);
                        padding: 5px 0px 25px 0px;
                        font-size: 18px;
                        color: rgba(80, 70, 90, 1);
                        text-align: center;
                        font-weight: 600;
                        letter-spacing: -0.5px;
                        float: left;
                    }

                    .content-elemento-resultado {
                        width: calc(100% - 40px);
                        margin-left: 20px;
                        float: left;
                        border-top: 1px solid rgba(44, 75, 108, 0.1);
                    }

                    .titulo-resultado {
                        float: left;
                        width: 170px;
                        height: 35px;
                        padding-top: 8px;
                        //border:1px solid blue;
                        font-weight: 600;
                        font-size: 15px;
                        color: rgba(80, 70, 90, 1);
                        letter-spacing: -0.5px;
                    }

                    .resultado {
                        border-left: 1px solid rgba(44, 75, 108, 0.1);
                        float: left;
                        height: 35px;
                        padding-top: 8px;
                        padding-left: 8px;
                        font-weight: 400;
                        font-size: 15px;
                        color: rgba(80, 70, 90, 1);
                        letter-spacing: -0.5px;
                    }

                    #footer-resultado {
                        border-top: 1px solid rgba(44, 75, 108, 0.1);
                        width: 100%;
                        color: rgba(80, 70, 90, 0.4);
                        float: left;
                        text-align: center;
                        line-height: 16px;
                        letter-spacing: -0.4px;
                        padding-top: 12px;
                    }

                </style>
                <script src="{{ asset('public/ecommerce/js/jquery-3.2.1.min.js') }}"></script>

                <script type="text/javascript">
                    $(window).on('load', function() {
                        var estado = document.getElementById('resultado').value;
                        if (estado == 4 || estado == 7) {

                           vaciarCarrito()
                            console.log('aprobado')
                        } else {
                            console.log('rechazado')
                        }


                    });
                </script>


            </div>
        </div>




    @endsection
