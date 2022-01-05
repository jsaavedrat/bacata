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
use DataTables;

class ProductosController extends Controller
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
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(){
	
        $mensaje="";

        $bodegas = DB::table('bodegas')
        ->where('estado_bodega','=','activo')
        ->where('id_bodega','!=',0)
        ->select('bodegas.id_bodega','bodegas.nombre_bodega')
        ->get();

        $proveedores = DB::table('proveedores')
        ->where('estado_proveedor','=','activo')
        ->get();

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        return view('productos.crear',compact('mensaje','tipo_productos','bodegas','proveedores'));
	}


/*:::::::::::::::::::::::::::::::::::::::::::AJAX Marca Tipo Producto AJAX::::::::::::::::::::::::::::::::::::::::::::::::*/
    public function marcaTipoProductos(Request $request){
        $marcas = DB::table('tipo_producto_marcas')
        ->where('estado_tipo_producto_marca','=','activo')
        ->where('tipo_producto_marcas.id_tipo_producto','=',$request->id_tipo_producto)

        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','tipo_producto_marcas.id_tipo_producto')
        ->where('tipo_productos.estado_tipo_producto','=','activo')

        ->leftJoin('marcas','marcas.id_marca','=','tipo_producto_marcas.id_marca')
        ->where('estado_marca','=','activo')
        ->select('marcas.id_marca','marcas.nombre_marca','tipo_productos.iva')
        ->get();
        return ($marcas);
    }


/*:::::::::::::::::::::::::::::::::::::::::::AJAX Modelo Marcas AJAX::::::::::::::::::::::::::::::::::::::::::::::::*/
    public function modeloMarcas(Request $request){

        $modelos = DB::table('modelos')
        ->where('estado_modelo','=','activo')
        ->where('id_tipo_producto','=',$request->id_tipo_producto)
        ->where('id_marca','=',$request->id_marca)
        ->select('modelos.id_modelo','modelos.nombre_modelo')
        ->get();
        return ($modelos);
    }

/*::::::::::::::::::::::::::::::::::::AJAX Clasificaciones Tipos Productos AJAX::::::::::::::::::::::::::::::::::::::*/
    public function clasificacionesTipoProductos(Request $request){

        $clasificacion_tipo_productos = DB::table('clasificacion_tipo_productos')
        ->where('estado_clasificacion_tipo_producto','=','activo')
        ->where('id_tipo_producto','=',$request->id_tipo_producto)
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
        ->where('estado_clasificacion','=','activo')
        ->select('clasificaciones.id_clasificacion','clasificaciones.nombre_clasificacion')
        ->get();
        

        $especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->select('id_clasificacion','id_especificacion','nombre_especificacion')
        ->get();


        $array = [];
        $vector= [];
        $esp = [];
        $valores = [];
        $i=0;
        foreach ($clasificacion_tipo_productos as $clasificacion) {
            $j=0;
            $esp = [];
            foreach ($especificaciones as $especificacion) {
                if($clasificacion->id_clasificacion == $especificacion->id_clasificacion){
                    $valores=[
                        'id_valor' => $especificacion->id_especificacion,
                        'nombre_valor' => $especificacion->nombre_especificacion
                    ];
                    $esp[$j]=$valores;
                    $j=$j+1;
                }
            }
            $vector=[
                'id_clasificacion' => $clasificacion->id_clasificacion,
                'nombre_clasificacion' => $clasificacion->nombre_clasificacion,
                'especificaciones' => $esp
            ];
            $array[$i]=$vector;
            $i=$i+1;
        }


        $productos = DB::table('productos')
        ->where('estado_producto','=','activo')
        ->where('id_modelo','=',$request->id_modelo)
        ->select('id_producto','precio_base','declara_iva','codigo_producto')
        ->get();

        $producto_especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.id_especificacion','especificaciones.nombre_especificacion')
        ->get();

        //dd($especificaciones);

        $array2 = [];
        $i = 0;

        foreach ($productos as $producto) {
            $j=0;
            $esp = [];
            foreach ($producto_especificaciones as $producto_especificacion) {
                if($producto_especificacion->id_producto == $producto->id_producto){
                    $valores=[
                        'id_especificacion' => $producto_especificacion->id_especificacion,
                        'nombre_especificacion' => $producto_especificacion->nombre_especificacion
                    ];
                    $esp[$j]=$valores;
                    $j=$j+1;
                }
            }
            $vector2=[
                'id_producto' => $producto->id_producto,
                'especificaciones' => $esp
            ];
            $array2[$i]=$vector2;
            $i=$i+1;
        }

        $data[0]=$array;
        $data[1]=$array2;

        return ($data);
    }

    /*::::::::::::::::::::::::::::::::::::AJAX Clasificaciones Tipos Productos AJAX::::::::::::::::::::::::::::::::::::::*/
    public function codigoPrecio(Request $request){

        $productos = DB::table('productos')
        ->where('estado_producto','=','activo')
        ->where('id_producto','=',$request->id_producto)
        ->select('id_producto','precio_base','codigo_producto')
        ->get();
        return ($productos);
    }


/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR:::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        function obtener_nombre_producto($id_producto_nombre){

            $producto_nombre = DB::table('productos')
            ->where('id_producto','=',$id_producto_nombre)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->first();

            $especificaciones_nombre_producto = DB::table('producto_especificaciones')
            ->where('producto_especificaciones.id_producto','=',$id_producto_nombre)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();

            $nombre_producto = $producto_nombre->nombre_tipo_producto . " - " . $producto_nombre->nombre_marca . " - " . $producto_nombre->nombre_modelo;
            foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
            }

            DB::table('productos')
            ->where('id_producto', $id_producto_nombre)
            ->update([
                'nombre_producto' => $nombre_producto
            ]);
        }

        $productos = json_decode($request->todo);

        $date = Carbon::now();$id_user = Auth::id();
        $id_ingreso = DB::table('ingresos')->insertGetId(
            ['id_bodega' => $request->bodega, 'id_proveedor' => $request->proveedor, 'fecha_ingreso' => $date]
        );
        DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 21, 'accion' => 'crear', 'id_elemento' => $id_ingreso, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

        foreach ($productos as $producto) {

            if($producto->id_producto=="null" || $producto->id_producto=="" || $producto->id_producto=="x"){

                $id_producto = DB::table('productos')->insertGetId(
                    ['id_modelo' => $producto->modelo, 'precio_base' => $producto->precio, 'declara_iva' => $producto->iva, 'codigo_producto' => $producto->codigo, 'estado_producto' => 'activo']
                );

                DB::table('productos')
                ->where('id_producto', $id_producto)
                ->update(['code128' => str_pad($producto->modelo, 5, "0", STR_PAD_LEFT) . str_pad($id_producto, 7, "0", STR_PAD_LEFT)]);

                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 22, 'accion' => 'crear', 'id_elemento' => $id_producto, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                foreach ($producto->especificaciones as $especificacion) {
                    DB::table('producto_especificaciones')->insert(
                        ['id_producto' => $id_producto, 'id_especificacion' => $especificacion->id_especificacion]
                    );
                }
                DB::table('producto_ingresos')->insert(
                    ['id_ingreso' => $id_ingreso, 'id_producto' => $id_producto, 'cantidad' => $producto->cantidad]
                );

                DB::table('producto_bodegas')->insert(
                    ['id_bodega' => $request->bodega, 'id_producto' => $id_producto, 'cantidad' => $producto->cantidad]
                );

            }else{
                DB::table('producto_ingresos')->insert(
                    ['id_ingreso' => $id_ingreso, 'id_producto' => $producto->id_producto, 'cantidad' => $producto->cantidad]
                );

                $encontrados = DB::table('producto_bodegas')
                ->where('id_bodega','=',$request->bodega)
                ->where('id_producto','=',$producto->id_producto)
                ->increment('cantidad',$producto->cantidad);
                if($encontrados == 0){
                    DB::table('producto_bodegas')->insert(
                        ['id_bodega' => $request->bodega, 'id_producto' => $producto->id_producto, 'cantidad' => $producto->cantidad]
                    );
                }
                $id_producto = $producto->id_producto;
            }
            obtener_nombre_producto($id_producto);
        }

        $bodegas = DB::table('bodegas')
        ->where('estado_bodega','=','activo')
        ->select('bodegas.id_bodega','bodegas.nombre_bodega')
        ->get();

        $proveedores = DB::table('proveedores')
        ->where('estado_proveedor','=','activo')
        ->get();

        $tipo_productos = DB::table('tipo_productos')
        ->where('estado_tipo_producto','=','activo')
        ->get();

        $mensaje="exito";

        return view('productos.crear',compact('mensaje','tipo_productos','bodegas','proveedores'));

    }


/*:::::::::::::::::::::::::::::::::::::LISTA DE PRODUCTOS:::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


   public function lista(){

        $producto_membresias = DB::table('productos_especiales')
        ->where('nombre_producto','=','membresias')
        ->where('estado_producto_especial','=','activo')
        ->first();

        if(isset($producto_membresias)){
            $producto_membresias = $producto_membresias->id_tipo_producto_especial;
        }else{
            $producto_membresias = 0;
        }

        return view('productos.lista',compact('producto_membresias'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::BUSCAR PRODUCTOS:::::::::::::::::::::::::::::::::::::::::::*/


    public function buscar(Request $request){

        $productos = DB::table('productos')
        ->where('estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->where('estado_modelo','=','activo')
        ->where('id_tipo_producto','!=',$request->tipo_producto_membresias);

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
                                <a href="" class="dropdown-content"><i class="icon-forward"> </i> VER / BUSCAR</a>
                                <i onclick="ver(' . $producto->id_producto . ');" class="icon-forward i-acciones"> </i> &nbsp;
                            </div>
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
/*:::::::::::::::::::::::::::::::::::::::VER PRODUCTO:::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){

        $producto = DB::table('productos')
        ->where('productos.id_producto','=',$request->ver)
        ->where('estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->first();

        $imagen = DB::table('imagen_productos')
        ->where('id_producto','=',$request->producto)
        ->first();
        if(isset($imagen)){ $producto->imagen = $imagen->nombre_imagen;}
        else{    $producto->imagen = "default.png"; }

        $producto_sucursales = DB::table('producto_sucursales')
        ->where('producto_sucursales.id_producto','=',$request->ver)
        ->where('producto_sucursales.cantidad','>',0)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','producto_sucursales.id_sucursal')
        ->get();

        $producto_bodegas = DB::table('producto_bodegas')
        ->where('producto_bodegas.id_producto','=',$request->ver)
        ->where('producto_bodegas.cantidad','>',0)
        ->leftJoin('bodegas','bodegas.id_bodega','=','producto_bodegas.id_bodega')
        ->get();

        return view('productos.ver',compact('producto','producto_sucursales','producto_bodegas'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::EDITAR PRODUCTO:::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){


        $producto = DB::table('productos')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->where('id_producto','=',$request->editar)
        ->where('estado_producto','=','activo')
        ->first();

        $imagenes_producto = DB::table('imagen_productos')
        ->where('id_producto','=',$request->editar)
        ->get();

        if(is_numeric($request->editar)&&($producto != null)) {

            $imagen = DB::table('imagen_productos')
            ->where('id_producto','=',$request->editar)
            ->first();
            if(isset($imagen)){ $producto->imagen = $imagen->nombre_imagen;}
            else{    $producto->imagen = "default.png"; }

            $producto_especificaciones = DB::table('producto_especificaciones')
            ->where('producto_especificaciones.id_producto','=',$producto->id_producto)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('especificaciones.id_especificacion','especificaciones.nombre_especificacion','clasificaciones.id_clasificacion','clasificaciones.nombre_clasificacion','producto_especificaciones.id_producto_especificaciones')
            ->get();

            $especificaciones = "";
            foreach ($producto_especificaciones as $producto_especificacion) {
                    $especificaciones = $especificaciones . ", " . $producto_especificacion->nombre_especificacion;
            }
            $producto->especificaciones = $especificaciones;

            $promocion_producto = DB::table('promocion_productos')
            ->where('id_producto','=',$producto->id_producto)
            ->where('estado_promocion_producto','=','activo')
            ->leftJoin('promociones','promociones.id_promocion','=','promocion_productos.id_promocion')
            ->first();

            if(isset($promocion_producto)){
                    $precio_promocion = $producto->precio_base - (($producto->precio_base * $promocion_producto->porcentaje_descuento) / 100);
                    $producto->precio_promocion = $precio_promocion;
                    $producto->porcentaje_descuento = $promocion_producto->porcentaje_descuento;
                    $producto->nombre_promocion = $promocion_producto->nombre_promocion;
            }

            //dd($producto_especificaciones);

            return view('productos.modificar',compact('producto','producto_especificaciones','imagenes_producto'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('productos.lista',['estatus' => $estatus]);
        }

    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::EDITAR PRODUCTO:::::::::::::::::::::::::::::::::::::::::::*/


    public function editarEspecificacion(Request $request){
        
        $producto_especificacion = DB::table('producto_especificaciones')
        ->where('id_producto_especificaciones','=',$request->editar)
        ->leftJoin('productos','productos.id_producto','=','producto_especificaciones.id_producto')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->first();

        if(is_numeric($request->editar)&&($producto_especificacion != null)) {

            $imagen = DB::table('imagen_productos')
            ->where('id_producto','=',$producto_especificacion->id_producto)
            ->first();
            if(isset($imagen)){ $producto_especificacion->imagen = $imagen->nombre_imagen;}
            else{    $producto_especificacion->imagen = "default.png"; }

            $productos_especificaciones = DB::table('producto_especificaciones')
            ->where('producto_especificaciones.id_producto','=',$producto_especificacion->id_producto)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('especificaciones.id_especificacion','especificaciones.nombre_especificacion','clasificaciones.id_clasificacion','clasificaciones.nombre_clasificacion','producto_especificaciones.id_producto_especificaciones')
            ->get();

            $especificaciones = "";
            foreach ($productos_especificaciones as $productos_especificacion) {
                    $especificaciones = $especificaciones . ", " . $productos_especificacion->nombre_especificacion;
            }
            $producto_especificacion->especificaciones = $especificaciones;

            $promocion_producto = DB::table('promocion_productos')
            ->where('id_producto','=',$producto_especificacion->id_producto)
            ->where('estado_promocion_producto','=','activo')
            ->leftJoin('promociones','promociones.id_promocion','=','promocion_productos.id_promocion')
            ->first();

            if(isset($promocion_producto)){
                    $precio_promocion = $producto_especificacion->precio_base - (($producto_especificacion->precio_base * $promocion_producto->porcentaje_descuento) / 100);
                    $producto_especificacion->precio_promocion = $precio_promocion;
                    $producto_especificacion->porcentaje_descuento = $promocion_producto->porcentaje_descuento;
                    $producto_especificacion->nombre_promocion = $promocion_producto->nombre_promocion;
            }

            return view('productos.modificarEspecificacion',compact('producto_especificacion'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('productos.lista',['estatus' => $estatus]);
        }
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::MODIFICAR PRODUCTO:::::::::::::::::::::::::::::::::::::::::::*/


    public function modificarEspecificacion(Request $request){

        function obtener_nombre_producto($id_producto_nombre){

            $producto_nombre = DB::table('productos')
            ->where('id_producto','=',$id_producto_nombre)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->first();

            $especificaciones_nombre_producto = DB::table('producto_especificaciones')
            ->where('producto_especificaciones.id_producto','=',$id_producto_nombre)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();

            $nombre_producto = $producto_nombre->nombre_tipo_producto . " - " . $producto_nombre->nombre_marca . " - " . $producto_nombre->nombre_modelo;
            foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
            }

            DB::table('productos')
            ->where('id_producto', $id_producto_nombre)
            ->update([
                'nombre_producto' => $nombre_producto
            ]);
        }

        $especificacion = DB::table('especificaciones')
        ->where('nombre_especificacion','=',$request->nombre_especificacion)
        ->where('id_clasificacion','=',$request->id_clasificacion)
        ->first();

        if(isset($especificacion)){

                DB::table('producto_especificaciones')
                ->where('id_producto_especificaciones','=',$request->id_producto_especificaciones)
                ->update([
                    'id_especificacion' => $especificacion->id_especificacion
                ]);
                $estatus="exito";
        }else{

                $id_especificacion = DB::table('especificaciones')->insertGetId(
                    ['id_clasificacion' => $request->id_clasificacion, 'nombre_especificacion' => $request->nombre_especificacion, 'estado_especificacion' => 'activo']
                );

                DB::table('producto_especificaciones')
                ->where('id_producto_especificaciones','=',$request->id_producto_especificaciones)
                ->update([
                    'id_especificacion' => $id_especificacion
                ]);
                $estatus="exito";
        }

        $producto_editar = DB::table('producto_especificaciones')
        ->where('id_producto_especificaciones','=',$request->id_producto_especificaciones)
        ->select('id_producto')
        ->first();

        obtener_nombre_producto($producto_editar->id_producto);

        return redirect()->route('productos.lista',['estatus' => $estatus]);

    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::EDITAR PRECIO PRODUCTO:::::::::::::::::::::::::::::::::::::*/


    public function editarPrecio(Request $request){

        DB::table('productos')
        ->where('id_producto','=',$request->id_producto)
        ->update([
                    'precio_base' => $request->precio
        ]);

        return ("exito");
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::EDITAR MARCA PRODUCTO:::::::::::::::::::::::::::::::::::::*/


    public function editarMarca(Request $request){

        $nueva_marca = DB::table('marcas')
        ->where('nombre_marca','=',$request->nombre_marca)
        ->where('estado_marca','=','activo')
        ->first();

        $producto = DB::table('productos')
        ->where('id_producto','=',$request->id_producto)
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->first();
        $nombre_modelo = $producto->nombre_modelo;

        if (isset($nueva_marca)) {

            $id_marca = $nueva_marca->id_marca;

            //BUSCAR SI ESA MARCA YA TIENE ESE NOMBRE MODELO
            $modelo = DB::table('modelos')
            ->where('id_marca','=',$id_marca)
            ->where('id_tipo_producto','=',$request->id_tipo_producto)
            ->where('nombre_modelo','=',$nombre_modelo)
            ->first();

            if (isset($modelo)) {
                //SI LO TIENE OBTENGO EL ID_MODELO Y ACTUALIZO EL PRODUCTO CON ESE ID_MODELO
                DB::table('productos')
                ->where('id_producto','=',$request->id_producto)
                ->update([
                            'id_modelo' => $modelo->id_modelo
                ]);
            }else{
                //SINO CREO EL MODELO CON ESA MARCA Y NOMBRE DE MODELO Y LUEGO ACTUALIZO EL PRODUCTO CON ESE ID_MODELO_CREADO
                $id_modelo = DB::table('modelos')->insertGetId([
                    'nombre_modelo' => $nombre_modelo,
                    'id_tipo_producto' => $request->id_tipo_producto,
                    'id_marca' => $id_marca,
                    'estado_modelo' => 'activo'
                ]);

                DB::table('productos')
                ->where('id_producto','=',$request->id_producto)
                ->update([
                            'id_modelo' => $id_modelo
                ]);
            }

        }else{

            $id_marca = DB::table('marcas')->insertGetId([
                'nombre_marca' => $request->nombre_marca,
                'estado_marca' => 'activo'
            ]);
            DB::table('tipo_producto_marcas')->insert([
                'id_tipo_producto' => $request->id_tipo_producto,
                'id_marca' => $id_marca,
                'estado_tipo_producto_marca' => 'activo'
            ]);
            //CREAR NUEVO MODELO CON EL NOMBRE MODELO DEL CAMPO DE LA VISTA, MARCA QUE CREE Y TIPO DE PRODUCTO Y LUEGO ACTUALIZAR EL ID MODELO DE LA TABLA PRODUCTOS DEL REQUEST ID_PRODUCTO
            $id_modelo = DB::table('modelos')->insertGetId([
                'nombre_modelo' => $nombre_modelo,
                'id_tipo_producto' => $request->id_tipo_producto,
                'id_marca' => $id_marca,
                'estado_modelo' => 'activo'
            ]);

            DB::table('productos')
            ->where('id_producto','=',$request->id_producto)
            ->update([
                        'id_modelo' => $id_modelo
            ]);

        }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::OJO REVISAR SI SE DEBEN CAMBIAR TODAS LAS MARCAS:::::::::::::::::::::::::::::*/


        function obtener_nombre_producto($id_producto_nombre){

            $producto_nombre = DB::table('productos')
            ->where('id_producto','=',$id_producto_nombre)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->first();

            $especificaciones_nombre_producto = DB::table('producto_especificaciones')
            ->where('producto_especificaciones.id_producto','=',$id_producto_nombre)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();

            $nombre_producto = $producto_nombre->nombre_tipo_producto . " - " . $producto_nombre->nombre_marca . " - " . $producto_nombre->nombre_modelo;
            foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
            }

            DB::table('productos')
            ->where('id_producto', $id_producto_nombre)
            ->update([
                'nombre_producto' => $nombre_producto
            ]);
        }
        obtener_nombre_producto($request->id_producto);

        return ("exito");
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::EDITAR MODELO PRODUCTO:::::::::::::::::::::::::::::::::::::*/


    public function editarModelo(Request $request){

        $producto = DB::table('productos')
        ->where('id_producto','=',$request->id_producto)
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->first();
        $id_marca = $producto->id_marca;

        $nuevo_modelo = DB::table('modelos')
        ->where('nombre_modelo','=',$request->nombre_modelo)
        ->where('id_tipo_producto','=',$request->id_tipo_producto)
        ->where('id_marca','=',$id_marca)
        ->where('estado_modelo','=','activo')
        ->first();

        if (isset($nuevo_modelo)) {

            $id_modelo = $nuevo_modelo->id_modelo;

            DB::table('productos')
            ->where('id_producto','=',$request->id_producto)
            ->update([
                        'id_modelo' => $id_modelo
            ]);

        }else{

            $id_modelo = DB::table('modelos')->insertGetId([
                'nombre_modelo' => $request->nombre_modelo,
                'id_tipo_producto' => $request->id_tipo_producto,
                'id_marca' => $id_marca,
                'estado_modelo' => 'activo'
            ]);

            DB::table('productos')
            ->where('id_producto','=',$request->id_producto)
            ->update([
                        'id_modelo' => $id_modelo
            ]);
        }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::OJO REVISAR SI SE DEBEN CAMBIAR TODOS LOS MODELOS:::::::::::::::::::::::::::::*/


        function obtener_nombre_producto($id_producto_nombre){

            $producto_nombre = DB::table('productos')
            ->where('id_producto','=',$id_producto_nombre)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
            ->first();

            $especificaciones_nombre_producto = DB::table('producto_especificaciones')
            ->where('producto_especificaciones.id_producto','=',$id_producto_nombre)
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
            ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion')
            ->get();

            $nombre_producto = $producto_nombre->nombre_tipo_producto . " - " . $producto_nombre->nombre_marca . " - " . $producto_nombre->nombre_modelo;
            foreach ($especificaciones_nombre_producto as $especificacion_nombre_producto) {
                $nombre_producto .= ", " . $especificacion_nombre_producto->nombre_clasificacion . ": " . $especificacion_nombre_producto->nombre_especificacion;
            }

            DB::table('productos')
            ->where('id_producto', $id_producto_nombre)
            ->update([
                'nombre_producto' => $nombre_producto
            ]);
        }
        obtener_nombre_producto($request->id_producto);

        return ("exito");
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::CREAR EL NOMBRE DEL PRODUCTO STRING PARA MEJORAR LA EFICIENCIA::::::::::::::::*/


    public function nombres(){

        $productos = DB::table('productos')
        ->where('estado_producto','=','activo')
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
        ->select('productos.id_producto','productos.precio_base','productos.codigo_producto','tipo_productos.nombre_tipo_producto','modelos.nombre_modelo','marcas.nombre_marca','productos.code128')
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

                    $especificaciones= $especificaciones . ", " . $producto_especificacion->nombre_clasificacion . ": " . $producto_especificacion->nombre_especificacion;
                }
            }
            $producto->especificaciones = $producto->nombre_tipo_producto . " - " . $producto->nombre_marca . " - " . $producto->nombre_modelo . $especificaciones;
        }

        foreach ($productos as $producto) {
            DB::table('productos')
            ->where('id_producto', $producto->id_producto)
            ->update([
                'nombre_producto' => $producto->especificaciones
            ]);
        }

        dd("Nombres creados para: ", $productos);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::CÓDIGOS DE BARRA:::::::::::::::::::::::::::::::::::::::::::*/


    public function cargar_imagen(Request $request){

        $date = Carbon::now();
        $hora = date("H-i-s");
        $nombre_imagen = 'producto-' . $date->toDateString() . '-' . $hora . '-' . $request->id_producto . "." . $request->file('imagen_producto')->extension();
        $request->imagen_producto->move('public/imagenes/sistema/productos',$nombre_imagen);

        DB::table('imagen_productos')->insert([
            'id_producto' => $request->id_producto,
            'nombre_imagen' => $nombre_imagen
        ]);

        $estatus = "exito_imagen";

        return redirect()->route('productos.lista',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::CÓDIGOS DE BARRA:::::::::::::::::::::::::::::::::::::::::::*/


    public function codigosBarra(){
        return view('productos.codigos');
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::IMPRIMIR CÓDIGOS:::::::::::::::::::::::::::::::::::::::::::*/


    public function Imprimircodigos(){
        return view('productos.imprimir');
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR::::::::::::::::::::::::::::::::::::::::::::*/
}

