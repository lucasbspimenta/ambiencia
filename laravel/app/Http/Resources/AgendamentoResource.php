<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgendamentoResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'inicio' => $this->inicio,
            'final' => $this->final,
            'start' => $this->inicio_americano,
            'end' => $this->final_americano,
            'unidade' => $this->unidade,
            'unidade_responsavel' => $this->unidade_responsavel,
            'title' => $this->tipoPv . ' ' . $this->nome,
            'tipo_cor' => $this->cor,
            'tipo_nome' => $this->tipo_nome,
            'tipo' => array('nome' => $this->tipo_nome, 'cor' => $this->cor),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
