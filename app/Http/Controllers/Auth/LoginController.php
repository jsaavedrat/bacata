<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // dd("SISTEMA EN MANTENIMIENTO");
        $this->middleware('guest')->except('logout');
    }

/*::::::::::::::::::::::::::::::::::::METODO AGREGADO PARA SABER SI UN USUARIO ESTA ACTIVO::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    protected function credentials(Request $request){
        $credenciales = $request->only($this->username(), 'password');
        $credenciales = array_add($credenciales, 'estado_usuario', 'activo');
        return $credenciales;
    }
}
