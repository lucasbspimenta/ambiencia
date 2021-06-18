<?php

namespace App\Http\Livewire\Painel;

use App\Http\Helpers\DateHelper;
use App\Services\RelatoriosService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VisitasPorPeriodo extends Component
{
    public $dados = [];
    public $dados_subordinados = [];
    public $data_inicio;
    public $data_final;
    public $funcao_gerencial = false;

    protected $listeners = ['atualizarData' => 'atualizarData'];

    public function render()
    {
        return view('livewire.painel.visitas-por-periodo');
    }

    public function mount()
    {
        $this->data_inicio = DateHelper::getInicioTrimestre(Carbon::parse('now'));
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));
        $this->dados = collect(RelatoriosService::VisitaPorPeriodo($this->data_inicio, $this->data_final))->toArray();
        $this->funcao_gerencial = Auth::user()->is_gestor;

        if($this->funcao_gerencial)
        {
            if(Auth::user()->is_gestor_equipe)
                $this->dados_subordinados = RelatoriosService::VisitaPorPeriodoCoordenador(Auth::user()->matricula, $this->data_inicio, $this->data_final)->toArray();
            elseif(Auth::user()->is_matriz)
                $this->dados_subordinados = RelatoriosService::VisitaPorPeriodoMatriz($this->data_inicio, $this->data_final)->toArray();
            else
                $this->dados_subordinados = RelatoriosService::VisitaPorPeriodoSupervisor(Auth::user()->matricula, $this->data_inicio, $this->data_final)->toArray();
        }
    }

    public function atualizarData($data_inicio, $data_final)
    {
        $this->data_inicio  = Carbon::createFromFormat('Y-m-d', $data_inicio);
        $this->data_final  = Carbon::createFromFormat('Y-m-d', $data_final);
        $this->dados = collect(RelatoriosService::VisitaPorPeriodo($this->data_inicio, $this->data_final))->toArray();

        if($this->funcao_gerencial)
        {
            if(Auth::user()->is_gestor_equipe)
                $this->dados_subordinados = RelatoriosService::VisitaPorPeriodoCoordenador(Auth::user()->matricula, $this->data_inicio, $this->data_final)->toArray();
            elseif(Auth::user()->is_matriz)
                $this->dados_subordinados = RelatoriosService::VisitaPorPeriodoMatriz($this->data_inicio, $this->data_final)->toArray();
            else
                $this->dados_subordinados = RelatoriosService::VisitaPorPeriodoSupervisor(Auth::user()->matricula, $this->data_inicio, $this->data_final)->toArray();
        }
    }
}
