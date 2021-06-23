<?php

namespace App\Services;

use App\Models\ChecklistItem;
use App\Models\User;
use App\Http\Helpers\DateHelper;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RelatoriosChecklistsService
{
    public static function Pendentes($data_inicial = null, $data_final = null, $matricula = null)
    {
        if(is_null($data_inicial))
            $data_inicial = DateHelper::getInicioTrimestre(Carbon::now());

        if(is_null($data_final))
            $data_final = DateHelper::getFinalTrimestre(Carbon::now());

        if(is_null($matricula))
            $usuario = Auth::user();
        else
            $usuario = User::findOrFail('matricula', $matricula);

        $sql = "
            SELECT * FROM
            ( SELECT 
                    chk.id
                    ,chk_preench.percentual_respondido
                    ,age.inicio
                    ,age.final
                    ,und.tipoPv + ' ' + und.nome as unidade_nome_completo
                    ,und_resp.matricula as responsavel
                    ,COALESCE(und_resp.nome_responsavel,und_resp.matricula) as responsavel_nome
                    ,COALESCE(supervisor.matricula, und_resp.supervisor) as supervisor
                    ,COALESCE(supervisor.name,supervisor.matricula, und_resp.supervisor) as supervisor_nome
                    ,COALESCE(coordenador.matricula, und_resp.coordenador) as coordenador
                    ,COALESCE(coordenador.name,coordenador.matricula, und_resp.coordenador) as coordenador_nome
                    ,und_resp.equipe_id
                    ,und_resp.equipe_nome
                FROM checklists chk
                JOIN relatorio_checklist_preenchimento chk_preench ON chk_preench.checklist_id = chk.id
                JOIN agendamentos age ON age.id = chk.agendamento_id
                JOIN agendamento_tipos age_tipo ON age_tipo.id = age.agendamento_tipos_id
                JOIN unidades und ON und.id = age.unidade_id
                JOIN unidades_responsavel und_resp ON und_resp.unidade_id = und.id
                LEFT JOIN users supervisor ON supervisor.matricula = und_resp.supervisor
                LEFT JOIN users coordenador ON coordenador.matricula = und_resp.coordenador
                WHERE chk.deleted_at IS NULL
            ) dados
            WHERE percentual_respondido < 100
            AND ([inicio] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."' OR [final] BETWEEN '". $data_inicial->format('Y-m-d') ."' AND '". $data_final->format('Y-m-d') ."')
        ";
        
        $where = '';

        if(!($usuario->is_matriz))
            $sql .= " AND (responsavel = '". $usuario->matricula ."' OR supervisor = '". $usuario->matricula ."' OR coordenador = '". $usuario->matricula ."')";

        $sql .= " ORDER BY inicio ASC";

        $dados = DB::select($sql);

        return collect($dados);
    }
}
