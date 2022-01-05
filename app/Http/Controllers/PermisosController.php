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

class PermisosController extends Controller
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

	public function crear(){
		
        $mensaje="";

        $modulos = DB::table('modulos')
        ->where('estado_modulo','=','activo')
        ->get();

        //dd($modulos);

        return view('permisos.crear',compact('mensaje','modulos'));
	}

/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR:::::::::::::::::::::::::::::::::::::::::::::::*/

    public function guardar(Request $request){

        $existe = DB::table('permissions')
        ->where('name','=',$request->nombre_permiso)
        ->pluck('name');

        $modulos = DB::table('modulos')
        ->where('estado_modulo','=','activo')
        ->get();

        if($existe=="[]"){
                $mensaje="exito";
                $permission = Permission::create(['name' => $request->nombre_permiso]);

                DB::table('permisos_modulos')->insert(
                    ['id_modulo' => $request->modulo_permiso, 'id_permiso' => $permission->id]
                );
        }else{
                $mensaje="error";
        }

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return view('permisos.crear',compact('mensaje','modulos'));
    }

/*:::::::::::::::::::::::::::::::::::::::::::LISTA::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function lista(){

        $permisos_modulos = DB::table('permisos_modulos')
        ->leftJoin('permissions','permissions.id','=','permisos_modulos.id_permiso')
        ->leftJoin('modulos','modulos.id_modulo','=','permisos_modulos.id_modulo')
        ->select('permisos_modulos.id_permiso','permissions.name','modulos.nombre_modulo','modulos.id_modulo')
        ->orderBy('id_modulo')
        ->get();

        return view('permisos.lista',compact('permisos_modulos'));
    }


}
