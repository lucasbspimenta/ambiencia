<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[usuario_perfil]');
        DB::unprepared("
        CREATE VIEW [dbo].[usuario_perfil]
        as
            SELECT RTRIM([CO_MATRICULA]) as matricula
                ,EPA.[CO_PERFIL] as id
                ,RTRIM(CPA.DE_PERFIL) as nome
            FROM [RH_UNIDADES].[dbo].[EMPREGADOS_PERFIL_ACESSO] EPA
            LEFT JOIN [RH_UNIDADES].[dbo].[CODIGOS_PERFIL_ACESSO] CPA ON EPA.CO_PERFIL = CPA.CO_PERFIL AND CPA.[IC_ATIVO] = 'S'
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [dbo].[usuario_perfil]');
    }
}
