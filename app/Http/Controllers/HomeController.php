<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
class HomeController extends Controller
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

    public function index(Request $request) {
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        // $resultado = user::find(1)->sucursales[0];

        $id_user = Auth::id();
        //$roles = Auth::user()->getRoleNames();
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
            
            if($modulo->id_modulo == 1){
                $sucursal = DB::table('sucursals')
                ->where('id_sucursal','!=',0)
                ->where('estado_sucursal','=','activo')
                ->count();
                $valores = [
                    'nombre_modulo'=> $modulo->nombre_modulo,
                    'cantidad_modulo'=> $sucursal. " Sucursales",
                    'url_crear' => route("sucursales.crear"),
                    'url_lista' => route("sucursales.lista"),
                    'icono' => 'fa fa-home'
                ];
                array_push($disponibles,$valores);
            }

            if($modulo->id_modulo == 23){
                $ecommerce = DB::table('producto_bodegas')
                ->where('producto_bodegas.id_bodega','=',0)
                ->where('producto_bodegas.cantidad','>',0)
                ->count();
                $valores = [
                    'nombre_modulo'=> $modulo->nombre_modulo,
                    'cantidad_modulo'=> $ecommerce . " Productos",
                    'url_crear' => route("adminecommerce.cargar"),
                    'url_lista' => route("adminecommerce.lista"),
                    'icono' => 'fa fa-globe'
                ];
                array_push($disponibles,$valores);
            }

            if($modulo->id_modulo == 2){
                $empleados = DB::table('users')
                ->where('users.tipo_usuario','=','empleado')
                ->where('users.estado_usuario','=','activo')
                ->count();
                $valores = [
                    'nombre_modulo'=> 'USUARIOS',
                    'cantidad_modulo'=> $empleados . " Empleados Activos",
                    'url_crear' => route("empleados.crear"),
                    'url_lista' => route("empleados.lista"),
                    'icono' => 'fa fa-users'
                ];
                array_push($disponibles,$valores);
            }

            //promociones
            //ventas
            //citas para hoy
            //productos
            //trabajos
            //ordenes
        }


        //dd($disponibles);
  
        //dd($modulos);
        $disponibles = json_encode($disponibles);
        $disponibles = json_decode($disponibles);


        return view('home',compact('disponibles','estatus'));
    }

}
