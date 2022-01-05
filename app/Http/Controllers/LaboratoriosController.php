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

class LaboratoriosController extends Controller
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
/*:::::::::::::::::::::::::::::::::::::::::::CREAR LABORATORIO::::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear(Request $request){
        
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $marcas = DB::table('marcas')
        ->where('estado_marca','=','activo')
        ->get();

        return view('laboratorios.crear',compact('estatus','marcas'));
	}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR LABORATORIO:::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        $existe = DB::table('laboratorios')
        ->where('nombre_laboratorio','=',$request->nombre_laboratorio)
        ->where('estado_laboratorio','=','activo')
        ->pluck('nombre_laboratorio');

        if($existe=="[]"){
                $estatus="exito";
                
                $hora = date('H-i-s');
                $nombre_imagen = 'lab-oa-' . $hora . $request->imagen_laboratorio->getClientOriginalName();
                $request->imagen_laboratorio->move('public/imagenes/sistema/laboratorios',$nombre_imagen);

                $id_laboratorio = DB::table('laboratorios')->insertGetId(
                    ['nombre_laboratorio' => $request->nombre_laboratorio, 'estado_laboratorio' => $request->estado_laboratorio, 'direccion_laboratorio' => $request->direccion_laboratorio, 'telefono_laboratorio' => $request->telefono_laboratorio, 'nombre_imagen_laboratorio' => $nombre_imagen]
                );

                $date = Carbon::now();$id_user = Auth::id();
                DB::table('user_auditorias')->insert(['id_usuario' => $id_user, 'id_modulo' => 14, 'accion' => 'crear', 'id_elemento' => $id_laboratorio, 'fecha_user_auditoria' => $date, 'estado_user_auditoria' => 'activo']);

                foreach ($request->marcas_laboratorio as $marca_laboratorio) {
                    DB::table('marcas_laboratorios')->insert(
                        ['id_laboratorio' => $id_laboratorio, 'id_marca' => $marca_laboratorio,  'estado_marca_laboratorio' => 'activo']
                    );
                }

        }else{
                $estatus="error";
        }

        return redirect()->route('laboratorios.crear',['estatus' => $estatus]);
    }


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE LABORATORIOS::::::::::::::::::::::::::::::::::::::::::::*/


   public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $laboratorios = DB::table('laboratorios')
        ->where('estado_laboratorio','=','activo')
        ->get();

        return view('laboratorios.lista',compact('laboratorios','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::BUSCAR LABORATORIO A EDITAR::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $laboratorio = DB::table('laboratorios')
        ->where('id_laboratorio','=',$request->editar)
        ->where('estado_laboratorio','=','activo')
        ->first();
        if(is_numeric($request->editar)&&($laboratorio != null)) {

            $marcas = DB::table('marcas')
            ->where('estado_marca','=','activo')
            ->orderBy('nombre_marca')
            ->get();

            $marcas_laboratorios = DB::table('marcas_laboratorios')
            ->where('estado_marca_laboratorio','=','activo')
            ->where('id_laboratorio','=',$request->editar)
            ->leftJoin('marcas','marcas.id_marca','=','marcas_laboratorios.id_marca')
            ->get();

            $marcas_distintas = [];
            foreach ($marcas as $marca) {
                $encontrado = false;
                foreach ($marcas_laboratorios as $marca_laboratorio) {
                        if ($marca_laboratorio->id_marca == $marca->id_marca) {
                            $encontrado = true;
                        }
                }
                if ($encontrado == false) {
                    array_push($marcas_distintas,$marca);
                }
            }

            return view('laboratorios.modificar',compact('laboratorio','marcas_distintas','marcas_laboratorios'));

        }else{

            $estatus="erroractualizar";
            return redirect()->route('laboratorios.lista',['estatus' => $estatus]);
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::MODIFICAR LABORATORIO::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){
        $laboratorio = DB::table('laboratorios')
        ->where('id_laboratorio','=',$request->id_laboratorio)
        ->where('estado_laboratorio','=','activo')
        ->first();

        if(is_numeric($request->id_laboratorio)&&($laboratorio != null)) {
            $verificar = DB::table('laboratorios')
            ->where('nombre_laboratorio','=',$request->nombre_laboratorio)
            ->where('estado_laboratorio','=','activo')
            ->first();
            if($verificar != null){
                if($laboratorio->nombre_laboratorio == $request->nombre_laboratorio){
                    if($request->imagen_laboratorio!=null){
                        $hora = date('H-i-s');
                        $nombre_imagen = 'prov-oa-' . $hora . $request->imagen_laboratorio->getClientOriginalName();
                        $request->imagen_laboratorio->move('public/imagenes/sistema/laboratorios',$nombre_imagen);
                        DB::table('laboratorios')
                        ->where('id_laboratorio', $request->id_laboratorio)
                        ->update([
                            'nombre_laboratorio' => $request->nombre_laboratorio,
                            'direccion_laboratorio' => $request->direccion_laboratorio,
                            'telefono_laboratorio' => $request->telefono_laboratorio,
                            'nombre_imagen_laboratorio' => $nombre_imagen
                        ]);
                        File::delete('public/imagenes/sistema/laboratorios/'.$laboratorio->nombre_imagen_laboratorio);
                    }else{
                        DB::table('laboratorios')
                        ->where('id_laboratorio', $request->id_laboratorio)
                        ->update([
                            'nombre_laboratorio' => $request->nombre_laboratorio,
                            'direccion_laboratorio' => $request->direccion_laboratorio,
                            'telefono_laboratorio' => $request->telefono_laboratorio
                        ]);
                    }

                    foreach ($request->marcas_laboratorio as $marca_laboratorio) {
                        DB::table('marcas_laboratorios')->insert(
                            ['id_laboratorio' => $request->id_laboratorio, 'id_marca' => $marca_laboratorio,  'estado_marca_laboratorio' => 'activo']
                        );
                    }
                    $estatus="actualizado";
                }else{
                    $estatus="erroractualizar";
                }
            }else{
                if($request->imagen_laboratorio!=null){
                    $hora = date('H-i-s');
                    $nombre_imagen = 'prov-oa-' . $hora . $request->imagen_laboratorio->getClientOriginalName();
                    $request->imagen_laboratorio->move('public/imagenes/sistema/laboratorios',$nombre_imagen);
                    DB::table('laboratorios')
                    ->where('id_laboratorio', $request->id_laboratorio)
                    ->update([
                        'nombre_laboratorio' => $request->nombre_laboratorio,
                        'direccion_laboratorio' => $request->direccion_laboratorio,
                        'telefono_laboratorio' => $request->telefono_laboratorio,
                        'nombre_imagen_laboratorio' => $nombre_imagen
                    ]);
                    File::delete('public/imagenes/sistema/laboratorios/'.$laboratorio->nombre_imagen_laboratorio);
                }else{
                    DB::table('laboratorios')
                    ->where('id_laboratorio', $request->id_laboratorio)
                    ->update([
                        'nombre_laboratorio' => $request->nombre_laboratorio,
                        'direccion_laboratorio' => $request->direccion_laboratorio,
                        'telefono_laboratorio' => $request->telefono_laboratorio
                    ]);
                }
                foreach ($request->marcas_laboratorio as $marca_laboratorio) {
                    DB::table('marcas_laboratorios')->insert(
                        ['id_laboratorio' => $request->id_laboratorio, 'id_marca' => $marca_laboratorio,  'estado_marca_laboratorio' => 'activo']
                    );
                }
                $estatus="actualizado";
            }
        }else{
            $estatus="erroractualizar";
        }    

        return redirect()->route('laboratorios.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::INACTIVAR LABORATORIO::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $existe = DB::table('laboratorios')
        ->where('id_laboratorio','=',$request->eliminar)
        ->where('estado_laboratorio','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('laboratorios')
            ->where('id_laboratorio', $request->eliminar)
            ->update(['estado_laboratorio' => 'inactivo']);
        }else{
            $estatus="error";
        }

       return redirect()->route('laboratorios.lista',['estatus' => $estatus]);
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::INACTIVAR LABORATORIO::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivarMarca(Request $request){


        //dd($request,"desvincular");
        $existe = DB::table('marcas_laboratorios')
        ->where('id_marca_laboratorio','=',$request->eliminar)
        ->where('estado_marca_laboratorio','=','activo')
        ->first();

        if(is_numeric($request->eliminar)&&($existe != null)) {

            $estatus="exito";
            DB::table('marcas_laboratorios')
            ->where('id_marca_laboratorio', $request->eliminar)
            ->update(['estado_marca_laboratorio' => 'inactivo']);
        }else{
            $estatus="error";
        }

       return redirect()->route('laboratorios.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('laboratorios.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR::::::::::::::::::::::::::::::::::::::::::::::::*/
}