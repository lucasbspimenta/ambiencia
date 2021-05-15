<?php

namespace App\Http\Middleware;

use App\Services\LDAPService;
use Closure;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AuthenticateLDAP
{
    public function handle($request, Closure $next)
    {
        switch(App::environment())
        {
            case 'testing':
            case 'local':
                $matricula = $this->getMatriculaUsuarioDoEnv();
                $usuario = User::where('matricula', '=', $matricula)->first();
                break;

            default:
                $matricula = $this->getMatriculaUsuarioDoServidor();
                $usuario = User::firstOrCreate(LDAPService::findByMatricula($matricula)) ?? User::where('matricula', '=', $matricula)->first();
                break;
        }

        if($usuario){
            Auth::login($usuario);
            return $next($request);
        }

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

        return $matricula;
    }
}
