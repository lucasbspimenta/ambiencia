<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
         [checklist_id]
        , SUM(respondido)  as respondido
        , COUNT(relbase.id)  as total
        ,percentual_respondido = (CAST(SUM(respondido) * 100.00 / COUNT(relbase.id) as decimal(16,2)) - CASE WHEN relbase.concluido = 0 AND SUM(respondido) > 0 THEN 0.01 ELSE 0 END)
        FROM [relatorio_base_respostas] relbase
        --JOIN [unidades_responsavel] uu ON uu.unidade_id = relbase.unidade_id
        --JOIN [agendamentos] age ON age.id = relbase.agendamento_id
        GROUP BY
        [checklist_id]
        ,relbase.concluido
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
