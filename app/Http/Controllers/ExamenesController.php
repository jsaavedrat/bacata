<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class ExamenesController extends Controller
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
/*::::::::::::::::::::::::::::::::::::::::::::CREAR EXAMEN:::::::::::::::::::::::::::::::::::::::::::::::*/


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

        $configuracion_cotizacion = DB::table('configuracion_cotizaciones')
        ->first();

        if(isset($configuracion_cotizacion)){

            $clasificaciones = DB::table('clasificacion_tipo_productos')
            ->where('estado_clasificacion_tipo_producto','=','activo')
            ->where('id_tipo_producto','=',$configuracion_cotizacion->id_tipo_producto_lentes)
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
            ->get();

            $especificaciones = DB::table('especificaciones')
            ->where('estado_especificacion','=','activo')
            ->orderBy('nombre_especificacion')
            ->get();

            foreach ($clasificaciones as $clasificacion) {

                    $aux = [];
                    foreach ($especificaciones as $especificacion) {
                            if($clasificacion->id_clasificacion == $especificacion->id_clasificacion){
                                    $valores=[
                                        'id_especificacion' => $especificacion->id_especificacion,
                                        'nombre_especificacion' => $especificacion->nombre_especificacion
                                    ];
                                    array_push($aux,$valores);
                            }
                    }
                    $clasificacion->especificaciones = $aux;
            }

            $clasificaciones = json_encode($clasificaciones);
            $clasificaciones = json_decode($clasificaciones);
        }else{
            $clasificaciones = [];
        }

        $marcas_laboratorios = DB::table('marcas_laboratorios')
        ->where('estado_marca_laboratorio','=','activo')
        ->leftJoin('marcas','marcas.id_marca','=','marcas_laboratorios.id_marca')
        ->leftJoin('modelos','modelos.id_marca','=','marcas.id_marca')
        ->select('marcas.id_marca','marcas.nombre_marca','modelos.id_modelo','modelos.nombre_modelo')
        ->orderBy('marcas.nombre_marca')
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

        $doctor = DB::table('users')
        ->where('id','=',Auth::id())
        ->first();

        return view('examenes.crear',compact('estatus','tipos_identificacion','cirugias','diagnosticos','marcas_laboratorios','clasificaciones','usuarios_sucursales','doctor'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::BUSCAR PACIENTE:::::::::::::::::::::::::::::::::::::::::::::*/


    public function paciente(Request $request){//AJAX

        $paciente = DB::table('pacientes')
        ->where('id_tipo_identificacion','=',$request->tipo_identificacion)
        ->where('identificacion','=',$request->identificacion)
        ->first();

        if (isset($paciente)) {

            $examenes = DB::table('examenes')
            ->where('examenes.id_paciente','=',$paciente->id_paciente)
            ->leftJoin('pacientes','pacientes.id_paciente','=','examenes.id_paciente')
            ->leftJoin('users','users.id','=','examenes.id_doctor')
            ->leftJoin('sucursals','sucursals.id_sucursal','=','examenes.id_sucursal')
            ->orderBy('examenes.id_examen', 'desc')
            ->get();
            if($examenes != "[]"){
                $paciente->examen = $examenes[0];
                $paciente->examenes = $examenes;
            }

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

            $triage = DB::table('configuracion_triage')
            ->where('estado_triage','=','activo')
            ->first();

            $date = Carbon::now();
            $hoy = $date->toDateString() . " 00:00:00";
            $manana = new Carbon('tomorrow');

            if (isset($triage)) {

                $triage_paciente = DB::table('triages_pacientes')
                ->where('id_paciente','=',$paciente->id_paciente)
                ->where('estado_triage','=','activo')
                ->where('fecha_triage','>=',$hoy)
                ->where('fecha_triage','<',$manana)
                ->first();

                if (isset($triage_paciente)) {
                    $paciente->triage = true;
                }else{
                    $paciente->triage = false;
                }
                
            }
            
        }

        $paciente = json_encode($paciente);

        return ($paciente);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::GUARDAR EXAMEN::::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        if (isset($request->firma_paciente) && $request->firma_paciente != "") {
            $base_to_php = base64_encode(file_get_contents($request->firma_paciente));
            $data = base64_decode($base_to_php);
            $filepath_paciente = "public/imagenes/sistema/f/imagen-firma-" . Auth::id() . ".png";
            file_put_contents($filepath_paciente,$data);
        }

        $doctor = DB::table('users')
        ->where('id','=',Auth::id())
        ->first();

        if (isset($request->firma_optometra) && $request->firma_optometra != "") {
            //Guardo la imagen
            $base_to_php = base64_encode(file_get_contents($request->firma_optometra));
            $data = base64_decode($base_to_php);
            $hora = date('H-i-s');
            $date = Carbon::now();
            $filepath = "public/imagenes/sistema/f/f-" . Auth::id() . "f-" . $date->toDateString() . "-" . $hora . ".png";
            file_put_contents($filepath,$data);
            //obtengo el nombre del archivo creado
            $nombre_firma = "f-" . Auth::id() . "f-" . $date->toDateString() . "-" . $hora . ".png";
            //verifico si el doctor tiene firma anteriormente
            if (isset($doctor)){
                // si tiene elimino la firma anterior
                if(isset($doctor->imagen_firma) && $doctor->imagen_firma != ""){
                    File::delete('public/imagenes/sistema/f/'.$doctor->imagen_firma);
                }
                //actualizo la nueva imagen del doctor
                DB::table('users')
                ->where('id', Auth::id())
                ->update([
                    'imagen_firma' => $nombre_firma
                ]);
                $doctor->imagen_firma = $nombre_firma;
            }
        }

        if(isset($request->id_paciente)){

            $id_paciente = $request->id_paciente;
            DB::table('pacientes')
            ->where('id_paciente',$id_paciente)
            ->update([
                'nombres_paciente' => $request->nombre,
                'apellidos_paciente' => $request->apellido,
                'id_tipo_identificacion' => $request->tipo_identificacion,
                'identificacion' => $request->identificacion,
                'telefono_paciente' => $request->telefono_actual,
                'correo_paciente' => $request->correo_actual,
                'lugar_residencia' => $request->residencia,
                'direccion_paciente' => $request->direccion_actual,
                'genero' => $request->genero,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'estado_civil' => $request->estado_civil,
                'ocupacion_actual' => $request->ocupacion,
                'motivo_consulta' => $request->motivo_consulta,
                'ultimo_examen' => Carbon::now(),
                'responsable' => $request->responsable,
                'telefono_responsable' => $request->telefono_responsable,
                'parentesco_responsable' => $request->parentesco_responsable,
                'acompanante' => $request->acompanante,
                'telefono_acompanante' => $request->telefono_acompanante,
                'afiliacion_salud' => $request->afiliacion_salud
            ]);
        }else{

            $existe_paciente = DB::table('pacientes')
            ->where('id_tipo_identificacion','=',$request->tipo_identificacion)
            ->where('identificacion','=',$request->identificacion)
            ->first();

            if(isset($existe_paciente)){

                    $id_paciente = $existe_paciente->id_paciente;
            }else{

                $id_paciente = DB::table('pacientes')->insertGetId([
                    'nombres_paciente' => $request->nombre,
                    'apellidos_paciente' => $request->apellido,
                    'id_tipo_identificacion' => $request->tipo_identificacion,
                    'identificacion' => $request->identificacion,
                    'telefono_paciente' => $request->telefono_actual,
                    'correo_paciente' => $request->correo_actual,
                    'lugar_residencia' => $request->residencia,
                    'direccion_paciente' => $request->direccion_actual,
                    'genero' => $request->genero,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'estado_civil' => $request->estado_civil,
                    'ocupacion_actual' => $request->ocupacion,
                    'motivo_consulta' => $request->motivo_consulta,
                    'ultimo_examen' => Carbon::now(),
                    'responsable' => $request->responsable,
                    'telefono_responsable' => $request->telefono_responsable,
                    'parentesco_responsable' => $request->parentesco_responsable,
                    'acompanante' => $request->acompanante,
                    'telefono_acompanante' => $request->telefono_acompanante,
                    'afiliacion_salud' => $request->afiliacion_salud
                ]);
            }
        }

        $id_examen = DB::table('examenes')->insertGetId([
            'id_paciente' => $id_paciente,
            'id_doctor' => Auth::id(),
            'id_sucursal' => $request->sucursal,
            'telefono_actual' => $request->telefono_actual,
            'correo_actual' => $request->correo_actual,
            'lugar_residencia' => $request->residencia,
            'direccion_actual' => $request->direccion_actual,
            'estado_civil' => $request->estado_civil,
            'ocupacion_actual' => $request->ocupacion,
            'motivo_consulta' => $request->motivo_consulta,
            'fecha_ultimo_examen' => $request->fecha_ultimo_examen,
            'afiliacion_salud' => $request->afiliacion_salud,
            'responsable' => $request->responsable,
            'telefono_responsable' => $request->telefono_responsable,
            'parentesco_responsable' => $request->parentesco_responsable,
            'acompanante' => $request->acompanante,
            'telefono_acompanante' => $request->telefono_acompanante,
            'lensometria_esfera_d' => $request->lensometria_esfera_d,
            'lensometria_esfera_i' => $request->lensometria_esfera_i,
            'lensometria_cilindro_d' => $request->lensometria_cilindro_d,
            'lensometria_cilindro_i' => $request->lensometria_cilindro_i,
            'lensometria_eje_d' => $request->lensometria_eje_d,
            'lensometria_eje_i' => $request->lensometria_eje_i,
            'lensometria_adicion_d' => $request->lensometria_adicion_d,
            'lensometria_adicion_i' => $request->lensometria_adicion_i,
            'refraccion_esfera_d' => $request->refraccion_esfera_d,
            'refraccion_esfera_i' => $request->refraccion_esfera_i,
            'refraccion_cilindro_d' => $request->refraccion_cilindro_d,
            'refraccion_cilindro_i' => $request->refraccion_cilindro_i,
            'refraccion_eje_d' => $request->refraccion_eje_d,
            'refraccion_eje_i' => $request->refraccion_eje_i,
            'refraccion_av_d' => $request->refraccion_av_d,
            'refraccion_av_i' => $request->refraccion_av_i,
            'av_sin_correccion_lejana_d' => $request->av_sin_correccion_lejana_d,
            'av_sin_correccion_lejana_i' => $request->av_sin_correccion_lejana_i,
            'av_sin_correccion_proxima_d' => $request->av_sin_correccion_proxima_d,
            'av_sin_correccion_proxima_i' => $request->av_sin_correccion_proxima_i,
            'av_con_correccion_lejana_d' => $request->av_con_correccion_lejana_d,
            'av_con_correccion_lejana_i' => $request->av_con_correccion_lejana_i,
            'av_con_correccion_proxima_d' => $request->av_con_correccion_proxima_d,
            'av_con_correccion_proxima_i' => $request->av_con_correccion_proxima_i,
            'subjetivo_esfera_d' => $request->subjetivo_esfera_d,
            'subjetivo_esfera_i' => $request->subjetivo_esfera_i,
            'subjetivo_cilindro_d' => $request->subjetivo_cilindro_d,
            'subjetivo_cilindro_i' => $request->subjetivo_cilindro_i,
            'subjetivo_eje_d' => $request->subjetivo_eje_d,
            'subjetivo_eje_i' => $request->subjetivo_eje_i,
            'subjetivo_av_d' => $request->subjetivo_av_d,
            'subjetivo_av_i' => $request->subjetivo_av_i,
            'queratometria_d' => $request->queratometria_d,
            'queratometria_i' => $request->queratometria_i,
            'oftalmoscopia_d' => $request->oftalmoscopia_d,
            'oftalmoscopia_i' => $request->oftalmoscopia_i,
            'biomicroscopia_d' => $request->biomicroscopia_d,
            'biomicroscopia_i' => $request->biomicroscopia_i,
            'estereopsis' => $request->estereopsis,
            'test_color' => $request->test_color,
            'cover_test_lejana' => $request->cover_test_lejana,
            'cover_test_proxima' => $request->cover_test_proxima,
            'formula_esfera_d' => $request->formula_esfera_d,
            'formula_esfera_i' => $request->formula_esfera_i,
            'formula_cilindro_d' => $request->formula_cilindro_d,
            'formula_cilindro_i' => $request->formula_cilindro_i,
            'formula_eje_d' => $request->formula_eje_d,
            'formula_eje_i' => $request->formula_eje_i,
            'formula_adicion_d' => $request->formula_adicion_d,
            'formula_adicion_i' => $request->formula_adicion_i,
            'formula_av_d' => $request->formula_av_d,
            'formula_av_i' => $request->formula_av_i,
            'dnp_d' => $request->dnp_d,
            'dnp_i' => $request->dnp_i,
            'dp' => $request->dp,
            'observaciones' => $request->observaciones,
            'uso' => $request->uso,
            'ppc' => $request->ppc,
            'tratamiento' => $request->tratamiento,
            'json_recomendacion_lente' => $request->recomendaciones_lente,
            'fecha_examen' => Carbon::now(),
            'estado_examen' => 'activo',
            'firmado' => 1
        ]);

        $string_diagnosticos = "";
        if(isset($request->diagnostico)){
            foreach ($request->diagnostico as $diagnostico) {
                $aux_nombre_diagnostico = DB::table('diagnosticos')
                ->where('id_diagnostico','=',$diagnostico)
                ->first();
                if(isset($aux_nombre_diagnostico)){
                    $string_diagnosticos .= "," . $aux_nombre_diagnostico->nombre_diagnostico;
                }
                DB::table('diagnosticos_pacientes_examen')->insert([
                    'id_examen' => $id_examen,
                    'id_diagnostico' => $diagnostico,
                    'estado_diagnostico_paciente' => 'activo'
                ]);
            }
        }

        $string_diagnosticos_familiares = "";
        if(isset($request->familiares)){
            foreach ($request->familiares as $familiares) {
                $aux_nombre_diagnostico = DB::table('diagnosticos')
                ->where('id_diagnostico','=',$familiares)
                ->first();
                if(isset($aux_nombre_diagnostico)){
                    $string_diagnosticos_familiares .= "," . $aux_nombre_diagnostico->nombre_diagnostico;
                }
                DB::table('diagnosticos_familiares_examen')->insert([
                    'id_examen' => $id_examen,
                    'id_diagnostico' => $familiares,
                    'estado_diagnostico_familiar' => 'activo'
                ]);
            }
        }

        $string_cirugias = "";
        if(isset($request->cirugias_oculares)){
            $cirugias_paciente = DB::table('cirugias_pacientes')
            ->where('cirugias_pacientes.id_paciente','=',$id_paciente)
            ->where('cirugias_pacientes.estado_cirugia_paciente','=','activo')
            ->leftJoin('cirugias','cirugias.id_cirugia','=','cirugias_pacientes.id_cirugia')
            ->where('cirugias.estado_cirugia','=','activo')
            ->get();
            foreach ($request->cirugias_oculares as $cirugias) {
                $encontro_cirugia = false;
                foreach ($cirugias_paciente as $cirugia_paciente) {
                    if ($cirugias == $cirugia_paciente->id_cirugia) {
                        $encontro_cirugia = true;
                        break;
                    }
                }
                if ($encontro_cirugia == false) {
                    DB::table('cirugias_pacientes')->insert([
                        'id_paciente' => $id_paciente,
                        'id_cirugia' => $cirugias,
                        'estado_cirugia_paciente' => 'activo'
                    ]);
                }
                $aux_nombre_cirugia = DB::table('cirugias')
                ->where('id_cirugia','=',$cirugias)
                ->first();
                if(isset($aux_nombre_cirugia)){
                    $string_cirugias .= "," . $aux_nombre_cirugia->nombre_cirugia;
                }
            }
        }

        $cliente_appweb = DB::table('cliente_appweb')
        ->where('estado_cliente_appweb','=','activo')
        ->first();

        $examen_creado = DB::table('examenes')
        ->where('id_examen','=',$id_examen)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','examenes.id_sucursal')
        ->leftJoin('pacientes','pacientes.id_paciente','=','examenes.id_paciente')
        ->first();

        $date_nacimiento = Carbon::parse($request->fecha_nacimiento);
        $edad =\Carbon\Carbon::parse($date_nacimiento)->age;


        $data = [
            'examen' => $examen_creado,
            'diagnosticos' => $string_diagnosticos,
            'diagnosticos_familiares' => $string_diagnosticos_familiares,
            'cirugias'=> $string_cirugias,
            'cliente_appweb' => $cliente_appweb,
            'imagen_firma' => "imagen-firma-" . Auth::id() . ".png",
            'edad' => $edad,
            'doctor' => $doctor
        ];

        $pdf = \PDF::loadView('examenes.examen_dompdf',$data)
        ->setPaper('a4')
        ->save(storage_path('examenes/') . 'examen-' . $id_examen . '.pdf');

        File::delete('public/imagenes/sistema/f/imagen-firma-' . Auth::id() . '.png');
        
        return redirect()->route('examenes.ver', ['ver'=>$id_examen]);

    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::MOSTRAR HISTORIA:::::::::::::::::::::::::::::::::::::::::::::*/


        public function historia($id_examen_encriptado){

            $id_examen = Crypt::decryptString($id_examen_encriptado);
            $filename = "examen-" . $id_examen . ".pdf";
            $path = storage_path('examenes/'.$filename);

            if ( file_exists ( storage_path('examenes/'.$filename) ) ) {
                return response()->file($path);
            } else {
                return redirect()->route('examenes.lista', ['estatus'=>"no_existe"]);
            }

        }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::AUTENTICAR(DESHABILITADA JOSEM):::::::::::::::::::::::::::::::::::::::*/


    public function validar($id_examen){

        dd("AUTENTICACION DESHABILITADA");

    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::ACTUALIZAR(DESHABILITADA JOSEM):::::::::::::::::::::::::::::::::::::::*/


    public function actualizar(Request $request, $id_examen){

        $pdf = $request->pdf;

        DB::table('examenes')
        ->where([['id_examen',$id_examen], ['estado_examen','activo'],['firmado', '!=', 1]])
        ->update([
            'firmado' => 1
        ]);

        $nombre_examen = 'examen-'.$id_examen.'.pdf';
        $request->pdf->move('public/pdf/',$nombre_examen);

        return "Firmado";
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::PRUEBA EXAMEN::::::::::::::::::::::::::::::::::::::::::::::*/


    public function prueba(Request $request){
        dd("PRUEBA DOM PDF");
        //PRUEBA DomPDF a STORAGE
        // dd($request->root());
        $data = [
            'titulo' => 'Titulo enviado a Dom PDF'
        ];
        $pdf = \PDF::loadView('examenes.examen_dompdf',$data)
        ->setPaper('a4')
        ->save(storage_path('examenes/') . 'examenStorage.pdf');

        //PRUEBA OBTENER DE STORAGE
        // $pathtoFile = public_path().'/../storage/app/public/'.'archivoPrueba.pdf';
        // return response()->download($pathtoFile);
        // return response()->file($pathtoFile);

        $filename = 'examenStorage.pdf';
        $path = storage_path('examenes/'.$filename);

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);

        return view('examenes.prueba',compact('estatus','laboratorios','tipos_lentes','modelos'));

    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::MARCAS DEL LABORATORIO::::::::::::::::::::::::::::::::::::::::::*/


    public function laboratorioMarcas(Request $request){//AJAX

        $marcas = DB::table('marcas_laboratorios')
        ->where('id_laboratorio','=',$request->id_laboratorio)
        ->where('estado_marca_laboratorio','=','activo')
        ->leftJoin('marcas','marcas.id_marca','=','marcas_laboratorios.id_marca')
        ->get();

        return $marcas;
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::MODELOS DEL LABORATORIO:::::::::::::::::::::::::::::::::::::::::*/


    public function laboratorioModelos(Request $request){//AJAX

        $modelos = DB::table('modelos')
        ->where('id_marca','=',$request->id_marca)
        ->where('estado_modelo','=','activo')
        ->get();

        return $modelos;
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::LISTA DE EXAMENES::::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $examenes = DB::table('examenes')
        ->where([['estado_examen','activo'],['tipo_examen','interno'], ['firmado','1']])
        ->leftJoin('pacientes','pacientes.id_paciente','=','examenes.id_paciente')
        ->leftJoin('users','users.id','=','examenes.id_doctor')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','examenes.id_sucursal')
        ->orderBy('id_examen','desc')
        ->select('pacientes.nombres_paciente','pacientes.apellidos_paciente','pacientes.identificacion','examenes.id_examen','examenes.fecha_examen','sucursals.nombre_sucursal')
        ->get();

        return view('examenes.lista',compact('examenes','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::VER EXAMEN::::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){


        $cliente_appweb = DB::table('cliente_appweb')
        ->where('estado_cliente_appweb','=','activo')
        ->first();

        if (!isset($cliente_appweb)){
            dd("Debe crear el Cliente SAS, Contactar App Web");
        }

        $examen = DB::table('examenes')
        ->where('estado_examen','=','activo')
        ->where('id_examen','=',$request->ver)
        //->leftJoin('pacientes','pacientes.id_paciente','=','examenes.id_paciente')
        ->leftJoin('users','users.id','=','examenes.id_doctor')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','examenes.id_sucursal')
        ->first();

        if (isset($examen)) {

            $examen->numero_examen = str_pad($examen->id_examen, 7, "0", STR_PAD_LEFT);

            $paciente = DB::table('pacientes')
            ->where('id_paciente','=',$examen->id_paciente)
            ->leftJoin('tipos_identificacion','tipos_identificacion.id_tipo_identificacion','=','pacientes.id_tipo_identificacion')
            ->first();

            if (isset($paciente)) {
                $examen->id_tipo_identificacion_paciente = $paciente->id_tipo_identificacion;
                $examen->nombre_tipo_identificacion_paciente = $paciente->nombre_tipo_identificacion;
                $examen->identificacion_paciente = $paciente->identificacion;
                $examen->nombres_paciente = $paciente->nombres_paciente;
                $examen->apellidos_paciente = $paciente->apellidos_paciente;
                $examen->telefono_paciente = $paciente->telefono_paciente;
                $examen->genero_paciente = $paciente->genero;
                $examen->nacimiento_paciente = $paciente->fecha_nacimiento;

                $date = Carbon::parse($paciente->fecha_nacimiento);
                $edad =\Carbon\Carbon::parse($date)->age;
                $examen->edad_paciente = $edad;
            }

            $diagnosticos = DB::table('diagnosticos_pacientes_examen')
            ->where('id_examen','=',$request->ver)
            ->leftJoin('diagnosticos','diagnosticos.id_diagnostico','=','diagnosticos_pacientes_examen.id_diagnostico')
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
            $examen->diagnosticos = $diagnosticos_pacientes;

            $cirugias = DB::table('cirugias_pacientes')
            ->where('id_paciente','=',$examen->id_paciente)
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
            $examen->cirugias = $cirugias_pacientes;
        }
        return view('examenes.ver',compact('examen','cliente_appweb'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::EDITAR EXAMEN::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        dd("funcion Editar No Disponible");
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function examenes_no_validados(){

        $examenes = DB::table('examenes')
        ->leftJoin('pacientes','pacientes.id_paciente','=','examenes.id_paciente')
        ->leftJoin('users','users.id','examenes.id_doctor')
        ->leftJoin('sucursals','sucursals.id_sucursal','examenes.id_sucursal')
        ->orderBy('id_examen','desc')
        ->where([['examenes.estado_examen','activo'],['examenes.tipo_examen','interno'], ['examenes.firmado', '!=', '1']])
        ->get();

        return view('examenes.no_validados', ['examenes'=>$examenes]);
    }
}
