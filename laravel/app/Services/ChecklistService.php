<?php

namespace App\Services;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;


class ChecklistService
{
    public function criar(array $data):Checklist
    {
        $validator = Validator::make($data,Checklist::VALIDATION_RULES(), Checklist::VALIDATION_MESSAGES);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return Checklist::create($data);
    }

    public function findById($id)
    {
        return Checklist::findOrFail($id);
    }

    public function existsById($id)
    {
        return Checklist::where('id', $id )->exists($id);
    }

    public function excluir($id)
    {
        return Checklist::findOrFail($id)->delete();
    }

    public function todos()
    {
        return Checklist::with(['agendamento','respostas'])->get();

    }

    public function finalizar($checklist_id)
    {
        $checklist = Checklist::findOrFail($checklist_id);

        if(!$checklist->percentual_preenchimento >= 100)
            throw new \Exception('O checklist deve estar 100% preenchido para finalizar');

        $checklist->concluido = true;
        $checklist->save();

        if(env('MIGRAR_DEMANDAS') && env('MIGRAR_DEMANDAS') == 1)
            self::processaDemandas($checklist);

        return true;
    }

    public function vincularItensAoChecklist(Checklist &$checklist)
    {
        $itens_ativos = ChecklistItem::select( DB::raw('id as checklist_item_id'),
            DB::raw($checklist->id . ' as checklist_id'),
            DB::raw('NULL as resposta'))
            ->where('situacao', 1)
            ->where(function($query) {
                $query->whereNotNull('item_pai_id')
                    ->orWhere('foto', 'S');
            })
            ->orderBy('ordem')
            ->get()->toArray();

        $checklist->respostas()->createMany($itens_ativos);
    }

    public static function processaDemandas(Checklist $checklist) {
        if($checklist->concluido && $checklist->demandas && sizeof($checklist->demandas) > 1) {

            $checklist->demandas->map(function($demanda) { DemandaService::processa($demanda); });

        } else {
            throw new \Exception("O checklist deve estar concluído para processar as demandas.", 1);

        }
    }
}
