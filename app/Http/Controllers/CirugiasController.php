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

class CirugiasController extends Controller
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
/*:::::::::::::::::::::::::::::::::::::::::::CREAR CIRUGIA:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function crear(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
        
        return view('cirugias.crear',compact('estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::GUARDAR CIRUGIAS:::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){
        //REALIZAR VALIDACION DE NO DUPLICAR
        DB::table('cirugias')->insert([
            'nombre_cirugia' => $request->nombre_cirugia,
            'descripcion_cirugia' => $request->descripcion_cirugia,
            'estado_cirugia' => 'activo'
        ]);

        $estatus = "exito";

        return redirect()->route('cirugias.crear',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::LISTA DE CIRUGIAS::::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $cirugias = DB::table('cirugias')
        ->where('estado_cirugia','=','activo')
        ->orderBy('id_cirugia','desc')
        ->get();

        return view('cirugias.lista',compact('cirugias','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::EDITAR CIRUGIA::::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $cirugia = DB::table('cirugias')
        ->where('estado_cirugia','=','activo')
        ->where('id_cirugia','=',$request->editar)
        ->first();

        if (isset($cirugia)) {
            $estatus="";
            return view('cirugias.modificar',compact('cirugia','estatus'));
        }else{
            $estatus="errorActualizar";
            return redirect()->route('cirugias.lista',['estatus' => $estatus]);
        }
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::MODIFICAR CIRUGIA:::::::::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){

        DB::table('cirugias')
        ->where('id_cirugia', $request->id_cirugia)
        ->update([
            'nombre_cirugia' => $request->nombre_cirugia,
            'descripcion_cirugia' => $request->descripcion_cirugia,
        ]);
        $estatus = "actualizado";
        return redirect()->route('cirugias.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::INACTIVAR CIRUGIA::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $cirugia = DB::table('cirugias')
        ->where('estado_cirugia','=','activo')
        ->where('id_cirugia','=',$request->eliminar)
        ->first();

        if(is_numeric($request->eliminar)&&($cirugia != null)) {

            $estatus="exito";
            DB::table('cirugias')
            ->where('id_cirugia', $request->eliminar)
            ->update(['estado_cirugia' => 'inactivo']);
        }else{
            $estatus="errorEliminar";
        }

        return redirect()->route('cirugias.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('cirugias.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR:::::::::::::::::::::::::::::::::::::::::::::::*/

}
