<?php

use Illuminate\Database\Migrations\Migration;

class CreateProcedureAtualizarDemandasLog extends Migration
{
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS [ATUALIZA_DEMANDA_LOG]');
        DB::unprepared("
        CREATE PROCEDURE [dbo].[ATUALIZA_DEMANDA_LOG]
            @demanda_id_ambiencia bigint
            ,@demanda_id_log bigint
        AS
        BEGIN
            SET NOCOUNT ON;

            --DECLARE @demanda_id bigint;
            DECLARE @demanda_id_externo bigint;
            DECLARE @demanda_url nvarchar(max);
            DECLARE @demanda_situacao nvarchar(300);
            DECLARE @demanda_prazo datetime;
            DECLARE @demanda_conclusao datetime;
            DECLARE @demanda_retorno nvarchar(max);

            DECLARE cursosAtendimento CURSOR FORWARD_ONLY FOR
                SELECT
                    [LOG_CHAMADO_LINK]
                    ,[LOG_CHAMADO_STATUS_NOME]
                    ,[LOG_CHAMADO_DATA_PRAZO_ATENDIMENTO]
                    ,[LOG_CHAMADO_DATA_EXECUCAO]

                FROM [ATENDIMENTO].[dbo].[WS_DEMANDAS_EXTERNAS]
                WHERE [LOG_CHAMADO_ATENDIMENTOID] = @demanda_id_log;
            OPEN cursosAtendimento;
            FETCH NEXT FROM cursosAtendimento INTO @demanda_url, @demanda_situacao, @demanda_prazo, @demanda_conclusao;
            WHILE @@FETCH_STATUS = 0 BEGIN
                EXECUTE [dbo].[ATUALIZA_DEMANDA] @demanda_id_ambiencia ,@demanda_id_log, @demanda_url, @demanda_situacao, @demanda_prazo, @demanda_conclusao, @demanda_retorno;

                FETCH NEXT FROM cursosAtendimento INTO @demanda_url, @demanda_situacao, @demanda_prazo, @demanda_conclusao;
            END;
            CLOSE cursosAtendimento;
            DEALLOCATE cursosAtendimento;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS [ATUALIZA_DEMANDA_LOG]');
    }
}
