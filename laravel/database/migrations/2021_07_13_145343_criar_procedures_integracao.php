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

        DB::unprepared('DROP PROCEDURE IF EXISTS [ATUALIZA_DEMANDA_TRATAR_ENGENHARIA]');
        DB::unprepared("CREATE PROCEDURE [dbo].[ATUALIZA_DEMANDA_TRATAR_ENGENHARIA]
                AS
                BEGIN
                    SET NOCOUNT ON;
                MERGE INTO [dbo].demanda_tratars
                USING (
                SELECT
                FK_SISTEMA_ID as sistema_id
                ,und.[id_unidade] as unidade_id
                            ,ESC_MATR_CRIACAO as matricula
                            ,ENG_PARECER_ID as demanda_id
                            ,ENG_SUB_ESC_PERGUNTA as solicitacao
                            ,ENG_SUB_ESC_RESPOSTA as resposta
                        FROM [ATENDIMENTO].[dbo].[WF_VW_ENG_PEDIDOS_ESC_AO_SISTEMA_ORIGEM]
                JOIN [ATENDIMENTO].[dbo].[UNIDADES_BUSCA] und ON und.[cunidade_int] = FK_UNIDADE_CRIACAO
                WHERE FK_SISTEMA_ID = 2
                ) AS reg
                ON demanda_tratars.demanda_id = reg.demanda_id
                WHEN MATCHED THEN
                UPDATE
                SET sistema_id = reg.sistema_id
                , unidade_id=reg.unidade_id
                , matricula=reg.matricula
                , demanda_id=reg.demanda_id
                , solicitacao=reg.solicitacao
                , resposta=reg.resposta
                WHEN NOT MATCHED THEN
                INSERT([sistema_id]
                ,[unidade_id]
                ,[matricula]
                ,[demanda_id]
                ,[solicitacao]
                ,[resposta]
                ,[migracao])
                VALUES (reg.[sistema_id]
                ,reg.unidade_id
                ,reg.matricula
                ,reg.demanda_id
                ,reg.solicitacao
                ,reg.resposta
                ,'P');
                END");
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
        DB::unprepared('DROP PROCEDURE IF EXISTS [ATUALIZA_DEMANDA_TRATAR_ENGENHARIA]');
    }
}
