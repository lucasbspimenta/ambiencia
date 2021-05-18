<?php

namespace App\Http\Livewire\Agendamento;

use App\Models\Agendamento;
use App\Services\AgendamentoService;
use Exception;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Cadastro extends Component
{
    public $unidade_id;
    public $inicio='';
    public $final='';
    public $agendamento_tipos_id;
    public $descricao='';

    public $tiposagendamentos;

    private $agendamentoService;

    protected $listeners = ['limpar' => 'limpar', 'definirDatas' => 'definirDatas'];

    protected $casts = [
        'inicio' => 'date:d/m/Y',
        'final' => 'date:d/m/Y'
    ];

    public function rules() {
        return Agendamento::VALIDATION_RULES;
    }

    public function messages() {
        return Agendamento::VALIDATION_MESSAGES;
    }

    public function definirDatas($inicio, $final) {
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
            'descricao',
            'inicio',
            'final',
            'unidade_id',
            'agendamento_tipos_id'
        ]);
    }

    public function salvar()
    {
        $data = $this->validate();
        $this->agendamentoService = new AgendamentoService();

        try {
            $this->agendamentoService->criar($data);
            $this->dispatchBrowserEvent('triggerAgendaGravadaSucesso',$this->inicio);
        }catch (Exception $e){
            $this->dispatchBrowserEvent('triggerError',$e->getMessage());
        }

    }
}
