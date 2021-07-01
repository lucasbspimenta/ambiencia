<?php

namespace App\Http\Livewire\Demanda;

use App\Models\ChecklistResposta;
use App\Models\Demanda;
use App\Models\DemandaSistema;
use App\Models\Unidade;
use App\Services\DemandaService;
use Exception;
use Livewire\Component;

class Cadastro extends Component
{
    public $demanda_id;

    public $sistemas = [];
    public $categorias = [];
    public $subcategorias = [];
    public $itens = [];
    public $demandaExistentes = [];

    public $sistema;
    public $resposta;

    public $unidade_id;
    public $sistema_id;
    public $categoriaSelecionado;
    public $subcategoriaSelecionado;
    public $sistema_item_id;
    public $descricao;
    public $checklist_resposta_id;
    public $demanda_antiga;

    protected $listeners = ['limpar' => 'limpar', 'defineResposta' => 'defineResposta', 'defineDemanda' => 'defineDemanda'];

    public function rules()
    {

        return ($this->resposta && $this->resposta->id) ? Demanda::VALIDATION_RULES + ['checklist_resposta_id' => ['required']] : Demanda::VALIDATION_RULES;
    }

    public function messages()
    {
        return ($this->resposta && $this->resposta->id) ? Demanda::VALIDATION_MESSAGES + ['checklist_resposta_id.required' => 'Demanda deve estar vinculada ao item'] : Demanda::VALIDATION_MESSAGES;
    }

    public function render()
    {
        return view('livewire.demanda.cadastro');
    }

    public function mount($unidades = null)
    {
        $this->sistema = new DemandaSistema();
        $this->sistemas = DemandaSistema::all() ?? [];
        $this->sistema_id = null;
        $this->categoriaSelecionado = null;
        $this->subcategoriaSelecionado = null;
        $this->sistema_item_id = null;
        $this->unidade_id = null;
        $this->demanda_antiga = null;
    }

    public function updatedSistemaSelecionado()
    {
        $this->sistema = DemandaSistema::find($this->sistema_id);
    }

    public function defineResposta($id)
    {
        $this->checklist_resposta_id = $id;
        $this->resposta = ChecklistResposta::find($id);
        $this->unidade_id = $this->resposta->checklist->agendamento->unidade->id;
        $this->demandaExistentes = $this->resposta->checklist->agendamento->unidade->demandas;
    }

    public function defineDemanda($id)
    {
        $demandaService = new DemandaService();
        $demanda = $demandaService->findById($this->demanda_id);
        $this->demanda_id = $demanda->id;
    }

    public function salvar()
    {
        if ($this->demanda_antiga) {
            $this->resposta->demandas()->attach($this->demanda_antiga);
            $this->emit('atualizar')->to('demanda.vinculadas');
            $this->dispatchBrowserEvent('triggerSucesso', $this->resposta->item->nome);
            $this->limpar();
            $this->dispatchBrowserEvent('atualizarResposta', ['resposta_id' => $this->checklist_resposta_id]);
            return true;
        }

        $data = $this->validate();
        $demandaService = new DemandaService();

        try {

            if ($this->demanda_id && $demandaService->existsById($this->demanda_id)) {
                $demanda = $demandaService->atualizar($data, $this->demanda_id);
            } else {
                $demanda = $demandaService->criar($data);
            }

            if ($this->resposta && $this->resposta->id) {
                $this->resposta->demandas()->attach($demanda->id);
                $this->emit('atualizar')->to('demanda.vinculadas');
                $this->dispatchBrowserEvent('triggerSucesso', $this->resposta->item->nome);
                $this->limpar();
                $this->dispatchBrowserEvent('atualizarResposta', ['resposta_id' => $this->checklist_resposta_id]);
            } else {
                $this->dispatchBrowserEvent('triggerSucesso', '');
                $this->limpar();
            }

        } catch (Exception $e) {
            $this->dispatchBrowserEvent('triggerError', $e->getMessage());
        }
    }

    public function limpar()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        //$this->reset();

        $this->sistema = new DemandaSistema();
        $this->resposta = new ChecklistResposta();
        $this->sistemas = DemandaSistema::all() ?? [];

        $this->demanda_id = null;
        $this->sistema_id = null;
        $this->categoriaSelecionado = null;
        $this->subcategoriaSelecionado = null;
        $this->sistema_item_id = null;
        $this->checklist_resposta_id = null;
        $this->unidade_id = null;
    }
}
