<?php

namespace App\Http\Livewire\Painel;

use App\Http\Helpers\DateHelper;
use App\Services\RelatoriosService;
use Carbon\Carbon;
use Livewire\Component;

class InconformidadePorItem extends Component
{
    public $dados = [];
    public $cores = [];
    public $data_inicio;
    public $data_final;

    public function render()
    {
        return view('livewire.painel.inconformidade-por-item');
    }

    public function mount()
    {
        $this->data_inicio = DateHelper::getInicioTrimestre(Carbon::parse('now'));
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));

        $this->dados = RelatoriosService::InconformidadePorItem($this->data_inicio, $this->data_final);
        $cores = RelatoriosService::CorPorItem();

        foreach($this->dados as $key => $item)
        {
            $this->cores[] = $cores[$key];
        }
    }
}
