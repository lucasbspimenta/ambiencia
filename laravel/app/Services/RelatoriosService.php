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
                  , percentual_inconforme = CAST(((SUM([inconforme]) OVER (PARTITION BY relbase.id )) * 100.00)/ COALESCE(NULLIF((SUM(inconforme) OVER ()),0),1) as decimal(12,2))
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
                  , percentual_inconforme = CAST(((SUM([inconforme]) OVER (PARTITION BY relbase.pai_id )) * 100.00)/ COALESCE(NULLIF((SUM(inconforme) OVER ()),0),1) as decimal(12,2))
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
            ,percentual_visitado = CAST(((SUM(visitado) OVER ()) * 100.00 / COALESCE(NULLIF((COUNT(unidade_id) OVER ()),0),1)) as decimal(16,2))
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

    public static function VisitaPorPeriodoSupervisor($supervisor, $data_inicial = null, $data_final = null)
    {
        if(is_null($data_final) || $data_final->greaterThan(Carbon::now()))
            $data_final = Carbon::now();

        if(is_null($data_inicial))
            $data_inicial = Carbon::now()->sub(90, 'day');

        $sql = "
                SELECT
                DISTINCT
                [responsavel]
                ,users.name as [responsavel_nome]
                ,[supervisor]
                ,COUNT(unidade_id) OVER (PARTITION BY [responsavel]) as total_unidades
                ,SUM(visitado) OVER (PARTITION BY [responsavel]) as total_visitado
                ,percentual_visitado = CAST(((SUM(visitado) OVER (PARTITION BY [responsavel])) * 100.00 / COALESCE(NULLIF((COUNT(unidade_id) OVER (PARTITION BY [responsavel])),0),1)) as decimal(16,2))
                FROM (
                    SELECT
                    DISTINCT
                    uu.matricula as [responsavel]
                    ,uu.supervisor
                    ,uu.unidade_id
                    ,visitado = CASE WHEN COUNT(age.[agendamento_id]) OVER (PARTITION BY uu.unidade_id) > 0 THEN 1 ELSE 0 END
                    FROM
                    [unidades_responsavel] uu
                    LEFT JOIN [laravel].[dbo].[relatorio_base_agendamentos] age ON uu.unidade_id = age.unidade_id
                    AND (([agendamento_inicio] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."' OR [agendamento_final] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."') OR [agendamento_inicio] IS NULL)
                    WHERE uu.supervisor = '". $supervisor ."'
                ) visitado
                LEFT JOIN users ON users.matricula = visitado.[responsavel]
        ";

        //dd($sql);

        $dados = collect(DB::select($sql));
        return $dados;
    }

    public static function VisitaPorPeriodoCoordenador($coordenador, $data_inicial = null, $data_final = null)
    {
        if(is_null($data_final) || $data_final->greaterThan(Carbon::now()))
            $data_final = Carbon::now();

        if(is_null($data_inicial))
            $data_inicial = Carbon::now()->sub(90, 'day');

        $sql = "
               SELECT
                DISTINCT
                equipe_id
                ,equipe_nome
                ,coordenador
                ,COUNT(unidade_id) OVER (PARTITION BY equipe_id) as total_unidades
                ,SUM(visitado) OVER (PARTITION BY equipe_id) as total_visitado
                ,percentual_visitado = CAST(((SUM(visitado) OVER (PARTITION BY equipe_id)) * 100.00 / COALESCE(NULLIF((COUNT(unidade_id) OVER (PARTITION BY equipe_id)),0),1)) as decimal(16,2))
                FROM (
                    SELECT
                    DISTINCT
                    uu.equipe_id
                    ,uu.equipe_nome
                    ,uu.coordenador
                    ,uu.unidade_id
                    ,visitado = CASE WHEN COUNT(age.[agendamento_id]) OVER (PARTITION BY uu.unidade_id) > 0 THEN 1 ELSE 0 END
                    FROM
                    [unidades_responsavel] uu
                    LEFT JOIN [laravel].[dbo].[relatorio_base_agendamentos] age ON uu.unidade_id = age.unidade_id
                    AND (([agendamento_inicio] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."' OR [agendamento_final] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."') OR [agendamento_inicio] IS NULL)
                    WHERE uu.coordenador = '". $coordenador ."'
                ) visitado
        ";

        //dd($sql);

        $dados = collect(DB::select($sql));
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
                ,percentual_visitado = CAST(((SUM(visitado) OVER (PARTITION BY agendamento_tipos_id)) * 100.00 / (COALESCE(NULLIF((SUM(visitado) OVER ()),0),1))) as decimal(16,2))
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

    public static function PreenchimentoChecklistCoordenador($coordenador)
    {
        $sql = "
        SELECT
        DISTINCT
         equipe_nome,
         COUNT(checklist_id) as total_pendentes
        FROM [relatorio_checklist_preenchimento] rcp
        WHERE
            coordenador = '". $coordenador ."'
            AND percentual_respondido < 100
            AND [final] < GETDATE()
        GROUP BY equipe_nome;
        ";

        $dados = collect(DB::select($sql));
        return $dados;
    }

    public static function PreenchimentoChecklistSupervisor($supervisor)
    {
        $sql = "
        SELECT
        DISTINCT
         rcp.matricula,
         users.name as equipe_nome,
         COUNT(checklist_id) as total_pendentes
        FROM [relatorio_checklist_preenchimento] rcp
        LEFT JOIN users ON users.matricula = rcp.matricula
        WHERE
            supervisor = '". $supervisor ."'
        AND percentual_respondido < 100
        AND [final] < GETDATE()
        GROUP BY rcp.matricula, users.name;
        ";

        $dados = collect(DB::select($sql));
        return $dados;
    }
}
