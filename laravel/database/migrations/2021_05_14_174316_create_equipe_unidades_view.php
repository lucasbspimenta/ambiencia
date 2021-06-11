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
                ATO_UNID.codigo as unidade,
                ATO_UNID.id as unidade_id,
                CE.CO_ID as id,
                CE.NO_EQUIPE as nome,
				CE.CO_GESTOR as coordenador
            FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV] RH_EMP_SEV
            JOIN [dbo].[unidades] ATO_UNID ON RH_EMP_SEV.CO_UNIDADE = ATO_UNID.codigoSev
            JOIN [RH_UNIDADES].[dbo].[EMPREGADOS_PERFIL_ACESSO] EPA ON UPPER(RTRIM(EPA.CO_MATRICULA)) = UPPER(RTRIM(RH_EMP_SEV.CO_MATRICULA))
            JOIN [RH_UNIDADES].[dbo].[CADASTRO_EQUIPES] CE ON EPA.CO_EQUIPE = CE.CO_ID AND UPPER(RTRIM(CE.[IC_ATIVO])) = 'S'
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
