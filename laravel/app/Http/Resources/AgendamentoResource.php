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
            'inicio' => $this->inicio_americano,
            'final' => $this->final_americano,
            'start' => $this->inicio_americano,
            'end' => $this->final_americano,
            'unidade' => $this->unidade,
            'title' => ($this->unidade ? $this->unidade->nome_completo : ''),
            'tipo' => $this->tipo,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
