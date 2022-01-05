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

class clientesController extends Controller
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
/*::::::::::::::::::::::::::::::::::::::::::::CREAR CLIENTE:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function crear(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
        
        $tipos_identificacion = DB::table('tipos_identificacion')
        ->where('estado_tipo_identificacion','=','activo')
        ->get();

        $cirugias = DB::table('cirugias')
        ->where('estado_cirugia','=','activo')
        ->get();

        $diagnosticos = DB::table('diagnosticos')
        ->where('estado_diagnostico','=','activo')
        ->get();
        //dd("crear",$request);

        return view('clientes.crear',compact('estatus','tipos_identificacion','cirugias','diagnosticos'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::BUSCAR CLIENTE::::::::::::::::::::::::::::::::::::::::::::::*/


    public function cliente(Request $request){//AJAX

        $cliente = DB::table('clientes')
        ->where('id_tipo_identificacion','=',$request->tipo_identificacion)
        ->where('identificacion','=',$request->identificacion)
        ->first();

        $cliente = json_encode($cliente);

        return ($cliente);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::GUARDAR CLIENTE:::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        //dd($request);
        
        if(isset($request->id_cliente)){
            
            DB::table('clientes')
            ->where('id_cliente','=',$request->id_cliente)
            ->update([
                'telefono' => $request->telefono_cliente,
                'email' => $request->correo_cliente
            ]);

            $estatus = "exitoActualizar";

        }else{
            
            DB::table('clientes')->insert([
                'id_tipo_identificacion' => $request->tipo_identificacion,
                'identificacion' => $request->identificacion,
                'nombres' => $request->nombre,
                'apellidos' => $request->apellido,
                'telefono' => $request->telefono_cliente,
                'email' => $request->correo_cliente
            ]);

            $estatus = "exitoCrear";
        }

       return redirect()->route('clientes.crear',['estatus' => $estatus]);

    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::LISTA DE PACIENTES::::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $clientes = DB::table('clientes')
        ->orderBy('id_cliente','desc')
        ->get();

        if (isset($clientes)) {

        }

        //dd($clientes);

        return view('clientes.lista',compact('clientes','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::VER PACIENTE::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){

        dd($request,"verr");

        $paciente = DB::table('pacientes')
        ->where('id_paciente','=',$request->ver)
        ->leftJoin('tipos_identificacion','tipos_identificacion.id_tipo_identificacion','=','pacientes.id_tipo_identificacion')
        ->first();

        if (isset($paciente)) {
            
            $date = Carbon::parse($paciente->fecha_nacimiento);
            $edad =\Carbon\Carbon::parse($date)->age;
            $paciente->edad_paciente = $edad;
        

            $diagnosticos = DB::table('diagnosticos_pacientes')
            ->where('id_paciente','=',$request->ver)
            ->leftJoin('diagnosticos','diagnosticos.id_diagnostico','=','diagnosticos_pacientes.id_diagnostico')
            ->select('diagnosticos.nombre_diagnostico')
            ->distinct()
            ->get();
            $c_diagnosticos = count($diagnosticos);
            $diagnosticos_pacientes = "";
            foreach ($diagnosticos as $diagnostico) {
                if($diagnostico == $diagnosticos[$c_diagnosticos - 1]){
                    $diagnosticos_pacientes = $diagnosticos_pacientes . $diagnostico->nombre_diagnostico;
                }else{
                    $diagnosticos_pacientes = $diagnosticos_pacientes . $diagnostico->nombre_diagnostico . ", ";
                }
            }
            $paciente->diagnosticos = $diagnosticos_pacientes;

            $cirugias = DB::table('cirugias_pacientes')
            ->where('id_paciente','=',$request->ver)
            ->leftJoin('cirugias','cirugias.id_cirugia','=','cirugias_pacientes.id_cirugia')
            ->select('cirugias.nombre_cirugia')
            ->distinct()
            ->get();
            $c_cirugias = count($cirugias);
            $cirugias_pacientes = "";
            foreach ($cirugias as $cirugia) {
                if ($cirugia == $cirugias[$c_cirugias - 1]) {
                    $cirugias_pacientes = $cirugias_pacientes . $cirugia->nombre_cirugia;
                }else{
                    $cirugias_pacientes = $cirugias_pacientes . $cirugia->nombre_cirugia . ", ";
                }
            }
            $paciente->cirugias = $cirugias_pacientes;

            $examenes = DB::table('examenes')
            ->where('estado_examen','=','activo')
            ->where('tipo_examen','=','interno')
            ->where('id_paciente','=',$request->ver)
            ->leftJoin('users','users.id','=','examenes.id_doctor')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','examenes.id_sucursal')
            ->orderBy('id_examen','desc')
            ->select('examenes.id_examen','examenes.fecha_examen','sucursals.nombre_sucursal','users.name','users.apellido')
            ->get();
        }

        //dd($examenes);

        return view('pacientes.ver',compact('paciente','examenes'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::EDITAR EXAMEN:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        dd("editar",$request);
    }




/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

}
