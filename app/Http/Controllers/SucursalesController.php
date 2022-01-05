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

class SucursalesController extends Controller
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
/*:::::::::::::::::::::::::::::::::::::::::::CREAR SUCURSAL::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        return view('sucursales.crear',compact('estatus'));
	}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR SUCURSAL:::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){
        //dd($request);
        // $request->validate([
        //     'nombre_sucursal' => ['required', 'max:255'],
        //     'direccion_sucursal' => ['required', 'max:255'],
        //     'telefono_sucursal'=>['required', 'digits_between:10,13'],
        //     'imagen_empresa_envio' =>['required', 'image']
        // ]);

        // dd($request);
        
        $existe = DB::table('sucursals')
        ->where('nombre_sucursal',$request->nombre_sucursal)
        ->where('estado_sucursal','=','activo')
        ->count();

        if($existe==0){
   
            $hora = date('H-i-s');
            $nombre_imagen = 'suc-oa-' . $hora . $request->imagen_sucursal->getClientOriginalName();
            $request->imagen_sucursal->move('public/imagenes/sistema/sucursales',$nombre_imagen);
            $color = 'linear-gradient(-20deg, '.$request->color_2.' 0%, '.$request->color_1.' 100%)';
            $id_sucursal = DB::table('sucursals')->insertGetId([
                'nombre_sucursal' => $request->nombre_sucursal,
                'estado_sucursal' => $request->estado_sucursal,
                'direccion_sucursal' => $request->direccion_sucursal,
                'telefono_sucursal' => $request->telefono_sucursal,
                'nombre_imagen_sucursal' => $nombre_imagen,
                'color'=> $color,
                'correo_sucursal' => $request->correo_sucursal,
                'mapa_sucursal' => $request->mapa_sucursal
            ]);

            $date = Carbon::now();$id_user = Auth::id();
            DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 1, 'accion' => 'crear', 'id_elemento' => $id_sucursal, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

            $estatus="exito";
        }else{
            $estatus="error";
        }

        return redirect()->route('sucursales.crear',['estatus' => $estatus]);
    }

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE SUCURSALES::::::::::::::::::::::::::::::::::::::::::::*/


   public function lista(Request $request){

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

        return view('sucursales.lista',compact('sucursales','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR SUCURSAL A EDITAR:::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$request->editar)
        ->where('estado_sucursal','=','activo')
        ->where('id_sucursal','!=',0)
        ->first();

        if(is_numeric($request->editar)&&($sucursal != null)) {

            return view('sucursales.modificar',compact('sucursal'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('sucursales.lista',['estatus' => $estatus]);
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::MODIFICAR SUCURSAL:::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){

        // dd($request);
        
        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$request->id_sucursal)
        ->where('estado_sucursal','=','activo')
        ->where('id_sucursal','!=',0)
        ->first();

        $verificar = DB::table('sucursals')
        ->where([['id_sucursal','!=',$request->id_sucursal],['estado_sucursal','activo'],['nombre_sucursal',$request->nombre_sucursal]])
        ->count();

        $data = [
            'nombre_sucursal' => $request->nombre_sucursal,
            'direccion_sucursal' => $request->direccion_sucursal,
            'telefono_sucursal' => $request->telefono_sucursal,
            'correo_sucursal' => $request->correo_sucursal,
            'mapa_sucursal' => $request->mapa_sucursal
        ];
        if(empty($sucursal) || $verificar>0) {
            $estatus="erroractualizar";
        }else{
            if($request->hasFile('imagen_sucursal')){
                $hora = date('H-i-s');
                $nombre_imagen = 'suc-oa-' . $hora . $request->imagen_sucursal->getClientOriginalName();
                $request->imagen_sucursal->move('public/imagenes/sistema/sucursales',$nombre_imagen);
                $data = array_merge($data, ['nombre_imagen_sucursal'=>$nombre_imagen]);
                File::delete('public/imagenes/sistema/sucursales/'.$sucursal->nombre_imagen_sucursal);
            }
            if($request->color_1 != "#000000" && $request->color_2 != "#000000"){
                $color = 'linear-gradient(-20deg, '.$request->color_2.' 0%, '.$request->color_1.' 100%)';
                $data = array_merge($data, ['color'=>$color]);
            }
            DB::table('sucursals')
            ->where('id_sucursal', $request->id_sucursal)
            ->update($data);
            $estatus="actualizado";
        }

        return redirect()->route('sucursales.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::INACTIVAR SUCURSAL:::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $existe = DB::table('sucursals')
        ->where('id_sucursal','=',$request->eliminar)
        ->where('estado_sucursal','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('sucursals')
            ->where('id_sucursal', $request->eliminar)
            ->update(['estado_sucursal' => 'inactivo']);
        }else{
            $estatus="error";
        }

       return redirect()->route('sucursales.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::VER DETALLE SUCURSAL:::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$request->ver)
        ->where('estado_sucursal','=','activo')
        ->where('id_sucursal','!=',0)
        ->first();

        $configuracion_cotizacion = DB::table('configuracion_cotizaciones')
        ->first();

        if (!isset($configuracion_cotizacion)){
            dd("configurar cotización, notificar al administrador de AppWebCA");
        }

        $productos = DB::table('producto_sucursales')
        ->where('id_sucursal','=',$request->ver)
        ->where('cantidad','>',0)
        ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_lentes)
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_examenes)
        ->get();

        $c = 0;
        foreach ($productos as $producto) {
            $c = $c + $producto->cantidad;
        }
        $sucursal->total = $c;
        $sucursal->cantidad = count($productos);

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
                    'url_lista' => route("sucursales.productos"),
                    'icono' => 'fa fa-tags'
                ];
                array_push($disponibles,$valores);
            }

            if($modulo->id_modulo == 2){/*EMPLEADOS*/
                $empleados = DB::table('usuarios_sucursales')
                ->where('usuarios_sucursales.id_sucursal','=',$request->ver)
                ->leftJoin('users','users.id','=','usuarios_sucursales.id_usuario')
                ->where('users.tipo_usuario','=','empleado')
                ->where('users.estado_usuario','=','activo')
                ->count();
                $valores = [
                    'nombre_modulo'=> $modulo->nombre_modulo,
                    'cantidad_modulo'=> $empleados . " Empleados Activos",
                    'url_lista' => route("sucursales.empleados"),
                    'icono' => 'fa fa-users'
                ];
                array_push($disponibles,$valores);
            }

            if($modulo->id_modulo == 21){/*INGRESOS*/
                $traslados = DB::table('traslados')
                ->where('traslados.establecimiento_llegada','=','sucursal')
                ->where('traslados.id_llegada','=',$request->ver)
                ->count();
                $valores = [
                    'nombre_modulo'=> $modulo->nombre_modulo,
                    'cantidad_modulo'=> $traslados . " Ingresos de Productos",
                    'url_lista' => route("sucursales.ingresos"),
                    'icono' => 'icon-arrow-with-circle-down'
                ];
                array_push($disponibles,$valores);

                $ingresos_directo = DB::table('ingresos_sucursal')
                ->where('id_sucursal','=',$request->ver)
                ->where('id_sucursal','!=',0)
                ->count();
                $valores = [
                    'nombre_modulo'=> 'Ingresos Directo',
                    'cantidad_modulo'=> $ingresos_directo . " Ingresos Directos",
                    'url_lista' => route("sucursales.ingresos.directo"),
                    'icono' => 'icon-arrow-with-circle-down'
                ];
                array_push($disponibles,$valores);
            }
        }

        $disponibles = json_encode($disponibles);
        $disponibles = json_decode($disponibles);

        return view('sucursales.ver',compact('sucursal','disponibles'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::EMPLEADOS SUCURSAL::::::::::::::::::::::::::::::::::::::::::::::*/


    public function empleados(Request $request){

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$request->ver)
        ->where('estado_sucursal','=','activo')
        ->where('id_sucursal','!=',0)
        ->first();

        $configuracion_cotizacion = DB::table('configuracion_cotizaciones')
        ->first();

        if (!isset($configuracion_cotizacion)){
            dd("configurar cotización, notificar al administrador de AppWebCA");
        }
        
        $productos = DB::table('producto_sucursales')
        ->where('id_sucursal','=',$request->ver)
        ->where('cantidad','>',0)
        ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_lentes)
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_examenes)
        ->get();
        $c = 0;
        foreach ($productos as $producto) {
            $c = $c + $producto->cantidad;
        }
        $sucursal->total = $c;
        $t = count($productos);
        $sucursal->cantidad = $t;

        $empleados = DB::table('usuarios_sucursales')
        ->where('usuarios_sucursales.id_sucursal','=',$request->ver)
        ->leftJoin('users','users.id','=','usuarios_sucursales.id_usuario')
        ->where('users.tipo_usuario','=','empleado')
        ->leftJoin('contratos_usuarios','contratos_usuarios.id_usuario_contrato','=','users.id')
        ->where('contratos_usuarios.estado_contrato_usuario','=','activo')
        ->where('users.estado_usuario','=','activo')
        ->get();
        foreach ($empleados as $key => $empleado) {
            if ($empleado->nombre_imagen_user == null) {
                $empleado->nombre_imagen_user = 'user_default.png';
            }
        }

        return view('sucursales.empleados',compact('sucursal','empleados'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::PRODUCTOS SUCURSAL::::::::::::::::::::::::::::::::::::::::::::::*/


    public function productos(Request $request){
        
        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$request->ver)
        ->where('estado_sucursal','=','activo')
        ->where('id_sucursal','!=',0)
        ->first();

        $configuracion_cotizacion = DB::table('configuracion_cotizaciones')
        ->first();

        if (!isset($configuracion_cotizacion)){
            dd("configurar cotización, notificar al administrador de AppWebCA");
        }

        $productos = DB::table('producto_sucursales')
        ->where('id_sucursal','=',$request->ver)
        ->where('cantidad','>',0)
        ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_lentes)
        ->where('modelos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_examenes)
        ->get();

        $c = 0;
        foreach ($productos as $producto) {
            $c = $c + $producto->cantidad;
        }
        $sucursal->total = $c;
        $sucursal->cantidad = count($productos);

        return view('sucursales.productos',compact('sucursal','productos','configuracion_cotizacion'));

        // dd($sucursal->total, $sucursal->cantidad);

        /*$productos = DB::table('producto_sucursales')
        ->where('id_sucursal','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->where('tipo_productos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_lentes)
        ->where('tipo_productos.id_tipo_producto','!=',$configuracion_cotizacion->id_tipo_producto_examenes)
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','productos.code128','producto_sucursales.cantidad','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca')
        ->get();

        $imagenes = DB::table('producto_sucursales')
        ->where('id_sucursal','=',$request->ver)
        ->leftJoin('imagen_productos','imagen_productos.id_producto','=','producto_sucursales.id_producto')
        ->select('imagen_productos.id_producto','imagen_productos.nombre_imagen')
        ->get();

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();

        $c = 0;
        foreach ($productos as $producto) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto->id_producto){
                    $especificaciones= ", " . $producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto->especificaciones = $especificaciones;
            $c = $c + $producto->cantidad;
            $band=false;
            $producto->imagen = "default.png";
            foreach ($imagenes as $imagen) {
                if(($producto->id_producto == $imagen->id_producto) && ($band == false)) {
                    $producto->imagen = $imagen->nombre_imagen;
                    $band=true;
                }
            }
        }

        $sucursal->total = $c;
        $sucursal->cantidad = count($productos);

        return view('sucursales.productos',compact('sucursal','productos'));*/
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::BUSCAR PRODUCTOS AJAX:::::::::::::::::::::::::::::::::::::::::*/

    public function buscar_productos(Request $request){
        
        $productos = DB::table('producto_sucursales')
        ->where('producto_sucursales.id_sucursal','=',$request->sucursal)
        ->where('cantidad','>',0)
        ->leftJoin('productos','productos.id_producto','=','producto_sucursales.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('modelos.id_tipo_producto','!=',$request->lentes)
        ->where('modelos.id_tipo_producto','!=',$request->examen);

        return datatables()->of($productos)
        ->addIndexColumn()
        ->addColumn('precio', function($producto){
            return "<div style='white-space:nowrap; text-align: right; float: right;'>$ " . number_format($producto->precio_base, 2, ',', '.') . "</div>";
        })
        ->addColumn('unidades', function($producto){
            return "<input class='input-cantidad unidades' id='input-unidades-$producto->id_producto' value='$producto->cantidad' disabled autocomplete='off' onfocusout='cambiarUnidades($producto->id_producto)'>
            <div style='display:none;'>$producto->cantidad</div>";
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
/*::::::::::::::::::::::::::::::::::::::::CAMBIAR PRODUCTOS SUCURSAL:::::::::::::::::::::::::::::::::::::::::*/


    public function productosCantidades(Request $request){

        DB::table('producto_sucursales')
        ->where('id_sucursal','=', $request->id_sucursal)
        ->where('id_producto','=', $request->id_producto)
        ->update([
            'cantidad' => $request->cantidad
        ]);

        $estatus = "exito";

        return ($estatus);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::INGRESOS SUCURSAL::::::::::::::::::::::::::::::::::::::::::::::*/


    public function ingresos(Request $request){
        
        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$request->ver)
        ->where('estado_sucursal','=','activo')
        ->where('id_sucursal','!=',0)
        ->first();

        $ingresos_bodega = DB::table('traslados')
        ->where('establecimiento_llegada','=','sucursal')
        ->where('establecimiento_salida','=','bodega')
        ->where('id_llegada','=',$request->ver)
        ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_salida')
        ->select('traslados.id_traslado','traslados.fecha_llegada_traslado','traslados.estado_traslado','bodegas.nombre_bodega')
        ->orderBy('fecha_llegada_traslado', 'desc')
        ->get();

        $ingresos_sucursal = DB::table('traslados')
        ->where('establecimiento_llegada','=','sucursal')
        ->where('establecimiento_salida','=','sucursal')
        ->where('id_llegada','=',$request->ver)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_salida')
        ->select('traslados.id_traslado','traslados.fecha_llegada_traslado','traslados.estado_traslado','sucursals.nombre_sucursal')
        ->orderBy('fecha_llegada_traslado', 'desc')
        ->get();

        $ingresos = $ingresos_bodega->merge($ingresos_sucursal)->sortByDesc('estado_traslado');

        $producto_traslados = DB::table('producto_traslados')
        ->select('producto_traslados.id_traslado','cantidad_llegada','cantidad_salida')
        ->get();

        foreach ($ingresos as $ingreso) {
            $c = 0;
            $cc = 0;
            foreach ($producto_traslados as $producto_traslado) {
                if ($ingreso->id_traslado == $producto_traslado->id_traslado) {
                    $c = $c + $producto_traslado->cantidad_llegada;
                    $cc = $cc + $producto_traslado->cantidad_salida;
                }
            }
            $ingreso->cantidad_ingresados = $c;
            $ingreso->cantidad_salida = $cc;
        }

        return view('sucursales.ingresos',compact('sucursal','ingresos'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::VER INGRESO SUCURSAL::::::::::::::::::::::::::::::::::::::::::::::*/


    public function verIngreso(Request $request){

        $producto_traslados = DB::table('producto_traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','producto_traslados.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('producto_traslados.id_traslado','producto_traslados.id_producto','producto_traslados.cantidad_salida','producto_traslados.cantidad_llegada','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo','productos.code128','productos.precio_base')
        ->get();

        $traslado = DB::table('traslados')
        ->where('id_traslado','=',$request->ver)
        ->leftJoin('users','users.id','=','traslados.id_user_salida')
        ->select('traslados.id_traslado','traslados.fecha_salida_traslado','traslados.id_user_registro','traslados.estado_traslado','traslados.id_salida','traslados.id_llegada','traslados.establecimiento_salida','users.name','users.apellido')
        ->first();

        if ($traslado->establecimiento_salida == 'sucursal') {
            $establecimiento = DB::table('traslados')
            ->where('id_traslado','=',$request->ver)
            ->leftJoin('sucursals','sucursals.id_sucursal','=','traslados.id_salida')
            ->select('sucursals.nombre_sucursal')
            ->first();
            $nombre_establecimiento = $establecimiento->nombre_sucursal;
        }else{
            $establecimiento = DB::table('traslados')
            ->where('id_traslado','=',$request->ver)
            ->leftJoin('bodegas','bodegas.id_bodega','=','traslados.id_salida')
            ->select('bodegas.nombre_bodega')
            ->first();
            $nombre_establecimiento = $establecimiento->nombre_bodega;
        }

        $traslado->nombre_establecimiento = $nombre_establecimiento;

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$traslado->id_llegada)
        ->select('nombre_sucursal')
        ->first();

        $traslado->nombre_sucursal_llegada = $sucursal->nombre_sucursal;

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
                    $especificaciones= ", " . $producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto_traslado->especificaciones = $especificaciones;            
        }

        //dd($producto_traslados);

        return view('sucursales.veringreso',compact('producto_traslados','traslado'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::INGRESOS DIRECTO SUCURSAL::::::::::::::::::::::::::::::::::::::::::*/


    public function ingresosDirecto(Request $request){

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$request->ver)
        ->where('estado_sucursal','=','activo')
        ->where('id_sucursal','!=',0)
        ->first();

        $ingresos_directo = DB::table('ingresos_sucursal')
        ->where('id_sucursal','=',$request->ver)
        ->get();

        $productos_ingreso_sucursal = DB::table('productos_ingreso_sucursal')
        ->get();

        foreach ($ingresos_directo as $ingreso_directo) {
            $c = 0;
            $cc = 0;
            foreach ($productos_ingreso_sucursal as $producto_ingreso_sucursal) {
                if($ingreso_directo->id_ingreso_sucursal == $producto_ingreso_sucursal->id_ingreso_sucursal){
                    $c = $c + 1;
                    $cc = $cc + $producto_ingreso_sucursal->cantidad;
                }
            }
            $ingreso_directo->distintos = $c;
            $ingreso_directo->total = $cc;
        }


        //dd($ingresos_directo);
        return view('sucursales.ingresosdirecto',compact('sucursal','ingresos_directo'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::VER INGRESO DIRECTO SUCURSAL::::::::::::::::::::::::::::::::::::::::::*/


    public function verIngresoDirecto(Request $request){

        $productos_ingreso_sucursal = DB::table('productos_ingreso_sucursal')
        ->where('id_ingreso_sucursal','=',$request->ver)
        ->leftJoin('productos','productos.id_producto','=','productos_ingreso_sucursal.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('productos_ingreso_sucursal.id_ingreso_sucursal','productos_ingreso_sucursal.id_producto','productos_ingreso_sucursal.cantidad','tipo_productos.nombre_tipo_producto','marcas.nombre_marca','modelos.nombre_modelo','productos.code128','productos.precio_base')
        ->get();

        $ingreso_sucursal = DB::table('ingresos_sucursal')
        ->where('id_ingreso_sucursal','=',$request->ver)
        ->first();

        $sucursal = DB::table('sucursals')
        ->where('id_sucursal','=',$ingreso_sucursal->id_sucursal)
        ->first();

        $c = 0;
        $cc = 0;
        foreach ($productos_ingreso_sucursal as $producto_ingreso_sucursal) {
            if($producto_ingreso_sucursal->id_ingreso_sucursal == $request->ver){
                $c = $c + 1;
                $cc = $cc + $producto_ingreso_sucursal->cantidad;
            }
        }
        $ingreso_sucursal->distintos = $c;
        $ingreso_sucursal->total = $cc;

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
        ->get();


        foreach ($productos_ingreso_sucursal as $producto_ingreso_sucursal) {
            $especificaciones="";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto_ingreso_sucursal->id_producto){
                    $especificaciones= ", " . $producto_especificacion->nombre_especificacion.$especificaciones;
                }
            }
            $producto_ingreso_sucursal->especificaciones = $especificaciones;            
        }

        //dd($productos_ingreso_sucursal,$ingreso_sucursal);
        return view('sucursales.veringresodirecto',compact('ingreso_sucursal','productos_ingreso_sucursal','sucursal'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('sucursales.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR:::::::::::::::::::::::::::::::::::::::::::::::*/
}
