<?php

namespace App\Services;

use App\Models\Demanda;
use Illuminate\Support\Facades\DB;

class EngenhariaService
{
    protected $demanda;

    protected $tabela = 'WF_VW_ENG_PARECERES';
    protected $procedure = 'Wf_eng_demanda_nova';

    protected $campos = array(
        'fk_dem_origem_id' => array('obrigatorio' => true, 'metodo' => '$this->demanda->id')
        , 'fk_unidade' => array('obrigatorio' => true, 'metodo' => '$this->demanda->unidade_id')
        , 'matr_criacao' => array('obrigatorio' => true, 'metodo' => '$this->demanda->responsavel->matricula ?? Auth::user()->matricula')
        , 'fk_eng_tdem_id' => array('obrigatorio' => true, 'metodo' => '$this->demanda->sistema_item_id')
        , 'dem_desc' => array('obrigatorio' => true, 'metodo' => '$this->demanda->descricao')
        , 'sistema_id' => array('obrigatorio' => true, 'metodo' => '"2"'),
    );

    public function __construct(Demanda $demanda)
    {
        $this->demanda = $demanda;
    }

    public function executar()
    {

        echo 'Migracao: ' . $this->demanda->migracao . PHP_EOL;

        if (trim($this->demanda->migracao) == 'P') {
            $this->exportarDemanda();
        } else {
            $this->atualizarDemanda();
        }

        return $this->demanda;
    }

    protected function exportarDemanda()
    {
        try {
            DB::beginTransaction();
            $this->gravarRegistro($this->prepararDemanda());
            $this->demanda->migracao = 'C';
            $this->demanda->demanda_situacao = 'Incluído na fila de processamento';
            $this->demanda->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage(), 1);
        }
    }

    protected function atualizarDemanda()
    {
        DB::select("EXEC [ATUALIZA_DEMANDA_ENGENHARIA] ". $this->demanda->id ." " . $this->demanda->demanda_id); //select($this->procedure,)->insert($dados);
    }

    protected function prepararDemanda()
    {
        $saida = [];
        $valor = '';

        foreach ($this->campos as $campo => $dados) {
            eval('$valor = ' . $dados['metodo'] . ';');
            $saida[] = "@" . trim($campo) . " = '" . $valor . "'";
        }

        return $saida;
    }

    protected function gravarRegistro($dados)
    {
        try {
            DB::connection($this->demanda->sistema->conexao)->beginTransaction();
            DB::connection($this->demanda->sistema->conexao)->select("EXEC " . $this->procedure . " " . implode(',', $dados)); //select($this->procedure,)->insert($dados);
            DB::connection($this->demanda->sistema->conexao)->commit();
        } catch (\Throwable $th) {
            DB::connection($this->demanda->sistema->conexao)->rollBack();
            throw new \Exception($th->getMessage(), 1);
        }

    }
}
