<?php

namespace App\Services;

use App\Models\GuiaItem;
use Exception;

use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GuiaItemService
{

    public function criar(array $data):GuiaItem
    {
        $validator = Validator::make($data,GuiaItem::VALIDATION_RULES(), GuiaItem::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return GuiaItem::create($data);
    }

    public function atualizar(array $data, $id)
    {
        $regras = GuiaItem::VALIDATION_RULES();

        $validator = Validator::make($data,$regras, GuiaItem::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $guiaItem = GuiaItem::findOrFail($id);

            $data = $validator->validated();
            $guiaItem->update($data);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Não foi possivel atualizar o item do guia: ' . $e->getMessage());
        }

        DB::commit();

        return $guiaItem;

    }

}
