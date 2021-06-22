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
    public $hierarquia;

    protected $listeners = ['atualizarData' => 'atualizarData'];

    public function render()
    {
        return view('livewire.painel.inconformidade-por-item');
    }

    public function mount()
    {
        $this->data_inicio = DateHelper::getInicioTrimestre(Carbon::parse('now'));
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));

        $this->carregaDados();
    }

    private function carregaDados()
    {
        $dados = RelatoriosService::InconformidadePorItem($this->data_inicio, $this->data_final);

        $itens = $dados->mapWithKeys(function ($item) {
            return [$item->id => $item->nome];
        });

        $this->cores = $dados->mapWithKeys(function ($item) {
            return [$item->id => $item->cor];
        });
        //dd($itens);

        $dados_grafico = $dados->mapWithKeys(function ($item) use ($itens) {
            return [$itens[$item->id] => (float) $item->percentual_inconforme];
        });

        $this->dados = $dados_grafico;

        $this->dispatchBrowserEvent('atualizarGraficoItem', ['label' => json_encode($this->dados->keys()), 'cores' => json_encode($this->cores->values()), 'dados' => json_encode($this->dados->values())]);
    }

    public function atualizarData($data_inicio, $data_final)
    {
        $this->data_inicio = Carbon::createFromFormat('Y-m-d', $data_inicio);
        $this->data_final = Carbon::createFromFormat('Y-m-d', $data_final);
        $this->carregaDados();
    }
}
