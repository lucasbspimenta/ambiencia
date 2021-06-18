<?php

namespace App\Http\Livewire\Painel;

use App\Models\Checklist;
use App\Models\User;
use App\Services\RelatoriosService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Http\Helpers\DateHelper;
use Carbon\Carbon;

class ChecklistsPendentes extends Component
{
    public $checklists = [];
    public $dados_subordinados = [];
    public $funcao_gerencial = false;
    public $data_inicio;
    public $data_final;

    public $matricula_subordinado = false;
    protected $matricula_anterior = [];

    protected $listeners = ['atualizarData' => 'atualizarData'];

    public function render()
    {
        return view('livewire.painel.checklists-pendentes');
    }

    public function mount()
    {
        $this->checklists = [];
        $this->dados_subordinados = [];
        $this->matricula_anterior = [];
        $this->matricula_subordinado = false;
        $this->funcao_gerencial = Auth::user()->is_gestor;
        $this->data_inicio = DateHelper::getInicioTrimestre(Carbon::parse('now'));
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));

        if($this->funcao_gerencial)
        {
            if(Auth::user()->is_gestor_equipe)
                $this->dados_subordinados = RelatoriosService::PreenchimentoChecklistCoordenador(Auth::user()->matricula)->toArray();
            elseif(Auth::user()->is_matriz)
                $this->dados_subordinados = RelatoriosService::PreenchimentoChecklistMatriz()->toArray();
            else
                $this->dados_subordinados = RelatoriosService::PreenchimentoChecklistSupervisor(Auth::user()->matricula)->toArray();
        }
        else
        {
            $this->checklists = RelatoriosService::PreenchimentoChecklist()->sortByDesc('inicio');
        }
    }

    public function exibirSubordinado($matricula)
    {
        array_push($this->matricula_anterior, $this->matricula_subordinado);
        $this->atualizarDados($matricula);
    }

    public function voltar()
    {
        dd($this->matricula_anterior, $anterior);

        $anterior = array_pop($this->matricula_anterior);
        
        if($anterior != false)
            $this->atualizarDados($anterior);
        else
            $this->mount();
    }

    private function atualizarDados($matricula)
    {
        $this->matricula_subordinado = $matricula;
        $user = User::firstWhere('matricula', $matricula);

        if($user->is_gestor){
            
            $this->checklists = [];

            if($user->is_gestor_equipe)
                $this->dados_subordinados = RelatoriosService::PreenchimentoChecklistCoordenador($user->matricula)->toArray();
            else
                $this->dados_subordinados = RelatoriosService::PreenchimentoChecklistSupervisor($user->matricula)->toArray();
        }
        else{
            $this->dados_subordinados = [];
            $this->checklists = RelatoriosService::PreenchimentoChecklist($user->matricula)->sortByDesc('inicio');
        }
    }
}
