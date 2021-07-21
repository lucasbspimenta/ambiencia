<?php

namespace App\Http\Livewire\Demanda;

use App\Models\ChecklistResposta;
use App\Services\DemandaService;
use Livewire\Component;

class Vinculadas extends Component
{
    public $checklist;
    public $respostas_vinculadas;

    protected $listeners = ['atualizar' => 'atualizar'];

    public function render()
    {
        return view('livewire.demanda.vinculadas');
    }

    public function atualizar()
    {

        $this->checklist->load('demandas');
    }

    public function excluir($demanda_id)
    {
        //$resposta = ChecklistResposta::find($resposta_id);
        //$resposta->demandas()->detach($demanda_id);

        $demandaService = new DemandaService();
        $respostas_para_atualizar = $demandaService->findById($demanda_id)->respostas->where('checklist_id', $this->checklist->id)->pluck('id');
        $demanda_de_outros_checklist = $demandaService->findById($demanda_id)->respostas->where('checklist_id', '!=', $this->checklist->id)->count();

        if ($demanda_de_outros_checklist > 0) {
            $demandaService->findById($demanda_id)->respostas()->detach($respostas_para_atualizar);
        } else {
            $demandaService->excluir($demanda_id);
        }

        $this->dispatchBrowserEvent('triggerSucessoExclusao');
        $this->dispatchBrowserEvent('atualizarResposta', ['resposta_id' => $respostas_para_atualizar]);
        $this->checklist->load('demandas');
    }

    public function desvincular($demanda_id, $resposta_id)
    {
        $resposta = ChecklistResposta::with('demandas')->find($resposta_id);
        $resposta->demandas()->detach($demanda_id);

        $this->dispatchBrowserEvent('triggerSucesso', 'Item desvinculado com sucesso');
        $this->dispatchBrowserEvent('atualizarResposta', ['resposta_id' => $resposta_id]);
        $this->checklist->load('demandas');
    }
}
