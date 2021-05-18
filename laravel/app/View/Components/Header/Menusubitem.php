<?php

namespace App\View\Components\Header;

use Illuminate\View\Component;

class Menusubitem extends Component
{
    public $nome;
    public $descricao;
    public $nomerota;
    public $icone;
    public $badge;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($nome, $descricao, $nomerota, $icone, $badge)
    {
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->nomerota = !empty($nomerota) ? $nomerota : 'index';
        $this->icone = $icone;
        $this->badge = $badge;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.header.menusubitem');
    }
}
