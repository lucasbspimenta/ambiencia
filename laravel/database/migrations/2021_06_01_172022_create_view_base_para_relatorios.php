<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateViewBaseParaRelatorios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[relatorio_base_respostas]');
        DB::unprepared("
        CREATE VIEW [dbo].[relatorio_base_respostas]
        as
        SELECT
    unidade_id
    ,cki_pai.id as pai_id
    ,cki_pai.nome as pai_nome
    ,cki.id
    ,cki.nome
	,cki.foto as foto_obrigatoria
	,ck.id as checklist_id
	,age.id as agendamento_id
	,age.[inicio] as agendamento_inicio
    ,age.[final] as agendamento_final
    ,inconforme = CASE WHEN ckr.resposta = -1 THEN 1 ELSE 0 END
    ,conforme = CASE WHEN ckr.resposta = 1 THEN 1 ELSE 0 END
    ,naoseaplica = CASE WHEN ckr.resposta = 0 THEN 1 ELSE 0 END
	,foto_enviada = CASE WHEN ckr.foto IS NOT NULL THEN 1 ELSE 0 END
    ,pendente = CASE
					WHEN ckr.resposta IS NULL AND cki_pai.id <> cki.id THEN 1
					WHEN ckr.resposta IS NULL AND cki_pai.id = cki.id AND cki.foto = 'S' AND ckr.foto IS NULL THEN 1
					WHEN ckr.resposta IS NULL AND cki_pai.id = cki.id AND cki.foto = 'N' THEN 0
					ELSE 0
				END
    ,respondido = CASE
					WHEN ckr.resposta = -1 AND dem.total > 0 AND cki.foto = 'N' THEN 1
					WHEN ckr.resposta IN (1,0) AND cki.foto = 'N' THEN 1
					WHEN ckr.resposta = -1 AND dem.total > 0 AND cki.foto = 'S' AND ckr.foto IS NOT NULL THEN 1
					WHEN ckr.resposta IN (1,0) AND cki.foto = 'S'  AND ckr.foto IS NOT NULL THEN 1
					WHEN ckr.resposta IS NULL AND cki_pai.id = cki.id AND cki.foto = 'S' AND ckr.foto IS NOT NULL THEN 1
					ELSE 0
				END
	,dem.total as demandas
	,ck.concluido
    FROM [checklist_items] cki
    INNER JOIN [checklist_items] cki_pai ON cki_pai.id = COALESCE(cki.item_pai_id, cki.id)
    JOIN [checklist_respostas] ckr ON ckr.checklist_item_id = cki.id
    JOIN [checklists] ck ON ck.id = ckr.checklist_id
    JOIN [agendamentos] age ON age.id = ck.agendamento_id
	CROSS APPLY (SELECT COUNT(demanda_id) as total FROM [demanda_checklist_resposta] dem_resp WHERE dem_resp.checklist_resposta_id = ckr.id) dem
              ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[relatorio_base_respostas]');
    }
}
