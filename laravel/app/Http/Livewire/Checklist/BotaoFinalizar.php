<?php

namespace App\Http\Livewire\Checklist;

use App\Services\ChecklistService;
use Livewire\Component;

class BotaoFinalizar extends Component
{
    public $checklist;

    protected $listeners = ['atualizaProgressoChecklist' => 'atualizar'];

    public function render()
    {
        return view('livewire.checklist.botao-finalizar');
    }

    public function atualizar() {
        $this->checklist->fresh();
    }

    public function salvar()
    {
        try
        {
            $checklistService = new ChecklistService();
            $sucesso = $checklistService->finalizar($this->checklist->id);
            if($sucesso)
            {
                $this->dispatchBrowserEvent('triggerSucesso','Checklist finalizado com sucesso!');
                return redirect()->route('checklist.show', ['checklist' => $this->checklist->id]);
            }
        }
        catch (Exception $e)
        {
            $this->dispatchBrowserEvent('triggerError',$e->getMessage());
        }
    }
}
