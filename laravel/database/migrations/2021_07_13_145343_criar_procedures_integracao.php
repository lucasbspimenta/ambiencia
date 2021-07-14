<?php

use Illuminate\Database\Migrations\Migration;

class CriarProceduresIntegracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS [PROC_RETORNO_ENGENHARIA]');
        DB::unprepared("CREATE PROCEDURE [dbo].[PROC_RETORNO_ENGENHARIA]
                            @ID_ENGENHARIA int
                        AS
                        BEGIN
                            SET NOCOUNT ON;

                            DECLARE @ID bigint;
                            DECLARE @DEMANDA_ID int;

                            DECLARE cursorDemanda CURSOR FOR
                                SELECT
                                    id
                                    ,demanda_id
                                FROM [dbo].demandas
                                WHERE demanda_id = @ID_ENGENHARIA;
                            OPEN cursorDemanda;
                            FETCH NEXT FROM cursorDemanda INTO @ID, @DEMANDA_ID;
                            WHILE @@FETCH_STATUS = 0 BEGIN
                                EXECUTE [dbo].[ATUALIZA_DEMANDA_ENGENHARIA] @ID ,@DEMANDA_ID;

                                FETCH NEXT FROM cursorDemanda INTO @ID, @DEMANDA_ID;
                            END;
                            CLOSE cursorDemanda;
                            DEALLOCATE cursorDemanda;
                        END
                        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS [PROC_RETORNO_ATENDIMENTO]');
        DB::unprepared("CREATE PROCEDURE [dbo].[PROC_RETORNO_ATENDIMENTO]
                            @ID_INTERNO bigint,
                            @LOG_FOI_PROCESSADO_S_N nvarchar(1),
                            @LOG_CHAMADO_ATENDIMENTOID bigint,
                            @LOG_CHAMADO_LINK nvarchar(max),
                            @LOG_CHAMADO_STATUS_NOME nvarchar(300),
                            @LOG_CHAMADO_DATA_PRAZO_ATENDIMENTO datetime
                        AS
                        BEGIN
                            SET NOCOUNT ON;

                            UPDATE demandas SET
                            demanda_id = @LOG_CHAMADO_ATENDIMENTOID,
                            demanda_url = @LOG_CHAMADO_LINK,
                            demanda_situacao = @LOG_CHAMADO_STATUS_NOME
                            WHERE id = @ID_INTERNO
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
        DB::unprepared('DROP PROCEDURE IF EXISTS [PROC_RETORNO_ENGENHARIA]');
        DB::unprepared('DROP PROCEDURE IF EXISTS [PROC_RETORNO_ATENDIMENTO]');
    }
}
