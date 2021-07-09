<?php

namespace App\Services;

use App\Http\Helpers\DateHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RelatoriosVisitasService
{
    public static function Realizadas($data_inicial = null, $data_final = null, $matricula = null)
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

        $sql = "
        SELECT * FROM
        (
            SELECT
                DISTINCT
                responsavel
                ,responsavel_nome
                ,supervisor
                ,supervisor_nome
                ,coordenador
                ,coordenador_nome
                ,equipe_id
                ,equipe_nome
                ,COUNT(id) OVER (PARTITION BY responsavel) as total_unidades
                ,SUM(visitado) OVER (PARTITION BY responsavel) as total_visitado
                ,percentual_visitado = CAST(((SUM(visitado) OVER (PARTITION BY responsavel)) * 100.00 / COALESCE(NULLIF((COUNT(id) OVER (PARTITION BY responsavel)),0),1)) as decimal(16,2))
            FROM (
                SELECT
                    und.id
                    ,visitado = CASE WHEN COUNT(age.[id]) > 0 THEN 1 ELSE 0 END
                    ,und_resp.matricula as responsavel
                    ,COALESCE(und_resp.nome_responsavel,und_resp.matricula) as responsavel_nome
                    ,COALESCE(supervisor.matricula, und_resp.supervisor) as supervisor
                    ,COALESCE(supervisor.name,supervisor.matricula, und_resp.supervisor) as supervisor_nome
                    ,COALESCE(coordenador.matricula, und_resp.coordenador) as coordenador
                    ,COALESCE(coordenador.name,coordenador.matricula, und_resp.coordenador) as coordenador_nome
                    ,und_resp.equipe_id
                    ,und_resp.equipe_nome
                FROM unidades und
                JOIN unidades_responsavel und_resp ON und_resp.unidade_id = und.id
                LEFT JOIN users supervisor ON supervisor.matricula = und_resp.supervisor
                LEFT JOIN users coordenador ON coordenador.matricula = und_resp.coordenador
                LEFT JOIN agendamentos age ON age.unidade_id = und.id AND age.[deleted_at] is null AND (age.inicio BETWEEN '" . $data_inicial->format('Y-m-d') . "' AND '" . $data_final->format('Y-m-d') . "' OR age.final BETWEEN '" . $data_inicial->format('Y-m-d') . "' AND '" . $data_final->format('Y-m-d') . "')
                LEFT JOIN agendamento_tipos age_tipo ON age_tipo.id = age.agendamento_tipos_id
                GROUP BY und.id
                        ,und_resp.matricula
                        ,und_resp.nome_responsavel
                        ,COALESCE(supervisor.matricula, und_resp.supervisor)
                        ,COALESCE(supervisor.name,supervisor.matricula, und_resp.supervisor)
                        ,COALESCE(coordenador.matricula, und_resp.coordenador)
                        ,COALESCE(coordenador.name,coordenador.matricula, und_resp.coordenador)
                        ,und_resp.equipe_id
                        ,und_resp.equipe_nome
            ) subquery
        ) dados
        ";

        $where = '';

        if (!($usuario->is_gestor)) {
            $sql .= " WHERE (responsavel = '" . $usuario->matricula . "' OR supervisor = '" . $usuario->matricula . "' OR coordenador = '" . $usuario->matricula . "')";
        }

        $sql .= " ORDER BY percentual_visitado DESC";

        $dados = DB::select($sql);

        return collect($dados);
    }

    public static function RealizadasPorTipo($data_inicial = null, $data_final = null, $matricula = null)
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

        $sql = "SELECT * FROM (
                    SELECT
                        DISTINCT
                        tipo_id
                        ,tipo_cor
                        ,tipo_nome
                        ,responsavel
                        ,responsavel_nome
                        ,supervisor
                        ,supervisor_nome
                        ,coordenador
                        ,coordenador_nome
                        ,equipe_id
                        ,equipe_nome
                        ,COUNT(visita) OVER (PARTITION BY responsavel) as total_visitas
                        ,COUNT(visita) OVER (PARTITION BY tipo_id, responsavel) as total_tipo
                    FROM (
                            SELECT
                                age.id as visita
                                ,age.agendamento_tipos_id as tipo_id
                                ,age_tipo.cor as tipo_cor
                                ,age_tipo.nome as tipo_nome
                                ,und_resp.matricula as responsavel
                                ,COALESCE(und_resp.nome_responsavel,und_resp.matricula) as responsavel_nome
                                ,COALESCE(supervisor.matricula, und_resp.supervisor) as supervisor
                                ,COALESCE(supervisor.name,supervisor.matricula, und_resp.supervisor) as supervisor_nome
                                ,COALESCE(coordenador.matricula, und_resp.coordenador) as coordenador
                                ,COALESCE(coordenador.name,coordenador.matricula, und_resp.coordenador) as coordenador_nome
                                ,und_resp.equipe_id
                                ,und_resp.equipe_nome
                            FROM agendamentos age
                            JOIN agendamento_tipos age_tipo ON age_tipo.id = age.agendamento_tipos_id
                            JOIN unidades_responsavel und_resp ON und_resp.unidade_id = age.unidade_id
                            LEFT JOIN users supervisor ON supervisor.matricula = und_resp.supervisor
                            LEFT JOIN users coordenador ON coordenador.matricula = und_resp.coordenador
                            WHERE age.[deleted_at] is null AND age.final < GETDATE() AND (age.inicio BETWEEN '" . $data_inicial->format('Y-m-d') . "' AND '" . $data_final->format('Y-m-d') . "' OR age.final BETWEEN '" . $data_inicial->format('Y-m-d') . "' AND '" . $data_final->format('Y-m-d') . "')
                            ) subquery

                ) dados
        ";

        if (!($usuario->is_gestor)) {
            $sql .= " WHERE (responsavel = '" . $usuario->matricula . "' OR supervisor = '" . $usuario->matricula . "' OR coordenador = '" . $usuario->matricula . "')";
        }

        $sql .= " ORDER BY tipo_id ASC";

        $dados = DB::select($sql);

        return collect($dados);
    }
}
