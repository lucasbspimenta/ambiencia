<?php

use Illuminate\Database\Migrations\Migration;

class CreateViewDemandasBase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [demandas_base]');
        DB::unprepared("
        CREATE VIEW [demandas_base]
        as
        SELECT
            dem.id AS demanda_id,
            dem.migracao AS demanda_migracao,
            dem.demanda_situacao,
            dem.descricao AS demanda_descricao,
			dem.demanda_prazo,
			dem.demanda_prazo_inicial,
			dem.demanda_conclusao,
            dem.updated_at as demanda_atualizacao,
            dem_sis.id AS sistema_id,
            dem_sis.nome AS sistema_nome,
            RTRIM(unid.tipoPv) + ' ' + RTRIM(unid.nome) AS unidade_nome,
            unid.id AS unidade_id,
            unid.codigo AS unidade_codigo,
            unid_resp.matricula AS responsavel,
            unid_resp.nome_responsavel AS responsavel_nome,
            unid_resp.supervisor,
            unid_resp.supervisor_nome,
            unid_resp.coordenador,
            unid_resp.coordenador_nome,
            unid_resp.equipe_id,
            unid_resp.equipe_nome
        FROM
            dbo.demandas AS dem INNER JOIN
            dbo.demanda_sistemas AS dem_sis ON dem_sis.id = dem.sistema_id INNER JOIN
            dbo.unidades AS unid ON unid.id = dem.unidade_id INNER JOIN
            dbo.unidades_responsavel AS unid_resp ON unid_resp.unidade_id = dem.unidade_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [demandas_base]');
    }
}
