<?php

use Illuminate\Database\Migrations\Migration;

class CreateProcedureAtualizarDemandasEngenharia extends Migration
{
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS [ATUALIZA_DEMANDA_ENGENHARIA]');
        DB::unprepared("
        CREATE PROCEDURE [dbo].[ATUALIZA_DEMANDA_ENGENHARIA]
            @demanda_id_ambiencia bigint
            ,@demanda_id_engenharia bigint
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

            DECLARE cursorEngenharia CURSOR FORWARD_ONLY FOR
                SELECT
                    [DEM_LINK]
                    ,[DEM_SIT_NOME]
                    ,[ETR_DATA_TERMINO_VIGENTE] -- DATA PRAZO
                    ,NULL -- DATA CONCLUSAO
                    ,[ENG_PARECER]
                FROM [ATENDIMENTO].[dbo].[WF_VW_ENG_PARECERES]
                WHERE [DEM_ID] = @demanda_id_engenharia;
            OPEN cursorEngenharia;
            FETCH NEXT FROM cursorEngenharia INTO @demanda_url, @demanda_situacao, @demanda_prazo, @demanda_conclusao, @demanda_retorno;
            WHILE @@FETCH_STATUS = 0 BEGIN
                EXECUTE [dbo].[ATUALIZA_DEMANDA] @demanda_id_ambiencia ,@demanda_id_engenharia, @demanda_url, @demanda_situacao, @demanda_prazo, @demanda_conclusao, @demanda_retorno;

                FETCH NEXT FROM cursorEngenharia INTO @demanda_url, @demanda_situacao, @demanda_prazo, @demanda_conclusao, @demanda_retorno;
            END;
            CLOSE cursorEngenharia;
            DEALLOCATE cursorEngenharia;

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
        DB::unprepared('DROP PROCEDURE IF EXISTS [ATUALIZA_DEMANDA_ENGENHARIA]');
    }
}
