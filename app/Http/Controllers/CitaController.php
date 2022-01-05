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

class CitaController extends Controller
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
/*::::::::::::::::::::::::::::::::::::::::::::CREAR CITAS::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function crear(Request $request){
            
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

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

        $tipos_identificacion = DB::table('tipos_identificacion')
        ->where('estado_tipo_identificacion','activo')
        ->get();

        return view('citas.crear',compact('usuarios_sucursales','tipos_identificacion','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::GUARDAR CITAS::::::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        $estatus = "exito";

        $fecha_cita = Carbon::parse($request->fecha_cita);
        $hora_cita = Carbon::parse($request->hora_cita);

        if($hora_cita<Carbon::parse('9:00')){
            // abort(response('Hora de cita debe ser posterior a las 9:00', 422));
            $estatus = "error_cita_posterior";
        }
        if($fecha_cita->dayOfWeek == 0){
            if($hora_cita>Carbon::parse('17:00')){
                // abort(response('Hora de cita debe ser anterior a las 17:00 los Domingos', 422));
                $estatus = "error_cita_anterior_domingos";
            }
        }
        else{
            if($hora_cita>Carbon::parse('19:00')){
                // abort(response('Hora de cita debe ser anterior a las 19:00 de Lunes a Sabado', 422));
                $estatus = "error_cita_posterior_l_s";
            }
        }

        // dd($request);

        if($estatus == "exito"){
            $paciente = DB::table('pacientes')
            ->where('id_tipo_identificacion', $request->tipo_identificacion)
            ->where('identificacion', $request->identificacion)
            ->first();

            if(isset($paciente)){
                $id_paciente = $paciente->id_paciente;
            }else{
                $id_paciente = DB::table('pacientes')->insertGetId([
                    'nombres_paciente' => $request->nombres_paciente,
                    'apellidos_paciente' => $request->apellidos_paciente,
                    'id_tipo_identificacion' => $request->tipo_identificacion,
                    'identificacion' => $request->identificacion,
                    'telefono_paciente' => $request->telefono_paciente,
                    'direccion_paciente' => $request->direccion_paciente,
                    'correo_paciente' => $request->correo_paciente,
                    'genero' => $request->genero,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'estado_paciente' => 'activo'
                ]);
            }

            $id_cita = DB::table('citas')->insertGetId([
                'id_paciente'=>$id_paciente,
                'id_sucursal'=>$request->sucursal,
                'fecha_cita'=> $request->fecha_cita,
                'hora_cita'=> $request->hora_cita,
                'estado_cita'=> 'activo',
                'fecha_registro' => Carbon::now(),
                'tipo_cita' => 'externa'
            ]);
        }

        return redirect()->route('citas.crear',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE CITAS::::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(){

        $citas = DB::table('citas')
        ->where('estado_cita','activo')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','citas.id_sucursal')
        ->leftJoin('pacientes','pacientes.id_paciente','=','citas.id_paciente')
        ->leftJoin('users','users.id','=','citas.usuario_registro')
        ->orderBy('fecha_cita','desc')
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

        $ahora = Carbon::now();
        // dd($ahora);

        $citas_disponibles = [];
        foreach ($citas as $cita) {
            foreach ($usuarios_sucursales as $usuario_sucursal) {
                if ($cita->id_sucursal == $usuario_sucursal->id_sucursal) {
                    if($cita->asistencia_cita == null || $cita->asistencia_cita == ""){
                        $fecha_cita = $cita->fecha_cita . " " . $cita->hora_cita;
                        if($fecha_cita > $ahora){
                            $cita->asistencia_cita = "Espera";
                        }else{
                            $cita->asistencia_cita = "Vencida";
                        }
                    }
                    array_push($citas_disponibles, $cita);
                    break;
                }
            }
        }
        $citas = $citas_disponibles;
        
        return view('citas.lista',compact('citas'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::LISTA DE CITAS::::::::::::::::::::::::::::::::::::::::::::::*/


    public function listaHoy(){

        $date = Carbon::now();
        $hoy = $date->toDateString();

        $citas = DB::table('citas')
        ->where('estado_cita','activo')
        ->where('fecha_cita','=',$hoy)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','citas.id_sucursal')
        ->leftJoin('pacientes','pacientes.id_paciente','=','citas.id_paciente')
        ->leftJoin('users','users.id','=','citas.usuario_registro')
        ->orderBy('hora_cita','asc')
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

        $ahora = Carbon::now();

        $citas_disponibles = [];
        foreach ($citas as $cita) {
            foreach ($usuarios_sucursales as $usuario_sucursal) {
                if ($cita->id_sucursal == $usuario_sucursal->id_sucursal) {
                    if($cita->asistencia_cita == null || $cita->asistencia_cita == ""){
                        $fecha_cita = $cita->fecha_cita . " " . $cita->hora_cita;
                        if($fecha_cita > $ahora){
                            $cita->asistencia_cita = "Espera";
                        }else{
                            $cita->asistencia_cita = "Vencida";
                        }
                    }
                    array_push($citas_disponibles, $cita);
                    break;
                }
            }
        }
        $citas = $citas_disponibles;
        
        return view('citas.listaHoy',compact('citas','hoy'));
    }


    public function agregar(){
        $tipos_identificacion = DB::table('tipos_identificacion')
        ->where('estado_tipo_identificacion','activo')
        ->get();
        $sucursales = DB::table('sucursals')->where('estado_sucursal', 'activo')->get();
        return view('citas.agregar', ['tipos_identificacion'=>$tipos_identificacion, 'sucursales'=>$sucursales]);
    }

    public function insertar(Request $request){
        $request->validate([
            'nombres_paciente'=>['required', 'max:50'],
            'apellidos_paciente'=>['required', 'max:50'],
            'id_tipo_identificacion'=> ['required','integer'],
            'identificacion'=> ['required','integer'],
            'telefono_paciente'=> ['required', 'digits_between:10,13'],
            'direccion_paciente' => ['max:100'],
            'correo_paciente' => ['max:100'],
            'genero'=> ['required', 'max:50'],
            'fecha_nacimiento'=> ['required','date'],
            'id_sucursal'=>['required', 'integer'],
            'fecha_cita'=>['required', 'date', 'after:'.Carbon::now()],
            'hora_cita'=>['required'],
        ]);
        if(!in_array($request->id_sucursal, $this->sucursales_permitidas())){
            abort(403);
        }

        $fecha_cita = Carbon::parse($request->fecha_cita);
        $hora_cita = Carbon::parse($request->hora_cita);

        if($hora_cita<Carbon::parse('9:00')){
            return back()->withErrors(['Hora de cita debe ser posterior a las 9:00']);
        }
        if($fecha_cita->dayOfWeek == 0){
            if($hora_cita>Carbon::parse('17:00')){
                return back()->withErrors(['Hora de cita debe ser anterior a las 17:00 los Domingos']);
            }
        }
        else{
            if($hora_cita>Carbon::parse('19:00')){
                return back()->withErrors(['Hora de cita debe ser anterior a las 19:00 de Lunes a Sabado']);
            }
        }

        $data=[
            'id_sucursal'=>$request->id_sucursal,
            'fecha_cita'=>$fecha_cita,
            'hora_cita'=>$hora_cita,
            'estado_cita'=>'activo',
            'asistencia_cita'=>'pendiente'
        ];
        $data['id_paciente'] = $this->verificar_paciente($request);
        DB::table('citas')->insert($data);
        return redirect()->route('citas.index');
    }

    public function editar($id_cita){
        $tipos_identificacion = DB::table('tipos_identificacion')
        ->where('estado_tipo_identificacion','activo')
        ->get();

        $cita = DB::table('citas')
        ->join('pacientes', 'pacientes.id_paciente', 'citas.id_paciente')
        ->join('tipos_identificacion', 'tipos_identificacion.id_tipo_identificacion', 'pacientes.id_tipo_identificacion')
        ->where([
            ['citas.id_cita', $id_cita],
            ['citas.estado_cita', 'activo']])
        ->whereBetween('citas.id_sucursal', $this->sucursales_permitidas())->first();
        return view('citas.editar', ['tipos_identificacion'=>$tipos_identificacion, 'cita'=>$cita]);
    }

    public function actualizar(Request $request, $id_cita){
        $request->validate([
            'nombres_paciente'=>['required', 'max:50'],
            'apellidos_paciente'=>['required', 'max:50'],
            'id_tipo_identificacion'=> ['required','integer'],
            'identificacion'=> ['required','integer'],
            'telefono_paciente'=> ['required', 'digits_between:10,13'],
            'direccion_paciente' => ['max:100'],
            'correo_paciente' => ['max:100'],
            'genero'=> ['required', 'max:50'],
            'fecha_nacimiento'=> ['required','date'],
            'fecha_cita'=>['required', 'date', 'after:'.Carbon::now()],
            'hora_cita'=>['required'],
            'asistencia_cita'=>['required']
        ]);
        
        $cita = DB::table('citas')
        ->where('id_cita', $id_cita)
        ->whereBetween('citas.id_sucursal', $this->sucursales_permitidas())
        ->first();
        if(empty($cita)){
            abort(403);
        }
        
        $fecha_cita = Carbon::parse($request->fecha_cita);
        $hora_cita = Carbon::parse($request->hora_cita);
        if($hora_cita<Carbon::parse('9:00')){
            return back()->withErrors(['Hora de cita debe ser posterior a las 9:00']);
        }
        if($fecha_cita->dayOfWeek == 0){
            if($hora_cita>Carbon::parse('17:00')){
                return back()->withErrors(['Hora de cita debe ser anterior a las 17:00 los Domingos']);
            }
        }
        else{
            if($hora_cita>Carbon::parse('19:00')){
                return back()->withErrors(['Hora de cita debe ser anterior a las 19:00 de Lunes a Sabado']);
            }
        }
        
        $data=[
            'fecha_cita'=>$fecha_cita,
            'hora_cita'=>$hora_cita,
            'asistencia_cita'=>$request->asistencia_cita
        ];
        $data['id_paciente'] = $this->verificar_paciente($request);

        DB::table('citas')
        ->where('id_cita', $id_cita)
        ->whereBetween('citas.id_sucursal', $this->sucursales_permitidas())
        ->update($data);
        return redirect()->route('citas.index');
    }
    
    public function crear_externa(Request $request){

        $estatus = "exito";
        
        $fecha_cita = Carbon::parse($request->fecha_cita);
        $hora_cita = Carbon::parse($request->hora_cita);

        if($hora_cita<Carbon::parse('9:00')){
            // abort(response('Hora de cita debe ser posterior a las 9:00', 422));
            $estatus = "error_cita_posterior";
        }
        if($fecha_cita->dayOfWeek == 0){
            if($hora_cita>Carbon::parse('17:00')){
                // abort(response('Hora de cita debe ser anterior a las 17:00 los Domingos', 422));
                $estatus = "error_cita_anterior_domingos";
            }
        }
        else{
            if($hora_cita>Carbon::parse('19:00')){
                // abort(response('Hora de cita debe ser anterior a las 19:00 de Lunes a Sabado', 422));
                $estatus = "error_cita_posterior_l_s";
            }
        }

        if($estatus == "exito"){
            $paciente = DB::table('pacientes')
            ->where('id_tipo_identificacion', $request->tipo_identificacion)
            ->where('identificacion', $request->identificacion)
            ->first();

            if(isset($paciente)){
                $id_paciente = $paciente->id_paciente;
            }else{
                $id_paciente = DB::table('pacientes')->insertGetId([
                    'nombres_paciente' => $request->nombres_paciente,
                    'apellidos_paciente' => $request->apellidos_paciente,
                    'id_tipo_identificacion' => $request->tipo_identificacion,
                    'identificacion' => $request->identificacion,
                    'telefono_paciente' => $request->telefono_paciente,
                    'direccion_paciente' => $request->direccion_paciente,
                    'correo_paciente' => $request->correo_paciente,
                    'genero' => $request->genero,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'estado_paciente' => 'activo'
                ]);
            }

            $id_cita = DB::table('citas')->insertGetId([
                'id_paciente'=>$id_paciente,
                'id_sucursal'=>$request->sucursal,
                'fecha_cita'=> $request->fecha_cita,
                'hora_cita'=> $request->hora_cita,
                'estado_cita'=> 'activo',
                'fecha_registro' => Carbon::now(),
                'tipo_cita' => 'externa'
            ]);
        }

        return $estatus;
    }
}
