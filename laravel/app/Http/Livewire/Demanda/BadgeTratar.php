<?php

namespace App\Http\Livewire\Demanda;

use App\Services\DemandaService;
use Livewire\Component;

class BadgeTratar extends Component
{
    public $pendentes = 0;

    public function mount()
    {
        $this->atualizarPendentes();
    }

    public function atualizarPendentes()
    {
        $service = new DemandaService();
        $this->pendentes = $service->tratar(true)->first()->total;
    }

    public function render()
    {
        return view('livewire.demanda.badge-tratar');
    }
}
