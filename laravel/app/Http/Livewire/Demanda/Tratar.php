<?php

namespace App\Http\Livewire\Demanda;

use App\Models\DemandaTratar;
use App\Services\DemandaService;
use Livewire\Component;

class Tratar extends Component
{
    public $demanda;

    protected $listeners = ['definirTratarDemanda' => 'definirTratarDemanda'];

    protected $rules = [
        'demanda.resposta' => 'required|string|max:500',
    ];

    protected $messages = [
        'demanda.resposta.required' => 'Você deve fornecer uma resposta',
    ];

    public function definirTratarDemanda($demanda_id)
    {
        $this->limpar();
        $this->demanda = DemandaTratar::find($demanda_id);
    }

    public function mount($demanda_id = null)
    {
        $this->demanda = ($demanda_id) ? DemandaTratar::find($demanda_id) : new DemandaTratar();
    }

    public function render()
    {
        return view('livewire.demanda.tratar');
    }

    public function salvar()
    {
        $this->validate();
        $this->demanda->save();

        if (env('MIGRAR_DEMANDAS') && env('MIGRAR_DEMANDAS') == 1 && $this->demanda->migracao == 'P') {
            DemandaService::processaDemandaTratada($this->demanda);
        }

        $this->dispatchBrowserEvent('triggerSucessoTratamento', '');
        $this->limpar();
    }

    public function limpar()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset();

        $this->demanda = new DemandaTratar();
    }
}
