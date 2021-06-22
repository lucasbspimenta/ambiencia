<?php

namespace App\Http\Controllers;

use App\Http\Helpers\HieraquiaHelper;
use App\Models\UnidadeResponsavel;
use Auth;

class PainelController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->is_admin) {
            $responsaveis = UnidadeResponsavel::selectRaw('matricula,nome,coordenador,coordenador_nome,supervisor,supervisor_nome,equipe_id,equipe_nome')
                ->where('matricula', $user->matricula)
                ->orWhere('coordenador', $user->matricula)
                ->orWhere('supervisor', $user->matricula)
                ->distinct()
                ->get();
        } else {
            $responsaveis = UnidadeResponsavel::selectRaw('matricula,nome,coordenador,coordenador_nome,supervisor,supervisor_nome,equipe_id,equipe_nome')->distinct()->get();
        }

        $niveis = ['coordenador_nome', 'equipe_nome', 'supervisor_nome', 'nome'];
        $dados_nivel = null;
        $nivel_encontrado = 0;

        foreach ($niveis as $ind => $nivel) {
            $agrupado = $responsaveis->groupBy($nivel);
            if ($agrupado->count() > 1) {
                $dados_nivel = $agrupado;
                $nivel_encontrado = $ind;
                break;
            }
        }
        $hierarquia = HieraquiaHelper::Agrupador($dados_nivel, $niveis, $nivel_encontrado);
        //dd($hierarquia);
        return view('index', compact('hierarquia'));
    }
}
