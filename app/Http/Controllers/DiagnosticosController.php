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

class DiagnosticosController extends Controller
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
/*::::::::::::::::::::::::::::::::::::::::CREAR DIAGNOSTICO::::::::::::::::::::::::::::::::::::::::::::::*/


    public function crear(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
        
        return view('diagnosticos.crear',compact('estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::GUARDAR DIAGNOSTICO::::::::::::::::::::::::::::::::::::::::::::*/

    public function guardar(Request $request){
        //REALIZAR VALIDACION DE NO DUPLICAR
        DB::table('diagnosticos')->insert([
            'nombre_diagnostico' => $request->nombre_diagnostico,
            'codigo_diagnostico' => $request->codigo_diagnostico,
            'descripcion_diagnostico' => $request->descripcion_diagnostico,
            'estado_diagnostico' => 'activo'
        ]);

        $estatus = "exito";

        return redirect()->route('diagnosticos.crear',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::LISTA DE DIAGNOSTICOS::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $diagnosticos = DB::table('diagnosticos')
        ->where('estado_diagnostico','=','activo')
        ->orderBy('id_diagnostico','desc')
        ->get();

        return view('diagnosticos.lista',compact('diagnosticos','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::EDITAR DIAGNOSTICO:::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $diagnostico = DB::table('diagnosticos')
        ->where('estado_diagnostico','=','activo')
        ->where('id_diagnostico','=',$request->editar)
        ->first();

        if (isset($diagnostico)) {
            $estatus="";
            return view('diagnosticos.modificar',compact('diagnostico','estatus'));
        }else{
            $estatus="errorActualizar";
            return redirect()->route('diagnosticos.lista',['estatus' => $estatus]);
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::MODIFICAR DIAGNOSTICO::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){

        DB::table('diagnosticos')
        ->where('id_diagnostico', $request->id_diagnostico)
        ->update([
            'nombre_diagnostico' => $request->nombre_diagnostico,
            'codigo_diagnostico' => $request->codigo_diagnostico,
            'descripcion_diagnostico' => $request->descripcion_diagnostico,
        ]);
        $estatus = "actualizado";
        return redirect()->route('diagnosticos.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::INACTIVAR DIAGNOSTICO:::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $diagnostico = DB::table('diagnosticos')
        ->where('estado_diagnostico','=','activo')
        ->where('id_diagnostico','=',$request->eliminar)
        ->first();

        if(is_numeric($request->eliminar)&&($diagnostico != null)) {

            $estatus="Eliminado";
            DB::table('diagnosticos')
            ->where('id_diagnostico', $request->eliminar)
            ->update(['estado_diagnostico' => 'inactivo']);
        }else{
            $estatus="errorEliminar";
        }

        return redirect()->route('diagnosticos.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('diagnosticos.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR:::::::::::::::::::::::::::::::::::::::::::::::*/
}
