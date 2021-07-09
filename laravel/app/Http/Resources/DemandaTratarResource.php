<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DemandaTratarResource extends JsonResource
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
            'id' => $this->id,
            'solicitacao' => $this->solicitacao,
            'resposta' => $this->resposta,
            'respondida' => (boolean) $this->resposta,
            'migracao' => $this->migracao,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
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
