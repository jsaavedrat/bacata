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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductosImport;

class IngresosController extends Controller
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

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::INGRESO > BODEGA:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::LISTA DE INGRESOS:::::::::::::::::::::::::::::::::::::::::*/


   public function lista(){

        $ingresos = DB::table('ingresos')
        ->leftJoin('proveedores','proveedores.id_proveedor','=','ingresos.id_proveedor')
        ->leftJoin('bodegas','bodegas.id_bodega','=','ingresos.id_bodega')
        ->where('bodegas.id_bodega','!=',0)
        ->select('ingresos.id_ingreso','bodegas.nombre_bodega','ingresos.fecha_ingreso','proveedores.nombre_proveedor')
        ->orderBy('id_ingreso', 'desc')
        ->get();
        
        $producto_ingresos = DB::table('ingresos')
        ->leftJoin('producto_ingresos','producto_ingresos.id_ingreso','=','ingresos.id_ingreso')
        ->get();

        foreach ($ingresos as $ingreso) {
            $c = 0;
            foreach ($producto_ingresos as $producto_ingreso) {
                if ($ingreso->id_ingreso == $producto_ingreso->id_ingreso) {
                    $c = $c + 1;
                }
            }
            $ingreso->cantidad = $c;
        }

        return view('ingresos.lista',compact('ingresos'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::VER DETALLE INGRESO::::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){

        $ingreso = DB::table('ingresos')
        ->where('id_ingreso','=',$request->ver)
        ->leftJoin('bodegas','bodegas.id_bodega','=','ingresos.id_bodega')
        ->where('bodegas.id_bodega','!=',0)
        ->leftJoin('proveedores','proveedores.id_proveedor','=','ingresos.id_proveedor')
        ->first();

        $producto_ingresos = DB::table('producto_ingresos')
        ->where('id_ingreso','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_ingresos.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->get();

        $especificaciones = DB::table('producto_especificaciones')
        ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
        ->get();

        $total = 0;
        foreach ($producto_ingresos as $producto_ingreso) {
            $esp = "";
            foreach ($especificaciones as $especificacion) {
                if ($producto_ingreso->id_producto == $especificacion->id_producto) {
                    $esp = $esp . " ," . $especificacion->nombre_especificacion;
                }
            }
            $producto_ingreso->especificaciones = $esp;
            $total = $total + $producto_ingreso->cantidad;
        }
        $ingreso->total = $total;

        //dd("ingreso");

        return view('ingresos.ver',compact('producto_ingresos','ingreso'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::EXPORTAR INGRESO SUCURSAL EXCEL::::::::::::::::::::::::::::::::::::::*/


    public function editarCantidades(Request $request){/*AJAX*/

        $producto_ingresos = DB::table('producto_ingresos')
        ->where('id_ingreso','=',$request->id_ingreso)
        ->where('id_producto','=',$request->id_producto)
        ->get();

        if(count($producto_ingresos) == 1){

                DB::table('producto_ingresos')
                ->where('id_ingreso','=',$request->id_ingreso)
                ->where('id_producto','=',$request->id_producto)
                ->update([
                    'cantidad' => $request->cantidad
                ]);
                                            //4          -          //6        =    -2
                $cantidad_actualizar = $request->cantidad - $producto_ingresos[0]->cantidad;
                                            //6          -          //4        =     2
                $producto_bodegas = DB::table('producto_bodegas')
                ->where('id_bodega','=',$request->id_bodega)
                ->where('id_producto','=',$request->id_producto)
                ->first();

                $cantidad_final = $producto_bodegas->cantidad + $cantidad_actualizar;

                DB::table('producto_bodegas')
                ->where('id_bodega','=',$request->id_bodega)
                ->where('id_producto','=',$request->id_producto)
                ->update([
                    'cantidad' => $cantidad_final
                ]);
        }

        return ($cantidad_final);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('ingresos.lista');
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::INGRESO SUCURSAL:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::CREAR INGRESO SUCURSAL::::::::::::::::::::::::::::::::::::::::::::*/


    public function crearIngresoSucursal(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $sucursales = DB::table('sucursals')
        ->where('estado_sucursal','=','activo')
        ->where('id_sucursal','!=',0)
        ->select('sucursals.id_sucursal','sucursals.nombre_sucursal')
        ->get();

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        return view('ingresos.sucursal.crear',compact('estatus','tipo_productos','sucursales'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::CREAR INGRESO SUCURSAL::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardarIngresoSucursal(Request $request){

        function obtener_nombre_producto($id_producto_nombre){

            $producto_nombre = DB::table('productos')
            ->where('id_producto','=',$id_producto_nombre)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->first();

            $especificaciones_nombre_producto = DB::table('producto_especificaciones')
            ->where('producto_especificaciones.id_producto','=',$id_producto_nombre)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();

            $nombre_producto = $producto_nombre->nombre_tipo_producto . " - " . $producto_nombre->nombre_marca . " - " . $producto_nombre->nombre_modelo;
            foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
            }

            DB::table('productos')
            ->where('id_producto', $id_producto_nombre)
            ->update([
                'nombre_producto' => $nombre_producto
            ]);
        }

        $productos = json_decode($request->todo);

        $date = Carbon::now();$id_user = Auth::id();
        $id_ingreso = DB::table('ingresos_sucursal')->insertGetId(
            ['id_sucursal' => $request->sucursal, 'fecha_ingreso_sucursal' => $date]
        );
        DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 29, 'accion' => 'crear', 'id_elemento' => $id_ingreso, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);
        
        foreach ($productos as $producto) {
            
            if($producto->id_producto=="null" || $producto->id_producto=="" || $producto->id_producto=="x"){

                $id_producto = DB::table('productos')->insertGetId(
                    ['id_modelo' => $producto->modelo, 'precio_base' => $producto->precio, 'declara_iva' => $producto->iva, 'codigo_producto' => $producto->codigo, 'estado_producto' => 'activo']
                );

                DB::table('productos')
                ->where('id_producto', $id_producto)
                ->update(['code128' => str_pad($producto->modelo, 5, "0", STR_PAD_LEFT) . str_pad($id_producto, 7, "0", STR_PAD_LEFT)]);

                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 22, 'accion' => 'crear', 'id_elemento' => $id_producto, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                foreach ($producto->especificaciones as $especificacion) {
                    DB::table('producto_especificaciones')->insert(
                        ['id_producto' => $id_producto, 'id_especificacion' => $especificacion->id_especificacion]
                    );
                }
                DB::table('productos_ingreso_sucursal')->insert(
                    ['id_ingreso_sucursal' => $id_ingreso, 'id_producto' => $id_producto, 'cantidad' => $producto->cantidad]
                );

                DB::table('producto_sucursales')->insert(
                    ['id_sucursal' => $request->sucursal, 'id_producto' => $id_producto, 'cantidad' => $producto->cantidad]
                );

            }else{
                DB::table('productos_ingreso_sucursal')->insert(
                    ['id_ingreso_sucursal' => $id_ingreso, 'id_producto' => $producto->id_producto, 'cantidad' => $producto->cantidad]
                );

                $encontrados = DB::table('producto_sucursales')
                ->where('id_sucursal','=',$request->sucursal)
                ->where('id_producto','=',$producto->id_producto)
                ->increment('cantidad',$producto->cantidad);
                if($encontrados == 0){
                    DB::table('producto_sucursales')->insert(
                        ['id_sucursal' => $request->sucursal, 'id_producto' => $producto->id_producto, 'cantidad' => $producto->cantidad]
                    );
                }
                $id_producto = $producto->id_producto;
            }
            obtener_nombre_producto($id_producto);
        }

        $estatus="exito";

        return redirect()->route('ingresos.sucursal.crear',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::LISTA INGRESO SUCURSAL::::::::::::::::::::::::::::::::::::::::::::*/


    public function listaIngresoSucursal(){

        $ingresos = DB::table('ingresos_sucursal')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ingresos_sucursal.id_sucursal')
        ->where('sucursals.id_sucursal','!=',0)
        ->select('ingresos_sucursal.id_ingreso_sucursal','sucursals.nombre_sucursal','ingresos_sucursal.fecha_ingreso_sucursal')
        ->orderBy('id_ingreso_sucursal', 'desc')
        ->get();
        
        $producto_ingresos = DB::table('ingresos_sucursal')
        ->leftJoin('productos_ingreso_sucursal','productos_ingreso_sucursal.id_ingreso_sucursal','=','ingresos_sucursal.id_ingreso_sucursal')
        ->get();

        foreach ($ingresos as $ingreso) {
            $c = 0;
            foreach ($producto_ingresos as $producto_ingreso) {
                if ($ingreso->id_ingreso_sucursal == $producto_ingreso->id_ingreso_sucursal) {
                    $c = $c + 1;
                }
            }
            $ingreso->cantidad = $c;
        }

        return view('ingresos.sucursal.lista',compact('ingresos'));
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::VER DETALLE INGRESO SUCURSAL::::::::::::::::::::::::::::::::::::::::*/


    public function verIngresoSucursal(Request $request){

        $ingreso = DB::table('ingresos_sucursal')
        ->where('id_ingreso_sucursal','=',$request->ver)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ingresos_sucursal.id_sucursal')
        ->where('sucursals.id_sucursal','!=',0)
        ->first();

        $producto_ingresos = DB::table('productos_ingreso_sucursal')
        ->where('id_ingreso_sucursal','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','productos_ingreso_sucursal.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->get();

        $especificaciones = DB::table('producto_especificaciones')
        ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
        ->get();

        $total = 0;
        foreach ($producto_ingresos as $producto_ingreso) {
            $esp = "";
            foreach ($especificaciones as $especificacion) {
                if ($producto_ingreso->id_producto == $especificacion->id_producto) {
                    $esp = $esp . " ," . $especificacion->nombre_especificacion;
                }
            }
            $producto_ingreso->especificaciones = $esp;
            $total = $total + $producto_ingreso->cantidad;
        }
        $ingreso->total = $total;

        return view('ingresos.sucursal.ver',compact('producto_ingresos','ingreso'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::CREAR INGRESO BODEGA EXCEL::::::::::::::::::::::::::::::::::::::::*/


    public function excelCrearIngreso(){

        //dd("crear excel");
        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        return view('ingresos.crear',compact('tipo_productos'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::CARGAR ARCHIVO EXCEL::::::::::::::::::::::::::::::::::::::::::::*/


    public function cargarExcel(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $bodegas = DB::table('bodegas')
        ->where('id_bodega','!=',0)
        ->where('estado_bodega','=','activo')
        ->get();

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        return view('ingresos.cargar',compact('tipo_productos','bodegas','estatus'));
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::GUARDAR INFO ARCHIVO EXCEL BODEGA::::::::::::::::::::::::::::::::::::::*/


    public function guardarExcel(Request $request){


        function identical_values( $arrayA , $arrayB ) {

            sort( $arrayA );
            sort( $arrayB );

            return $arrayA == $arrayB;
        }

        function obtener_nombre_producto($id_producto_nombre){
//610
            $producto_nombre = DB::table('productos')
            ->where('id_producto','=',$id_producto_nombre)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->first();

            $especificaciones_nombre_producto = DB::table('producto_especificaciones')
            ->where('producto_especificaciones.id_producto','=',$id_producto_nombre)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();

            $nombre_producto = $producto_nombre->nombre_tipo_producto . " - " . $producto_nombre->nombre_marca . " - " . $producto_nombre->nombre_modelo;
            foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
            }

            DB::table('productos')
            ->where('id_producto', $id_producto_nombre)
            ->update([
                'nombre_producto' => $nombre_producto
            ]);
        }


        $excel = $request->file('excel');
        $coleccion = EXCEL::toArray(new ProductosImport, $excel);
        $collection = $coleccion[0];

        // dd($collection);

        //dd($collection,"Guardar Bodega");

        $tipo_producto = DB::table('tipo_productos')
        ->where('id_tipo_producto','=',$request->tipo_producto)
        ->where('estado_tipo_producto','=','activo')
        ->select('id_tipo_producto','nombre_tipo_producto','iva')
        ->first();

        $clasificaciones = DB::table('clasificacion_tipo_productos')
        ->where('clasificacion_tipo_productos.estado_clasificacion_tipo_producto','=','activo')
        ->where('clasificacion_tipo_productos.id_tipo_producto','=',$request->tipo_producto)
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
        ->where('clasificaciones.estado_clasificacion','=','activo')
        ->select('clasificaciones.nombre_clasificacion','clasificaciones.id_clasificacion')
        ->get();

        $c = 0;
        $it = 0;
        $m = count($clasificaciones);
        $n = count($collection[0]);

        for ($i = 3; $i < $n - 3; $i++) {
            foreach ($clasificaciones as $clasificacion) {
                if ($clasificacion->nombre_clasificacion == $collection[0][$i]) {
                    $c = $c + 1;
                    $collection[0][$i] = $clasificacion->id_clasificacion;
                }
            }
            $it = $it + 1;
        }

        //dd($tipo_producto,$collection[1][0],$c,$m,$it);

        if((isset($tipo_producto))&&($tipo_producto->nombre_tipo_producto == $collection[1][0])&&($c == $m)&&($c == $it)){

                $date = Carbon::now();$id_user = Auth::id();
                $id_ingreso = DB::table('ingresos')->insertGetId(['id_bodega' => $request->id_bodega, 'fecha_ingreso' => $date, 'id_proveedor' => 0]);

                $cc = 0;
                foreach ($collection as $filas) {
                        if($filas[1] !=null || $filas[2] != null){
                                if($cc != 0){
                                        $especificaciones = [];
                                        $cod_clas = "";
                                        for($i = 1; $i < $n; $i++){

                                                if($i == 1){

                                                        $marca = DB::table('marcas')
                                                        ->where('nombre_marca','=',$filas[$i])
                                                        ->first();
                                                        if(!isset($marca)){
                                                                $id_marca = DB::table('marcas')->insertGetId(['nombre_marca' => $filas[$i], 'estado_marca' => 'activo']);
                                                                $marca = (object) array('id_marca' => $id_marca);
                                                        }

                                                        $tipo_producto_marca = DB::table('tipo_producto_marcas')
                                                        ->where('id_tipo_producto','=',$request->tipo_producto)
                                                        ->where('id_marca','=',$marca->id_marca)
                                                        ->where('estado_tipo_producto_marca','=','activo')
                                                        ->first();
                                                        if(!isset($tipo_producto_marca)){
                                                                DB::table('tipo_producto_marcas')->insert(['id_tipo_producto' => $request->tipo_producto, 'id_marca' => $marca->id_marca, 'estado_tipo_producto_marca' => 'activo']);
                                                        }

                                                }else if($i == 2){

                                                        $modelo = DB::table('modelos')
                                                        ->where('nombre_modelo','=',$filas[$i])
                                                        ->where('id_tipo_producto','=',$request->tipo_producto)
                                                        ->where('id_marca','=',$marca->id_marca)
                                                        ->first();

                                                        if(!isset($modelo)){
                                                                $id_modelo = DB::table('modelos')->insertGetId([
                                                                    'nombre_modelo' => $filas[$i], 'descripcion_modelo' => '', 'id_tipo_producto' => $request->tipo_producto, 'id_marca' => $marca->id_marca, 'estado_modelo' => 'activo']);
                                                                $modelo = (object) array('id_modelo' => $id_modelo);
                                                        }

                                                }else if(2 < $i && $i < ($n - 3)){

                                                        $id_clasificacion = $collection[0][$i];

                                                        $especificacion = DB::table('especificaciones')
                                                        ->where('nombre_especificacion','=',$filas[$i])
                                                        ->where('id_clasificacion','=',$id_clasificacion)
                                                        ->where('estado_especificacion','=','activo')
                                                        ->first();
                                                        if(!isset($especificacion)){
                                                                $id_especificacion = DB::table('especificaciones')->insertGetId(
                                                                    ['nombre_especificacion' => $filas[$i], 'id_clasificacion' => $id_clasificacion, 'estado_especificacion' => 'activo']);
                                                                $especificacion = (object) array('id_especificacion' => $id_especificacion);
                                                        }
                                                        array_push($especificaciones,$especificacion->id_especificacion);
                                                        $cod_clas = $cod_clas . "-" . $especificacion->id_especificacion;

                                                }else if($i < ($n - 2)){

                                                        if($filas[$i] != null){
                                                                $codigo = $filas[$i];
                                                        }else{
                                                                $codigo = substr($collection[1][0],0,2) . substr($filas[1],0,2) . substr($filas[2],0,2) .$cod_clas;
                                                        }

                                                }else if($i < ($n - 1)){

                                                        $cantidad = $filas[$i];

                                                }else if($i < $n){

                                                        $precio_venta = $filas[$i];

                                                        /*::::::::::::::::::EL PRODUCTO EXISTE?::::::::::::::::::*/

                                                        $productos = DB::table('productos')
                                                        ->where('id_modelo', $modelo->id_modelo)
                                                        ->where('estado_producto','activo')
                                                        ->get();

                                                        if (count($productos) != 0) {//SI existen productos de ese modelo pero no se si existe el mismo, hay que verificar:

                                                                foreach ($productos as $producto) {//verificamos si alguno de esos productos coinciden las especificaciones del excel//AQUI

                                                                        $esp_prod = DB::table('producto_especificaciones')
                                                                        ->where('id_producto', $producto->id_producto)
                                                                        ->select('id_especificacion')
                                                                        ->get();
                                                                        $prod_esp = [];
                                                                        foreach ($esp_prod as $esp) {
                                                                                array_push($prod_esp,$esp->id_especificacion);
                                                                        }
                                                                        $iguales = identical_values($especificaciones,$prod_esp);
                                                                        if ($iguales == true) {
                                                                            break;
                                                                        }
                                                                }
                                                                if($iguales == true){//si coinciden las especificaciones, es porque existe y actualizo las cantidades

                                                                        DB::table('producto_ingresos')->insert(
                                                                            ['id_ingreso' => $id_ingreso, 'id_producto' => $producto->id_producto, 'cantidad' => $cantidad]
                                                                        );

                                                                        $encontrados = DB::table('producto_bodegas')
                                                                        ->where('id_bodega','=',$request->id_bodega)
                                                                        ->where('id_producto','=',$producto->id_producto)
                                                                        ->increment('cantidad',$cantidad);
                                                                        if($encontrados == 0){
                                                                            DB::table('producto_bodegas')->insert(
                                                                                ['id_bodega' => $request->id_bodega, 'id_producto' => $producto->id_producto, 'cantidad' => $cantidad]
                                                                            );
                                                                        }
                                                                }else{//existe el modelo pero no con esas especificaciones,creo el producto nuevo
                                                                        $id_producto = DB::table('productos')->insertGetId([
                                                                            'id_modelo' => $modelo->id_modelo,
                                                                            'precio_base' => $precio_venta,
                                                                            'declara_iva' => $tipo_producto->iva,
                                                                            'codigo_producto' => $codigo,
                                                                            'estado_producto' => 'activo'
                                                                        ]);
                                                                        DB::table('productos')
                                                                        ->where('id_producto', $id_producto)
                                                                        ->update(['code128' => str_pad($modelo->id_modelo, 5, "0", STR_PAD_LEFT) . str_pad($id_producto, 7, "0", STR_PAD_LEFT)]);

                                                                        obtener_nombre_producto($id_producto);

                                                                        DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 22, 'accion' => 'crear', 'id_elemento' => $id_producto, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                                                                        foreach ($especificaciones as $especificacion) {
                                                                            DB::table('producto_especificaciones')->insert(
                                                                                ['id_producto' => $id_producto, 'id_especificacion' => $especificacion]
                                                                            );
                                                                        }
                                                                        DB::table('producto_ingresos')->insert(
                                                                            ['id_ingreso' => $id_ingreso, 'id_producto' => $id_producto, 'cantidad' => $cantidad]
                                                                        );

                                                                        DB::table('producto_bodegas')->insert(
                                                                            ['id_bodega' => $request->id_bodega, 'id_producto' => $id_producto, 'cantidad' => $cantidad]
                                                                        );
                                                                }
                                                        }
                                                        else{//no existen productos de ese modelo, guardo normalmente
                                                                $id_producto = DB::table('productos')->insertGetId([
                                                                    'id_modelo' => $modelo->id_modelo,
                                                                    'precio_base' => $precio_venta,
                                                                    'declara_iva' => $tipo_producto->iva,
                                                                    'codigo_producto' => $codigo,
                                                                    'estado_producto' => 'activo'
                                                                ]);
                                                                DB::table('productos')
                                                                ->where('id_producto', $id_producto)
                                                                ->update(['code128' => str_pad($modelo->id_modelo, 5, "0", STR_PAD_LEFT) . str_pad($id_producto, 7, "0", STR_PAD_LEFT)]);

                                                                obtener_nombre_producto($id_producto);

                                                                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 22, 'accion' => 'crear', 'id_elemento' => $id_producto, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                                                                foreach ($especificaciones as $especificacion) {
                                                                    DB::table('producto_especificaciones')->insert(
                                                                        ['id_producto' => $id_producto, 'id_especificacion' => $especificacion]
                                                                    );
                                                                }
                                                                DB::table('producto_ingresos')->insert(
                                                                    ['id_ingreso' => $id_ingreso, 'id_producto' => $id_producto, 'cantidad' => $cantidad]
                                                                );

                                                                DB::table('producto_bodegas')->insert(
                                                                    ['id_bodega' => $request->id_bodega, 'id_producto' => $id_producto, 'cantidad' => $cantidad]
                                                                );
                                                        }                                                
                                                }
                                        }
                                }
                                $cc = $cc + 1;
                        }else{
                                break;
                        }
                }
                $estatus = "exito";
        }else{
            $estatus = "error";
        }

        return redirect()->route('ingresos.excel.cargar',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::INGRESO SUCURSAL EXCEL::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::CREAR INGRESO SUCURSAL EXCEL::::::::::::::::::::::::::::::::::::::::*/


    public function excelCrearIngresoSucursal(){

        //dd("crear excel");
        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        return view('ingresos.sucursal.excel.crear',compact('tipo_productos'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::EXPORTAR INGRESO SUCURSAL EXCEL::::::::::::::::::::::::::::::::::::::*/


    public function generarExcel(Request $request){/*AJAX*/

        $tipo_producto = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->where('id_tipo_producto','=',$request->id_tipo_producto)
        ->first();


        if (isset($tipo_producto)) {

            $marcas = DB::table('tipo_producto_marcas')
            ->where('tipo_producto_marcas.estado_tipo_producto_marca','=','activo')
            ->where('tipo_producto_marcas.id_tipo_producto','=',$request->id_tipo_producto)
            ->leftJoin('marcas','marcas.id_marca','=','tipo_producto_marcas.id_marca')
            ->where('marcas.estado_marca','=','activo')
            ->select('marcas.nombre_marca')
            ->get();

            $clasificaciones = DB::table('clasificacion_tipo_productos')
            ->where('clasificacion_tipo_productos.estado_clasificacion_tipo_producto','=','activo')
            ->where('clasificacion_tipo_productos.id_tipo_producto','=',$request->id_tipo_producto)
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
            ->where('clasificaciones.estado_clasificacion','=','activo')
            ->select('clasificaciones.nombre_clasificacion')
            ->get();

            $titulos = ["Tipo de Producto","Marca","Modelo"];

            foreach ($clasificaciones as $clasificacion) {
                array_push($titulos,$clasificacion->nombre_clasificacion);
            }

            array_push($titulos,"Codigo");
            array_push($titulos,"Cantidad");
            array_push($titulos,"Precio Venta");

            $excel = [
                'titulos' => $titulos,
                'marcas' => $marcas,
                'tipo_producto' => $tipo_producto->nombre_tipo_producto
            ];

            $excel = json_encode($excel);

            return ($excel);

        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::CARGAR ARCHIVO EXCEL::::::::::::::::::::::::::::::::::::::::::::*/


    public function cargarExcelSucursal(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $sucursales = DB::table('sucursals')
        ->where('id_sucursal','!=',0)
        ->where('estado_sucursal','=','activo')
        ->get();

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        return view('ingresos.sucursal.excel.cargar',compact('tipo_productos','sucursales','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::GUARDAR INFO ARCHIVO EXCEL SUCURSAL:::::::::::::::::::::::::::::::::::::*/


    public function guardarExcelSucursal(Request $request){


        function identical_values( $arrayA , $arrayB ) {

            sort( $arrayA );
            sort( $arrayB );

            return $arrayA == $arrayB;
        }

        function obtener_nombre_producto($id_producto_nombre){

            $producto_nombre = DB::table('productos')
            ->where('id_producto','=',$id_producto_nombre)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->first();

            $especificaciones_nombre_producto = DB::table('producto_especificaciones')
            ->where('producto_especificaciones.id_producto','=',$id_producto_nombre)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();

            $nombre_producto = $producto_nombre->nombre_tipo_producto . " - " . $producto_nombre->nombre_marca . " - " . $producto_nombre->nombre_modelo;
            foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
            }

            DB::table('productos')
            ->where('id_producto', $id_producto_nombre)
            ->update([
                'nombre_producto' => $nombre_producto
            ]);
        }

        $excel = $request->file('excel');
        $coleccion = EXCEL::toArray(new ProductosImport, $excel);
        $collection = $coleccion[0];

        //dd($collection);

        $tipo_producto = DB::table('tipo_productos')
        ->where('id_tipo_producto','=',$request->tipo_producto)
        ->where('estado_tipo_producto','=','activo')
        ->select('id_tipo_producto','nombre_tipo_producto','iva')
        ->first();

        $clasificaciones = DB::table('clasificacion_tipo_productos')
        ->where('clasificacion_tipo_productos.estado_clasificacion_tipo_producto','=','activo')
        ->where('clasificacion_tipo_productos.id_tipo_producto','=',$request->tipo_producto)
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
        ->where('clasificaciones.estado_clasificacion','=','activo')
        ->select('clasificaciones.nombre_clasificacion','clasificaciones.id_clasificacion')
        ->get();

        $c = 0;
        $it = 0;
        $m = count($clasificaciones);
        $n = count($collection[0]);

        for ($i = 3; $i < $n - 3; $i++) {
            foreach ($clasificaciones as $clasificacion) {
                if ($clasificacion->nombre_clasificacion == $collection[0][$i]) {
                    $c = $c + 1;
                    $collection[0][$i] = $clasificacion->id_clasificacion;
                }
            }
            $it = $it + 1;
        }

        //dd($tipo_producto,$collection[1][0],$c,$m,$it);

        if((isset($tipo_producto))&&($tipo_producto->nombre_tipo_producto == $collection[1][0])&&($c == $m)&&($c == $it)){

                $date = Carbon::now();$id_user = Auth::id();
                $id_ingreso_sucursal = DB::table('ingresos_sucursal')->insertGetId(['id_sucursal' => $request->id_sucursal, 'fecha_ingreso_sucursal' => $date]);

                $cc = 0;
                foreach ($collection as $filas) {
                        if($filas[1] !=null || $filas[2] != null){
                                if($cc != 0){
                                        $especificaciones = [];
                                        $cod_clas = "";
                                        for($i = 1; $i < $n; $i++){

                                                if($i == 1){

                                                        $marca = DB::table('marcas')
                                                        ->where('nombre_marca','=',$filas[$i])
                                                        ->first();
                                                        if(!isset($marca)){
                                                                $id_marca = DB::table('marcas')->insertGetId(['nombre_marca' => $filas[$i], 'estado_marca' => 'activo']);
                                                                $marca = (object) array('id_marca' => $id_marca);
                                                        }

                                                        $tipo_producto_marca = DB::table('tipo_producto_marcas')
                                                        ->where('id_tipo_producto','=',$request->tipo_producto)
                                                        ->where('id_marca','=',$marca->id_marca)
                                                        ->where('estado_tipo_producto_marca','=','activo')
                                                        ->first();
                                                        if(!isset($tipo_producto_marca)){
                                                                DB::table('tipo_producto_marcas')->insert(['id_tipo_producto' => $request->tipo_producto, 'id_marca' => $marca->id_marca, 'estado_tipo_producto_marca' => 'activo']);
                                                        }

                                                }else if($i == 2){

                                                        $modelo = DB::table('modelos')
                                                        ->where('nombre_modelo','=',$filas[$i])
                                                        ->where('id_tipo_producto','=',$request->tipo_producto)
                                                        ->where('id_marca','=',$marca->id_marca)
                                                        ->first();

                                                        if(!isset($modelo)){
                                                                $id_modelo = DB::table('modelos')->insertGetId([
                                                                    'nombre_modelo' => $filas[$i], 'descripcion_modelo' => '', 'id_tipo_producto' => $request->tipo_producto, 'id_marca' => $marca->id_marca, 'estado_modelo' => 'activo']);
                                                                $modelo = (object) array('id_modelo' => $id_modelo);
                                                        }

                                                }else if(2 < $i && $i < ($n - 3)){

                                                        $id_clasificacion = $collection[0][$i];

                                                        $especificacion = DB::table('especificaciones')
                                                        ->where('nombre_especificacion','=',$filas[$i])
                                                        ->where('id_clasificacion','=',$id_clasificacion)
                                                        ->where('estado_especificacion','=','activo')
                                                        ->first();
                                                        if(!isset($especificacion)){
                                                                $id_especificacion = DB::table('especificaciones')->insertGetId(
                                                                    ['nombre_especificacion' => $filas[$i], 'id_clasificacion' => $id_clasificacion, 'estado_especificacion' => 'activo']);
                                                                $especificacion = (object) array('id_especificacion' => $id_especificacion);
                                                        }
                                                        array_push($especificaciones,$especificacion->id_especificacion);
                                                        $cod_clas = $cod_clas . "-" . $especificacion->id_especificacion;

                                                }else if($i < ($n - 2)){

                                                        if($filas[$i] != null){
                                                                $codigo = $filas[$i];
                                                        }else{
                                                                $codigo = substr($collection[1][0],0,2) . substr($filas[1],0,2) . substr($filas[2],0,2) .$cod_clas;
                                                        }

                                                }else if($i < ($n - 1)){

                                                        $cantidad = $filas[$i];

                                                }else if($i < $n){

                                                        $precio_venta = $filas[$i];

                                                        /*::::::::::::::::::EL PRODUCTO EXISTE?::::::::::::::::::*/

                                                        $productos = DB::table('productos')
                                                        ->where('id_modelo', $modelo->id_modelo)
                                                        ->where('estado_producto','activo')
                                                        ->get();

                                                        if (count($productos) != 0) {//SI existen productos de ese modelo pero no se si existe el mismo, hay que verificar:
                                                            
                                                                foreach ($productos as $producto) {//verificamos si alguno de esos productos coinciden las especificaciones del excel//AQUI

                                                                        $esp_prod = DB::table('producto_especificaciones')
                                                                        ->where('id_producto', $producto->id_producto)
                                                                        ->select('id_especificacion')
                                                                        ->get();
                                                                        $prod_esp = [];
                                                                        foreach ($esp_prod as $esp) {
                                                                                array_push($prod_esp,$esp->id_especificacion);
                                                                        }
                                                                        $iguales = identical_values($especificaciones,$prod_esp);
                                                                        if ($iguales == true) {
                                                                            break;
                                                                        }
                                                                }
                                                                if($iguales == true){//si coinciden las especificaciones, es porque existe y actualizo las cantidades

                                                                        DB::table('productos_ingreso_sucursal')->insert(
                                                                            ['id_ingreso_sucursal' => $id_ingreso_sucursal, 'id_producto' => $producto->id_producto, 'cantidad' => $cantidad]
                                                                        );

                                                                        $encontrados = DB::table('producto_sucursales')
                                                                        ->where('id_sucursal','=',$request->id_sucursal)
                                                                        ->where('id_producto','=',$producto->id_producto)
                                                                        ->increment('cantidad',$cantidad);
                                                                        if($encontrados == 0){
                                                                            DB::table('producto_sucursales')->insert(
                                                                                ['id_sucursal' => $request->id_sucursal, 'id_producto' => $producto->id_producto, 'cantidad' => $cantidad]
                                                                            );
                                                                        }
                                                                }else{//existe el modelo pero no con esas especificaciones,creo el producto nuevo
                                                                        $id_producto = DB::table('productos')->insertGetId([
                                                                            'id_modelo' => $modelo->id_modelo,
                                                                            'precio_base' => $precio_venta,
                                                                            'declara_iva' => $tipo_producto->iva,
                                                                            'codigo_producto' => $codigo,
                                                                            'estado_producto' => 'activo'
                                                                        ]);
                                                                        DB::table('productos')
                                                                        ->where('id_producto', $id_producto)
                                                                        ->update(['code128' => str_pad($modelo->id_modelo, 5, "0", STR_PAD_LEFT) . str_pad($id_producto, 7, "0", STR_PAD_LEFT)]);

                                                                        DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 22, 'accion' => 'crear', 'id_elemento' => $id_producto, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                                                                        foreach ($especificaciones as $especificacion) {
                                                                            DB::table('producto_especificaciones')->insert(
                                                                                ['id_producto' => $id_producto, 'id_especificacion' => $especificacion]
                                                                            );
                                                                        }

                                                                        obtener_nombre_producto($id_producto);

                                                                        DB::table('productos_ingreso_sucursal')->insert(
                                                                            ['id_ingreso_sucursal' => $id_ingreso_sucursal, 'id_producto' => $id_producto, 'cantidad' => $cantidad]
                                                                        );

                                                                        DB::table('producto_sucursales')->insert(
                                                                            ['id_sucursal' => $request->id_sucursal, 'id_producto' => $id_producto, 'cantidad' => $cantidad]
                                                                        );
                                                                }
                                                        }
                                                        else{//no existen productos de ese modelo, guardo normalmente
                                                                $id_producto = DB::table('productos')->insertGetId([
                                                                    'id_modelo' => $modelo->id_modelo,
                                                                    'precio_base' => $precio_venta,
                                                                    'declara_iva' => $tipo_producto->iva,
                                                                    'codigo_producto' => $codigo,
                                                                    'estado_producto' => 'activo'
                                                                ]);
                                                                DB::table('productos')
                                                                ->where('id_producto', $id_producto)
                                                                ->update(['code128' => str_pad($modelo->id_modelo, 5, "0", STR_PAD_LEFT) . str_pad($id_producto, 7, "0", STR_PAD_LEFT)]);

                                                                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 22, 'accion' => 'crear', 'id_elemento' => $id_producto, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                                                                foreach ($especificaciones as $especificacion) {
                                                                    DB::table('producto_especificaciones')->insert(
                                                                        ['id_producto' => $id_producto, 'id_especificacion' => $especificacion]
                                                                    );
                                                                }

                                                                obtener_nombre_producto($id_producto);
                                                                
                                                                DB::table('productos_ingreso_sucursal')->insert(
                                                                    ['id_ingreso_sucursal' => $id_ingreso_sucursal, 'id_producto' => $id_producto, 'cantidad' => $cantidad]
                                                                );

                                                                DB::table('producto_sucursales')->insert(
                                                                    ['id_sucursal' => $request->id_sucursal, 'id_producto' => $id_producto, 'cantidad' => $cantidad]
                                                                );
                                                        }                                                
                                                }
                                        }
                                }
                                $cc = $cc + 1;
                        }else{
                                break;
                        }
                }
                $estatus = "exito";
        }else{
            $estatus = "error";
        }

        return redirect()->route('ingresos.sucursal.excel.cargar',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR:::::::::::::::::::::::::::::::::::::::::::::::*/
}
