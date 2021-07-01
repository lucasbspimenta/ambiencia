<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() === 'production') {
            DB::unprepared("INSERT INTO [users] (
                [name]
                ,[matricula]
                ,[email]
                ,[cargo]
                ,[funcao]
                ,[fisica]
                ,[unidade]
            )
            SELECT
                RTRIM([no_empregado]) as [name]
                ,RTRIM([nu_matricula]) as [matricula]
                ,NULL as [email]
                ,RTRIM([co_cargo]) as [cargo]
                ,RTRIM([no_funcao]) as [funcao]
                ,RTRIM([co_lot_fisica]) as [fisica]
                ,RTRIM([co_lot_adm]) as [unidade]
            FROM [ATENDIMENTO].[dbo].[RH_EMPREGADOS]
            WHERE [nu_matricula] IN(
                SELECT DISTINCT RTRIM([CO_MATRICULA]) FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV]
                UNION
                SELECT DISTINCT RTRIM([CO_COORDENADOR]) FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV]
                UNION
                SELECT DISTINCT RTRIM([CO_SUPERVISOR]) FROM [RH_UNIDADES].[dbo].[EMPREGADOS_SEV])
            ;");
        } else {
            $usuario = [
                [
                    'name' => 'Lucas Pimenta    ',
                    'matricula' => 'C096810',
                    'email' => 'lucas.pimenta@caixa.gov.br',
                    'cargo' => 'TBN',
                    'funcao' => 'Assistente Sênior',
                    'fisica' => '7767',
                    'unidade' => '7001',
                ],
                [
                    'name' => 'Marcus Rodrigo',
                    'matricula' => 'C073128',
                    'email' => 'lucas.pimenta@caixa.gov.br',
                    'cargo' => 'TBN',
                    'funcao' => 'Assistente Sênior',
                    'fisica' => '7767',
                    'unidade' => '7001',
                ],
                [
                    'name' => 'Manoel Neto',
                    'matricula' => 'C132747',
                    'email' => 'lucas.pimenta@caixa.gov.br',
                    'cargo' => 'TBN',
                    'funcao' => 'Assistente Sênior',
                    'fisica' => '7767',
                    'unidade' => '7001',
                ],
                [
                    'name' => 'Nara Fiuza',
                    'matricula' => 'C074846',
                    'email' => 'lucas.pimenta@caixa.gov.br',
                    'cargo' => 'TBN',
                    'funcao' => 'Assistente Sênior',
                    'fisica' => '7767',
                    'unidade' => '7001',
                ],
                [
                    'name' => 'Thais Aghat Magalhaes Orestes',
                    'matricula' => 'C091844',
                    'email' => 'lucas.pimenta@caixa.gov.br',
                    'cargo' => 'TBN',
                    'funcao' => 'Assistente Sênior',
                    'fisica' => '7767',
                    'unidade' => '7001',
                ],
                [
                    'name' => 'Leonardo Jose Martins Carneiro',
                    'matricula' => 'C073029',
                    'email' => 'lucas.pimenta@caixa.gov.br',
                    'cargo' => 'TBN',
                    'funcao' => 'Assistente Sênior',
                    'fisica' => '7767',
                    'unidade' => '7001',
                ],
                [
                    'name' => 'Francisca Antonia Narcizo',
                    'matricula' => 'C029188',
                    'email' => 'lucas.pimenta@caixa.gov.br',
                    'cargo' => 'TBN',
                    'funcao' => 'Assistente Sênior',
                    'fisica' => '7767',
                    'unidade' => '7001',
                ],
                [
                    'name' => 'Josiane Reciolino de Oliveira',
                    'matricula' => 'C045238',
                    'email' => 'lucas.pimenta@caixa.gov.br',
                    'cargo' => 'TBN',
                    'funcao' => 'Assistente Sênior',
                    'fisica' => '7767',
                    'unidade' => '7001',
                ],
                [
                    'name' => 'Raimundo Everton de Aquino Moreira',
                    'matricula' => 'C082807',
                    'email' => 'lucas.pimenta@caixa.gov.br',
                    'cargo' => 'TBN',
                    'funcao' => 'Assistente Sênior',
                    'fisica' => '7767',
                    'unidade' => '7001',
                ],
            ];

            User::insert($usuario);
        }
    }
}
