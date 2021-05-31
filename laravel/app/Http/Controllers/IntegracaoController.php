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
        $demandas_pendentes = Demanda::where('migracao', 'P')->count();
        $demandas_migradas = Demanda::where('migracao', 'C')->count();
        $ultima_atualizacao = Demanda::max('updated_at');

        return view('pages.administracao.integracao', compact('demandas_pendentes', 'demandas_migradas', 'ultima_atualizacao'));
    }

    public function create()
    {
        $demandas = Demanda::all();
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
