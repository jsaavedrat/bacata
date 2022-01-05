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
use Carbon\Carbon;

class BodegasController extends Controller
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
/*:::::::::::::::::::::::::::::::::::::::::::CREAR BODEGA::::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        return view('bodegas.crear',compact('estatus'));
	}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR BODEGA:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        $existe = DB::table('bodegas')
        ->where('nombre_bodega','=',$request->nombre_bodega)
        ->where('estado_bodega','=','activo')
        ->pluck('nombre_bodega');

        if($existe=="[]"){

            $id_bodega = DB::table('bodegas')->insertGetId([
                'nombre_bodega' => $request->nombre_bodega,
                'estado_bodega' => $request->estado_bodega,
                'direccion_bodega' => $request->direccion_bodega,
                'telefono_bodega' => $request->telefono_bodega
            ]);

            $date = Carbon::now();$id_user = Auth::id();
            DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 12, 'accion' => 'crear', 'id_elemento' => $id_bodega, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);
 
            $estatus="exito";
        }else{
            $estatus="error";
        }

        return redirect()->route('bodegas.crear',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE BODEGAS:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $bodegas = DB::table('bodegas')
        ->where('id_bodega','!=',0)
        ->where('estado_bodega','=','activo')
        ->get();

        return view('bodegas.lista',compact('bodegas','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR BODEGA A EDITAR:::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $bodega = DB::table('bodegas')
        ->where('id_bodega','=',$request->editar)
        ->where('estado_bodega','=','activo')
        ->where('id_bodega','!=',0)
        ->first();

        if(is_numeric($request->editar)&&($bodega != null)) {

            return view('bodegas.modificar',compact('bodega'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('bodegas.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::MODIFICAR BODEGA:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){

        $bodega = DB::table('bodegas')
        ->where('id_bodega','=',$request->id_bodega)
        ->where('estado_bodega','=','activo')
        ->where('id_bodega','!=',0)
        ->first();

        if(is_numeric($request->id_bodega)&&($bodega != null)) {
            $verificar = DB::table('bodegas')
            ->where('nombre_bodega','=',$request->nombre_bodega)
            ->where('estado_bodega','=','activo')
            ->where('id_bodega','!=',0)
            ->first();
            if($verificar != null){
                if($bodega->nombre_bodega == $request->nombre_bodega){
                    DB::table('bodegas')
                    ->where('id_bodega', $request->id_bodega)
                    ->update([
                        'nombre_bodega' => $request->nombre_bodega,
                        'direccion_bodega' => $request->direccion_bodega,
                        'telefono_bodega' => $request->telefono_bodega
                    ]);
                    $estatus="actualizado";
                }else{
                    $estatus="erroractualizar";
                }
            }else{
                DB::table('bodegas')
                    ->where('id_bodega', $request->id_bodega)
                    ->update([
                        'nombre_bodega' => $request->nombre_bodega,
                        'direccion_bodega' => $request->direccion_bodega,
                        'telefono_bodega' => $request->telefono_bodega
                    ]);
                $estatus="actualizado";
            }
        }else{
            $estatus="erroractualizar";
        }    
        
        return redirect()->route('bodegas.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::INACTIVAR BODEGA:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $existe = DB::table('bodegas')
        ->where('id_bodega','=',$request->eliminar)
        ->where('estado_bodega','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('bodegas')
            ->where('id_bodega', $request->eliminar)
            ->update(['estado_bodega' => 'inactivo']);
        }else{
            $estatus="error";
        }

        return redirect()->route('bodegas.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::VER DETALLE BODEGA:::::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){

        $bodega = DB::table('bodegas')
        ->where('id_bodega','=',$request->ver)
        ->where('estado_bodega','=','activo')
        ->where('id_bodega','!=',0)
        ->first();

        $configuracion_cotizacion = DB::table('configuracion_cotizaciones')
        ->first();

        if (!isset($configuracion_cotizacion)){
            dd("configurar cotización, notificar al administrador de AppWebCA");
        }

        $productos_especiales = DB::table('productos_especiales')
        ->where('estado_producto_especial','=','activo')
        ->select('id_tipo_producto_especial')
        ->pluck('id_tipo_producto_especial');

        $productos = DB::table('producto_bodegas')
        ->where('id_bodega','=',$request->ver)
        ->where('cantidad','>',0)
        ->leftJoin('productos','productos.id_producto','=','producto_bodegas.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_lentes)
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_examenes)
        ->whereNotIn('modelos.id_tipo_producto',$productos_especiales)
        ->get();

        $c = 0;
        foreach ($productos as $producto) {
            $c = $c + $producto->cantidad;
        }
        $bodega->total = $c;
        $bodega->cantidad = count($productos);

        $id_user = Auth::id();
        $modulos = DB::table('model_has_roles')
        ->where('model_id','=',$id_user)
        ->where('model_type','=','App\User')
        ->leftJoin('role_has_permissions','role_has_permissions.role_id','=','model_has_roles.role_id')
        ->leftJoin('permissions','permissions.id','=','role_has_permissions.permission_id')
        ->leftJoin('permisos_modulos','permisos_modulos.id_permiso','=','permissions.id')
        ->leftJoin('modulos','modulos.id_modulo','=','permisos_modulos.id_modulo')
        ->select('modulos.id_modulo','modulos.nombre_modulo')
        ->distinct()
        ->get();

        $disponibles = [];
        foreach ($modulos as $modulo) {

            if($modulo->id_modulo == 22){/*PRODUCTOS*/
                $valores = [
                    'nombre_modulo'=> $modulo->nombre_modulo,
                    'cantidad_modulo'=> $c . " Productos",
                    'url_lista' => route("bodegas.productos"),
                    'icono' => 'fa fa-tags'
                ];
                array_push($disponibles,$valores);
            }
/*
            if($modulo->id_modulo == 2){//EMPLEADOS
                $empleados = DB::table('users')
                ->where('users.tipo_usuario','=','empleado')
                ->where('users.estado_usuario','=','activo')
                ->count();
                $valores = [
                    'nombre_modulo'=> $modulo->nombre_modulo,
                    'cantidad_modulo'=> $empleados . " Empleados Activos",
                    'url_lista' => route("empleados.lista"),
                    'icono' => 'fa fa-users'
                ];
                array_push($disponibles,$valores);
            }
*/
            if($modulo->id_modulo == 21){/*INGRESOS*/
                $ingresos = DB::table('ingresos')
                ->where('ingresos.id_bodega','=',$request->ver)
                ->count();
                $valores = [
                    'nombre_modulo'=> $modulo->nombre_modulo,
                    'cantidad_modulo'=> $ingresos . " Ingresos de Productos",
                    'url_lista' => route("bodegas.ingresos"),
                    'icono' => 'icon-arrow-with-circle-down'
                ];
                array_push($disponibles,$valores);
            }
        }

        $disponibles = json_encode($disponibles);
        $disponibles = json_decode($disponibles);

        return view('bodegas.ver',compact('bodega','disponibles'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::PRODUCTOS BODEGA::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function productos(Request $request){

        $bodega = DB::table('bodegas')
        ->where('id_bodega','=',$request->ver)
        ->where('estado_bodega','=','activo')
        ->where('id_bodega','!=',0)
        ->first();

        $productos_especiales = DB::table('productos_especiales')
        ->where('estado_producto_especial','=','activo')
        ->select('id_tipo_producto_especial')
        ->pluck('id_tipo_producto_especial');

        $configuracion_cotizacion = DB::table('configuracion_cotizaciones')
        ->first();

        if (!isset($configuracion_cotizacion)){
            dd("configurar cotización, notificar al administrador de AppWebCA");
        }

        $productos = DB::table('producto_bodegas')
        ->where('id_bodega','=',$request->ver)
        ->where('cantidad','>',0)
        ->leftJoin('productos','productos.id_producto','=','producto_bodegas.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_lentes)
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_examenes)
        ->whereNotIn('modelos.id_tipo_producto',$productos_especiales)
        ->get();

        $c = 0;
        foreach ($productos as $producto) {
            $c = $c + $producto->cantidad;
        }
        $bodega->total = $c;
        $bodega->cantidad = count($productos);

        return view('bodegas.productos',compact('bodega','productos','configuracion_cotizacion','productos_especiales'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::BUSCAR PRODUCTOS AJAX:::::::::::::::::::::::::::::::::::::::::*/

    public function buscar_productos(Request $request){
        
        $productos = DB::table('producto_bodegas')
        ->where('producto_bodegas.id_bodega','=',$request->bodega)
        ->where('cantidad','>',0)
        ->leftJoin('productos','productos.id_producto','=','producto_bodegas.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('modelos.id_tipo_producto','!=',$request->lentes)
        ->where('modelos.id_tipo_producto','!=',$request->examen)
        ->whereNotIn('modelos.id_tipo_producto',$request->especiales);

        return datatables()->of($productos)
        ->addIndexColumn()
        ->addColumn('precio', function($producto){
            return "<div style='white-space:nowrap; text-align: right; float: right;'>$ " . number_format($producto->precio_base, 2, ',', '.') . "</div>";
        })
        ->addColumn('unidades', function($producto){
            return "<input class='input-cantidad unidades' id='input-unidades-$producto->id_producto' value='$producto->cantidad' disabled autocomplete='off' onfocusout='cambiarUnidades($producto->id_producto)'>";
        })
        ->addColumn('agregar-codigo', function($producto){
            return "<input class='input-cantidad' id='input-cantidad-$producto->id_producto' onfocusout='cambiarCodigo(`$producto->id_producto`,this.value, `$producto->nombre_producto`,`$producto->code128`,`$producto->precio_base`);' autocomplete='off' placeholder='Cantidad'>";
        })
        ->addColumn('acciones', function($producto){
            return "<div class='iconos-acciones'>
                        <div class='content-acciones'>
                            <a class='dropdown-content'><i class='icon-pencil'> </i> MODIFICAR</a>
                            <i onclick='modificarCantidad($producto->id_producto);'' class='icon-pencil i-acciones'></i>
                        </div>
                    </div>";
        })
        ->rawColumns(['precio','unidades','agregar-codigo','acciones'])
        ->toJson();
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::PRODUCTOS BODEGA::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function productosCantidades(Request $request){

        DB::table('producto_bodegas')
        ->where('id_bodega','=', $request->id_bodega)
        ->where('id_producto','=', $request->id_producto)
        ->update([
            'cantidad' => $request->cantidad
        ]);

        $estatus = "exito";

        return ($estatus);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::INGRESOS BODEGA::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function ingresos(Request $request){

        $bodega = DB::table('bodegas')
        ->where('id_bodega','=',$request->ver)
        ->where('estado_bodega','=','activo')
        ->where('id_bodega','!=',0)
        ->first();

        $configuracion_cotizacion = DB::table('configuracion_cotizaciones')
        ->first();

        if (!isset($configuracion_cotizacion)){
            dd("configurar cotización, notificar al administrador de AppWebCA");
        }

        $productos_especiales = DB::table('productos_especiales')
        ->where('estado_producto_especial','=','activo')
        ->select('id_tipo_producto_especial')
        ->pluck('id_tipo_producto_especial');

        $productos = DB::table('producto_bodegas')
        ->where('id_bodega','=',$request->ver)
        ->where('cantidad','>',0)
        ->leftJoin('productos','productos.id_producto','=','producto_bodegas.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_lentes)
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_examenes)
        ->whereNotIn('modelos.id_tipo_producto',$productos_especiales)
        ->get();

        $c = 0;
        foreach ($productos as $producto) {
            $c = $c + $producto->cantidad;
        }
        $bodega->total = $c;
        $bodega->cantidad = count($productos);

        $ingresos = DB::table('ingresos')
        ->where('id_bodega','=',$request->ver)
        ->leftJoin('proveedores','proveedores.id_proveedor','=','ingresos.id_proveedor')
        ->select('ingresos.id_ingreso','ingresos.fecha_ingreso','proveedores.nombre_proveedor')
        ->orderBy('id_ingreso', 'desc')
        ->get();
        
        $producto_ingresos = DB::table('ingresos')
        ->where('id_bodega','=',$request->ver)
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

        return view('bodegas.ingresos',compact('bodega','ingresos'));
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('bodegas.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR:::::::::::::::::::::::::::::::::::::::::::::::*/
}
