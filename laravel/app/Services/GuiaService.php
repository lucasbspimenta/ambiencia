<?php

namespace App\Services;

use App\Models\Guia;
use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\TemporaryUploadedFile;


class GuiaService
{
    public function todos()
    {
        return Guia::with(['checklistItem','itens','imagens'])->get();
    }

    public function todosAtivos()
    {
        return Guia::with(['checklistItem','itens','imagens'])->get();
    }

    public function criar(array $data):Guia
    {
        $validator = Validator::make($data,Guia::VALIDATION_RULES(), Guia::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }
        DB::beginTransaction();

        $guia = Guia::create($data);

        if(isset($data['imagens']) && sizeof($data['imagens']) > 0){
            $imagemService = new ImagemService();
            $imagem_array = [];
            foreach($data['imagens'] as $img)
            {
                $imagem_array[] = $imagemService->criar($img);
            }

            $guia->imagens()->saveMany($imagem_array);
        }

        if(isset($data['itens']) && sizeof($data['itens']) > 0){
            $guiaItemService = new GuiaItemService();
            foreach($data['itens'] as $guiaItem)
            {
                $guiaItem['guia_id'] = $guia->id;
                $guiaItem_criado = $guiaItemService->criar($guiaItem);
                $guia->itens()->save($guiaItem_criado);
            }
        }

        $guia->refresh();

        DB::commit();

        return $guia;
    }

    public function atualizar(array $data, $id)
    {
        $regras = Guia::VALIDATION_RULES($id);

        $validator = Validator::make($data,$regras, Guia::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $guia = Guia::findOrFail($id);
            $dados_validados = $validator->validated();
            $guia->update($dados_validados);

            function filter_callback($element) {
                if (isset($element->id) || isset($element['id']) ) {
                    return TRUE;
                }
                return FALSE;
            }

            //$array_imagens_filtrado = $arr = array_filter($data['imagens'], 'filter_callback'); // Traz somente os itens que tem propriedade ID, que são os existentes
            //$ids_das_imagens_mantidas = array_map(function($o) { return $o->id ?? $o['id']; }, $array_imagens_filtrado);

            $ids_das_imagens_mantidas = array_column($data['imagens'], 'id');

            if (count($guia->imagens) > 0) {
                foreach ($guia->imagens as $imagem) {
                    if (!in_array($imagem->id, $ids_das_imagens_mantidas)) {
                        $imagem->delete();
                    }
                }
            }

            if(isset($data['imagens']) && sizeof($data['imagens']) > 0){
                $imagemService = new ImagemService();
                $imagem_array = [];

                foreach($data['imagens'] as $img)
                {
                    if($img instanceof TemporaryUploadedFile)
                        $imagem_array[] = $imagemService->criar($img);
                }

                $guia->imagens()->saveMany($imagem_array);
            }

            $ids_dos_itens_mantidos = array_column($data['itens'], 'id');

            if (count($guia->itens) > 0) {
                foreach ($guia->itens as $item) {
                    if (!in_array($item->id, $ids_dos_itens_mantidos)) {
                        $item->delete();
                    }
                }
            }

            if(isset($data['itens']) && sizeof($data['itens']) > 0){
                $guiaItemService = new GuiaItemService();
                foreach($data['itens'] as $guiaItem)
                {
                    if(is_null($guiaItem['id'])){
                        $guiaItem['guia_id'] = $guia->id;
                        $guiaItem_criado = $guiaItemService->criar($guiaItem);
                        $guia->itens()->save($guiaItem_criado);
                    }
                }
            }


        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Não foi possivel atualizar o guia: ' . $e->getMessage());
        }

        DB::commit();

        return $guia;

    }

    public function findById($id)
    {
        return Guia::findOrFail($id);
    }

    public function existsById($id)
    {
        return Guia::where('id', $id )->exists($id);
    }

    public function excluir($id)
    {
        return Guia::findOrFail($id)->delete();
    }
}
