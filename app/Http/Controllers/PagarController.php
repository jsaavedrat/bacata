<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class PagarController extends Controller
{
    /**
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::::::::::::::::::::::::::::::::::::::::::GENERAR KEY PAYU:::::::::::::::::::::::::::::::::::::::::::::*/


    public function generarKey(Request $request)
    {

        // dd($request);

        $key = DB::table('procesadores')
            ->where('id_procesador', '=', 1)
            ->first();

        $letraAleatoria = chr(rand(ord("a"), ord("z")));
        $letraAleatoria2 = chr(rand(ord("a"), ord("z")));
        $letraAleatoria3 = chr(rand(ord("a"), ord("z")));
        $letraAleatoria4 = chr(rand(ord("a"), ord("z")));
        $letraAleatoria5 = chr(rand(ord("a"), ord("z")));
        $letraAleatoria6 = chr(rand(ord("a"), ord("z")));
        $dates = getdate();
        $fecha = "REF" . $dates["year"] . $letraAleatoria . $dates["mon"] . $letraAleatoria2 . $dates["mday"] . $letraAleatoria3 . $dates["hours"] . $letraAleatoria4 . $dates["minutes"] . $letraAleatoria5 . $dates["seconds"] . $letraAleatoria6;
        $charEmail = substr($request->email, 2, 2);

        $referencia = $fecha . $charEmail;
        $firma = md5($key->apiKey . "~" . $key->merchantId . "~" . $referencia . "~" . $request->monto . "~COP");

        $form = "<form method='post' action='https://checkout.payulatam.com/ppp-web-gateway-payu/'>
                    <input name='merchantId'    type='hidden'  value='$key->merchantId'>
                    <input name='accountId'     type='hidden'  value='$key->accountId'>
                    <input name='extra1'        type='hidden'  value='$request->nombre'>
                    <input name='extra2'        type='hidden'  value='$request->telefono'>
                    <input name='extra3'        type='hidden'  value='$request->direccion'>
                    <input name='description'   type='hidden'  value='$request->descripcion'>
                    <input name='referenceCode' type='hidden'  value='$referencia'>
                    <input name='amount'        type='hidden'  value='$request->monto'>
                    <input name='tax'           type='hidden'  value='0'>
                    <input name='taxReturnBase' type='hidden'  value='0'>
                    <input name='currency'      type='hidden'  value='COP'>
                    <input name='signature'     type='hidden'  value='$firma'>
                    <input name='test'          type='hidden'  value='1'>
                    <input name='buyerEmail'    type='hidden'  value='$request->email'>
                    <input name='responseUrl'   type='hidden'  value='http://opticaangeles.com/compra'>
                    <input name='confirmationUrl'    type='hidden'  value='http://opticaangeles.com/respuesta'>
                    <input name='Submit' id='$fecha' type='submit'  value='Pagar con PayU' style='display:none'>
                </form>
                <div id='espera-transaccion'> <div class='cargando-pago'></div>VERIFICANDO INFORMACIÓN<br>Pronto serás redirigido a PayU Colombia. </div>
                ";

        $datos = ["f" => $form, "id" => $fecha];
        $datos = json_encode($datos);

        return $datos;
    }


    public function respuesta(Request $request)
    {
        dd('pendiente');
        DB::table('pruebapago')->insert(
            ['nombre' => 'prueba', 'transactionState' => 'prueba', 'telefono' => 'prueba', 'direccion' => 'prueba', 'descripcion' => 'prueba', 'correo' => 'prueba@mail.com', 'monto' => 120, 'referencia' => 'prueba']
        );
    }


    public function compra(Request $request)
    {
        $productos = DB::table('productos_vendidos')->where('referencia', '=', $request->referenceCode)->get();
     //     dd($request);


        if ($request->transactionState == "4" || $request->transactionState == "7") {
            # Aprobada o Pendiente...
            DB::table('pruebapago')->insert(
                ['nombre' => $request->extra1, 'id_user' => Auth::id(), 'transactionState' => $request->transactionState, 'telefono' => $request->extra2, 'direccion' => $request->extra3, 'descripcion' => $request->description, 'correo' => $request->buyerEmail, 'monto' => $request->TX_VALUE, 'referencia' => $request->referenceCode]
            );
            foreach ($productos as $producto) {
                $producto_bodegas = DB::table('producto_bodegas')->where('id_producto', '=', $producto->id_producto)->get();
             //   dd($producto_bodegas);
                foreach ($producto_bodegas as $producto_bodega) {
                    # code...
                    $data['cantidad'] = $producto_bodega->cantidad - $producto->cantidad;
                    DB::table('producto_bodegas')
                        ->where('id_producto', $producto->id_producto)->update($data);
                }
            }
        } elseif ($request->transactionState == "6") {
            # Rechazada o error...
            //  dd($request);
            DB::table('pruebapago')->insert(
                ['nombre' => $request->extra1, 'id_user' => Auth::id(), 'transactionState' => $request->transactionState, 'telefono' => $request->extra2, 'direccion' => $request->extra3, 'descripcion' => $request->description, 'correo' => $request->buyerEmail, 'monto' => $request->TX_VALUE, 'referencia' => $request->referenceCode]
            );
        }

        return view('compra', compact('request'));
    }
}
