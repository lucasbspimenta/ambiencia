<?php

namespace App\Http\Livewire\Checklist;

use App\Services\ChecklistService;
use Livewire\Component;

class MacroitemProgresso extends Component
{
    public $macroitem;
    public $checklist;
    public $progresso;

    protected function getListeners()
    {
        return ['atualizaProgressoMacroitem' . $this->macroitem->id => 'atualizar'];
    }

    public function render()
    {
        return view('livewire.checklist.macroitem-progresso');
    }

    public function mount()
    {
        $this->atualizar();
    }

    public function atualizar() {

        $service = new ChecklistService();
        $this->progresso = $service->getMacroitemProgresso($this->checklist, $this->macroitem);

        if($this->progresso >= 100)
        {
            $this->dispatchBrowserEvent('fecharMacroitem',['id' => $this->macroitem->id]);
        }
    }
}
