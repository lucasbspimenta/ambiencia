<?php

namespace App\Http\Livewire\Checklist;

use App\Models\Checklist;
use App\Services\AgendamentoService;
use App\Services\ChecklistService;
use Livewire\Component;

class Agendamento extends Component
{
    public $checklist_id;
    public $agendamento_id;
    public $agendamentos = [];

    protected $listeners = ['limpar' => 'limpar', 'excluirChecklist' => 'excluirChecklist'];

    public function rules()
    {
        return Checklist::VALIDATION_RULES();
    }

    public function messages()
    {
        return Checklist::VALIDATION_MESSAGES;
    }

    public function render()
    {
        return view('livewire.checklist.agendamento');
    }

    public function mount()
    {
        $agendamentoService = new AgendamentoService();
        //dd($this->agendamentos);
    }

    public function limpar()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset();

        $agendamentoService = new AgendamentoService();
        //$this->agendamentos = $agendamentoService->agendamentosSemChecklist();
    }

    public function salvar()
    {
        $data = $this->validate();

        try {
            $checklistService = new ChecklistService();
            $this->checklist_id = $checklistService->criar($data);
            $this->dispatchBrowserEvent('triggerSucesso', $this->checklist_id);

            $this->limpar();

        } catch (Exception $e) {
            $this->dispatchBrowserEvent('triggerError', $e->getMessage());
        }
    }

    public function excluirChecklist($checklist_id)
    {
        $checklistService = new ChecklistService();
        $checklistService->excluir($checklist_id);
        $this->limpar();
        $this->dispatchBrowserEvent('triggerSucessoExclusao');
    }
}
