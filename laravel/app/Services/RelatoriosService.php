<?php

namespace App\Services;

use App\Http\Helpers\DateHelper;
use App\Models\ChecklistItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RelatoriosService
{
    public static function CorPorItem(): array
    {
        $itens = ChecklistItem::where('situacao', 1)->get();
        $itens = $itens->mapWithKeys(function ($item) {
            return [$item['nome'] => $item['cor']];
        });
        return $itens->toArray();
    }

    public static function InconformidadePorItem($data_inicial = null, $data_final = null, $matricula = null)
    {
        if (is_null($data_inicial) || $data_inicial->lessThan(Carbon::now())) {
            $data_inicial = DateHelper::getInicioTrimestre(Carbon::now());
        }

        if (is_null($data_final) || $data_final->greaterThan(Carbon::now())) {
            $data_final = Carbon::now();
        }

        if (is_null($matricula)) {
            $usuario = Auth::user();
        } else {
            $usuario = User::findOrFail('matricula', $matricula);
        }

        $sql_filtro_usuario = '';
        if (!($usuario->is_matriz)) {
            $sql_filtro_usuario = " AND (und_resp.matricula = '" . $usuario->matricula . "' OR supervisor = '" . $usuario->matricula . "' OR coordenador = '" . $usuario->matricula . "')";
        }

        $sql = "SELECT
                ck_item.id
                , ck_item.nome
                , ck_item_pai.cor
                , total_inconforme_item
                , SUM(total_inconforme_item) OVER () as total_inconforme
                , percentual_inconforme = CAST(COALESCE(total_inconforme_item,0.00) * 100 / COALESCE(NULLIF(SUM(total_inconforme_item) OVER (),0),1) as decimal(12,2))
                FROM
                [checklist_items] ck_item
                JOIN [checklist_items] ck_item_pai ON ck_item.item_pai_id = ck_item_pai.id
                LEFT JOIN (
                    SELECT
                    relbase_in.id
                    ,SUM(inconforme) as total_inconforme_item
                    FROM [relatorio_base_respostas] relbase_in
                    JOIN unidades_responsavel und_resp ON und_resp.unidade_id = relbase_in.unidade_id
                    LEFT JOIN users supervisor ON supervisor.matricula = und_resp.supervisor
                    LEFT JOIN users coordenador ON coordenador.matricula = und_resp.coordenador
                    WHERE (relbase_in.[agendamento_inicio] BETWEEN '" . $data_inicial->format('Y-m-d') . "' AND '" . $data_final->format('Y-m-d') . "' OR relbase_in.[agendamento_final] BETWEEN '" . $data_inicial->format('Y-m-d') . "' AND '" . $data_final->format('Y-m-d') . "')
                    " . $sql_filtro_usuario . "
                    GROUP BY
                        relbase_in.id
                ) relbase ON relbase.id = ck_item.id
                WHERE ck_item.item_pai_id IS NOT NULL AND ck_item.situacao = 1
                ORDER BY CAST(COALESCE(total_inconforme_item,0.00) * 100 / COALESCE(NULLIF(SUM(total_inconforme_item) OVER (),0),1) as decimal(12,2)) DESC, ck_item.id ASC
        ";

        $dados = DB::select($sql);
        return collect($dados);
    }

    public static function InconformidadePorMacroitem($data_inicial = null, $data_final = null, $matricula = null)
    {
        if (is_null($data_inicial) || $data_inicial->lessThan(Carbon::now())) {
            $data_inicial = DateHelper::getInicioTrimestre(Carbon::now());
        }

        if (is_null($data_final) || $data_final->greaterThan(Carbon::now())) {
            $data_final = Carbon::now();
        }

        if (is_null($matricula)) {
            $usuario = Auth::user();
        } else {
            $usuario = User::findOrFail('matricula', $matricula);
        }

        $sql_filtro_usuario = '';
        if (!($usuario->is_matriz)) {
            $sql_filtro_usuario = " AND (und_resp.matricula = '" . $usuario->matricula . "' OR supervisor = '" . $usuario->matricula . "' OR coordenador = '" . $usuario->matricula . "')";
        }

        $sql = "SELECT
                ck_item.id
                , ck_item.nome
                , ck_item.cor
                , total_inconforme_item
                , SUM(total_inconforme_item) OVER () as total_inconforme
                , percentual_inconforme = CAST(COALESCE(total_inconforme_item,0.00) * 100 / COALESCE(NULLIF(SUM(total_inconforme_item) OVER (),0),1) as decimal(12,2))
                FROM
                [checklist_items]ck_item
                LEFT JOIN (
                    SELECT
                    pai_id
                    ,SUM(inconforme) as total_inconforme_item
                    FROM [relatorio_base_respostas] relbase_in
                    JOIN unidades_responsavel und_resp ON und_resp.unidade_id = relbase_in.unidade_id
                    LEFT JOIN users supervisor ON supervisor.matricula = und_resp.supervisor
                    LEFT JOIN users coordenador ON coordenador.matricula = und_resp.coordenador
                    WHERE (relbase_in.[agendamento_inicio] BETWEEN '" . $data_inicial->format('Y-m-d') . "' AND '" . $data_final->format('Y-m-d') . "' OR relbase_in.[agendamento_final] BETWEEN '" . $data_inicial->format('Y-m-d') . "' AND '" . $data_final->format('Y-m-d') . "')
                    " . $sql_filtro_usuario . "
                    GROUP BY
                        relbase_in.pai_id
                ) relbase ON relbase.pai_id = ck_item.id
                WHERE ck_item.item_pai_id IS NULL AND ck_item.situacao = 1
                ORDER BY CAST(COALESCE(total_inconforme_item,0.00) * 100 / COALESCE(NULLIF(SUM(total_inconforme_item) OVER (),0),1) as decimal(12,2)) DESC, ck_item.id ASC
        ";

        $dados = DB::select($sql);
        return collect($dados);
    }

    // public static function VisitaPorPeriodo($data_inicial = null, $data_final = null)
    // {
    //     if(is_null($data_final) || $data_final->greaterThan(Carbon::now()))
    //         $data_final = Carbon::now();

    //     if(is_null($data_inicial))
    //         $data_inicial = Carbon::now()->sub(90, 'day');

    //     $sql = "
    //         SELECT
    //         DISTINCT
    //         COUNT(unidade_id) OVER () as total_unidades
    //         ,SUM(visitado) OVER () as total_visitado
    //         ,percentual_visitado = CAST(((SUM(visitado) OVER ()) * 100.00 / COALESCE(NULLIF((COUNT(unidade_id) OVER ()),0),1)) as decimal(16,2))
    //         FROM (
    //             SELECT
    //             DISTINCT
    //             uu.unidade_id
    //             ,visitado = CASE WHEN COUNT(age.[id]) OVER (PARTITION BY uu.unidade_id) > 0 THEN 1 ELSE 0 END
    //             FROM
    //             [dbo].[usuario_unidades] uu
    //             LEFT JOIN [dbo].[agendamentos] age
    //                 ON uu.unidade_id = age.unidade_id
    //                 AND (([inicio] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."' OR [final] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."') OR [inicio] IS NULL)
    //             WHERE uu.matricula = '". Auth::user()->matricula ."'
    //         ) visitado
    //     ";

    //     $dados = collect(DB::select($sql))->first();
    //     return $dados;
    // }

    // public static function VisitaPorPeriodoSupervisor($supervisor, $data_inicial = null, $data_final = null)
    // {
    //     if(is_null($data_final) || $data_final->greaterThan(Carbon::now()))
    //         $data_final = Carbon::now();

    //     if(is_null($data_inicial))
    //         $data_inicial = Carbon::now()->sub(90, 'day');

    //     $sql = "
    //             SELECT
    //             DISTINCT
    //             [responsavel]
    //             ,users.name as [responsavel_nome]
    //             ,[supervisor]
    //             ,COUNT(unidade_id) OVER (PARTITION BY [responsavel]) as total_unidades
    //             ,SUM(visitado) OVER (PARTITION BY [responsavel]) as total_visitado
    //             ,percentual_visitado = CAST(((SUM(visitado) OVER (PARTITION BY [responsavel])) * 100.00 / COALESCE(NULLIF((COUNT(unidade_id) OVER (PARTITION BY [responsavel])),0),1)) as decimal(16,2))
    //             FROM (
    //                 SELECT
    //                 DISTINCT
    //                 uu.matricula as [responsavel]
    //                 ,uu.supervisor
    //                 ,uu.unidade_id
    //                 ,visitado = CASE WHEN COUNT(age.[agendamento_id]) OVER (PARTITION BY uu.unidade_id) > 0 THEN 1 ELSE 0 END
    //                 FROM
    //                 [unidades_responsavel] uu
    //                 LEFT JOIN [relatorio_base_agendamentos] age ON uu.unidade_id = age.unidade_id
    //                 AND (([agendamento_inicio] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."' OR [agendamento_final] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."') OR [agendamento_inicio] IS NULL)
    //                 WHERE uu.supervisor = '". $supervisor ."'
    //             ) visitado
    //             LEFT JOIN users ON users.matricula = visitado.[responsavel]
    //     ";

    //     //dd($sql);

    //     $dados = collect(DB::select($sql));
    //     return $dados;
    // }

    // public static function VisitaPorPeriodoCoordenador($coordenador, $data_inicial = null, $data_final = null)
    // {
    //     if(is_null($data_final) || $data_final->greaterThan(Carbon::now()))
    //         $data_final = Carbon::now();

    //     if(is_null($data_inicial))
    //         $data_inicial = Carbon::now()->sub(90, 'day');

    //     $sql = "
    //            SELECT
    //             DISTINCT
    //             equipe_id
    //             ,equipe_nome
    //             ,coordenador
    //             ,COUNT(unidade_id) OVER (PARTITION BY equipe_id) as total_unidades
    //             ,SUM(visitado) OVER (PARTITION BY equipe_id) as total_visitado
    //             ,percentual_visitado = CAST(((SUM(visitado) OVER (PARTITION BY equipe_id)) * 100.00 / COALESCE(NULLIF((COUNT(unidade_id) OVER (PARTITION BY equipe_id)),0),1)) as decimal(16,2))
    //             FROM (
    //                 SELECT
    //                 DISTINCT
    //                 uu.equipe_id
    //                 ,uu.equipe_nome
    //                 ,uu.coordenador
    //                 ,uu.unidade_id
    //                 ,visitado = CASE WHEN COUNT(age.[agendamento_id]) OVER (PARTITION BY uu.unidade_id) > 0 THEN 1 ELSE 0 END
    //                 FROM
    //                 [unidades_responsavel] uu
    //                 LEFT JOIN [relatorio_base_agendamentos] age ON uu.unidade_id = age.unidade_id
    //                 AND (([agendamento_inicio] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."' OR [agendamento_final] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."') OR [agendamento_inicio] IS NULL)
    //                 WHERE uu.coordenador = '". $coordenador ."'
    //             ) visitado
    //     ";

    //     //dd($sql);

    //     $dados = collect(DB::select($sql));
    //     return $dados;
    // }

    // public static function VisitaPorPeriodoMatriz($data_inicial = null, $data_final = null)
    // {
    //     if(is_null($data_final) || $data_final->greaterThan(Carbon::now()))
    //         $data_final = Carbon::now();

    //     if(is_null($data_inicial))
    //         $data_inicial = Carbon::now()->sub(90, 'day');

    //     $sql = "
    //         SELECT
    //         DISTINCT
    //         COALESCE(users.name, coordenador) as equipe_nome
    //         ,COUNT(unidade_id) OVER (PARTITION BY coordenador) as total_unidades
    //         ,SUM(visitado) OVER (PARTITION BY coordenador) as total_visitado
    //         ,percentual_visitado = CAST(((SUM(visitado) OVER (PARTITION BY coordenador)) * 100.00 / COALESCE(NULLIF((COUNT(unidade_id) OVER (PARTITION BY coordenador)),0),1)) as decimal(16,2))
    //         FROM (
    //             SELECT
    //             DISTINCT
    //             uu.equipe_id
    //             ,uu.equipe_nome
    //             ,uu.coordenador
    //             ,uu.unidade_id
    //             ,visitado = CASE WHEN COUNT(age.[agendamento_id]) OVER (PARTITION BY uu.unidade_id) > 0 THEN 1 ELSE 0 END
    //             FROM
    //             [unidades_responsavel] uu
    //             LEFT JOIN [relatorio_base_agendamentos] age ON uu.unidade_id = age.unidade_id
    //             AND (([agendamento_inicio] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."' OR [agendamento_final] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."') OR [agendamento_inicio] IS NULL)
    //         ) visitado
    //         LEFT JOIN users ON users.matricula = visitado.[coordenador]
    //     ";

    //     //dd($sql);

    //     $dados = collect(DB::select($sql));
    //     return $dados;
    // }

    public static function VisitaPorTipo($data_inicial = null, $data_final = null)
    {

        if (is_null($data_final) || $data_final->greaterThan(Carbon::now())) {
            $data_final = Carbon::now();
        }

        if (is_null($data_inicial)) {
            $data_inicial = Carbon::now()->sub(90, 'day');
        }

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
                        AND (([inicio] BETWEEN '" . $data_inicial->format('Y-m-d') . "' AND '" . $data_final->format('Y-m-d') . "' OR [final] BETWEEN '" . $data_inicial->format('Y-m-d') . "' AND '" . $data_final->format('Y-m-d') . "') OR [inicio] IS NULL)
                    WHERE uu.matricula = '" . Auth::user()->matricula . "' AND agendamento_tipos_id IS NOT NULL
                ) visitado";

        $dados = collect(DB::select($sql));
        return $dados;
    }

    // public static function PreenchimentoChecklist($matricula=null){

    //     if(is_null($matricula))
    //         $matricula = Auth::user()->matricula;

    //     $sql = "SELECT
    //             DISTINCT
    //             rcp.*
    //             , nome_completo = u.[tipoPv] + ' ' + u.[nome]
    //             FROM [relatorio_checklist_preenchimento] rcp
    //             JOIN [unidades] u ON u.id = rcp.unidade_id
    //             WHERE
    //                  matricula = '". $matricula ."'
    //                 AND percentual_respondido < 100
    //                 AND [final] <= '". Carbon::now()->format('Y-m-d') ."'
    //             ";

    //     $dados = collect(DB::select($sql));
    //     return $dados;
    // }

    // public static function PreenchimentoChecklistMatriz()
    // {
    //     $sql = "
    //     SELECT
    //     DISTINCT
    //         COALESCE(users.name, coordenador) as equipe_nome,
    //         COUNT(checklist_id) as total_pendentes,
    //         coordenador as matricula
    //     FROM [relatorio_checklist_preenchimento] rcp
    //     LEFT JOIN users ON users.matricula = rcp.[coordenador]
    //     WHERE
    //         percentual_respondido < 100
    //         AND [final] < GETDATE()
    //     GROUP BY coordenador, users.name;
    //     ";

    //     $dados = collect(DB::select($sql));
    //     return $dados;
    // }

    // public static function PreenchimentoChecklistCoordenador($coordenador)
    // {
    //     $sql = "
    //     SELECT
    //     DISTINCT
    //      equipe_nome,
    //      COUNT(checklist_id) as total_pendentes,
    //      supervisor as matricula
    //     FROM [relatorio_checklist_preenchimento] rcp
    //     WHERE
    //         coordenador = '". $coordenador ."'
    //         AND percentual_respondido < 100
    //         AND [final] < GETDATE()
    //     GROUP BY equipe_nome, supervisor;
    //     ";

    //     $dados = collect(DB::select($sql));
    //     return $dados;
    // }

    // public static function PreenchimentoChecklistSupervisor($supervisor)
    // {
    //     $sql = "
    //     SELECT
    //     DISTINCT
    //      rcp.matricula,
    //      users.name as equipe_nome,
    //      COUNT(checklist_id) as total_pendentes
    //     FROM [relatorio_checklist_preenchimento] rcp
    //     LEFT JOIN users ON users.matricula = rcp.matricula
    //     WHERE
    //         supervisor = '". $supervisor ."'
    //     AND percentual_respondido < 100
    //     AND [final] < GETDATE()
    //     GROUP BY rcp.matricula, users.name;
    //     ";

    //     $dados = collect(DB::select($sql));
    //     return $dados;
    // }
}
