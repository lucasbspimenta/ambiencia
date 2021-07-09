<?php

use Illuminate\Database\Migrations\Migration;

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
        SELECT DISTINCT * FROM (
            SELECT UPPER(RTRIM([CO_MATRICULA])) as matricula
                ,EPA.[CO_EQUIPE] as id
                ,CE.[CO_UNIDADE] as unidade
                ,RTRIM(CE.NO_EQUIPE) as nome
                ,UPPER(RTRIM(CE.CO_GESTOR)) as gestor
            FROM [RH_UNIDADES].[dbo].[EMPREGADOS_PERFIL_ACESSO] EPA
            LEFT JOIN [RH_UNIDADES].[dbo].[CADASTRO_EQUIPES] CE ON EPA.CO_EQUIPE = CE.CO_ID AND CE.[IC_ATIVO] = 'S'
            WHERE EPA.[CO_EQUIPE] IS NOT NULL
			) usuario_equipe
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
