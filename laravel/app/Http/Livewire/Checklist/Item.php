<?php

namespace App\Http\Livewire\Checklist;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\ChecklistResposta;
use Livewire\Component;

class Item extends Component
{
    public $checklist;
    public $macroitem;
    public $itens;
    public $resposta;
    public $resposta_valor = null;

    protected $rules = [
        'resposta.resposta' => 'required'
    ];

    public function render()
    {
        return view('livewire.checklist.item');
    }

    public function mount(ChecklistResposta $resposta)
    {
        $this->resposta = $resposta ?? new ChecklistResposta();
        $this->resposta_valor = $resposta->resposta;
    }

    public function updatedRespostaResposta($valor)
    {
        $this->resposta->save();
        $this->emit('atualizaProgressoChecklist')->to('checklist.botao-finalizar');
    }

    public function atualizar()
    {
        $this->resposta->refresh();
        $this->emit('atualizaProgressoChecklist')->to('checklist.botao-finalizar');
    }
}
