<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[usuario_equipe]');
        DB::unprepared("
        CREATE VIEW [dbo].[usuario_equipe]
        as
            SELECT RTRIM([CO_MATRICULA]) as matricula
                ,EPA.[CO_EQUIPE] as id
                ,RTRIM(CE.NO_EQUIPE) as nome
                ,RTRIM(CE.CO_GESTOR) as gestor
            FROM [RH_UNIDADES].[dbo].[EMPREGADOS_PERFIL_ACESSO] EPA
            LEFT JOIN [RH_UNIDADES].[dbo].[CADASTRO_EQUIPES] CE ON EPA.CO_PERFIL = CE.CO_ID AND CE.[IC_ATIVO] = 'S'
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[usuario_equipe]');
    }
}
