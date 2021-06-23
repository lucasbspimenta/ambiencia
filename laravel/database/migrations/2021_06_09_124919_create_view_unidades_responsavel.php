<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
                COALESCE(ud.nome, UPPER(RH_EMP_SEV.CO_MATRICULA)) as nome
                ,ud.[cargo]
                ,ud.[funcao]
                ,ud.[fisica]
                ,ud.[unidade]
				,UPPER(RTRIM(COALESCE(NULLIF(RH_EMP_SEV.CO_COORDENADOR,''),EQP_UNID.coordenador))) as coordenador
				,COALESCE(coord.nome,UPPER(RTRIM(COALESCE(NULLIF(RH_EMP_SEV.CO_COORDENADOR,''),EQP_UNID.coordenador)))) as coordenador_nome
				,COALESCE(UPPER(RTRIM(NULLIF(RH_EMP_SEV.CO_SUPERVISOR,''))), UPPER(RTRIM(COALESCE(NULLIF(RH_EMP_SEV.CO_COORDENADOR,''),EQP_UNID.coordenador)))) as supervisor
				,COALESCE(supv.nome,COALESCE(UPPER(RTRIM(NULLIF(RH_EMP_SEV.CO_SUPERVISOR,''))), UPPER(RTRIM(COALESCE(NULLIF(RH_EMP_SEV.CO_COORDENADOR,''),EQP_UNID.coordenador))))) as supervisor_nome
				,EQP_UNID.id as equipe_id
				,EQP_UNID.nome as equipe_nome
            FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV] RH_EMP_SEV
            INNER JOIN [unidades] ATO_UNID ON RH_EMP_SEV.CO_UNIDADE = ATO_UNID.codigoSev
			INNER JOIN [equipe_unidades] EQP_UNID ON ATO_UNID.id = EQP_UNID.unidade_id
            INNER JOIN usuario_dados ud ON ud.matricula = UPPER(RH_EMP_SEV.CO_MATRICULA)
			INNER JOIN usuario_dados coord ON coord.matricula = UPPER(RTRIM(COALESCE(NULLIF(RH_EMP_SEV.CO_COORDENADOR,''),EQP_UNID.coordenador)))
			INNER JOIN usuario_dados supv ON supv.matricula = COALESCE(UPPER(RTRIM(NULLIF(RH_EMP_SEV.CO_SUPERVISOR,''))), UPPER(RTRIM(COALESCE(NULLIF(RH_EMP_SEV.CO_COORDENADOR,''),EQP_UNID.coordenador))))
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
