<?php

namespace App\Http\Livewire\Checklist;

use App\Services\AgendamentoService;
use Livewire\Component;

class BotaoNovo extends Component
{
    public $agendamentos_sem_checklist;

    protected $listeners = ['atualizarBotaoIncluir' => 'atualizarBotaoIncluir'];

    public function render()
    {
        return view('livewire.checklist.botao-novo');
    }

    public function mount()
    {
        $agendamentoService = new AgendamentoService();
        $this->agendamentos_sem_checklist = $agendamentoService->agendamentosSemChecklist();
    }

    public function atualizarBotaoIncluir()
    {
        $agendamentoService = new AgendamentoService();
        $this->agendamentos_sem_checklist = $agendamentoService->agendamentosSemChecklist();
    }
}
