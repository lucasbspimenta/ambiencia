<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImagemResource extends JsonResource
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
            'name' => $this->name,
            //'imagem' => $this->imagem,
            'vinculacao' => GuiaItemResource::collection($this->whenLoaded('imageable')),
            'width' => $this->width,
            'height' => $this->height,
            'size' => $this->size,
            //'imageable_id' => $this->imageable_id,
            //'imageable_type' => $this->imageable_type,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
