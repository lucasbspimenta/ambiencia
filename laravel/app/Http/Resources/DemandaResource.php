<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DemandaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'demanda_id' => $this->demanda_id,
            'demanda_migracao' => $this->demanda_migracao,
            'demanda_situacao' => ($this->demanda_migracao == 'P') ? 'Integração pendente' : $this->demanda_situacao,
            'demanda_descricao' => $this->demanda_descricao,
            'demanda_prazo' => $this->demanda_prazo,
            'demanda_prazo_inicial' => $this->demanda_prazo_inicial,
            'demanda_conclusao' => $this->demanda_conclusao,
            'demanda_atualizacao' => $this->demanda_atualizacao,
            'sistema_id' => $this->sistema_id,
            'sistema_nome' => $this->sistema_nome,
            'unidade_nome' => $this->unidade_nome,
            'unidade_id' => $this->unidade_id,
            'unidade_codigo' => $this->unidade_codigo,
            'responsavel' => $this->responsavel,
            'responsavel_nome' => $this->responsavel_nome,
            'supervisor' => $this->supervisor,
            'supervisor_nome' => $this->supervisor_nome,
            'coordenador' => $this->coordenador,
            'coordenador_nome' => $this->coordenador_nome,
            'equipe_id' => $this->equipe_id,
            'equipe_nome' => $this->equipe_nome,
        ];
    }
}
