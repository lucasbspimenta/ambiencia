<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateViewUsuarioDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS [usuario_dados]');

        if (App::environment() === 'production') {

            DB::unprepared("CREATE VIEW [usuario_dados]
            AS
            SELECT
                rh.nu_matricula
                ,rh.no_empregado
                ,rh.[co_cargo]
                ,rh.[no_funcao]
                ,rh.[co_lot_fisica]
                ,rh.[co_lot_adm]
            FROM [ATENDIMENTO].[dbo].[RH_EMPREGADOS] rh
            ");
        } else {
            DB::unprepared("CREATE VIEW [usuario_dados]
            AS
            SELECT
                COALESCE(rh.nu_matricula, u.matricula) as matricula
                ,COALESCE(rh.no_empregado, u.name) as nome
                ,COALESCE(rh.[co_cargo], u.cargo) as cargo
                ,COALESCE(rh.[no_funcao], u.funcao) as funcao
                ,COALESCE(rh.[co_lot_fisica], u.fisica) as fisica
                ,COALESCE(rh.[co_lot_adm], u.unidade) as unidade
            FROM users u
            FULL OUTER JOIN [RH_UNIDADES].[dbo].[VW_RH_EMPREGADOS] rh ON UPPER(RTRIM(u.matricula)) = UPPER(RTRIM(rh.nu_matricula))
        ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS [usuario_dados]');
    }
}
