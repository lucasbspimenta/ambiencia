<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipeUnidadesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[equipe_unidades]');
        DB::unprepared("
        CREATE VIEW [dbo].[equipe_unidades]
        as
            SELECT
                DISTINCT
                unidade_codigo as unidade,
                ue.id,
                ue.nome
              FROM [dbo].[usuario_unidades] uu
              JOIN [dbo].[usuario_equipe] ue ON uu.matricula = ue.matricula
              GROUP BY unidade_codigo, ue.id,
                ue.nome
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipe_unidades');
    }
}
