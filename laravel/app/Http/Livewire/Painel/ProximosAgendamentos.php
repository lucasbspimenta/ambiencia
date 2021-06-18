<?php

namespace App\Http\Livewire\Painel;
use App\Http\Helpers\DateHelper;
use App\Models\Agendamento;
use App\Services\RelatoriosAgendamentosService;

use Illuminate\Support\Carbon;
use Livewire\Component;
use Carbon\CarbonInterface;

class ProximosAgendamentos extends Component
{
    public $agendamentos = [];
    public $data_inicio;
    public $data_final;

    protected $listeners = ['atualizarData' => 'atualizarData'];

    public function render()
    {
        return view('livewire.painel.proximos-agendamentos');
    }

    public function mount()
    {
        $this->data_inicio = Carbon::parse('now');
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));
        $this->carregaDados();
    }

    public function atualizarData($data_inicio, $data_final)
    {
        $this->data_inicio  = Carbon::createFromFormat('Y-m-d', $data_inicio);
        $this->data_final  = Carbon::createFromFormat('Y-m-d', $data_final);
        $this->carregaDados();
    }

    public function carregaDados()
    {
        $this->agendamentos = RelatoriosAgendamentosService::Agendamentos($this->data_inicio, $this->data_final);
    }
}
