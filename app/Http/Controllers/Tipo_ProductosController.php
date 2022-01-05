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

class Tipo_ProductosController extends Controller
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

        $mensaje="";

        $categorias = DB::table('categorias')
        ->where('estado_categoria','=','activo')
        ->get();

        $clasificaciones = DB::table('clasificaciones')
        ->where('estado_clasificacion','=','activo')
        ->get();

        return view('tipo_productos.crear',compact('mensaje','clasificaciones','categorias'));
	}

/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR:::::::::::::::::::::::::::::::::::::::::::::::*/

    public function guardar(Request $request){

        //dd($request);
        $clasificaciones=json_decode($request->clasificaciones);

        $existe = DB::table('tipo_productos')
        ->where('nombre_tipo_producto','=',$request->nombre_tipo_producto)
        ->pluck('nombre_tipo_producto');

        if($existe=="[]"){
                $mensaje="exito";
                
                $id_tipo_producto = DB::table('tipo_productos')->insertGetId(
                    ['nombre_tipo_producto' => $request->nombre_tipo_producto,'id_categoria' => $request->categoria_tipo_producto, 'iva' => $request->iva_tipo_producto, 'estado_tipo_producto' => 'activo']
                );

                $date = Carbon::now();$id_user = Auth::id();
                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 18, 'accion' => 'crear', 'id_elemento' => $id_tipo_producto, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                foreach($clasificaciones as $clasificacion){
                    DB::table('clasificacion_tipo_productos')->insert(
                        ['id_tipo_producto' => $id_tipo_producto, 'id_clasificacion' => $clasificacion, 'estado_clasificacion_tipo_producto' => 'activo']
                    );
                }


        }else{
                $mensaje="error";
        }

        $categorias = DB::table('categorias')
        ->where('estado_categoria','=','activo')
        ->get();

        $clasificaciones = DB::table('clasificaciones')
        ->where('estado_clasificacion','=','activo')
        ->get();

        return view('tipo_productos.crear',compact('mensaje','clasificaciones','categorias'));
    }

/*:::::::::::::::::::::::::::::::::::::::::::LISTA::::::::::::::::::::::::::::::::::::::::::::::::*/

   public function lista(){

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->leftJoin('categorias','categorias.id_categoria','=','tipo_productos.id_categoria')
        ->where('categorias.estado_categoria','=','activo')
        ->select('tipo_productos.id_tipo_producto','tipo_productos.nombre_tipo_producto','tipo_productos.estado_tipo_producto','categorias.nombre_categoria')
        ->get();

        //dd($tipo_productos);

        $clasificacion_tipo_productos = DB::table('clasificacion_tipo_productos')
        ->where('estado_clasificacion_tipo_producto','=','activo')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
        ->where('estado_clasificacion','=','activo')
        ->select('clasificacion_tipo_productos.id_tipo_producto','clasificaciones.nombre_clasificacion')
        ->get();

        //dd($clasificacion_tipo_productos);

        foreach ($tipo_productos as $tipo_producto) {

            $clasificaciones="";
            foreach ($clasificacion_tipo_productos as $clasificacion_tipo_producto) {

                if ($tipo_producto->id_tipo_producto == $clasificacion_tipo_producto->id_tipo_producto) {
                   $clasificaciones = $clasificaciones . $clasificacion_tipo_producto->nombre_clasificacion . ", ";
                }
            }
            $tipo_producto->clasificaciones = $clasificaciones;
        }

        $mensaje="";

        return view('tipo_productos.lista',compact('tipo_productos','mensaje'));
    }



    public function productos(Request $request){

        // $tiempo_inicial = microtime(true);
        $tipo_producto = DB::table('tipo_productos')
        ->where('id_tipo_producto','=',$request->ver)
        ->where('estado_tipo_producto','=','activo')
        ->first();

        return view('tipo_productos.productos',compact('tipo_producto'));

        // $clasificaciones = DB::table('clasificacion_tipo_productos')
        // ->where('id_tipo_producto','=',$request->ver)
        // ->where('estado_clasificacion_tipo_producto','=','activo')
        // ->get();
        // $cantidad_clasificaciones = count($clasificaciones);

        // $productos = DB::table('productos')
        // ->where('estado_producto','=','activo')
        // ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        // ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        // ->where('tipo_productos.id_tipo_producto','=',$request->ver)
        // ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        // ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
        // ->get();

        // $producto_especificaciones = DB::table('especificaciones')
        // ->where('estado_especificacion','=','activo')
        // ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        // ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        // ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        // ->get();

        // foreach ($productos as $producto) {
        //     $cantidad_encontradas = 0;
        //     $especificaciones="";
        //     foreach ($producto_especificaciones as $producto_especificacion) {
        //         if($producto_especificacion->id_producto == $producto->id_producto){
        //             $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
        //             $cantidad_encontradas = $cantidad_encontradas + 1;
        //         }
        //         if ($cantidad_encontradas == $cantidad_clasificaciones) {
        //             break;
        //         }
        //     }
        //     $producto->especificaciones = $especificaciones;
        // }
        
        // return view('tipo_productos.productos',compact('productos','tipo_producto'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::BUSCAR TIPO PRODUCTOS AJAX::::::::::::::::::::::::::::::::::::::*/


    public function buscar(Request $request) {

        $productos = DB::table('productos')
        ->where('estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('modelos.id_tipo_producto','=',$request->id_tipo_producto)
        ->select('productos.id_producto','productos.precio_base','productos.nombre_producto','productos.code128');

        return datatables()->of($productos)
        ->addIndexColumn()
        ->addColumn('precio', function($producto){
            return "<div style='white-space:nowrap; text-align: right; float: right;'>$ " . number_format($producto->precio_base, 2, ',', '.') . "</div>";
        })
        ->addColumn('agregar-codigo', function($producto){
            return "<input class='input-cantidad' id='input-cantidad-$producto->id_producto' onfocusout='cambiarCodigo(`$producto->id_producto`,this.value, `$producto->nombre_producto`,`$producto->code128`,`$producto->precio_base`);' autocomplete='off' placeholder='Cantidad'>";
        })
        ->addColumn('acciones', function($producto){
            return '<div class="iconos-acciones">
                        <div class="content-acciones">
                            <a href="" class="dropdown-content"><i class="icon-pencil"> </i> EDITAR</a>
                            <i onclick="editar(' . $producto->id_producto . ');" class="icon-pencil i-acciones"> </i> &nbsp;
                        </div>
                    </div>';
        })
        ->rawColumns(['precio','agregar-codigo','acciones'])
        ->toJson();
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){
            
            $tipo_producto = DB::table('tipo_productos')
            ->where('id_tipo_producto','=',$request->editar)
            ->where('estado_tipo_producto','=','activo')
            ->first();

            if(is_numeric($request->editar)&&($tipo_producto != null)) {

                $mensaje="";
                return view('tipo_productos.modificar',compact('tipo_producto','mensaje'));

            }else{

                $mensaje="erroractualizar";

                $tipo_productos = DB::table('tipo_productos')
                ->where('estado_tipo_producto','=','activo')
                ->leftJoin('categorias','categorias.id_categoria','=','tipo_productos.id_categoria')
                ->where('categorias.estado_categoria','=','activo')
                ->select('tipo_productos.id_tipo_producto','tipo_productos.nombre_tipo_producto','tipo_productos.estado_tipo_producto','categorias.nombre_categoria')
                ->get();

                $clasificacion_tipo_productos = DB::table('clasificacion_tipo_productos')
                ->where('estado_clasificacion_tipo_producto','=','activo')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
                ->where('estado_clasificacion','=','activo')
                ->select('clasificacion_tipo_productos.id_tipo_producto','clasificaciones.nombre_clasificacion')
                ->get();

                foreach ($tipo_productos as $tipo_producto) {

                    $clasificaciones="";
                    foreach ($clasificacion_tipo_productos as $clasificacion_tipo_producto) {

                        if ($tipo_producto->id_tipo_producto == $clasificacion_tipo_producto->id_tipo_producto) {
                           $clasificaciones = $clasificaciones . $clasificacion_tipo_producto->nombre_clasificacion . ", ";
                        }
                    }
                    $tipo_producto->clasificaciones = $clasificaciones;
                }

                return view('tipo_productos.lista',compact('tipo_productos'));
            }        
        }


    public function modificar(Request $request){

        function obtener_nombre_tipo_producto($id_tipo_producto_nombre){

            $productos_nombres = DB::table('productos')
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->where('modelos.id_tipo_producto','=',$id_tipo_producto_nombre)
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->get();

            foreach ($productos_nombres as $producto_nombre) {

                $especificaciones_nombre_producto = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$producto_nombre->id_producto)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
                ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
                ->get();

                $nombre_producto = $producto_nombre->nombre_tipo_producto . " - " . $producto_nombre->nombre_marca . " - " . $producto_nombre->nombre_modelo;
                foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                    $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
                }

                DB::table('productos')
                ->where('id_producto', $producto_nombre->id_producto)
                ->update([
                    'nombre_producto' => $nombre_producto
                ]);
            }  
        }

        $tipo_producto = DB::table('tipo_productos')
        ->where('id_tipo_producto','=',$request->id_tipo_producto)
        ->where('estado_tipo_producto','=','activo')
        ->first();

        if(is_numeric($request->id_tipo_producto)&&($tipo_producto != null)) {
            $verificar = DB::table('tipo_productos')
            ->where('nombre_tipo_producto','=',$request->nombre_tipo_producto)
            ->where('estado_tipo_producto','=','activo')
            ->first();
            if($verificar != null){
                if($tipo_producto->nombre_tipo_producto == $request->nombre_tipo_producto){
                    DB::table('tipo_productos')
                    ->where('id_tipo_producto', $request->id_tipo_producto)
                    ->update([
                                'nombre_tipo_producto' => $request->nombre_tipo_producto,
                            ]);
                    $mensaje="actualizado";
                }else{
                    $mensaje="erroractualizar";
                }
            }else{
                DB::table('tipo_productos')
                    ->where('id_tipo_producto', $request->id_tipo_producto)
                    ->update([
                                'nombre_tipo_producto' => $request->nombre_tipo_producto,
                            ]);
                $mensaje="actualizado";
            }

            obtener_nombre_tipo_producto($request->id_tipo_producto);
            
        }else{
            $mensaje="erroractualizar";
        }

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->leftJoin('categorias','categorias.id_categoria','=','tipo_productos.id_categoria')
        ->where('categorias.estado_categoria','=','activo')
        ->select('tipo_productos.id_tipo_producto','tipo_productos.nombre_tipo_producto','tipo_productos.estado_tipo_producto','categorias.nombre_categoria')
        ->get();

        $clasificacion_tipo_productos = DB::table('clasificacion_tipo_productos')
        ->where('estado_clasificacion_tipo_producto','=','activo')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
        ->where('estado_clasificacion','=','activo')
        ->select('clasificacion_tipo_productos.id_tipo_producto','clasificaciones.nombre_clasificacion')
        ->get();

        foreach ($tipo_productos as $tipo_producto) {

            $clasificaciones="";
            foreach ($clasificacion_tipo_productos as $clasificacion_tipo_producto) {

                if ($tipo_producto->id_tipo_producto == $clasificacion_tipo_producto->id_tipo_producto) {
                   $clasificaciones = $clasificaciones . $clasificacion_tipo_producto->nombre_clasificacion . ", ";
                }
            }
            $tipo_producto->clasificaciones = $clasificaciones;
        } 

        return view('tipo_productos.lista',compact('tipo_productos','mensaje'));

    }

    public function inactivar(Request $request){
        $existe = DB::table('tipo_productos')
        ->where('id_tipo_producto','=',$request->eliminar)
        ->where('estado_tipo_producto','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $mensaje="exito";
            DB::table('tipo_productos')
            ->where('id_tipo_producto', $request->eliminar)
            ->update(['estado_tipo_producto' => 'inactivo']);
        }else{
            $mensaje="error";
        }

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->leftJoin('categorias','categorias.id_categoria','=','tipo_productos.id_categoria')
        ->where('categorias.estado_categoria','=','activo')
        ->select('tipo_productos.id_tipo_producto','tipo_productos.nombre_tipo_producto','tipo_productos.estado_tipo_producto','categorias.nombre_categoria')
        ->get();

        $clasificacion_tipo_productos = DB::table('clasificacion_tipo_productos')
        ->where('estado_clasificacion_tipo_producto','=','activo')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
        ->where('estado_clasificacion','=','activo')
        ->select('clasificacion_tipo_productos.id_tipo_producto','clasificaciones.nombre_clasificacion')
        ->get();

        foreach ($tipo_productos as $tipo_producto) {

            $clasificaciones="";
            foreach ($clasificacion_tipo_productos as $clasificacion_tipo_producto) {

                if ($tipo_producto->id_tipo_producto == $clasificacion_tipo_producto->id_tipo_producto) {
                   $clasificaciones = $clasificaciones . $clasificacion_tipo_producto->nombre_clasificacion . ", ";
                }
            }
            $tipo_producto->clasificaciones = $clasificaciones;
        } 

        return view('tipo_productos.lista',compact('tipo_productos','mensaje'));
    }


}
