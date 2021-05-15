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
            SELECT
                DISTINCT
                RH_EMP_SEV.CO_MATRICULA as matricula,
                ATO_UNID.codigo as unidade_codigo,
                RH_EMP_SEV.CO_UNIDADE as sev_codigo
            FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV] RH_EMP_SEV
            INNER JOIN [dbo].[unidades] ATO_UNID ON RH_EMP_SEV.CO_UNIDADE = ATO_UNID.codigoSev
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
