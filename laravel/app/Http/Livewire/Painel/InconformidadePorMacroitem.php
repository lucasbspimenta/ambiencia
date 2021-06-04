<?php

namespace App\Http\Livewire\Painel;

use App\Services\RelatoriosService;
use Livewire\Component;

class InconformidadePorMacroitem extends Component
{
    public $dados = [];
    public $cores = [];

    public function render()
    {
        return view('livewire.painel.inconformidade-por-macroitem');
    }

    public function mount()
    {
        $this->dados = RelatoriosService::InconformidadePorMacroitem();
        $cores = RelatoriosService::CorPorItem();

        foreach($this->dados as $key => $item)
        {
            $this->cores[] = $cores[$key];
        }
    }
}
