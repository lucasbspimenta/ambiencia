<?php

namespace App\Http\Livewire\Painel;

use App\Models\Agendamento;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Carbon\CarbonInterface;



class ProximosAgendamentos extends Component
{
    public $agendamentos = [];

    public function render()
    {
        return view('livewire.painel.proximos-agendamentos');
    }

    public function mount()
    {
        $this->agendamentos = Agendamento::whereDate('inicio','>=', Date('Y-m-d'))->orderBy('inicio','ASC')->get();
    }
}
