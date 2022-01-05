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

class AdminEcommerceController extends Controller
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

    public function cargar()
    {

        //dd("cargar");

        $mensaje = "";

        /*$bodegas = DB::table('bodegas')
        ->where('estado_bodega','=','activo')
        ->where('id_bodega','!=',0)
        ->select('bodegas.id_bodega','bodegas.nombre_bodega')
        ->get();*/

        $proveedores = DB::table('proveedores')
            ->where('estado_proveedor', '=', 'activo')
            ->get();

        $tipo_productos = DB::table('tipo_productos')
            ->where('estado_tipo_producto', '=', 'activo')
            ->get();

        return view('adminecommerce.cargar', compact('mensaje', 'tipo_productos', 'proveedores'));
    }


    /*:::::::::::::::::::::::::::::::::::::::::::AJAX Marca Tipo Producto AJAX::::::::::::::::::::::::::::::::::::::::::::::::*/
    public function marcaTipoProductos(Request $request)
    {
        $marcas = DB::table('tipo_producto_marcas')
            ->where('estado_tipo_producto_marca', '=', 'activo')
            ->where('tipo_producto_marcas.id_tipo_producto', '=', $request->id_tipo_producto)

            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'tipo_producto_marcas.id_tipo_producto')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')

            ->leftJoin('marcas', 'marcas.id_marca', '=', 'tipo_producto_marcas.id_marca')
            ->where('estado_marca', '=', 'activo')
            ->select('marcas.id_marca', 'marcas.nombre_marca', 'tipo_productos.iva')
            ->get();
        return ($marcas);
    }


    /*:::::::::::::::::::::::::::::::::::::::::::AJAX Modelo Marcas AJAX::::::::::::::::::::::::::::::::::::::::::::::::*/
    public function modeloMarcas(Request $request)
    {

        $modelos = DB::table('modelos')
            ->where('estado_modelo', '=', 'activo')
            ->where('id_tipo_producto', '=', $request->id_tipo_producto)
            ->where('id_marca', '=', $request->id_marca)
            ->select('modelos.id_modelo', 'modelos.nombre_modelo')
            ->get();
        return ($modelos);
    }

    /*::::::::::::::::::::::::::::::::::::AJAX Clasificaciones Tipos Productos AJAX::::::::::::::::::::::::::::::::::::::*/
    public function clasificacionesTipoProductos(Request $request)
    {

        $clasificacion_tipo_productos = DB::table('clasificacion_tipo_productos')
            ->where('estado_clasificacion_tipo_producto', '=', 'activo')
            ->where('id_tipo_producto', '=', $request->id_tipo_producto)
            ->leftJoin('clasificaciones', 'clasificaciones.id_clasificacion', '=', 'clasificacion_tipo_productos.id_clasificacion')
            ->where('estado_clasificacion', '=', 'activo')
            ->select('clasificaciones.id_clasificacion', 'clasificaciones.nombre_clasificacion')
            ->get();


        $especificaciones = DB::table('especificaciones')
            ->where('estado_especificacion', '=', 'activo')
            ->select('id_clasificacion', 'id_especificacion', 'nombre_especificacion')
            ->get();


        $array = [];
        $vector = [];
        $esp = [];
        $valores = [];
        $i = 0;
        foreach ($clasificacion_tipo_productos as $clasificacion) {
            $j = 0;
            $esp = [];
            foreach ($especificaciones as $especificacion) {
                if ($clasificacion->id_clasificacion == $especificacion->id_clasificacion) {
                    $valores = [
                        'id_valor' => $especificacion->id_especificacion,
                        'nombre_valor' => $especificacion->nombre_especificacion
                    ];
                    $esp[$j] = $valores;
                    $j = $j + 1;
                }
            }
            $vector = [
                'id_clasificacion' => $clasificacion->id_clasificacion,
                'nombre_clasificacion' => $clasificacion->nombre_clasificacion,
                'especificaciones' => $esp
            ];
            $array[$i] = $vector;
            $i = $i + 1;
        }


        $productos = DB::table('productos')
            ->where('estado_producto', '=', 'activo')
            ->where('id_modelo', '=', $request->id_modelo)
            ->select('id_producto', 'precio_base', 'declara_iva', 'codigo_producto')
            ->get();

        $producto_especificaciones = DB::table('especificaciones')
            ->where('estado_especificacion', '=', 'activo')
            ->leftJoin('producto_especificaciones', 'producto_especificaciones.id_especificacion', '=', 'especificaciones.id_especificacion')
            ->select('producto_especificaciones.id_producto', 'especificaciones.id_especificacion', 'especificaciones.nombre_especificacion')
            ->get();

        //dd($especificaciones);

        $array2 = [];
        $i = 0;

        foreach ($productos as $producto) {
            $j = 0;
            $esp = [];
            foreach ($producto_especificaciones as $producto_especificacion) {
                if ($producto_especificacion->id_producto == $producto->id_producto) {
                    $valores = [
                        'id_especificacion' => $producto_especificacion->id_especificacion,
                        'nombre_especificacion' => $producto_especificacion->nombre_especificacion
                    ];
                    $esp[$j] = $valores;
                    $j = $j + 1;
                }
            }
            $vector2 = [
                'id_producto' => $producto->id_producto,
                'especificaciones' => $esp
            ];
            $array2[$i] = $vector2;
            $i = $i + 1;
        }

        $data[0] = $array;
        $data[1] = $array2;

        return ($data);
    }

    /*::::::::::::::::::::::::::::::::::::AJAX Clasificaciones Tipos Productos AJAX::::::::::::::::::::::::::::::::::::::*/
    public function codigoPrecio(Request $request)
    {

        $productos = DB::table('productos')
            ->where('estado_producto', '=', 'activo')
            ->where('id_producto', '=', $request->id_producto)
            ->select('id_producto', 'precio_base', 'codigo_producto')
            ->get();
        return ($productos);
    }

    /*:::::::::::::::::::::::::::::::::::::::::::GUARDAR:::::::::::::::::::::::::::::::::::::::::::::::*/

    public function guardar(Request $request)
    {
        $request->bodega = 0;
        $hora = date('H-i-s');

        $date = Carbon::now();
        $id_user = Auth::id();
        $id_ingreso = DB::table('ingresos')->insertGetId(
            ['id_bodega' => $request->bodega, 'id_proveedor' => $request->proveedor, 'fecha_ingreso' => $date]
        );
        DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 21, 'accion' => 'crear', 'id_elemento' => $id_ingreso, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

        $productos = json_decode($request->todo);
        foreach ($productos as $producto) {

            if ($producto->id_producto == "null" || $producto->id_producto == "" || $producto->id_producto == "x") {

                $id_producto = DB::table('productos')->insertGetId(
                    ['id_modelo' => $producto->modelo, 'precio_base' => $producto->precio, 'declara_iva' => $producto->iva, 'codigo_producto' => $producto->codigo, 'estado_producto' => 'activo']
                );
                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 22, 'accion' => 'crear', 'id_elemento' => $id_producto, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                foreach ($producto->especificaciones as $especificacion) {
                    DB::table('producto_especificaciones')->insert(
                        ['id_producto' => $id_producto, 'id_especificacion' => $especificacion->id_especificacion]
                    );
                }

                $img = $producto->numero;
                $imagenes = $request->$img;
                foreach ($imagenes as $imagen) {
                    $nombre_imagen = 'prod-ec-oa-' . $id_producto . "-" . $hora . $imagen->getClientOriginalName();
                    $imagen->move('public/imagenes/sistema/productos', $nombre_imagen);
                    DB::table('imagen_productos')->insert(['id_producto' => $id_producto, 'nombre_imagen' => $nombre_imagen]);
                }

                DB::table('producto_ingresos')->insert(
                    ['id_ingreso' => $id_ingreso, 'id_producto' => $id_producto, 'cantidad' => $producto->cantidad]
                );

                DB::table('producto_bodegas')->insert(
                    ['id_bodega' => $request->bodega, 'id_producto' => $id_producto, 'cantidad' => $producto->cantidad]
                );
            } else {
                DB::table('producto_ingresos')->insert(
                    ['id_ingreso' => $id_ingreso, 'id_producto' => $producto->id_producto, 'cantidad' => $producto->cantidad]
                );

                $encontrados = DB::table('producto_bodegas')
                    ->where('id_bodega', '=', $request->bodega)
                    ->where('id_producto', '=', $producto->id_producto)
                    ->increment('cantidad', $producto->cantidad);
                if ($encontrados == 0) {
                    DB::table('producto_bodegas')->insert(
                        ['id_bodega' => $request->bodega, 'id_producto' => $producto->id_producto, 'cantidad' => $producto->cantidad]
                    );
                }

                $img = $producto->numero;
                $imagenes = $request->$img;
                foreach ($imagenes as $imagen) {
                    $nombre_imagen = 'prod-ec-oa-' . $producto->id_producto . "-" . $hora . $imagen->getClientOriginalName();
                    $imagen->move('public/imagenes/sistema/productos', $nombre_imagen);
                    DB::table('imagen_productos')->insert(['id_producto' => $producto->id_producto, 'nombre_imagen' => $nombre_imagen]);
                }
            }
        }

        /*$bodegas = DB::table('bodegas')
        ->where('estado_bodega','=','activo')
        ->select('bodegas.id_bodega','bodegas.nombre_bodega')
        ->get();*/

        $proveedores = DB::table('proveedores')
            ->where('estado_proveedor', '=', 'activo')
            ->get();

        $tipo_productos = DB::table('tipo_productos')
            ->where('estado_tipo_producto', '=', 'activo')
            ->get();

        $mensaje = "exito";

        return view('adminecommerce.cargar', compact('mensaje', 'tipo_productos', 'proveedores'));
    }

    /*:::::::::::::::::::::::::::::::::::::::::::LISTA::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function lista()
    {



        $productos = DB::table('producto_bodegas')
            ->where('id_bodega', '=', 0)
            ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->where('productos.estado_producto', '=', 'activo')
            ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
            ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
            ->select('productos.id_producto', 'productos.precio_base', 'productos.codigo_producto', 'producto_bodegas.cantidad', 'tipo_productos.nombre_tipo_producto', 'modelos.nombre_modelo', 'marcas.nombre_marca')
            ->get();


        $producto_especificaciones = DB::table('especificaciones')
            ->where('estado_especificacion', '=', 'activo')
            ->leftJoin('producto_especificaciones', 'producto_especificaciones.id_especificacion', '=', 'especificaciones.id_especificacion')
            ->leftJoin('clasificaciones', 'clasificaciones.id_clasificacion', '=', 'especificaciones.id_clasificacion')
            ->select('producto_especificaciones.id_producto', 'especificaciones.nombre_especificacion', 'clasificaciones.nombre_clasificacion')
            ->get();

        $imagenes = DB::table('producto_bodegas')
            ->where('id_bodega', '=', 0)
            ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->select('imagen_productos.id_producto', 'imagen_productos.nombre_imagen')
            ->get();

        //dd($productos,$imagenes);

        foreach ($productos as $producto) {
            $especificaciones = "";
            foreach ($producto_especificaciones as $producto_especificacion) {
                if ($producto_especificacion->id_producto == $producto->id_producto) {
                    $especificaciones = ", " . $producto_especificacion->nombre_clasificacion . ": " . $producto_especificacion->nombre_especificacion . $especificaciones;
                }
            }
            $producto->especificaciones = $especificaciones;
            $band = false;
            $producto->imagen = "default.png";
            foreach ($imagenes as $imagen) {
                if (($producto->id_producto == $imagen->id_producto) && ($band == false)) {
                    $producto->imagen = $imagen->nombre_imagen;
                    $band = true;
                }
            }
        }


        return view('adminecommerce.lista', compact('productos'));
    }

    /*:::::::::::::::::::::::::::::::::::::::::::CREAR EXCEL::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function crearExcel(Request $request)
    {

        $tipo_productos = DB::table('tipo_productos')
            ->where('estado_tipo_producto', '=', 'activo')
            ->get();

        return view('adminecommerce.crear_excel', compact('tipo_productos'));
    }


    /*:::::::::::::::::::::::::::::::::::::::::::CREAR EXCEL::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function cargarExcel(Request $request)
    {

        if (isset($request->estatus)) {
            $estatus = $request->estatus;
        } else {
            $estatus = "";
        }

        $tipo_productos = DB::table('tipo_productos')
            ->where('estado_tipo_producto', '=', 'activo')
            ->get();

        return view('adminecommerce.cargar_excel', compact('tipo_productos', 'estatus'));
    }

    public function ventas()
    {
        $ventas = DB::table('pruebapago')->orderBy('id_prueba', 'desc')->get();
        return view('adminecommerce.ventas', compact('ventas'));
    }

    public function ventas_productos(Request $request)
    {

        $productos = DB::table('productos_vendidos')->where('referencia', '=', $request->ver)->get();
        //   dd($productos);  
        return view('adminecommerce.productos_vendidos', compact('productos'));
    }

    public function producto_comentarios(Request $request)
    {
        $comentarios = DB::table('producto_comentario')
            ->where('id_producto', '=', $request->ver)->get();
        return view('adminecommerce.producto_comentarios', compact('comentarios'));
    }

    public function producto_comentarios_editar(Request $request)
    {
       $comentario = DB::table('producto_comentario')
        ->where('id', $request->editar)
        ->first();  

        if ($comentario->estado == 1) {
            $estado = 0;
        } else {
            $estado = 1;
        }
         
        DB::table('producto_comentario')
            ->where('id', $request->editar)
            ->update([
                'estado' => $estado
            ]);


        return redirect()->route('adminecommerce.lista');
    }
}
