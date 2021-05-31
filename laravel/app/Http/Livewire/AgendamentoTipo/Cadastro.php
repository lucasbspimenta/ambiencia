<?php

namespace App\Http\Livewire\AgendamentoTipo;

use App\Models\AgendamentoTipo;
use App\Services\AgendamentoTipoService;
use Exception;
use Livewire\Component;

class Cadastro extends Component
{
    public $agendamentotipo_id='';

    public $nome='';
    public $cor='';
    public $descricao='';
    public $situacao=false;
    public $ordem='';

    private $agendamentoTipoService;

    protected $listeners = ['limpar' => 'limpar', 'carregaTipoDeAgendamento' => 'carregaTipoDeAgendamento', 'excluirTipoDeAgendamento' => 'excluir'];

    public function rules() {

        $regras = AgendamentoTipo::VALIDATION_RULES;

        if($this->agendamentotipo_id != '')
            $regras['nome'] = ['required','unique:agendamento_tipos,nome,' . $this->agendamentotipo_id];

        return $regras;
    }

    public function messages() {
        return AgendamentoTipo::VALIDATION_MESSAGES;
    }

    public function render()
    {
        return view('livewire.agendamento-tipo.cadastro');
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
            'ordem',
            'agendamentotipo_id'
        ]);
    }

    public function salvar()
    {
        $data = $this->validate();
        $this->agendamentoTipoService = new AgendamentoTipoService();

        try {

            if($this->agendamentotipo_id && $this->agendamentoTipoService->existsById($this->agendamentotipo_id))
                $this->agendamentoTipoService->atualizar($data, $this->agendamentotipo_id);
            else
                $this->agendamentoTipoService->criar($data);

            $this->dispatchBrowserEvent('triggerTipoAgendamentoGravadoSucesso',$this->nome);
        }catch (Exception $e){
            $this->dispatchBrowserEvent('triggerError',$e->getMessage());
        }

    }

    public function carregaTipoDeAgendamento($agendamentotipo_id)
    {
        $this->agendamentoTipoService = new AgendamentoTipoService();

        $agendamentoTipo = $this->agendamentoTipoService->findById($agendamentotipo_id);

        $this->nome  = $agendamentoTipo->nome;
        $this->cor  = $agendamentoTipo->cor;
        $this->descricao  = $agendamentoTipo->descricao;
        $this->situacao  = (bool)$agendamentoTipo->situacao;
        $this->ordem  = $agendamentoTipo->ordem;
        $this->agendamentotipo_id  = $agendamentoTipo->id;
    }

    public function excluir($agendamentotipo_id)
    {
        $this->agendamentoTipoService = new AgendamentoTipoService();
        $this->agendamentoTipoService->excluir($agendamentotipo_id);
        $this->limpar();
        $this->dispatchBrowserEvent('triggerTipoAgendamentoExcluidoSucesso');
    }
}
