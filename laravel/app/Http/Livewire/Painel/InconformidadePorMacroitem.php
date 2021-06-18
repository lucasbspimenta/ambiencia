<?php

namespace App\Http\Livewire\Painel;

use App\Http\Helpers\DateHelper;
use App\Services\RelatoriosService;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class InconformidadePorMacroitem extends Component
{
    public $dados = [];
    public $cores = [];
    public $data_inicio;
    public $data_final;
    public $perfil;

    protected $listeners = ['atualizarData' => 'atualizarData'];

    public function render()
    {
        return view('livewire.painel.inconformidade-por-macroitem');
    }

    public function mount()
    {
        $this->data_inicio = DateHelper::getInicioTrimestre(Carbon::parse('now'));
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));

        $this->perfil = null;

        if(Auth::user()->is_gestor)
            $this->perfil = 'supervisor';

        if(Auth::user()->is_gestor_equipe)
            $this->perfil = 'coordenador';

        if(Auth::user()->is_matriz)
            $this->perfil = 'matriz';

        $this->dados = RelatoriosService::InconformidadePorMacroitem($this->data_inicio, $this->data_final, $this->perfil);
        $cores = RelatoriosService::CorPorItem();

        foreach($this->dados as $key => $item)
        {
            $this->cores[] = $cores[$key];
        }
    }

    public function atualizarData($data_inicio, $data_final)
    {
        $this->data_inicio  = Carbon::createFromFormat('Y-m-d', $data_inicio);
        $this->data_final  = Carbon::createFromFormat('Y-m-d', $data_final);
        $this->dados = RelatoriosService::InconformidadePorMacroitem($this->data_inicio, $this->data_final, $this->perfil);

        $cores = RelatoriosService::CorPorItem();

        foreach($this->dados as $key => $item)
        {
            $this->cores[] = $cores[$key];
        }

        $this->dispatchBrowserEvent('atualizarGraficoMacroItem', [ 'label' => json_encode($this->dados->keys()), 'cores' => json_encode(array_values($this->cores)), 'dados' => json_encode($this->dados->values())]);
    }
}
