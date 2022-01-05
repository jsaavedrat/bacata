<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::PAGINA INICIAL::::::::::::::::::::::::::::::::::::::::::::::*/

    public function index(){

        $ultimos_productos_ecommerce = DB::table('producto_bodegas')
        ->where('producto_bodegas.id_bodega','=',0)
        ->where('producto_bodegas.cantidad','>',0)
        ->leftJoin('productos','productos.id_producto','=','producto_bodegas.id_producto')
        ->where('productos.estado_producto','=','activo')
        ->leftJoin('imagen_productos','imagen_productos.id_producto','=','productos.id_producto')
        ->where('id_imagen_producto','!=',null)
        ->orderBy('id_producto_bodega','desc')
        ->take(6)
        ->groupBy(['productos.id_producto'])
        ->get();

        $logos_marcas = DB::table('logos_marcas_slick')
        ->where('estado_logo_marca','=','activo')
        ->get();

        $infos = DB::table('infos_slick')
        ->where('estado_info','=','activo')
        ->get();

        $producto_membresias = DB::table('productos_especiales')
        ->where('nombre_producto','=','membresias')
        ->where('estado_producto_especial','=','activo')
        ->first();

        if(isset($producto_membresias->id_tipo_producto_especial)){

            $membresias = DB::table('modelos')
            ->where('id_tipo_producto','=',$producto_membresias->id_tipo_producto_especial)
            ->where('estado_modelo','=','activo')
            ->leftJoin('productos','productos.id_modelo','=','modelos.id_modelo')
            ->where('productos.estado_producto','=','activo')
            ->leftJoin('imagen_productos','imagen_productos.id_producto','=','productos.id_producto')
            ->select('modelos.nombre_modelo','productos.id_producto','productos.precio_base','imagen_productos.nombre_imagen')
            ->orderBy('productos.precio_base')
            ->get();

            foreach($membresias as $membresia){
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$membresia->id_producto)
                ->where('producto_especificaciones.estado_producto_especificacion','=','activo')
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
                ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion','clasificaciones.id_clasificacion')
                ->orderBy('clasificaciones.id_clasificacion')
                ->get();
                $membresia->especificaciones = $especificaciones;
            }
        }

        $fondo_kids = DB::table('imagenes_kids')
        ->where('id_imagen_kids','=',0)
        ->first();

        $imagenes_kids = DB::table('imagenes_kids')
        ->where('estado_imagen_kids','=','activo')
        ->where('id_imagen_kids','!=',0)
        ->orderBy('orden')
        ->get();

        $imagenes_equipo = DB::table('imagenes_equipo')
        ->where('estado_imagen_equipo','=','activo')
        ->orderBy('orden')
        ->get();

        $imagenes_carrusel = DB::table('imagenes_carrusel')
        ->where('estado_imagen_carrusel','=','activo')
        ->orderBy('orden')
        ->get();

        $promociones_pagina = DB::table('promociones_pagina')
        ->where('estado_promocion_pagina','=','activo')
        ->get();

        return view('welcome',compact('ultimos_productos_ecommerce','logos_marcas','infos','producto_membresias','membresias','promociones_pagina','imagenes_carrusel','imagenes_kids','imagenes_equipo','fondo_kids'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::NOSOTROS / QUEINES SOMOS:::::::::::::::::::::::::::::::::::::::*/


    public function quienes(){

        $quienes = DB::table('textos_web')
        ->where('estado_texto_web','=','activo')
        ->where('tipo_texto_web','=','quienes')
        ->get();

        // dd($quienes);

        return view('quienes',compact('quienes'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::PAGO ? REVISAR::::::::::::::::::::::::::::::::::::::::::::*/


    public function pago(){

        return view('pago');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::CONSULTAR VENTA:::::::::::::::::::::::::::::::::::::::::::*/


    public function consulta($id_venta){

        $ordenes = DB::table('ordenes')
        ->where('id_venta','=',$id_venta)
        ->leftJoin('cotizaciones','cotizaciones.id_cotizacion','=','ordenes.id_cotizacion')
        ->leftJoin('sucursals','sucursals.id_sucursal','=','ordenes.id_sucursal')
        ->leftJoin('pacientes','pacientes.id_paciente','=','cotizaciones.id_paciente')
        ->get();

        foreach ($ordenes as $orden) {
            $lente = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_lente)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if ($orden->estado_orden == "enviado") {
                $envio = DB::table('ordenes_enviadas_pivote')
                ->where('ordenes_enviadas_pivote.id_orden','=',$orden->id_orden)
                ->leftJoin('ordenes_enviadas_historial','ordenes_enviadas_historial.id_orden_enviada_historial','=','ordenes_enviadas_pivote.id_orden_enviada_historial')
                ->leftJoin('empresa_envios','empresa_envios.id_empresa_envio','=','ordenes_enviadas_historial.id_empresa_envio')
                ->orderBy('ordenes_enviadas_pivote.id_orden_enviada_pivote','desc')
                ->first();

                if(isset($envio)){
                    $orden->envio = $envio;
                }
            }

            if (isset($lente)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_lente)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_lente = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_lente = $especificaciones_lente . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->lente_orden = $lente->nombre_tipo_producto . " - " . $lente->nombre_modelo . " - " . $especificaciones_lente;
                $orden->nombre_laboratorio = $lente->nombre_marca;
            }


            $montura = DB::table('productos')
            ->where('productos.id_producto','=',$orden->id_montura)
            ->leftJoin('modelos','modelos.id_modelo','=','productos.id_modelo')
            ->leftJoin('marcas','marcas.id_marca','=','modelos.id_marca')
            ->leftJoin('tipo_productos','tipo_productos.id_tipo_producto','=','modelos.id_tipo_producto')
            ->first();

            if (isset($montura)) {
                $especificaciones = DB::table('producto_especificaciones')
                ->where('producto_especificaciones.id_producto','=',$orden->id_montura)
                ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
                ->get();
                $len = count($especificaciones);
                $especificaciones_montura = "";
                foreach ($especificaciones as $especificacion) {
                    if($especificacion == $especificaciones[$len - 1]){
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion;
                    }else{
                        $especificaciones_montura = $especificaciones_montura . $especificacion->nombre_especificacion . ", ";
                    }
                }
                $orden->montura_orden = $montura->nombre_tipo_producto . " - " . $montura->nombre_marca . " - " . $montura->nombre_modelo . " - " . $especificaciones_montura;

            }else{
                $orden->montura_orden = "Montura propiedad del cliente";
            }
            
        }

        return view('consulta',compact('ordenes'));
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::VER SERVICIOS:::::::::::::::::::::::::::::::::::::::::::::*/


    public function servicios($id_servicio){

        $servicio = DB::table('servicios')
        ->where('id_servicio','=',$id_servicio)
        ->where('estado_servicio','=','activo')
        ->first();
        if(isset($servicio)){
            return view('servicios',compact('servicio'));
        }else{
            return redirect()->route('welcome');
        }
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::VER SERVICIOS:::::::::::::::::::::::::::::::::::::::::::::*/


    public function servicio(){

        return redirect()->route('welcome');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::PROMOCIONES PAGINA WEB:::::::::::::::::::::::::::::::::::::::::*/


    public function promocion($id_promocion){

        $promocion_pagina = DB::table('promociones_pagina')
        ->where('id_promocion_pagina','=',$id_promocion)
        ->where('estado_promocion_pagina','=','activo')
        ->first();

        if(isset($promocion_pagina)){
            return view('promocion',compact('promocion_pagina'));
        }else{
            return redirect()->route('welcome');
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::PROMOCIONES PAGINA WEB:::::::::::::::::::::::::::::::::::::::::*/


    public function redimir_promocion(Request $request){

        $cliente = DB::table('clientes')
        ->where('id_tipo_identificacion','=',$request->id_tipo_identificacion)
        ->where('identificacion','=',$request->identificacion)
        ->first();

        if(isset($cliente)){

            if(isset($request->telefono) && $request->telefono != ""){
                DB::table('clientes')
                ->where('id_tipo_identificacion','=',$request->id_tipo_identificacion)
                ->where('identificacion','=',$request->identificacion)
                ->update(['telefono' => $request->telefono]);
            }

            if(isset($request->email) && $request->email != ""){
                DB::table('clientes')
                ->where('id_tipo_identificacion','=',$request->id_tipo_identificacion)
                ->where('identificacion','=',$request->identificacion)
                ->update(['email' => $request->email]);
            }

            $id_cliente = $cliente->id_cliente;

        }else{

            $id_cliente = DB::table('clientes')->insertGetId([
                'id_tipo_identificacion' => $request->id_tipo_identificacion,
                'identificacion' => $request->identificacion,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'email' => $request->email
            ]);
        }

        $cliente_redime = DB::table('clientes')
        ->where('id_cliente','=',$id_cliente)
        ->first();

        $promocion_pagina = DB::table('promociones_pagina')
        ->where('id_promocion_pagina','=',$request->id_promocion_pagina)
        ->where('estado_promocion_pagina','=','activo')
        ->first();

        $cliente_appweb = DB::table('cliente_appweb')
        ->where('estado_cliente_appweb','=','activo')
        ->first();
        // dd($cliente_appweb);

        $data = [
            'cliente' => $cliente_redime,
            'cliente_appweb' => $cliente_appweb,
            'fecha_promocion' => Carbon::now(),
            'promocion_pagina' => $promocion_pagina

        ];
        $pdf = \PDF::loadView('pdf_promocion',$data)
        ->setPaper('a4');
        return $pdf->download('archivo.pdf');
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::BUSCAR MEMBRESIA:::::::::::::::::::::::::::::::::::::::::::*/


    public function buscar_membresia(Request $request){

        $especificaciones_membresia = DB::table('producto_especificaciones')
        ->where('producto_especificaciones.id_producto','=',$request->id_membresia)
        ->where('producto_especificaciones.estado_producto_especificacion','=','activo')
        ->leftJoin('especificaciones','especificaciones.id_especificacion','=','producto_especificaciones.id_especificacion')
        ->leftJoin('clasificaciones','clasificaciones.id_clasificacion','=','especificaciones.id_clasificacion')
        ->select('especificaciones.nombre_especificacion','clasificaciones.nombre_clasificacion','clasificaciones.id_clasificacion')
        ->orderBy('clasificaciones.id_clasificacion')
        ->get();

        return $especificaciones_membresia;
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::BUSCAR MEMBRESIA:::::::::::::::::::::::::::::::::::::::::::*/


    public function terminos_condiciones($termino){

        $termino = DB::table('textos_web')
        ->where('nombre_texto_web','=',$termino)
        ->where('estado_texto_web','=','activo')
        ->where('tipo_texto_web','=','termino_condicion')
        ->first();

        $privacidad = null;
        if(isset($termino)){
            if($termino->mostrar_privacidad == "activo"){
                $privacidad = DB::table('textos_web')
                ->where('nombre_texto_web','=','Politica de Privacidad y Protección de Datos')
                ->where('estado_texto_web','=','activo')
                ->first();
            }
        }
        

        if(isset($termino)){
            return view('terminos',compact('termino','privacidad'));
        }else{
            return redirect()->route('welcome');
        }
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::MOSTRAR CHAT AL CARGAR PAGINA::::::::::::::::::::::::::::::::::::::*/


    public function mostrar_chat(Request $request){

        $chats = DB::table('chats')
        ->where('id_correo_chat','=',$request->id_correo_chat)
        ->where('estado_chat','=','activo')
        ->where('chat','!=',null)
        ->where('chat','!=','')
        ->select("chats.*", DB::raw("DATE_FORMAT(chats.fecha_chat, '%d/%m/%Y %r') as fecha_chat"))
        ->get();
        
        return json_encode($chats);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::VERIFICAR CORREO CHAT::::::::::::::::::::::::::::::::::::::::::*/


    public function verificar_correo(Request $request){

        $correo = DB::table('correos_chats')
        ->where('correo_chat','=',$request->correo)
        ->where('estado_correo_chat','=','activo')
        ->first();

        if(!isset($correo)){

            $id_correo = DB::table('correos_chats')->insertGetId([
                'correo_chat' => $request->correo,
                'nombre' => $request->nombre
            ]);
            $correo = DB::table('correos_chats')
            ->where('id_correo_chat','=',$id_correo)
            ->first();
            $correo->chats = [];

        }else{

            $chats = DB::table('chats')
            ->where('id_correo_chat','=',$correo->id_correo_chat)
            ->where('estado_chat','=','activo')
            ->where('chat','!=',null)
            ->where('chat','!=','')
            ->select("chats.*", DB::raw("DATE_FORMAT(chats.fecha_chat, '%d/%m/%Y %r') as fecha_chat"))
            ->get();
            $correo->chats = $chats;
        }

        return json_encode($correo);
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::GUARDAR CHAT:::::::::::::::::::::::::::::::::::::::::::::*/


    public function enviar_chat(Request $request){

        $chat = "";
        if(isset($request->chat)){
            $id_chat = DB::table('chats')->insertGetId([
                'id_correo_chat' => $request->id_correo_chat,
                'tipo_usuario_chat' => 'cliente',
                'chat' => $request->chat,
            ]);

            $chat = DB::table('chats')
            ->where('id_chat','=',$id_chat)
            ->where('chat','!=',null)
            ->where('chat','!=','')
            ->select("chats.*", DB::raw("DATE_FORMAT(chats.fecha_chat, '%d/%m/%Y %r') as fecha_chat"))
            ->first();
        }

        return json_encode($chat);
    }


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::GUARDAR NOMBRE USUARIO CHAT::::::::::::::::::::::::::::::::::::::*/


    public function guardar_nombre(Request $request){

        DB::table('correos_chats')
        ->where('id_correo_chat','=',$request->id_correo_chat)
        ->update(['nombre' => $request->nombre]);

        return json_encode("exito");
    }

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::FIN CONTROLADOR::::::::::::::::::::::::::::::::::::::::::::*/

}
