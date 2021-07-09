<?php

namespace App\Http\Livewire\Agendamento;

use App\Models\Agendamento;
use App\Services\AgendamentoService;
use Exception;
use Livewire\Component;

class Cadastro extends Component
{
    public $agendamento_id = '';

    public $unidade_id = '';
    public $inicio = '';
    public $final = '';
    public $agendamento_tipos_id = '';
    public $descricao = '';
    public $agendamento_tem_checklist = false;
    public $agendamento_checklist_finalizado = false;

    public $tiposagendamentos;

    private $agendamentoService;

    protected $listeners = ['limpar' => 'limpar', 'definirDatas' => 'definirDatas', 'carregaAgendamento' => 'carregaAgendamento', 'excluirAgendamento' => 'excluir'];

    protected $casts = [
        'inicio' => 'date:d/m/Y',
        'final' => 'date:d/m/Y',
    ];

    public function rules()
    {
        return Agendamento::VALIDATION_RULES;
    }

    public function messages()
    {
        return Agendamento::VALIDATION_MESSAGES;
    }

    public function definirDatas($inicio, $final)
    {
        $this->limpar();
        $this->inicio = $inicio;
        $this->final = $final;
    }

    public function render()
    {
        return view('livewire.agendamento.cadastro');
    }

    public function limpar()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset([
            'agendamento_id',
            'descricao',
            'inicio',
            'final',
            'unidade_id',
            'agendamento_tipos_id',
            'agendamento_tem_checklist',
            'agendamento_checklist_finalizado',
        ]);

        //dd($this);
    }

    public function salvar()
    {
        $data = $this->validate();
        $this->agendamentoService = new AgendamentoService();

        try {

            if ($this->agendamento_id && $this->agendamentoService->existsById($this->agendamento_id)) {
                $this->agendamentoService->atualizar($data, $this->agendamento_id);
            } else {
                $this->agendamentoService->criar($data);
            }

            $this->dispatchBrowserEvent('triggerAgendaGravadaSucesso', $this->inicio);
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('triggerError', $e->getMessage());
        }

    }

    public function carregaAgendamento($agendamento_id)
    {
        $this->agendamentoService = new AgendamentoService();

        $agendamento = $this->agendamentoService->findById($agendamento_id);

        $this->agendamento_id = $agendamento->id;
        $this->descricao = $agendamento->descricao;
        $this->inicio = $agendamento->inicio;
        $this->final = $agendamento->final;
        $this->unidade_id = $agendamento->unidade_id;
        $this->agendamento_tipos_id = $agendamento->agendamento_tipos_id;
        $this->agendamento_tem_checklist = $agendamento->checklist()->exists();
        $this->agendamento_checklist_finalizado = ($this->agendamento_tem_checklist) ? (boolean) $agendamento->checklist->concluido : false;

    }

    public function excluir($agendamento_id)
    {
        $this->agendamentoService = new AgendamentoService();
        $this->agendamentoService->excluir($agendamento_id);
        $this->limpar();
        $this->dispatchBrowserEvent('triggerAgendaExcluidaSucesso');
    }
}
