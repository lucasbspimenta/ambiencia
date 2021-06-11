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
            ],
            [
                'name' => 'Manoel Neto',
                'matricula' => 'C132747',
                'email' => 'lucas.pimenta@caixa.gov.br',
                'cargo' => 'TBN',
                'funcao' => 'Assistente Sênior',
                'fisica' => '7767',
                'unidade' => '7001'
            ],
            [
                'name' => 'Nara Fiuza',
                'matricula' => 'C074846',
                'email' => 'lucas.pimenta@caixa.gov.br',
                'cargo' => 'TBN',
                'funcao' => 'Assistente Sênior',
                'fisica' => '7767',
                'unidade' => '7001'
            ],
            [
                'name' => 'Thais Aghat Magalhaes Orestes',
                'matricula' => 'C091844',
                'email' => 'lucas.pimenta@caixa.gov.br',
                'cargo' => 'TBN',
                'funcao' => 'Assistente Sênior',
                'fisica' => '7767',
                'unidade' => '7001'
            ],
            [
                'name' => 'Leonardo Jose Martins Carneiro',
                'matricula' => 'C073029',
                'email' => 'lucas.pimenta@caixa.gov.br',
                'cargo' => 'TBN',
                'funcao' => 'Assistente Sênior',
                'fisica' => '7767',
                'unidade' => '7001'
            ],
            [
                'name' => 'Francisca Antonia Narcizo',
                'matricula' => 'C029188',
                'email' => 'lucas.pimenta@caixa.gov.br',
                'cargo' => 'TBN',
                'funcao' => 'Assistente Sênior',
                'fisica' => '7767',
                'unidade' => '7001'
            ],
            [
                'name' => 'Josiane Reciolino de Oliveira',
                'matricula' => 'C045238',
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
