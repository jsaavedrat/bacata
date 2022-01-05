<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use File;
use Carbon\Carbon;

class VentasController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::CREAR VENTA::::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(Request $request){
        
        if   (isset($request->cotizacion)){ $cotizacion = $request->cotizacion;}
        else {$cotizacion=null;}
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}


        $cotizacion = DB::table('cotizaciones')
        ->where('id_cotizacion','=',$request->cotizacion)
        ->where('estado_venta_cotizacion','=','pendiente')
        ->where('estado_cotizacion','=','activo')
        ->leftJoin('clientes','clientes.id_cliente','=','cotizaciones.id_cliente')
        ->first();

        //dd($cotizacion);

        if (isset($cotizacion)) {
            $lente = DB::table('productos')
            ->where('id_producto','=',$cotizacion->id_lente)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            $cotizacion->nombre_lente = $lente->nombre_tipo_producto . " " . $lente->nombre_marca . " " . $lente->nombre_modelo;
            $cotizacion->iva_lente = $lente->iva;

            $montura = DB::table('productos')
            ->where('id_producto','=',$cotizacion->id_montura)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            // dd($montura);

            if(isset($montura)){
                $cotizacion->nombre_tipo_producto = $montura->nombre_tipo_producto;
                $cotizacion->nombre_marca = $montura->nombre_marca;
                $cotizacion->nombre_modelo = $montura->nombre_modelo;
                $cotizacion->declara_iva = $montura->declara_iva;
            }else{
                $cotizacion->nombre_tipo_producto = "Montura";
                $cotizacion->nombre_marca = " ";
                $cotizacion->nombre_modelo = "Cliente";
                $cotizacion->declara_iva = "no";
            }
        }

        $mensaje="";
        $id_user = Auth::id();
        $roles = DB::table('model_has_roles')
        ->where('model_type','=','App\User')
        ->where('model_id','=',$id_user)
        ->select('role_id')
        ->get();

        $todas = false;
        foreach ($roles as $rol) {
            if ($rol->role_id == 1 || $rol->role_id == 2) {
                $todas = true;
            }
        }

        if($todas == false){

            $usuarios_sucursales = DB::table('usuarios_sucursales')
            ->where('usuarios_sucursales.id_usuario','=',$id_user)
            ->where('usuarios_sucursales.estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.id_sucursal','!=',0)
            ->where('sucursals.estado_sucursal','=','activo')
            ->select('usuarios_sucursales.id_sucursal','sucursals.nombre_sucursal','sucursals.nombre_imagen_sucursal')
            ->get();

        }else{
            $usuarios_sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $tipos_identificacion = DB::table('tipos_identificacion')
        ->where('estado_tipo_identificacion','=','activo')
        ->get();

        $tipos_pagos = DB::table('tipos_pagos')
        ->where('estado_tipo_pago','=','activo')
        ->get();

        $iva = DB::table('iva')
        ->where('estado_iva','=','activo')
        ->orderBy('id_iva','desc')
        ->first();

        // dd($cotizacion);

        return view('ventas.crear',compact('usuarios_sucursales','tipos_identificacion','mensaje','cotizacion','tipos_pagos','iva','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::CREAR VENTA:::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){
        
        $venta = json_decode($request->venta_final);

        // dd($venta);

        $valid_sucursal = false;
        $valid_cliente = false;
        $valid_productos = false;
        $valid_montos = false;
        $valid_total_venta = false;
        $valid_total_abono = false;

        if ($venta->sucursal->id_sucursal != null && $venta->sucursal->id_sucursal != "") {//hay que verificar si el usuario puede
                $valid_sucursal = true;
        }

        if ($venta->cliente->id_cliente != null && $venta->cliente->id_cliente != "") {
                $valid_cliente = true;
        }elseif (
                $venta->cliente->tipo_identificacion != null && $venta->cliente->tipo_identificacion != "" &&
                $venta->cliente->identificacion != null && $venta->cliente->identificacion != "" &&
                $venta->cliente->nombres != null && $venta->cliente->nombres != "" &&
                $venta->cliente->apellidos != null && $venta->cliente->apellidos != ""
        ){
                $valid_cliente = true;
        }

        //dd($venta->productos);

        $configuracion_cotizacion = DB::table('configuracion_cotizaciones')
        ->first();

        if (count($venta->productos) > 0) {
                //dd("paso");
                $valid_productos = true;

                $aux_productos = [];
                foreach ($venta->productos as $producto) {
                    if($producto->id_producto != null){
                        $enc = false;
                        foreach($aux_productos as $aux_producto){
                            if($aux_producto->id_producto == $producto->id_producto){
                                $enc = true;
                            }
                        }
                        if($enc == false){
                            $pr = [
                                'id_producto' => $producto->id_producto,
                                'cantidad' => $producto->cantidad
                            ];
                            $pr = json_encode($pr);
                            $pr = json_decode($pr);
                            array_push($aux_productos, $pr);
                        }else{
                            foreach($aux_productos as $aux_producto){
                                if($aux_producto->id_producto == $producto->id_producto){
                                    $aux_producto->cantidad = $aux_producto->cantidad + $producto->cantidad;
                                }
                            }
                        }
                    }
                }

                // dd($aux_productos,$valid_productos,$venta->sucursal->id_sucursal);

                foreach ($aux_productos as $aux_producto) {

                        $producto_sucursal = DB::table('producto_sucursales')
                        ->where('id_producto','=',$aux_producto->id_producto)
                        ->where('id_sucursal','=',$venta->sucursal->id_sucursal)
                        ->first();

                        if(isset($producto_sucursal)){
                            
                            if($aux_producto->cantidad > $producto_sucursal->cantidad){
                                $valid_productos = false;
                                $aux_valid_productos = "errorAgotado";
                                // dd("no alcanzan: ",$aux_producto,$producto_sucursal);
                            }
                            
                        }else{

                            //COMO ES NULO VERIFICAR SI ES LENTE
                            $producto_verificar_lente = DB::table('producto_sucursales')//prueba excluir lentes AJUSATADA, YA HABIA CONSULTA
                            ->where('producto_sucursales.id_producto','=',$aux_producto->id_producto)
                            ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
                            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                            ->first();

                            if ($producto_verificar_lente->id_tipo_producto != $configuracion_cotizacion->id_tipo_producto_lentes) {//prueba excluir lentes
                                $valid_productos = false;
                                $aux_valid_productos = "errorExistencia";
                                // dd("no existe en esa sucursal: ",$aux_producto);
                            }//prueba excluir lentes
                        }
                }
                //dd($aux_productos,$valid_productos,$venta->productos); 
        }

        if (count($venta->montos) > 0) {
            $valid_montos = true;
        }

        if (is_numeric($venta->total_venta)) {
            $valid_total_venta = true;
        }

        if (is_numeric($venta->total_abono)) {
            $valid_total_abono = true;
        }


        // dd($valid_sucursal,$valid_cliente,$valid_productos,$valid_montos,$valid_total_venta,$valid_total_abono);

        if($valid_sucursal == true){

            if($valid_cliente == true){
            
                if($valid_productos == true){
            
                    if($valid_montos == true){
                
                        if($valid_total_venta == true){
                    
                            if($valid_total_abono == true){

/*________________________________________________EJECUCION DE LA VENTA___________________________________________________*/

                                $date = Carbon::now();
                                $id_user = Auth::id();                

                                if($venta->cliente->id_cliente == null || $venta->cliente->id_cliente == ""){
                                        $id_cliente = DB::table('clientes')->insertGetId([
                                            'id_tipo_identificacion' => $venta->cliente->tipo_identificacion,
                                            'identificacion' => $venta->cliente->identificacion,
                                            'nombres' => $venta->cliente->nombres,
                                            'apellidos' => $venta->cliente->apellidos,
                                            'telefono' => $venta->cliente->telefono,
                                            'email' => $venta->cliente->correo
                                        ]);
                                }else{
                                        $id_cliente = $venta->cliente->id_cliente;
                                }

                                if($venta->total_abono >= $venta->total_venta){
                                    $estado_venta = 'vendido';
                                }else{
                                    $estado_venta = 'pendiente';
                                }

                                $id_venta = DB::table('ventas')->insertGetId([
                                    'id_cliente' => $id_cliente,
                                    'id_sucursal' => $venta->sucursal->id_sucursal,
                                    'monto_total' => $venta->total_venta,
                                    'monto_abonado' => $venta->total_abono,
                                    'fecha_venta' => $date,
                                    'id_usuario_venta' => $id_user,
                                    'estado_venta' => $estado_venta
                                ]);

                                $cotizaciones = [];
                                // dd($venta->productos);
                                foreach ($venta->productos as $producto) {

                                    $tipo_producto = DB::table('productos')//prueba excluir lentes
                                    ->where('productos.id_producto','=',$producto->id_producto)
                                    ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                                    ->first();

                                    if($producto->id_producto != null){

                                            DB::table('producto_ventas')->insert([
                                                'id_venta' => $id_venta,
                                                'id_producto' => $producto->id_producto,
                                                'cantidad' => $producto->cantidad,
                                                'precio_venta' => $producto->precio,
                                                'iva' => $producto->iva
                                            ]);

                                            if($venta->cotizacion == true){
                                                if ($producto->cotizacion != null) {
                                                    array_push($cotizaciones, $producto->cotizacion);
                                                }
                                            }

                                            $producto_sucursal = DB::table('producto_sucursales')
                                            ->where('id_producto','=',$producto->id_producto)
                                            ->where('id_sucursal','=',$venta->sucursal->id_sucursal)
                                            ->first();

                                            if ($tipo_producto->id_tipo_producto != $configuracion_cotizacion->id_tipo_producto_lentes) {//prueba excluir lentes

                                                if(isset($producto_sucursal)){
                                                    $cantidad_actualizada = $producto_sucursal->cantidad - $producto->cantidad;

                                                    DB::table('producto_sucursales')
                                                    ->where('id_producto','=',$producto->id_producto)
                                                    ->where('id_sucursal','=',$venta->sucursal->id_sucursal)
                                                    ->update([
                                                        'cantidad' => $cantidad_actualizada
                                                    ]);
                                                }

                                            }//prueba excluir lentes
                                    }
                                    
                                }

                                if($venta->produccion == true){
                                    $produccion = 'pendiente';
                                }else{
                                    $produccion = 'apartado';
                                }
                                $cotizaciones = array_unique($cotizaciones);
                                foreach ($cotizaciones as $cotizacion) {

                                    DB::table('ordenes')->insert([
                                        'id_cotizacion' => $cotizacion,
                                        'id_venta' => $id_venta,
                                        'id_sucursal' => $venta->sucursal->id_sucursal,
                                        'estado_orden' => $produccion,
                                        'id_usuario_registro' => $id_user
                                    ]);

                                    DB::table('cotizaciones')
                                    ->where('id_cotizacion','=',$cotizacion)
                                    ->update([
                                        'estado_venta_cotizacion' => 'vendido'
                                    ]);
                                }

                                foreach ($venta->montos as $monto) {
                                    DB::table('pagos')->insert([
                                        'id_venta' => $id_venta,
                                        'id_usuario_registro' => $id_user,
                                        'id_tipo_pago' => $monto->id_tipo_pago,
                                        'monto' => $monto->monto_pago,
                                        'fecha_pago' => $date
                                    ]);
                                }

                                return redirect()->route('ventas.ver',['ver' => $id_venta]);

/*________________________________________________________________________________________________________________________*/

                            }else{
                                $estatus = "errorTotalAbono";
                                return redirect()->route('ventas.crear',['estatus' => $estatus]);
                            }
                        }else{
                            $estatus = "errorTotalVenta";
                            return redirect()->route('ventas.crear',['estatus' => $estatus]);
                        }
                    }else{
                        $estatus = "errorMontos";
                        return redirect()->route('ventas.crear',['estatus' => $estatus]);
                    }
                }else{
                        $estatus = "errorProductos";
                        if (isset($aux_valid_productos)){
                            $estatus = $aux_valid_productos;
                        }
                        return redirect()->route('ventas.crear',['estatus' => $estatus]);
                }
            }else{
                $estatus = "errorCliente";
                return redirect()->route('ventas.crear',['estatus' => $estatus]);
            }
        }else{
            $estatus = "errorSucursal";
            return redirect()->route('ventas.crear',['estatus' => $estatus]);
        }

    }



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::RESULTADO VENTA::::::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){

        $cliente_appweb = DB::table('cliente_appweb')
        ->where('estado_cliente_appweb','=','activo')
        ->first();

        if (!isset($cliente_appweb)){
            dd("Debe crear el Cliente SAS, Contactar App Web");
        }
        
        $venta = DB::table('ventas')
        ->where('id_venta','=',$request->ver)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ventas.id_sucursal')
        ->leftJoin('users','users.id','=','ventas.id_usuario_venta')
        ->first();

        if (isset($venta)) {

            $cliente = DB::table('clientes')
            ->where('id_cliente','=',$venta->id_cliente)
            ->leftJoin('tipos_identificacion','tipos_identificacion.id_tipo_identificacion','=','clientes.id_tipo_identificacion')
            ->first();

            if (isset($cliente)) {
                $venta->nombre_tipo_identificacion_cliente = $cliente->nombre_tipo_identificacion;
                $venta->identificacion_cliente = $cliente->identificacion;
                $venta->nombres_cliente = $cliente->nombres;
                $venta->apellidos_cliente = $cliente->apellidos;
                $venta->telefono_cliente = $cliente->telefono;
            }

            $productos_venta = DB::table('producto_ventas')
            ->where('id_venta','=',$request->ver)
            ->leftJoin('productos','productos.id_producto','=','producto_ventas.id_producto')
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->get();

            foreach ($productos_venta as $producto_venta) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('id_producto','=',$producto_venta->id_producto)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();

                $len = count($especificaciones);
                $especificaciones_producto = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_producto = $especificaciones_producto . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_producto = $especificaciones_producto . $especificacion->nombre_especificacion . ", ";
                    }
                }

                $producto_venta->sub = $producto_venta->precio_venta * $producto_venta->cantidad;

                $producto_venta->especificaciones = $especificaciones_producto;
            }

            $venta->productos = $productos_venta;

            $venta->numero_venta = str_pad($venta->id_venta, 7, "0", STR_PAD_LEFT);

            $venta->saldo_restante = $venta->monto_total - $venta->monto_abonado;
            //dd($venta->saldo_restante);

            $ordenes = DB::table('ordenes')
            ->where('id_venta','=',$request->ver)
            ->get();

            if (count($ordenes) > 0) {
                $venta->ordenes = true;
            }else{
                $venta->ordenes = false;
            }

            $id_user = Auth::id();
            $roles = DB::table('model_has_roles')
            ->where('model_type','=','App\User')
            ->where('model_id','=',$id_user)
            ->select('role_id')
            ->get();

            $todas = false;
            foreach ($roles as $rol) {
                if ($rol->role_id == 1 || $rol->role_id == 2) {
                    $todas = true;
                }
            }

            if($todas == false){

                $usuarios_sucursales = DB::table('usuarios_sucursales')
                ->where('usuarios_sucursales.id_usuario','=',$id_user)
                ->where('usuarios_sucursales.estado_usuario_sucursal','=','activo')
                ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
                ->where('sucursals.id_sucursal','!=',0)
                ->where('sucursals.estado_sucursal','=','activo')
                ->select('usuarios_sucursales.id_sucursal','sucursals.nombre_sucursal','sucursals.nombre_imagen_sucursal')
                ->get();

            }else{
                $usuarios_sucursales = DB::table('sucursals')
                ->where('id_sucursal','!=',0)
                ->where('estado_sucursal','=','activo')
                ->get();
            }
            $existe = false;
            foreach ($usuarios_sucursales as $usuario_sucursal) {
                    if ($usuario_sucursal->id_sucursal == $venta->id_sucursal) {
                        $existe = true;
                    }
            }

            if ($existe == true) {
                //dd($venta);
                    return view('ventas.ver',compact('venta','cliente_appweb'));
            }else{
                $estatus = "errorSucursal";
                return redirect()->route('ventas.lista',['estatus' => $estatus]);   
            }
        }
        $estatus = "errorSucursal";
        return redirect()->route('ventas.lista',['estatus' => $estatus]);   
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::BUSCAR SUCURSAL::::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursal(Request $request){//AJAX

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$request->id)
        ->where('estado_sucursal','=','activo')
        ->where('id_sucursal','!=',0)
        ->first();

        $iva = DB::table('iva')
        ->where('estado_iva','=','activo')
        ->select('porcentaje_iva')
        ->first();

        $sucursal->ivaActual = $iva->porcentaje_iva;

        $sucursal = json_encode($sucursal);

        return ($sucursal);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::BUSCAR CLIENTE::::::::::::::::::::::::::::::::::::::::::::::*/


    public function cliente(Request $request){//AJAX

        $cliente = DB::table('clientes')
        ->where('id_tipo_identificacion','=',$request->tipo_identificacion)
        ->where('identificacion','=',$request->identificacion)
        ->first();

        if (isset($cliente)) {
            $cotizaciones = DB::table('cotizaciones')
            ->where('id_cliente','=',$cliente->id_cliente)
            ->where('estado_venta_cotizacion','=','pendiente')
            ->where('estado_cotizacion','=','activo')
            ->where('id_sucursal','=',$request->id_sucursal)
            ->orderBy('id_cotizacion','desc')
            ->leftJoin('productos','productos.id_producto','=','cotizaciones.id_montura')
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->get();

            foreach ($cotizaciones as $cotizacion) {

                $lente = DB::table('productos')
                ->where('id_producto','=',$cotizacion->id_lente)
                ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                ->first();

                $cotizacion->nombre_lente = $lente->nombre_tipo_producto . " " . $lente->nombre_marca . " " . $lente->nombre_modelo;
                $cotizacion->iva_lente = $lente->iva;
            }
            $cliente->cotizaciones = $cotizaciones;
        }

        $cliente = json_encode($cliente);

        return ($cliente);
    }




/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::BUSCAR PRODUCTO::::::::::::::::::::::::::::::::::::::::::::::*/


    public function producto(Request $request){//AJAX

        $producto = DB::table('producto_sucursales')
        ->where('id_sucursal','=',$request->id)
        ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
        ->where('productos.code128','=',$request->producto)
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->select('productos.id_producto','productos.declara_iva','productos.precio_base','modelos.nombre_modelo','tipo_productos.nombre_tipo_producto','tipo_productos.id_tipo_producto','marcas.nombre_marca','producto_sucursales.cantidad')
        ->first();

        if(isset($producto)){//1

                $imagen = DB::table('imagen_productos')
                ->where('id_producto','=',$request->producto)
                ->first();
                if(isset($imagen)){ $producto->imagen = $imagen->nombre_imagen;}
                else{    $producto->imagen = "default.png";                    }

                $producto_especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$producto->id_producto)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->select('especificaciones.nombre_especificacion')
                ->get();

                $especificaciones = "";
                foreach ($producto_especificaciones as $producto_especificacion) {
                        $especificaciones = $especificaciones . ", " . $producto_especificacion->nombre_especificacion;
                }
                $producto->especificaciones = $especificaciones;

                $promocion_producto = DB::table('promocion_productos')
                ->where('id_producto','=',$producto->id_producto)
                ->where('estado_promocion_producto','=','activo')
                ->leftJoin('promociones','promociones.id_promocion','=','promocion_productos.id_promocion')
                ->first();

                if(isset($promocion_producto)){
                        $precio_promocion = $producto->precio_base - (($producto->precio_base * $promocion_producto->porcentaje_descuento) / 100);
                        $producto->precio_promocion = $precio_promocion;
                        $producto->porcentaje_descuento = $promocion_producto->porcentaje_descuento;
                        $producto->nombre_promocion = $promocion_producto->nombre_promocion;
                }

                if($producto->cantidad < 1){//1.

                        $producto_sucursales = DB::table('producto_sucursales')
                        ->where('producto_sucursales.id_producto','=',$producto->id_producto)
                        ->where('producto_sucursales.cantidad','>',0)
                        ->where('sucursals.estado_sucursal','=','activo')
                        ->where('sucursals.id_sucursal','!=',0)
                        ->where('sucursals.id_sucursal','!=',$request->id)
                        ->leftJoin('sucursals','sucursals.id_sucursal','=','producto_sucursales.id_sucursal')
                        ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
                        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                        //->select('productos.id_producto','productos.declara_iva','productos.precio_base','modelos.nombre_modelo','tipo_productos.nombre_tipo_producto','marcas.nombre_marca')
                        ->get();
                        $producto->sucursales = $producto_sucursales;

                        if($producto_sucursales == "[]"){//1.3
                            $producto->agotado = "si";
                        }else{
                            $producto->agotado = "no";//1.2
                        }

                }else{//1.1

                }

        }else{//2

                $producto = DB::table('productos')
                ->where('productos.code128','=',$request->producto)
                ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                ->select('productos.id_producto','productos.declara_iva','productos.precio_base','modelos.nombre_modelo','tipo_productos.nombre_tipo_producto','tipo_productos.id_tipo_producto','marcas.nombre_marca')
                ->first();

                if(isset($producto)){//2.1

                        $imagen = DB::table('imagen_productos')
                        ->where('id_producto','=',$request->producto)
                        ->first();
                        if(isset($imagen)){ $producto->imagen = $imagen->nombre_imagen;}
                        else{    $producto->imagen = "default.png";                    }

                        $producto_especificaciones = DB::table('producto_especificaciones')
                        ->where('producto_especificaciones.id_producto','=',$producto->id_producto)
                        ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                        ->select('especificaciones.nombre_especificacion')
                        ->get();

                        $especificaciones = "";
                        foreach ($producto_especificaciones as $producto_especificacion) {
                                $especificaciones = $especificaciones . ", " . $producto_especificacion->nombre_especificacion;
                        }
                        $producto->especificaciones = $especificaciones;

                        $promocion_producto = DB::table('promocion_productos')
                        ->where('id_producto','=',$producto->id_producto)
                        ->where('estado_promocion_producto','=','activo')
                        ->leftJoin('promociones','promociones.id_promocion','=','promocion_productos.id_promocion')
                        ->first();

                        if(isset($promocion_producto)){
                                $precio_promocion = $producto->precio_base - (($producto->precio_base * $promocion_producto->porcentaje_descuento) / 100);
                                $producto->precio_promocion = $precio_promocion;
                                $producto->porcentaje_descuento = $promocion_producto->porcentaje_descuento;
                                $producto->nombre_promocion = $promocion_producto->nombre_promocion;
                        }

                        $producto_sucursales = DB::table('producto_sucursales')
                        ->where('producto_sucursales.id_producto','=',$producto->id_producto)
                        ->where('sucursals.estado_sucursal','=','activo')
                        ->where('sucursals.id_sucursal','!=',0)
                        ->where('sucursals.id_sucursal','!=',$request->id)
                        ->leftJoin('sucursals','sucursals.id_sucursal','=','producto_sucursales.id_sucursal')
                        ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
                        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                        //->select('productos.id_producto','productos.declara_iva','productos.precio_base','modelos.nombre_modelo','tipo_productos.nombre_tipo_producto','marcas.nombre_marca')
                        ->get();

                        $producto->sucursales = $producto_sucursales;

                        $producto->agotado = "no-disponible";

                }else{//2.2
                        $producto = "vacio";
                }
        }
        

        

        $producto = json_encode($producto);

        return ($producto);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::LISTA DE COTIZACIONES::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

        // dd("lista");

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $id_user = Auth::id();
        $roles = DB::table('model_has_roles')
        ->where('model_type','=','App\User')
        ->where('model_id','=',$id_user)
        ->select('role_id')
        ->get();

        $todas = false;
        foreach ($roles as $rol) {
            if ($rol->role_id == 1 || $rol->role_id == 2) {
                $todas = true;
            }
        }

        if($todas == false){

            $usuarios_sucursales = DB::table('usuarios_sucursales')
            ->where('usuarios_sucursales.id_usuario','=',$id_user)
            ->where('usuarios_sucursales.estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.id_sucursal','!=',0)
            ->where('sucursals.estado_sucursal','=','activo')
            ->select('usuarios_sucursales.id_sucursal','sucursals.nombre_sucursal','sucursals.nombre_imagen_sucursal')
            ->get();

        }else{
            $usuarios_sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $ventas = DB::table('ventas')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ventas.id_sucursal')
        ->leftJoin('users','users.id','=','ventas.id_usuario_venta')
        ->orderBy('id_venta','desc')
        ->get();

        $aux_ventas = [];
        foreach ($ventas as $venta) {

                $pertenece = false;
                foreach ($usuarios_sucursales as $usuario_sucursal) {
                    if ($usuario_sucursal->id_sucursal == $venta->id_sucursal) {
                        array_push($aux_ventas, $venta);
                    }
                }
        }

        $ventas = $aux_ventas;

        foreach ($ventas as $venta) {

            $cliente = DB::table('clientes')
            ->where('id_cliente','=',$venta->id_cliente)
            ->leftJoin('tipos_identificacion','tipos_identificacion.id_tipo_identificacion','=','clientes.id_tipo_identificacion')
            ->first();
            if (isset($cliente)) {
                $venta->nombre_tipo_identificacion_cliente = $cliente->nombre_tipo_identificacion;
                $venta->identificacion_cliente = $cliente->identificacion;
                $venta->nombres_cliente = $cliente->nombres;
                $venta->apellidos_cliente = $cliente->apellidos;
            }
            $venta->saldo = $venta->monto_total - $venta->monto_abonado;
        }
        //dd($ventas);

        return view('ventas.lista',compact('ventas','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::LISTA DE COTIZACIONES::::::::::::::::::::::::::::::::::::::::::::*/


    public function saldo(Request $request){
        //dd($request);

        $venta = DB::table('ventas')
        ->where('id_venta',$request->pagar)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ventas.id_sucursal')
        ->leftJoin('users','users.id','=','ventas.id_usuario_venta')
        ->first();

        if (isset($venta)) {

            $cliente = DB::table('clientes')
            ->where('id_cliente','=',$venta->id_cliente)
            ->leftJoin('tipos_identificacion','tipos_identificacion.id_tipo_identificacion','=','clientes.id_tipo_identificacion')
            ->first();

            if (isset($cliente)) {
                $venta->nombre_tipo_identificacion_cliente = $cliente->nombre_tipo_identificacion;
                $venta->identificacion_cliente = $cliente->identificacion;
                $venta->nombres_cliente = $cliente->nombres;
                $venta->apellidos_cliente = $cliente->apellidos;
                $venta->telefono_cliente = $cliente->telefono;
            }

            $productos_venta = DB::table('producto_ventas')
            ->where('id_venta','=',$request->pagar)
            ->leftJoin('productos','productos.id_producto','=','producto_ventas.id_producto')
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->get();

            foreach ($productos_venta as $producto_venta) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('id_producto','=',$producto_venta->id_producto)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();

                $len = count($especificaciones);
                $especificaciones_producto = "";
                for($i = 0; $i < $len; $i++){
                    $especificaciones_producto .= $especificaciones[$i]->nombre_especificacion;
                    if($i < ($len-1)){
                        $especificaciones_producto .= ", ";
                    }
                }
                $producto_venta->sub = $producto_venta->precio_venta * $producto_venta->cantidad;
                $producto_venta->especificaciones = $especificaciones_producto;
            }

            $venta->productos = $productos_venta;

            $venta->numero_venta = str_pad($venta->id_venta, 7, "0", STR_PAD_LEFT);

            $venta->saldo_restante = $venta->monto_total - $venta->monto_abonado;

            $ordenes = DB::table('ordenes')
            ->where('id_venta','=',$request->pagar)
            ->get();

            if (count($ordenes) > 0) {
                $venta->ordenes = "true";
                $venta->lista_ordenes = $ordenes;
            }else{
                $venta->ordenes = "false";
                $venta->lista_ordenes = null;
            }

            $id_user = Auth::id();
            $roles = DB::table('model_has_roles')
            ->where('model_type','=','App\User')
            ->where('model_id','=',$id_user)
            ->select('role_id')
            ->get();

            $todas = false;
            foreach ($roles as $rol) {
                if ($rol->role_id == 1 || $rol->role_id == 2) {
                    $todas = true;
                }
            }

            if($todas == false){

                $usuarios_sucursales = DB::table('usuarios_sucursales')
                ->where('usuarios_sucursales.id_usuario','=',$id_user)
                ->where('usuarios_sucursales.estado_usuario_sucursal','=','activo')
                ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
                ->where('sucursals.id_sucursal','!=',0)
                ->where('sucursals.estado_sucursal','=','activo')
                ->select('usuarios_sucursales.id_sucursal','sucursals.nombre_sucursal','sucursals.nombre_imagen_sucursal')
                ->get();

            }else{
                $usuarios_sucursales = DB::table('sucursals')
                ->where('id_sucursal','!=',0)
                ->where('estado_sucursal','=','activo')
                ->get();
            }
            $tipos_pagos = DB::table('tipos_pagos')
            ->where('estado_tipo_pago','=','activo')
            ->get();

            $existe = false;
            foreach ($usuarios_sucursales as $usuario_sucursal) {
                    if ($usuario_sucursal->id_sucursal == $venta->id_sucursal) {
                        $existe = true;
                    }
            }

            //dd($venta->ordenes);
            if ($existe == true) {
                    return view('ventas.saldo',compact('venta','tipos_pagos'));
            }else{
                    $estatus = "errorSucursal";
                    return redirect()->route('ventas.lista',['estatus' => $estatus]);   
            }
        }
        return redirect()->route('ventas.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::PAGAR SALDO GABRIEL::::::::::::::::::::::::::::::::::::::::::::::*/


    // public function pagar(Request $request, $venta_id){

    //     $id_user = Auth::id();
    //     $venta = json_decode($request->saldo_final);
    //     $date = Carbon::now();

    //     dd($request,$venta,$venta->montos,$venta_id);

    //     // foreach ($venta->montos as $monto) {            //Recorro los tipos de pago y montos y los guardo
    //     //     DB::table('pagos')->insert([
    //     //         'id_venta' => $venta_id,
    //     //         'id_usuario_registro' => $id_user,
    //     //         'id_tipo_pago' => $monto->id_tipo_pago,
    //     //         'monto' => $monto->monto_pago,
    //     //         'fecha_pago' => $date
    //     //     ]);
    //     // }

    //     $pagos = DB::table('pagos')//obtengo el historial de los pagos de esa venta junto con los nuevos agregados
    //     ->where('id_venta',$venta_id)
    //     ->get();

    //     $venta = DB::table('ventas')//obtengo la venta actual
    //     ->where('id_venta',$venta_id)
    //     ->first();

    //     $monto_abonado_total=0;
    //     $estado_venta = 'pendiente';
    //     $fecha_pago = NULL;
    //     foreach($pagos as $pago){
    //         $monto_abonado_total += $pago->monto;
    //     }
    //     if($venta->monto_total <= $monto_abonado_total){
    //         $estado_venta = 'vendido';
    //         $fecha_pago = Carbon::now();
    //     }

    //     DB::table('ventas')
    //         ->where('id_venta',$venta_id)
    //         ->update([
    //             'monto_abonado' => $monto_abonado_total,
    //             'estado_venta' => $estado_venta,
    //             'fecha_pago' => $fecha_pago]);

    //     return redirect()->route('ventas.ver',['ver' => $venta_id]);

    // }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::PAGAR SALDO JOSE MANUEL::::::::::::::::::::::::::::::::::::::::*/


    public function pagar(Request $request){

        $pagos = json_decode($request->saldo_final);
        $id_user = Auth::id();
        $date = Carbon::now();

        // dd($pagos);

        $venta = DB::table('ventas')//obtengo la venta actual
        ->where('id_venta',$pagos->venta)
        ->first();

        $monto_abonado_nuevo = floatval($venta->monto_abonado) + floatval($pagos->total_abono);//obtengo el nuevo valor abonado

        if($venta->monto_total <= $monto_abonado_nuevo){
            DB::table('ventas')
            ->where('id_venta','=',$pagos->venta)
            ->update([
                'monto_abonado' => $monto_abonado_nuevo,
                'estado_venta' => 'vendido',
                'fecha_pago' => $date
            ]);
        }else{
            DB::table('ventas')
            ->where('id_venta','=',$pagos->venta)
            ->update([
                'monto_abonado' => $monto_abonado_nuevo
            ]);
        }

        $ordenes_venta = DB::table('ordenes')
        ->where('id_venta',$pagos->venta)
        ->get();

        // dd($ordenes_venta);

        if ($pagos->produccion == true) {
            foreach ($ordenes_venta as $orden_venta) {
                if ($orden_venta->estado_orden == "apartado") {
                    DB::table('ordenes')
                    ->where('id_orden','=',$orden_venta->id_orden)
                    ->update([
                        'estado_orden' => 'pendiente',
                    ]);
                }
            }
        }

        foreach ($pagos->montos as $monto) {            //Recorro los tipos de pago y montos y los guardo
            DB::table('pagos')->insert([
                'id_venta' => $pagos->venta,
                'id_usuario_registro' => $id_user,
                'id_tipo_pago' => $monto->id_tipo_pago,
                'monto' => $monto->monto_pago,
                'fecha_pago' => $date
            ]);
        }

        return redirect()->route('ventas.ver',['ver' => $pagos->venta]);

        // dd($pagos,$venta,$monto_total_nuevo);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::VENTAS CONCRETADAS::::::::::::::::::::::::::::::::::::::::::::*/


    public function ventas_dia(){

        $ventas = DB::table('ventas')
        ->where('estado_venta','=','vendido')
        ->join('clientes', 'ventas.id_cliente', '=', 'clientes.id_cliente')
        ->join('tipos_identificacion','clientes.id_tipo_identificacion','=','tipos_identificacion.id_tipo_identificacion')
        ->join('sucursals','ventas.id_sucursal','=','sucursals.id_sucursal')
        ->orderBy('id_venta','desc')
        ->get();
        
        return view('ventas.lista_dia', ['ventas'=>$ventas]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::CIERRE CAJA::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function cierre_caja(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $date = Carbon::now();
        $hoy = $date->toDateString();

        $id_user = Auth::id();
        $roles = DB::table('model_has_roles')
        ->where('model_type','=','App\User')
        ->where('model_id','=',$id_user)
        ->select('role_id')
        ->get();

        $todas = false;
        foreach ($roles as $rol) {
            if ($rol->role_id == 1 || $rol->role_id == 2) {
                $todas = true;
            }
        }

        if($todas == false){

            $usuarios_sucursales = DB::table('usuarios_sucursales')
            ->where('usuarios_sucursales.id_usuario','=',$id_user)
            ->where('usuarios_sucursales.estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.id_sucursal','!=',0)
            ->where('sucursals.estado_sucursal','=','activo')
            ->select('usuarios_sucursales.id_sucursal','sucursals.nombre_sucursal','sucursals.nombre_imagen_sucursal')
            ->get();

        }else{
            $usuarios_sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        return view('ventas.cerrar_caja',compact('usuarios_sucursales','hoy','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::CIERRE CAJA GUARDAR::::::::::::::::::::::::::::::::::::::::::::*/


    public function cierre_caja_guardar(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $monto = str_replace(",", ".", $request->gran_total);

        DB::table('cierre_cajas')->insert([
            'id_usuario_registro' => Auth::id(),
            'id_sucursal' => $request->sucursal,
            'monto_cierre_caja' => $monto,
            'fecha_cierre_caja' => Carbon::now(),
            'estado_cierre_caja' => 'activo'
        ]);

        $estatus = "exito";

        return redirect()->route('caja.cierre.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR REGISTROS CAJA::::::::::::::::::::::::::::::::::::::::::*/


    public function caja_buscar_registros(Request $request){

        $date = Carbon::now();
        $hoy = $date->toDateString();
        // $hoy = "2021-02-20";
        $hoy_inicio = $hoy.' 00:00:00';
        $hoy_fin = $hoy.' 23:59:59';

        $transacciones_caja = [];
        $tipos_pagos_encontrados = [];
        $montos_tipos_pagos = [];
        $total_caja = 0;
        $total_ventas = 0;
        $total_ingresos = 0;
        $total_efectivo = 0;
        $total_egresos = 0;

        $ingresos_egresos = DB::table('ingresos_egresos_cajas')
        ->where('id_sucursal','=',$request->sucursal)
        ->where('fecha_ingreso_egreso','>',$hoy_inicio)
        ->where('fecha_ingreso_egreso','<',$hoy_fin)
        ->leftJoin('users','users.id','=','ingresos_egresos_cajas.id_usuario_registro_ingreso_egreso')
        ->select('monto_ingreso_egreso','fecha_ingreso_egreso','tipo_ingreso_egreso','origen_destino','descripcion_ingreso_egreso','users.name','users.apellido')
        ->get();

        foreach ($ingresos_egresos as $ingreso_egreso) {
            $transaccion_caja = [
                "tipo" => $ingreso_egreso->tipo_ingreso_egreso . " CAJA",
                "descripcion" => $ingreso_egreso->origen_destino . ", " . $ingreso_egreso->descripcion_ingreso_egreso,
                "fecha" => $ingreso_egreso->fecha_ingreso_egreso,
                "hora" => date("h:i:s a", strtotime($ingreso_egreso->fecha_ingreso_egreso)),
                "usuario_registro" => $ingreso_egreso->name . " " . $ingreso_egreso->apellido,
                "monto" => "$ " . number_format($ingreso_egreso->monto_ingreso_egreso, 2, ',', '.')
            ];
            array_push($transacciones_caja, $transaccion_caja);

            if($ingreso_egreso->tipo_ingreso_egreso == "INGRESO"){
                $total_caja = $total_caja + $ingreso_egreso->monto_ingreso_egreso;
                $total_ingresos = $total_ingresos + $ingreso_egreso->monto_ingreso_egreso;
                $total_efectivo = $total_efectivo + $ingreso_egreso->monto_ingreso_egreso;
            }else{
                $total_caja = $total_caja + $ingreso_egreso->monto_ingreso_egreso * (-1);
                $total_egresos = $total_egresos + $ingreso_egreso->monto_ingreso_egreso;
                $total_efectivo = $total_efectivo + $ingreso_egreso->monto_ingreso_egreso * (-1);
            }
        }

        $pagos = DB::table('pagos')
        ->where('id_sucursal','=',$request->sucursal)
        ->where('pagos.fecha_pago','>',$hoy_inicio)
        ->where('pagos.fecha_pago','<',$hoy_fin)
        ->leftJoin('ventas','ventas.id_venta','=','pagos.id_venta')
        ->leftJoin('clientes','clientes.id_cliente','=','ventas.id_cliente')
        ->leftJoin('tipos_pagos','tipos_pagos.id_tipo_pago','=','pagos.id_tipo_pago')
        ->leftJoin('users','users.id','=','pagos.id_usuario_registro')
        ->select('pagos.monto','pagos.fecha_pago','tipos_pagos.id_tipo_pago','tipos_pagos.nombre_tipo_pago','clientes.nombres','clientes.apellidos','users.name','users.apellido')
        ->get();

        foreach ($pagos as $pago) {
            $transaccion_caja = [
                "tipo" => "VENTA / ABONO",
                "descripcion" => $pago->nombre_tipo_pago . ", Cliente: " . $pago->nombres . " " . $pago->apellidos,
                "fecha" => $pago->fecha_pago,
                "hora" => date("h:i:s a", strtotime($pago->fecha_pago)),
                "usuario_registro" => $pago->name . " " . $pago->apellido,
                "monto" => "$ " . number_format($pago->monto, 2, ',', '.')
            ];
            array_push($transacciones_caja, $transaccion_caja);

            $tipo_pago_encontrado = [
                "tipo" => $pago->nombre_tipo_pago,
                "id_tipo_pago" => $pago->id_tipo_pago,
                "monto" => 0
            ];
            array_push($tipos_pagos_encontrados, json_encode($tipo_pago_encontrado));

            $total_caja = $total_caja + $pago->monto;
            $total_ventas = $total_ventas + $pago->monto;

            if($pago->id_tipo_pago == 1){
                $total_efectivo = $total_efectivo + $pago->monto;
            }
        }

        $tipos_pagos_encontrados = array_unique($tipos_pagos_encontrados);
        foreach ($tipos_pagos_encontrados as $tipo_pago_encontrado) {
            $tipo_pago_encontrado = json_decode($tipo_pago_encontrado);
            foreach ($pagos as $pago) {
                if($tipo_pago_encontrado->id_tipo_pago == $pago->id_tipo_pago){
                   $tipo_pago_encontrado->monto = $tipo_pago_encontrado->monto + $pago->monto;
                }
            }
            $tipo_pago_encontrado->monto = "$ " . number_format($tipo_pago_encontrado->monto, 2, ',', '.');
            array_push($montos_tipos_pagos, $tipo_pago_encontrado);
        }

        usort($transacciones_caja, function($a, $b){
            return strcmp($a["fecha"],$b["fecha"]);
        });

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$request->sucursal)
        ->first();

        $data = [
            "registros" => $transacciones_caja,
            "total_caja" => "$ " . number_format($total_caja, 2, ',', '.'),
            "total_ventas" => "$ " . number_format($total_ventas, 2, ',', '.'),
            "total_ingresos" => "$ " . number_format($total_ingresos, 2, ',', '.'),
            "total_efectivo" => "$ " . number_format($total_efectivo, 2, ',', '.'),
            "total_egresos" => "$ " . number_format($total_egresos, 2, ',', '.'),
            "imagen_sucursal" => $sucursal->nombre_imagen_sucursal,
            "tipos_pagos_encontrados" => $montos_tipos_pagos
        ];

        return $data;
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::LISTA CIERRES CAJA::::::::::::::::::::::::::::::::::::::::::::*/


    public function cierre_caja_lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        // dd("cierre caja lista");

        $id_user = Auth::id();
        $roles = DB::table('model_has_roles')
        ->where('model_type','=','App\User')
        ->where('model_id','=',$id_user)
        ->select('role_id')
        ->get();

        $todas = false;
        foreach ($roles as $rol) {
            if ($rol->role_id == 1 || $rol->role_id == 2) {
                $todas = true;
            }
        }

        if($todas == false){

            $usuarios_sucursales = DB::table('usuarios_sucursales')
            ->where('usuarios_sucursales.id_usuario','=',$id_user)
            ->where('usuarios_sucursales.estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.id_sucursal','!=',0)
            ->where('sucursals.estado_sucursal','=','activo')
            ->select('usuarios_sucursales.id_sucursal','sucursals.nombre_sucursal','sucursals.nombre_imagen_sucursal')
            ->get();

        }else{
            $usuarios_sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        return view('ventas.lista_cierres_caja',compact('usuarios_sucursales','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::BUSCAR CIERRES DE CAJA DE SUCURSAL:::::::::::::::::::::::::::::::::::*/


    public function caja_cierres_sucursal(Request $request){//AJAX

        $cierres = DB::table('cierre_cajas')
        ->where('cierre_cajas.id_sucursal','=',$request->sucursal)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','cierre_cajas.id_sucursal')
        ->leftJoin('users','users.id','=','cierre_cajas.id_usuario_registro')
        ->get();

        return $cierres;
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::INGRESO / EGRESO DE CAJA:::::::::::::::::::::::::::::::::::::::::*/


    public function ingresos_egresos_crear(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $id_user = Auth::id();
        $roles = DB::table('model_has_roles')
        ->where('model_type','=','App\User')
        ->where('model_id','=',$id_user)
        ->select('role_id')
        ->get();

        $todas = false;
        foreach ($roles as $rol) {
            if ($rol->role_id == 1 || $rol->role_id == 2) {
                $todas = true;
            }
        }

        if($todas == false){

            $usuarios_sucursales = DB::table('usuarios_sucursales')
            ->where('usuarios_sucursales.id_usuario','=',$id_user)
            ->where('usuarios_sucursales.estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.id_sucursal','!=',0)
            ->where('sucursals.estado_sucursal','=','activo')
            ->select('usuarios_sucursales.id_sucursal','sucursals.nombre_sucursal','sucursals.nombre_imagen_sucursal')
            ->get();

        }else{
            $usuarios_sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        return view('ventas.ingreso_egreso',compact('usuarios_sucursales','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::INGRESO / EGRESO DE CAJA:::::::::::::::::::::::::::::::::::::::::*/


    public function ingresos_egresos_guardar(Request $request){

        $monto = str_replace(",", ".", $request->monto);
        DB::table('ingresos_egresos_cajas')->insert([
            'id_sucursal' => $request->sucursal,
            'tipo_ingreso_egreso' => $request->ingreso_egreso,
            'origen_destino' => $request->origen_destino,
            'descripcion_ingreso_egreso' => $request->descripcion,
            'monto_ingreso_egreso' => $monto,
            'id_usuario_registro_ingreso_egreso' => Auth::id(),
            'fecha_ingreso_egreso' => Carbon::now(),
            'estado_ingreso_egreso' => 'activo'
        ]);

        $estatus = "exito";

        return redirect()->route('caja.ingresos.egresos.crear',['estatus' => $estatus]);
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::DETALLE:::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function detalle($id_venta){

        $venta = DB::table('ventas')
        ->where('id_venta',$id_venta)
        ->join('clientes', 'ventas.id_cliente', '=', 'clientes.id_cliente')
        ->join('tipos_identificacion','clientes.id_tipo_identificacion','=','tipos_identificacion.id_tipo_identificacion')
        ->first();

        if (empty($venta)) {
            return abort(404);
        }

        $productos_venta = DB::table('producto_ventas')
        ->where('id_venta',$id_venta)
        ->leftJoin('productos','productos.id_producto','=','producto_ventas.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->get();

        foreach ($productos_venta as $producto_venta) {
            $especificaciones = DB::table('producto_especificaciones')
            ->where('id_producto',$producto_venta->id_producto)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->get();
            $len = count($especificaciones);
            $especificaciones_producto = "";
            for($i = 0; $i < $len; $i++){
                $especificaciones_producto .= $especificaciones[$i]->nombre_especificacion;
                if($i < ($len-1)){
                    $especificaciones_producto .= ", ";
                }
            }
            $producto_venta->sub = $producto_venta->precio_venta * $producto_venta->cantidad;
            $producto_venta->especificaciones = $especificaciones_producto;
        }
        $venta->productos = $productos_venta;
        return view('ventas.detalle',['venta' => $venta]);
    }




//FIN CONTROLADOR
}
