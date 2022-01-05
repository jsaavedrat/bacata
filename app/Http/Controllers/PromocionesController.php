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

class PromocionesController extends Controller
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

/*:::::::::::::::::::::::::::::::::::::::::::CREAR::::::::::::::::::::::::::::::::::::::::::::::::*/

	public function crear(){
		//dd("crear");

        $productos = DB::table('productos')
        ->where('estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca')
        ->get();

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();

        $promociones = DB::table('promocion_productos')
        ->where('estado_promocion_producto','!=','inactivo')
        ->select('promocion_productos.id_producto')
        ->get();

        foreach ($productos as $key => $producto){
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion){
                if($producto_especificacion->id_producto == $producto->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto->especificaciones = $especificaciones;
            foreach ($promociones as $promocion){
                if ($producto->id_producto == $promocion->id_producto){
                    unset($productos[$key]);
                }
            }
        }

        $mensaje="";

        return view('promociones.crear',compact('mensaje','productos'));
	}


/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR:::::::::::::::::::::::::::::::::::::::::::::::*/

    public function guardar(Request $request){

        $productos = json_decode($request->productos_promocion);
        //dd($request,$productos);
        $fin = $request->fecha_fin." "."23:59:59";
   

        $existe = DB::table('promociones')
        ->where('nombre_promocion','=',$request->nombre_promocion)
        ->pluck('nombre_promocion');

        if($existe=="[]"){
                $mensaje="exito";
                
                $id_promocion = DB::table('promociones')->insertGetId(
                    ['nombre_promocion' => $request->nombre_promocion, 'porcentaje_descuento' => $request->descuento_promocion, 'fecha_inicio' => $request->fecha_inicio, 'fecha_fin' => $fin, 'estado_promocion' => $request->estado_promocion]
                );

                $date = Carbon::now();$id_user = Auth::id();
                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 6, 'accion' => 'crear', 'id_elemento' => $id_promocion, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                foreach($productos as $producto){
                    DB::table('promocion_productos')->insert(
                        ['id_promocion' => $id_promocion, 'id_producto' => $producto, 'estado_promocion_producto' => $request->estado_promocion]
                    );
                }

        }else{
                $mensaje="error";
        }


        $productos = DB::table('productos')
        ->where('estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca')
        ->get();

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();

        $promociones = DB::table('promocion_productos')
        ->where('estado_promocion_producto','!=','inactivo')
        ->select('promocion_productos.id_producto')
        ->get();

        foreach ($productos as $key => $producto) {
            foreach ($promociones as $promocion) {
                if ($producto->id_producto == $promocion->id_producto) {
                    unset($productos[$key]);
                }else{
                    $especificaciones="";
                    foreach ($producto_especificaciones as $producto_especificacion) {
                        if($producto_especificacion->id_producto == $producto->id_producto){
                            $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                        }
                    }
                    $producto->especificaciones = $especificaciones;
                }
            }
        }

        $mensaje="";

        return view('promociones.crear',compact('mensaje','productos'));
    }

/*:::::::::::::::::::::::::::::::::::::::::::LISTA::::::::::::::::::::::::::::::::::::::::::::::::*/

   public function lista(){

        $promociones = DB::table('promociones')
        ->orderBy('fecha_fin', 'desc')
        ->get();

        $promocion_productos = DB::table('promocion_productos')
        ->get();


        foreach ($promociones as $promocion) {
            $c=0;
            foreach ($promocion_productos as $promocion_producto) {
                if($promocion->id_promocion == $promocion_producto->id_promocion && $promocion->estado_promocion == $promocion_producto->estado_promocion_producto){
                    $c=$c+1;
                }
            }
            $promocion->cantidad = $c;
        }


        return view('promociones.lista',compact('promociones'));
    }



}
