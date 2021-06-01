<?php

namespace App\Http\Controllers;

use App\Models\Demanda;
use App\Services\AtendimentoService;
use App\Services\DemandaService;
use Illuminate\Http\Request;

class IntegracaoController extends Controller
{
    public function index()
    {
        $demandas_pendentes = Demanda::where('migracao', 'P')->withoutGlobalScopes()->count();
        $demandas_migradas = Demanda::where('migracao', 'C')->withoutGlobalScopes()->count();
        $ultima_atualizacao = Demanda::withoutGlobalScopes()->max('updated_at');

        return view('pages.administracao.integracao', compact('demandas_pendentes', 'demandas_migradas', 'ultima_atualizacao'));
    }

    public function create()
    {
        $demandas = Demanda::whereIn('migracao', ['P','C'])->get();
        $migradas = 0;
        $atualizadas = 0;
        $errors = [];

        foreach($demandas as $demanda)
        {
            try{
                $migracao = $demanda->migracao;
                DemandaService::processa($demanda);
                if($migracao == 'P')
                    $migradas++;
                else
                    $atualizadas++;

            } catch (\Exception $th) {
                $errors[$demanda->id] = $th->getMessage();
            }
        }

        return redirect()->route('adm.integracao.index')->with('status', compact('migradas','atualizadas','errors'));
    }
}
