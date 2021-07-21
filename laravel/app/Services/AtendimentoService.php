<?php

namespace App\Services;

use App\Models\Demanda;
use App\Models\DemandaSistema;
use Illuminate\Support\Facades\DB;

class AtendimentoService
{
    protected $demanda;

    protected $tabela = 'WS_DEMANDAS_EXTERNAS';
    protected $campos = array(
        'NU_DEMANDA_EXTERNA_ID' => array('obrigatorio' => true, 'metodo' => '$this->demanda->id')
        , 'CO_UNIDADE_CGC' => array('obrigatorio' => true, 'metodo' => '$this->demanda->unidade_id')
        , 'CO_MATRICULA_RESPONSAVEL' => array('obrigatorio' => true, 'metodo' => '$this->demanda->responsavel->matricula ?? Auth::user()->matricula')
        , 'DT_CRIACAO' => array('obrigatorio' => true, 'metodo' => '(new \DateTime())->format("Y-m-d H:i:s")')
        , 'LOG_BASECONHECIMENTOID' => array('obrigatorio' => true, 'metodo' => '$this->demanda->sistema_item_id')
        , 'DE_OBSERVACAO' => array('obrigatorio' => true, 'metodo' => '$this->demanda->descricao')
        , 'SISTEMA_ORIGEM' => array('obrigatorio' => true, 'metodo' => '"checklist"')
        , 'LOG_FOI_PROCESSADO_S_N' => array('obrigatorio' => true, 'metodo' => '"N"'),
    );

    public function __construct(Demanda $demanda)
    {
        $this->demanda = $demanda;
    }

    public function executar()
    {

        //echo 'Migracao: ' . $this->demanda->migracao . PHP_EOL;

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
            //dd($this->demanda);
            $this->demanda->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage(), 1);
        }
    }

    // protected function atualizarDemanda() {
    //     $sql = "
    //         SELECT
    //             [LOG_CHAMADO_ATENDIMENTOID] as demanda_id,
    //             RTRIM([LOG_CHAMADO_LINK]) as demanda_url,
    //             RTRIM([LOG_CHAMADO_STATUS_NOME]) as demanda_situacao
    //         FROM ". $this->tabela ."
    //         WHERE NU_DEMANDA_ID = ". $this->demanda->id ."
    //     ";
    //     $dados = DB::connection($this->demanda->sistema->conexao)->select($sql);

    //     foreach($dados as $retorno_demanda) {
    //         $this->demanda->demanda_id = $retorno_demanda->demanda_id;
    //         $this->demanda->demanda_url = $retorno_demanda->demanda_url;
    //         $this->demanda->demanda_situacao = $retorno_demanda->demanda_situacao;
    //         $this->demanda->save();
    //     }
    // }

    protected function atualizarDemanda()
    {
        DB::select("EXEC [ATUALIZA_DEMANDA_LOG] " . $this->demanda->id . " " . $this->demanda->demanda_id); //select($this->procedure,)->insert($dados);
    }

    protected function prepararDemanda()
    {
        $saida = [];
        $valor = '';

        foreach ($this->campos as $campo => $dados) {
            eval('$valor = ' . $dados['metodo'] . ';');
            $saida[$campo] = $valor;
        }

        return $saida;
    }

    protected function gravarRegistro($dados)
    {
        try {
            DB::connection($this->demanda->sistema->conexao)->beginTransaction();
            DB::connection($this->demanda->sistema->conexao)->table($this->tabela)->insert($dados);
            DB::connection($this->demanda->sistema->conexao)->commit();
        } catch (\Throwable $th) {
            DB::connection($this->demanda->sistema->conexao)->rollBack();
            throw new \Exception($th->getMessage(), 1);
        }

    }

    public static function atendimentoEmAndamentoUnidade($unidade_id)
    {
        $sistema = DemandaSistema::where('service_class_name', substr(static::class, strrpos(static::class, '\\') + 1))->first();

        $retorno = DB::connection($sistema->conexao)->select("SELECT * FROM [dbo].[VW_CHAMADOS_EM_ATENDIMENTO] WHERE ID_UNIDADE = '" . $unidade_id . "' ");

        $demandas = [];
        foreach ($retorno as $chamado_retornado) {
            $demanda = new Demanda();
            $demanda->unidade_id = $unidade_id;
            $demanda->migracao = 'C';
            $demanda->sistema_id = $sistema->id;
            $demanda->sistema_item_id = $chamado_retornado->BANCOCONHECIMENTOID;
            $demanda->demanda_id = $chamado_retornado->ATENDIMENTOID;
            $demanda->demanda_url = $chamado_retornado->LINK;
            $demanda->demanda_situacao = $chamado_retornado->SITUACAO_DEMANDA;
            $demanda->demanda_prazo = $chamado_retornado->DATA_PRAZO;
            $demanda->demanda_retorno = '';
            $demanda->demanda_conclusao = $chamado_retornado->DATA_FIM;
            $demanda->descricao = $chamado_retornado->DESCRICAO ?? 'Chamado sem descrição';
            $demandas[] = $demanda;
        }
        return $demandas;
    }
}
