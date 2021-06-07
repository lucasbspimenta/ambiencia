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
            ,[checklist_id]
            ,age.inicio
            ,age.final
            ,age.unidade_id
            , SUM(respondido)  as respondido
            , COUNT(relbase.id)  as total
            ,percentual_respondido = CAST(SUM(respondido) * 100.00 / COUNT(relbase.id) as decimal(16,2))
            FROM [relatorio_base_respostas] relbase
            JOIN [usuario_unidades] uu ON uu.unidade_id = relbase.unidade_id
            JOIN [agendamentos] age ON age.id = relbase.agendamento_id

            GROUP BY uu.[matricula]
            ,[checklist_id]
            ,age.inicio
            ,age.final
            ,age.unidade_id
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
