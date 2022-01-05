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
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class ConfiguracionesController extends Controller
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
/*:::::::::::::::::::::::::::::::::::::::CREAR CLIENTE APPWEB::::::::::::::::::::::::::::::::::::::::::::*/


	public function crear_cliente(){
		
        $cliente_appweb = DB::table('cliente_appweb')
        ->where('estado_cliente_appweb','=','activo')
        ->first();

        if (isset($cliente_appweb)) {

            $estatus = "ya_existe_cliente";
            return redirect()->route('home',['estatus' => $estatus]);

        } else {

            return view('configuraciones.cliente.crear');
        }
	}


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::GUARDAR CLIENTE APPWEB::::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_cliente(Request $request){

        $cliente_appweb = DB::table('cliente_appweb')
        ->where('estado_cliente_appweb','=','activo')
        ->first();

        if (isset($cliente_appweb)) {
           
            $estatus = "ya_existe_cliente";

        } else {

            $date = Carbon::now();
            $hora = date("H-i-s");
            $nombre_imagen = 'logo-' . $date->toDateString() . '-' . $hora . '-' . Auth::id() . "." . $request->file('logo_empresa')->extension();
            $request->logo_empresa->move('public/imagenes/sistema/cliente_empresa',$nombre_imagen);

            DB::table('cliente_appweb')->insert([
                'nombre_cliente_sas' => $request->nombre_cliente,
                'nit_empresa' => $request->nit_empresa,
                'persona_cliente' => $request->persona_cliente,
                'telefono_contacto' => $request->telefono_contacto,
                'correo_electronico' => $request->correo_electronico,
                'dominio' => $request->dominio,
                'direccion' => $request->direccion,
                'pie_pagina' => $request->pie_pagina,
                'nombre_imagen_cliente' => $nombre_imagen,
                'estado_cliente_appweb' => 'activo'
            ]);

            $estatus = "exito_cliente_appweb";
        }
  
        return redirect()->route('home',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::EDITAR CLIENTE APPWEB:::::::::::::::::::::::::::::::::::::::::::*/


    public function editar_cliente(Request $request){
        
        $cliente_appweb = DB::table('cliente_appweb')
        ->where('estado_cliente_appweb','=','activo')
        ->first();

        if (isset($cliente_appweb)) {

            return view('configuraciones.cliente.modificar',compact('cliente_appweb'));
            
        } else {

            $estatus = "no_existe_cliente";
            return redirect()->route('home',['estatus' => $estatus]);
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::MODIFICAR CLIENTE APPWEB:::::::::::::::::::::::::::::::::::::::::*/


    public function modificar_cliente(Request $request){

        $cliente_appweb = DB::table('cliente_appweb')
        ->where('estado_cliente_appweb','=','activo')
        ->first();

        if (isset($cliente_appweb)) {

            if ($request->logo_empresa) {

                $date = Carbon::now();
                $hora = date("H-i-s");
                $nombre_imagen = 'logo-' . $date->toDateString() . '-' . $hora . '-' . Auth::id() . "." . $request->file('logo_empresa')->extension();
                $request->logo_empresa->move('public/imagenes/sistema/cliente_empresa',$nombre_imagen);

                if (isset($cliente_appweb->nombre_imagen_cliente) && $cliente_appweb->nombre_imagen_cliente != "") {

                    File::delete('public/imagenes/sistema/cliente_empresa/'.$cliente_appweb->nombre_imagen_cliente);
                }

                DB::table('cliente_appweb')
                ->where('id_cliente_appweb','=',$cliente_appweb->id_cliente_appweb)
                ->update([
                    'nombre_cliente_sas' => $request->nombre_cliente,
                    'nit_empresa' => $request->nit_empresa,
                    'persona_cliente' => $request->persona_cliente,
                    'telefono_contacto' => $request->telefono_contacto,
                    'correo_electronico' => $request->correo_electronico,
                    'dominio' => $request->dominio,
                    'direccion' => $request->direccion,
                    'pie_pagina' => $request->pie_pagina,
                    'nombre_imagen_cliente' => $nombre_imagen,
                    'estado_cliente_appweb' => 'activo'
                ]);
            } else {

                DB::table('cliente_appweb')
                ->where('id_cliente_appweb','=',$cliente_appweb->id_cliente_appweb)
                ->update([
                    'nombre_cliente_sas' => $request->nombre_cliente,
                    'nit_empresa' => $request->nit_empresa,
                    'persona_cliente' => $request->persona_cliente,
                    'telefono_contacto' => $request->telefono_contacto,
                    'correo_electronico' => $request->correo_electronico,
                    'dominio' => $request->dominio,
                    'direccion' => $request->direccion,
                    'pie_pagina' => $request->pie_pagina,
                    'estado_cliente_appweb' => 'activo'
                ]);
            }

            $estatus = "exito_actualizar_cliente_appweb";
            
        } else {

            $estatus = "ya_existe_cliente";
            
        }

        return redirect()->route('home',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::CARGAR CODIGOS CIE10:::::::::::::::::::::::::::::::::::::::::*/


    public function cargar_cie10(Request $request){
        if   (isset($request->estatus)){ $estatus = $request->estatus;}
        else {$estatus="";}

        return view('configuraciones.cie10.cargar',compact('estatus'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::GUARDAR CODIGOS CIE10::::::::::::::::::::::::::::::::::::::::*/


    public function guardar_cie10(Request $request){

        $codigos = json_decode($request->codigos);

        // dd($codigos);

        foreach ($codigos as $codigo) {

            $cie10 = DB::table('diagnosticos')
            ->where('codigo_diagnostico','=',$codigo->codigo)
            ->where('estado_diagnostico','=','activo')
            ->first();

            if(!isset($cie10)){

                DB::table('diagnosticos')->insert([
                    'nombre_diagnostico' => $codigo->descripcion,
                    'codigo_diagnostico' => $codigo->codigo,
                    'descripcion_diagnostico' => $codigo->descripcion,
                    'estado_diagnostico' => 'activo'
                ]);
            }
        }

        $estatus = "exito";

        return redirect()->route('configuraciones.codigoscie10.cargar',['estatus' => $estatus]);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR::::::::::::::::::::::::::::::::::::::::::::::::*/
}
