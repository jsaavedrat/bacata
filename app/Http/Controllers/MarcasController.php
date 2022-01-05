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

class MarcasController extends Controller
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

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        return view('marcas.crear',compact('mensaje','tipo_productos'));
	}

/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR:::::::::::::::::::::::::::::::::::::::::::::::*/

    public function guardar(Request $request){
        //dd($request);
        
        $tipo_productos=json_decode($request->tipo_productos);

        $existe = DB::table('marcas')
        ->where('nombre_marca','=',$request->nombre_marca)
        ->pluck('nombre_marca');

        if($existe=="[]"){
                $mensaje="exito";
                
                $id_marca = DB::table('marcas')->insertGetId(
                    ['nombre_marca' => $request->nombre_marca, 'estado_marca' => 'activo']
                );

                $date = Carbon::now();$id_user = Auth::id();
                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 19, 'accion' => 'crear', 'id_elemento' => $id_marca, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                foreach($tipo_productos as $tipo_producto){
                    DB::table('tipo_producto_marcas')->insert(
                        ['id_tipo_producto' => $tipo_producto, 'id_marca' => $id_marca,  'estado_tipo_producto_marca' => 'activo']
                    );
                }


        }else{
                $mensaje="error";
        }

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        return view('marcas.crear',compact('mensaje','tipo_productos'));
    }


    public function cargar(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        return view('marcas.cargar',compact('estatus','tipo_productos'));
    }



    public function guardarCarga(Request $request){
        
        $info_marcas = json_decode($request->marcas);

        $date = Carbon::now();
        $id_user = Auth::id();
        foreach ($info_marcas as $info_marca) {
            
            $id_marca = DB::table('marcas')->insertGetId(
                ['nombre_marca' => $info_marca->nombre_marca, 'estado_marca' => 'activo']
            );

            DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 19, 'accion' => 'crear', 'id_elemento' => $id_marca, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

            DB::table('tipo_producto_marcas')->insert([
                'id_tipo_producto' => $request->tipo_producto,
                'id_marca' => $id_marca,
                'estado_tipo_producto_marca' => 'activo'
            ]); 
        }

        $productos_nombres = DB::table('productos')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('modelos.id_tipo_producto','=',$request->tipo_producto)
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

        $estatus="exito";

        return redirect()->route('marcas.cargar',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::LISTA::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function lista(){

        // if(!auth()->user()->hasPermissionTo('Lista_Marcas')) {
        //     dd("NO tiene permiso");
        // } else {
        //     dd("Tiene permiso");
        // }

        return view('marcas.lista');

        


        // $marcas = DB::table('marcas')
        // ->where('estado_marca','=','activo')
        // ->get();

        // $tipo_producto_marcas = DB::table('tipo_producto_marcas')
        // ->where('estado_tipo_producto_marca','=','activo')
        // ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','tipo_producto_marcas.id_tipo_producto')
        // ->where('estado_tipo_producto','=','activo')
        // ->select('tipo_producto_marcas.id_marca')
        // ->get();

        // //dd($tipo_producto_marcas);

        // foreach ($marcas as $marca) {

        //     $cantidad = 0;
        //     foreach ($tipo_producto_marcas as $tipo_producto_marca) {

        //         if ($marca->id_marca == $tipo_producto_marca->id_marca) {
        //            $cantidad = $cantidad + 1;
        //         }
        //     }
        //     $marca->cantidad = $cantidad;
        // }

        // //dd($tipo_productos);

        // return view('marcas.lista',compact('marcas'));
    }

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::BUSCAR MARCAS AJAX:::::::::::::::::::::::::::::::::::::::::*/


    public function buscar(Request $request) {

        $marcas = DB::table('marcas')
        ->where('estado_marca','=','activo')
        ->leftJoin('tipo_producto_marcas','tipo_producto_marcas.id_marca','=','marcas.id_marca')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','tipo_producto_marcas.id_tipo_producto')
        ->select('marcas.id_marca','marcas.nombre_marca','tipo_productos.nombre_tipo_producto');

        return datatables()->of($marcas)
        ->addIndexColumn()
        ->addColumn('acciones', function($marca){

            $opcion_editar = "";
            if(auth()->user()->hasPermissionTo('Editar_Marca')){
                $opcion_editar = "
                <div class='content-acciones'>
                    <a class='dropdown-content'><i class='icon-pencil'> </i> EDITAR</a>
                    <i onclick='editar(" . $marca->id_marca . ");' class='icon-pencil i-acciones'> </i> &nbsp;
                </div>";
            }
            $opcion_eliminar = "";
            if(auth()->user()->hasPermissionTo('Eliminar_Marca')){
                $opcion_eliminar = "
                <div class='content-acciones'>
                    <a class='dropdown-content'><i class='icon-trash'> </i> ELIMINAR</a>
                    <i onclick='eliminar(" . $marca->id_marca . ",`" . $marca->nombre_marca . "`);' class='icon-trash i-acciones'></i>
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


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::EDITAR:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function editar(Request $request){
        //dd("editar");
        $marca = DB::table('marcas')
        ->where('id_marca','=',$request->editar)
        ->where('estado_marca','=','activo')
        ->first();

        if(is_numeric($request->editar)&&($marca != null)) {

            $mensaje="";
            $tipo_productos = DB::table('tipo_productos')
            ->where('estado_tipo_producto','=','activo')
            ->select('id_tipo_producto','nombre_tipo_producto')
            ->get();

            $tipo_producto_marcas = DB::table('tipo_producto_marcas')
            ->where('estado_tipo_producto_marca','=','activo')
            ->where('id_marca','=',$request->editar)
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','tipo_producto_marcas.id_tipo_producto')
            ->select('tipo_producto_marcas.id_tipo_producto_marcas','tipo_productos.id_tipo_producto','tipo_productos.nombre_tipo_producto')
            ->get();

            $productos = [];
            foreach ($tipo_productos as $tipo_producto) {
                $encontrado = false;
                foreach ($tipo_producto_marcas as $tipo_producto_marca) {
                   if ($tipo_producto->id_tipo_producto == $tipo_producto_marca->id_tipo_producto) {
                        $encontrado = true;
                   }
                }
                if ($encontrado == false) {
                    array_push($productos, $tipo_producto);
                }
            }

            return view('marcas.modificar',compact('mensaje','productos','tipo_producto_marcas','marca'));

        }else{

            $mensaje="erroractualizar";

            $marcas = DB::table('marcas')
            ->where('estado_marca','=','activo')
            ->get();
            return view('marcas.lista',compact('marcas','mensaje'));
        }        
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::MODIFICAR:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function modificar(Request $request){

        function obtener_nombres_productos_marcas($id_marca_nombres){

            $productos_marcas = DB::table('productos')
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->where('marcas.id_marca','=',$id_marca_nombres)
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->get();

            foreach ($productos_marcas as $producto_marca) {

                $especificaciones_nombre_producto = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$producto_marca->id_producto)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
                ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
                ->get();

                $nombre_producto = $producto_marca->nombre_tipo_producto . " - " . $producto_marca->nombre_marca . " - " . $producto_marca->nombre_modelo;
                foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                    $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
                }

                DB::table('productos')
                ->where('id_producto', $producto_marca->id_producto)
                ->update([
                    'nombre_producto' => $nombre_producto
                ]);
            }            
        }

        $tipo_productos=json_decode($request->tipo_productos);

        $marca = DB::table('marcas')
        ->where('id_marca','=',$request->id_marca)
        ->where('estado_marca','=','activo')
        ->first();

        if(is_numeric($request->id_marca)&&($marca != null)) {
            $verificar = DB::table('marcas')
            ->where('nombre_marca','=',$request->nombre_marca)
            ->where('estado_marca','=','activo')
            ->first();
            if($verificar != null){
                if($marca->nombre_marca == $request->nombre_marca){
                    //dd("es el mismo");
                    DB::table('marcas')
                    ->where('id_marca', $request->id_marca)
                    ->update(['nombre_marca' => $request->nombre_marca]);

                    $date = Carbon::now();$id_user = Auth::id();
                    DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 19, 'accion' => 'modificar', 'id_elemento' => $request->id_marca, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                    if($tipo_productos != null){
                        foreach($tipo_productos as $tipo_producto){
                            DB::table('tipo_producto_marcas')->insert(
                                ['id_tipo_producto' => $tipo_producto, 'id_marca' => $request->id_marca,  'estado_tipo_producto_marca' => 'activo']
                            );
                        }
                    }
                    $mensaje="actualizado";
                }else{
                    //dd("NO es el mismo");
                    $mensaje="erroractualizar";
                }
            }else{
                //dd("Actualiza normalmente");
                DB::table('marcas')
                ->where('id_marca', $request->id_marca)
                ->update(['nombre_marca' => $request->nombre_marca]);

                $date = Carbon::now();$id_user = Auth::id();
                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 19, 'accion' => 'modificar', 'id_elemento' => $request->id_marca, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                if($tipo_productos != null){
                    foreach($tipo_productos as $tipo_producto){
                        DB::table('tipo_producto_marcas')->insert(
                            ['id_tipo_producto' => $tipo_producto, 'id_marca' => $request->id_marca,  'estado_tipo_producto_marca' => 'activo']
                        );
                    }
                }
                $mensaje="actualizado";
            }

            obtener_nombres_productos_marcas($request->id_marca);

        }else{
            $mensaje="erroractualizar";
        }

        $marcas = DB::table('marcas')
        ->where('estado_marca','=','activo')
        ->get();

        $tipo_producto_marcas = DB::table('tipo_producto_marcas')
        ->where('estado_tipo_producto_marca','=','activo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','tipo_producto_marcas.id_tipo_producto')
        ->where('estado_tipo_producto','=','activo')
        ->select('tipo_producto_marcas.id_marca')
        ->get();

        //dd($tipo_producto_marcas);

        foreach ($marcas as $marca) {

            $cantidad = 0;
            foreach ($tipo_producto_marcas as $tipo_producto_marca) {

                if ($marca->id_marca == $tipo_producto_marca->id_marca) {
                   $cantidad = $cantidad + 1;
                }
            }
            $marca->cantidad = $cantidad;
        }

        return view('marcas.lista',compact('marcas','mensaje'));
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::INACTIVAR:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function inactivar(Request $request){
        //dd("inactivar");
        $existe = DB::table('marcas')
        ->where('id_marca','=',$request->eliminar)
        ->where('estado_marca','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $mensaje="exito";
            DB::table('marcas')
            ->where('id_marca', $request->eliminar)
            ->update(['estado_marca' => 'inactivo']);
        }else{
            $mensaje="error";
        }

        $marcas = DB::table('marcas')
        ->where('estado_marca','=','activo')
        ->get();

        $tipo_producto_marcas = DB::table('tipo_producto_marcas')
        ->where('estado_tipo_producto_marca','=','activo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','tipo_producto_marcas.id_tipo_producto')
        ->where('estado_tipo_producto','=','activo')
        ->select('tipo_producto_marcas.id_marca')
        ->get();

        //dd($tipo_producto_marcas);

        foreach ($marcas as $marca) {

            $cantidad = 0;
            foreach ($tipo_producto_marcas as $tipo_producto_marca) {

                if ($marca->id_marca == $tipo_producto_marca->id_marca) {
                   $cantidad = $cantidad + 1;
                }
            }
            $marca->cantidad = $cantidad;
        }

        return view('marcas.lista',compact('marcas','mensaje'));
    }






















}
