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

class PacientesController extends Controller
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
/*::::::::::::::::::::::::::::::::::::::::::::CREAR PACIENTE:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function crear(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
        
        $tipos_identificacion = DB::table('tipos_identificacion')
        ->where('estado_tipo_identificacion','=','activo')
        ->get();

        $cirugias = DB::table('cirugias')
        ->where('estado_cirugia','=','activo')
        ->get();

        $triage = DB::table('configuracion_triage')
        ->where('estado_triage','=','activo')
        ->first();

        if (isset($triage)) {
            $categorias_triage = DB::table('categorias_triage')
            ->where('estado_categoria_triage','=','activo')
            ->get();
            $triage = true;
        }else{
            $categorias_triage = [];
            $triage = false;
        }

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
            ->get();

        }else{
            $usuarios_sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        return view('pacientes.crear',compact('estatus','tipos_identificacion','cirugias','categorias_triage','triage','usuarios_sucursales'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::BUSCAR PACIENTE:::::::::::::::::::::::::::::::::::::::::::::*/


    public function paciente(Request $request){//AJAX

        $paciente = DB::table('pacientes')
        ->where('id_tipo_identificacion','=',$request->tipo_identificacion)
        ->where('identificacion','=',$request->identificacion)
        ->first();

        if (isset($paciente)) {

            $cirugias = DB::table('cirugias_pacientes')
            ->where('cirugias_pacientes.id_paciente','=',$paciente->id_paciente)
            ->where('cirugias_pacientes.estado_cirugia_paciente','=','activo')
            ->leftJoin('cirugias','cirugias.id_cirugia','=','cirugias_pacientes.id_cirugia')
            ->where('cirugias.estado_cirugia','=','activo')
            ->get();
            $paciente->cirugias = $cirugias;

            $date = Carbon::parse($paciente->fecha_nacimiento);
            $edad =\Carbon\Carbon::parse($date)->age;
            $paciente->edad = $edad;
        }

        $paciente = json_encode($paciente);

        return ($paciente);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::GUARDAR PACIENTE:::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        // dd($request);

        if(isset($request->id_paciente)){
            
            DB::table('pacientes')
            ->where('id_paciente','=',$request->id_paciente)
            ->update([
                    'nombres_paciente' => $request->nombre,
                    'apellidos_paciente' => $request->apellido,
                    'id_tipo_identificacion' => $request->tipo_identificacion,
                    'identificacion' => $request->identificacion,
                    'telefono_paciente' => $request->telefono,
                    'correo_paciente' => $request->correo,
                    'lugar_residencia' => $request->residencia,
                    'direccion_paciente' => $request->direccion,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'genero' => $request->genero,
                    'estado_civil' => $request->estado_civil,
                    'ocupacion_actual' => $request->ocupacion_actual,
                    'motivo_consulta' => $request->motivo_consulta,
                    'ultimo_examen' => $request->ultimo_examen,
                    'responsable' => $request->responsable,
                    'telefono_responsable' => $request->telefono_responsable,
                    'parentesco_responsable' => $request->parentesco_responsable,
                    'acompanante' => $request->acompanante,
                    'telefono_acompanante' => $request->telefono_acompanante,
                    'afiliacion_salud' => $request->afiliacion_salud
            ]);

            $id_paciente = $request->id_paciente;

            $estatus = "exitoActualizar";

        }else{
            
            $id_paciente = DB::table('pacientes')->insertGetId([
                    'nombres_paciente' => $request->nombre,
                    'apellidos_paciente' => $request->apellido,
                    'id_tipo_identificacion' => $request->tipo_identificacion,
                    'identificacion' => $request->identificacion,
                    'telefono_paciente' => $request->telefono,
                    'correo_paciente' => $request->correo,
                    'lugar_residencia' => $request->residencia,
                    'direccion_paciente' => $request->direccion,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'genero' => $request->genero,
                    'estado_civil' => $request->estado_civil,
                    'ocupacion_actual' => $request->ocupacion_actual,
                    'motivo_consulta' => $request->motivo_consulta,
                    'ultimo_examen' => $request->ultimo_examen,
                    'responsable' => $request->responsable,
                    'telefono_responsable' => $request->telefono_responsable,
                    'parentesco_responsable' => $request->parentesco_responsable,
                    'acompanante' => $request->acompanante,
                    'telefono_acompanante' => $request->telefono_acompanante,
                    'afiliacion_salud' => $request->afiliacion_salud
            ]);

            $estatus = "exitoCrear";
        }

        $cirugias_paciente = DB::table('cirugias_pacientes')
        ->where('id_paciente','=',$id_paciente)
        ->where('estado_cirugia_paciente','=','activo')
        ->get();
        
        if(isset($request->cirugias)){
            foreach ($request->cirugias as $cirugia_post) {
                $encontro_cirugia = false;
                foreach ($cirugias_paciente as $cirugia_paciente) {
                    if($cirugia_paciente->id_cirugia == $cirugia_post){
                        $encontro_cirugia = true;
                        break;
                    }                
                }
                if($encontro_cirugia == false){
                    DB::table('cirugias_pacientes')->insert([
                        'id_paciente' => $id_paciente,
                        'id_cirugia' => $cirugia_post,
                        'estado_cirugia_paciente' => 'activo'
                    ]);
                }
            }
        }
        
        if($request->triage == "SI"){

            DB::table('triages_pacientes')->insert([
                    'id_paciente' => $id_paciente,
                    'fecha_triage' => Carbon::now(),
                    'id_categoria_triage' => $request->id_categoria_triage,
                    'tos_ultimos_14' => $request->tos_ultimos_14,
                    'fiebre_ultimos_14' => $request->fiebre_ultimos_14,
                    'dolor_garganta_ultimos_14' => $request->dolor_garganta_ultimos_14,
                    'dolor_garganta_ultimos_14' => $request->dolor_garganta_ultimos_14,
                    'dolor_cabeza_ultimos_14' => $request->dolor_cabeza_ultimos_14,
                    'secresion_nasal_ultimos_14' => $request->secresion_nasal_ultimos_14,
                    'fatiga_ultimos_14' => $request->fatiga_ultimos_14,
                    'dolor_muscular_ultimos_14' => $request->dolor_muscular_ultimos_14,
                    'escalofrio_resfriado_ultimos_14' => $request->escalofrio_resfriado_ultimos_14,
                    'perdida_apetito_ultimos_14' => $request->perdida_apetito_ultimos_14,
                    'perdida_gusto_olfato_ultimos_14' => $request->perdida_gusto_olfato_ultimos_14,
                    'contacto_persona_sintomas_anteriores' => $request->contacto_persona_sintomas_anteriores,
                    'viaje_internacional_ultimos_30' => $request->viaje_internacional_ultimos_30,
                    'le_realizaron_pruebas_covid19' => $request->le_realizaron_pruebas_covid19,
                    'enfermedad_respiratoria' => $request->enfermedad_respiratoria,
                    'diagnosticado_enfermedades' => $request->diagnosticado_enfermedades,
                    'esta_embarazada' => $request->esta_embarazada,
                    'obesidad' => $request->obesidad,
                    'fuma' => $request->fuma,
                    'mas_de_65' => $request->mas_de_65,
                    'temperatura' => $request->temperatura,
                    'fecha_triage' => Carbon::now(),
                    'id_sucursal_triage' => $request->sucursal,
                    'estado_triage' => 'activo'
            ]);

        }

       return redirect()->route('pacientes.crear',['estatus' => $estatus]);

    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::LISTA DE PACIENTES::::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $pacientes = DB::table('pacientes')
        ->where('estado_paciente','=','activo')
        ->orderBy('id_paciente','desc')
        ->get();

        return view('pacientes.lista',compact('pacientes','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::VER PACIENTE::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){

        $paciente = DB::table('pacientes')
        ->where('id_paciente','=',$request->ver)
        ->leftJoin('tipos_identificacion','tipos_identificacion.id_tipo_identificacion','=','pacientes.id_tipo_identificacion')
        ->first();

        if (isset($paciente)) {
            
            $date = Carbon::parse($paciente->fecha_nacimiento);
            $edad =\Carbon\Carbon::parse($date)->age;
            $paciente->edad_paciente = $edad;
        

            // $diagnosticos = DB::table('diagnosticos_pacientes')
            // ->where('id_paciente','=',$request->ver)
            // ->leftJoin('diagnosticos','diagnosticos.id_diagnostico','=','diagnosticos_pacientes.id_diagnostico')
            // ->select('diagnosticos.nombre_diagnostico')
            // ->distinct()
            // ->get();
            // $c_diagnosticos = count($diagnosticos);
            // $diagnosticos_pacientes = "";
            // foreach ($diagnosticos as $diagnostico) {
            //     if($diagnostico == $diagnosticos[$c_diagnosticos - 1]){
            //         $diagnosticos_pacientes = $diagnosticos_pacientes . $diagnostico->nombre_diagnostico;
            //     }else{
            //         $diagnosticos_pacientes = $diagnosticos_pacientes . $diagnostico->nombre_diagnostico . ", ";
            //     }
            // }
            // $paciente->diagnosticos = $diagnosticos_pacientes;

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
/*::::::::::::::::::::::::::::::::::::::::LISTA DE TRIAGES:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function listaTriages(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $triages = DB::table('triages_pacientes')
        ->where('estado_triage','=','activo')
        ->leftJoin('pacientes','pacientes.id_paciente','=','triages_pacientes.id_paciente')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','triages_pacientes.id_sucursal_triage')
        ->leftJoin('categorias_triage','categorias_triage.id_categoria_triage','=','triages_pacientes.id_categoria_triage')
        ->orderBy('id_triage_paciente','desc')
        ->get();

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
            ->get();

        }else{
            $usuarios_sucursales = DB::table('sucursals')
            ->where('id_sucursal','!=',0)
            ->where('estado_sucursal','=','activo')
            ->get();
        }

        $triages_disponibles = [];
        foreach ($triages as $triage) {
            foreach ($usuarios_sucursales as $usuario_sucursal) {
                if ($triage->id_sucursal_triage == $usuario_sucursal->id_sucursal) {
                    array_push($triages_disponibles, $triage);
                    break;
                }
            }
        }
        $triages = $triages_disponibles;

        return view('triages.lista',compact('triages'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

}
