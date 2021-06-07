<?php

namespace App\Services;

use App\Models\ChecklistItem;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RelatoriosService
{
    public static function CorPorItem():array
    {
        $itens = ChecklistItem::where('situacao',1)->get();
        $itens = $itens->mapWithKeys(function ($item) {
            return [$item['nome'] => $item['cor']];
        });
        return $itens->toArray();
    }

    public static function InconformidadePorItem($data_inicial = null, $data_final = null)
    {
        if(is_null($data_final) || $data_final->greaterThan(Carbon::now()))
            $data_final = Carbon::now();

        if(is_null($data_inicial))
            $data_inicial = Carbon::now()->sub(90, 'day');

        $itens = ChecklistItem::select('id','nome')->where('situacao',1)->get();
        $itens = $itens->mapWithKeys(function ($item) {
            return [$item['id'] => $item['nome']];
        });

        $sql = "SELECT DISTINCT relbase.id
                  , SUM([inconforme]) OVER (PARTITION BY relbase.id ) as inconforme_por_macroitem
                  , SUM([inconforme]) OVER () as total_inconforme
                  , SUM([respondido]) OVER () as total_respondido
                  , SUM([pendente]) OVER () as total_pendente
                  , percentual_inconforme = CAST(((SUM([inconforme]) OVER (PARTITION BY relbase.id )) * 100.00)/ SUM([inconforme]) OVER () as decimal(12,2))
              FROM [relatorio_base_respostas] relbase
              JOIN [usuario_unidades] uu ON uu.unidade_id = relbase.unidade_id AND uu.matricula = '". Auth::user()->matricula ."'
              WHERE relbase.pai_id <> relbase.id
                ";

        $dados = collect(DB::select($sql));
        $dados = $dados->sortByDesc('percentual_inconforme');

        $dados_grafico = $dados->where('percentual_inconforme','>',0)->mapWithKeys(function ($item) use ($itens) {
                return [$itens[$item->id] => (float) $item->percentual_inconforme];
        });

        return $dados_grafico;
    }

    public static function InconformidadePorMacroitem($data_inicial = null, $data_final = null)
    {
        if(is_null($data_final) || $data_final->greaterThan(Carbon::now()))
            $data_final = Carbon::now();

        if(is_null($data_inicial))
            $data_inicial = Carbon::now()->sub(90, 'day');

        $itens = ChecklistItem::select('id','nome')->where('situacao',1)->get();
        $itens = $itens->mapWithKeys(function ($item) {
            return [$item['id'] => $item['nome']];
        });

        $sql = "SELECT DISTINCT relbase.pai_id as id
                  , SUM([inconforme]) OVER (PARTITION BY relbase.pai_id ) as inconforme_por_macroitem
                  , SUM([inconforme]) OVER () as total_inconforme
                  , SUM([respondido]) OVER () as total_respondido
                  , SUM([pendente]) OVER () as total_pendente
                  , percentual_inconforme = CAST(((SUM([inconforme]) OVER (PARTITION BY relbase.pai_id )) * 100.00)/ SUM([inconforme]) OVER () as decimal(12,2))
              FROM [relatorio_base_respostas] relbase
              JOIN [usuario_unidades] uu ON uu.unidade_id = relbase.unidade_id AND uu.matricula = '". Auth::user()->matricula ."'
              WHERE relbase.pai_id <> relbase.id
                ";

        $dados = collect(DB::select($sql));
        $dados = $dados->sortByDesc('percentual_inconforme');

        $dados_grafico = $dados->mapWithKeys(function ($item) use ($itens) {
            return [$itens[$item->id] => (float) $item->percentual_inconforme];
        });

        return $dados_grafico;
    }

    public static function VisitaPorPeriodo($data_inicial = null, $data_final = null)
    {
        if(is_null($data_final) || $data_final->greaterThan(Carbon::now()))
            $data_final = Carbon::now();

        if(is_null($data_inicial))
            $data_inicial = Carbon::now()->sub(90, 'day');


        $sql = "
            SELECT
            DISTINCT
            COUNT(unidade_id) OVER () as total_unidades
            ,SUM(visitado) OVER () as total_visitado
            ,percentual_visitado = CAST(((SUM(visitado) OVER ()) * 100.00 / (COUNT(unidade_id) OVER ())) as decimal(16,2))
            FROM (
                SELECT
                DISTINCT
                uu.unidade_id
                ,visitado = CASE WHEN COUNT(age.[id]) OVER (PARTITION BY uu.unidade_id) > 0 THEN 1 ELSE 0 END
                FROM
                [dbo].[usuario_unidades] uu
                LEFT JOIN [dbo].[agendamentos] age
                    ON uu.unidade_id = age.unidade_id
                    AND (([inicio] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."' OR [final] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."') OR [inicio] IS NULL)
                WHERE uu.matricula = '". Auth::user()->matricula ."'
            ) visitado
        ";

        $dados = collect(DB::select($sql))->first();
        return $dados;
    }

    public static function VisitaPorTipo($data_inicial = null, $data_final = null){

        if(is_null($data_final) || $data_final->greaterThan(Carbon::now()))
            $data_final = Carbon::now();

        if(is_null($data_inicial))
            $data_inicial = Carbon::now()->sub(90, 'day');

        $sql = "SELECT
                DISTINCT
                agendamento_tipos_id
                ,COUNT(unidade_id) OVER () as total_unidades
                ,SUM(visitado) OVER (PARTITION BY agendamento_tipos_id) as total_por_tipo
                ,SUM(visitado) OVER () as total_visitado
                ,percentual_visitado = CAST(((SUM(visitado) OVER (PARTITION BY agendamento_tipos_id)) * 100.00 / (SUM(visitado) OVER ())) as decimal(16,2))
                FROM (
                    SELECT
                    DISTINCT
                    uu.unidade_id
                    ,agendamento_tipos_id
                    ,visitado = CASE WHEN COUNT(age.[id]) OVER (PARTITION BY uu.unidade_id) > 0 THEN 1 ELSE 0 END
                    FROM
                    [dbo].[usuario_unidades] uu
                    LEFT JOIN [dbo].[agendamentos] age
                        ON uu.unidade_id = age.unidade_id
                        AND (([inicio] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."' OR [final] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."') OR [inicio] IS NULL)
                    WHERE uu.matricula = '". Auth::user()->matricula ."' AND agendamento_tipos_id IS NOT NULL
                ) visitado";

        $dados = collect(DB::select($sql));
        return $dados;
    }

    public static function PreenchimentoChecklist(){

        $sql = "SELECT
                DISTINCT
                rcp.*
                , nome_completo = u.[tipoPv] + ' ' + u.[nome]
                FROM [relatorio_checklist_preenchimento] rcp
                JOIN [unidades] u ON u.id = rcp.unidade_id
                WHERE
                     matricula = '". Auth::user()->matricula ."'
                    AND percentual_respondido < 100
                    AND [final] <= '". Carbon::now()->format('Y-m-d') ."'
                ";

        $dados = collect(DB::select($sql));
        return $dados;
    }
}
