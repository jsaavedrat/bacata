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

class EmpresasEnvioController extends Controller
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
/*::::::::::::::::::::::::::::::::::::::::CREAR EMPRESA ENVIO::::::::::::::::::::::::::::::::::::::::::::*/


    public function crear(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
        
        return view('empresas_envio.crear',compact('estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::GUARDAR EMPRESA ENVIO::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar(Request $request){

        //REALIZAR VALIDACION DE NO DUPLICAR
        $date = Carbon::now();
        if(isset($request->imagen_empresa_envio)){
            
            $nombre_imagen = 'ee-' . Auth::id() . "--" . $date->toDateString() . "-" . date('H-i-s') . "." .$request->file('imagen_empresa_envio')->extension();
            $request->imagen_empresa_envio->move('public/imagenes/sistema/empresas_envio',$nombre_imagen);
            DB::table('empresa_envios')->insert([
                'nombre_empresa_envio' => $request->nombre_empresa_envio,
                'imagen_empresa_envio' => $nombre_imagen,
                'nombre_codigo' => $request->nombre_codigo,
                'estado_empresa_envio' => 'activo'
            ]);
        }else{
            DB::table('empresa_envios')->insert([
                'nombre_empresa_envio' => $request->nombre_empresa_envio,
                'nombre_codigo' => $request->nombre_codigo,
                'estado_empresa_envio' => 'activo'
            ]);
        }
        $estatus = "exito";

        return redirect()->route('empresas.envio.crear',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::LISTA DE  EMPRESA ENVIO:::::::::::::::::::::::::::::::::::::::::::*/


    public function lista(Request $request){

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        $empresas_envio = DB::table('empresa_envios')
        ->where('estado_empresa_envio','=','activo')
        ->orderBy('id_empresa_envio','desc')
        ->get();

        return view('empresas_envio.lista',compact('empresas_envio','estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::EDITAR EMPRESA ENVIO::::::::::::::::::::::::::::::::::::::::::*/


    public function editar(Request $request){

        $empresa_envio = DB::table('empresa_envios')
        ->where('estado_empresa_envio','=','activo')
        ->where('id_empresa_envio','=',$request->editar)
        ->first();

        if (isset($empresa_envio)) {
            $estatus="";
            return view('empresas_envio.modificar',compact('empresa_envio','estatus'));
        }else{
            $estatus="errorActualizar";
            return redirect()->route('empresas.envio.lista',['estatus' => $estatus]);
        }
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::MODIFICAR  EMPRESA ENVIO:::::::::::::::::::::::::::::::::::::::*/


    public function modificar(Request $request){

        $empresa_envio = DB::table('empresa_envios')
        ->where('estado_empresa_envio','=','activo')
        ->where('id_empresa_envio','=',$request->id_empresa_envio)
        ->first();

        if(isset($empresa_envio)){

            if(isset($request->imagen_empresa_envio)){

                $date = Carbon::now();
                $nombre_imagen = 'ee-' . Auth::id() . "--" . $date->toDateString() . "-" . date('H-i-s') . "." .$request->file('imagen_empresa_envio')->extension();
                $request->imagen_empresa_envio->move('public/imagenes/sistema/empresas_envio',$nombre_imagen);
                DB::table('empresa_envios')
                ->where('id_empresa_envio','=',$request->id_empresa_envio)
                ->update([
                    'nombre_empresa_envio' => $request->nombre_empresa_envio,
                    'imagen_empresa_envio' => $nombre_imagen,
                    'nombre_codigo' => $request->nombre_codigo,
                ]);
                if (isset($empresa_envio->imagen_empresa_envio)) {
                    File::delete('public/imagenes/sistema/empresas_envio/'.$empresa_envio->imagen_empresa_envio);
                }

            }else{

                DB::table('empresa_envios')
                ->where('id_empresa_envio','=',$request->id_empresa_envio)
                ->update([
                    'nombre_empresa_envio' => $request->nombre_empresa_envio,
                    'nombre_codigo' => $request->nombre_codigo,
                ]);

            }
            $estatus="actualizado";
        }else{
            $estatus="erroractualizar";
        }
        return redirect()->route('empresas.envio.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::INACTIVAR EMPRESA ENVIO::::::::::::::::::::::::::::::::::::::::*/


    public function inactivar(Request $request){

        $empresa_envio = DB::table('empresa_envios')
        ->where('estado_empresa_envio','=','activo')
        ->where('id_empresa_envio','=',$request->eliminar)
        ->first();

        if(is_numeric($request->eliminar)&&($empresa_envio != null)) {

            $estatus="exito";
            DB::table('empresa_envios')
            ->where('id_empresa_envio', $request->eliminar)
            ->update(['estado_empresa_envio' => 'inactivo']);
        }else{
            $estatus="error";
        }

        return redirect()->route('empresas.envio.lista',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::CREAR REPORTE ENVIO::::::::::::::::::::::::::::::::::::::::::::*/


    public function crear_reporte(Request $request){

        $empresas_envio = DB::table('empresa_envios')
        ->where('estado_empresa_envio','=','activo')
        ->orderBy('id_empresa_envio','desc')
        ->get();

        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}
        
        return view('empresas_envio.reporte',compact('estatus','empresas_envio'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::GENERAR REPORTE ENVIO::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_reporte(Request $request){

        // dd($request);

        $cliente_appweb = DB::table('cliente_appweb')
        ->where('estado_cliente_appweb','=','activo')
        ->first();

        $empresa_envio = DB::table('empresa_envios')
        ->where('id_empresa_envio','=',$request->id_empresa_envio)
        ->first();

        // dd($empresa_envio);

        $data = [
            'request' => $request,
            'cliente_appweb' => $cliente_appweb,
            'fecha_reporte' => Carbon::now(),
            'empresa_envio' => $empresa_envio
        ];

        $pdf = \PDF::loadView('empresas_envio.pdf_reporte',$data)
        ->setPaper('a4');
        return $pdf->download('reporte.pdf');
    }



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::REDIRECCIONES GET:::::::::::::::::::::::::::::::::::::::::::::::*/


    public function redirect(){
        return redirect()->route('empresas.envio.lista');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR:::::::::::::::::::::::::::::::::::::::::::::::*/

}
