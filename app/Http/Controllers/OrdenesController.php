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

class OrdenesController extends Controller
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


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ORDENES APARTADAS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA DE APARTADAS::::::::::::::::::::::::::::::::::::::::::::*/


   public function lista_apartados(Request $request){

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

        return view('ordenes.apartados.lista',compact('usuarios_sucursales','estatus'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::BUSCAR ORDENES APARTADAS DE SUCURSAL::::::::::::::::::::::::::::::::::::*/


    public function apartados_sucursal(Request $request){

        $ordenes = DB::table('ordenes')
        ->where('ordenes.id_sucursal','=',$request->sucursal)
        ->where('ordenes.estado_orden','=','apartado')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
        ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
        ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
        ->select('ordenes.id_orden','sucursals.nombre_sucursal','cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion')
        ->get();

        foreach ($ordenes as $orden) {
            $lente = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_lente)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($lente)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_lente = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
                $orden->nombre_laboratorio = $lente->nombre_marca;
            }


            $montura = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_montura)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($montura)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_montura = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

            }else{
                $orden->montura_orden = "Montura propiedad del cliente";
            }
            
        }

        return ($ordenes);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::CAMBIAR APARTADAS -> PENDIENTE::::::::::::::::::::::::::::::::::::::*/


    public function apartados_cambiar(Request $request){
        // dd($request,"controller");
        $ordenes = json_decode($request->ordenes);
        // dd($ordenes,$request);

        $id_orden_pendiente_historial = DB::table('ordenes_pendientes_historial')->insertGetId([
            'id_sucursal' => $request->sucursal,
            'fecha_orden_pendiente_historial' => Carbon::now(),
            'id_usuario_registro' => Auth::id()
        ]);

        foreach ($ordenes as $orden) {

            DB::table('ordenes')
            ->where('id_orden','=',$orden)
            ->update([
                'estado_orden' => 'pendiente'
            ]);

            DB::table('ordenes_pendientes_pivote')->insert([
                'id_orden_pendiente_historial' => $id_orden_pendiente_historial,
                'id_orden' => $orden,
                'estado_orden_pendiente_pivote' => 'activo'
            ]);
        }

        $estatus = "exito";
        return redirect()->route('ordenes.apartados.lista',['estatus' => $estatus]);

    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ORDENES APARTADAS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA DE PENDIENTES:::::::::::::::::::::::::::::::::::::::::::*/


   public function lista_pendientes(Request $request){

        // dd("apartadas");

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

        $laboratorios = DB::table('laboratorios')
        ->where('estado_laboratorio','=','activo')
        ->get();

        return view('ordenes.pendientes.lista',compact('usuarios_sucursales','estatus','laboratorios'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::BUSCAR ORDENES PENDIENTES CON SUCURSAL Y LABORATORIO::::::::::::::::::::::::::::::*/


    public function pendientes_sucursal_laboratorio(Request $request){

        $ordenes = DB::table('ordenes')
        ->where('ordenes.id_sucursal','=',$request->sucursal)
        ->where('ordenes.estado_orden','=','pendiente')
        ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
        ->where('cotizaciones.id_laboratorio','=',$request->laboratorio)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
        ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
        ->select('ordenes.id_orden','sucursals.nombre_sucursal','cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','laboratorios.nombre_laboratorio')
        ->get();

        foreach ($ordenes as $orden) {
            $lente = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_lente)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($lente)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_lente = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
                // $orden->nombre_laboratorio = $lente->nombre_marca;
            }


            $montura = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_montura)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($montura)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_montura = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

            }else{
                $orden->montura_orden = "Montura propiedad del cliente";
            }
            
        }

        return ($ordenes);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::CAMBIAR PENDIENTES -> PRODUCCION::::::::::::::::::::::::::::::::::::*/


    public function pendientes_cambiar(Request $request){
        
        $ordenes = json_decode($request->ordenes);
        // dd($ordenes,$request);

        $id_orden_produccion_historial = DB::table('ordenes_produccion_historial')->insertGetId([
            'id_sucursal' => $request->sucursal,
            'id_laboratorio' => $request->laboratorio,
            'fecha_orden_produccion_historial' => Carbon::now(),
            'id_usuario_registro' => Auth::id(),
            'responsable' => $request->responsable
        ]);

        foreach ($ordenes as $orden) {

            DB::table('ordenes')
            ->where('id_orden','=',$orden)
            ->update([
                'estado_orden' => 'produccion'
            ]);

            DB::table('ordenes_produccion_pivote')->insert([
                'id_orden_produccion_historial' => $id_orden_produccion_historial,
                'id_orden' => $orden,
                'estado_orden_produccion_pivote' => 'activo'
            ]);
        }

        $estatus = "exito";
        return redirect()->route('ordenes.produccion.detalle.historial.envios',['id_historial' => $id_orden_produccion_historial]);
        // return redirect()->route('ordenes.pendientes.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ORDENES PRODUCCION:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA DE PRODUCCION:::::::::::::::::::::::::::::::::::::::::::*/


   public function lista_produccion(Request $request){

        // dd("apartadas");

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

        $laboratorios = DB::table('laboratorios')
        ->where('estado_laboratorio','=','activo')
        ->get();

        return view('ordenes.produccion.lista',compact('usuarios_sucursales','estatus','laboratorios'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::BUSCAR ORDENES PRODUCCION CON SUCURSAL Y LABORATORIO::::::::::::::::::::::::::::::*/


    public function produccion_sucursal_laboratorio(Request $request){

        $ordenes = DB::table('ordenes')
        ->where('ordenes.id_sucursal','=',$request->sucursal)
        ->where('ordenes.estado_orden','=','produccion')
        ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
        ->where('cotizaciones.id_laboratorio','=',$request->laboratorio)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
        ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
        ->select('ordenes.id_orden','sucursals.nombre_sucursal','cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','laboratorios.nombre_laboratorio')
        ->get();

        foreach ($ordenes as $orden) {
            $lente = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_lente)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($lente)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_lente = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
                // $orden->nombre_laboratorio = $lente->nombre_marca;
            }


            $montura = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_montura)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($montura)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_montura = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

            }else{
                $orden->montura_orden = "Montura propiedad del cliente";
            }
            
        }

        return ($ordenes);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::CAMBIAR PRODUCCION -> CALIDAD:::::::::::::::::::::::::::::::::::::::*/


    public function produccion_cambiar(Request $request){
        // dd($request,"controller");
        $ordenes = json_decode($request->ordenes);
        // dd($ordenes,$request);

        $id_orden_calidad_historial = DB::table('ordenes_calidad_historial')->insertGetId([
            'id_sucursal' => $request->sucursal,
            'id_laboratorio' => $request->laboratorio,
            'fecha_orden_calidad_historial' => Carbon::now(),
            'id_usuario_registro' => Auth::id(),
            'responsable' => $request->responsable
        ]);

        foreach ($ordenes as $orden) {

            DB::table('ordenes')
            ->where('id_orden','=',$orden)
            ->update([
                'estado_orden' => 'calidad'
            ]);

            DB::table('ordenes_calidad_pivote')->insert([
                'id_orden_calidad_historial' => $id_orden_calidad_historial,
                'id_orden' => $orden,
                'estado_orden_calidad_pivote' => 'activo'
            ]);
        }

        $estatus = "exito";
        return redirect()->route('ordenes.produccion.lista',['estatus' => $estatus]);

    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::PRODUCCION HISTORIAL ENVIOS:::::::::::::::::::::::::::::::::::::::::*/


    public function produccion_historial_envios(Request $request){

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


        return view('ordenes.produccion.envios',compact('usuarios_sucursales'));

    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::HISTORIAL ORDENES ENVIADAS DE SUCURSAL:::::::::::::::::::::::::::::::::*/


    public function produccion_historial_envios_sucursal(Request $request){

        $ordenes = DB::table('ordenes_produccion_historial')
        ->where('id_sucursal','=',$request->sucursal)
        ->leftJoin('users','users.id','=','ordenes_produccion_historial.id_usuario_registro')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','ordenes_produccion_historial.id_laboratorio')
        ->orderBy('id_orden_produccion_historial','desc')
        ->get();

        foreach ($ordenes as $orden) {
            
            $ordenes_pivotes = DB::table('ordenes_produccion_pivote')
            ->where('estado_orden_produccion_pivote','=','activo')
            ->where('id_orden_produccion_historial','=',$orden->id_orden_produccion_historial)
            ->count();

            $orden->cantidad_ordenes = $ordenes_pivotes;
        }

        return ($ordenes);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::DETALLE HISTORIAL ORDENES ENVIADAS DE SUCURSAL::::::::::::::::::::::::::::::::*/


    public function produccion_detalle_historial_envios($id_historial){

        // dd($id_historial);

        $historial_orden = DB::table('ordenes_produccion_historial')
        ->where('id_orden_produccion_historial','=',$id_historial)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes_produccion_historial.id_sucursal')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','ordenes_produccion_historial.id_laboratorio')
        ->leftJoin('users','users.id','=','ordenes_produccion_historial.id_usuario_registro')
        ->first();

        if (isset($historial_orden)) {

            $ordenes = DB::table('ordenes_produccion_pivote')
            ->where('id_orden_produccion_historial','=',$id_historial)
            ->leftJoin('ordenes','ordenes.id_orden','=','ordenes_produccion_pivote.id_orden')
            ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
            ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
            ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
            ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
            ->select('ordenes.id_orden','sucursals.nombre_sucursal','cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','laboratorios.nombre_laboratorio','ordenes.estado_orden')
            ->get();

            foreach ($ordenes as $orden) {
                $lente = DB::table('productos')
                ->where('productos.id_producto','=',$orden->id_lente)
                ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                ->first();

                if (isset($lente)) {
                    $especificaciones = DB::table('producto_especificaciones')
                    ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                    ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                    ->get();
                    $len = count($especificaciones);
                    $especificaciones_lente = "";
                    foreach ($especificaciones as $especificacion) {
                        if($especificacion == $especificaciones[$len - 1]){
                            $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                        }else{
                            $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                        }
                    }
                    $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
                    // $orden->nombre_laboratorio = $lente->nombre_marca;
                }


                $montura = DB::table('productos')
                ->where('productos.id_producto','=',$orden->id_montura)
                ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                ->first();

                if (isset($montura)) {
                    $especificaciones = DB::table('producto_especificaciones')
                    ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                    ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                    ->get();
                    $len = count($especificaciones);
                    $especificaciones_montura = "";
                    foreach ($especificaciones as $especificacion) {
                        if($especificacion == $especificaciones[$len - 1]){
                            $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                        }else{
                            $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                        }
                    }
                    $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

                }else{
                    $orden->montura_orden = "Montura propiedad del cliente";
                }
            }

            $estatus = "";

            // dd($historial_orden);
            return view('ordenes.produccion.detalleenvio',compact('ordenes','historial_orden','estatus'));

        }else{
            $estatus = "noExiste";
            return redirect()->route('ordenes.produccion.historial.envios',['estatus' => $estatus]);
        }

    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::IMPRIMIR DETALLE HISTORIAL ORDENES PRODUCCION::::::::::::::::::::::::::::::::*/


    public function produccion_imprimir_historial_envio($id_historial){
        // dd($id_historial);

        $cliente_appweb = DB::table('cliente_appweb')
        ->where('estado_cliente_appweb','=','activo')
        ->first();

        if (!isset($cliente_appweb)){
            dd("Debe crear el Cliente SAS, Contactar App Web");
        }

        $historial_orden = DB::table('ordenes_produccion_historial')
        ->where('id_orden_produccion_historial','=',$id_historial)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes_produccion_historial.id_sucursal')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','ordenes_produccion_historial.id_laboratorio')
        ->leftJoin('users','users.id','=','ordenes_produccion_historial.id_usuario_registro')
        ->first();

        if (isset($historial_orden)) {

            $ordenes = DB::table('ordenes_produccion_pivote')
            ->where('id_orden_produccion_historial','=',$id_historial)
            ->leftJoin('ordenes','ordenes.id_orden','=','ordenes_produccion_pivote.id_orden')
            ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
            ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
            ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
            ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
            // ->select('ordenes.id_orden','sucursals.nombre_sucursal','cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','laboratorios.nombre_laboratorio','ordenes.estado_orden')
            ->get();

            foreach ($ordenes as $orden) {
                $lente = DB::table('productos')
                ->where('productos.id_producto','=',$orden->id_lente)
                ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                ->first();

                if (isset($lente)) {
                    $especificaciones = DB::table('producto_especificaciones')
                    ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                    ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                    ->get();
                    $len = count($especificaciones);
                    $especificaciones_lente = "";
                    foreach ($especificaciones as $especificacion) {
                        if($especificacion == $especificaciones[$len - 1]){
                            $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                        }else{
                            $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                        }
                    }
                    $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
                    // $orden->nombre_laboratorio = $lente->nombre_marca;
                }


                $montura = DB::table('productos')
                ->where('productos.id_producto','=',$orden->id_montura)
                ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                ->first();

                if (isset($montura)) {
                    $especificaciones = DB::table('producto_especificaciones')
                    ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                    ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                    ->get();
                    $len = count($especificaciones);
                    $especificaciones_montura = "";
                    foreach ($especificaciones as $especificacion) {
                        if($especificacion == $especificaciones[$len - 1]){
                            $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                        }else{
                            $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                        }
                    }
                    $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

                }else{
                    $orden->montura_orden = "Montura propiedad del cliente";
                }

                $orden->id_orden = str_pad($orden->id_orden, 8, "0", STR_PAD_LEFT);
            }

            $estatus = "";

            // dd($ordenes);
            return view('ordenes.produccion.ver',compact('ordenes','historial_orden','estatus','cliente_appweb'));

        }else{
            $estatus = "noExiste";
            return redirect()->route('ordenes.produccion.historial.envios',['estatus' => $estatus]);
        }

    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::PRODUCCION HISTORIAL INGRESOS:::::::::::::::::::::::::::::::::::::::*/


    public function produccion_historial_ingresos(Request $request){

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


        return view('ordenes.produccion.ingresos',compact('usuarios_sucursales'));

    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::HISTORIAL ORDENES INGRESADAS DE SUCURSAL:::::::::::::::::::::::::::::::*/


    public function produccion_historial_ingresos_sucursal(Request $request){

        $ordenes = DB::table('ordenes_calidad_historial')
        ->where('id_sucursal','=',$request->sucursal)
        ->leftJoin('users','users.id','=','ordenes_calidad_historial.id_usuario_registro')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','ordenes_calidad_historial.id_laboratorio')
        ->orderBy('id_orden_calidad_historial','desc')
        ->get();

        foreach ($ordenes as $orden) {
            
            $ordenes_pivotes = DB::table('ordenes_calidad_pivote')
            ->where('estado_orden_calidad_pivote','=','activo')
            ->where('id_orden_calidad_historial','=',$orden->id_orden_calidad_historial)
            ->count();

            $orden->cantidad_ordenes = $ordenes_pivotes;
        }

        return ($ordenes);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::DETALLE HISTORIAL ORDENES INGRESADAS DE SUCURSAL:::::::::::::::::::::::::::::::*/


    public function produccion_detalle_historial_ingresos($id_historial){

        // dd($id_historial);

        $historial_orden = DB::table('ordenes_calidad_historial')
        ->where('id_orden_calidad_historial','=',$id_historial)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes_calidad_historial.id_sucursal')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','ordenes_calidad_historial.id_laboratorio')
        ->leftJoin('users','users.id','=','ordenes_calidad_historial.id_usuario_registro')
        ->first();

        if (isset($historial_orden)) {

            $ordenes = DB::table('ordenes_calidad_pivote')
            ->where('id_orden_calidad_historial','=',$id_historial)
            ->leftJoin('ordenes','ordenes.id_orden','=','ordenes_calidad_pivote.id_orden')
            ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
            ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
            ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
            ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
            ->select('ordenes.id_orden','sucursals.nombre_sucursal','cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','laboratorios.nombre_laboratorio','ordenes.estado_orden')
            ->get();

            foreach ($ordenes as $orden) {
                $lente = DB::table('productos')
                ->where('productos.id_producto','=',$orden->id_lente)
                ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                ->first();

                if (isset($lente)) {
                    $especificaciones = DB::table('producto_especificaciones')
                    ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                    ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                    ->get();
                    $len = count($especificaciones);
                    $especificaciones_lente = "";
                    foreach ($especificaciones as $especificacion) {
                        if($especificacion == $especificaciones[$len - 1]){
                            $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                        }else{
                            $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                        }
                    }
                    $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
                    // $orden->nombre_laboratorio = $lente->nombre_marca;
                }


                $montura = DB::table('productos')
                ->where('productos.id_producto','=',$orden->id_montura)
                ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                ->first();

                if (isset($montura)) {
                    $especificaciones = DB::table('producto_especificaciones')
                    ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                    ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                    ->get();
                    $len = count($especificaciones);
                    $especificaciones_montura = "";
                    foreach ($especificaciones as $especificacion) {
                        if($especificacion == $especificaciones[$len - 1]){
                            $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                        }else{
                            $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                        }
                    }
                    $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

                }else{
                    $orden->montura_orden = "Montura propiedad del cliente";
                }
            }

            $estatus = "";

            // dd($historial_orden,$ordenes);
            return view('ordenes.produccion.detalleingreso',compact('ordenes','historial_orden','estatus'));

        }else{
            $estatus = "noExiste";
            return redirect()->route('ordenes.produccion.historial.ingresos',['estatus' => $estatus]);
        }

    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ORDENES CALIDAD::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA DE CALIDAD::::::::::::::::::::::::::::::::::::::::::::::*/


   public function lista_calidad(Request $request){

        // dd("apartadas");

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

        $laboratorios = DB::table('laboratorios')
        ->where('estado_laboratorio','=','activo')
        ->get();

        return view('ordenes.calidad.lista',compact('usuarios_sucursales','estatus','laboratorios'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::BUSCAR ORDENES EN CALIDAD DE SUCURSAL:::::::::::::::::::::::::::::::::::*/


    public function calidad_sucursal(Request $request){

        $ordenes = DB::table('ordenes')
        ->where('ordenes.id_sucursal','=',$request->sucursal)
        ->where('ordenes.estado_orden','=','calidad')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
        ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
        ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
        ->select('ordenes.id_orden','sucursals.nombre_sucursal','cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','laboratorios.nombre_laboratorio')
        ->get();

        foreach ($ordenes as $orden) {
            $lente = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_lente)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($lente)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_lente = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
            }


            $montura = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_montura)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($montura)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_montura = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

            }else{
                $orden->montura_orden = "Montura propiedad del cliente";
            }
            
        }

        return ($ordenes);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::CAMBIAR CALIDAD -> INGRESADO o CALIDAD -> RECHAZADO:::::::::::::::::::::::::::::*/


    public function calidad_cambiar(Request $request){
        // dd($request,"controller");
        $ordenes = json_decode($request->ordenes);
        // dd($ordenes,$request->ordenes);

        if ($request->accion == "ingresado") {
                
                $id_orden_ingresada_historial = DB::table('ordenes_ingresadas_historial')->insertGetId([
                    'id_sucursal' => $request->sucursal,
                    'fecha_orden_ingresada_historial' => Carbon::now(),
                    'id_usuario_registro' => Auth::id()
                ]);

                foreach ($ordenes as $orden) {

                    DB::table('ordenes')
                    ->where('id_orden','=',$orden)
                    ->update([
                        'estado_orden' => 'ingresado'
                    ]);

                    DB::table('ordenes_ingresadas_pivote')->insert([
                        'id_orden_ingresada_historial' => $id_orden_ingresada_historial,
                        'id_orden' => $orden,
                        'estado_orden_ingresada_pivote' => 'activo'
                    ]);
                }

                $estatus = "exito";
                return redirect()->route('ordenes.calidad.lista',['estatus' => $estatus]);
        }
        if ($request->accion == "rechazado") {
                
                $id_orden_rechazada_historial = DB::table('ordenes_rechazadas_historial')->insertGetId([
                    'id_sucursal' => $request->sucursal,
                    'fecha_orden_rechazada_historial' => Carbon::now(),
                    'id_usuario_registro' => Auth::id(),
                    'descripcion' => $request->descripcion
                ]);

                foreach ($ordenes as $orden) {

                    DB::table('ordenes')
                    ->where('id_orden','=',$orden)
                    ->update([
                        'estado_orden' => 'rechazado'
                    ]);

                    DB::table('ordenes_rechazadas_pivote')->insert([
                        'id_orden_rechazada_historial' => $id_orden_rechazada_historial,
                        'id_orden' => $orden,
                        'estado_orden_rechazada_pivote' => 'activo'
                    ]);
                }

                $estatus = "exito";
                return redirect()->route('ordenes.calidad.lista',['estatus' => $estatus]);
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ORDENES INGRESADAS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA DE INGRESOS:::::::::::::::::::::::::::::::::::::::::::::*/


   public function lista_ingresadas(Request $request){

        // dd("ingresadas");

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

        $laboratorios = DB::table('laboratorios')
        ->where('estado_laboratorio','=','activo')
        ->get();

        $empresas_envio = DB::table('empresa_envios')
        ->where('estado_empresa_envio','=','activo')
        ->get();

        return view('ordenes.ingresadas.lista',compact('usuarios_sucursales','estatus','laboratorios','empresas_envio'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::BUSCAR ORDENES INGRESADAS DE SUCURSAL:::::::::::::::::::::::::::::::::::*/


    public function ingresadas_sucursal(Request $request){

        $ordenes = DB::table('ordenes')
        ->where('ordenes.id_sucursal','=',$request->sucursal)
        ->where('ordenes.estado_orden','=','ingresado')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
        ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
        ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
        ->select('ordenes.id_orden','sucursals.nombre_sucursal','cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','laboratorios.nombre_laboratorio')
        ->get();

        foreach ($ordenes as $orden) {
            $lente = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_lente)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($lente)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_lente = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
            }


            $montura = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_montura)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($montura)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_montura = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

            }else{
                $orden->montura_orden = "Montura propiedad del cliente";
            }
            
        }

        return ($ordenes);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::CAMBIAR INGRESADO -> ENTREGADO o INGRESADO -> ENVIADO:::::::::::::::::::::::::::::*/


    public function ingresadas_cambiar(Request $request){

        $ordenes = json_decode($request->ordenes);

        if ($request->enviar == "entregado") {
                
                $id_orden_entregada_historial = DB::table('ordenes_entregadas_historial')->insertGetId([
                    'id_sucursal' => $request->sucursal,
                    'fecha_orden_entregada_historial' => Carbon::now(),
                    'id_usuario_registro' => Auth::id()
                ]);

                foreach ($ordenes as $orden) {

                    DB::table('ordenes')
                    ->where('id_orden','=',$orden)
                    ->update([
                        'estado_orden' => 'entregado'
                    ]);

                    DB::table('ordenes_entregadas_pivote')->insert([
                        'id_orden_entregada_historial' => $id_orden_entregada_historial,
                        'id_orden' => $orden,
                        'estado_orden_entregada_pivote' => 'activo'
                    ]);
                }

                $estatus = "exito";
                return redirect()->route('ordenes.ingresadas.lista',['estatus' => $estatus]);
        }
        if ($request->enviar == "enviado") {
                
                $id_orden_enviada_historial = DB::table('ordenes_enviadas_historial')->insertGetId([
                    'id_sucursal' => $request->sucursal,
                    'fecha_orden_enviada_historial' => Carbon::now(),
                    'id_usuario_registro' => Auth::id(),
                    'id_empresa_envio' => $request->empresa_envio,
                    'numero_control' => $request->numero_control
                ]);

                foreach ($ordenes as $orden) {

                    DB::table('ordenes')
                    ->where('id_orden','=',$orden)
                    ->update([
                        'estado_orden' => 'enviado'
                    ]);

                    DB::table('ordenes_enviadas_pivote')->insert([
                        'id_orden_enviada_historial' => $id_orden_enviada_historial,
                        'id_orden' => $orden,
                        'estado_orden_enviada_pivote' => 'activo'
                    ]);
                }

                $estatus = "exito";
                return redirect()->route('ordenes.ingresadas.lista',['estatus' => $estatus]);
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ORDENES RECHAZADAS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA DE RECHAZADAS:::::::::::::::::::::::::::::::::::::::::::*/


   public function lista_rechazadas(Request $request){

        // dd("apartadas");

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

        $laboratorios = DB::table('laboratorios')
        ->where('estado_laboratorio','=','activo')
        ->get();

        return view('ordenes.rechazadas.lista',compact('usuarios_sucursales','estatus','laboratorios'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::HISTORIAL DE RECHAZADAS::::::::::::::::::::::::::::::::::::::::::*/


   public function historial_rechazadas(Request $request){

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

        $laboratorios = DB::table('laboratorios')
        ->where('estado_laboratorio','=','activo')
        ->get();

        return view('ordenes.rechazadas.historial',compact('usuarios_sucursales','estatus','laboratorios'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::HISTORIAL RECHAZADAS DE SUCURSAL:::::::::::::::::::::::::::::::::::*/


    public function historial_rechazadas_consulta(Request $request){

        $ordenes = DB::table('ordenes_rechazadas_pivote')
        ->leftJoin('ordenes','ordenes.id_orden','=','ordenes_rechazadas_pivote.id_orden')
        ->where('ordenes.id_sucursal','=',$request->sucursal)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
        ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
        ->leftJoin('ordenes_rechazadas_historial','ordenes_rechazadas_historial.id_orden_rechazada_historial','=','ordenes_rechazadas_pivote.id_orden_rechazada_historial')
        ->leftJoin('users','users.id','=','ordenes_rechazadas_historial.id_usuario_registro')
        ->get();

        return ($ordenes);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::BUSCAR ORDENES RECHAZADAS DE SUCURSAL:::::::::::::::::::::::::::::::::::*/


    public function rechazadas_sucursal(Request $request){

        $ordenes = DB::table('ordenes')
        ->where('ordenes.id_sucursal','=',$request->sucursal)
        ->where('ordenes.estado_orden','=','rechazado')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
        ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
        ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
        ->select('ordenes.id_orden','sucursals.nombre_sucursal','cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','laboratorios.nombre_laboratorio')
        ->get();

        foreach ($ordenes as $orden) {
            $lente = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_lente)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($lente)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_lente = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
            }


            $montura = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_montura)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($montura)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_montura = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

            }else{
                $orden->montura_orden = "Montura propiedad del cliente";
            }
            
        }

        return ($ordenes);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::CAMBIAR RECHAZADAS -> PENDIENTE:::::::::::::::::::::::::::::::::::::*/


    public function rechazadas_cambiar(Request $request){
        // dd($request,"controller");
        $ordenes = json_decode($request->ordenes);
        // dd($ordenes,$request);

        $id_orden_devuelta_historial = DB::table('ordenes_devueltas_historial')->insertGetId([
            'id_sucursal' => $request->sucursal,
            'fecha_orden_devuelta_historial' => Carbon::now(),
            'id_usuario_registro' => Auth::id()
        ]);

        foreach ($ordenes as $orden) {

            DB::table('ordenes')
            ->where('id_orden','=',$orden)
            ->update([
                'estado_orden' => 'pendiente'
            ]);

            DB::table('ordenes_devueltas_pivote')->insert([
                'id_orden_devuelta_historial' => $id_orden_devuelta_historial,
                'id_orden' => $orden,
                'estado_orden_devuelta_pivote' => 'activo'
            ]);
        }

        $estatus = "exito";
        return redirect()->route('ordenes.rechazadas.lista',['estatus' => $estatus]);

    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ORDENES ENTREGADAS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA DE ENTREGADAS:::::::::::::::::::::::::::::::::::::::::::*/


   public function lista_entregadas(Request $request){

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

        return view('ordenes.entregadas.lista',compact('usuarios_sucursales','estatus'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::BUSCAR ORDENES ENTREGADAS DE SUCURSAL:::::::::::::::::::::::::::::::::::*/


    public function entregadas_sucursal(Request $request){

        $ordenes = DB::table('ordenes')
        ->where('ordenes.id_sucursal','=',$request->sucursal)
        ->where('ordenes.estado_orden','=','entregado')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
        ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
        ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
        ->select('ordenes.id_orden','sucursals.nombre_sucursal','cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','laboratorios.nombre_laboratorio')
        ->get();

        foreach ($ordenes as $orden) {
            $lente = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_lente)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($lente)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_lente = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
            }


            $montura = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_montura)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($montura)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_montura = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

            }else{
                $orden->montura_orden = "Montura propiedad del cliente";
            }
            
        }

        return ($ordenes);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ORDENES ENVIADAS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::LISTA DE ENVIADAS:::::::::::::::::::::::::::::::::::::::::::::*/


   public function lista_enviadas(Request $request){

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

        return view('ordenes.enviadas.lista',compact('usuarios_sucursales','estatus'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::BUSCAR ORDENES ENVIADAS DE SUCURSAL:::::::::::::::::::::::::::::::::::*/


    public function enviadas_sucursal(Request $request){

        $ordenes = DB::table('ordenes')
        ->where('ordenes.id_sucursal','=',$request->sucursal)
        ->where('ordenes.estado_orden','=','enviado')
        // ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
        ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','cotizaciones.id_laboratorio')
        ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')

        ->leftJoin('ordenes_enviadas_pivote','ordenes_enviadas_pivote.id_orden','=','ordenes.id_orden')
        ->leftJoin('ordenes_enviadas_historial','ordenes_enviadas_historial.id_orden_enviada_historial','=','ordenes_enviadas_pivote.id_orden_enviada_historial')
        ->leftJoin('empresa_envios','empresa_envios.id_empresa_envio','=','ordenes_enviadas_historial.id_empresa_envio')

        ->select('ordenes.id_orden',/*'sucursals.nombre_sucursal',*/'cotizaciones.id_lente','cotizaciones.id_montura','pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','laboratorios.nombre_laboratorio','empresa_envios.nombre_empresa_envio','empresa_envios.nombre_codigo','ordenes_enviadas_historial.numero_control')
        ->get();

        foreach ($ordenes as $orden) {
            $lente = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_lente)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($lente)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_lente = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_marca . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
            }


            $montura = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_montura)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($montura)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_montura = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

            }else{
                $orden->montura_orden = "Montura propiedad del cliente";
            }
            
        }

        return ($ordenes);
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR::::::::::::::::::::::::::::::::::::::::::::::::*/
}