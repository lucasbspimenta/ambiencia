<?php

namespace Database\Seeders;

use App\Models\DemandaSistema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                , 'conexao' => 'atendimento'
                , 'categorias_table' => '[dbo].[CATEGORIA_ITENS]'
                , 'categorias_campo_id' => '[CATEGORIAID]'
                , 'categorias_campo_texto' => '[NOME]'
                , 'categorias_filtros' => "([ATIVO] = 'S')"
                , 'itens_table' => '[dbo].[BASE_CONHECIMENTO]'
                , 'itens_campo_id' => '[BANCOCONHECIMENTOID]'
                , 'itens_campo_texto' => '[NOME_ITEM]'
                , 'itens_filtros' => "([EHATIVO] = 'S')"
                , 'itens_campo_id_categoria' => "[CATEGORIAID]"
                , 'service_class_name' => "AtendimentoService"
                , 'url_base' => null,
            ],
            [
                'nome' => 'Atendimento Técnico - Engenharia'
                , 'conexao' => 'atendimento'
                , 'categorias_table' => null
                , 'categorias_campo_id' => null
                , 'categorias_campo_texto' => null
                , 'categorias_filtros' => null
                , 'itens_table' => '[dbo].[WF_ENG_TDEM_DEMANDA]'
                , 'itens_campo_id' => '[ENG_TDEM_ID]'
                , 'itens_campo_texto' => '[ENG_TDEM_NOME]'
                , 'itens_filtros' => "([ATIVO] = 1)"
                , 'itens_campo_id_categoria' => null
                , 'service_class_name' => "EngenhariaService"
                , 'url_base' => "http://log.df.caixa/fluxo/engenharia/",
            ],
            [
                'nome' => 'Outros (Sem integração)'
                , 'conexao' => 'sqlsrv'
                , 'categorias_table' => null
                , 'categorias_campo_id' => null
                , 'categorias_campo_texto' => null
                , 'categorias_filtros' => null
                , 'itens_table' => '[dbo].[integracao_inexistente]'
                , 'itens_campo_id' => '[id]'
                , 'itens_campo_texto' => '[descricao]'
                , 'itens_filtros' => null
                , 'itens_campo_id_categoria' => null
                , 'service_class_name' => "IntegracaoInexistenteService"
                , 'url_base' => null,
            ],

        ];

        DemandaSistema::insert($sistemas);

        DB::table('integracao_inexistente')->insert([
            ['descricao' => 'Demanda encaminha por e-mail ou terceiros sem serviços de integração.'],
        ]);
    }
}
