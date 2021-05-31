<?php

namespace App\Http\Livewire\Guia;

use App\Models\ChecklistItem;
use App\Models\Guia;
use App\Models\GuiaItem;
use App\Services\ChecklistItemService;
use App\Services\GuiaService;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;

class Cadastro extends Component
{
    use WithFileUploads;

    public $guia_id;
    public $checklistItens = [];

    public $descricao;
    public $checklist_item_id;

    public $fotosenviadas = [];
    public $fotosexistentes = [];

    public $item_is_pergunta = false;
    public $pergunta = '';
    public $resposta = '';
    public $itens = [];

    protected $listeners = ['limpar' => 'limpar', 'excluirGuia' => 'excluir','carregaGuia' => 'carregaGuia'];

    public function mount()
    {
        $this->checklistItens = ChecklistItem::with('guia')->get();
    }

    public function rules() {

        $regras = Guia::VALIDATION_RULES();

        if($this->guia_id != '')
            $regras = Guia::VALIDATION_RULES($this->guia_id);

        return $regras;
    }

    public function messages() {
        return Guia::VALIDATION_MESSAGES;
    }

    public function updatedFotosenviadas()
    {
        $this->validate([
            'fotosenviadas.*' => 'image', // 1MB Max
        ]);
        $this->fotosexistentes  = array_merge($this->fotosexistentes, $this->fotosenviadas);
    }

    public function removerFoto($index)
    {
        unset($this->fotosexistentes[$index]);
    }

    public function render()
    {
        return view('livewire.guia.cadastro');
    }

    public function incluirItem()
    {
        $this->validate([
            'item_is_pergunta' => 'boolean',
            'pergunta' => 'required',
            'resposta' => 'required_if:item_is_pergunta,true',
        ]);

        $this->itens[] = ['id' => null, 'pergunta' => $this->pergunta, 'resposta' => $this->resposta];


        $this->pergunta = '';
        $this->resposta = '';

        $this->resetValidation();
    }

    public function removerItem($index)
    {
        unset($this->itens[$index]);
    }

    public function salvar()
    {
        $data = $this->validate();

        $guiaService = new GuiaService();
        $checklistitemService = new ChecklistItemService();
        $checklistItem = $checklistitemService->findById($this->checklist_item_id);

        $data['itens'] = $this->itens;
        $data['imagens'] = $this->fotosexistentes;

        try {

            if($this->guia_id && $guiaService->existsById($this->guia_id))
                $guiaService->atualizar($data, $this->guia_id);
            else
                $guiaService->criar($data);

            $this->dispatchBrowserEvent('triggerSucesso',$checklistItem->nome);
        }catch (Exception $e){
            $this->dispatchBrowserEvent('triggerError',$e->getMessage());
        }

    }

    public function excluir($guia_id)
    {
        $guiaService = new GuiaService();
        $guiaService->excluir($guia_id);
        $this->limpar();
        $this->dispatchBrowserEvent('triggerSucessoExclusao');
    }

    public function limpar()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset();
        $this->checklistItens = ChecklistItem::with('guia')->get();
    }

    public function carregaGuia($guiaId)
    {
        $guiaService = new GuiaService();

        $guia = $guiaService->findById($guiaId);

        $this->guia_id  = $guiaId;

        $this->descricao  = $guia->descricao;
        $this->checklist_item_id  = $guia->checklist_item_id;

        foreach($guia->imagens as $imagem)
        {
            $this->fotosexistentes[] = $imagem;
        }

        foreach($guia->itens() as $item) {
            $this->itens[] = ['id' => $item->id, 'pergunta' => $item->pergunta, 'resposta' => $item->resposta];
        }

    }
}
