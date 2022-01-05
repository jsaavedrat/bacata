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
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class Pagina_WebController extends Controller
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

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::SERVICIOS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::CREAR SERVICIO::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear_servicios(Request $request){
		
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

  
        return view('pagina_web.servicios.crear',compact('estatus'));
	}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR SERVICIO:::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_servicios(Request $request){
                
        $id_servicio = DB::table('servicios')->insertGetId([
            'nombre_servicio' => $request->nombre_servicio,
            'descripcion_servicio' => $request->descripcion_servicio,
            'estado_servicio' => 'activo'
        ]);

        $nombre_imagen = 'servicio-' . $id_servicio . "." . $request->file('imagen_servicio')->extension();
        $request->imagen_servicio->move('public/imagenes/pagina/servicios',$nombre_imagen);

        DB::table('servicios')
        ->where('id_servicio', $id_servicio)
        ->update([
            'imagen_servicio' => $nombre_imagen
        ]);

        $estatus="exito";
        
        return redirect()->route('pagina.servicios.lista',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE SERVICIOS:::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista_servicios(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $servicios = DB::table('servicios')
        ->where('estado_servicio','=','activo')
        ->get();

        return view('pagina_web.servicios.lista',compact('servicios','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR SERVICIO A EDITAR:::::::::::::::::::::::::::::::::::::::::::*/


    public function editar_servicio(Request $request){

        $servicio = DB::table('servicios')
        ->where('id_servicio','=',$request->editar)
        ->where('estado_servicio','=','activo')
        ->first();

        if(is_numeric($request->editar)&&($servicio != null)) {

            $estatus="";
            return view('pagina_web.servicios.modificar',compact('servicio','estatus'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('pagina.servicios.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR SERVICIO::::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar_servicio(Request $request){

        if(isset($request->imagen_servicio)){

            $nombre_imagen = 'servicio-' . $request->id_servicio . "." . $request->file('imagen_servicio')->extension();
            $request->imagen_servicio->move('public/imagenes/pagina/servicios',$nombre_imagen);
        
            DB::table('servicios')
            ->where('id_servicio', $request->id_servicio)
            ->update([
                'nombre_servicio' => $request->nombre_servicio,
                'descripcion_servicio' => $request->descripcion_servicio,
                'imagen_servicio' => $nombre_imagen
            ]);
        } else {

            DB::table('servicios')
            ->where('id_servicio', $request->id_servicio)
            ->update([
                'nombre_servicio' => $request->nombre_servicio,
                'descripcion_servicio' => $request->descripcion_servicio
            ]);
        }
        $estatus="actualizado";
        
        return redirect()->route('pagina.servicios.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::INACTIVAR SERVICIO::::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar_servicio(Request $request){

        $existe = DB::table('servicios')
        ->where('id_servicio','=',$request->eliminar)
        ->where('estado_servicio','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            DB::table('servicios')
            ->where('id_servicio', $request->eliminar)
            ->update(['estado_servicio' => 'inactivo']);

            $estatus="eliminado";
        }else{
            $estatus="error";
        }

        return redirect()->route('pagina.servicios.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::LOGOS DE MARCAS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::CREAR LOGO MARCA::::::::::::::::::::::::::::::::::::::::::::*/


public function crear_logos_marcas(Request $request){
        
    if   (isset($request->estatus)){ $estatus = $request->estatus;}
    else {$estatus="";}


    return view('pagina_web.logos_marcas.crear',compact('estatus'));
}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR LOGO MARCA:::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_logos_marcas(Request $request){
                
        $id_logo_marca = DB::table('logos_marcas_slick')->insertGetId([
            'nombre_marca' => $request->nombre_marca,
            'pagina_web' => $request->pagina_web,
            'estado_logo_marca' => 'activo'
        ]);

        $nombre_imagen = 'logo-marca-' . $id_logo_marca . "." . $request->file('imagen_logo_marca')->extension();
        $request->imagen_logo_marca->move('public/imagenes/pagina/logos_marcas',$nombre_imagen);

        DB::table('logos_marcas_slick')
        ->where('id_logo_marca', $id_logo_marca)
        ->update([
            'imagen_logo_marca' => $nombre_imagen
        ]);

        $estatus="exito";
        
        return redirect()->route('pagina.logos_marcas.lista',['estatus' => $estatus]);
    }


    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::::::::::::::::::::::::::::::::::::::::::LISTA DE LOGOS MARCAS:::::::::::::::::::::::::::::::::::::::::::*/
    
    
        public function lista_logos_marcas(Request $request){
    
            if   (isset($request->estatus)){ $estatus = $request->estatus;}
            else {$estatus="";}
    
            $logos_marcas = DB::table('logos_marcas_slick')
            ->where('estado_logo_marca','=','activo')
            ->get();
    
            return view('pagina_web.logos_marcas.lista',compact('logos_marcas','estatus'));
        }


        /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
        /*::::::::::::::::::::::::::::::::::::::::BUSCAR LOGO MARCA A EDITAR:::::::::::::::::::::::::::::::::::::::::*/
        
        
            public function editar_logos_marcas(Request $request){
        
                $logo_marca = DB::table('logos_marcas_slick')
                ->where('id_logo_marca','=',$request->editar)
                ->where('estado_logo_marca','=','activo')
                ->first();
        
                if(is_numeric($request->editar)&&($logo_marca != null)) {
        
                    $estatus="";
                    return view('pagina_web.logos_marcas.modificar',compact('logo_marca','estatus'));
        
                }else{
        
                    $estatus="erroractualizar";
                    return redirect()->route('pagina.logos_marcas.lista',['estatus' => $estatus]);
                }        
            }


            /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
            /*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR LOGO MARCA::::::::::::::::::::::::::::::::::::::::::::*/
            
            
                public function modificar_logos_marcas(Request $request){
            
                    if(isset($request->imagen_logo_marca)){
            
                        $nombre_imagen = 'logo-marca-' . $request->id_logo_marca . "." . $request->file('imagen_logo_marca')->extension();
                        $request->imagen_logo_marca->move('public/imagenes/pagina/logos_marcas',$nombre_imagen);
                    
                        DB::table('logos_marcas_slick')
                        ->where('id_logo_marca', $request->id_logo_marca)
                        ->update([
                            'nombre_marca' => $request->nombre_marca,
                            'pagina_web' => $request->pagina_web,
                            'imagen_logo_marca' => $nombre_imagen
                        ]);
                    } else {
            
                        DB::table('logos_marcas_slick')
                        ->where('id_logo_marca', $request->id_logo_marca)
                        ->update([
                            'nombre_marca' => $request->nombre_marca,
                            'pagina_web' => $request->pagina_web,
                        ]);
                    }
                    $estatus="actualizado";
                    
                    return redirect()->route('pagina.logos_marcas.lista',['estatus' => $estatus]);
                }
            
            
            /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
            /*:::::::::::::::::::::::::::::::::::::::::::INACTIVAR LOGO MARCA::::::::::::::::::::::::::::::::::::::::::::*/
            
            
                public function inactivar_logos_marcas(Request $request){
            
                    $existe = DB::table('logos_marcas_slick')
                    ->where('id_logo_marca','=',$request->eliminar)
                    ->where('estado_logo_marca','=','activo')
                    ->first();
            
                    if(is_numeric($request->eliminar)&&($existe != null)) {
            
                        DB::table('logos_marcas_slick')
                        ->where('id_logo_marca', $request->eliminar)
                        ->update(['estado_logo_marca' => 'inactivo']);
            
                        $estatus="eliminado";
                    }else{
                        $estatus="error";
                    }
            
                    return redirect()->route('pagina.logos_marcas.lista',['estatus' => $estatus]);
                }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::CREAR INFO::::::::::::::::::::::::::::::::::::::::::::*/
    
    
    public function crear_infos(Request $request){
            
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
    
    
        return view('pagina_web.infos.crear',compact('estatus'));
    }


    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::GUARDAR INFO:::::::::::::::::::::::::::::::::::::::::::*/
    
    
        public function guardar_infos(Request $request){
                    
            $id_info = DB::table('infos_slick')->insertGetId([
                'nombre_info' => $request->nombre_info,
                'detalle_info' => $request->detalle_info,
                'pagina_web' => $request->pagina_web,
                'estado_info' => 'activo'
            ]);
    
            $nombre_imagen = 'info-' . $id_info . "." . $request->file('imagen_info')->extension();
            $request->imagen_info->move('public/imagenes/pagina/infos',$nombre_imagen);
    
            DB::table('infos_slick')
            ->where('id_info', $id_info)
            ->update([
                'imagen_info' => $nombre_imagen
            ]);
    
            $estatus="exito";
            
            return redirect()->route('pagina.infos.lista',['estatus' => $estatus]);
        }


    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::::::::::::::::::::::::::::::::::::::::::LISTA DE INFOS:::::::::::::::::::::::::::::::::::::::::::*/
    
    
        public function lista_infos(Request $request){
    
            if   (isset($request->estatus)){ $estatus = $request->estatus;}
            else {$estatus="";}
    
            $infos = DB::table('infos_slick')
            ->where('estado_info','=','activo')
            ->get();
    
            return view('pagina_web.infos.lista',compact('infos','estatus'));
        }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR INFO A EDITAR:::::::::::::::::::::::::::::::::::::::::*/


    public function editar_infos(Request $request){

        $info = DB::table('infos_slick')
        ->where('id_info','=',$request->editar)
        ->where('estado_info','=','activo')
        ->first();

        if(is_numeric($request->editar)&&($info != null)) {

            $estatus="";
            return view('pagina_web.infos.modificar',compact('info','estatus'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('pagina.infos.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR INFO::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar_infos(Request $request){

        if(isset($request->imagen_info)){

            $nombre_imagen = 'info-' . $request->id_info . "." . $request->file('imagen_info')->extension();
            $request->imagen_info->move('public/imagenes/pagina/infos',$nombre_imagen);
        
            DB::table('infos_slick')
            ->where('id_info', $request->id_info)
            ->update([
                'nombre_info' => $request->nombre_info,
                'detalle_info' => $request->detalle_info,
                'pagina_web' => $request->pagina_web,
                'imagen_info' => $nombre_imagen
            ]);
        } else {

            DB::table('infos_slick')
            ->where('id_info', $request->id_info)
            ->update([
                'nombre_info' => $request->nombre_info,
                'detalle_info' => $request->detalle_info,
                'pagina_web' => $request->pagina_web,
            ]);
        }
        $estatus="actualizado";
        
        return redirect()->route('pagina.infos.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::INACTIVAR INFO::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar_infos(Request $request){

        $existe = DB::table('infos_slick')
        ->where('id_info','=',$request->eliminar)
        ->where('estado_info','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            DB::table('infos_slick')
            ->where('id_info', $request->eliminar)
            ->update(['estado_info' => 'inactivo']);

            $estatus="eliminado";
        }else{
            $estatus="error";
        }

        return redirect()->route('pagina.infos.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::MEMBRESIAS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::CREAR MEMBRESIA:::::::::::::::::::::::::::::::::::::::::::::*/


    public function crear_membresias(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $producto_membresias = DB::table('productos_especiales')
        ->where('nombre_producto','=','membresias')
        ->where('estado_producto_especial','=','activo')
        ->first();

        if (isset($producto_membresias)) {
            $clasificacion_membresias = DB::table('clasificacion_tipo_productos')
            ->where('id_tipo_producto','=',$producto_membresias->id_tipo_producto_especial)
            ->where('estado_clasificacion_tipo_producto','=','activo')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
            ->orderBy('clasificaciones.id_clasificacion')
            ->get();

            $producto_membresias->clasificaciones = $clasificacion_membresias;

            $clasificacion_membresias = DB::table('clasificacion_tipo_productos')
            ->where('id_tipo_producto','=',$producto_membresias->id_tipo_producto_especial)
            ->where('estado_clasificacion_tipo_producto','=','activo')
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
            ->orderBy('clasificaciones.id_clasificacion')
            ->pluck('clasificaciones.id_clasificacion');

            $producto_membresias->ids_clasificaciones = json_encode($clasificacion_membresias);
            return view('pagina_web.membresias.crear',compact('producto_membresias','estatus'));
        } else {
            $estatus = "errorMembresia";
            return redirect()->route('home',['estatus' => $estatus]);
        }

    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR MEMBRESIA::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_membresias(Request $request){

        //busco el tipo de producto membresia
        $producto_membresias = DB::table('productos_especiales')
        ->where('nombre_producto','=','membresias')
        ->where('estado_producto_especial','=','activo')
        ->first();

        //busco si ya existe ese modelo o "membresia"
        $existe = DB::table('modelos')
        ->where('estado_modelo','=','activo')
        ->where('id_tipo_producto','=',$producto_membresias->id_tipo_producto_especial)
        ->where('nombre_modelo','=',$request->modelo_membresia)
        ->leftJoin('productos','productos.id_modelo','=','modelos.id_modelo')
        ->where('estado_producto','=','activo')
        ->first();

        if(isset($existe)){
            $estatus = "yaExiste";
        }else{//si NO existe, la guardo
            //verifico si existe la marca Membresía de el tipo de producto membresia
            $marca = DB::table('marcas')
            ->where('nombre_marca','=','Membresía')
            ->where('estado_marca','=','activo')
            ->leftJoin('tipo_producto_marcas','tipo_producto_marcas.id_marca','=','marcas.id_marca')
            ->where('tipo_producto_marcas.id_tipo_producto','=',$producto_membresias->id_tipo_producto_especial)
            ->first();

            // dd("marca:",$marca);

            if(isset($marca)){//si existe obtengo el id
                $id_marca = $marca->id_marca;
            }else{//sino, lo creo
                $id_marca = DB::table('marcas')->insertGetId([
                    'nombre_marca' => 'Membresía',
                    'estado_marca' => 'activo'
                ]);

                DB::table('tipo_producto_marcas')->insert([
                    'id_tipo_producto' => $producto_membresias->id_tipo_producto_especial,
                    'id_marca' => $id_marca,
                    'estado_tipo_producto_marca' => 'activo'
                ]);
            }

            //creo el modelo
            $id_modelo = DB::table('modelos')->insertGetId([
                'nombre_modelo' => $request->modelo_membresia,
                'id_tipo_producto' => $producto_membresias->id_tipo_producto_especial,
                'id_marca' => $id_marca,
                'estado_modelo' => 'activo'
            ]);

            //creo el producto de ese modelo creado
            $id_producto = DB::table('productos')->insertGetId([
                'id_modelo' => $id_modelo,
                'precio_base' => $request->precio_membresia,
                'declara_iva' => 'si',
                'estado_producto' => 'activo'
            ]);

            $nombre = "Membresias - Membresía " . $request->modelo_membresia;
            $codigo = "MEMBRESIA";
            foreach (json_decode($request->ids_clasificaciones) as $id_clasificacion) {
                
                $existe_especificacion = DB::table('especificaciones')
                ->where('id_clasificacion','=',$id_clasificacion)
                ->where('estado_especificacion','=',"activo")
                ->where('nombre_especificacion','=',$request["clasificacion".$id_clasificacion])
                ->first();

                if(isset($existe_especificacion)){
                    $id_especificacion = $existe_especificacion->id_especificacion;
                }else{
                    $id_especificacion = DB::table('especificaciones')->insertGetId([
                        'id_clasificacion' => $id_clasificacion,
                        'nombre_especificacion' => $request["clasificacion".$id_clasificacion],
                        'estado_especificacion' => 'activo'
                    ]);
                }

                DB::table('producto_especificaciones')->insert([
                    'id_producto' => $id_producto,
                    'id_especificacion' => $id_especificacion
                ]);
                $codigo = $codigo."-".$id_especificacion;
                $nombre = $nombre.", ".$request["clasificacion".$id_clasificacion];
            }

            DB::table('productos')
            ->where('id_producto', $id_producto)
            ->update([
                'code128' => str_pad($id_modelo, 5, "0", STR_PAD_LEFT) . str_pad($id_producto, 7, "0", STR_PAD_LEFT),
                'codigo_producto' => $codigo,
                'nombre_producto' => $nombre
            ]);

            $estatus = "exito";

            $date = Carbon::now();
            $hora = date("H-i-s");
            $nombre_imagen = 'producto-' . $date->toDateString() . '-' . $hora . '-' . $id_producto . "." . $request->file('imagen_membresia')->extension();
            $request->imagen_membresia->move('public/imagenes/sistema/productos',$nombre_imagen);
            DB::table('imagen_productos')->insert([
                'id_producto' => $id_producto,
                'nombre_imagen' => $nombre_imagen
            ]);

        }
        return redirect()->route('pagina.membresias.crear',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE MEMBRESIAS::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista_membresias(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $producto_membresias = DB::table('productos_especiales')
        ->where('nombre_producto','=','membresias')
        ->where('estado_producto_especial','=','activo')
        ->first();

        if(isset($producto_membresias->id_tipo_producto_especial)){

            $membresias = DB::table('modelos')
            ->where('id_tipo_producto','=',$producto_membresias->id_tipo_producto_especial)
            ->where('estado_modelo','=','activo')
            ->leftJoin('productos','productos.id_modelo','=','modelos.id_modelo')
            ->where('productos.estado_producto','=','activo')
            ->leftJoin('imagen_productos','imagen_productos.id_producto','=','productos.id_producto')
            ->select('modelos.nombre_modelo','productos.id_producto','productos.precio_base','imagen_productos.nombre_imagen')
            ->orderBy('productos.precio_base')
            ->get();

            foreach($membresias as $membresia){
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$membresia->id_producto)
                ->where('producto_especificaciones.estado_producto_especificacion','=','activo')
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
                ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion','clasificaciones.id_clasificacion')
                ->orderBy('clasificaciones.id_clasificacion')
                ->get();
                $membresia->especificaciones = $especificaciones;
            }
        }

        return view('pagina_web.membresias.lista',compact('producto_membresias','membresias','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::BUSCAR MEMBRESIA A EDITAR:::::::::::::::::::::::::::::::::::::::::*/


    public function editar_membresias(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $producto_membresias = DB::table('productos_especiales')
        ->where('nombre_producto','=','membresias')
        ->where('estado_producto_especial','=','activo')
        ->first();

        if (isset($producto_membresias)) {

            $membresia = DB::table('productos')
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->where('id_producto','=',$request->editar)
            ->where('estado_producto','=','activo')
            ->first();

            if(is_numeric($request->editar)&&($membresia != null)) {

                $membresia_especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$membresia->id_producto)
                ->where('producto_especificaciones.estado_producto_especificacion','=','activo')
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
                ->select('especificaciones.id_especificacion','especificaciones.nombre_especificacion','clasificaciones.id_clasificacion','clasificaciones.nombre_clasificacion','producto_especificaciones.id_producto_especificaciones','producto_especificaciones.estado_producto_especificacion','producto_especificaciones.id_producto')
                ->get();
                $membresia->especificaciones = $membresia_especificaciones;

                $clasificacion_membresias = DB::table('clasificacion_tipo_productos')
                ->where('id_tipo_producto','=',$producto_membresias->id_tipo_producto_especial)
                ->where('estado_clasificacion_tipo_producto','=','activo')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
                ->orderBy('clasificaciones.id_clasificacion')
                ->get();
                $producto_membresias->clasificaciones = $clasificacion_membresias;

                $clasificacion_membresias = DB::table('clasificacion_tipo_productos')
                ->where('id_tipo_producto','=',$producto_membresias->id_tipo_producto_especial)
                ->where('estado_clasificacion_tipo_producto','=','activo')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
                ->orderBy('clasificaciones.id_clasificacion')
                ->pluck('clasificaciones.id_clasificacion');

                $producto_membresias->ids_clasificaciones = json_encode($clasificacion_membresias);
                return view('pagina_web.membresias.modificar',compact('producto_membresias','membresia','estatus'));
            }else{
                $estatus = "errorMembresia";
                return redirect()->route('home',['estatus' => $estatus]);
            }
        } else {
            $estatus = "errorMembresia";
            return redirect()->route('home',['estatus' => $estatus]);
        }    
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR MEMBRESIA:::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar_membresias(Request $request){

        //busco el tipo de producto membresia
        $producto_membresias = DB::table('productos_especiales')
        ->where('nombre_producto','=','membresias')
        ->where('estado_producto_especial','=','activo')
        ->first();

        //busco si ya existe ese modelo o "membresia"
        $existe = DB::table('modelos')
        ->where('estado_modelo','=','activo')
        ->where('id_tipo_producto','=',$producto_membresias->id_tipo_producto_especial)
        ->where('nombre_modelo','=',$request->modelo_membresia)
        ->where('id_modelo','!=',$request->id_modelo_anterior)
        ->first();

        // dd($request->imagen_membresia);

        if(isset($existe)){
            $estatus = "yaExiste";
        }else{//si NO existe, la modifico
            //verifico si existe la marca Membresía de el tipo de producto membresia
            $marca = DB::table('marcas')
            ->where('nombre_marca','=','Membresía')
            ->where('estado_marca','=','activo')
            ->leftJoin('tipo_producto_marcas','tipo_producto_marcas.id_marca','=','marcas.id_marca')
            ->where('tipo_producto_marcas.id_tipo_producto','=',$producto_membresias->id_tipo_producto_especial)
            ->first();

            if(isset($marca)){//si existe obtengo el id
                $id_marca = $marca->id_marca;
            }else{//sino, lo creo
                $id_marca = DB::table('marcas')->insertGetId([
                    'nombre_marca' => 'Membresía',
                    'estado_marca' => 'activo'
                ]);

                DB::table('tipo_producto_marcas')->insert([
                    'id_tipo_producto' => $producto_membresias->id_tipo_producto_especial,
                    'id_marca' => $id_marca,
                    'estado_tipo_producto_marca' => 'activo'
                ]);
            }

            //obtengo el modelo
            $id_modelo = $request->id_modelo_anterior;

            //obtengo el producto
            $id_producto = $request->id_producto_anterior;

            $nombre = "Membresias - Membresía " . $request->modelo_membresia;
            $codigo = "MEMBRESIA";

            foreach (json_decode($request->ids_clasificaciones) as $id_clasificacion) {
                
                //buscar si existe esa especificacion
                $existe_especificacion = DB::table('especificaciones')
                ->where('id_clasificacion','=',$id_clasificacion)
                ->where('estado_especificacion','=',"activo")
                ->where('nombre_especificacion','=',$request["clasificacion".$id_clasificacion])
                ->first();

                //si existe la obtengo
                if(isset($existe_especificacion)){
                    $id_especificacion = $existe_especificacion->id_especificacion;
                }else{// sino, la creo
                    $id_especificacion = DB::table('especificaciones')->insertGetId([
                        'id_clasificacion' => $id_clasificacion,
                        'nombre_especificacion' => $request["clasificacion".$id_clasificacion],
                        'estado_especificacion' => 'activo'
                    ]);
                }

                //buscar si ese producto(membresia) tiene esa especificacion de esa clasificacion
                $tiene_especificacion = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$id_producto)
                ->where('producto_especificaciones.id_especificacion','=',$id_especificacion)
                ->where('estado_producto_especificacion','=','activo')
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->where('especificaciones.id_clasificacion','=',$id_clasificacion)
                ->where('especificaciones.estado_especificacion','=','activo')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
                ->where('clasificaciones.estado_clasificacion','=','activo')
                ->first();

                if(!isset($tiene_especificacion)){//si no la tiene
                    //inactivo la que tiene anterior de esa clasificacion
                    DB::table('producto_especificaciones')
                    ->where('id_producto','=',$id_producto)
                    ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                    ->where('especificaciones.id_clasificacion','=',$id_clasificacion)
                    ->update([
                        'producto_especificaciones.estado_producto_especificacion' => 'inactivo'
                    ]);

                    //guardo la nueva
                    DB::table('producto_especificaciones')->insert([
                        'id_producto' => $id_producto,
                        'id_especificacion' => $id_especificacion
                    ]);
                }

                $nombre = $nombre.", ".$request["clasificacion".$id_clasificacion];
            }

            DB::table('productos')
            ->where('id_producto', $id_producto)
            ->update([
                'nombre_producto' => $nombre
            ]);

            if (isset($request->imagen_membresia)) {

                $imagen_membresia = DB::table('imagen_productos')
                ->where('id_producto','=',$id_producto)
                ->first();

                $date = Carbon::now();
                $hora = date("H-i-s");
                $nombre_imagen = 'producto-' . $date->toDateString() . '-' . $hora . '-' . $id_producto . "." . $request->file('imagen_membresia')->extension();
                $request->imagen_membresia->move('public/imagenes/sistema/productos',$nombre_imagen);

                if(isset($imagen_membresia)){

                    if (isset($imagen_membresia->nombre_imagen)) {
                        File::delete('public/imagenes/sistema/productos/'.$imagen_membresia->nombre_imagen);
                    }

                    DB::table('imagen_productos')
                    ->where('id_imagen_producto','=',$imagen_membresia->id_imagen_producto)
                    ->update([
                        'nombre_imagen' => $nombre_imagen
                    ]);
                }else{
                    DB::table('imagen_productos')->insert([
                        'id_producto' => $id_producto,
                        'nombre_imagen' => $nombre_imagen
                    ]);
                }
            }

            $estatus = "actualizado";
        }
        return redirect()->route('pagina.membresias.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::INACTIVAR MEMBRESÍA:::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar_membresias(Request $request){

        $existe = DB::table('productos')
        ->where('id_producto','=',$request->eliminar)
        ->where('estado_producto','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            DB::table('productos')
            ->where('id_producto', $request->eliminar)
            ->update(['estado_producto' => 'inactivo']);

            $estatus="eliminado";
        }else{
            $estatus="error";
        }

        return redirect()->route('pagina.membresias.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::PROMOCIONES EN PAGINA WEB::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::CREAR PROMOCION PAGINA WEB:::::::::::::::::::::::::::::::::::::*/


    public function crear_promociones_pagina(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
  
        return view('pagina_web.promociones_pagina.crear',compact('estatus'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::GUARDAR PROMOCION PAGINA WEB::::::::::::::::::::::::::::::::::::*/


    public function guardar_promociones_pagina(Request $request){

        // dd($request);
                
        $id_promocion_pagina = DB::table('promociones_pagina')->insertGetId([
            'nombre_promocion_pagina' => $request->nombre_promocion_pagina,
            'resultado_promocion' => $request->resultado_promocion,
            'mostrar_qr' => $request->mostrar_qr,
            'ubicacion_qr' => $request->ubicacion_qr,
            'texto_qr' => $request->texto_qr,
            'color_texto_qr' => $request->color_texto_qr,
            'mostrar_banner' => $request->mostrar_banner,
            'ubicacion_banner' => $request->ubicacion_banner,
            'texto_banner' => $request->texto_banner,
            'texto_banner_2' => $request->texto_banner_2,
            'color_texto_banners' => $request->color_texto_banners,
            'estado_promocion_pagina' => 'activo'
        ]);

        if(isset($request->imagen_promocion_pagina)){

            $nombre_imagen = 'promocion-pagina-' . $id_promocion_pagina . "." . $request->file('imagen_promocion_pagina')->extension();
            $request->imagen_promocion_pagina->move('public/imagenes/pagina/promociones',$nombre_imagen);

            DB::table('promociones_pagina')
            ->where('id_promocion_pagina', $id_promocion_pagina)
            ->update([
                'imagen_promocion_pagina' => $nombre_imagen
            ]);
        }

        $estatus="exito";
        
        return redirect()->route('pagina.promociones_pagina.lista',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::LISTA DE PROMOCIONES PAGINA WEB:::::::::::::::::::::::::::::::::::::*/


    public function lista_promociones_pagina(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $promociones_pagina = DB::table('promociones_pagina')
        ->where('estado_promocion_pagina','=','activo')
        ->get();

        return view('pagina_web.promociones_pagina.lista',compact('promociones_pagina','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::BUSCAR PROMOCION PAGINA WEB A EDITAR:::::::::::::::::::::::::::::::::::*/


    public function editar_promociones_pagina(Request $request){

        $promocion_pagina = DB::table('promociones_pagina')
        ->where('id_promocion_pagina','=',$request->editar)
        ->where('estado_promocion_pagina','=','activo')
        ->first();

        if(is_numeric($request->editar)&&($promocion_pagina != null)) {

            $estatus="";
            return view('pagina_web.promociones_pagina.modificar',compact('promocion_pagina','estatus'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('pagina.promociones_pagina.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::MODIFICAR PROMOCION PAGINA WEB::::::::::::::::::::::::::::::::::::::*/


    public function modificar_promociones_pagina(Request $request){

        if(isset($request->imagen_promocion_pagina)){

            $nombre_imagen = 'promocion-' . $request->id_promocion_pagina . "." . $request->file('imagen_promocion_pagina')->extension();
            $request->imagen_promocion_pagina->move('public/imagenes/pagina/promociones',$nombre_imagen);
        
            DB::table('promociones_pagina')
            ->where('id_promocion_pagina', $request->id_promocion_pagina)
            ->update([
                'nombre_promocion_pagina' => $request->nombre_promocion_pagina,
                'imagen_promocion_pagina' => $nombre_imagen,
                'resultado_promocion' => $request->resultado_promocion,
                'mostrar_qr' => $request->mostrar_qr,
                'ubicacion_qr' => $request->ubicacion_qr,
                'texto_qr' => $request->texto_qr,
                'color_texto_qr' => $request->color_texto_qr,
                'mostrar_banner' => $request->mostrar_banner,
                'ubicacion_banner' => $request->ubicacion_banner,
                'texto_banner' => $request->texto_banner,
                'texto_banner_2' => $request->texto_banner_2,
                'color_texto_banners' => $request->color_texto_banners
            ]);
        }else{

            DB::table('promociones_pagina')
            ->where('id_promocion_pagina', $request->id_promocion_pagina)
            ->update([
                'nombre_promocion_pagina' => $request->nombre_promocion_pagina,
                'resultado_promocion' => $request->resultado_promocion,
                'mostrar_qr' => $request->mostrar_qr,
                'ubicacion_qr' => $request->ubicacion_qr,
                'texto_qr' => $request->texto_qr,
                'color_texto_qr' => $request->color_texto_qr,
                'mostrar_banner' => $request->mostrar_banner,
                'ubicacion_banner' => $request->ubicacion_banner,
                'texto_banner' => $request->texto_banner,
                'texto_banner_2' => $request->texto_banner_2,
                'color_texto_banners' => $request->color_texto_banners
            ]);
        }
        $estatus="actualizado";
        
        return redirect()->route('pagina.promociones_pagina.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::INACTIVAR PROMOCION PAGINA WEB:::::::::::::::::::::::::::::::::::::::*/


    public function inactivar_promociones_pagina(Request $request){

        $existe = DB::table('promociones_pagina')
        ->where('id_promocion_pagina','=',$request->eliminar)
        ->where('estado_promocion_pagina','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            DB::table('promociones_pagina')
            ->where('id_promocion_pagina','=',$request->eliminar)
            ->update(['estado_promocion_pagina' => 'inactivo']);

            $estatus="eliminado";
        }else{
            $estatus="error";
        }

        return redirect()->route('pagina.promociones_pagina.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::CARRUSEL DE IMAGENES::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::AGREGAR IMAGEN CARRUSEL::::::::::::::::::::::::::::::::::::::::*/


    public function crear_carrusel(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
  
        return view('pagina_web.carrusel.crear',compact('estatus'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::GUARDAR IMAGEN CARRUSEL:::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_carrusel(Request $request){
                
        $id_imagen_carrusel = DB::table('imagenes_carrusel')->insertGetId([
            'subtitulo_carrusel' => $request->subtitulo_carrusel,
            'titulo_carrusel' => $request->titulo_carrusel,
            'descripcion_carrusel' => $request->descripcion_carrusel,
            'color_texto_carrusel' => $request->color_texto_carrusel,
            'color_desvanecido_fondo' => $request->color_desvanecido_fondo,
            'orden' => $request->orden,
            'estado_imagen_carrusel' => 'activo'
        ]);

        if(isset($request->imagen_carrusel)){

            $nombre_imagen = 'imagen-carrusel-' . $id_imagen_carrusel . "." . $request->file('imagen_carrusel')->extension();
            $request->imagen_carrusel->move('public/imagenes/pagina/carrusel',$nombre_imagen);

            DB::table('imagenes_carrusel')
            ->where('id_imagen_carrusel', $id_imagen_carrusel)
            ->update([
                'imagen_carrusel' => $nombre_imagen
            ]);
        }

        $estatus="exito";
        
        return redirect()->route('pagina.carrusel.lista',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::LISTA / VISTA PREVIA IMAGENES CARRUSEL::::::::::::::::::::::::::::::::::*/


    public function lista_carrusel(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $imagenes_carrusel = DB::table('imagenes_carrusel')
        ->where('estado_imagen_carrusel','=','activo')
        ->orderBy('orden')
        ->get();

        return view('pagina_web.carrusel.lista',compact('imagenes_carrusel','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::BUSCAR IMAGEN CARRUSEL A EDITAR::::::::::::::::::::::::::::::::::::::*/


    public function editar_carrusel(Request $request){

        $imagen_carrusel = DB::table('imagenes_carrusel')
        ->where('id_imagen_carrusel','=',$request->editar)
        ->where('estado_imagen_carrusel','=','activo')
        ->first();

        if(is_numeric($request->editar)&&($imagen_carrusel != null)) {

            $estatus="";
            return view('pagina_web.carrusel.modificar',compact('imagen_carrusel','estatus'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('pagina.carrusel.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::MODIFICAR IMAGEN CARRUSEL:::::::::::::::::::::::::::::::::::::::::*/


    public function modificar_carrusel(Request $request){

        if(isset($request->imagen_carrusel)){

            $nombre_imagen = 'imagen-carrusel-' . $request->id_imagen_carrusel . "." . $request->file('imagen_carrusel')->extension();
            $request->imagen_carrusel->move('public/imagenes/pagina/carrusel',$nombre_imagen);
        
            DB::table('imagenes_carrusel')
            ->where('id_imagen_carrusel', $request->id_imagen_carrusel)
            ->update([
                'subtitulo_carrusel' => $request->subtitulo_carrusel,
                'imagen_carrusel' => $nombre_imagen,
                'titulo_carrusel' => $request->titulo_carrusel,
                'descripcion_carrusel' => $request->descripcion_carrusel,
                'color_texto_carrusel' => $request->color_texto_carrusel,
                'color_desvanecido_fondo' => $request->color_desvanecido_fondo,
                'orden' => $request->orden,
                'estado_imagen_carrusel' => 'activo'
            ]);
        }else{

            DB::table('imagenes_carrusel')
            ->where('id_imagen_carrusel', $request->id_imagen_carrusel)
            ->update([
                'subtitulo_carrusel' => $request->subtitulo_carrusel,
                'titulo_carrusel' => $request->titulo_carrusel,
                'descripcion_carrusel' => $request->descripcion_carrusel,
                'color_texto_carrusel' => $request->color_texto_carrusel,
                'color_desvanecido_fondo' => $request->color_desvanecido_fondo,
                'orden' => $request->orden,
                'estado_imagen_carrusel' => 'activo'
            ]);
        }
        $estatus="actualizado";
        
        return redirect()->route('pagina.carrusel.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::INACTIVAR IMAGEN CARRUSEL:::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar_carrusel(Request $request){

        $existe = DB::table('imagenes_carrusel')
        ->where('id_imagen_carrusel','=',$request->eliminar)
        ->where('estado_imagen_carrusel','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            DB::table('imagenes_carrusel')
            ->where('id_imagen_carrusel','=',$request->eliminar)
            ->update(['estado_imagen_carrusel' => 'inactivo']);

            $estatus="eliminado";
        }else{
            $estatus="error";
        }

        return redirect()->route('pagina.carrusel.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::REDES SOCIALES:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::LISTA DE REDES SOCIALES:::::::::::::::::::::::::::::::::::::::::*/


    public function lista_redes(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $redes = DB::table('redes_sociales')
        ->get();

        return view('pagina_web.redes_sociales.lista',compact('redes','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR RED SOCIAL A EDITAR:::::::::::::::::::::::::::::::::::::::::*/


    public function editar_redes(Request $request){

        $red = DB::table('redes_sociales')
        ->where('id_red','=',$request->editar)
        ->first();

        if(is_numeric($request->editar)&&($red != null)) {

            $estatus="";
            return view('pagina_web.redes_sociales.modificar',compact('red','estatus'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('pagina.redes.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR RED SOCIAL::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar_redes(Request $request){

        if(isset($request->acceso_red)){
            DB::table('redes_sociales')
            ->where('id_red', $request->id_red)
            ->update([
                'acceso_red' => $request->acceso_red
            ]);
        }
        if(isset($request->texto_extra_red)){
            DB::table('redes_sociales')
            ->where('id_red', $request->id_red)
            ->update([
                'texto_extra_red' => $request->texto_extra_red
            ]);
        }

        $estatus="actualizada";
        
        return redirect()->route('pagina.redes.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::INACTIVAR RED SOCIAL::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar_redes(Request $request){

        $existe = DB::table('redes_sociales')
        ->where('id_red','=',$request->eliminar)
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            if ($existe->estado_red == "activo") {
                DB::table('redes_sociales')
                ->where('id_red', $request->eliminar)
                ->update(['estado_red' => 'inactivo']);
                $estatus="inactivada";
            }else{
                DB::table('redes_sociales')
                ->where('id_red', $request->eliminar)
                ->update(['estado_red' => 'activo']);
                $estatus="activada";
            }
            
        }else{
            $estatus="error";
        }

        return redirect()->route('pagina.redes.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::SECCION KIDS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::CREAR IMAGEN KIDS:::::::::::::::::::::::::::::::::::::::::::*/


    public function crear_imagen_kids(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

  
        return view('pagina_web.kids.crear',compact('estatus'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR IMAGEN KIDS::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_imagen_kids(Request $request){
                
        $id_imagen_kids = DB::table('imagenes_kids')->insertGetId([
            'titulo_imagen_kids' => $request->titulo_imagen_kids,
            'orden' => $request->orden,
            'estado_imagen_kids' => 'activo'
        ]);

        $nombre_imagen = 'imagen-kids-' . $id_imagen_kids . "." . $request->file('nombre_imagen_kids')->extension();
        $request->nombre_imagen_kids->move('public/imagenes/pagina/kids',$nombre_imagen);

        DB::table('imagenes_kids')
        ->where('id_imagen_kids', $id_imagen_kids)
        ->update([
            'nombre_imagen_kids' => $nombre_imagen
        ]);

        $estatus="exito";
        
        return redirect()->route('pagina.kids.lista',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::LISTA DE IMAGENES KIDS::::::::::::::::::::::::::::::::::::::::::*/


    public function lista_imagen_kids(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $imagenes_kids = DB::table('imagenes_kids')
        ->where('estado_imagen_kids','=','activo')
        ->where('id_imagen_kids','!=',0)
        ->orderBy('orden')
        ->get();

        return view('pagina_web.kids.lista',compact('imagenes_kids','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::BUSCAR IMAGEN KIDS EDITAR:::::::::::::::::::::::::::::::::::::::::*/


    public function editar_imagen_kids(Request $request){

        $imagen_kids = DB::table('imagenes_kids')
        ->where('id_imagen_kids','=',$request->editar)
        ->where('estado_imagen_kids','=','activo')
        ->first();

        if(is_numeric($request->editar)&&($imagen_kids != null)) {

            $estatus="";
            return view('pagina_web.kids.modificar',compact('imagen_kids','estatus'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('pagina.kids.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR IMAGEN KIDS:::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar_imagen_kids(Request $request){

        if(isset($request->nombre_imagen_kids)){

            $nombre_imagen = 'imagen-kids-' . $request->id_imagen_kids . "." . $request->file('nombre_imagen_kids')->extension();
            $request->nombre_imagen_kids->move('public/imagenes/pagina/kids',$nombre_imagen);

            DB::table('imagenes_kids')
            ->where('id_imagen_kids', $request->id_imagen_kids)
            ->update([
                'titulo_imagen_kids' => $request->titulo_imagen_kids,
                'nombre_imagen_kids' => $nombre_imagen,
                'orden' => $request->orden
            ]);
        } else {

            DB::table('imagenes_kids')
            ->where('id_imagen_kids', $request->id_imagen_kids)
            ->update([
                'titulo_imagen_kids' => $request->titulo_imagen_kids,
                'orden' => $request->orden
            ]);
        }
        $estatus="actualizado";

        return redirect()->route('pagina.kids.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::INACTIVAR IMAGEN KIDS:::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar_imagen_kids(Request $request){

        $existe = DB::table('imagenes_kids')
        ->where('id_imagen_kids','=',$request->eliminar)
        ->where('estado_imagen_kids','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            DB::table('imagenes_kids')
            ->where('id_imagen_kids', $request->eliminar)
            ->update(['estado_imagen_kids' => 'inactivo']);

            $estatus="eliminado";
        }else{
            $estatus="error";
        }

        return redirect()->route('pagina.kids.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::CAMBIAR FONDO KIDS:::::::::::::::::::::::::::::::::::::::::::*/


    public function crear_imagen_fondo_kids(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $imagen_kids = DB::table('imagenes_kids')
        ->where('id_imagen_kids','=',0)
        ->first();

        return view('pagina_web.kids.fondo.crear',compact('estatus','imagen_kids'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR FONDO KIDS:::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_imagen_fondo_kids(Request $request){

        $imagen_kids = DB::table('imagenes_kids')
        ->where('id_imagen_kids','=',0)
        ->first();
        File::delete('public/imagenes/pagina/kids/fondo/'.$imagen_kids->nombre_imagen_kids);

        $nombre_imagen = 'fondo-kids' . "." . $request->file('nombre_imagen_kids')->extension();
        $request->nombre_imagen_kids->move('public/imagenes/pagina/kids/fondo',$nombre_imagen);

        DB::table('imagenes_kids')
        ->where('id_imagen_kids', 0)
        ->update([
            'nombre_imagen_kids' => $nombre_imagen
        ]);

        $estatus="exito";
        
        return redirect()->route('pagina.kids.fondo.crear',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::SECCION EQUIPO:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::CREAR IMAGEN EQUIPO::::::::::::::::::::::::::::::::::::::::::*/


    public function crear_imagen_equipo(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

  
        return view('pagina_web.equipo.crear',compact('estatus'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::GUARDAR IMAGEN EQUIPO::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_imagen_equipo(Request $request){
                
        $id_imagen_equipo = DB::table('imagenes_equipo')->insertGetId([
            'titulo_imagen_equipo' => $request->titulo_imagen_equipo,
            'orden' => $request->orden,
            'estado_imagen_equipo' => 'activo'
        ]);

        $nombre_imagen = 'imagen-equipo-' . $id_imagen_equipo . "." . $request->file('nombre_imagen_equipo')->extension();
        $request->nombre_imagen_equipo->move('public/imagenes/pagina/equipo',$nombre_imagen);

        DB::table('imagenes_equipo')
        ->where('id_imagen_equipo', $id_imagen_equipo)
        ->update([
            'nombre_imagen_equipo' => $nombre_imagen
        ]);

        $estatus="exito";
        
        return redirect()->route('pagina.equipo.lista',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::LISTA DE IMAGENES EQUIPO::::::::::::::::::::::::::::::::::::::::::*/


    public function lista_imagen_equipo(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $imagenes_equipo = DB::table('imagenes_equipo')
        ->where('estado_imagen_equipo','=','activo')
        ->orderBy('orden')
        ->get();

        return view('pagina_web.equipo.lista',compact('imagenes_equipo','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::BUSCAR IMAGEN EQUIPO EDITAR:::::::::::::::::::::::::::::::::::::::::*/


    public function editar_imagen_equipo(Request $request){

        $imagen_equipo = DB::table('imagenes_equipo')
        ->where('id_imagen_equipo','=',$request->editar)
        ->where('estado_imagen_equipo','=','activo')
        ->first();

        if(is_numeric($request->editar)&&($imagen_equipo != null)) {

            $estatus="";
            return view('pagina_web.equipo.modificar',compact('imagen_equipo','estatus'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('pagina.equipo.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::MODIFICAR IMAGEN EQUIPO::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar_imagen_equipo(Request $request){

        if(isset($request->nombre_imagen_equipo)){

            $nombre_imagen = 'imagen-equipo-' . $request->id_imagen_equipo . "." . $request->file('nombre_imagen_equipo')->extension();
            $request->nombre_imagen_equipo->move('public/imagenes/pagina/equipo',$nombre_imagen);

            DB::table('imagenes_equipo')
            ->where('id_imagen_equipo', $request->id_imagen_equipo)
            ->update([
                'titulo_imagen_equipo' => $request->titulo_imagen_equipo,
                'nombre_imagen_equipo' => $nombre_imagen,
                'orden' => $request->orden
            ]);
        } else {

            DB::table('imagenes_equipo')
            ->where('id_imagen_equipo', $request->id_imagen_equipo)
            ->update([
                'titulo_imagen_equipo' => $request->titulo_imagen_equipo,
                'orden' => $request->orden
            ]);
        }
        $estatus="actualizado";

        return redirect()->route('pagina.equipo.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::INACTIVAR IMAGEN EQUIPO::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar_imagen_equipo(Request $request){

        $existe = DB::table('imagenes_equipo')
        ->where('id_imagen_equipo','=',$request->eliminar)
        ->where('estado_imagen_equipo','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            DB::table('imagenes_equipo')
            ->where('id_imagen_equipo', $request->eliminar)
            ->update(['estado_imagen_equipo' => 'inactivo']);

            $estatus="eliminado";
        }else{
            $estatus="error";
        }

        return redirect()->route('pagina.equipo.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::CONFIGURAR FUENTE:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::CARGAR FUENTE::::::::::::::::::::::::::::::::::::::::::::::*/


    public function cargar_fuente(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        return view('pagina_web.fuentes.crear',compact('estatus'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::GUARDAR FUENTE::::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_fuente(Request $request){

        DB::table('fuentes_pagina')
        ->where('estado_fuente', 'actual')
        ->update([
            'estado_fuente' => 'activo'
        ]);

        $nombre_fuente = $request->file('nombre_archivo')->getClientOriginalName();
        $request->nombre_archivo->move('public/fuentes',$nombre_fuente);

        $clase = str_replace(".", "", $nombre_fuente);
        $clase = str_replace(" ", "_", $clase);

        $id_fuente_pagina = DB::table('fuentes_pagina')->insertGetId([
            'nombre_fuente' => $request->nombre_fuente,
            'nombre_archivo' => $nombre_fuente,
            'clase_fuente' => $clase,
            'estado_fuente' => 'actual'
        ]);

        $estatus="exito";
        
        return redirect()->route('pagina.fuentes.lista',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE FUENTES:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista_fuentes(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $fuentes_pagina = DB::table('fuentes_pagina')
        ->where('estado_fuente','!=','actual')
        ->orderBy('estado_fuente')
        ->get();

        $fuentes_actual = DB::table('fuentes_pagina')
        ->where('estado_fuente','=','actual')
        ->get();

        return view('pagina_web.fuentes.lista',compact('fuentes_pagina','fuentes_actual','estatus'));
    }

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::CAMBIAR FUENTE::::::::::::::::::::::::::::::::::::::::::::::*/


    public function cambiar_fuente($id_fuente){

        DB::table('fuentes_pagina')
        ->where('estado_fuente', 'actual')
        ->update([
            'estado_fuente' => 'activo'
        ]);

        DB::table('fuentes_pagina')
        ->where('id_fuente_pagina', $id_fuente)
        ->update([
            'estado_fuente' => 'actual'
        ]);

        $estatus = "cambiada";

        return redirect()->route('pagina.fuentes.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::CONFIGURAR COLORES:::::::::::::::::::::::::::::::::::::::::::*/


    public function configurar_colores(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $colores = DB::table('colores_pagina')
        ->where('estado_color_pagina','=','activo')
        ->get();

        return view('pagina_web.colores.configurar',compact('estatus','colores'));
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::GUARDAR COLORES:::::::::::::::::::::::::::::::::::::::::::::*/


    public function cambiar_colores(Request $request){

        $colores = DB::table('colores_pagina')
        ->where('estado_color_pagina','=','activo')
        ->get();

        foreach ($colores as $color) {
            DB::table('colores_pagina')
            ->where('id_color_pagina', $color->id_color_pagina)
            ->update([
                'color_pagina' => $request[$color->id_color_pagina]
            ]);
        }

        $estatus="exito";
        
        return redirect()->route('pagina.colores.configurar',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::TEXTOS PAGINA::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::LISTA DE TEXTOS PAGINA::::::::::::::::::::::::::::::::::::::::::*/


    public function lista_textos(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $textos = DB::table('textos_web')
        ->where('estado_texto_web','=','activo')
        ->get();

        return view('pagina_web.textos.lista',compact('textos','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR TEXTO PAGINA A EDITAR:::::::::::::::::::::::::::::::::::::::*/


    public function editar_texto(Request $request){

        $texto = DB::table('textos_web')
        ->where('id_texto_web','=',$request->editar)
        ->first();

        if(is_numeric($request->editar)&&($texto != null)) {

            $estatus="";
            return view('pagina_web.textos.modificar',compact('texto','estatus'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('pagina.textos.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR TEXTO PAGINA::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar_texto(Request $request){

        DB::table('textos_web')
        ->where('id_texto_web', $request->id_texto_web)
        ->update([
            'descripcion_texto_web' => $request->descripcion_texto_web
        ]);

        $estatus = "actualizada";
        
        return redirect()->route('pagina.textos.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR::::::::::::::::::::::::::::::::::::::::::::::::*/
}
