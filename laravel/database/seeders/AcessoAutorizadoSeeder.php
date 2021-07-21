<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcessoAutorizadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = [
            ['identificador' => '1', 'tipo' => 'EQP', 'autorizado' => true]
            , ['identificador' => '15', 'tipo' => 'EQP', 'autorizado' => true]
            , ['identificador' => '17', 'tipo' => 'EQP', 'autorizado' => true]
            , ['identificador' => '29', 'tipo' => 'EQP', 'autorizado' => true]
            , ['identificador' => '31', 'tipo' => 'EQP', 'autorizado' => true]
            , ['identificador' => '41', 'tipo' => 'EQP', 'autorizado' => true]
            , ['identificador' => 'C073128', 'tipo' => 'USR', 'autorizado' => true]
            , ['identificador' => 'C083922', 'tipo' => 'USR', 'autorizado' => true],
        ];

        DB::table('acessos_autorizados')->insert($tipos);
    }
}
