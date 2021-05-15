<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario = [
            [
                'name' => 'Lucas Pimenta    ',
                'matricula' => 'C096810',
                'email' => 'lucas.pimenta@caixa.gov.br',
                'cargo' => 'TBN',
                'funcao' => 'Assistente Sênior',
                'fisica' => '7767',
                'unidade' => '7001'
            ],
            [
                'name' => 'Marcus Rodrigo',
                'matricula' => 'C073128',
                'email' => 'lucas.pimenta@caixa.gov.br',
                'cargo' => 'TBN',
                'funcao' => 'Assistente Sênior',
                'fisica' => '7767',
                'unidade' => '7001'
            ]
        ];

        User::insert($usuario);
    }
}
