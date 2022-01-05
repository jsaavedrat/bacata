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

class ModelosController extends Controller
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
/*::::::::::::::::::::::::::::::::::::::::::CREAR MODELOS::::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        return view('modelos.crear',compact('estatus','tipo_productos'));
	}


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::AJAX Marca Tipo Producto AJAX:::::::::::::::::::::::::::::::::::::::::*/


    public function marcaTipoProductos(Request $request){

        $marcas = DB::table('tipo_producto_marcas')
        ->where('estado_tipo_producto_marca','=','activo')
        ->where('id_tipo_producto','=',$request->id_tipo_producto)
        ->leftJoin('marcas','marcas.id_marca','=','tipo_producto_marcas.id_marca')
        ->where('estado_marca','=','activo')
        ->select('marcas.id_marca','marcas.nombre_marca')
        ->get();

        return ($marcas);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::AJAX Modelos de Marca y Tipo Producto AJAX::::::::::::::::::::::::::::::::*/


    public function modelosMarca(Request $request){

        $modelos = DB::table('modelos')
        ->where('id_tipo_producto','=',$request->id_tipo_producto)
        ->where('id_marca','=',$request->id_marca)
        ->where('estado_modelo','=','activo')
        ->get();

        return ($modelos);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::GUARDAR MODELOS:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        $info_modelos = json_decode($request->modelos);

        $date = Carbon::now();$id_user = Auth::id();
        foreach ($info_modelos as $info_modelo) {
            
            $id_modelo = DB::table('modelos')->insertGetId(
                ['nombre_modelo' => $info_modelo->nombre_modelo, 'id_tipo_producto' => $request->tipo_producto, 'id_marca' => $request->marca, 'descripcion_modelo' => $info_modelo->descripcion, 'estado_modelo' => 'activo']
            );

            DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 20, 'accion' => 'crear', 'id_elemento' => $id_modelo, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

        }
        $estatus="exito";

        return redirect()->route('modelos.crear',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::LISTA DE MODELOS::::::::::::::::::::::::::::::::::::::::::::::*/


   public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        return view('modelos.lista',compact('estatus'));

        // $modelos = DB::table('modelos')
        // ->where('estado_modelo','=','activo')
        // ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        // ->where('estado_tipo_producto','=','activo')
        // ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        // ->where('estado_marca','=','activo')
        // ->select('modelos.id_modelo','modelos.nombre_modelo','modelos.estado_modelo','marcas.nombre_marca','tipo_productos.nombre_tipo_producto')
        // ->get();

        // return view('modelos.lista',compact('modelos','estatus'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::BUSCAR MARCAS AJAX:::::::::::::::::::::::::::::::::::::::::*/


    public function buscar(Request $request) {

        $modelos = DB::table('modelos')
        ->where('estado_modelo','=','activo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->where('estado_tipo_producto','=','activo')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->where('estado_marca','=','activo')
        ->select('modelos.id_modelo','modelos.nombre_modelo','marcas.nombre_marca','tipo_productos.nombre_tipo_producto');

        return datatables()->of($modelos)
        ->addIndexColumn()
        ->addColumn('acciones', function($modelo){

            $opcion_editar = "";
            if(auth()->user()->hasPermissionTo('Editar_Modelo')){
                $opcion_editar = "
                <div class='content-acciones'>
                    <a class='dropdown-content'><i class='icon-pencil'> </i> EDITAR</a>
                    <i onclick='editar(" . $modelo->id_modelo . ");' class='icon-pencil i-acciones'> </i> &nbsp;
                </div>";
            }
            $opcion_eliminar = "";
            if(auth()->user()->hasPermissionTo('Eliminar_Modelo')){
                $opcion_eliminar = "
                <div class='content-acciones'>
                    <a class='dropdown-content'><i class='icon-trash'> </i> ELIMINAR</a>
                    <i onclick='eliminar(" . $modelo->id_modelo . ",`" . $modelo->nombre_modelo . "`);' class='icon-trash i-acciones'></i>
                </div>";
            }

            return "
            <div class='iconos-acciones'>
                " . $opcion_editar . $opcion_eliminar . "
            </div>";
        })
        ->rawColumns(['acciones'])
        ->toJson();
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR MODELO A EDITAR:::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $modelo = DB::table('modelos')
        ->where('id_modelo','=',$request->editar)
        ->where('estado_modelo','=','activo')
        ->first();

        if(is_numeric($request->editar)&&($modelo != null)) {

            return view('modelos.modificar',compact('modelo'));

        }else{

            $estatus="erroractualizar";
             return redirect()->route('modelos.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR MODELO::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){

        function obtener_nombres_productos_modelos($id_modelo_nombres, $id_marca_nombres){

            $productos_modelos = DB::table('productos')
            ->where('productos.id_modelo','=',$id_modelo_nombres)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->where('marcas.id_marca','=',$id_marca_nombres)
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->get();

            foreach ($productos_modelos as $producto_modelo) {

                $especificaciones_nombre_producto = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$producto_modelo->id_producto)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
                ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
                ->get();

                $nombre_producto = $producto_modelo->nombre_tipo_producto . " - " . $producto_modelo->nombre_marca . " - " . $producto_modelo->nombre_modelo;
                foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                    $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
                }

                DB::table('productos')
                ->where('id_producto', $producto_modelo->id_producto)
                ->update([
                    'nombre_producto' => $nombre_producto
                ]);
            }            
        }

        $modelo = DB::table('modelos')
        ->where('id_modelo','=',$request->id_modelo)
        ->where('id_marca','=',$request->id_marca)
        ->where('estado_modelo','=','activo')
        ->first();

        if(is_numeric($request->id_modelo)&&($modelo != null)) {
            $verificar = DB::table('modelos')
            ->where('nombre_modelo','=',$request->nombre_modelo)
            ->where('id_marca','=',$request->id_marca)
            ->where('estado_modelo','=','activo')
            ->first();
            if($verificar != null){
                if($modelo->nombre_modelo == $request->nombre_modelo){
                    //dd("es el mismo");
                    DB::table('modelos')
                    ->where('id_modelo', $request->id_modelo)
                    ->update(['nombre_modelo' => $request->nombre_modelo,'descripcion_modelo' => $request->descripcion_modelo]);

                    $date = Carbon::now();$id_user = Auth::id();
                    DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 20, 'accion' => 'modificar', 'id_elemento' => $request->id_modelo, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                    $estatus="actualizado";
                }else{
                    //dd("NO es el mismo");
                    $estatus="erroractualizar";
                }
            }else{
                //dd("Actualiza normalmente");
                DB::table('modelos')
                ->where('id_modelo', $request->id_modelo)
                ->update(['nombre_modelo' => $request->nombre_modelo,'descripcion_modelo' => $request->descripcion_modelo]);

                $date = Carbon::now();$id_user = Auth::id();
                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 20, 'accion' => 'modificar', 'id_elemento' => $request->id_modelo, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                $estatus="actualizado";
            }

            obtener_nombres_productos_modelos($request->id_modelo, $request->id_marca);

        }else{
            $estatus="erroractualizar";
        }

        return redirect()->route('modelos.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::INACTIVAR MODELO:::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $existe = DB::table('modelos')
        ->where('id_modelo','=',$request->eliminar)
        ->where('estado_modelo','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('modelos')
            ->where('id_modelo', $request->eliminar)
            ->update(['estado_modelo' => 'inactivo']);
        }else{
            $mensaje="error";
        }

        return redirect()->route('modelos.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('modelos.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR:::::::::::::::::::::::::::::::::::::::::::::::*/
}
