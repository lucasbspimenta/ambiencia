<?php

namespace App\Http\Livewire\Guia;

use App\Models\Guia;
use App\Services\GuiaService;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Exibir extends Component
{
    public $guia;

    protected $listeners = ['carregaGuia' => 'carregaGuia','limpar' => 'limpar'];

    public function render()
    {
        return view('livewire.guia.exibir');
    }

    public function mount()
    {
        $this->guia = new Guia();
    }

    public function limpar()
    {
        $this->guia = new Guia();
    }

    public function carregaGuia($guiaId)
    {
        $guiaService = new GuiaService();
        $this->guia = $guiaService->findById($guiaId);
    }
}
