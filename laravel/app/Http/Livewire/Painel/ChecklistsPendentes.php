<?php

namespace App\Http\Livewire\Painel;

use App\Models\Checklist;
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
        $this->checklists = Checklist::where('concluido',0)->get()->where('percentual_preenchimento','<=','100')->sortByDesc('agendamento.inicio');
    }
}
