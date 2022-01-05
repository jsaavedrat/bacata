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

class ClasificacionesController extends Controller
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
/*::::::::::::::::::::::::::::::::::::::::::CREAR CLASIFICACIONES::::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        return view('clasificaciones.crear',compact('estatus'));
	}


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::GUARDAR CLASIFICACION::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        $especificaciones=json_decode($request->especificaciones);

        $existe = DB::table('clasificaciones')
        ->where('nombre_clasificacion','=',$request->nombre_clasificacion)
        ->pluck('nombre_clasificacion');

        if($existe=="[]"){
            
            $id_clasificacion = DB::table('clasificaciones')->insertGetId(
                ['nombre_clasificacion' => $request->nombre_clasificacion, 'estado_clasificacion' => 'activo']
            );

            $date = Carbon::now();$id_user = Auth::id();
            DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 16, 'accion' => 'crear', 'id_elemento' => $id_clasificacion, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

            foreach($especificaciones as $especificacion){
                $id_especificacion = DB::table('especificaciones')->insertGetId(
                    ['id_clasificacion' => $id_clasificacion, 'nombre_especificacion' => $especificacion, 'estado_especificacion' => 'activo']
                );

                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 17, 'accion' => 'crear', 'id_elemento' => $id_especificacion, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);
            }

            $estatus="exito";
        }else{
            $estatus="error";
        }

        return redirect()->route('clasificaciones.crear',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::LISTA DE CLASIFICACIONES::::::::::::::::::::::::::::::::::::::::::::::*/


   public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $clasificaciones = DB::table('clasificaciones')
        ->where('estado_clasificacion','=','activo')
        ->get();

        $especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->select('id_clasificacion')
        ->get();

        foreach ($clasificaciones as $clasificacion) {

            $cantidad = 0;
            foreach ($especificaciones as $especificacion) {
                if ($clasificacion->id_clasificacion == $especificacion->id_clasificacion) {
                    $cantidad = $cantidad+1;
                }
            }
            $clasificacion->cantidad = $cantidad;
        }

        return view('clasificaciones.lista',compact('clasificaciones','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::VER ESPECIFICACIONES::::::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){

        $clasificacion = DB::table('clasificaciones')
        ->where('id_clasificacion','=',$request->ver)
        ->select('id_clasificacion','nombre_clasificacion')
        ->first();

        $especificaciones= DB::table('especificaciones')
        ->where('id_clasificacion','=',$request->ver)
        ->where('estado_especificacion','=','activo')
        ->select('id_especificacion','id_clasificacion','nombre_especificacion')
        ->get();

        return view('clasificaciones.ver',compact('clasificacion','especificaciones'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR CLASIFICACION A EDITAR:::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $clasificacion = DB::table('clasificaciones')
        ->where('id_clasificacion','=',$request->editar)
        ->select('id_clasificacion','nombre_clasificacion')
        ->first();

        $especificaciones= DB::table('especificaciones')
        ->where('id_clasificacion','=',$request->editar)
        ->where('estado_especificacion','=','activo')
        ->select('id_especificacion','nombre_especificacion')
        ->get();

        if(is_numeric($request->editar)&&($clasificacion != null)) {

            return view('clasificaciones.modificar',compact('clasificacion','especificaciones'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('clasificaciones.lista',['estatus' => $estatus]);
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR CLASIFICACION::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){

        function obtener_nombre_calsificacion($id_clasificacion){

            $productos_clasificaciones = DB::table('productos')
            ->leftJoin('producto_especificaciones','producto_especificaciones.id_producto','=','productos.id_producto')
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->where('especificaciones.id_clasificacion','=',$id_clasificacion)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->get();

            foreach ($productos_clasificaciones as $producto_clasificacion) {

                $especificaciones_nombre_producto = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$producto_clasificacion->id_producto)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
                ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
                ->get();

                $nombre_producto = $producto_clasificacion->nombre_tipo_producto . " - " . $producto_clasificacion->nombre_marca . " - " . $producto_clasificacion->nombre_modelo;
                foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                    $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
                }

                DB::table('productos')
                ->where('id_producto', $producto_clasificacion->id_producto)
                ->update([
                    'nombre_producto' => $nombre_producto
                ]);
            }  
        }

        $especificaciones=json_decode($request->especificaciones);

        $clasificacion = DB::table('clasificaciones')
        ->where('id_clasificacion','=',$request->id_clasificacion)
        ->where('estado_clasificacion','=','activo')
        ->first();

        if(is_numeric($request->id_clasificacion)&&($clasificacion != null)) {
            $verificar = DB::table('clasificaciones')
            ->where('nombre_clasificacion','=',$request->nombre_clasificacion)
            ->where('estado_clasificacion','=','activo')
            ->first();
            if($verificar != null){
                if($verificar->id_clasificacion == $request->id_clasificacion){

                    DB::table('clasificaciones')
                    ->where('id_clasificacion', $request->id_clasificacion)
                    ->update([
                                'nombre_clasificacion' => $request->nombre_clasificacion
                            ]);

                    $date = Carbon::now();$id_user = Auth::id();
                    foreach($especificaciones as $especificacion){
                        $id_especificacion = DB::table('especificaciones')->insertGetId(
                            ['id_clasificacion' => $request->id_clasificacion, 'nombre_especificacion' => $especificacion, 'estado_especificacion' => 'activo']
                        );

                        DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 17, 'accion' => 'crear', 'id_elemento' => $id_especificacion, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);
                    }

                    $estatus="actualizado";
                }else{

                    $estatus="erroractualizar";
                }
            }else{

                DB::table('clasificaciones')
                    ->where('id_clasificacion', $request->id_clasificacion)
                    ->update([
                                'nombre_clasificacion' => $request->nombre_clasificacion
                            ]);

                $date = Carbon::now();$id_user = Auth::id();
                foreach($especificaciones as $especificacion){
                    $id_especificacion = DB::table('especificaciones')->insertGetId(
                        ['id_clasificacion' => $request->id_clasificacion, 'nombre_especificacion' => $especificacion, 'estado_especificacion' => 'activo']
                    );

                    DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 17, 'accion' => 'crear', 'id_elemento' => $id_especificacion, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);
                }
                $estatus="actualizado";
            }

            obtener_nombre_calsificacion($request->id_clasificacion);

        }else{
            $estatus="erroractualizar";
        }

        return redirect()->route('clasificaciones.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::INACTIVAR CLASIFICACION:::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $existe = DB::table('clasificaciones')
        ->where('id_clasificacion','=',$request->eliminar)
        ->where('estado_clasificacion','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('clasificaciones')
            ->where('id_clasificacion', $request->eliminar)
            ->update(['estado_clasificacion' => 'inactivo']);
        }else{
            $estatus="error";
        }

         return redirect()->route('clasificaciones.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::EDITAR ESPECIFICACION:::::::::::::::::::::::::::::::::::::::::::::::*/			//AREGLAR VERIFICACION


    public function editarEspecificacion(Request $request){

        $especificacion = DB::table('especificaciones')
        ->where('id_especificacion','=',$request->editar)
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('especificaciones.id_especificacion','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->first();

        return view('clasificaciones.modificarEspecificacion',compact('especificacion'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR ESPECIFICACION:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificarEspecificacion(Request $request){

        DB::table('especificaciones')
        ->where('id_especificacion', $request->id_especificacion)
        ->update([
            'nombre_especificacion' => $request->nombre_especificacion
        ]);

        $productos_especificaciones = DB::table('productos')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_producto','=','productos.id_producto')
        ->where('producto_especificaciones.id_especificacion','=',$request->id_especificacion)
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
        ->get();

        foreach ($productos_especificaciones as $producto_especificacion) {

            $especificaciones_nombre_producto = DB::table('producto_especificaciones')
            ->where('producto_especificaciones.id_producto','=',$producto_especificacion->id_producto)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();

            $nombre_producto = $producto_especificacion->nombre_tipo_producto . " - " . $producto_especificacion->nombre_marca . " - " . $producto_especificacion->nombre_modelo;
            foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
            }

            DB::table('productos')
            ->where('id_producto', $producto_especificacion->id_producto)
            ->update([
                'nombre_producto' => $nombre_producto
            ]);
        }

        return redirect()->route('clasificaciones.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::INACTIVAR ESPECIFICACION:::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivarEspecificacion(Request $request){
        
        $existe = DB::table('especificaciones')
        ->where('id_especificacion','=',$request->eliminar)
        ->where('estado_especificacion','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('especificaciones')
            ->where('id_especificacion', $request->eliminar)
            ->update(['estado_especificacion' => 'inactivo']);
        }else{
            $estatus="error";
        }

        return redirect()->route('clasificaciones.lista');
    }

}
