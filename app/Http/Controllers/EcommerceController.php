<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


class EcommerceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::INDEX ECOMMERCE:::::::::::::::::::::::::::::::::::::::::::::*/


    public function index()
    {

        // https://www.youtube.com/watch?v=XrfFugq9AIY

        $producto_bodegas = DB::table('producto_bodegas')
            ->where('producto_bodegas.id_bodega', '=', 0)
            ->where('producto_bodegas.cantidad', '>', 0)
            ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->where('productos.estado_producto', '=', 'activo')
            ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
            ->where('modelos.estado_modelo', '=', 'activo')
            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
            ->where('marcas.estado_marca', '=', 'activo')
            ->orderBy('productos.id_producto', 'desc')
            ->select('productos.id_producto', 'productos.precio_base', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca')
            ->get();

        $tipo_productos = DB::table('tipo_productos')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->get();

        $especificaciones = DB::table('producto_especificaciones')
            ->leftJoin('especificaciones', 'especificaciones.id_especificacion', '=', 'producto_especificaciones.id_especificacion')
            ->select('producto_especificaciones.id_producto', 'especificaciones.nombre_especificacion')
            ->get();

        $imagenes = DB::table('imagen_productos')
            ->select('id_producto', 'nombre_imagen')
            ->get();

        $tipos_productos_ecommerce = [];
        $i = 0;
        foreach ($tipo_productos as $tipo_producto) {
            $band = false;
            foreach ($producto_bodegas as $producto_bodega) {
                if (($tipo_producto->id_tipo_producto == $producto_bodega->id_tipo_producto) && ($band == false) && ($i < 5)) {
                    $vector = [
                        'id_tipo_producto' => $tipo_producto->id_tipo_producto,
                        'nombre_tipo_producto' => $tipo_producto->nombre_tipo_producto
                    ];
                    $tipos_productos_ecommerce[$i] = $vector;
                    $i = $i + 1;
                    $band = true;
                }

                $esp = "";
                foreach ($especificaciones as $especificacion) {
                    if ($especificacion->id_producto == $producto_bodega->id_producto) {
                        $esp = $esp . " " . $especificacion->nombre_especificacion;
                    }
                }
                $producto_bodega->especificaciones = $esp;

                $imagen_producto = "default.png";
                $bandImg = false;
                foreach ($imagenes as $imagen) {
                    if (($imagen->id_producto == $producto_bodega->id_producto) && ($bandImg == false)) {
                        $imagen_producto = $imagen->nombre_imagen;
                        $bandImg = true;
                    }
                }
                $producto_bodega->imagen = $imagen_producto;
            }
        }
        $band = count($tipo_productos);
        $tipos_productos_ecommerce = json_encode($tipos_productos_ecommerce);
        $tipos_productos_ecommerce = json_decode($tipos_productos_ecommerce);

        $tipos_identificacion = DB::table('tipos_identificacion')
            ->where('estado_tipo_identificacion', '=', 'activo')
            ->get();

        $sucursales = DB::table('sucursals')
            ->where('estado_sucursal', '=', 'activo')
            ->where('id_sucursal', '!=', 0)
            ->get();

        return view('ecommerce.index', compact('producto_bodegas', 'tipos_productos_ecommerce', 'band') + ['tipos_identificacion' => $tipos_identificacion, 'sucursales' => $sucursales]);
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::INDEX ACTUAL DEL ECOMMERCE::::::::::::::::::::::::::::::::::::::::*/


    public function categorias()
    {

        $tipo_productos_marcas = DB::table('producto_bodegas')
            ->where('producto_bodegas.id_bodega', '=', 0)
            ->where('producto_bodegas.cantidad', '>', 0)
            ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->where('productos.estado_producto', '=', 'activo')
            ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
            ->where('modelos.estado_modelo', '=', 'activo')
            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->select('tipo_productos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto')
            ->distinct()
            ->get();

        foreach ($tipo_productos_marcas as $tipo_producto_marca) {
            $marcas = DB::table('tipo_producto_marcas')
                ->where('id_tipo_producto', '=', $tipo_producto_marca->id_tipo_producto)
                ->where('estado_tipo_producto_marca', '=', 'activo')
                ->leftJoin('marcas', 'marcas.id_marca', '=', 'tipo_producto_marcas.id_marca')
                ->where('marcas.estado_marca', '=', 'activo')
                ->select('marcas.id_marca', 'marcas.nombre_marca')
                ->distinct()
                ->get();
            $tipo_producto_marca->marcas = $marcas;
        }

        $producto_bodegas = DB::table('producto_bodegas')
            ->where('producto_bodegas.id_bodega', '=', 0)
            ->where('producto_bodegas.cantidad', '>', 0)
            ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->where('productos.estado_producto', '=', 'activo')
            ->leftJoin('producto_especificaciones', 'producto_especificaciones.id_producto', '=', 'productos.id_producto')
            ->where('estado_producto_especificacion', '=', 'activo')
            ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'productos.id_producto')
            ->where('id_imagen_producto', '!=', null)
            ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
            ->where('modelos.estado_modelo', '=', 'activo')
            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
            ->where('marcas.estado_marca', '=', 'activo')
            ->groupBy(['productos.id_producto'])
            ->orderBy('productos.id_producto', 'desc')
            ->select('productos.id_producto', 'productos.precio_base', 'productos.nombre_producto', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca', 'imagen_productos.nombre_imagen')
            ->distinct()
            ->paginate(16);

        return view('ecommerce.categorias', compact('producto_bodegas', 'tipo_productos_marcas'));
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::::CARRITO:::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function carrito(Request $request)
    {
        //dd($request);
        $producto_bodegas = DB::table('producto_bodegas')
            ->where('producto_bodegas.id_bodega', '=', 0)
            ->where('producto_bodegas.cantidad', '>', 0)
            ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->where('productos.estado_producto', '=', 'activo')
            ->leftJoin('producto_especificaciones', 'producto_especificaciones.id_producto', '=', 'productos.id_producto')
            ->where('estado_producto_especificacion', '=', 'activo')
            ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'productos.id_producto')
            ->where('id_imagen_producto', '!=', null)
            ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
            ->where('modelos.estado_modelo', '=', 'activo')
            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
            ->where('marcas.estado_marca', '=', 'activo')
            ->groupBy(['productos.id_producto'])
            ->orderBy('productos.id_producto', 'desc')
            ->select('productos.id_producto', 'productos.precio_base', 'productos.nombre_producto', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca', 'imagen_productos.nombre_imagen')
            ->distinct()
            ->paginate(16);

        return view('ecommerce.carrito', compact('producto_bodegas'));
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::CATEGORIAS::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function categoria($id_tipo_producto)
    {

        if (is_numeric($id_tipo_producto)) {

            $producto_bodegas = DB::table('producto_bodegas')
                ->where('producto_bodegas.id_bodega', '=', 0)
                ->where('producto_bodegas.cantidad', '>', 0)
                ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
                ->where('productos.estado_producto', '=', 'activo')
                ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'productos.id_producto')
                ->where('id_imagen_producto', '!=', null)
                ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
                ->where('modelos.estado_modelo', '=', 'activo')
                ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
                ->where('tipo_productos.id_tipo_producto', '=', $id_tipo_producto)
                ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
                ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
                ->where('marcas.estado_marca', '=', 'activo')
                ->groupBy(['productos.id_producto'])
                ->orderBy('productos.id_producto', 'desc')
                ->select('productos.id_producto', 'productos.precio_base', 'productos.nombre_producto', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca', 'imagen_productos.nombre_imagen')
                ->distinct()
                ->paginate(16);

            $nombre_tipo_producto = DB::table('tipo_productos')
                ->where('id_tipo_producto', '=', $id_tipo_producto)
                ->first();

            return view('ecommerce.categoria', compact('producto_bodegas', 'nombre_tipo_producto'));
        } else {
            return redirect()->route('ecommerce.error');
        }
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::CATEGORIA PRODUCTO::::::::::::::::::::::::::::::::::::::::::::*/


    public function categoria_producto($id_tipo_producto, $id_especificacion)
    {


        $producto_bodegas = DB::table('producto_bodegas')
            ->where('producto_bodegas.id_bodega', '=', 0)
            ->where('producto_bodegas.cantidad', '>', 0)
            ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->where('productos.estado_producto', '=', 'activo')
            ->leftJoin('producto_especificaciones', 'producto_especificaciones.id_producto', '=', 'productos.id_producto')
            ->where('id_especificacion', '=', $id_especificacion)
            ->where('estado_producto_especificacion', '=', 'activo')
            ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'productos.id_producto')
            ->where('id_imagen_producto', '!=', null)
            ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
            ->where('modelos.estado_modelo', '=', 'activo')
            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
            ->where('tipo_productos.id_tipo_producto', '=', $id_tipo_producto)
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
            ->where('marcas.estado_marca', '=', 'activo')
            ->groupBy(['productos.id_producto'])
            ->orderBy('productos.id_producto', 'desc')
            ->select('productos.id_producto', 'productos.precio_base', 'productos.nombre_producto', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca', 'imagen_productos.nombre_imagen')
            ->distinct()
            ->paginate(8);

        // dd($producto_bodegas);

        $nombre_tipo_producto = DB::table('tipo_productos')
            ->where('id_tipo_producto', '=', $id_tipo_producto)
            ->first();

        $nombre_especificacion = DB::table('especificaciones')
            ->where('id_especificacion', '=', $id_especificacion)
            ->first();


        // dd($id_tipo_producto,$id_especificacion,$producto_bodegas);

        return view('ecommerce.categoria_producto', compact('producto_bodegas', 'nombre_especificacion', 'nombre_tipo_producto'));
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::::::::::::::::::::::::::::::::::::::::::::::::MARCAS:::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function marca($id_marca)
    {

        if (is_numeric($id_marca)) {

            $producto_bodegas = DB::table('producto_bodegas')
                ->where('producto_bodegas.id_bodega', '=', 0)
                ->where('producto_bodegas.cantidad', '>', 0)
                ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
                ->where('productos.estado_producto', '=', 'activo')
                ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'productos.id_producto')
                ->where('id_imagen_producto', '!=', null)
                ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
                ->where('modelos.estado_modelo', '=', 'activo')
                ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
                ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
                ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
                ->where('marcas.id_marca', '=', $id_marca)
                ->where('marcas.estado_marca', '=', 'activo')
                ->groupBy(['productos.id_producto'])
                ->orderBy('productos.id_producto', 'desc')
                ->select('productos.id_producto', 'productos.precio_base', 'productos.nombre_producto', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca', 'marcas.id_marca', 'imagen_productos.nombre_imagen')
                ->distinct()
                ->paginate(8);

            $marca = DB::table('marcas')
                ->where('id_marca', '=', $id_marca)
                ->where('estado_marca', '=', 'activo')
                ->first();

            return view('ecommerce.marca', compact('producto_bodegas', 'marca'));
        } else {
            return redirect()->route('ecommerce.error');
        }
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::::::::::::::::::::::::::::::::::::::::::MARCAS TIPO PRODUCTO:::::::::::::::::::::::::::::::::::::::::*/


    public function marca_producto($id_tipo_producto, $id_marca)
    {

        if (is_numeric($id_tipo_producto) && is_numeric($id_marca)) {

            $producto_bodegas = DB::table('producto_bodegas')
                ->where('producto_bodegas.id_bodega', '=', 0)
                ->where('producto_bodegas.cantidad', '>', 0)
                ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
                ->where('productos.estado_producto', '=', 'activo')
                ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'productos.id_producto')
                ->where('id_imagen_producto', '!=', null)
                ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
                ->where('modelos.estado_modelo', '=', 'activo')
                ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
                ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
                ->where('tipo_productos.id_tipo_producto', '=', $id_tipo_producto)
                ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
                ->where('marcas.id_marca', '=', $id_marca)
                ->where('marcas.estado_marca', '=', 'activo')
                ->groupBy(['productos.id_producto'])
                ->orderBy('productos.id_producto', 'desc')
                ->select('productos.id_producto', 'productos.precio_base', 'productos.nombre_producto', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca', 'marcas.id_marca', 'imagen_productos.nombre_imagen')
                ->distinct()
                ->paginate(8);

            $marca = DB::table('marcas')
                ->where('id_marca', '=', $id_marca)
                ->where('estado_marca', '=', 'activo')
                ->first();

            $tipo_producto = DB::table('tipo_productos')
                ->where('id_tipo_producto', '=', $id_tipo_producto)
                ->first();

            return view('ecommerce.marca_producto', compact('producto_bodegas', 'marca', 'tipo_producto'));
        } else {
            return redirect()->route('ecommerce.error');
        }
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::::ARTICULO::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function articulo($id_articulo)
    {

        $access = false;
        if (is_numeric($id_articulo)) {
            $comentarios = DB::table('producto_comentario')
                ->where('id_producto', '=', $id_articulo)                
                ->where('estado', '=', 1)->get();

            $articulo = DB::table('producto_bodegas')
                ->where('producto_bodegas.id_producto', '=', $id_articulo)
                ->where('producto_bodegas.id_bodega', '=', 0)
                ->where('producto_bodegas.cantidad', '>', 0)
                ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
                ->where('productos.estado_producto', '=', 'activo')
                ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
                ->where('modelos.estado_modelo', '=', 'activo')
                ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
                ->where('marcas.estado_marca', '=', 'activo')
                ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
                ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
                ->first();

            $articulo->imagenes = DB::table('imagen_productos')
                ->where('id_producto', '=', $id_articulo)
                ->get();

            $articulo->especificaciones = DB::table('producto_especificaciones')
                ->where('id_producto', '=', $articulo->id_producto)
                ->leftJoin('especificaciones', 'especificaciones.id_especificacion', '=', 'producto_especificaciones.id_especificacion')
                ->where('estado_especificacion', '=', "activo")
                ->leftJoin('clasificaciones', 'clasificaciones.id_clasificacion', '=', 'especificaciones.id_clasificacion')
                ->where('estado_especificacion', '=', "activo")
                ->select('especificaciones.nombre_especificacion', 'especificaciones.id_especificacion', 'clasificaciones.nombre_clasificacion')
                ->get();

            global $modelo;
            $modelo = $articulo->id_modelo;
            global $marca;
            $marca = $articulo->id_marca;

            $producto_bodegas = DB::table('producto_bodegas')
                ->where('producto_bodegas.id_bodega', '=', 0)
                ->where('producto_bodegas.id_producto', '!=', $articulo->id_producto)
                ->where('producto_bodegas.cantidad', '>', 0)
                ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
                ->where('productos.estado_producto', '=', 'activo')
                ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'productos.id_producto')
                ->where('id_imagen_producto', '!=', null)
                ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
                ->where('modelos.estado_modelo', '=', 'activo')
                ->where(function ($query) {
                    $query->where('modelos.id_modelo', '=', $GLOBALS['modelo'])
                        ->orWhere('modelos.id_marca', '=', $GLOBALS['marca']);
                })
                ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
                ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
                ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
                ->where('marcas.estado_marca', '=', 'activo')
                ->groupBy(['productos.id_producto'])
                ->select('productos.id_producto', 'productos.precio_base', 'productos.nombre_producto', 'modelos.id_modelo', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca', 'marcas.id_marca', 'imagen_productos.nombre_imagen')
                ->orderBy('productos.id_producto', 'desc')
                ->distinct()
                ->paginate(8);

            $colores = DB::table('productos')
                ->where('productos.id_producto', '!=', $id_articulo)
                ->where('productos.id_modelo', '=', $articulo->id_modelo)
                ->leftJoin('producto_bodegas', 'producto_bodegas.id_producto', '=', 'productos.id_producto')
                ->where('producto_bodegas.id_bodega', '=', 0)
                ->where('producto_bodegas.cantidad', '>', 0)
                ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'productos.id_producto')
                ->where('id_imagen_producto', '!=', null)
                ->leftJoin('producto_especificaciones', 'producto_especificaciones.id_producto', '=', 'productos.id_producto')
                ->where('estado_producto_especificacion', '=', "activo")
                ->leftJoin('especificaciones', 'especificaciones.id_especificacion', '=', 'producto_especificaciones.id_especificacion')
                ->where('estado_especificacion', '=', "activo")
                ->leftJoin('clasificaciones', 'clasificaciones.id_clasificacion', '=', 'especificaciones.id_clasificacion')
                ->where('estado_especificacion', '=', "activo")
                ->where('nombre_clasificacion', '=', 'color')
                ->select('nombre_especificacion', 'productos.id_producto')
                ->distinct()
                ->get();

            return view('ecommerce.articulo', compact('producto_bodegas', 'articulo', 'colores', 'comentarios'));
        } else {

            return redirect()->route('ecommerce.error');
        }
    }


    //:::::::VISTA DE CATEGORIAS DONDE MUESTRO LOS TIPOS DE PRODUCTOS QUE TIENEN CON SUB LISTA DE TIPO_PRODUCTO->MARCAS


    //:::::::VISTA DE CATEGORIAS PERO DEBE BUSCAR ESOS ARTICULOS QUE TIENEN ESAS ESPECIFICACIONES(COLOR,GENERO)


    public function monturas()
    {
        /*$monturas = DB::table('modelos')
        ->where('modelos.id_tipo_producto','=',4)
        ->where('modelos.estado_modelo','=','activo')
        ->leftJoin('productos','productos.id_modelo','=','modelos.id_modelo')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('producto_bodegas','producto_bodegas.id_producto','=','productos.id_producto')
        ->where('producto_bodegas.id_bodega','=',0)
        ->where('producto_bodegas.cantidad','>',0)
        //->select('')
        ->get();*/
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::PAGAR 2? REVISAR::::::::::::::::::::::::::::::::::::::::::::*/


    public function pagar()
    {

        $producto_bodegas = DB::table('producto_bodegas')
            ->where('producto_bodegas.id_bodega', '=', 0)
            ->where('producto_bodegas.cantidad', '>', 0)
            ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->where('productos.estado_producto', '=', 'activo')
            ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
            ->where('modelos.estado_modelo', '=', 'activo')
            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
            ->where('marcas.estado_marca', '=', 'activo')
            ->orderBy('productos.id_producto', 'desc')
            ->select('productos.id_producto', 'productos.precio_base', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca')
            ->get();

        $tipo_productos = DB::table('tipo_productos')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->get();

        $especificaciones = DB::table('producto_especificaciones')
            ->leftJoin('especificaciones', 'especificaciones.id_especificacion', '=', 'producto_especificaciones.id_especificacion')
            ->select('producto_especificaciones.id_producto', 'especificaciones.nombre_especificacion')
            ->get();

        $imagenes = DB::table('imagen_productos')
            ->select('id_producto', 'nombre_imagen')
            ->get();

        $tipos_productos_ecommerce = [];
        $i = 0;
        foreach ($tipo_productos as $tipo_producto) {
            $band = false;
            foreach ($producto_bodegas as $producto_bodega) {
                if (($tipo_producto->id_tipo_producto == $producto_bodega->id_tipo_producto) && ($band == false) && ($i < 5)) {
                    $vector = [
                        'id_tipo_producto' => $tipo_producto->id_tipo_producto,
                        'nombre_tipo_producto' => $tipo_producto->nombre_tipo_producto
                    ];
                    $tipos_productos_ecommerce[$i] = $vector;
                    $i = $i + 1;
                    $band = true;
                }

                $esp = "";
                foreach ($especificaciones as $especificacion) {
                    if ($especificacion->id_producto == $producto_bodega->id_producto) {
                        $esp = $esp . " " . $especificacion->nombre_especificacion;
                    }
                }
                $producto_bodega->especificaciones = $esp;

                $imagen_producto = "default.png";
                $bandImg = false;
                foreach ($imagenes as $imagen) {
                    if (($imagen->id_producto == $producto_bodega->id_producto) && ($bandImg == false)) {
                        $imagen_producto = $imagen->nombre_imagen;
                        $bandImg = true;
                    }
                }
                $producto_bodega->imagen = $imagen_producto;
            }
        }
        $band = count($tipo_productos);
        $tipos_productos_ecommerce = json_encode($tipos_productos_ecommerce);
        $tipos_productos_ecommerce = json_decode($tipos_productos_ecommerce);

        $tipos_identificacion = DB::table('tipos_identificacion')
            ->where('estado_tipo_identificacion', '=', 'activo')
            ->get();

        $sucursales = DB::table('sucursals')
            ->where('estado_sucursal', '=', 'activo')
            ->where('id_sucursal', '!=', 0)
            ->get();

        $currencies = DB::table('currencies')->get();
        $paymentPlatforms = DB::table('payment_platforms')
            ->where('name', '=', 'payu')->get();
        //dd($paymentPlatforms);
        return view('ecommerce.pagar', compact('currencies', 'paymentPlatforms', 'producto_bodegas', 'tipos_productos_ecommerce', 'band') + ['tipos_identificacion' => $tipos_identificacion, 'sucursales' => $sucursales]);
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::REVISAR FUNCIONAMIENTO::::::::::::::::::::::::::::::::::::::::::*/


    public function error()
    {

        $producto_bodegas = DB::table('producto_bodegas')
            ->where('producto_bodegas.id_bodega', '=', 0)
            ->where('producto_bodegas.cantidad', '>', 0)
            ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->where('productos.estado_producto', '=', 'activo')
            ->leftJoin('producto_especificaciones', 'producto_especificaciones.id_producto', '=', 'productos.id_producto')
            ->where('estado_producto_especificacion', '=', 'activo')
            ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'productos.id_producto')
            ->where('id_imagen_producto', '!=', null)
            ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
            ->where('modelos.estado_modelo', '=', 'activo')
            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
            ->where('marcas.estado_marca', '=', 'activo')
            ->groupBy(['productos.id_producto'])
            ->orderBy('productos.id_producto', 'desc')
            ->select('productos.id_producto', 'productos.precio_base', 'productos.nombre_producto', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca', 'imagen_productos.nombre_imagen')
            ->distinct()
            ->paginate(16);

        return view('ecommerce.error', compact('producto_bodegas'));
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::PAGAR PAYU::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function pagarPayu(Request $request)
    {

        $producto_bodegas = DB::table('producto_bodegas')
            ->where('producto_bodegas.id_bodega', '=', 0)
            ->where('producto_bodegas.cantidad', '>', 0)
            ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->where('productos.estado_producto', '=', 'activo')
            ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
            ->where('modelos.estado_modelo', '=', 'activo')
            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
            ->where('marcas.estado_marca', '=', 'activo')
            ->orderBy('productos.id_producto', 'desc')
            ->select('productos.id_producto', 'productos.precio_base', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca', 'producto_bodegas.cantidad')
            ->get();


        $monto = 0;
        $productos2 = $request->productos;
        $productos = json_decode($request->productos);
        $existe = true;
        foreach ($productos as $producto) { //recorro los productos del carrito con sus cantidades

            $prod = DB::table('productos') //obtengo las cantidades existentes
                ->where('id_producto', '=', $producto->id)
                ->first();

            $encontrado = false;
            foreach ($producto_bodegas as $producto_bodega) { //busco si cada uno de los que seleccionó, pertenece al ecommerce

                if (($producto_bodega->id_producto == $prod->id_producto) && ($encontrado == false)) { //si pertenece entonces si es valido
                    $encontrado = true;
                    if ($producto_bodega->cantidad >= $producto->cantidad) { //si es valido, pregunto si hay la cantidad existente
                        $monto = $monto + ($prod->precio_base * $producto->cantidad); //aumento el monto
                    } else { //si no hay cantidad, no es valido
                        $existe = false;
                    }
                }
            }
            if ($encontrado == false) {
                $existe = false;
            }
        }

        $key = DB::table('procesadores')
            ->where('id_procesador', '=', 1)
            ->first();

        $letraAleatoria = chr(rand(ord("a"), ord("z")));
        $letraAleatoria2 = chr(rand(ord("a"), ord("z")));
        $letraAleatoria3 = chr(rand(ord("a"), ord("z")));
        $letraAleatoria4 = chr(rand(ord("a"), ord("z")));
        $letraAleatoria5 = chr(rand(ord("a"), ord("z")));
        $letraAleatoria6 = chr(rand(ord("a"), ord("z")));
        $dates = getdate();
        $fecha = "REF" . $dates["year"] . $letraAleatoria . $dates["mon"] . $letraAleatoria2 . $dates["mday"] . $letraAleatoria3 . $dates["hours"] . $letraAleatoria4 . $dates["minutes"] . $letraAleatoria5 . $dates["seconds"] . $letraAleatoria6;
        $charEmail = substr($request->email, 2, 2);
        $route = route('compra');
        $route2 = route('respuesta');
        $referencia = $fecha . $charEmail;
        $firma = md5($key->apiKey . "~" . $key->merchantId . "~" . $referencia . "~" . $monto . "~COP");

        //guardar productos del carrito
        foreach ($productos as $producto) {
            DB::table('productos_vendidos')->insert(
                ['referencia' => $referencia, 'id_producto' => $producto->id, 'cantidad' => $producto->cantidad, 'nombre' => $producto->nombre, 'imagen' => $producto->imagen, 'precio' => $producto->precio]
            );
        };

        $form = "<form method='post' action='https://checkout.payulatam.com/ppp-web-gateway-payu/'>
                    <input name='merchantId'    type='hidden'  value='$key->merchantId'>
                    <input name='accountId'     type='hidden'  value='$key->accountId'>
                    <input name='extra1'        type='hidden'  value='$request->nombre'>
                    <input name='extra2'        type='hidden'  value='$request->telefono'>
                    <input name='extra3'        type='hidden'  value='$request->direccion'>
                    <input name='description'   type='hidden'  value='$request->descripcion'>
                    <input name='referenceCode' type='hidden'  value='$referencia'>
                    <input name='amount'        type='hidden'  value='$monto'>
                    <input name='tax'           type='hidden'  value='0'>
                    <input name='taxReturnBase' type='hidden'  value='0'>
                    <input name='currency'      type='hidden'  value='COP'>
                    <input name='signature'     type='hidden'  value='$firma'>
                    <input name='test'          type='hidden'  value='1'>
                    <input name='buyerEmail'    type='hidden'  value='$request->email'>
                    <input name='responseUrl'   type='hidden'  value=$route>
                    <input name='confirmationUrl'    type='hidden'  value=$route2>
                    <input name='Submit' id='$fecha' type='submit'  value='Pagar con PayU' style='display:none'>
                </form>
                <div id='espera-transaccion'> <div class='loader' style='overflow:hidden;margin-bottom:10px;position:relative'></div>VERIFICANDO INFORMACIÓN<br>Pronto serás redirigido a PayU Colombia. </div>
                ";

        $datos = ["f" => $form, "id" => $fecha, "cant" => $monto, "existe" => $existe];
        $datos = json_encode($datos);

        return $datos;
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::::::::::::::::::::::::::::::::::::::::::::HOME CLIENTE:::::::::::::::::::::::::::::::::::::::::::::::*/

    public function cliente()
    {

        $producto_bodegas = DB::table('producto_bodegas')
            ->where('producto_bodegas.id_bodega', '=', 0)
            ->where('producto_bodegas.cantidad', '>', 0)
            ->leftJoin('productos', 'productos.id_producto', '=', 'producto_bodegas.id_producto')
            ->where('productos.estado_producto', '=', 'activo')
            ->leftJoin('producto_especificaciones', 'producto_especificaciones.id_producto', '=', 'productos.id_producto')
            ->where('estado_producto_especificacion', '=', 'activo')
            ->leftJoin('imagen_productos', 'imagen_productos.id_producto', '=', 'productos.id_producto')
            ->where('id_imagen_producto', '!=', null)
            ->leftJoin('modelos', 'modelos.id_modelo', '=', 'productos.id_modelo')
            ->where('modelos.estado_modelo', '=', 'activo')
            ->leftJoin('tipo_productos', 'tipo_productos.id_tipo_producto', '=', 'modelos.id_tipo_producto')
            ->where('tipo_productos.estado_tipo_producto', '=', 'activo')
            ->leftJoin('marcas', 'marcas.id_marca', '=', 'modelos.id_marca')
            ->where('marcas.estado_marca', '=', 'activo')
            ->groupBy(['productos.id_producto'])
            ->orderBy('productos.id_producto', 'desc')
            ->select('productos.id_producto', 'productos.precio_base', 'productos.nombre_producto', 'modelos.nombre_modelo', 'modelos.id_tipo_producto', 'tipo_productos.nombre_tipo_producto', 'marcas.nombre_marca', 'imagen_productos.nombre_imagen')
            ->distinct()
            ->paginate(16);

        $usuario = DB::table('users')
            ->where('id', '=', Auth::id())
            ->first();

        $compras = DB::table('pruebapago')
            ->where('id_user', '=', Auth::id())
            ->orderBy('id_prueba', 'desc')
            ->get();

        return view('ecommerce.cliente', compact('usuario', 'compras', 'producto_bodegas'));
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function promociones($id)
    {

        dd("promociones", $id);

        return view('home');
    }

    public function cliente_modificar(Request $request)
    {
        // dd($request);

        $cliente = DB::table('users')
            ->where('id', '=', $request->id_cliente)
            ->first();

        if (is_numeric($request->id_cliente) && ($cliente != null)) {
            $verificarIdentificacion = DB::table('users')
                ->where('identificacion', '=', $request->identificacion_cliente)
                ->where('id_tipo_identificacion', '=', $request->tipo_identificacion_cliente)
                ->first();

            $verificarEmail = DB::table('users')
                ->where('email', '=', $request->correo_cliente)
                ->first();

            if ($verificarIdentificacion != null || $verificarEmail != null) {

                //CASO 1 IDENTIFICACION

                if ($verificarIdentificacion != null && $verificarEmail == null) {

                    if ($cliente->id == $verificarIdentificacion->id) {

                        DB::table('users')
                            ->where('id', $request->id_cliente)
                            ->update([
                                'name' => $request->nombre_cliente,
                                'email' => $request->correo_cliente,
                                'apellido' => $request->apellido_cliente,
                                'telefono' => $request->telefono_cliente,
                                'id_tipo_identificacion' => $request->tipo_identificacion_cliente,
                                'identificacion' => $request->identificacion_cliente,
                                'direccion' => $request->direccion_cliente
                            ]);
                        $estatus = "actualizado";
                    } else {
                        //dd("NO puede guardar, esta identificacion es de otro");
                        $estatus = "erroractualizar";
                    }
                } //FIN CASO 1

                //CASO 2 EMAIL
                if ($verificarEmail != null && $verificarIdentificacion == null) {

                    if ($cliente->id == $verificarEmail->id) {
                        //dd("Es el mismo Email de usuario a editar, SI puede");
                        DB::table('users')
                            ->where('id', $request->id_cliente)
                            ->update([
                                'name' => $request->nombre_cliente,
                                'email' => $request->correo_cliente,
                                'apellido' => $request->apellido_cliente,
                                'telefono' => $request->telefono_cliente,
                                'id_tipo_identificacion' => $request->tipo_identificacion_cliente,
                                'identificacion' => $request->identificacion_cliente,
                                'direccion' => $request->direccion_cliente
                            ]);


                        $estatus = "actualizado";
                    } else {
                        //dd("NO puede guardar, este Email es de otro");
                        $estatus = "erroractualizar";
                    }
                } //FIN CASO 2


                //CASO 3 IDENTIFICACION Y EMAIL
                if ($verificarIdentificacion != null && $verificarEmail != null) {

                    if ($cliente->id == $verificarIdentificacion->id && $cliente->id == $verificarEmail->id) {
                        //dd("Es el mismo Email e Identificacion de usuario a editar, SI puede");
                        DB::table('users')
                            ->where('id', $request->id_cliente)
                            ->update([
                                'name' => $request->nombre_cliente,
                                'email' => $request->correo_cliente,
                                'apellido' => $request->apellido_cliente,
                                'telefono' => $request->telefono_cliente,
                                'id_tipo_identificacion' => $request->tipo_identificacion_cliente,
                                'identificacion' => $request->identificacion_cliente,
                                'direccion' => $request->direccion_cliente
                            ]);

                        $estatus = "actualizado";
                    } else {
                        //dd("NO puede guardar, esta identificacion o correo es de otro");
                        $estatus = "erroractualizar";
                    }
                } //FIN CASO 3

            } else {
                //dd("No existe, puede guardar normalmente");
                DB::table('users')
                    ->where('id', $request->id_cliente)
                    ->update([
                        'name' => $request->nombre_cliente,
                        'email' => $request->correo_cliente,
                        'apellido' => $request->apellido_cliente,
                        'telefono' => $request->telefono_cliente,
                        'id_tipo_identificacion' => $request->tipo_identificacion_cliente,
                        'identificacion' => $request->identificacion_cliente,
                        'direccion' => $request->direccion_cliente
                    ]);




                $estatus = "actualizado";
            }
        } else {
            //dd("Error el usuario no existe");
            $estatus = "error";
        }
        //dd($estatus);
        return redirect()->route('ecommerce.cliente_perfil', ['estatus' => $estatus]);

        return view('home');
    }
    public function cliente_perfil(Request $request)
    {
        if (isset($request->estatus)) {
            $estatus = $request->estatus;
        } else {
            $estatus = "";
        }
        // dd(Auth::id());
        $cliente = DB::table('users')
            ->where('id', '=', Auth::id())
            ->where('estado_usuario', '=', 'activo')
            ->leftjoin('tipos_identificacion', 'tipos_identificacion.id_tipo_identificacion', '=', 'users.id_tipo_identificacion')
            ->first();

        if (is_numeric(Auth::id()) && ($cliente != null)) {

            $tipos_identificacion = DB::table('tipos_identificacion')
                ->where('estado_tipo_identificacion', '=', 'activo')
                ->get();

            return view('ecommerce.perfil_cliente', compact('cliente', 'tipos_identificacion', 'estatus'));
        } else {

            $estatus = "erroractualizar";
            return redirect()->route('home', ['estatus' => $estatus]);
        }
    }

    public function productos_compras(Request $request)
    {
        $productos = DB::table('productos_vendidos')->where('referencia', '=', $request->ver)->get();
        return view('ecommerce.productos_vendidos', compact('productos'));
    }

    public function articulo_comentario(Request $request)
    {

        DB::table('producto_comentario')->insert(
            ['id_producto' => $request->id_producto, 'nombre' => $request->nombre, 'comentario' => $request->comentario]
        );
        return redirect()->back()->with('success', 'Comentario en espera de revision');  
    }
}
