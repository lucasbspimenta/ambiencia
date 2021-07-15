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
                            @LOG_CHAMADO_ATENDIMENTOID int
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
                                WHERE demanda_id = @LOG_CHAMADO_ATENDIMENTOID;
                            OPEN cursorDemanda;
                            FETCH NEXT FROM cursorDemanda INTO @ID, @DEMANDA_ID;
                            WHILE @@FETCH_STATUS = 0 BEGIN
                                EXECUTE [dbo].[ATUALIZA_DEMANDA_LOG] @ID ,@DEMANDA_ID;

                                FETCH NEXT FROM cursorDemanda INTO @ID, @DEMANDA_ID;
                            END;
                            CLOSE cursorDemanda;
                            DEALLOCATE cursorDemanda;
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
