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

class CotizacionesController extends Controller
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

        $configuracion_cotizacion = DB::table('configuracion_cotizaciones')
        ->first();

        $id_user = Auth::id();

        $usuario_vendedor = DB::table('users')
        ->where('id','=',Auth::id())
        ->first();

        $roles = DB::table('model_has_roles')
        ->where('model_type','=','App\User')
        ->where('model_id','=',$id_user)
        ->leftJoin('roles','roles.id','=','model_has_roles.role_id')
        // ->select('role_id')
        ->get();

        $cambia_vendedor = false;
        $todas = false;
        $coloca_nombre = true;
        foreach ($roles as $rol) {
            if ($rol->role_id == 1 || $rol->role_id == 2) {
                $todas = true;
            }
            if ($rol->role_id == 1 || $rol->role_id == 2 || $rol->role_id == 5 || $rol->name == "Punto De Venta General") {
                $cambia_vendedor = true;
            }
            if ($rol->name == "Punto De Venta General") {
                $coloca_nombre = false;
            }
        }

        $usuario_vendedor->cambia_vendedor = $cambia_vendedor;
        $usuario_vendedor->coloca_nombre = $coloca_nombre;

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

        if(isset($configuracion_cotizacion)){

            $clasificaciones = DB::table('clasificacion_tipo_productos')
            ->where('estado_clasificacion_tipo_producto','=','activo')
            ->where('id_tipo_producto','=',$configuracion_cotizacion->id_tipo_producto_lentes)
            ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','clasificacion_tipo_productos.id_clasificacion')
            ->get();

            $especificaciones = DB::table('marcas_laboratorios')
            ->where('estado_marca_laboratorio','=','activo')
            ->leftJoin('modelos','modelos.id_marca','=','marcas_laboratorios.id_marca')
            ->leftJoin('productos','productos.id_modelo','=','modelos.id_modelo')
            ->leftJoin('producto_especificaciones','producto_especificaciones.id_producto','=','productos.id_producto')
            ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
            ->select('especificaciones.id_especificacion','especificaciones.nombre_especificacion','especificaciones.id_clasificacion')
            ->distinct()
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

        return view('cotizaciones.crear',compact('usuarios_sucursales','estatus','tipos_identificacion','clasificaciones','marcas_laboratorios','configuracion_cotizacion','usuario_vendedor'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::BUSCAR LENTES::::::::::::::::::::::::::::::::::::::::::::*/


    public function lentes(Request $request){//AJAX

        $esp = [];

        $request->lentes = json_decode($request->lentes);

        foreach ($request->lentes as $especificacion_lente) {
                if($especificacion_lente->id_especificacion != "" && $especificacion_lente->id_especificacion != null){

                        if($request->id_modelo != null && $request->id_modelo != ""){

                            $productos = DB::table('marcas_laboratorios')
                            ->where('estado_marca_laboratorio','=','activo')
                            ->leftJoin('modelos','modelos.id_marca','=','marcas_laboratorios.id_marca')
                            ->where('modelos.id_modelo','=',$request->id_modelo)
                            ->leftJoin('productos','productos.id_modelo','=','modelos.id_modelo')
                            ->leftJoin('producto_especificaciones','producto_especificaciones.id_producto','=','productos.id_producto')
                            ->where('producto_especificaciones.id_especificacion','=',$especificacion_lente->id_especificacion)
                            ->select('producto_especificaciones.id_producto')
                            ->get();

                        }else{
                            $productos = DB::table('marcas_laboratorios')
                            ->where('estado_marca_laboratorio','=','activo')
                            ->leftJoin('modelos','modelos.id_marca','=','marcas_laboratorios.id_marca')
                            ->leftJoin('productos','productos.id_modelo','=','modelos.id_modelo')
                            ->leftJoin('producto_especificaciones','producto_especificaciones.id_producto','=','productos.id_producto')
                            ->where('producto_especificaciones.id_especificacion','=',$especificacion_lente->id_especificacion)
                            ->select('producto_especificaciones.id_producto')
                            ->get();
                        }
                        
                        foreach ($productos as $producto) {

                                $especificaciones = DB::table('marcas_laboratorios')
                                ->where('estado_marca_laboratorio','=','activo')
                                ->leftJoin('modelos','modelos.id_marca','=','marcas_laboratorios.id_marca')
                                ->leftJoin('productos','productos.id_modelo','=','modelos.id_modelo')
                                ->leftJoin('producto_especificaciones','producto_especificaciones.id_producto','=','productos.id_producto')
                                ->where('producto_especificaciones.id_producto','=',$producto->id_producto)
                                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                                ->select('producto_especificaciones.id_producto','especificaciones.id_especificacion','especificaciones.nombre_especificacion','especificaciones.id_clasificacion')
                                ->distinct()
                                ->get();

                                $valido = true;
                                $anteriores = true;
                                
                                foreach ($request->lentes as $json_lente){
                                        $encontrado = false;
                                        if($json_lente->id_especificacion != "" && $json_lente->id_especificacion != null){
                                                if ($anteriores == true) {
                                                        foreach ($especificaciones as $especificacion) {
                                                                if($json_lente->id_especificacion == $especificacion->id_especificacion){
                                                                        $encontrado = true;
                                                                }
                                                        }
                                                        if($encontrado == false){
                                                            $anteriores = false;
                                                        }
                                                }
                                        }                                                
                                }
                                if($anteriores == true){
                                        foreach ($especificaciones as $especificacion) {
                                            $valores =[
                                                    'id_especificacion' => $especificacion->id_especificacion,
                                                    'id_clasificacion' => $especificacion->id_clasificacion,
                                                    'nombre_especificacion' => $especificacion->nombre_especificacion
                                                ];
                                                $valores = json_encode($valores);
                                                array_push($esp, $valores);
                                        }
                                }
                        }
                }
        }

        $esp = array_unique($esp);
        $resultado = [];

        foreach ($esp as $es) {
            array_push($resultado, $es);
        }

        return ($resultado);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::BUSCAR LENTE CON LAS 6 ESPECIFICACIONES::::::::::::::::::::::::::::*/


    public function buscarLente(Request $request){//AJAX


        $esp = [];

        foreach ($request->lentes as $especificacion_lente) {

                if($especificacion_lente != "" && $especificacion_lente != null){
                        
                        if($request->id_modelo != null && $request->id_modelo != ""){

                            $productos = DB::table('producto_especificaciones')
                            ->leftJoin('productos','productos.id_producto','=','producto_especificaciones.id_producto')
                            ->where('productos.id_modelo','=',$request->id_modelo)
                            ->where('producto_especificaciones.id_especificacion','=',$especificacion_lente)
                            ->select('producto_especificaciones.id_producto')
                            ->get();
                        }else{
                            $productos = DB::table('producto_especificaciones')
                            ->where('producto_especificaciones.id_especificacion','=',$especificacion_lente)
                            ->select('producto_especificaciones.id_producto')
                            ->get(); 
                        }

                        foreach ($productos as $producto) {

                                $especificaciones = DB::table('marcas_laboratorios')
                                ->where('estado_marca_laboratorio','=','activo')
                                ->leftJoin('modelos','modelos.id_marca','=','marcas_laboratorios.id_marca')
                                ->leftJoin('productos','productos.id_modelo','=','modelos.id_modelo')
                                ->leftJoin('producto_especificaciones','producto_especificaciones.id_producto','=','productos.id_producto')
                                ->where('producto_especificaciones.id_producto','=',$producto->id_producto)
                                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                                ->select('producto_especificaciones.id_producto','especificaciones.id_especificacion','especificaciones.nombre_especificacion','especificaciones.id_clasificacion')
                                ->distinct()
                                ->get();

                                $valido = true;
                                $anteriores = true;
                                
                                foreach ($request->lentes as $json_lente){
                                        $encontrado = false;
                                        if($json_lente != "" && $json_lente != null){
                                                if ($anteriores == true) {
                                                        foreach ($especificaciones as $especificacion) {
                                                                if($json_lente == $especificacion->id_especificacion){
                                                                        $encontrado = true;
                                                                }
                                                        }
                                                        if($encontrado == false){
                                                            $anteriores = false;
                                                        }
                                                }
                                        }                                                
                                }
                                if($anteriores == true){
                                        foreach ($especificaciones as $especificacion) {
                                            $valores =[
                                                    'id_producto' => $especificacion->id_producto,
                                                    //'id_especificacion' => $especificacion->id_especificacion,
                                                    //'id_clasificacion' => $especificacion->id_clasificacion,
                                                    //'nombre_especificacion' => $especificacion->nombre_especificacion
                                                ];
                                                $valores = json_encode($valores);
                                                array_push($esp, $valores);
                                        }
                                }
                        }
                }
        }

        $esp = array_unique($esp);
        $resultado = [];

        foreach ($esp as $es){
                $buscar = json_decode($es);

                $pr = DB::table('productos')
                ->where('id_producto','=',$buscar->id_producto)
                ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
                ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                ->select('productos.id_producto','marcas.nombre_marca','modelos.nombre_modelo','productos.precio_base','modelos.id_modelo')
                ->first();

                $p = [
                    'id_producto' =>  $pr->id_producto,
                    'precio_base' =>  $pr->precio_base,
                    'nombre_marca' => $pr->nombre_marca,
                    'id_modelo'   =>  $pr->id_modelo,
                    'nombre_modelo' =>$pr->nombre_modelo
                ];
                array_push($resultado, $p);
        }

        return ($resultado);

    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::BUSCAR MODELOS LENTE::::::::::::::::::::::::::::::::::::::::::*/


    public function modelos(Request $request){//AJAX

        $productos = DB::table('productos')
        ->where('id_modelo','=',$request->id_modelo)
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_producto','=','productos.id_producto')
        ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
        ->select('especificaciones.id_especificacion','especificaciones.nombre_especificacion','especificaciones.id_clasificacion')
        ->distinct()
        ->get();

        return ($productos);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::BUSCAR CLIENTE:::::::::::::::::::::::::::::::::::::::::::::*/


    public function cliente(Request $request){//AJAX

        $cliente = DB::table('clientes')
        ->where('id_tipo_identificacion','=',$request->tipo_identificacion)
        ->where('identificacion','=',$request->identificacion)
        ->first();

        $cliente = json_encode($cliente);

        return ($cliente);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::BUSCAR PACIENTE::::::::::::::::::::::::::::::::::::::::::::*/


    public function paciente(Request $request){//AJAX

       
        $paciente = DB::table('pacientes')
        ->where('id_tipo_identificacion','=',$request->tipo_identificacion)
        ->where('identificacion','=',$request->identificacion)
        ->first();

        if (isset($paciente)) {

                $examen = DB::table('examenes')
                ->where('examenes.id_paciente','=',$paciente->id_paciente)
                ->where('examenes.tipo_examen','=','interno')//AGREGUE
                ->orderBy('examenes.id_examen', 'desc')
                ->first();

                if (isset($examen)) {
                        $paciente->examen = $examen;

                        if(isset($examen->json_recomendacion_lente)){
                                $recomendaciones = json_decode($examen->json_recomendacion_lente);
                                foreach ($recomendaciones as $recomendacion) {
                                    $especificacion = DB::table('especificaciones')
                                    ->where('id_especificacion','=',$recomendacion->e)
                                    ->select('nombre_especificacion')
                                    ->first();
                                    $recomendacion->ne = $especificacion->nombre_especificacion;
                                }
                                $examen->recomendaciones_lente = $recomendaciones;
                        }

                        $diagnosticos_antecedentes = DB::table('diagnosticos_pacientes_examen')
                        ->where('diagnosticos_pacientes_examen.id_examen','=',$examen->id_examen)
                        ->where('diagnosticos_pacientes_examen.estado_diagnostico_paciente','=','activo')
                        ->leftJoin('diagnosticos','diagnosticos.id_diagnostico','=','diagnosticos_pacientes_examen.id_diagnostico')
                        ->where('diagnosticos.estado_diagnostico','=','activo')
                        ->get();
                        $paciente->diagnosticos_antecedentes = $diagnosticos_antecedentes;

                        $diagnosticos_familiares = DB::table('diagnosticos_familiares_examen')
                        ->where('diagnosticos_familiares_examen.id_examen','=',$examen->id_examen)
                        ->where('diagnosticos_familiares_examen.estado_diagnostico_familiar','=','activo')
                        ->leftJoin('diagnosticos','diagnosticos.id_diagnostico','=','diagnosticos_familiares_examen.id_diagnostico')
                        ->where('diagnosticos.estado_diagnostico','=','activo')
                        ->get();
                        $paciente->diagnosticos_familiares = $diagnosticos_familiares;

                        $cirugias = DB::table('cirugias_pacientes')
                        ->where('cirugias_pacientes.id_paciente','=',$paciente->id_paciente)
                        ->where('cirugias_pacientes.estado_cirugia_paciente','=','activo')
                        ->leftJoin('cirugias','cirugias.id_cirugia','=','cirugias_pacientes.id_cirugia')
                        ->where('cirugias.estado_cirugia','=','activo')
                        ->get();
                        $paciente->cirugias = $cirugias;
                }
        }

        $paciente = json_encode($paciente);

        return ($paciente);

    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::GUARDAR COTIZACION:::::::::::::::::::::::::::::::::::::::::::::*/

    public function guardar(Request $request){

        $cotizacion = json_decode($request->cotizacion_final);

        // dd($cotizacion->vendedor);

        $valid_sucursal = false;
        $valid_cliente = false;
        $valid_paciente = false;
        $valid_examen = false;
        $valid_lente = false;
        $valid_laboratorio = false;
        //$valid_montura = false;

        if ($cotizacion->sucursal->id_sucursal != null && $cotizacion->sucursal->id_sucursal != "") {
                $valid_sucursal = true;
        }

        if ($cotizacion->cliente->id_cliente != null && $cotizacion->cliente->id_cliente != "") {
                $valid_cliente = true;
        }elseif (
                $cotizacion->cliente->tipo_identificacion != null && $cotizacion->cliente->tipo_identificacion != "" &&
                $cotizacion->cliente->identificacion != null && $cotizacion->cliente->identificacion != "" &&
                $cotizacion->cliente->nombres != null && $cotizacion->cliente->nombres != "" &&
                $cotizacion->cliente->apellidos != null && $cotizacion->cliente->apellidos != ""
        ){
                $valid_cliente = true;
        }

        if ($cotizacion->paciente->id_paciente != null && $cotizacion->paciente->id_paciente != "") {
                $valid_paciente = true;
        }elseif (
                $cotizacion->paciente->tipo_identificacion != null && $cotizacion->paciente->tipo_identificacion != "" &&
                $cotizacion->paciente->identificacion != null && $cotizacion->paciente->identificacion != "" &&
                $cotizacion->paciente->nombres != null && $cotizacion->paciente->nombres != "" &&
                $cotizacion->paciente->apellidos != null && $cotizacion->paciente->apellidos != "" &&
                $cotizacion->paciente->fecha_nacimiento != null && $cotizacion->paciente->fecha_nacimiento != "" &&
                $cotizacion->paciente->genero != null && $cotizacion->paciente->genero != ""
        ){
                $valid_paciente = true;
        }

        if ($cotizacion->examen->id_examen != null && $cotizacion->examen->id_examen != ""){
                $valid_examen = true;
        }elseif(
                $cotizacion->examen->es_d != null && $cotizacion->examen->es_d != "" &&
                $cotizacion->examen->es_i != null && $cotizacion->examen->es_i != "" &&
                $cotizacion->examen->ci_d != null && $cotizacion->examen->ci_d != "" &&
                $cotizacion->examen->ci_i != null && $cotizacion->examen->ci_i != "" &&
                $cotizacion->examen->ej_d != null && $cotizacion->examen->ej_d != "" &&
                $cotizacion->examen->ej_i != null && $cotizacion->examen->ej_i != "" &&
                $cotizacion->examen->dn_d != null && $cotizacion->examen->dn_d != "" &&
                $cotizacion->examen->dn_i != null && $cotizacion->examen->dn_i != "" &&
                $cotizacion->examen->dp_c != null && $cotizacion->examen->dp_c != ""
        ){
                $valid_examen = true;
        }

        if ($cotizacion->lente->id_lente != null && $cotizacion->lente->id_lente != "" && is_numeric($cotizacion->lente->precio_lente)){
               $valid_lente = true;
        }

        $laboratorio = DB::table('productos')
        ->where('productos.id_producto','=',$cotizacion->lente->id_lente)
        ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
        ->leftJoin('marcas_laboratorios','marcas_laboratorios.id_marca','=','modelos.id_marca')
        ->leftJoin('laboratorios','laboratorios.id_laboratorio','=','marcas_laboratorios.id_laboratorio')
        ->select('laboratorios.id_laboratorio','laboratorios.nombre_laboratorio')
        ->first();

        if(isset($laboratorio)){
            $valid_laboratorio = true;
        }

        /*if ($cotizacion->montura->id_montura != null && $cotizacion->montura->id_montura != "" && is_numeric($cotizacion->montura->precio_montura)){
               $valid_montura = true;
        }*/


        if($valid_sucursal == true && $valid_cliente == true && $valid_paciente == true && $valid_examen == true && $valid_examen == true && $valid_lente == true && $valid_laboratorio == true/*&& $valid_montura == true*/){

                $date = Carbon::now();

                if (isset($cotizacion->vendedor)){
                    $id_user = $cotizacion->vendedor;
                } else {
                    $id_user = Auth::id();
                }
                
                // dd("El id usuario es: " . $id_user);

                if($cotizacion->cliente->id_cliente == null || $cotizacion->cliente->id_cliente == ""){//CREO EL CLIENTE SINO LO OBTENGO
                        $id_cliente = DB::table('clientes')->insertGetId([
                            'id_tipo_identificacion' => $cotizacion->cliente->tipo_identificacion,
                            'identificacion' => $cotizacion->cliente->identificacion,
                            'nombres' => $cotizacion->cliente->nombres,
                            'apellidos' => $cotizacion->cliente->apellidos,
                            'telefono' => $cotizacion->cliente->telefono,
                            'email' => $cotizacion->cliente->correo_c
                        ]);
                }else{
                        $id_cliente = $cotizacion->cliente->id_cliente;
                }

                if($cotizacion->paciente->id_paciente == null || $cotizacion->paciente->id_paciente == ""){//CREO EL PACIENTE SINO LO OBTENGO
                        $id_paciente = DB::table('pacientes')->insertGetId([
                            'nombres_paciente' => $cotizacion->paciente->nombres,
                            'apellidos_paciente' => $cotizacion->paciente->apellidos,
                            'id_tipo_identificacion' => $cotizacion->paciente->tipo_identificacion,
                            'identificacion' => $cotizacion->paciente->identificacion,
                            'fecha_nacimiento' => $cotizacion->paciente->fecha_nacimiento,
                            'genero' => $cotizacion->paciente->genero,

                            'telefono_paciente' => $cotizacion->cliente->telefono,//Utilizo los del cliente
                            'correo_paciente' => $cotizacion->cliente->correo_c//Utilizo los del cliente
                        ]);
                }else{
                        $id_paciente = $cotizacion->paciente->id_paciente;
                }

                if ($cotizacion->examen->id_examen == null || $cotizacion->examen->id_examen == "") {//CREO EL EXAMEN EXTERNO SI ES EXTERNO SINO LO OBTENGO
                        $id_examen = DB::table('examenes')->insertGetId([
                            'id_paciente' => $id_paciente,
                            'id_sucursal' => $cotizacion->sucursal->id_sucursal,
                            'formula_esfera_d' => $cotizacion->examen->es_d,
                            'formula_esfera_i' => $cotizacion->examen->es_i,
                            'formula_cilindro_d' => $cotizacion->examen->ci_d,
                            'formula_cilindro_i' => $cotizacion->examen->ci_i,
                            'formula_eje_d' => $cotizacion->examen->ej_d,
                            'formula_eje_i' => $cotizacion->examen->ej_i,
                            'formula_adicion_d' => $cotizacion->examen->ad_d,
                            'formula_adicion_i' => $cotizacion->examen->ad_i,
                            'dnp_d' => $cotizacion->examen->dn_d,
                            'dnp_i' => $cotizacion->examen->dn_i,
                            'dp' => $cotizacion->examen->dp_c,
                            'tipo_examen' => 'externo',
                            'fecha_examen' => $date,
                            'estado_examen' => 'activo'
                        ]);
                }else{
                        $id_examen = $cotizacion->examen->id_examen;
                }

                

                $mensaje = null;

                if($cotizacion->examen->estado_examen == true){//1  YA EXISTIA EL EXAMEN Y SE PUEDE

                        $examenes = DB::table('configuracion_cotizaciones')//OBTENGO LOS EXAMENES
                        ->leftJoin('modelos','modelos.id_tipo_producto','=','configuracion_cotizaciones.id_tipo_producto_examenes')
                        ->leftJoin('productos','productos.id_modelo','=','modelos.id_modelo')
                        ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
                        ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
                        ->select('modelos.nombre_modelo','productos.precio_base','productos.id_producto','tipo_productos.nombre_tipo_producto','marcas.nombre_marca')
                        ->get();

                        $array_permitidos = [];
                        foreach ($examenes as $examen) {
                                if (intval($examen->precio_base) <= intval($cotizacion->montura->precio_montura)) {
                                        array_push($array_permitidos, $examen);
                                }
                        }

                        $mayor = 0;
                        $id_mayor = null;
                        $nombre_mayor = null;
                        foreach ($array_permitidos as $array_permitido) {
                                if(intval($array_permitido->precio_base) >= $mayor){
                                        $mayor = intval($array_permitido->precio_base);
                                        $id_mayor = $array_permitido->id_producto;
                                        $nombre_mayor = $array_permitido->nombre_tipo_producto . " " . $array_permitido->nombre_marca . " " . $array_permitido->nombre_modelo;
                                }
                        }
                        
                        $array_nuevos = [];
                        foreach ($array_permitidos as $array_permitido) {
                                if (intval($array_permitido->precio_base) < $mayor) {
                                        array_push($array_nuevos, $array_permitido);
                                }
                        }
                        $mayor_nuevo = 0;
                        $id_mayor_nuevo = null;
                        $nombre_mayor_nuevo = null;
                        foreach ($array_nuevos as $array_nuevo) {
                                if(intval($array_nuevo->precio_base) > $mayor_nuevo){
                                        $mayor_nuevo = intval($array_nuevo->precio_base);
                                        $id_mayor_nuevo = $array_nuevo->id_producto;
                                        $nombre_mayor_nuevo = $array_nuevo->nombre_tipo_producto . " " . $array_nuevo->nombre_marca . " " . $array_nuevo->nombre_modelo;
                                }
                        }

                        $mayor = $mayor_nuevo;
                        $id_mayor = $id_mayor_nuevo;
                        $nombre_mayor = $nombre_mayor_nuevo;

                        $aux_examen = "true";//verificar si esta cotizacion existe, si existe obtener si fue un examen o una cotizacion

                        $examen_cotizacion = DB::table('cotizaciones')//busca si ese examen se cotizo antes
                        ->where('cotizaciones.id_examen','=',$id_examen)
                        ->orderBy('id_cotizacion','desc')
                        ->first();

                        if(isset($examen_cotizacion)){//en el caso que si se halla cotizado:
                            $aux_examen = $examen_cotizacion->examen;
                        }

                        // dd($mayor,$id_mayor,$nombre_mayor,$aux_examen,$examen_cotizacion);

                        if($id_mayor != null && $nombre_mayor != null && $aux_examen == "true"){//1.1  SI SE PUEDE HACER EL TRUCO

                                $mensaje = "1.1";

                                $precio_final_montura = $cotizacion->montura->precio_montura - $mayor;
                                $cotizacion_final = DB::table('cotizaciones')->insertGetId([
                                    'id_sucursal' => $cotizacion->sucursal->id_sucursal,
                                    'id_laboratorio' => $laboratorio->id_laboratorio,
                                    'id_vendedor' => $id_user,
                                    'id_administrador_descuento' => $cotizacion->montura->usuario_autoriza_descuento,
                                    'id_cliente'  => $id_cliente,
                                    'id_paciente' => $id_paciente,
                                    'id_examen'   => $id_examen,
                                    'examen'      => "true",
                                    'id_producto_examen'      => $id_mayor,
                                    'nombre_examen' =>$nombre_mayor,
                                    'precio_examen' =>$mayor,
                                    'id_lente' => $cotizacion->lente->id_lente,
                                    'precio_lente' => $cotizacion->lente->precio_lente,
                                    'id_montura' => $cotizacion->montura->id_montura,
                                    'precio_original_montura' => $cotizacion->montura->precio_o_montura,
                                    'precio_descuento_montura' => $cotizacion->montura->precio_montura,
                                    'precio_examen_montura' => $precio_final_montura,
                                    'fecha_cotizacion' => $date,
                                    'estado_cotizacion' => 'activo',
                                    'estado_venta_cotizacion' => 'pendiente',
                                    'cantidad_recordatorios' => 0
                                ]);
                        }else{//1.2

                                $mensaje = "1.2";

                                $cotizacion_final = DB::table('cotizaciones')->insertGetId([
                                    'id_sucursal' => $cotizacion->sucursal->id_sucursal,
                                    'id_laboratorio' => $laboratorio->id_laboratorio,
                                    'id_vendedor' => $id_user,
                                    'id_administrador_descuento' => $cotizacion->montura->usuario_autoriza_descuento,
                                    'id_cliente'  => $id_cliente,
                                    'id_paciente' => $id_paciente,
                                    'id_examen'   => $id_examen,
                                    'examen'      => "false",
                                    'id_lente' => $cotizacion->lente->id_lente,
                                    'precio_lente' => $cotizacion->lente->precio_lente,
                                    'id_montura' => $cotizacion->montura->id_montura,
                                    'precio_original_montura' => $cotizacion->montura->precio_o_montura,
                                    'precio_descuento_montura' => $cotizacion->montura->precio_montura,
                                    'fecha_cotizacion' => $date,
                                    'estado_cotizacion' => 'activo',
                                    'estado_venta_cotizacion' => 'pendiente',
                                    'cantidad_recordatorios' => 0
                                ]);
                        }
                }else{//2

                        $mensaje = "2";

                        $cotizacion_final = DB::table('cotizaciones')->insertGetId([
                            'id_sucursal' => $cotizacion->sucursal->id_sucursal,
                            'id_laboratorio' => $laboratorio->id_laboratorio,
                            'id_vendedor' => $id_user,
                            'id_administrador_descuento' => $cotizacion->montura->usuario_autoriza_descuento,
                            'id_cliente'  => $id_cliente,
                            'id_paciente' => $id_paciente,
                            'id_examen'   => $id_examen,
                            'examen'      => "false",
                            'id_lente' => $cotizacion->lente->id_lente,
                            'precio_lente' => $cotizacion->lente->precio_lente,
                            'id_montura' => $cotizacion->montura->id_montura,
                            'precio_original_montura' => $cotizacion->montura->precio_o_montura,
                            'precio_descuento_montura' => $cotizacion->montura->precio_montura,
                            'fecha_cotizacion' => $date,
                            'estado_cotizacion' => 'activo',
                            'estado_venta_cotizacion' => 'pendiente',
                            'cantidad_recordatorios' => 0
                        ]);
                }
        }

        return redirect()->route('cotizaciones.resultado',['cotizacion' => $cotizacion_final]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::LISTA DE CIRUGIAS::::::::::::::::::::::::::::::::::::::::::::::*/
    public function resultado(Request $request){

        $cotizacion = DB::table('cotizaciones')
        ->where('id_cotizacion','=',$request->cotizacion)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','cotizaciones.id_sucursal')
        ->leftJoin('users','users.id','=','cotizaciones.id_vendedor')
        ->leftJoin('clientes','clientes.id_cliente','=','cotizaciones.id_cliente')
        ->first();

        //dd($cotizacion);

        if (isset($cotizacion)) {
            
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

            $existe = false;
            foreach ($usuarios_sucursales as $usuario_sucursal) {
                    if ($usuario_sucursal->id_sucursal == $cotizacion->id_sucursal) {
                        $existe = true;
                    }
            }

            if ($existe == true) {
                    return view('cotizaciones.resultado',compact('cotizacion'));
            }else{
                $estatus = "errorSucursal";
                return redirect()->route('cotizaciones.crear',['estatus' => $estatus]);   
            }

        }else{
            $estatus = "errorExiste";
            return redirect()->route('cotizaciones.crear',['estatus' => $estatus]);
        }
        
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::LISTA DE COTIZACIONES::::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

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

        $cotizaciones = DB::table('cotizaciones')
        ->where('estado_cotizacion','=','activo')
        ->where('estado_venta_cotizacion','=','pendiente')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','cotizaciones.id_sucursal')
        ->leftJoin('users','users.id','=','cotizaciones.id_vendedor')
        ->orderBy('id_cotizacion','desc')
        ->get();

        // dd($cotizaciones);

        $aux_cotizaciones = [];
        foreach ($cotizaciones as $cotizacion) {

                $pertenece = false;
                foreach ($usuarios_sucursales as $usuario_sucursal) {
                    if ($usuario_sucursal->id_sucursal == $cotizacion->id_sucursal) {
                        array_push($aux_cotizaciones, $cotizacion);
                    }
                }
        }

        $cotizaciones = $aux_cotizaciones;

        foreach ($cotizaciones as $cotizacion) {

            $paciente = DB::table('pacientes')
            ->where('id_paciente','=',$cotizacion->id_paciente)
            ->leftJoin('tipos_identificacion','tipos_identificacion.id_tipo_identificacion','=','pacientes.id_tipo_identificacion')
            ->first();
            if (isset($paciente)) {
                $cotizacion->nombre_tipo_identificacion_paciente = $paciente->nombre_tipo_identificacion;
                $cotizacion->identificacion_paciente = $paciente->identificacion;
                $cotizacion->nombres_paciente = $paciente->nombres_paciente;
                $cotizacion->apellidos_paciente = $paciente->apellidos_paciente;
            }

            $cliente = DB::table('clientes')
            ->where('id_cliente','=',$cotizacion->id_cliente)
            ->leftJoin('tipos_identificacion','tipos_identificacion.id_tipo_identificacion','=','clientes.id_tipo_identificacion')
            ->first();
            if (isset($cliente)) {
                $cotizacion->nombre_tipo_identificacion_cliente = $cliente->nombre_tipo_identificacion;
                $cotizacion->identificacion_cliente = $cliente->identificacion;
                $cotizacion->nombres_cliente = $cliente->nombres;
                $cotizacion->apellidos_cliente = $cliente->apellidos;
            }

            if($cotizacion->examen == "true"){
                if ($cotizacion->id_montura == null) {
                    $cotizacion->precio_examen_montura = 0;
                }
                $precio_cotizacion = $cotizacion->precio_lente + $cotizacion->precio_examen_montura + $cotizacion->precio_examen;
            }else{
                if ($cotizacion->id_montura == null) {
                    $cotizacion->precio_descuento_montura = 0;
                }
                $precio_cotizacion = $cotizacion->precio_lente + $cotizacion->precio_descuento_montura;
            }
            $cotizacion->precio_cotizacion = $precio_cotizacion;
        }


        // dd($cotizaciones);

        return view('cotizaciones.lista',compact('cotizaciones','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::VER CIRUGIA::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function ver(Request $request){

        dd("ver",$request);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::EDITAR CIRUGIA::::::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        dd("editar",$request);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::INACTIVAR CIRUGIA::::::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        dd("inactivar",$request);
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::PRUEBA::::::::::::::::::::::::::::::::::::::::::::::::::*/


    public function prueba(){

        //dd("prueba");

        for ($i = 1; $i <= 1000; $i++) {
            $id_cliente = DB::table('ajax_prueba')->insertGetId([
                'numero' => $i
            ]);
        }
        
        //dd("fin");
        $fin = "fin";

        return ($fin);
    }


    public function ajax(){

//dd("dasd");

        return view('cotizaciones.ajax');
    }



    public function cotizacion(Request $request){

        //dd($request);


        $cotizacion = DB::table('cotizaciones')
        ->where('id_cotizacion','=',$request->cotizacion)
        ->leftJoin('sucursals','sucursals.id_sucursal','=','cotizaciones.id_sucursal')
        ->leftJoin('users','users.id','=','cotizaciones.id_vendedor')
        ->leftJoin('clientes','clientes.id_cliente','=','cotizaciones.id_cliente')
        ->leftJoin('tipos_identificacion','tipos_identificacion.id_tipo_identificacion','=','clientes.id_tipo_identificacion')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
        ->leftJoin('examenes','examenes.id_examen','=','cotizaciones.id_examen')
        //->select('modelos.nombre_modelo','productos.precio_base','productos.id_producto','tipo_productos.nombre_tipo_producto','marcas.nombre_marca')
        ->first();

        //dd($cotizacion);


        return view('cotizaciones.cotizacion',compact('cotizacion'));
    }


//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
}
