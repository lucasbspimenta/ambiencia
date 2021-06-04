<?php

namespace App\Http\Livewire\Painel;

use App\Models\AgendamentoTipo;
use App\Services\RelatoriosService;
use Livewire\Component;

class VisitasPorTipo extends Component
{
    public $dados = [];
    public $tipos = [];

    public function render()
    {
        return view('livewire.painel.visitas-por-tipo');
    }

    public function mount()
    {
        $this->tipos = AgendamentoTipo::where('situacao',1)->get();
        $percentuais = RelatoriosService::VisitaPorTipo();

        $this->dados = $percentuais->mapWithKeys(function ($item) {
            //dd($item);
            return [$item->agendamento_tipos_id => $item->percentual_visitado];
        })->toArray();
        //dd($this->dados);

    }
}
