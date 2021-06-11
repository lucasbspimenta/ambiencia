<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateViewRelatorioChecklistPreenchimento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [relatorio_checklist_preenchimento]');
        DB::unprepared("
        CREATE VIEW [relatorio_checklist_preenchimento]
        as
            SELECT
            DISTINCT
            uu.[matricula]
			,uu.coordenador
			,uu.supervisor
			,uu.equipe_id
			,uu.equipe_nome
            ,[checklist_id]
            ,age.inicio
            ,age.final
            ,age.unidade_id
            , SUM(respondido)  as respondido
            , COUNT(relbase.id)  as total
            ,percentual_respondido = (CAST(SUM(respondido) * 100.00 / COUNT(relbase.id) as decimal(16,2)) - CASE WHEN relbase.concluido = 0 AND SUM(respondido) > 0 THEN 0.01 ELSE 0 END)
            FROM [relatorio_base_respostas] relbase
            JOIN [unidades_responsavel] uu ON uu.unidade_id = relbase.unidade_id
            JOIN [agendamentos] age ON age.id = relbase.agendamento_id
			--CROSS APPLY (SELECT CASE WHEN ver_conc.concluido = 0 THEN 0.01 ELSE 0 END as total FROM [relatorio_base_respostas] ver_conc WHERE ver_conc.checklist_id = relbase.checklist_id) ver_conc
            GROUP BY uu.[matricula]
            ,[checklist_id]
            ,age.inicio
            ,age.final
            ,age.unidade_id
			,relbase.concluido
			,uu.coordenador
			,uu.supervisor
			,uu.equipe_id
			,uu.equipe_nome
              ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [relatorio_checklist_preenchimento]');
    }
}
