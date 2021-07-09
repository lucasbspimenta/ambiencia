<?php

use Illuminate\Database\Migrations\Migration;

class CreateUnidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[unidades]');
        DB::unprepared('
        CREATE VIEW [dbo].[unidades]
        as
        SELECT
            id_unidade as id,
            COALESCE(TRY_CAST(RTRIM(cUnidade) as integer), cUnidade) as codigo,
            cdv as codigoDv,
            RTRIM(cTipo) as tipo,
            RTRIM(cTipo_PV) as tipoPv,
            RTRIM(cNome) as nome,
            RTRIM(cEndereco_Cidade) as municipio,
            RTRIM(cEndereco_UF) as uf,
            RTRIM(cBairro) as bairro,
            RTRIM(cEndereco) as endereco,
            RTRIM(cSituacao) as situacao,
            RTRIM(cEmail) as email,
            RTRIM(cCEP) as cep,
            cSubordinacao as codigoSubordinacao,
            cSubordinacao_dv as codigoDvSubordinacao,
            RTRIM(cSubordinacao_Tipo) as tipoSubordinacao,
            RTRIM(cSubordinacao_Nome) as nomeSubordinacao,
            csev_codigo_especifico as codigoSev,
            csev_dv as codigoDvSev,
            RTRIM(csev_tipo) as tipoSev,
            RTRIM(csev_nome) as nomeSev,
            RTRIM(csev_nome_completo) as nomeCompletoSev
        FROM
            ATENDIMENTO.dbo.UNIDADES_BUSCA
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[unidades]');
    }
}
