<?php

namespace App\Http\Livewire\Checklist;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\ChecklistResposta;
use Livewire\Component;

class Macroitem extends Component
{
    public $checklist;
    public $macroitem;
    public $itens;
    public $macroitem_reposta;

    public function render()
    {
        return view('livewire.checklist.macroitem');
    }

    public function mount(Checklist $checklist, ChecklistItem $macroitem)
    {
        $this->macroitem = $macroitem;
        $this->checklist = $checklist;
        $this->itens = $checklist->respostasMacroitem($this->macroitem->id)->with('item')->get();
        $this->macroitem_reposta = ChecklistResposta::where('checklist_item_id',$this->macroitem->id)->where('checklist_id',$this->checklist->id)->get()->first();
    }


}
