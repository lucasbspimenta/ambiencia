<?php

use Illuminate\Database\Migrations\Migration;

class CreateProcedureAtualizarDemandas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS [ATUALIZA_DEMANDA]');
        DB::unprepared("
        CREATE PROCEDURE [dbo].[ATUALIZA_DEMANDA]
            @demanda_id bigint
            ,@demanda_id_externo bigint
            ,@demanda_url nvarchar(max)
            ,@demanda_situacao nvarchar(300)
            ,@demanda_prazo datetime
            ,@demanda_conclusao datetime
            ,@demanda_retorno nvarchar(max)
        AS
        BEGIN
            SET NOCOUNT ON;

            UPDATE demandas SET
            demanda_id = @demanda_id_externo,
            demanda_url = @demanda_url,
            demanda_situacao = @demanda_situacao,
            demanda_prazo = @demanda_prazo,
            demanda_conclusao = @demanda_conclusao,
            demanda_retorno = @demanda_retorno
            WHERE id = @demanda_id;

        END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS [ATUALIZA_DEMANDA]');
    }
}
