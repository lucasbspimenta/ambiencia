<?php

namespace App\Http\Livewire\Painel;

use App\Http\Helpers\DateHelper;
use App\Services\RelatoriosService;
use Carbon\Carbon;
use Livewire\Component;

class VisitasPorPeriodo extends Component
{
    public $dados = [];
    public $data_inicio;
    public $data_final;

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
    }

    public function atualizarData($data_inicio, $data_final)
    {
        $this->data_inicio  = Carbon::createFromFormat('Y-m-d', $data_inicio);
        $this->data_final  = Carbon::createFromFormat('Y-m-d', $data_final);
        $this->dados = collect(RelatoriosService::VisitaPorPeriodo($this->data_inicio, $this->data_final))->toArray();

    }
}
