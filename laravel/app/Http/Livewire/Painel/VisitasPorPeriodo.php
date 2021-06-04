<?php

namespace App\Http\Livewire\Painel;

use App\Services\RelatoriosService;
use Livewire\Component;

class VisitasPorPeriodo extends Component
{
    public $dados = [];

    public function render()
    {
        return view('livewire.painel.visitas-por-periodo');
    }

    public function mount()
    {
        $this->dados = collect(RelatoriosService::VisitaPorPeriodo())->toArray();

    }
}
