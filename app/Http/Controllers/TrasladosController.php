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

class TrasladosController extends Controller
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
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::BODEGA > SUCURSAL:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::CREAR SALIDA B-S:::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaSucursalCrearSalida(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $bodegas = DB::table('bodegas')
        ->where('id_bodega','!=',0)
        ->where('estado_bodega','=','activo')
        ->get();

        $sucursales = DB::table('sucursals')
        ->where('id_sucursal','!=',0)
        ->where('estado_sucursal','=','activo')
        ->get();

        return view('traslados.bodegasucursal.crearsalida',compact('sucursales','bodegas','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::GUARDAR SALIDA B-S:::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaSucursalGuardarSalida(Request $request){

        $productos = json_decode($request->productos);
        $date = Carbon::now();
        $id_user = Auth::id();

        $id_traslado = DB::table('traslados')->insertGetId([
            'establecimiento_salida' => 'bodega',
            'id_salida' =>$request->bodega,
            'establecimiento_llegada' => 'sucursal',
            'id_llegada' =>$request->sucursal,
            'id_user_registro' =>$id_user,
            'id_user_salida' => $request->empleado_salida,
            'fecha_salida_traslado' => $date,
            'estado_traslado' => 'pendiente'
         ]);

        DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 24, 'accion' => 'crear', 'id_elemento' => $id_traslado, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

        foreach ($productos as $producto) {

            $producto_bodega = DB::table('producto_bodegas')
            ->where('id_bodega','=',$request->bodega)
            ->where('id_producto','=',$producto->id_producto)
            ->select('id_producto_bodega','cantidad')
            ->first();

            if ($producto_bodega->cantidad >= $producto->cantidad) {

                DB::table('producto_traslados')->insert([
                    'id_traslado' => $id_traslado,
                    'id_producto' => $producto->id_producto,
                    'cantidad_salida' => $producto->cantidad
                ]);
                $cantidad = $producto_bodega->cantidad - $producto->cantidad;

                DB::table('producto_bodegas')
                ->where('id_producto_bodega','=',$producto_bodega->id_producto_bodega)
                ->update([
                    'cantidad' => $cantidad
                ]);
            }
        }

        $estatus="exito";

        return redirect()->route('traslados.bodegasucursal.crearsalida',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::EMPLEADO TRASLADO B-S::::::::::::::::::::::::::::::::::::::::::*/


    public function traerEmpleadosBodegaSucursal(Request $request){//AJAX

        $empleados = DB::table('usuarios_sucursales')
        ->where('usuarios_sucursales.id_sucursal','=',$request->id)
        ->leftJoin('users','users.id','=','usuarios_sucursales.id_usuario')
        ->where('users.tipo_usuario','=','empleado')
        ->where('users.estado_usuario','=','activo')
        ->get();
        return ($empleados);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::PRODUCTOS TRASLADO B-S:::::::::::::::::::::::::::::::::::::::::*/


    public function traerProductosBodega(Request $request){//AJAX

        $productos = DB::table('producto_bodegas')
        ->where('id_bodega','=',$request->id)
        ->leftJoin('productos','productos.id_producto','=','producto_bodegas.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','producto_bodegas.cantidad','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca')
        ->get();

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();

        foreach ($productos as $producto) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto->especificaciones = $especificaciones;            
        }
        return ($productos);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA SALIDAS B-S:::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaSucursalListaSalidas(){

        $traslados = DB::table('traslados')
        ->where('establecimiento_salida','=','bodega')
        ->where('establecimiento_llegada','=','sucursal')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_salida')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','traslados.id_llegada','bodegas.nombre_bodega','sucursals.nombre_sucursal','users.name','users.apellido')
        ->orderBy('traslados.id_traslado', 'desc')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();

        $empleados =DB::table('users')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $traslados_aux = [];
        foreach($traslados as $traslado){
            $c=0;
            $guarda = false;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($empleados as $empleado) {
                if($traslado->id_user_registro == $empleado->id){
                    $traslado->usuario_registro = $empleado->name . " " . $empleado->apellido;
                }
            }
            foreach ($sucursales as $sucursal) {
                if($traslado->id_llegada == $sucursal->id_sucursal){
                    $guarda = true;
                }
            }
            if ($guarda == true) {
                array_push($traslados_aux,$traslado);
            }
        }
        $traslados = $traslados_aux;
        
        return view('traslados.bodegasucursal.listasalidas',compact('traslados'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::VER SALIDA B-S::::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaSucursalVerSalida(Request $request){

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_salida')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','bodegas.nombre_bodega','sucursals.nombre_sucursal','sucursals.id_sucursal','users.name','users.apellido')
        ->first();

        $user_registro =DB::table('users')
        ->where('id','=',$traslado->id_user_registro)
        ->first();
        $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;
        
        $c=0;
        $cc=0;
        foreach ($producto_traslados as $producto_traslado) {
            if($producto_traslado->id_traslado == $request->ver){
                $c = $c + $producto_traslado->cantidad_salida;
            }
        }
        $traslado->cantidad = $c;

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();

        foreach ($producto_traslados as $producto_traslado) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto_traslado->especificaciones = $especificaciones;            
        }

        return view('traslados.bodegasucursal.versalida',compact('producto_traslados','traslado'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::ENTRADAS PENDIENTES B-S:::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaSucursalEntradasPendientes(){
        $traslados = DB::table('traslados')
        ->where('establecimiento_salida','=','bodega')
        ->where('establecimiento_llegada','=','sucursal')
        ->where('estado_traslado','=','pendiente')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_salida')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','traslados.id_llegada','bodegas.nombre_bodega','sucursals.nombre_sucursal','users.name','users.apellido')
        ->orderBy('traslados.id_traslado', 'desc')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $traslados_aux = [];
        foreach($traslados as $traslado){
            $c=0;
            $guarda = false;
            foreach ($producto_traslados as $producto_traslado){
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($sucursales as $sucursal) {
                if($traslado->id_llegada == $sucursal->id_sucursal){
                    $guarda = true;
                }
            }
            if ($guarda == true) {
                array_push($traslados_aux,$traslado);
            }
        }
        $traslados = $traslados_aux;

        return view('traslados.bodegasucursal.entradaspendientes',compact('traslados'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::CREAR ENTRADA B-S:::::::::::::::::::::::::::::::::::::::::::::*/


     public function bodegaSucursalCrearEntrada(Request $request){

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_salida')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','bodegas.nombre_bodega','sucursals.nombre_sucursal','sucursals.id_sucursal','users.name','users.apellido')
        ->first();

        $user_registro =DB::table('users')
        ->where('id','=',$traslado->id_user_registro)
        ->first();
        $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;
        
        $c=0;
        $cc=0;
        foreach ($producto_traslados as $producto_traslado) {
            if($producto_traslado->id_traslado == $request->ver){
                $c = $c + $producto_traslado->cantidad_salida;
            }
        }
        $traslado->cantidad = $c;

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();


        foreach ($producto_traslados as $producto_traslado) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto_traslado->especificaciones = $especificaciones;            
        }

        //dd($producto_traslados,$traslado);

        return view('traslados.bodegasucursal.crearentrada',compact('producto_traslados','traslado'));
     }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::GUARDAR ENTRADA B-S::::::::::::::::::::::::::::::::::::::::::*/


     public function bodegaSucursalGuardarEntrada(Request $request){
 
        $productos = json_decode($request->productos);
        $date = Carbon::now();
        $id_user = Auth::id();

        //dd($productos);

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->traslado)
        ->where('estado_traslado','=','pendiente')
        ->first();

        if($traslado != null){

            DB::table('traslados')
            ->where('id_traslado','=',$request->traslado)
            ->update([
                'id_user_llegada' => $id_user,
                'fecha_llegada_traslado'=> $date,
                'novedad_llegada' => $request->novedad,
                'estado_traslado' => 'ingresado'
            ]);

            DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 24, 'accion' => 'ingresar', 'id_elemento' => $request->traslado, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

            foreach ($productos as $producto) {

                DB::table('producto_traslados')
                ->where('id_traslado','=',$request->traslado)
                ->where('id_producto','=',$producto->id_producto)
                ->update([
                    'cantidad_llegada' => intval($producto->cantidad_entrada)
                ]);

                $encontrados = DB::table('producto_sucursales')
                ->where('id_sucursal','=',$request->sucursal)
                ->where('id_producto','=',$producto->id_producto)
                ->increment('cantidad',intval($producto->cantidad_entrada));
                if($encontrados == 0){
                    DB::table('producto_sucursales')->insert(
                        ['id_sucursal' => $request->sucursal, 'id_producto' => $producto->id_producto, 'cantidad' => intval($producto->cantidad_entrada)]
                    );
                }
            }
            $estatus="exito";
        }
        else{
            $estatus="error";
        }

        return redirect()->route('traslados.bodegasucursal.listaentradas',['estatus' => $estatus]);
    }
    

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::LISTA ENTRADAS B-S:::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaSucursalListaEntradas(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $traslados = DB::table('traslados')
        ->where('establecimiento_salida','=','bodega')
        ->where('establecimiento_llegada','=','sucursal')
        ->where('estado_traslado','=','ingresado')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_salida')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.id_user_llegada','traslados.estado_traslado','traslados.id_llegada','traslados.fecha_llegada_traslado','bodegas.nombre_bodega','sucursals.nombre_sucursal','users.name','users.apellido')
        ->orderBy('traslados.fecha_llegada_traslado', 'desc')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();

        $empleados =DB::table('users')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $traslados_aux = [];
        foreach($traslados as $traslado){
            $c=0;
            $guarda = false;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($empleados as $empleado) {
                if($traslado->id_user_registro == $empleado->id){
                    $traslado->usuario_registro = $empleado->name . " " . $empleado->apellido;
                }
                if($traslado->id_user_llegada == $empleado->id){
                    $traslado->usuario_llegada = $empleado->name . " " . $empleado->apellido;
                }
            }
            foreach ($sucursales as $sucursal) {
                if($traslado->id_llegada == $sucursal->id_sucursal){
                    $guarda = true;
                }
            }
            if ($guarda == true) {
                array_push($traslados_aux,$traslado);
            }
        }
        $traslados = $traslados_aux;

        return view('traslados.bodegasucursal.listaentradas',compact('traslados','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::VER ENTRADA B-S::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaSucursalVerEntrada(Request $request){

        if (isset($request->estatus)) {
            $estatus = $request->estatus;
        }else{
            $estatus="";
        }

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','producto_traslados.cantidad_llegada','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->where('estado_traslado','=','ingresado')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_salida')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.fecha_llegada_traslado','traslados.id_user_registro','traslados.id_user_llegada','traslados.estado_traslado','traslados.novedad_llegada','bodegas.nombre_bodega','sucursals.nombre_sucursal','sucursals.id_sucursal','users.name','users.apellido')
        ->first();
        
        if($traslado != null){
   
            $user_registro = DB::table('users')
            ->where('id','=',$traslado->id_user_registro)
            ->first();
            $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;
            
            $user_llegada = DB::table('users')
            ->where('id','=',$traslado->id_user_llegada)
            ->first();
            $traslado->user_llegada = $user_registro->name . " " . $user_registro->apellido;

            $c=0;
            $cc=0;
            foreach ($producto_traslados as $producto_traslado) {
                if($producto_traslado->id_traslado == $request->ver){
                    $c = $c + $producto_traslado->cantidad_salida;
                    $cc = $cc + $producto_traslado->cantidad_llegada;
                }
            }
            $traslado->cantidad = $c;
            $traslado->cantidad_llegada = $cc;

            $producto_especificaciones = DB::table('especificaciones')
            ->where('estado_especificacion','=','activo')
            ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();

            foreach ($producto_traslados as $producto_traslado) {
                $especificaciones="";
                foreach ($producto_especificaciones as $producto_especificacion) {
                    if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                        $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                    }
                }
                $producto_traslado->especificaciones = $especificaciones;            
            }

            return view('traslados.bodegasucursal.verentrada',compact('producto_traslados','traslado'));

        }else{
            return redirect()->route('traslados.bodegasucursal.listaentradas');
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET B-S:::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaSucursalRedirectSalida(){
        return redirect()->route('traslados.bodegasucursal.listasalidas');
    }

    public function bodegaSucursalRedirectEntrada(){
        return redirect()->route('traslados.bodegasucursal.listaentrada');
    }



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::SUCURSAL > SUCURSAL:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::CREAR SALIDA S-S:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalSucursalCrearSalida(Request $request){

        if (isset($request->estatus)) {
            $estatus = $request->estatus;
        }else{
            $estatus="";
        }
        $bodegas = DB::table('bodegas')
        ->where('id_bodega','!=',0)
        ->where('estado_bodega','=','activo')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $sucursales_llegada = DB::table('sucursals')
        ->where('id_sucursal','!=',0)
        ->where('estado_sucursal','=','activo')
        ->get();

        return view('traslados.sucursalsucursal.crearsalida',compact('sucursales','sucursales_llegada','bodegas','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR SALIDA S-S::::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalSucursalGuardarSalida(Request $request){
        //dd($request);
        $productos = json_decode($request->productos);
        $date = Carbon::now();
        $id_user = Auth::id();

        $id_traslado = DB::table('traslados')->insertGetId([
            'establecimiento_salida' => 'sucursal',
            'id_salida' =>$request->sucursalS,
            'establecimiento_llegada' => 'sucursal',
            'id_llegada' =>$request->sucursal,
            'id_user_registro' =>$id_user,
            'id_user_salida' => $request->empleado_salida,
            'fecha_salida_traslado' => $date,
            'estado_traslado' => 'pendiente'
         ]);

        DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 24, 'accion' => 'crear', 'id_elemento' => $id_traslado, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

        foreach ($productos as $producto) {

            $producto_sucursal = DB::table('producto_sucursales')
            ->where('id_sucursal','=',$request->sucursalS)
            ->where('id_producto','=',$producto->id_producto)
            ->select('id_producto_sucursal','cantidad')
            ->first();

            if ($producto_sucursal->cantidad >= $producto->cantidad) {

                DB::table('producto_traslados')->insert([
                    'id_traslado' => $id_traslado,
                    'id_producto' => $producto->id_producto,
                    'cantidad_salida' => $producto->cantidad
                ]);
                $cantidad = $producto_sucursal->cantidad - $producto->cantidad;

                DB::table('producto_sucursales')
                ->where('id_producto_sucursal','=',$producto_sucursal->id_producto_sucursal)
                ->update([
                    'cantidad' => $cantidad
                ]);
            }
        }

        $estatus="exito";

        return redirect()->route('traslados.sucursalsucursal.crearsalida',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::EMPLEADO TRASLADO S-S::::::::::::::::::::::::::::::::::::::::::*/


    public function traerEmpleadosSucursalSucursal(Request $request){//AJAX

        $empleados = DB::table('usuarios_sucursales')
        ->where('usuarios_sucursales.id_sucursal','=',$request->id)
        ->leftJoin('users','users.id','=','usuarios_sucursales.id_usuario')
        ->where('users.tipo_usuario','=','empleado')
        ->where('users.estado_usuario','=','activo')
        ->get();
        return ($empleados);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::PRODUCTOS TRASLADO S-S:::::::::::::::::::::::::::::::::::::::::*/


    public function traerProductosSucursal(Request $request){//AJAX

        $productos = DB::table('producto_sucursales')
        ->where('id_sucursal','=',$request->id)
        ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','producto_sucursales.cantidad','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca')
        ->get();

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();

        foreach ($productos as $producto) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto->especificaciones = $especificaciones;            
        }
        return ($productos);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::LISTA SALIDAS S-S::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalSucursalListaSalidas(){

        $traslados = DB::table('traslados')
        ->where('establecimiento_salida','=','sucursal')
        ->where('establecimiento_llegada','=','sucursal')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.id_salida','traslados.estado_traslado','sucursals.nombre_sucursal','users.name','users.apellido')
        ->orderBy('traslados.id_traslado', 'desc')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();

        $empleados = DB::table('users')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $traslados_aux = [];
        foreach($traslados as $traslado){
            $c=0;
            $guarda = false;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($empleados as $empleado) {
                if($traslado->id_user_registro == $empleado->id){
                    $traslado->usuario_registro = $empleado->name . " " . $empleado->apellido;
                }
            }
            foreach ($sucursales as $sucursal) {
                if($traslado->id_salida == $sucursal->id_sucursal){
                    $traslado->nombre_sucursal_salida = $sucursal->nombre_sucursal;
                    $guarda = true;
                }
            }
            if ($guarda == true) {
                array_push($traslados_aux,$traslado);
            }
        }
        $traslados = $traslados_aux;

        return view('traslados.sucursalsucursal.listasalidas',compact('traslados'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::VER SALIDA S-S:::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalSucursalVerSalida(Request $request){

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','traslados.id_salida','sucursals.nombre_sucursal','sucursals.id_sucursal','users.name','users.apellido')
        ->first();

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$traslado->id_salida)
        ->select('nombre_sucursal')
        ->first();

        $traslado->nombre_sucursal_salida = $sucursal->nombre_sucursal;

        $user_registro =DB::table('users')
        ->where('id','=',$traslado->id_user_registro)
        ->first();
        $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;

        $c=0;
        $cc=0;
        foreach ($producto_traslados as $producto_traslado) {
            if($producto_traslado->id_traslado == $request->ver){
                $c = $c + $producto_traslado->cantidad_salida;
            }
        }
        $traslado->cantidad = $c;

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();

        foreach ($producto_traslados as $producto_traslado) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto_traslado->especificaciones = $especificaciones;            
        }

        return view('traslados.sucursalsucursal.versalida',compact('producto_traslados','traslado'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::ENTRADAS PENDIENTES S-S::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalSucursalEntradasPendientes(){

        $traslados = DB::table('traslados')
        ->where('estado_traslado','=','pendiente')
        ->where('establecimiento_salida','=','sucursal')
        ->where('establecimiento_llegada','=','sucursal')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_salida')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.id_llegada','traslados.estado_traslado','sucursals.nombre_sucursal','users.name','users.apellido')
        ->orderBy('traslados.id_traslado', 'desc')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $traslados_aux = [];
        foreach($traslados as $traslado){
            $c=0;
            $guarda = false;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($sucursales as $sucursal) {
                if($traslado->id_llegada == $sucursal->id_sucursal){
                    $traslado->nombre_sucursal_llegada = $sucursal->nombre_sucursal;
                    $guarda = true;
                }
            }
            if ($guarda == true) {
                array_push($traslados_aux,$traslado);
            }
        }

        $traslados = $traslados_aux;

        return view('traslados.sucursalsucursal.entradaspendientes',compact('traslados'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::CREAR ENTRADA S-S::::::::::::::::::::::::::::::::::::::::::::::*/


     public function sucursalSucursalCrearEntrada(Request $request){

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','traslados.id_salida','sucursals.nombre_sucursal','sucursals.id_sucursal','users.name','users.apellido')
        ->first();

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$traslado->id_salida)
        ->select('nombre_sucursal')
        ->first();

        $traslado->nombre_sucursal_salida = $sucursal->nombre_sucursal;

        $user_registro =DB::table('users')
        ->where('id','=',$traslado->id_user_registro)
        ->first();
        $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;
        
        $c=0;
        $cc=0;
        foreach ($producto_traslados as $producto_traslado) {
            if($producto_traslado->id_traslado == $request->ver){
                $c = $c + $producto_traslado->cantidad_salida;
            }
        }
        $traslado->cantidad = $c;

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();


        foreach ($producto_traslados as $producto_traslado) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto_traslado->especificaciones = $especificaciones;            
        }

        return view('traslados.sucursalsucursal.crearentrada',compact('producto_traslados','traslado'));
     }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::GUARDAR ENTRADA S-S::::::::::::::::::::::::::::::::::::::::::::::*/


     public function sucursalSucursalGuardarEntrada(Request $request){

        $productos = json_decode($request->productos);
        $date = Carbon::now();
        $id_user = Auth::id();

        //dd($productos);

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->traslado)
        ->where('estado_traslado','=','pendiente')
        ->first();

        if($traslado != null){

            DB::table('traslados')
            ->where('id_traslado','=',$request->traslado)
            ->update([
                'id_user_llegada' => $id_user,
                'fecha_llegada_traslado'=> $date,
                'novedad_llegada' => $request->novedad,
                'estado_traslado' => 'ingresado'
            ]);

            DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 24, 'accion' => 'ingresar', 'id_elemento' => $request->traslado, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

            foreach ($productos as $producto) {

                DB::table('producto_traslados')
                ->where('id_traslado','=',$request->traslado)
                ->where('id_producto','=',$producto->id_producto)
                ->update([
                    'cantidad_llegada' => intval($producto->cantidad_entrada)
                ]);

                $encontrados = DB::table('producto_sucursales')
                ->where('id_sucursal','=',$request->sucursal)
                ->where('id_producto','=',$producto->id_producto)
                ->increment('cantidad',intval($producto->cantidad_entrada));
                if($encontrados == 0){
                    DB::table('producto_sucursales')->insert(
                        ['id_sucursal' => $request->sucursal, 'id_producto' => $producto->id_producto, 'cantidad' => intval($producto->cantidad_entrada)]
                    );
                }
            }
            $estatus="exito";
        }else{
            $estatus="error";
        }

        return redirect()->route('traslados.sucursalsucursal.listaentradas',['estatus' => $estatus]);
    }
    

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA ENTRADAS S-S:::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalSucursalListaEntradas(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $traslados = DB::table('traslados')
        ->where('establecimiento_salida','=','sucursal')
        ->where('establecimiento_llegada','=','sucursal')
        ->where('estado_traslado','=','ingresado')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_salida')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.id_user_llegada','traslados.estado_traslado','traslados.fecha_llegada_traslado','traslados.id_llegada','sucursals.nombre_sucursal','users.name','users.apellido')
        ->orderBy('traslados.fecha_llegada_traslado', 'desc')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();

        $empleados =DB::table('users')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $traslados_aux = [];
        foreach($traslados as $traslado){
            $c=0;
            $guarda = false;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($empleados as $empleado) {
                if($traslado->id_user_registro == $empleado->id){
                    $traslado->usuario_registro = $empleado->name . " " . $empleado->apellido;
                }
                if($traslado->id_user_llegada == $empleado->id){
                    $traslado->usuario_llegada = $empleado->name . " " . $empleado->apellido;
                }
            }
            foreach ($sucursales as $sucursal) {
                if($traslado->id_llegada == $sucursal->id_sucursal){
                    $traslado->nombre_sucursal_llegada = $sucursal->nombre_sucursal;
                    $guarda = true;
                }
            }
            if ($guarda == true) {
                array_push($traslados_aux,$traslado);
            }
        }
        $traslados = $traslados_aux;

        return view('traslados.sucursalsucursal.listaentradas',compact('traslados','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::VER ENTRADA S-S::::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalSucursalVerEntrada(Request $request){

        if (isset($request->estatus)) {
            $estatus = $request->estatus;
        }else{
            $estatus="";
        }

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','producto_traslados.cantidad_llegada','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->where('estado_traslado','=','ingresado')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.fecha_llegada_traslado','traslados.id_user_registro','traslados.id_user_llegada','traslados.estado_traslado','traslados.novedad_llegada','traslados.id_salida','sucursals.nombre_sucursal','sucursals.id_sucursal','users.name','users.apellido')
        ->first();
        
        if($traslado != null){

            $sucursal = DB::table('sucursals')
            ->where('id_sucursal','=',$traslado->id_salida)
            ->select('nombre_sucursal')
            ->first();
            $traslado->nombre_sucursal_salida = $sucursal->nombre_sucursal;
   
            $user_registro = DB::table('users')
            ->where('id','=',$traslado->id_user_registro)
            ->first();
            $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;
            
            $user_llegada = DB::table('users')
            ->where('id','=',$traslado->id_user_llegada)
            ->first();
            $traslado->user_llegada = $user_registro->name . " " . $user_registro->apellido;

            $c=0;
            $cc=0;
            foreach ($producto_traslados as $producto_traslado) {
                if($producto_traslado->id_traslado == $request->ver){
                    $c = $c + $producto_traslado->cantidad_salida;
                    $cc = $cc + $producto_traslado->cantidad_llegada;
                }
            }
            $traslado->cantidad = $c;
            $traslado->cantidad_llegada = $cc;

            $producto_especificaciones = DB::table('especificaciones')
            ->where('estado_especificacion','=','activo')
            ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();


            foreach ($producto_traslados as $producto_traslado) {
                $especificaciones="";
                foreach ($producto_especificaciones as $producto_especificacion) {
                    if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                        $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                    }
                }
                $producto_traslado->especificaciones = $especificaciones;            
            }

            return view('traslados.sucursalsucursal.verentrada',compact('producto_traslados','traslado'));

        }else{
            return redirect()->route('traslados.sucursalsucursal.listaentradas');
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET S-S:::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalSucursalRedirectSalida(){
        return redirect()->route('traslados.sucursalsucursal.listasalidas');
    }

    public function sucursalSucursalRedirectEntrada(){
        return redirect()->route('traslados.sucursalsucursal.listaentrada');
    }




/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::BODEGA > BODEGA:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::CREAR SALIDA B-B::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaBodegaCrearSalida(Request $request){

        if (isset($request->estatus)) {
            $estatus = $request->estatus;
        }else{
            $estatus="";
        }
        $bodegas = DB::table('bodegas')
        ->where('id_bodega','!=',0)
        ->where('estado_bodega','=','activo')
        ->get();

        return view('traslados.bodegabodega.crearsalida',compact('bodegas','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::GUARDAR SALIDA B-B:::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaBodegaGuardarSalida(Request $request){

        $productos = json_decode($request->productos);
        $date = Carbon::now();
        $id_user = Auth::id();

        $id_traslado = DB::table('traslados')->insertGetId([
            'establecimiento_salida' => 'bodega',
            'id_salida' =>$request->bodegaS,
            'establecimiento_llegada' => 'bodega',
            'id_llegada' =>$request->bodega,
            'id_user_registro' =>$id_user,
            'id_user_salida' => $request->empleado_salida,
            'fecha_salida_traslado' => $date,
            'estado_traslado' => 'pendiente'
         ]);

        DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 24, 'accion' => 'crear', 'id_elemento' => $id_traslado, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

        foreach ($productos as $producto) {

            $producto_bodega = DB::table('producto_bodegas')
            ->where('id_bodega','=',$request->bodegaS)
            ->where('id_producto','=',$producto->id_producto)
            ->select('id_producto_bodega','cantidad')
            ->first();

            if ($producto_bodega->cantidad >= $producto->cantidad) {

                DB::table('producto_traslados')->insert([
                    'id_traslado' => $id_traslado,
                    'id_producto' => $producto->id_producto,
                    'cantidad_salida' => $producto->cantidad
                ]);
                $cantidad = $producto_bodega->cantidad - $producto->cantidad;

                DB::table('producto_bodegas')
                ->where('id_producto_bodega','=',$producto_bodega->id_producto_bodega)
                ->update([
                    'cantidad' => $cantidad
                ]);
            }
        }

        $estatus="exito";

        return redirect()->route('traslados.bodegabodega.crearsalida',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::EMPLEADO TRASLADO B-B::::::::::::::::::::::::::::::::::::::::::*/


    public function traerEmpleadosBodegaBodega(Request $request){//AJAX

        $empleados = DB::table('usuarios_sucursales')
        ->where('usuarios_sucursales.id_sucursal','=',$request->id)
        ->leftJoin('users','users.id','=','usuarios_sucursales.id_usuario')
        ->where('users.tipo_usuario','=','empleado')
        ->where('users.estado_usuario','=','activo')
        ->get();
        return ($empleados);
    }


    /*public function traerProductosBodega(Request $request){//AJAX

        $productos = DB::table('producto_sucursales')
        ->where('id_sucursal','=',$request->id)
        ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','producto_sucursales.cantidad','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca')
        ->get();

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();

        foreach ($productos as $producto) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto->especificaciones = $especificaciones;            
        }
        return ($productos);
    }*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::LISTA SALIDAS B-B:::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaBodegaListaSalidas(){

        $traslados = DB::table('traslados')
        ->where('establecimiento_salida','=','bodega')
        ->where('establecimiento_llegada','=','bodega')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.id_salida','traslados.estado_traslado','bodegas.nombre_bodega','users.name','users.apellido')
        ->orderBy('traslados.id_traslado', 'desc')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();

        $empleados = DB::table('users')
        ->get();

        $bodegas = DB::table('bodegas')
        ->where('id_bodega','!=',0)
        ->where('estado_bodega','=','activo')
        ->get();

        foreach($traslados as $traslado){
            $c=0;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($empleados as $empleado) {
                if($traslado->id_user_registro == $empleado->id){
                    $traslado->usuario_registro = $empleado->name . " " . $empleado->apellido;
                }
            }
            foreach ($bodegas as $bodega) {
                if($traslado->id_salida == $bodega->id_bodega){
                    $traslado->nombre_bodega_salida = $bodega->nombre_bodega;
                }
            }
        }

        return view('traslados.bodegabodega.listasalidas',compact('traslados'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::VER SALIDA B-B::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaBodegaVerSalida(Request $request){

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','traslados.id_salida','bodegas.nombre_bodega','bodegas.id_bodega','users.name','users.apellido')
        ->first();

        $bodega = DB::table('bodegas')
        ->where('id_bodega','=',$traslado->id_salida)
        ->select('nombre_bodega')
        ->first();

        $traslado->nombre_bodega_salida = $bodega->nombre_bodega;

        $user_registro =DB::table('users')
        ->where('id','=',$traslado->id_user_registro)
        ->first();
        $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;
        
        $c=0;
        $cc=0;
        foreach ($producto_traslados as $producto_traslado) {
            if($producto_traslado->id_traslado == $request->ver){
                $c = $c + $producto_traslado->cantidad_salida;
            }
        }
        $traslado->cantidad = $c;

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();


        foreach ($producto_traslados as $producto_traslado) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto_traslado->especificaciones = $especificaciones;            
        }

        return view('traslados.bodegabodega.versalida',compact('producto_traslados','traslado'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::ENTRADAS PENDIENTES B-B:::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaBodegaEntradasPendientes(){

        $traslados = DB::table('traslados')
        ->where('estado_traslado','=','pendiente')
        ->where('establecimiento_salida','=','bodega')
        ->where('establecimiento_llegada','=','bodega')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.id_salida','traslados.estado_traslado','bodegas.nombre_bodega','users.name','users.apellido')
        ->orderBy('traslados.id_traslado', 'desc')
        ->get();

        $bodegas = DB::table('bodegas')
        ->where('id_bodega','!=',0)
        ->where('estado_bodega','=','activo')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();


        foreach($traslados as $traslado){
            $c=0;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($bodegas as $bodega) {
                if($traslado->id_salida == $bodega->id_bodega){
                    $traslado->nombre_bodega_salida = $bodega->nombre_bodega;
                }
            }
        }

        return view('traslados.bodegabodega.entradaspendientes',compact('traslados'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::CREAR ENTRADA B-B:::::::::::::::::::::::::::::::::::::::::::::::*/


     public function bodegaBodegaCrearEntrada(Request $request){

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','traslados.id_salida','bodegas.nombre_bodega','bodegas.id_bodega','users.name','users.apellido')
        ->first();

        $bodega = DB::table('bodegas')
        ->where('id_bodega','=',$traslado->id_salida)
        ->select('nombre_bodega')
        ->first();

        $traslado->nombre_bodega_salida = $bodega->nombre_bodega;

        $user_registro =DB::table('users')
        ->where('id','=',$traslado->id_user_registro)
        ->first();
        $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;
        
        $c=0;
        $cc=0;
        foreach ($producto_traslados as $producto_traslado) {
            if($producto_traslado->id_traslado == $request->ver){
                $c = $c + $producto_traslado->cantidad_salida;
            }
        }
        $traslado->cantidad = $c;

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();


        foreach ($producto_traslados as $producto_traslado) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto_traslado->especificaciones = $especificaciones;            
        }

        return view('traslados.bodegabodega.crearentrada',compact('producto_traslados','traslado'));
     }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::GUARDAR ENTRADA B-B::::::::::::::::::::::::::::::::::::::::::::*/


     public function bodegaBodegaGuardarEntrada(Request $request){

        $productos = json_decode($request->productos);
        $date = Carbon::now();
        $id_user = Auth::id();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->traslado)
        ->where('estado_traslado','=','pendiente')
        ->first();

        if($traslado != null){

            DB::table('traslados')
            ->where('id_traslado','=',$request->traslado)
            ->update([
                'id_user_llegada' => $id_user,
                'fecha_llegada_traslado'=> $date,
                'novedad_llegada' => $request->novedad,
                'estado_traslado' => 'ingresado'
            ]);

            DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 24, 'accion' => 'ingresar', 'id_elemento' => $request->traslado, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

            foreach ($productos as $producto) {

                DB::table('producto_traslados')
                ->where('id_traslado','=',$request->traslado)
                ->where('id_producto','=',$producto->id_producto)
                ->update([
                    'cantidad_llegada' => $producto->cantidad
                ]);

                $encontrados = DB::table('producto_bodegas')
                ->where('id_bodega','=',$request->bodega)
                ->where('id_producto','=',$producto->id_producto)
                ->increment('cantidad',$producto->cantidad);
                if($encontrados == 0){
                    DB::table('producto_bodegas')->insert(
                        ['id_bodega' => $request->bodega, 'id_producto' => $producto->id_producto, 'cantidad' => $producto->cantidad]
                    );
                }
            }
            $estatus="exito";
        }else{
            $estatus="error";
        }

        return redirect()->route('traslados.bodegabodega.listaentradas',['estatus' => $estatus]);
    }
    

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA ENTRADAS B-B:::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaBodegaListaEntradas(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $traslados = DB::table('traslados')
        ->where('establecimiento_salida','=','bodega')
        ->where('establecimiento_llegada','=','bodega')
        ->where('estado_traslado','=','ingresado')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.id_user_llegada','traslados.estado_traslado','traslados.fecha_llegada_traslado','traslados.id_salida','bodegas.nombre_bodega','users.name','users.apellido')
        ->orderBy('traslados.fecha_llegada_traslado', 'desc')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();

        $bodegas = DB::table('bodegas')
        ->where('id_bodega','!=',0)
        ->where('estado_bodega','=','activo')
        ->get();

        $empleados =DB::table('users')
        ->get();

        foreach($traslados as $traslado){
            $c=0;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($empleados as $empleado) {
                if($traslado->id_user_registro == $empleado->id){
                    $traslado->usuario_registro = $empleado->name . " " . $empleado->apellido;
                }
                if($traslado->id_user_llegada == $empleado->id){
                    $traslado->usuario_llegada = $empleado->name . " " . $empleado->apellido;
                }
            }
            foreach ($bodegas as $bodega) {
                if($traslado->id_salida == $bodega->id_bodega){
                    $traslado->nombre_bodega_salida = $bodega->nombre_bodega;
                }
            }
        }
        return view('traslados.bodegabodega.listaentradas',compact('traslados','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::VER ENTRADA B-B:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaBodegaVerEntrada(Request $request){

        if (isset($request->estatus)) {
            $estatus = $request->estatus;
        }else{
            $mensaje="";
        }

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','producto_traslados.cantidad_llegada','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->where('estado_traslado','=','ingresado')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.fecha_llegada_traslado','traslados.id_user_registro','traslados.id_user_llegada','traslados.estado_traslado','traslados.novedad_llegada','traslados.id_salida','bodegas.nombre_bodega','bodegas.id_bodega','users.name','users.apellido')
        ->first();
        
        if($traslado != null){

            $bodega = DB::table('bodegas')
            ->where('id_bodega','=',$traslado->id_salida)
            ->select('nombre_bodega')
            ->first();
            $traslado->nombre_bodega_salida = $bodega->nombre_bodega;
   
            $user_registro = DB::table('users')
            ->where('id','=',$traslado->id_user_registro)
            ->first();
            $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;
            
            $user_llegada = DB::table('users')
            ->where('id','=',$traslado->id_user_llegada)
            ->first();
            $traslado->user_llegada = $user_registro->name . " " . $user_registro->apellido;

            $c=0;
            $cc=0;
            foreach ($producto_traslados as $producto_traslado) {
                if($producto_traslado->id_traslado == $request->ver){
                    $c = $c + $producto_traslado->cantidad_salida;
                    $cc = $cc + $producto_traslado->cantidad_llegada;
                }
            }
            $traslado->cantidad = $c;
            $traslado->cantidad_llegada = $cc;

            $producto_especificaciones = DB::table('especificaciones')
            ->where('estado_especificacion','=','activo')
            ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();


            foreach ($producto_traslados as $producto_traslado) {
                $especificaciones="";
                foreach ($producto_especificaciones as $producto_especificacion) {
                    if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                        $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                    }
                }
                $producto_traslado->especificaciones = $especificaciones;            
            }

            return view('traslados.bodegabodega.verentrada',compact('producto_traslados','traslado'));

        }else{
            return redirect()->route('traslados.bodegabodega.listaentradas');
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET B-B:::::::::::::::::::::::::::::::::::::::::::::*/


    public function bodegaBodegaRedirectSalida(){
        return redirect()->route('traslados.bodegabodega.listasalidas');
    }

    public function bodegaBodegaRedirectEntrada(){
        return redirect()->route('traslados.bodegabodega.listaentrada');
    }















/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::SUCURSAL > BODEGA:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::CREAR SALIDA S-B:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalBodegaCrearSalida(Request $request){

        if (isset($request->estatus)) {
            $estatus = $request->estatus;
        }else{
            $estatus="";
        }
        $bodegas = DB::table('bodegas')
        ->where('id_bodega','!=',0)
        ->where('estado_bodega','=','activo')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $sucursales_llegada = DB::table('sucursals')
        ->where('id_sucursal','!=',0)
        ->where('estado_sucursal','=','activo')
        ->get();

        return view('traslados.sucursalbodega.crearsalida',compact('sucursales','sucursales_llegada','bodegas','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR SALIDA S-B::::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalBodegaGuardarSalida(Request $request){
        //dd($request);
        $productos = json_decode($request->productos);
        $date = Carbon::now();
        $id_user = Auth::id();

        $id_traslado = DB::table('traslados')->insertGetId([
            'establecimiento_salida' => 'sucursal',
            'id_salida' =>$request->sucursalS,
            'establecimiento_llegada' => 'bodega',
            'id_llegada' =>$request->bodega,
            'id_user_registro' =>$id_user,
            'id_user_salida' => $request->empleado_salida,
            'fecha_salida_traslado' => $date,
            'estado_traslado' => 'pendiente'
         ]);

        DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 24, 'accion' => 'crear', 'id_elemento' => $id_traslado, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

        foreach ($productos as $producto) {

            $producto_sucursal = DB::table('producto_sucursales')
            ->where('id_sucursal','=',$request->sucursalS)
            ->where('id_producto','=',$producto->id_producto)
            ->select('id_producto_sucursal','cantidad')
            ->first();

            if ($producto_sucursal->cantidad >= $producto->cantidad) {

                DB::table('producto_traslados')->insert([
                    'id_traslado' => $id_traslado,
                    'id_producto' => $producto->id_producto,
                    'cantidad_salida' => $producto->cantidad
                ]);
                $cantidad = $producto_sucursal->cantidad - $producto->cantidad;

                DB::table('producto_sucursales')
                ->where('id_producto_sucursal','=',$producto_sucursal->id_producto_sucursal)
                ->update([
                    'cantidad' => $cantidad
                ]);
            }
        }

        $estatus="exito";

        return redirect()->route('traslados.sucursalbodega.crearsalida',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::EMPLEADO TRASLADO S-B::::::::::::::::::::::::::::::::::::::::::*/


    public function traerEmpleadossucursalBodega(Request $request){//AJAX

        $empleados = DB::table('usuarios_sucursales')
        ->where('usuarios_sucursales.id_sucursal','=',$request->id)
        ->leftJoin('users','users.id','=','usuarios_sucursales.id_usuario')
        ->where('users.tipo_usuario','=','empleado')
        ->where('users.estado_usuario','=','activo')
        ->get();
        return ($empleados);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::PRODUCTOS TRASLADO S-B:::::::::::::::::::::::::::::::::::::::::*/


    // public function traerProductosSucursal(Request $request){//AJAX

    //     $productos = DB::table('producto_sucursales')
    //     ->where('id_sucursal','=',$request->id)
    //     ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
    //     ->where('productos.estado_producto','=','activo')
    //     ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
    //     ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
    //     ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
    //     ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','producto_sucursales.cantidad','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca')
    //     ->get();

    //     $producto_especificaciones = DB::table('especificaciones')
    //     ->where('estado_especificacion','=','activo')
    //     ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
    //     ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
    //     ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
    //     ->get();

    //     foreach ($productos as $producto) {
    //         $especificaciones="";
    //         foreach ($producto_especificaciones as $producto_especificacion) {
    //             if($producto_especificacion->id_producto == $producto->id_producto){
    //                 $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
    //             }
    //         }
    //         $producto->especificaciones = $especificaciones;            
    //     }
    //     return ($productos);
    // }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::LISTA SALIDAS S-B::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalBodegaListaSalidas(){

        $traslados = DB::table('traslados')
        ->where('establecimiento_salida','=','sucursal')
        ->where('establecimiento_llegada','=','bodega')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_salida')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.id_salida','traslados.estado_traslado','sucursals.nombre_sucursal','users.name','users.apellido','bodegas.nombre_bodega')
        ->orderBy('traslados.id_traslado', 'desc')
        ->get();

        // dd($traslados);

        $producto_traslados = DB::table('producto_traslados')
        ->get();

        $empleados = DB::table('users')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $traslados_aux = [];
        foreach($traslados as $traslado){
            $c=0;
            $guarda = false;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($empleados as $empleado) {
                if($traslado->id_user_registro == $empleado->id){
                    $traslado->usuario_registro = $empleado->name . " " . $empleado->apellido;
                }
            }
            foreach ($sucursales as $sucursal) {
                if($traslado->id_salida == $sucursal->id_sucursal){
                    $traslado->nombre_sucursal_salida = $sucursal->nombre_sucursal;
                    $guarda = true;
                }
            }
            if ($guarda == true) {
                array_push($traslados_aux,$traslado);
            }
        }
        $traslados = $traslados_aux;

        // dd($traslados);

        return view('traslados.sucursalbodega.listasalidas',compact('traslados'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::VER SALIDA S-B:::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalBodegaVerSalida(Request $request){

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','traslados.id_salida','bodegas.nombre_bodega','bodegas.id_bodega','users.name','users.apellido')
        ->first();

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$traslado->id_salida)
        ->select('nombre_sucursal')
        ->first();

        $traslado->nombre_sucursal_salida = $sucursal->nombre_sucursal;

        $user_registro =DB::table('users')
        ->where('id','=',$traslado->id_user_registro)
        ->first();
        $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;

        $c=0;
        $cc=0;
        foreach ($producto_traslados as $producto_traslado) {
            if($producto_traslado->id_traslado == $request->ver){
                $c = $c + $producto_traslado->cantidad_salida;
            }
        }
        $traslado->cantidad = $c;

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();

        foreach ($producto_traslados as $producto_traslado) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto_traslado->especificaciones = $especificaciones;            
        }

        return view('traslados.sucursalbodega.versalida',compact('producto_traslados','traslado'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::ENTRADAS PENDIENTES S-B::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalBodegaEntradasPendientes(){

        $traslados = DB::table('traslados')
        ->where('estado_traslado','=','pendiente')
        ->where('establecimiento_salida','=','sucursal')
        ->where('establecimiento_llegada','=','bodega')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_salida')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.id_llegada','traslados.estado_traslado','sucursals.nombre_sucursal','bodegas.nombre_bodega','users.name','users.apellido')
        ->orderBy('traslados.id_traslado', 'desc')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $traslados_aux = [];
        foreach($traslados as $traslado){
            $c=0;
            $guarda = false;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($sucursales as $sucursal) {
                if($traslado->id_llegada == $sucursal->id_sucursal){
                    $traslado->nombre_sucursal_llegada = $sucursal->nombre_sucursal;
                    $guarda = true;
                }
            }
            if ($guarda == true) {
                array_push($traslados_aux,$traslado);
            }
        }

        $traslados = $traslados_aux;

        return view('traslados.sucursalbodega.entradaspendientes',compact('traslados'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::CREAR ENTRADA S-B::::::::::::::::::::::::::::::::::::::::::::::*/


     public function sucursalBodegaCrearEntrada(Request $request){

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_salida')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','traslados.id_salida','sucursals.nombre_sucursal','sucursals.id_sucursal','bodegas.nombre_bodega','users.name','users.apellido')
        ->first();

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$traslado->id_salida)
        ->select('nombre_sucursal')
        ->first();

        $traslado->nombre_sucursal_salida = $sucursal->nombre_sucursal;

        $user_registro =DB::table('users')
        ->where('id','=',$traslado->id_user_registro)
        ->first();
        $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;
        
        $c=0;
        $cc=0;
        foreach ($producto_traslados as $producto_traslado) {
            if($producto_traslado->id_traslado == $request->ver){
                $c = $c + $producto_traslado->cantidad_salida;
            }
        }
        $traslado->cantidad = $c;

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();


        foreach ($producto_traslados as $producto_traslado) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                    $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto_traslado->especificaciones = $especificaciones;            
        }

        return view('traslados.sucursalbodega.crearentrada',compact('producto_traslados','traslado'));
     }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::GUARDAR ENTRADA S-B::::::::::::::::::::::::::::::::::::::::::::::*/


     public function sucursalBodegaGuardarEntrada(Request $request){

        $productos = json_decode($request->productos);
        $date = Carbon::now();
        $id_user = Auth::id();        

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->traslado)
        ->where('estado_traslado','=','pendiente')
        ->first();

        if($traslado != null){

            DB::table('traslados')
            ->where('id_traslado','=',$request->traslado)
            ->update([
                'id_user_llegada' => $id_user,
                'fecha_llegada_traslado'=> $date,
                'novedad_llegada' => $request->novedad,
                'estado_traslado' => 'ingresado'
            ]);

            DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 24, 'accion' => 'ingresar', 'id_elemento' => $request->traslado, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

            foreach ($productos as $producto) {

                DB::table('producto_traslados')
                ->where('id_traslado','=',$request->traslado)
                ->where('id_producto','=',$producto->id_producto)
                ->update([
                    'cantidad_llegada' => intval($producto->cantidad_entrada)
                ]);

                $encontrados = DB::table('producto_bodegas')
                ->where('id_bodega','=',$traslado->id_llegada)
                ->where('id_producto','=',$producto->id_producto)
                ->increment('cantidad',intval($producto->cantidad_entrada));
                if($encontrados == 0){
                    DB::table('producto_bodegas')->insert(
                        ['id_bodega' => $traslado->id_llegada, 'id_producto' => $producto->id_producto, 'cantidad' => intval($producto->cantidad_entrada)]
                    );
                }
            }
            $estatus="exito";
        }else{
            $estatus="error";
        }

        return redirect()->route('traslados.sucursalbodega.listaentradas',['estatus' => $estatus]);
    }
    

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA ENTRADAS S-B:::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalBodegaListaEntradas(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $traslados = DB::table('traslados')
        ->where('establecimiento_salida','=','sucursal')
        ->where('establecimiento_llegada','=','bodega')
        ->where('estado_traslado','=','ingresado')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_salida')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.id_user_llegada','traslados.estado_traslado','traslados.fecha_llegada_traslado','traslados.id_llegada','sucursals.nombre_sucursal','users.name','users.apellido','bodegas.nombre_bodega')
        ->orderBy('traslados.fecha_llegada_traslado', 'desc')
        ->get();

        $producto_traslados = DB::table('producto_traslados')
        ->get();

        $empleados =DB::table('users')
        ->get();

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

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{
            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $traslados_aux = [];
        foreach($traslados as $traslado){
            $c=0;
            $guarda = false;
            foreach ($producto_traslados as $producto_traslado) {
                if($traslado->id_traslado == $producto_traslado->id_traslado){
                    $c = $c + $producto_traslado->cantidad_salida;
                }
            }
            $traslado->cantidad_productos = $c;
            foreach ($empleados as $empleado) {
                if($traslado->id_user_registro == $empleado->id){
                    $traslado->usuario_registro = $empleado->name . " " . $empleado->apellido;
                }
                if($traslado->id_user_llegada == $empleado->id){
                    $traslado->usuario_llegada = $empleado->name . " " . $empleado->apellido;
                }
            }
            foreach ($sucursales as $sucursal) {
                if($traslado->id_llegada == $sucursal->id_sucursal){
                    $traslado->nombre_sucursal_llegada = $sucursal->nombre_sucursal;
                    $guarda = true;
                }
            }
            if ($guarda == true) {
                array_push($traslados_aux,$traslado);
            }
        }
        $traslados = $traslados_aux;

        return view('traslados.sucursalbodega.listaentradas',compact('traslados','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::VER ENTRADA S-B::::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalBodegaVerEntrada(Request $request){

        if (isset($request->estatus)) {
            $estatus = $request->estatus;
        }else{
            $estatus="";
        }

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','producto_traslados.cantidad_llegada','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->where('estado_traslado','=','ingresado')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_salida')
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_llegada')
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.fecha_llegada_traslado','traslados.id_user_registro','traslados.id_user_llegada','traslados.estado_traslado','traslados.novedad_llegada','traslados.id_salida','sucursals.nombre_sucursal','sucursals.id_sucursal','users.name','users.apellido','bodegas.nombre_bodega')
        ->first();
        
        if($traslado != null){

            $sucursal = DB::table('sucursals')
            ->where('id_sucursal','=',$traslado->id_salida)
            ->select('nombre_sucursal')
            ->first();
            $traslado->nombre_sucursal_salida = $sucursal->nombre_sucursal;
   
            $user_registro = DB::table('users')
            ->where('id','=',$traslado->id_user_registro)
            ->first();
            $traslado->user_registro = $user_registro->name . " " . $user_registro->apellido;
            
            $user_llegada = DB::table('users')
            ->where('id','=',$traslado->id_user_llegada)
            ->first();
            $traslado->user_llegada = $user_registro->name . " " . $user_registro->apellido;

            $c=0;
            $cc=0;
            foreach ($producto_traslados as $producto_traslado) {
                if($producto_traslado->id_traslado == $request->ver){
                    $c = $c + $producto_traslado->cantidad_salida;
                    $cc = $cc + $producto_traslado->cantidad_llegada;
                }
            }
            $traslado->cantidad = $c;
            $traslado->cantidad_llegada = $cc;

            $producto_especificaciones = DB::table('especificaciones')
            ->where('estado_especificacion','=','activo')
            ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();


            foreach ($producto_traslados as $producto_traslado) {
                $especificaciones="";
                foreach ($producto_especificaciones as $producto_especificacion) {
                    if($producto_especificacion->id_producto == $producto_traslado->id_producto){
                        $especificaciones= ", ".$producto_especificacion->nombre_clasificacion.": ".$producto_especificacion->nombre_especificacion.$especificaciones;
                    }
                }
                $producto_traslado->especificaciones = $especificaciones;            
            }

            return view('traslados.sucursalbodega.verentrada',compact('producto_traslados','traslado'));

        }else{
            return redirect()->route('traslados.sucursalbodega.listaentradas');
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET S-B:::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursalBodegaRedirectSalida(){
        return redirect()->route('traslados.sucursalbodega.listasalidas');
    }

    public function sucursalBodegaRedirectEntrada(){
        return redirect()->route('traslados.sucursalbodega.listaentrada');
    }





















/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR::::::::::::::::::::::::::::::::::::::::::::::::*/
}
