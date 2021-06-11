<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewMacroitemPreenchimento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [checklist_macroitem_preenchimento]');
        DB::unprepared("
        CREATE VIEW [checklist_macroitem_preenchimento]
            AS
            SELECT
            [checklist_id]
            ,[pai_id]
            ,[pai_nome]
            ,COUNT(relbase.id)  as total
            ,SUM(respondido)  as respondido
            ,percentual_respondido = CAST(SUM(respondido) * 100.00 / COUNT(relbase.id) as decimal(16,2))
           FROM [relatorio_base_respostas] relbase
           GROUP BY [checklist_id]
            ,[pai_id]
            ,[pai_nome]
       ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [checklist_macroitem_preenchimento]');
    }
}
