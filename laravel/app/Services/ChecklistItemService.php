<?php

namespace App\Services;

use App\Models\ChecklistItem;
use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class ChecklistItemService
{
    public static function todos()
    {
        return ChecklistItem::with('subitens','guia')->get();
    }

    public static function todosAtivos()
    {
        return ChecklistItem::with(['subitens','guia'])->where('situacao',1)->get();;
    }

    public function criar(array $data):ChecklistItem
    {
        $validator = Validator::make($data,ChecklistItem::VALIDATION_RULES(), ChecklistItem::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return ChecklistItem::create($data);
    }

    public function atualizar(array $data, $id)
    {
        $regras = ChecklistItem::VALIDATION_RULES($id);
        $regras['nome'] = ['required','unique:checklist_items,nome,' . $id];

        $validator = Validator::make($data,$regras, ChecklistItem::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $checklistItem = ChecklistItem::findOrFail($id);

            $data = $validator->validated();
            $checklistItem->update($data);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Não foi possivel atualizar o item: ' . $e->getMessage());
        }

        DB::commit();

        return $checklistItem;

    }

    public function findById($id)
    {
        return ChecklistItem::findOrFail($id);
    }

    public function existsById($id)
    {
        return ChecklistItem::where('id', $id )->exists($id);
    }

    public function excluir($id)
    {
        return ChecklistItem::findOrFail($id)->delete();
    }
}
