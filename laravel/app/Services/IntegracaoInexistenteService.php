<?php

namespace App\Services;

use App\Models\Demanda;

class IntegracaoInexistenteService
{
    protected $demanda;

    public function __construct(Demanda $demanda)
    {
        $this->demanda = $demanda;
    }

    public function executar()
    {

        if (trim($this->demanda->migracao) == 'P') {

            $this->demanda->migracao = 'C';
            $this->demanda->demanda_id = null;
            $this->demanda->demanda_url = null;
            $this->demanda->demanda_situacao = 'FINALIZADO';
            $this->demanda->demanda_retorno = 'Finalizada automaticamente';
            $this->demanda->demanda_conclusao = date('Y-m-d H:i:s');
            $this->demanda->save();
        }

        return $this->demanda;
    }
}
