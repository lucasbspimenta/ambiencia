<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificaNavegador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
            return $next($request);
        }

        die('Gentileza realizar o acesso pelo navegador Google Chrome!');
    }
}
