<?php

namespace App\Http\Livewire\Painel;

use App\Http\Helpers\DateHelper;
use App\Models\AgendamentoTipo;
use App\Services\RelatoriosService;
use Carbon\Carbon;
use Livewire\Component;

class VisitasPorTipo extends Component
{
    public $dados = [];
    public $tipos = [];
    public $data_inicio;
    public $data_final;

    protected $listeners = ['atualizarData' => 'atualizarData'];

    public function render()
    {
        return view('livewire.painel.visitas-por-tipo');
    }

    public function mount()
    {
        $this->data_inicio = DateHelper::getInicioTrimestre(Carbon::parse('now'));
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));

        $this->tipos = AgendamentoTipo::where('situacao',1)->get();
        $percentuais = RelatoriosService::VisitaPorTipo($this->data_inicio, $this->data_final);

        $this->dados = $percentuais->mapWithKeys(function ($item) {
            return [$item->agendamento_tipos_id => $item->percentual_visitado];
        })->toArray();

    }

    public function atualizarData($data_inicio, $data_final)
    {
        $this->data_inicio  = Carbon::createFromFormat('Y-m-d', $data_inicio);
        $this->data_final  = Carbon::createFromFormat('Y-m-d', $data_final);
        $percentuais = RelatoriosService::VisitaPorTipo($this->data_inicio, $this->data_final);

        $this->dados = $percentuais->mapWithKeys(function ($item) {
            return [$item->agendamento_tipos_id => $item->percentual_visitado];
        })->toArray();

    }
}
