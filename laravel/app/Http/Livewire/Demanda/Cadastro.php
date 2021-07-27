<?php

namespace App\Http\Livewire\Demanda;

use App\Models\ChecklistResposta;
use App\Models\Demanda;
use App\Models\DemandaSistema;
use App\Models\Unidade;
use App\Services\AtendimentoService;
use App\Services\DemandaService;
use Livewire\Component;

class Cadastro extends Component
{
    public $vinculada_a_resposta = false;
    public $unidade_id;
    public $sistema_id;
    public $categoriaSelecionado;
    public $subcategoriaSelecionado;
    public $sistema_item_id;
    public $descricao;
    public $checklist_resposta_id;

    public $demandaExistentes = [];
    public $sistemas = [];
    public $demanda_nova = 'S';
    public $demanda_vinculacao = 'C';
    public $resposta;
    public $unidade;
    public $demandaExistenteSelecionada;

    protected $listeners = ['limpar' => 'limpar', 'defineResposta' => 'defineResposta', 'defineDemanda' => 'defineDemanda', 'excluirExterno' => 'excluirExterno'];

    public function rules()
    {
        return ($this->resposta && $this->resposta->id) ? Demanda::VALIDATION_RULES + ['resposta' => ['required']] : Demanda::VALIDATION_RULES;
    }

    public function messages()
    {
        return ($this->resposta && $this->resposta->id) ? Demanda::VALIDATION_MESSAGES + ['resposta.required' => 'Demanda deve estar vinculada ao item'] : Demanda::VALIDATION_MESSAGES;
    }

    public function render()
    {
        return view('livewire.demanda.cadastro');
    }

    public function mount($unidades = null)
    {
        $this->sistemas = DemandaSistema::all() ?? [];
    }

    public function updatedSistemaId()
    {
        $this->reset(['categoriaSelecionado', 'subcategoriaSelecionado', 'sistema_item_id', 'demanda_nova', 'demanda_vinculacao']);
        if ($this->sistema_id) {
            $sistema_selecionado = $this->sistemas->where('id', $this->sistema_id)->first();
            if ($sistema_selecionado->itens->count() == 1) {
                //dd($sistema_selecionado->itens->first());
                $this->sistema_item_id = $sistema_selecionado->itens->first()->id;
            }
        }
    }

    public function updatedUnidadeId()
    {
        $this->unidade = Unidade::findOrFail($this->unidade_id);
    }

    public function updatedDemandaVinculacao()
    {
        if ($this->unidade_id && $this->unidade && $this->demanda_nova != 'S') {
            if ($this->demanda_vinculacao == 'D') {

                if ($this->sistema_id == 1) {
                    $this->demandaExistentes = AtendimentoService::atendimentoEmAndamentoUnidade($this->unidade_id);
                } else {
                    $this->demandaExistentes = [];
                }

            } elseif ($this->demanda_vinculacao == 'C') {
                $this->demandaExistentes = $this->resposta->checklist->demandas->where('sistema_id', $this->sistema_id);
            } else {
                $this->reset(['demandaExistentes', 'demandaExistenteSelecionada']);
            }
        } else {
            $this->reset(['demandaExistentes', 'demandaExistenteSelecionada']);
        }
    }

    public function updatedDemandaNova()
    {
        $this->updatedDemandaVinculacao();
    }

    public function hydrateDemandaVinculacao()
    {
        $this->updatedDemandaVinculacao();
    }

    public function salvar()
    {
        if ($this->demanda_nova == 'S') {
            $id = $this->salvarDemandaNova();
        } else {

            if ($this->demanda_vinculacao == 'C') {
                $id = $this->demandaExistenteSelecionada;
            }

            if ($this->demanda_vinculacao == 'D') {
                $id = $this->vinculaDemandaExterna();
            }
        }

        if ($id) {
            if ($this->vinculada_a_resposta) {
                $this->vinculaDemanda($id);
            } else {
                $this->dispatchBrowserEvent('triggerSucesso', '&nbsp;');
                $this->limpar();
            }
        } else {
            $this->dispatchBrowserEvent('triggerError', 'Não foi possivel criar demanda');
        }
    }

    protected function salvarDemandaNova()
    {
        $data = $this->validate();

        if (!$this->vinculada_a_resposta) {
            $data['migracao'] = 'P';
        }

        $demandaService = new DemandaService();
        try {
            $demanda = $demandaService->criar($data);
            return $demanda->id;
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('triggerError', $e->getMessage());
        }
    }

    protected function vinculaDemanda($demanda_id)
    {
        $this->resposta->demandas()->attach($demanda_id);
        $this->emit('atualizar')->to('demanda.vinculadas');
        $this->dispatchBrowserEvent('triggerSucesso', $this->resposta->item->nome);
        $this->limpar();
        $this->dispatchBrowserEvent('atualizarResposta', ['resposta_id' => $this->checklist_resposta_id]);
    }

    protected function vinculaDemandaExterna()
    {
        $dem = $this->demandaExistentes[$this->demandaExistenteSelecionada];
        $data = $dem->attributesToArray();
//        dd($data);
        $demandaService = new DemandaService();
        try {
            $demanda = $demandaService->criar($data);
            return $demanda->id;
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('triggerError', $e->getMessage());
        }
    }

    public function limpar()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset();
        $this->sistemas = DemandaSistema::all() ?? [];
    }

    public function defineResposta($id)
    {
        $this->resposta = ChecklistResposta::find($id);
        if ($this->resposta) {
            $this->checklist_resposta_id = $id;
            $this->vinculada_a_resposta = 'S';
            $this->unidade_id = $this->resposta->checklist->agendamento->unidade->id;
            $this->unidade = Unidade::find($this->unidade_id);
            $this->demandaExistentes = $this->resposta->checklist->agendamento->unidade->demandas;
        }
    }
}
