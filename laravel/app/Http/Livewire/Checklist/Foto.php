<?php

namespace App\Http\Livewire\Checklist;

use App\Services\ImagemService;
use Livewire\Component;
use Livewire\WithFileUploads;

class Foto extends Component
{
    use WithFileUploads;

    public $foto;
    public $resposta;

    public function render()
    {
        return view('livewire.checklist.foto');
    }

    public function updatedFoto()
    {
        $this->validate([
            'foto' => 'image',
        ]);

        $imagemService = new ImagemService();
        $this->resposta->foto = $imagemService->processaImagem($this->foto->getRealPath());
        $this->resposta->save();
        $this->dispatchBrowserEvent('atualizarResposta', ['resposta_id' => $this->resposta->id]);
        $this->dispatchBrowserEvent('renderizeiFoto',['id' => $this->id]);

    }

    public function removerFoto()
    {
        $this->resposta->foto = null;
        $this->resposta->save();
        $this->dispatchBrowserEvent('atualizarResposta', ['resposta_id' => $this->resposta->id]);
        $this->dispatchBrowserEvent('renderizeiFoto',['id' => $this->id]);
    }
}
