<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Demanda;
use Illuminate\Support\Facades\Auth;


class AtendimentoService
{
    protected $demanda;
    protected $tabela = 'VIRED_CHECKLIST_INCONFORMES_CAPTURADO';
    protected $campos = array(
        'NU_DEMANDA_ID'            => array('obrigatorio' => true, 'metodo' => '$this->demanda->id')
    ,'CO_UNIDADE_CGC'           => array('obrigatorio' => true, 'metodo' => '$this->demanda->unidade_id')
    ,'CO_MATRICULA_RESPONSAVEL' => array('obrigatorio' => true, 'metodo' => '$this->demanda->responsavel->matricula ?? Auth::id()')
    ,'DT_CRIACAO'               => array('obrigatorio' => true, 'metodo' => '(new \DateTime())->format("Y-m-d H:i:s")')
    ,'LOG_BASECONHECIMENTOID'   => array('obrigatorio' => true, 'metodo' => '$this->demanda->sistema_item_id')
    ,'DE_DEMANDA'               => array('obrigatorio' => true, 'metodo' => '$this->demanda->descricao')
    );

    function __construct(Demanda $demanda) {
        $this->demanda = $demanda;
    }

    public function executar() {

        echo 'Migracao: ' . $this->demanda->migracao . PHP_EOL;

        if(trim($this->demanda->migracao) == 'P')
        {
            $this->exportarDemanda();
        }
        else
        {
            $this->atualizarDemanda();
        }

        return $this->demanda;
    }

    protected function exportarDemanda(){
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

    protected function atualizarDemanda() {
        $sql = "
            SELECT
                [LOG_CHAMADO_ATENDIMENTOID] as demanda_id,
                RTRIM([LOG_CHAMADO_LINK]) as demanda_url,
                RTRIM([LOG_CHAMADO_STATUS_NOME]) as demanda_situacao
            FROM ". $this->tabela ."
            WHERE NU_DEMANDA_ID = ". $this->demanda->id ."
        ";
        $dados = DB::connection($this->demanda->sistema->conexao)->select($sql);

        foreach($dados as $retorno_demanda) {
            $this->demanda->demanda_id = $retorno_demanda->demanda_id;
            $this->demanda->demanda_url = $retorno_demanda->demanda_url;
            $this->demanda->demanda_situacao = $retorno_demanda->demanda_situacao;
            $this->demanda->save();
        }
    }

    protected function prepararDemanda() {
        $saida = [];
        $valor = '';

        foreach($this->campos as $campo => $dados){
            eval('$valor = ' . $dados['metodo'] . ';');
            $saida[$campo] = $valor;
        }

        return $saida;
    }

    protected function gravarRegistro($dados) {
        try {
            DB::connection($this->demanda->sistema->conexao)->beginTransaction();
            DB::connection($this->demanda->sistema->conexao)->table($this->tabela)->insert($dados);
            DB::connection($this->demanda->sistema->conexao)->commit();
        } catch (\Throwable $th) {
            DB::connection($this->demanda->sistema->conexao)->rollBack();
            throw new \Exception($th->getMessage(), 1);
        }


    }
}
