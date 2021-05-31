<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersUnidadesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[usuario_unidades]');
        DB::unprepared("
        CREATE VIEW [dbo].[usuario_unidades]
        as
            SELECT DISTINCT * FROM (
                SELECT
                    DISTINCT
                    UPPER(RH_EMP_SEV.CO_MATRICULA) as matricula,
                    ATO_UNID.codigo as unidade_codigo,
                    ATO_UNID.id as unidade_id,
                    RH_EMP_SEV.CO_UNIDADE as sev_codigo
                FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV] RH_EMP_SEV
                INNER JOIN [dbo].[unidades] ATO_UNID ON RH_EMP_SEV.CO_UNIDADE = ATO_UNID.codigoSev
                UNION
                SELECT
                    DISTINCT
                    UPPER(CE.CO_GESTOR) as matricula,
                    ATO_UNID.codigo as unidade_codigo,
                    ATO_UNID.id as unidade_id,
                    RH_EMP_SEV.CO_UNIDADE as sev_codigo
                FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV] RH_EMP_SEV
                JOIN [dbo].[unidades] ATO_UNID ON RH_EMP_SEV.CO_UNIDADE = ATO_UNID.codigoSev
                JOIN [RH_UNIDADES].[dbo].[EMPREGADOS_PERFIL_ACESSO] EPA ON UPPER(EPA.CO_MATRICULA) = UPPER(RH_EMP_SEV.CO_MATRICULA)
                JOIN [RH_UNIDADES].[dbo].[CADASTRO_EQUIPES] CE ON EPA.CO_EQUIPE = CE.CO_ID AND CE.[IC_ATIVO] = 'S'
            ) usuario_unidades
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[usuario_unidades]');
    }
}
