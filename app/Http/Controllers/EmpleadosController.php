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
use App\Sucursal;
use App\User;
use Illuminate\Support\Facades\Hash;

class EmpleadosController extends Controller
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
/*:::::::::::::::::::::::::::::::::::::::::::CREAR EMPLEADO::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $id_user = Auth::id();
        $roles_usuario = DB::table('model_has_roles')
        ->where('model_type','=','App\User')
        ->where('model_id','=',$id_user)
        ->select('role_id')
        ->get();

        $todas = false;
        foreach ($roles_usuario as $rol) {
            if ($rol->role_id == 1 || $rol->role_id == 2) {
                $todas = true;
            }
        }

        if($todas == false){

            $roles = DB::table('roles')
            ->where('id','!=',1)
            ->where('id','!=',2)
            ->where('name','!=','Punto De Venta General')
            ->get();

            $sucursales = DB::table('usuarios_sucursales')
            ->where('id_usuario','=',$id_user)
            ->where('estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->where('sucursals.estado_sucursal','=','activo')
            ->where('sucursals.id_sucursal','!=',0)
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
            ->get();

        }else{

            $roles = DB::table('roles')
            ->get();

            $sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $tipos_contratos = DB::table('tipos_contratos')
        ->where('estado_tipo_contrato','=','activo')
        ->get();

        $tipos_identificacion = DB::table('tipos_identificacion')
        ->where('estado_tipo_identificacion','=','activo')
        ->get();

        return view('empleados.crear',compact('estatus','roles','sucursales','tipos_contratos','tipos_identificacion'));
	}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR EMPLEADO:::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){
        
        $existeIdentificacion = DB::table('users')
        ->where('identificacion','=',$request->identificacion_empleado)
        ->where('id_tipo_identificacion','=',$request->tipo_identificacion_empleado)
        ->pluck('identificacion');

        $existeEmail = DB::table('users')
        ->where('email','=',$request->correo_empleado)
        ->pluck('email');

        if($existeIdentificacion=="[]" && $existeEmail=="[]"){
                $estatus="exito";

                $empleado = User::create(['name' => $request->nombre_empleado, 'email' => $request->correo_empleado, 'apellido' => $request->apellido_empleado, 'telefono' => $request->telefono_empleado, 'id_tipo_identificacion' => $request->tipo_identificacion_empleado, 'identificacion' => $request->identificacion_empleado, 'direccion' =>$request->direccion_empleado, 'tipo_usuario' => 'empleado', 'estado_usuario' => 'activo', 'password'=> Hash::make($request->identificacion_empleado)]);
                
                $sucursales=json_decode($request->vector_sucursales[0]);

                DB::table('model_has_roles')->insert(
                    ['role_id' => 3,'model_id' => $empleado->id, 'model_type' => 'App\User']
                );

        }else{
                if($existeIdentificacion!="[]"){
                    $estatus="errorIdentificacion";
                }
                if($existeEmail!="[]"){
                    $estatus="errorEmail";
                }

                if($existeEmail!="[]" && $existeIdentificacion!="[]"){
                    $estatus="errorEmailIdentificacion";
                }
        }

        return redirect()->route('empleados.crear',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE EMPLEADOS:::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $roles_usuario = DB::table('model_has_roles')
        ->where('model_type','=','App\User')
        ->where('model_id','=',Auth::id())
        ->select('role_id')
        ->get();

        $todas = false;
        foreach ($roles_usuario as $rol) {
            if ($rol->role_id == 1 || $rol->role_id == 2) {
                $todas = true;
            }
        }

        if ($todas == false){
            $empleados = DB::table('users')
            ->where('estado_usuario','=','activo')
            ->orderBy('users.name')
            ->get();
        } else {
            $empleados = DB::table('users')
            ->where('tipo_usuario','=','empleado')
            ->where('estado_usuario','=','activo')
            ->orderBy('users.name')
            ->get();
        }

        return view('empleados.lista',compact('empleados','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR EMPLEADO A EDITAR:::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $empleado = DB::table('users')
        ->where('id','=',$request->editar)
        ->where('estado_usuario','=','activo')
        ->leftjoin('tipos_identificacion','tipos_identificacion.id_tipo_identificacion','=','users.id_tipo_identificacion')
        ->first();

        if(is_numeric($request->editar)&&($empleado != null)) {

            $rol_empleado = DB::table('model_has_roles')
            ->where('model_has_roles.model_id','=',$empleado->id)
            ->where('model_has_roles.model_type','=','App\User')
            ->leftjoin('roles','roles.id','=','model_has_roles.role_id')
            ->select('roles.name','roles.id')
            ->first();

            if(isset($rol_empleado)){
                $empleado->role_id = $rol_empleado->id;
                $empleado->rol_empleado = $rol_empleado->name;
            }else{
                $empleado->role_id = "";
                $empleado->rol_empleado = "";
            }

            $id_user = Auth::id();
            $roles_usuario = DB::table('model_has_roles')
            ->where('model_type','=','App\User')
            ->where('model_id','=',$id_user)
            ->select('role_id')
            ->get();

            $todas = false;
            foreach ($roles_usuario as $rol) {
                if ($rol->role_id == 1 || $rol->role_id == 2) {
                    $todas = true;
                }
            }

            if($todas == false){

                $roles = DB::table('roles')
                ->where('id','!=',1)
                ->where('id','!=',2)
                ->get();

                $sucursales = DB::table('usuarios_sucursales')
                ->where('id_usuario','=',$id_user)
                ->where('estado_usuario_sucursal','=','activo')
                ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
                ->where('sucursals.estado_sucursal','=','activo')
                ->where('sucursals.id_sucursal','!=',0)
                ->select('sucursals.id_sucursal','sucursals.nombre_sucursal','sucursals.direccion_sucursal','sucursals.telefono_sucursal','sucursals.nombre_imagen_sucursal','sucursals.estado_sucursal')
                ->get();

            }else{

                $roles = DB::table('roles')
                ->get();

                $sucursales = DB::table('sucursals')
                ->where('id_sucursal','!=',0)
                ->where('estado_sucursal','=','activo')
                ->get();
            }

            $tipos_contratos = DB::table('tipos_contratos')
            ->where('estado_tipo_contrato','=','activo')
            ->get();

            $tipos_identificacion = DB::table('tipos_identificacion')
            ->where('estado_tipo_identificacion','=','activo')
            ->get();

            $sucursales_empleado = DB::table('usuarios_sucursales')
            ->where('usuarios_sucursales.id_sucursal','!=',0)
            ->where('usuarios_sucursales.id_usuario','=',$request->editar)
            ->where('usuarios_sucursales.estado_usuario_sucursal','=','activo')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','usuarios_sucursales.id_sucursal')
            ->select('sucursals.id_sucursal','sucursals.nombre_sucursal')
            ->get();

            return view('empleados.modificar',compact('empleado','roles','sucursales','tipos_contratos','tipos_identificacion','sucursales_empleado'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('empleados.lista',['estatus' => $estatus]);
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::MODIFICAR EMPLEADO:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){

        $empleado = DB::table('users')
        ->where('id','=',$request->id_empleado)
        ->first();

        if(is_numeric($request->id_empleado)&&($empleado != null)) {

            $verificarIdentificacion = DB::table('users')
            ->where('identificacion','=',$request->identificacion_empleado)
            ->where('id_tipo_identificacion','=',$request->tipo_identificacion_empleado)
            ->first();

            $verificarEmail = DB::table('users')
            ->where('email','=',$request->correo_empleado)
            ->first();

            if($verificarIdentificacion != null || $verificarEmail != null){
                
//CASO 1 IDENTIFICACION

                if($verificarIdentificacion != null && $verificarEmail == null){

                    if($empleado->id == $verificarIdentificacion->id){

                        DB::table('users')
                        ->where('id', $request->id_empleado)
                        ->update([
                            'name' => $request->nombre_empleado,
                            'email' => $request->correo_empleado,
                            'apellido' => $request->apellido_empleado,
                            'telefono' => $request->telefono_empleado,
                            'id_tipo_identificacion' => $request->tipo_identificacion_empleado,
                            'identificacion' => $request->identificacion_empleado,
                            'direccion' =>$request->direccion_empleado
                        ]);

                        $roles_empleado = DB::table('model_has_roles')
                        ->where('model_has_roles.model_id','=',$empleado->id)
                        ->where('model_has_roles.model_type','=','App\User')
                        ->first();

                        if (isset($roles_empleado)) {
                            DB::table('model_has_roles')
                            ->where('model_has_roles.model_id','=',$empleado->id)
                            ->where('model_has_roles.model_type','=','App\User')
                            ->update([
                                'role_id' => $request->rol_empleado,
                            ]);
                        }else{
                            DB::table('model_has_roles')->insert([
                                'role_id' => $request->rol_empleado,
                                'model_id' => $request->id_empleado,
                                'model_type' => 'App\User'
                            ]);
                        }

                        $sucursales=json_decode($request->vector_sucursales[0]);

                        foreach ($sucursales as $sucursal) {
                            $sucursal_empleado = DB::table('usuarios_sucursales')
                            ->where('usuarios_sucursales.id_usuario','=',$request->id_empleado)
                            ->where('usuarios_sucursales.id_sucursal','=',$sucursal->id_sucursal)
                            ->first();
                            if (isset($sucursal_empleado)) {
                                DB::table('usuarios_sucursales')
                                ->where('usuarios_sucursales.id_usuario','=',$request->id_empleado)
                                ->where('usuarios_sucursales.id_sucursal','=',$sucursal->id_sucursal)
                                ->update([
                                    'estado_usuario_sucursal' => $sucursal->estado
                                ]);
                            }else{

                                $prioridad = DB::table('usuarios_sucursales')
                                ->where('usuarios_sucursales.id_usuario','=',$request->id_empleado)
                                ->orderBy('prioridad','desc')
                                ->first();

                                DB::table('usuarios_sucursales')->insert(
                                    ['id_usuario' => $request->id_empleado, 'id_sucursal' => $sucursal->id_sucursal, 'estado_usuario_sucursal' => $sucursal->estado, 'prioridad' => $prioridad->prioridad + 1]
                                );
                            }
                        }

                        $estatus="actualizado";
                    }else{
                        //dd("NO puede guardar, esta identificacion es de otro");
                        $estatus="erroractualizar";
                    }
                }//FIN CASO 1

//CASO 2 EMAIL
                if($verificarEmail != null && $verificarIdentificacion == null){

                    if($empleado->id == $verificarEmail->id){
                        //dd("Es el mismo Email de usuario a editar, SI puede");
                        DB::table('users')
                        ->where('id', $request->id_empleado)
                        ->update([
                            'name' => $request->nombre_empleado,
                            'email' => $request->correo_empleado,
                            'apellido' => $request->apellido_empleado,
                            'telefono' => $request->telefono_empleado,
                            'id_tipo_identificacion' => $request->tipo_identificacion_empleado,
                            'identificacion' => $request->identificacion_empleado,
                            'direccion' =>$request->direccion_empleado
                        ]);

                        $roles_empleado = DB::table('model_has_roles')
                        ->where('model_has_roles.model_id','=',$empleado->id)
                        ->where('model_has_roles.model_type','=','App\User')
                        ->first();

                        if (isset($roles_empleado)) {
                            DB::table('model_has_roles')
                            ->where('model_has_roles.model_id','=',$empleado->id)
                            ->where('model_has_roles.model_type','=','App\User')
                            ->update([
                                'role_id' => $request->rol_empleado,
                            ]);
                        }else{
                            DB::table('model_has_roles')->insert([
                                'role_id' => $request->rol_empleado,
                                'model_id' => $request->id_empleado,
                                'model_type' => 'App\User'
                            ]);
                        }

                        $sucursales=json_decode($request->vector_sucursales[0]);

                        foreach ($sucursales as $sucursal) {
                            $sucursal_empleado = DB::table('usuarios_sucursales')
                            ->where('usuarios_sucursales.id_usuario','=',$request->id_empleado)
                            ->where('usuarios_sucursales.id_sucursal','=',$sucursal->id_sucursal)
                            ->first();
                            if (isset($sucursal_empleado)) {
                                DB::table('usuarios_sucursales')
                                ->where('usuarios_sucursales.id_usuario','=',$request->id_empleado)
                                ->where('usuarios_sucursales.id_sucursal','=',$sucursal->id_sucursal)
                                ->update([
                                    'estado_usuario_sucursal' => $sucursal->estado
                                ]);
                            }else{

                                $prioridad = DB::table('usuarios_sucursales')
                                ->where('usuarios_sucursales.id_usuario','=',$request->id_empleado)
                                ->orderBy('prioridad','desc')
                                ->first();

                                DB::table('usuarios_sucursales')->insert(
                                    ['id_usuario' => $request->id_empleado, 'id_sucursal' => $sucursal->id_sucursal, 'estado_usuario_sucursal' => $sucursal->estado, 'prioridad' => $prioridad->prioridad + 1]
                                );
                            }
                        }

                        $estatus="actualizado";
                    }else{
                        //dd("NO puede guardar, este Email es de otro");
                        $estatus="erroractualizar";
                    }
                }//FIN CASO 2


//CASO 3 IDENTIFICACION Y EMAIL
                if($verificarIdentificacion != null && $verificarEmail != null){

                    if($empleado->id == $verificarIdentificacion->id && $empleado->id == $verificarEmail->id){
                        //dd("Es el mismo Email e Identificacion de usuario a editar, SI puede");
                        DB::table('users')
                        ->where('id', $request->id_empleado)
                        ->update([
                            'name' => $request->nombre_empleado,
                            'email' => $request->correo_empleado,
                            'apellido' => $request->apellido_empleado,
                            'telefono' => $request->telefono_empleado,
                            'id_tipo_identificacion' => $request->tipo_identificacion_empleado,
                            'identificacion' => $request->identificacion_empleado,
                            'direccion' =>$request->direccion_empleado
                        ]);

                        $estatus="actualizado";
                    }else{
                        //dd("NO puede guardar, esta identificacion o correo es de otro");
                        $estatus="erroractualizar";
                    }
                }//FIN CASO 3

            }else{
                //dd("No existe, puede guardar normalmente");
                DB::table('users')
                ->where('id', $request->id_empleado)
                ->update([
                    'name' => $request->nombre_empleado,
                    'email' => $request->correo_empleado,
                    'apellido' => $request->apellido_empleado,
                    'telefono' => $request->telefono_empleado,
                    'id_tipo_identificacion' => $request->tipo_identificacion_empleado,
                    'identificacion' => $request->identificacion_empleado,
                    'direccion' =>$request->direccion_empleado
                ]);

                $roles_empleado = DB::table('model_has_roles')
                ->where('model_has_roles.model_id','=',$empleado->id)
                ->where('model_has_roles.model_type','=','App\User')
                ->first();

                if (isset($roles_empleado)) {
                    DB::table('model_has_roles')
                    ->where('model_has_roles.model_id','=',$empleado->id)
                    ->where('model_has_roles.model_type','=','App\User')
                    ->update([
                        'role_id' => $request->rol_empleado,
                    ]);
                }else{
                    DB::table('model_has_roles')->insert([
                        'role_id' => $request->rol_empleado,
                        'model_id' => $request->id_empleado,
                        'model_type' => 'App\User'
                    ]);
                }

                $sucursales=json_decode($request->vector_sucursales[0]);

                foreach ($sucursales as $sucursal) {
                    $sucursal_empleado = DB::table('usuarios_sucursales')
                    ->where('usuarios_sucursales.id_usuario','=',$request->id_empleado)
                    ->where('usuarios_sucursales.id_sucursal','=',$sucursal->id_sucursal)
                    ->first();
                    if (isset($sucursal_empleado)) {
                        DB::table('usuarios_sucursales')
                        ->where('usuarios_sucursales.id_usuario','=',$request->id_empleado)
                        ->where('usuarios_sucursales.id_sucursal','=',$sucursal->id_sucursal)
                        ->update([
                            'estado_usuario_sucursal' => $sucursal->estado
                        ]);
                    }else{

                        $prioridad = DB::table('usuarios_sucursales')
                        ->where('usuarios_sucursales.id_usuario','=',$request->id_empleado)
                        ->orderBy('prioridad','desc')
                        ->first();

                        DB::table('usuarios_sucursales')->insert(
                            ['id_usuario' => $request->id_empleado, 'id_sucursal' => $sucursal->id_sucursal, 'estado_usuario_sucursal' => $sucursal->estado, 'prioridad' => $prioridad->prioridad + 1]
                        );
                    }
                }
                $estatus="actualizado";
            }
        }else{
            //dd("Error el usuario no existe");
            $estatus="error";
        }    

        return redirect()->route('empleados.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::INACTIVAR USUARIO::::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $existe = DB::table('users')
        ->where('id','=',$request->eliminar)
        ->where('estado_usuario','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('users')
            ->where('id', $request->eliminar)
            ->update(['estado_usuario' => 'inactivo']);
        }else{
            $estatus="error";
        }

        return redirect()->route('empleados.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::VER DETALLE EMPLEADO:::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){
        dd("Ver");
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('empleados.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR:::::::::::::::::::::::::::::::::::::::::::::::*/
}
