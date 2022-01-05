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

class ProveedoresController extends Controller
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
/*:::::::::::::::::::::::::::::::::::::::::::CREAR PROVEEDOR::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        return view('proveedores.crear',compact('estatus'));
	}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR PROVEEDOR:::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        $existe = DB::table('proveedores')
        ->where('nombre_proveedor','=',$request->nombre_proveedor)
        ->pluck('nombre_proveedor');

        if($existe=="[]"){
                $estatus="exito";
                
                $hora = date('H-i-s');
                $nombre_imagen = 'prov-oa-' . $hora . $request->imagen_proveedor->getClientOriginalName();
                $request->imagen_proveedor->move('public/imagenes/sistema/proveedores',$nombre_imagen);

                $id_proveedor = DB::table('proveedores')->insertGetId(
                    ['nombre_proveedor' => $request->nombre_proveedor, 'estado_proveedor' => $request->estado_proveedor, 'direccion_proveedor' => $request->direccion_proveedor, 'telefono_proveedor' => $request->telefono_proveedor, 'nombre_imagen_proveedor' => $nombre_imagen]
                );

                $date = Carbon::now();$id_user = Auth::id();
                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 13, 'accion' => 'crear', 'id_elemento' => $id_proveedor, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);
        }else{
                $estatus="error";
        }

        return redirect()->route('proveedores.crear',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE PROVEEDORES::::::::::::::::::::::::::::::::::::::::::::*/


   public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $proveedores = DB::table('proveedores')
        ->where('estado_proveedor','=','activo')
        ->get();

        return view('proveedores.lista',compact('proveedores','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR PROVEEDOR A EDITAR::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $proveedor = DB::table('proveedores')
        ->where('id_proveedor','=',$request->editar)
        ->where('estado_proveedor','=','activo')
        ->first();
        if(is_numeric($request->editar)&&($proveedor != null)) {

            return view('proveedores.modificar',compact('proveedor'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('proveedores.lista',['estatus' => $estatus]);
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::MODIFICAR PROVEEDOR::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){
        $proveedor = DB::table('proveedores')
        ->where('id_proveedor','=',$request->id_proveedor)
        ->where('estado_proveedor','=','activo')
        ->first();

        if(is_numeric($request->id_proveedor)&&($proveedor != null)) {
            $verificar = DB::table('proveedores')
            ->where('nombre_proveedor','=',$request->nombre_proveedor)
            ->where('estado_proveedor','=','activo')
            ->first();
            if($verificar != null){
                if($proveedor->nombre_proveedor == $request->nombre_proveedor){
                    if($request->imagen_proveedor!=null){
                        $hora = date('H-i-s');
                        $nombre_imagen = 'prov-oa-' . $hora . $request->imagen_proveedor->getClientOriginalName();
                        $request->imagen_proveedor->move('public/imagenes/sistema/proveedores',$nombre_imagen);
                        DB::table('proveedores')
                        ->where('id_proveedor', $request->id_proveedor)
                        ->update([
                            'nombre_proveedor' => $request->nombre_proveedor,
                            'direccion_proveedor' => $request->direccion_proveedor,
                            'telefono_proveedor' => $request->telefono_proveedor,
                            'nombre_imagen_proveedor' => $nombre_imagen
                        ]);
                        File::delete('public/imagenes/sistema/proveedores/'.$proveedor->nombre_imagen_proveedor);
                    }else{
                        DB::table('proveedores')
                        ->where('id_proveedor', $request->id_proveedor)
                        ->update([
                            'nombre_proveedor' => $request->nombre_proveedor,
                            'direccion_proveedor' => $request->direccion_proveedor,
                            'telefono_proveedor' => $request->telefono_proveedor
                        ]);
                    }
                    $estatus="actualizado";
                }else{
                    $estatus="erroractualizar";
                }
            }else{
                if($request->imagen_proveedor!=null){
                    $hora = date('H-i-s');
                    $nombre_imagen = 'prov-oa-' . $hora . $request->imagen_proveedor->getClientOriginalName();
                    $request->imagen_proveedor->move('public/imagenes/sistema/proveedores',$nombre_imagen);
                    DB::table('proveedores')
                    ->where('id_proveedor', $request->id_proveedor)
                    ->update([
                        'nombre_proveedor' => $request->nombre_proveedor,
                        'direccion_proveedor' => $request->direccion_proveedor,
                        'telefono_proveedor' => $request->telefono_proveedor,
                        'nombre_imagen_proveedor' => $nombre_imagen
                    ]);
                    File::delete('public/imagenes/sistema/proveedores/'.$proveedor->nombre_imagen_proveedor);
                }else{
                    DB::table('proveedores')
                    ->where('id_proveedor', $request->id_proveedor)
                    ->update([
                        'nombre_proveedor' => $request->nombre_proveedor,
                        'direccion_proveedor' => $request->direccion_proveedor,
                        'telefono_proveedor' => $request->telefono_proveedor
                    ]);
                }
                $estatus="actualizado";
            }
        }else{
            $estatus="erroractualizar";
        }    

        return redirect()->route('proveedores.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::INACTIVAR PROVEEDOR::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $existe = DB::table('proveedores')
        ->where('id_proveedor','=',$request->eliminar)
        ->where('estado_proveedor','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('proveedores')
            ->where('id_proveedor', $request->eliminar)
            ->update(['estado_proveedor' => 'inactivo']);
        }else{
            $estatus="error";
        }

       return redirect()->route('proveedores.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('proveedores.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR::::::::::::::::::::::::::::::::::::::::::::::::*/
}