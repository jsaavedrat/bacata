<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Closure;

class administradores
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        $usuario = DB::table('users')
        ->where('id','=',Auth::id())
        ->first();

        if($usuario->tipo_usuario == "" || $usuario->tipo_usuario == null || $usuario->tipo_usuario == "cliente"){
            return redirect()->route('ecommerce.cliente');
        }else{
            return $next($request);
        }        
    }
}
