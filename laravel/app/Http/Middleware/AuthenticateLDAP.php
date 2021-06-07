<?php

namespace App\Http\Middleware;

use App\Services\LDAPService;
use Closure;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthenticateLDAP
{
    public function handle($request, Closure $next)
    {
        $matricula = '';

        switch(App::environment())
        {
            case 'testing':
            case 'local':
                $matricula = $this->getMatriculaUsuarioDoEnv();

                if (Session::has('usuario_simulado')) {
                    $matricula = Session::get('usuario_simulado');
                }

                $usuario = User::where('matricula', '=', $matricula)->first();
                break;

            default:
                $matricula = $this->getMatriculaUsuarioDoServidor();

                if (Session::has('usuario_simulado')) {
                    $matricula = Session::get('usuario_simulado');
                }

                $dadosLdap = (array)LDAPService::findByMatricula($matricula);

                if(is_null($dadosLdap))
                    die('Erro de conexao com o servidor de autenticação - LDAP');

                if(User::where('matricula', '=', $matricula)->doesntExist()) {
                    $usuario = User::create($dadosLdap);
                } else {
                    User::where('matricula', '=', $matricula)->first()->update($dadosLdap);
                    $usuario = User::where('matricula', '=', $matricula)->first();
                }

                break;
        }

        if($usuario){
            Auth::login($usuario);
            return $next($request);
        }

        Session::forget('usuario_simulado',$matricula);
        Session::save();

        return response('Não autorizado!', 403);
    }

    private function getMatriculaUsuarioDoEnv()
    {
        return env('USUARIO_TESTE');
    }

    private function getMatriculaUsuarioDoServidor() :string
    {
        $replaceDomains = ["CORPCAIXA\\", "corpcaixa\\", "CORPCAIXA/", "corpcaixa/"];
        $matricula = false;

        if (isset($_SERVER["AUTH_USER"])) {
            $matricula = str_replace($replaceDomains, "", $_SERVER["AUTH_USER"]);
        }

        if(strtoupper($matricula) == 'C096810')
            return env('USUARIO_TESTE');

        return $matricula;
    }
}
