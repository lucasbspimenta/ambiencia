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
            SELECT DISTINCT * FROM (
            SELECT UPPER(RTRIM([CO_MATRICULA])) as matricula
                ,EPA.[CO_EQUIPE] as id
                ,RTRIM(CE.NO_EQUIPE) as nome
                ,UPPER(RTRIM(CE.CO_GESTOR)) as gestor
            FROM [RH_UNIDADES].[dbo].[EMPREGADOS_PERFIL_ACESSO] EPA
            LEFT JOIN [RH_UNIDADES].[dbo].[CADASTRO_EQUIPES] CE ON EPA.CO_EQUIPE = CE.CO_ID AND CE.[IC_ATIVO] = 'S'
            WHERE EPA.[CO_EQUIPE] IS NOT NULL
			UNION
			SELECT
				UPPER(RTRIM([CO_GESTOR])) as matricula
				,CE.[CO_ID] as id
                ,RTRIM(CE.NO_EQUIPE) as nome
                ,NULL as gestor
			FROM [RH_UNIDADES].[dbo].[CADASTRO_EQUIPES] CE
			WHERE CE.[IC_ATIVO] = 'S'
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
