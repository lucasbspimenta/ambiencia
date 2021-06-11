<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewRelatorioBaseAgendamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [relatorio_base_agendamentos]');
        DB::unprepared("
        CREATE VIEW [relatorio_base_agendamentos]
        as
        SELECT
              uresp.unidade_id
              ,uresp.unidade_codigo
              ,uresp.sev_codigo as sev_codigo
              , uresp.matricula as responsavel
              , equip.id as equipe_id
              , equip.nome as equipe_nome
              , uresp.coordenador
              , uresp.supervisor
              ,visitado = CASE WHEN [final] < GETDATE() THEN 1 ELSE 0 END
              ,[agendamento_tipos_id] as agendamento_tipo
              ,age.[id] as agendamento_id
              ,[inicio] as agendamento_inicio
              ,[final] as agendamento_final
            FROM [unidades_responsavel] uresp
            JOIN [equipe_unidades] equip ON uresp.unidade_id = equip.unidade_id
            LEFT JOIN [agendamentos] age ON age.unidade_id = uresp.unidade_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [relatorio_base_agendamentos]');
    }
}
