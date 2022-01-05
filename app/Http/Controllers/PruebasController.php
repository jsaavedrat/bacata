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

class PruebasController extends Controller
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


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::CREAR::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

	public function crear(){

        $mensaje="";

        return view('pruebas.crear',compact('mensaje'));
	}



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::GUARDAR::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function guardar(Request $request){

        //dd($request);

        $productos = DB::table('productos')
        ->where('estado_producto','=','activo')
        ->where('id_modelo','=',$request->id_modelo)
        ->select('id_producto','precio_base','declara_iva','codigo_producto')
        ->get();

        //dd($productos);

        $especificaciones = DB::table('especificaciones')
        ->where('estado_especificacion','=','activo')
        ->leftJoin('producto_especificaciones','producto_especificaciones.id_especificacion','=','especificaciones.id_especificacion')
        ->select('producto_especificaciones.id_producto','especificaciones.id_especificacion','especificaciones.nombre_especificacion')
        ->get();

        //dd($especificaciones);

        
        $i = 0;

        foreach ($productos as $producto) {
            $j=0;
            $esp = [];
            foreach ($especificaciones as $especificacion) {
                if($especificacion->id_producto == $producto->id_producto){
                    $valores=[
                        'id_especificacion' => $especificacion->id_especificacion,
                        'nombre_especificacion' => $especificacion->nombre_especificacion
                    ];
                    $esp[$j]=$valores;
                    $j=$j+1;
                }
            }
            $vector=[
                'id_producto' => $producto->id_producto,
                'especificaciones' => $esp
            ];
            $array[$i]=$vector;
            $i=$i+1;
        }






        dd($array);//first


        /*$incremental = DB::table('prueba_incremental')
        ->where('id_bodega','=',14)
        ->increment('cantidad', 1);*/

        dd("Operacion Realizada");
        $mensaje="";
        return view('bodegas.crear',compact('mensaje'));
    }



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::LISTA::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

   public function lista(){



    $usuarios = array(
    array('nombre' => 'Alex', 'apellido' => 'Escobar', 'telefono' => '3213211212'),
    array('nombre' => 'Juan', 'apellido' => 'Gomez', 'telefono' => '3211231212'),
    array('nombre' => 'Andres', 'apellido' => 'Marín', 'telefono' => '3211112223'),
    array('nombre' => 'Angie', 'apellido' => 'Rivera', 'telefono' => '3211212121')
    );
    foreach ($usuarios as $usuario){
        dd($usuario['nombre'] . ' ' . $usuario['apellido'] . ' ' . $usuario['telefono']);
    }





        /*$userss = DB::table('userss')
        ->leftJoin('usersContactInfo','usersContactInfo.userId','=','userss.userId')
        ->leftJoin('companies','companies.companyId','=','userss.companyIds')
        ->get();

        dd($userss);*/

        return view('bodegas.lista',compact('bodegas'));
    }



}
