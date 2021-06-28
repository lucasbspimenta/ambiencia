<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\LDAPService;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AuthenticateLDAP
{
    public function handle($request, Closure $next)
    {
        $matricula = '';
        switch (App::environment()) {
            case 'testing':
            case 'local':
                $matricula = $this->getMatriculaUsuarioDoEnv();
                $usuario = User::where('matricula', '=', $matricula)->first();
                break;

            default:
                $matricula = $this->getMatriculaUsuarioDoServidor();
                $dadosLdap = (array) LDAPService::findByMatricula($matricula);

                if (is_null($dadosLdap)) {
                    die('Erro de conexao com o servidor de autenticação - LDAP');
                }

                if (User::where('matricula', '=', $matricula)->doesntExist()) {
                    $usuario = User::create($dadosLdap);
                } else {
                    User::where('matricula', '=', $matricula)->first()->update($dadosLdap);
                    $usuario = User::where('matricula', '=', $matricula)->first();
                }

                break;
        }

        if ($usuario) {

            if (!is_null($usuario->simulando) && User::where('matricula', '=', $usuario->simulando)->exists()) {
                $usuario_simulador = $usuario;
                $usuario = User::where('matricula', '=', $usuario->simulando)->first();
                $usuario->is_simulado = true;
                $usuario->usuario_simulador = $usuario_simulador->matricula;
            }

            Auth::login($usuario);
            return $next($request);
        }

        return response('Não autorizado!', 403);
    }

    private function getMatriculaUsuarioDoEnv()
    {
        return env('USUARIO_TESTE');
    }

    private function getMatriculaUsuarioDoServidor(): string
    {
        $replaceDomains = ["CORPCAIXA\\", "corpcaixa\\", "CORPCAIXA/", "corpcaixa/"];
        $matricula = false;

        if (isset($_SERVER["AUTH_USER"])) {
            $matricula = str_replace($replaceDomains, "", $_SERVER["AUTH_USER"]);
        }

        if (strtoupper($matricula) == 'C096810') {
            return env('USUARIO_TESTE');
        }

        return $matricula;
    }
}
