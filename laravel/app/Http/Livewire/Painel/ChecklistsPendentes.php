<?php

namespace App\Http\Livewire\Painel;

use App\Models\Checklist;
use App\Services\RelatoriosService;
use Livewire\Component;

class ChecklistsPendentes extends Component
{
    public $checklists = [];

    public function render()
    {
        return view('livewire.painel.checklists-pendentes');
    }

    public function mount()
    {
        $this->checklists = RelatoriosService::PreenchimentoChecklist()->sortByDesc('inicio');
    }
}
