<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use File;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuariosController extends Controller
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
/*:::::::::::::::::::::::::::::::::::::::::::PERFIL USUARIO::::::::::::::::::::::::::::::::::::::::::::::*/


    public function perfil(Request $request){
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
        
        $usuario = DB::table('users')
        ->where('id','=',Auth::id())
        ->first();

        if(isset($usuario)){

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

                $usuarios_sucursales = DB::table('usuarios_sucursales')
                ->where('usuarios_sucursales.id_usuario','=',$id_user)
                ->where('usuarios_sucursales.estado_usuario_sucursal','=','activo')
                ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
                ->where('sucursals.id_sucursal','!=',0)
                ->where('sucursals.estado_sucursal','=','activo')
                ->select('usuarios_sucursales.id_sucursal','sucursals.nombre_sucursal','sucursals.nombre_imagen_sucursal')
                ->orderBy('usuarios_sucursales.prioridad','asc')
                ->get();

                $usuario->sucursales = $usuarios_sucursales;

            }else{
            
                $sucursal_principal = DB::table('usuarios_sucursales')
                ->where('id_usuario','=',Auth::id())
                ->where('estado_usuario_sucursal','=','activo')
                ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
                ->where('sucursals.estado_sucursal','=','activo')
                ->orderBy('prioridad','asc')
                ->first();
             //   dd(DB::table('usuarios_sucursales')->get());
                $sucursales = DB::table('sucursals')
                ->where('id_sucursal','!=',0)
                ->where('id_sucursal','!=',$sucursal_principal->id_sucursal)
                ->where('estado_sucursal','=','activo')
                ->get();
                
                $sucursales_final = [];
                array_push($sucursales_final,$sucursal_principal);
                
                foreach($sucursales as $sucursal){
                    array_push($sucursales_final,$sucursal);
                }
                
                $usuario->sucursales = $sucursales_final;
            }

            $usuario->ventas = DB::table('ventas')
            ->where('id_usuario_venta','=',Auth::id())
            ->leftJoin('sucursals','sucursals.id_sucursal','=','ventas.id_sucursal')
            ->leftJoin('clientes','clientes.id_cliente','=','ventas.id_cliente')
            ->orderBy('ventas.fecha_venta','desc')
            ->get();

        }else{
            return redirect()->route('home');
        }

        return view('usuarios.perfil',compact('usuario','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::BUSCAR SUCURSAL::::::::::::::::::::::::::::::::::::::::::::::*/


    public function contrasena(Request $request){//AJAX

    	if (Hash::check($request->actual_c, Auth::user()->password)){
	    	DB::table('users')
            ->where('id','=',Auth::id())
            ->update([
                'password' => Hash::make($request->nueva_c)
            ]);
            $estado = "actualizado";
	    }else{
	    	$estado = "error";
	    }

        return ($estado);
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::BUSCAR SUCURSAL::::::::::::::::::::::::::::::::::::::::::::::*/


    public function sucursal(Request $request){
        
        $prioridad = DB::table('usuarios_sucursales')
        ->where('id_usuario','=',Auth::id())
        ->where('estado_usuario_sucursal','=','activo')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
        ->where('sucursals.estado_sucursal','=','activo')
        ->orderBy('prioridad','asc')
        ->first();

        $prioridad_colocar = DB::table('usuarios_sucursales')
        ->where('id_usuario','=',Auth::id())
        ->where('usuarios_sucursales.id_sucursal','=',$request->id_sucursal_p)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
        ->where('sucursals.estado_sucursal','=','activo')
        ->orderBy('prioridad','asc')
        ->first();

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

        if($todas == true){
            if($prioridad_colocar == null){

                $usuario_prioridad_sucursal = DB::table('usuarios_sucursales')
                ->where('id_usuario','=',Auth::id())
                ->where('estado_usuario_sucursal','=','activo')
                ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
                ->where('sucursals.estado_sucursal','=','activo')
                ->orderBy('prioridad','desc')
                ->first();

                $prioridad_nueva = $usuario_prioridad_sucursal->prioridad + 1;
                DB::table('usuarios_sucursales')->insert(
                    ['id_usuario' => Auth::id(), 'id_sucursal' => $request->id_sucursal_p, 'estado_usuario_sucursal' => 'activo', 'prioridad' => $prioridad_nueva]
                );

                $prioridad_colocar = DB::table('usuarios_sucursales')
                ->where('id_usuario','=',Auth::id())
                ->where('usuarios_sucursales.id_sucursal','=',$request->id_sucursal_p)
                ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
                ->where('sucursals.estado_sucursal','=','activo')
                ->orderBy('prioridad','asc')
                ->first();
            }
        }

        if (isset($prioridad_colocar) && isset($prioridad)) {
            
            DB::table('usuarios_sucursales')
            ->where('id_usuario','=',Auth::id())
            ->where('id_sucursal','=',$prioridad->id_sucursal)
            ->where('estado_usuario_sucursal','=','activo')
            ->update([
                'prioridad' => $prioridad_colocar->prioridad
            ]);

            DB::table('usuarios_sucursales')
            ->where('id_usuario','=',Auth::id())
            ->where('id_sucursal','=',$request->id_sucursal_p)
            ->where('estado_usuario_sucursal','=','activo')
            ->update([
                'prioridad' => $prioridad->prioridad
            ]);
            $estatus = "exito";
        }else{
            $estatus = "error";
        }

        return redirect()->route('usuarios.perfil',['estatus' => $estatus]);

    }


    public function firma(Request $request){

        $nombre_imagen = 'f-' . Auth::id() . 'f-' . $request->imagen_firma->getClientOriginalName();
        $request->imagen_firma->move('public/imagenes/sistema/f',$nombre_imagen);

        $estatus = "errorFirma";

        if (isset($request->imagen_firma)) {
            DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'imagen_firma' => $nombre_imagen
            ]);
            $estatus = "updatedFirma";
        }

        return redirect()->route('usuarios.perfil',['estatus' => $estatus]);
    }

    public function imagen(Request $request){

        $estatus = "errorImagen";
        if (isset($request->imagen_usuario)) {

            $date = Carbon::now();
            $nombre_imagen = 'u-' . $date->toDateString() . '-' . Auth::id() . "." . $request->file('imagen_usuario')->extension();

            $imagen = DB::table('users')
            ->where('id', Auth::id())
            ->first();

            if (isset($imagen)) {
                File::delete('public/imagenes/sistema/users/'.$imagen->nombre_imagen_user);
            }

            $request->imagen_usuario->move('public/imagenes/sistema/users',$nombre_imagen);
            DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'nombre_imagen_user' => $nombre_imagen
            ]);
            $estatus = "exitoImagen";
        }

        return redirect()->route('usuarios.perfil',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::BUSCAR  USUARIOS::::::::::::::::::::::::::::::::::::::::::::::*/


    public function buscar(Request $request){

        $buscar = $request->buscar;
        /*https://www.youtube.com/watch?v=oBk6tD5_5ME*///VALUE
        $users = DB::table('users')
        ->where('name','like','%'.$buscar.'%')
        ->orWhere('apellido','like','%'.$buscar.'%')
        ->leftJoin('model_has_roles','model_has_roles.model_id','=','users.id')
        ->orderBy('name', 'asc')
        ->get();

        $user_aux = [];
        foreach ($users as $user) {
            if($user->role_id == 2 || $user->role_id == 5){
                array_push($user_aux, $user);
            }
        }
        $users = $user_aux;
        $data = [];
        foreach ($users as $user) {
            $aux = [
                "nombre" => $user->name,
                "apellido" => $user->apellido,
                "id_usuario" => $user->id,
                "rol" => $user->role_id
            ];
            array_push($data, $aux);
        }
        return $data;
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::BUSCAR VENDEDOR:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function buscarVendedor(Request $request){

        global $usuarios_sucursales;
        $usuarios_sucursales = DB::table('usuarios_sucursales')
        ->where('usuarios_sucursales.id_usuario','=',Auth::id())
        ->where('usuarios_sucursales.estado_usuario_sucursal','=','activo')
        ->select('usuarios_sucursales.id_sucursal')
        ->pluck('id_sucursal');

        global $buscar;
        $buscar = $request->buscar;

        $users = DB::table('users')
        ->where(function ($query) {
            $query->where('name','like','%'.$GLOBALS['buscar'].'%')
                  ->orWhere('apellido','like','%'.$GLOBALS['buscar'].'%');
        })
        ->leftJoin('usuarios_sucursales','usuarios_sucursales.id_usuario','=','users.id')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
        ->where('estado_usuario_sucursal','=','activo')
        ->where(function ($query) {
            $query->whereIn('usuarios_sucursales.id_sucursal',$GLOBALS['usuarios_sucursales']);
        })
        ->leftJoin('model_has_roles','model_has_roles.model_id','=','users.id')
        ->where('model_has_roles.role_id','!=',1)
        ->where('model_has_roles.role_id','!=',8)
        ->leftJoin('role_has_permissions','role_has_permissions.role_id','=','model_has_roles.role_id')
        ->where('role_has_permissions.permission_id','=',120)
        ->select('users.id','users.name','users.apellido')
        ->distinct()
        ->orderBy('name', 'asc')
        ->get();

        return $users;
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::VERIFICAR USUARIO AUTORIZACION CAMBIO PRECIO::::::::::::::::::::::::::::::::*/

    
    public function verificar_cambio_precio(Request $request){

        $administrador = DB::table('users')
        ->where('id','=',$request->id_usuario_cotizacion)
        ->first();

        if (Hash::check($request->contrasena, $administrador->password)){
            return "SI"; 
        } else {
            return "NO";
        }
        
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::VERIFICAR USUARIO AUTORIZACION CAMBIO PRECIO::::::::::::::::::::::::::::::::*/

    
    public function verificar_cambio_vendedor(Request $request){

        $vendedor = DB::table('users')
        ->where('id','=',$request->id_usuario_vendedor)
        ->first();

        if (Hash::check($request->contrasena, $vendedor->password)){
            $vendedor->respuesta = "SI";
            return json_encode($vendedor); 
        } else {
            $vendedor->respuesta = "NO";
            return json_encode($vendedor); 
        }
        
    }
}
