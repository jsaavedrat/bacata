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

class RolesController extends Controller
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
        ->orderBy('modulos.nombre_modulo')
        ->get();

        $permisos_modulos = DB::table('permisos_modulos')
        ->leftJoin('permissions','id','=','permisos_modulos.id_permiso')
        ->get();

        // dd($modulos);

        // app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return view('roles.crear',compact('mensaje','modulos','permisos_modulos'));
	}

/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR:::::::::::::::::::::::::::::::::::::::::::::::*/

    public function guardar(Request $request){

        $existe = DB::table('roles')
        ->where('name','=',$request->nombre_rol)
        ->pluck('name');

        if($existe=="[]"){
                $mensaje="exito";
                $rol = Role::create(['name' => $request->nombre_rol]);
                $permisos=json_decode($request->vector_permisos[0]);
                foreach($permisos as $permiso){
                    DB::table('role_has_permissions')->insert(
                        ['role_id' => $rol->id, 'permission_id' => $permiso]
                    );
                }
        }else{
                $mensaje="error";
        }

        $modulos = DB::table('modulos')
        ->where('estado_modulo','=','activo')
        ->get();

        $permisos_modulos = DB::table('permisos_modulos')
        ->leftJoin('permissions','id','=','permisos_modulos.id_permiso')
        ->get();

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // return view('roles.crear',compact('mensaje','modulos','permisos_modulos'));
        return redirect()->route('roles.crear',['mensaje' => $mensaje]);

    }


/*:::::::::::::::::::::::::::::::::::::::::::LISTA::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function lista(){

        $roles = DB::table('roles')
        ->select('id','name')
        ->get();

        $role_has_permissions = DB::table('role_has_permissions')
        ->select('role_id')
        ->get();

        $roles_usuarios = DB::table('model_has_roles')
        ->select('role_id')
        ->get();

        foreach ($roles as $rol) {
        	$c = 0;
        	foreach ($role_has_permissions as $rol_permiso) {
        		if($rol->id == $rol_permiso->role_id){
        			$c = $c + 1;
        		}
        	}
        	$rol->cantidad_permisos = $c;
        	$cc = 0;
        	foreach ($roles_usuarios as $rol_usuario) {
        		if($rol->id == $rol_usuario->role_id){
        			$cc = $cc + 1;
        		}
        	}
        	$rol->cantidad_usuarios = $cc;
        }


        // dd($roles);

        return view('roles.lista',compact('roles'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::EDITAR ROL::::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        // dd($request);
        $rol = DB::table('roles')
        ->where('id','=',$request->editar)
        ->first();
        if(is_numeric($request->editar)&&($rol != null)) {

            $modulos = DB::table('modulos')
            ->where('estado_modulo','=','activo')
            ->orderBy('modulos.nombre_modulo')
            ->get();

            $permisos_modulos = DB::table('permisos_modulos')
            ->leftJoin('permissions','id','=','permisos_modulos.id_permiso')
            ->get();

            $permisos_rol = DB::table('role_has_permissions')
            ->where('role_id','=',$request->editar)
            ->get();

            $modulos_permisos = DB::table('role_has_permissions')//modulos que tenga permisos asociados para que se de click y muestra en la vista
            ->where('role_id','=',$request->editar)
            ->leftJoin('permissions','id','=','role_has_permissions.permission_id')
            ->leftJoin('permisos_modulos','permisos_modulos.id_permiso','=','permissions.id')
            ->leftJoin('modulos','modulos.id_modulo','=','permisos_modulos.id_modulo')
            ->select('modulos.nombre_modulo')
            ->distinct()
            ->orderBy('modulos.nombre_modulo','asc')
            ->get();

            $estatus = "";

            return view('roles.modificar',compact('modulos','permisos_modulos','permisos_rol','estatus','rol','modulos_permisos'));
            
        }else{

            $estatus="erroractualizar";
            return redirect()->route('roles.lista');
        }
        
    }


/*:::::::::::::::::::::::::::::::::::::::::::MODIFICAR:::::::::::::::::::::::::::::::::::::::::::::::*/

    public function modificar(Request $request){

        // dd($request);

        //verificar si ya existe el nombre de rol

        DB::table('roles')
        ->where('id', $request->id_rol)
        ->update(['name' => $request->nombre_rol]);
        
        $permisos=json_decode($request->vector_permisos[0]);
        // dd($permisos);
        foreach($permisos as $permiso){

            $rol_permiso = DB::table('role_has_permissions')
            ->where('role_id','=',$request->id_rol)
            ->where('permission_id','=',$permiso)
            ->first();
            
            if(!isset($rol_permiso)){
                DB::table('role_has_permissions')->insert(
                    ['role_id' => $request->id_rol, 'permission_id' => $permiso]
                );
            }
        }
        
        // dd("stop");
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles.lista');

    }


}
