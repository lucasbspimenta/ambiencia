<?php

use Illuminate\Database\Migrations\Migration;

class CreateViewChecklistDemandasAndamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [checklist_demandas_andamento]');
        DB::unprepared("
        CREATE VIEW [checklist_demandas_andamento]
        as
        SELECT chk.id as checklist_id
            , CAST(COUNT(dem.id) as decimal(8,2)) as total_demandas
            ,CAST(SUM(CASE WHEN UPPER(RTRIM(dem.demanda_situacao)) = UPPER('Finalizado') THEN 1 ELSE 0 END) as decimal(8,2)) as total_finalizada

        FROM [checklists] chk
        INNER JOIN [checklist_respostas] chk_resp ON chk.id = chk_resp.checklist_id AND chk_resp.resposta = -1
        INNER JOIN [demanda_checklist_resposta] dem_chk_resp ON chk_resp.id = dem_chk_resp.[checklist_resposta_id]
        INNER JOIN [demandas] dem ON dem.id = dem_chk_resp.demanda_id
        WHERE chk.[concluido] = 1 AND chk.[deleted_at] IS NULL
        GROUP BY chk.id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [checklist_demandas_andamento]');
    }
}
