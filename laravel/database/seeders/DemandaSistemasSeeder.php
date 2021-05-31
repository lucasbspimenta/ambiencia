<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\DemandaSistema;

class DemandaSistemasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sistemas = [
            [
                'nome' => 'Atendimento Log (chamados)'
                ,'conexao' => 'atendimento'
                ,'categorias_table' => '[dbo].[CATEGORIA_ITENS]'
                ,'categorias_campo_id' => '[CATEGORIAID]'
                ,'categorias_campo_texto' => '[NOME]'
                ,'categorias_filtros' => "([ATIVO] = 'S')"
                ,'itens_table' => '[dbo].[BASE_CONHECIMENTO]'
                ,'itens_campo_id' => '[BANCOCONHECIMENTOID]'
                ,'itens_campo_texto' => '[NOME_ITEM]'
                ,'itens_filtros' => "([EHATIVO] = 'S')"
                ,'itens_campo_id_categoria' => "[CATEGORIAID]"
                ,'service_class_name' => "AtendimentoService"
            ]
        ];

        DemandaSistema::insert($sistemas);
    }
}
