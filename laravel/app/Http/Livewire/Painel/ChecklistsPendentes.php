<?php

namespace App\Http\Livewire\Painel;

use App\Models\Checklist;
use App\Services\RelatoriosService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChecklistsPendentes extends Component
{
    public $checklists = [];
    public $dados_subordinados = [];
    public $funcao_gerencial = false;

    public function render()
    {
        return view('livewire.painel.checklists-pendentes');
    }

    public function mount()
    {
        $this->funcao_gerencial = Auth::user()->is_gestor;

        if($this->funcao_gerencial)
        {
            if(Auth::user()->is_gestor_equipe)
                $this->dados_subordinados = RelatoriosService::PreenchimentoChecklistCoordenador(Auth::user()->matricula)->toArray();
            else
                $this->dados_subordinados = RelatoriosService::PreenchimentoChecklistSupervisor(Auth::user()->matricula)->toArray();
        }
        else
        {
            $this->checklists = RelatoriosService::PreenchimentoChecklist()->sortByDesc('inicio');
        }
    }
}
