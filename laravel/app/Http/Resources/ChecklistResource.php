<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistResource extends JsonResource
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
            'concluido' => $this->concluido,
            'agendamento_id' => $this->agendamento_id,
            'agendamento' => array('inicio' => $this->agendamento_inicio, 'final' => $this->agendamento_final),
            'unidade' => $this->unidade_tipoPv . ' ' . $this->unidade_nome,
            'preenchimento' => $this->percentual_preenchimento_sql ?? $this->percentual_preenchimento,
            //'respostas' => PadraoResource::collection($this->respostas),
        ];
    }
}
