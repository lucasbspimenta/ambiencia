<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Administracao\AgendamentoTipo;

class AgendamentoTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = [
            ['nome' => 'Clássico', 'descricao' => 'Visita geral prevista', 'cor' => '#014478']
           ,['nome' => 'Ampliado', 'descricao' => 'Adicionais realizados na base', 'cor' => '#008138']
           ,['nome' => 'Me chama que eu vou', 'descricao' => 'A pedido dos gestores', 'cor' => '#A65F00']
           ,['nome' => 'Emergencial', 'descricao' => 'Sinistro, Mudanças de Endereço, etc', 'cor' => '#A62300']
        ];

        AgendamentoTipo::insert($tipos);
    }
}
