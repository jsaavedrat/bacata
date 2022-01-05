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

class CategoriasController extends Controller
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
/*::::::::::::::::::::::::::::::::::::::::::CREAR CATEGORIA::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        return view('categorias.crear',compact('estatus'));
	}


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::GUARDAR CATEGORIA:::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){
   
        $existe = DB::table('categorias')
        ->where('nombre_categoria','=',$request->nombre_categoria)
        ->where('estado_categoria','=','activo')
        ->pluck('nombre_categoria');

        if($existe=="[]"){
                                
                $id_categoria = DB::table('categorias')->insertGetId([
                    'nombre_categoria' => $request->nombre_categoria,
                    'estado_categoria' => $request->estado_categoria
                ]);

                $date = Carbon::now();$id_user = Auth::id();
                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 15, 'accion' => 'crear', 'id_elemento' => $id_categoria, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);
        
                $estatus="exito";
        }else{
                $estatus="error";
        }

        return redirect()->route('categorias.crear',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::LISTA DE CATEGORIAS:::::::::::::::::::::::::::::::::::::::::::*/


   public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $categorias = DB::table('categorias')
        ->where('estado_categoria','=','activo')
        ->get();

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        foreach ($categorias as $categoria) {
            $c = 0;
            foreach ($tipo_productos as $tipo_producto) {
                if ($categoria->id_categoria == $tipo_producto->id_categoria) {
                    $c = $c + 1;
                }
            }
            $categoria->cantidad_productos = $c;
        }

        return view('categorias.lista',compact('categorias','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR CATEGORIA A EDITAR:::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $categoria = DB::table('categorias')
        ->where('id_categoria','=',$request->editar)
        ->where('estado_categoria','=','activo')
        ->first();

        if(is_numeric($request->editar)&&($categoria != null)) {

            return view('categorias.modificar',compact('categoria'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('categorias.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::MODIFICAR CATEGORIA:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){

        $categoria = DB::table('categorias')
        ->where('id_categoria','=',$request->id_categoria)
        ->where('estado_categoria','=','activo')
        ->first();

        if(is_numeric($request->id_categoria)&&($categoria != null)) {

            $verificar = DB::table('categorias')
            ->where('nombre_categoria','=',$request->nombre_categoria)
            ->where('estado_categoria','=','activo')
            ->first();
            if($verificar != null){
                if($categoria->nombre_categoria == $request->nombre_categoria){
                    DB::table('categorias')
                    ->where('id_categoria', $request->id_categoria)
                    ->update([
                                'nombre_categoria' => $request->nombre_categoria
                            ]);
                    $estatus="actualizado";
                }else{
                    $estatus="erroractualizar";
                }
            }else{
                DB::table('categorias')
                    ->where('id_categoria', $request->id_categoria)
                    ->update([
                                'nombre_categoria' => $request->nombre_categoria
                            ]);
                $estatus="actualizado";
            }
        }else{
            $estatus="erroractualizar";
        }    

        return redirect()->route('categorias.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::INACTIVAR CATEGORIA:::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $existe = DB::table('categorias')
        ->where('id_categoria','=',$request->eliminar)
        ->where('estado_categoria','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('categorias')
            ->where('id_categoria', $request->eliminar)
            ->update(['estado_categoria' => 'inactivo']);
        }else{
            $estatus="error";
        }

        return redirect()->route('categorias.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::TIPOS DE PRODUCTO DE CATEGORIA:::::::::::::::::::::::::::::::::::::::::::*/


    public function listaTipoProductos(Request $request){

        $categoria = DB::table('categorias')
        ->where('id_categoria','=',$request->ver)
        ->first();

        $tipo_productos = DB::table('tipo_productos')
        ->where('id_categoria','=',$request->ver)
        ->where('estado_tipo_producto','=','activo')
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

        return view('categorias.listatipoproductos',compact('tipo_productos','categoria'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('categorias.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR:::::::::::::::::::::::::::::::::::::::::::::::*/
}
