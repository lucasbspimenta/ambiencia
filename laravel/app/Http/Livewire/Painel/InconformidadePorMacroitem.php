<?php

namespace App\Http\Livewire\Painel;

use App\Http\Helpers\DateHelper;
use App\Services\RelatoriosService;
use Carbon\Carbon;
use Livewire\Component;

class InconformidadePorMacroitem extends Component
{
    public $dados_values = [];
    public $dados_keys = [];
    public $cores_values = [];
    public $cores_keys = [];
    protected $data_inicio;
    protected $data_final;
    public $hierarquia;

    protected $listeners = ['atualizarData' => 'atualizarData'];

    public function render()
    {
        return view('livewire.painel.inconformidade-por-macroitem');
    }

    public function mount()
    {
        $this->data_inicio = DateHelper::getInicioTrimestre(Carbon::parse('now'));
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));
        $this->dados_keys = [];
        $this->dados_values = [];
        $this->cores_values = [];
        $this->carregaDados();
    }

    private function carregaDados()
    {
        $dados = RelatoriosService::InconformidadePorMacroitem($this->data_inicio, $this->data_final);

        $itens = $dados->mapWithKeys(function ($item) {
            return [$item->id => $item->nome];
        });

        $cores = $dados->mapWithKeys(function ($item) {
            return [$item->id => $item->cor];
        });

        $dados_grafico = $dados->mapWithKeys(function ($item) use ($itens) {
            return [$itens[$item->id] => (float) $item->percentual_inconforme];
        });

        $this->dados_keys = json_encode($dados_grafico->keys());
        $this->dados_values = json_encode($dados_grafico->values());
        $this->cores_values = json_encode($cores->values());

        $this->dispatchBrowserEvent('atualizarGraficoMacroItem', ['label' => $this->dados_keys, 'cores' => $this->cores_values, 'dados' => $this->dados_values]);
    }

    public function atualizarData($data_inicio, $data_final)
    {
        $this->data_inicio = Carbon::createFromFormat('Y-m-d', $data_inicio);
        $this->data_final = Carbon::createFromFormat('Y-m-d', $data_final);
        $this->dados_keys = [];
        $this->dados_values = [];
        $this->cores_values = [];
        $this->carregaDados();
    }
}
