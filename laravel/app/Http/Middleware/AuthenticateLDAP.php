<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\LDAPService;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

            if (!$usuario->equipe) {
                return response('Não existe equipe vinculada a sua matricula, verifique com o agente de RH', 403);
            }

            if (!is_null($usuario->simulando) && User::where('matricula', '=', $usuario->simulando)->exists()) {
                $usuario_simulador = $usuario;
                $usuario = User::where('matricula', '=', $usuario->simulando)->first();
                $usuario->is_simulado = true;
                $usuario->usuario_simulador = $usuario_simulador->matricula;
            }

            if (
                DB::table('acessos_autorizados')->where('identificador', $usuario->equipe->id)->where('tipo', 'EQP')->where('autorizado', true)->exists() ||
                DB::table('acessos_autorizados')->where('identificador', $usuario->matricula)->where('tipo', 'USR')->where('autorizado', true)->exists()
            ) {
                Auth::login($usuario);
                return $next($request);
            } else {
                return response('Acesso não autorizado para o seu perfil!: Matricula: ' . $usuario->matricula . ' - Equipe: ' . $usuario->equipe->id . '  = ' . $usuario->equipe->nome, 403);
            }
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
