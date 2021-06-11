<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateViewUnidadesResponsavel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [unidades_responsavel]');
        DB::unprepared("
            CREATE VIEW [unidades_responsavel]
            AS
            SELECT
                DISTINCT
                UPPER(RH_EMP_SEV.CO_MATRICULA) as matricula,
                ATO_UNID.codigo as unidade_codigo,
                ATO_UNID.id as unidade_id,
                RH_EMP_SEV.CO_UNIDADE as sev_codigo,
                ud.nome
                ,ud.[cargo]
                ,ud.[funcao]
                ,ud.[fisica]
                ,ud.[unidade]
				,UPPER(RTRIM(COALESCE(RH_EMP_SEV.CO_COORDENADOR,EQP_UNID.coordenador))) as coordenador
				,UPPER(RTRIM(RH_EMP_SEV.CO_SUPERVISOR)) as supervisor
				,EQP_UNID.id as equipe_id
				,EQP_UNID.nome as equipe_nome
            FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV] RH_EMP_SEV
            INNER JOIN [dbo].[unidades] ATO_UNID ON RH_EMP_SEV.CO_UNIDADE = ATO_UNID.codigoSev
            LEFT JOIN usuario_dados ud ON ud.matricula = UPPER(RH_EMP_SEV.CO_MATRICULA)
			INNER JOIN [dbo].[equipe_unidades] EQP_UNID ON ATO_UNID.id = EQP_UNID.unidade_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [unidades_responsavel]');
    }
}
