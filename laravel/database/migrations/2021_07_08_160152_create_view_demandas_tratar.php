<?php

use Illuminate\Database\Migrations\Migration;

class CreateViewDemandasTratar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [demandas_a_tratar]');
        DB::unprepared("
        CREATE VIEW [demandas_a_tratar]
        as
        SELECT
			dem_tratar.id
			,dem_tratar.solicitacao
			,dem_tratar.resposta
			,dem_tratar.migracao
			,dem_tratar.created_at
			,dem_tratar.updated_at
            ,dem_sis.id as [sistema_id]
            ,dem_sis.nome as [sistema_nome]
            ,unid.nome as [unidade_nome]
            ,unid.id as [unidade_id]
            ,unid.codigo as [unidade_codigo]
            ,COALESCE(dem_tratar.matricula, und_resp.matricula) as [responsavel]
            ,COALESCE([users].name, und_resp.nome_responsavel) as [responsavel_nome]
            ,[supervisor]
            ,[supervisor_nome]
            ,[coordenador]
            ,[coordenador_nome]
            ,[equipe_id]
            ,[equipe_nome]
        FROM [demanda_tratars] dem_tratar
        JOIN dbo.demanda_sistemas AS dem_sis ON dem_sis.id = dem_tratar.sistema_id
        LEFT JOIN [users] ON users.matricula = dem_tratar.matricula
        LEFT JOIN [unidades_responsavel] und_resp ON und_resp.unidade_codigo = dem_tratar.[unidade_id] OR dem_tratar.[matricula] = und_resp.matricula
        LEFT JOIN dbo.unidades AS unid ON unid.codigo = dem_tratar.[unidade_id]
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [demandas_a_tratar]');
    }
}
