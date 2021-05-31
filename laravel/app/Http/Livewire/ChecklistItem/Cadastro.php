<?php

namespace App\Http\Livewire\ChecklistItem;

use App\Models\ChecklistItem;
use App\Services\ChecklistItemService;
use Exception;
use Livewire\Component;

class Cadastro extends Component
{
    public $checklistitem_id='';

    public $nome='';
    public $cor='';
    public $descricao='';
    public $situacao=false;
    public $foto_boolean=false;
    public $foto='N';
    public $ordem='';
    public $item_pai_id=null;

    public $macroitens;

    protected $listeners = ['limpar' => 'limpar', 'carregaChecklistItem' => 'carregaChecklistItem', 'excluirChecklistItem' => 'excluir', 'defineItemPai' => 'defineItemPai'];

    public function rules() {

        $regras = ChecklistItem::VALIDATION_RULES();

        if($this->checklistitem_id != '')
            $regras = ChecklistItem::VALIDATION_RULES($this->checklistitem_id);

        return $regras;
    }

    public function messages() {
        return ChecklistItem::VALIDATION_MESSAGES;
    }

    public function render()
    {
        return view('livewire.checklist-item.cadastro');
    }

    public function salvar()
    {
        $this->foto = ($this->foto_boolean ? 'S' : 'N');

        $data = $this->validate();
        $checklistItemService = new ChecklistItemService();

        try {

            if($this->checklistitem_id && $checklistItemService->existsById($this->checklistitem_id))
                $checklistItemService->atualizar($data, $this->checklistitem_id);
            else
                $checklistItemService->criar($data);

            $this->dispatchBrowserEvent('triggerSucesso',$this->nome);
        }catch (Exception $e){
            $this->dispatchBrowserEvent('triggerError',$e->getMessage());
        }

    }

    public function carregaChecklistItem($checklistitem_id)
    {
        $checklistItemService = new ChecklistItemService();

        $checklistItem = $checklistItemService->findById($checklistitem_id);

        $this->checklistitem_id  = $checklistitem_id;
        $this->nome  = $checklistItem->nome;
        $this->cor  = $checklistItem->cor;
        $this->descricao  = $checklistItem->descricao;
        $this->situacao  = (bool)$checklistItem->situacao;
        $this->ordem  = $checklistItem->ordem;
        $this->foto  = $checklistItem->foto;
        $this->foto_boolean  = $checklistItem->foto == 'S';
        $this->item_pai_id  = $checklistItem->item_pai_id;
    }

    public function excluir($checklistitem_id)
    {
        $checklistItemService = new ChecklistItemService();
        $checklistItemService->excluir($checklistitem_id);
        $this->limpar();
        $this->dispatchBrowserEvent('triggerSucessoExclusao');
    }

    public function limpar()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset([
            'nome',
            'cor',
            'descricao',
            'situacao',
            'foto_boolean',
            'foto',
            'ordem',
            'item_pai_id',
            'checklistitem_id'
        ]);
    }

    public function defineItemPai($id) {
        $this->item_pai_id = $id;
    }
}
