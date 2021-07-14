<?php

namespace App\Http\Livewire\Demanda;

use App\Models\Demanda;
use Livewire\Component;

class Visualizar extends Component
{
    public $demanda;

    protected $listeners = ['definirVerDemanda' => 'definirVerDemanda'];

    public function definirVerDemanda($demanda_id)
    {
        $this->limpar();
        $this->demanda = Demanda::find($demanda_id);
    }

    public function mount($demanda_id = null)
    {
        $this->demanda = ($demanda_id) ? Demanda::find($demanda_id) : new Demanda();
    }

    public function render()
    {
        return view('livewire.demanda.visualizar');
    }

    public function limpar()
    {
        $this->demanda = new Demanda();
    }
}
