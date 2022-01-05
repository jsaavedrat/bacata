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

class ModulosController extends Controller
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
/*:::::::::::::::::::::::::::::::::::::::::::CREAR MODULO::::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(Request $request){
		
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
  
        return view('modulos.crear',compact('estatus'));
	}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR MODULO:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        $existe = DB::table('modulos')
        ->where('nombre_modulo','=',$request->nombre_modulo)
        ->where('estado_modulo','=','activo')
        ->pluck('nombre_modulo');

        if($existe=="[]"){
                
            DB::table('modulos')->insert([
                'nombre_modulo' => $request->nombre_modulo,
                'estado_modulo' => $request->estado_modulo
            ]);

            $estatus="exito";
        }else{
            $estatus="error";
        }

        return redirect()->route('modulos.crear',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE MODULOS:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $modulos = DB::table('modulos')
        ->where('estado_modulo','=','activo')
        ->get();

        return view('modulos.lista',compact('modulos','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR MODULO A EDITAR:::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $modulo = DB::table('modulos')
        ->where('id_modulo','=',$request->editar)
        ->where('estado_modulo','=','activo')
        ->first();

        if(is_numeric($request->editar)&&($modulo != null)) {

            $estatus="";
            return view('modulos.modificar',compact('modulo','estatus'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('modulos.lista',['estatus' => $estatus]);
        }        
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::MODIFICAR MODULO:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){

        $modulo = DB::table('modulos')
        ->where('id_modulo','=',$request->id_modulo)
        ->where('estado_modulo','=','activo')
        ->first();

        if(is_numeric($request->id_modulo)&&($modulo != null)) {
            $verificar = DB::table('modulos')
            ->where('nombre_modulo','=',$request->nombre_modulo)
            ->where('estado_modulo','=','activo')
            ->first();
            if($verificar != null){
                if($modulo->nombre_modulo == $request->nombre_modulo){
                    DB::table('modulos')
                    ->where('id_modulo', $request->id_modulo)
                    ->update([
                        'nombre_modulo' => $request->nombre_modulo,
                        'estado_modulo' => $request->estado_modulo
                    ]);
                    $estatus="actualizado";
                }else{
                    $estatus="erroractualizar";
                }
            }else{
                DB::table('modulos')
                ->where('id_modulo', $request->id_modulo)
                ->update([
                    'nombre_modulo' => $request->nombre_modulo,
                    'estado_modulo' => $request->estado_modulo
                ]);
                $estatus="actualizado";
            }
        }else{
            $estatus="erroractualizar";
        }    
 
        return redirect()->route('modulos.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::INACTIVAR MODULO:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $existe = DB::table('modulos')
        ->where('id_modulo','=',$request->eliminar)
        ->where('estado_modulo','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('modulos')
            ->where('id_modulo', $request->eliminar)
            ->update(['estado_modulo' => 'inactivo']);
        }else{
            $estatus="error";
        }

        return redirect()->route('modulos.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/

    public function redirect(){
        return redirect()->route('modulos.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR::::::::::::::::::::::::::::::::::::::::::::::::*/
}
