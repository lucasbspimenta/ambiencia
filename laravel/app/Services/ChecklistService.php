<?php

namespace App\Services;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ChecklistService
{
    public function criar(array $data): Checklist
    {
        $validator = Validator::make($data, Checklist::VALIDATION_RULES(), Checklist::VALIDATION_MESSAGES);

        if ($validator->fails()) {
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
        return Checklist::where('id', $id)->exists($id);
    }

    public function excluir($id)
    {
        return Checklist::findOrFail($id)->delete();
    }

    public function todos()
    {
        return Checklist::with(['agendamento', 'respostas'])->get();
    }

    public function todosDatatables()
    {
        return Checklist::join('relatorio_checklist_preenchimento', 'relatorio_checklist_preenchimento.checklist_id', '=', 'checklists.id')
            ->join('agendamentos', 'checklists.agendamento_id', '=', 'agendamentos.id')
            ->join('agendamento_tipos', 'agendamentos.agendamento_tipos_id', '=', 'agendamento_tipos.id')
            ->join('unidades', 'unidades.id', '=', 'agendamentos.unidade_id')
            ->leftJoin('checklist_demandas_andamento', 'checklists.id', '=', 'checklist_demandas_andamento.checklist_id')
            ->select(
                'checklists.id'
                , 'checklists.concluido'
                , 'checklists.agendamento_id'
                , DB::raw('percentual_respondido as percentual_preenchimento_sql')
                , DB::raw('agendamentos.inicio as agendamento_inicio')
                , DB::raw('agendamentos.final as agendamento_final')
                , DB::raw('agendamento_tipos.com_checklist as agendamento_com_checklist')
                , DB::raw('unidades.nome as unidade_nome')
                , DB::raw('unidades.tipoPv as unidade_tipoPv')
                , DB::raw('CAST(COALESCE(((checklist_demandas_andamento.total_finalizada * 100.00)/checklist_demandas_andamento.total_demandas),0.00) as decimal(8,2)) as percentual_demandas')
            )
            ->distinct()
            ->get();
    }

    public function finalizar($checklist_id)
    {
        $checklist = Checklist::findOrFail($checklist_id);

        if (!$checklist->percentual_preenchimento >= 100) {
            throw new \Exception('O checklist deve estar 100% preenchido para finalizar');
        }

        $checklist->concluido = true;
        $checklist->save();

        foreach ($checklist->demandas as $demanda) {
            $demanda->migracao = 'P';
            $demanda->save();
        }

        if (env('MIGRAR_DEMANDAS') && env('MIGRAR_DEMANDAS') == 1) {

            self::processaDemandas($checklist);
        }

        return true;
    }

    public function vincularItensAoChecklist(Checklist &$checklist)
    {
        $itens_ativos = ChecklistItem::select(DB::raw('id as checklist_item_id'),
            DB::raw($checklist->id . ' as checklist_id'),
            DB::raw('NULL as resposta'))
            ->where('situacao', 1)
            ->where(function ($query) {
                $query->whereNotNull('item_pai_id')
                    ->orWhere('foto', 'S');
            })
            ->orderBy('ordem')
            ->get()->toArray();

        $checklist->respostas()->createMany($itens_ativos);
    }

    public static function processaDemandas(Checklist $checklist)
    {
        $checklist->refresh();
        $checklist->loadMissing('demandas');
        //dd($checklist->concluido, $checklist->demandas, sizeof($checklist->demandas));

        if ($checklist->concluido && $checklist->demandas && sizeof($checklist->demandas) > 0) {

            $checklist->demandas->map(function ($demanda) {DemandaService::processa($demanda);});

        } else {
            throw new \Exception("O checklist deve estar concluído para processar as demandas.", 1);

        }
    }

    public function getMacroitemProgresso(Checklist $checklist, ChecklistItem $macroitem)
    {
        $percentual = DB::table('checklist_macroitem_preenchimento')->select('percentual_respondido')->where('checklist_id', $checklist->id)->where('pai_id', $macroitem->id)->first();
        return $percentual->percentual_respondido ?? 0.00;
    }
}
