<?php

namespace App\Services;

use App\Models\Demanda;
use App\Models\DemandaTratar;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class DemandaService
{
    public function criar(array $data): Demanda
    {
        $validator = Validator::make($data, Demanda::VALIDATION_RULES, Demanda::VALIDATION_MESSAGES);
        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return Demanda::create($data);
    }

    public function findById($id)
    {
        return Demanda::findOrFail($id);
    }

    public function existsById($id)
    {
        return Demanda::where('id', $id)->exists($id);
    }

    public function excluir($id)
    {
        return Demanda::findOrFail($id)->delete();
    }

    public static function processa(Demanda $demanda)
    {
        //echo $demanda->sistema->service_class_name . PHP_EOL;
        if ($demanda->sistema->service_class_name && class_exists('App\\Services\\' . $demanda->sistema->service_class_name)) {
            $class = new \ReflectionClass('App\\Services\\' . $demanda->sistema->service_class_name);
            $instance = $class->newInstanceArgs([$demanda]);
            return $instance->executar();
        } else {
            throw new \Exception("Sistema da demanda sem classe de tratamento registrada", 1);
        }
    }

    public function todosDatatables($finalizados = false)
    {
        $usuario = Auth::user();

        $sql = "SELECT
                    *
                FROM [demandas_base]
                WHERE 1=1
        ";

        if (!($usuario->is_gestor)) {
            $sql .= " AND (responsavel = '" . $usuario->matricula . "' OR supervisor = '" . $usuario->matricula . "' OR coordenador = '" . $usuario->matricula . "')";
        }

        if ($finalizados) {
            $sql .= " AND (UPPER(RTRIM(demanda_situacao)) = 'FINALIZADO' OR UPPER(RTRIM(demanda_situacao)) = 'CONCLUÍDA')";
        } else {
            $sql .= " AND (UPPER(RTRIM(demanda_situacao)) <> 'FINALIZADO' AND UPPER(RTRIM(demanda_situacao)) <> 'CONCLUÍDA')";
        }

        $sql .= " ORDER BY demanda_prazo ASC, demanda_atualizacao ASC";

        $dados = DB::select($sql);

        return collect($dados);
    }

    public function tratar($contarPendentes = false)
    {
        $usuario = Auth::user();

        $sql = "SELECT
                    *
                FROM [demandas_a_tratar]
                WHERE 1=1
        ";

        if (!($usuario->is_gestor)) {
            $sql .= " AND (responsavel = '" . $usuario->matricula . "' OR supervisor = '" . $usuario->matricula . "' OR coordenador = '" . $usuario->matricula . "')";
        }

        if ($contarPendentes) {
            $sql = str_ireplace('*', 'count(id) as total', $sql);
            $sql .= ' AND resposta IS NULL';
        } else {
            $sql .= " ORDER BY id DESC";
        }

        $dados = DB::select($sql);

        return collect($dados);
    }

    public static function processaDemandaTratada(DemandaTratar $demanda)
    {
        $usuario = User::find($demanda->updated_by);

        if (!is_null($demanda->resposta) && is_numeric($demanda->demanda_id) && $demanda->sistema && $demanda->sistema->conexao && $usuario->matricula) {
            try {
                DB::connection($demanda->sistema->conexao)->beginTransaction();
                DB::connection($demanda->sistema->conexao)->select("DECLARE @PARECER_ID int; EXEC @PARECER_ID = WF_ENG_RESPONDE_ESCLARECIMENTO @parecer_id=" . $demanda->demanda_id . ", @resposta='" . $demanda->resposta . "', @matr_criacao='" . $usuario->matricula . "'; SELECT @PARECER_ID as PARECER_ID;");
                DB::connection($demanda->sistema->conexao)->commit();

                $demanda->migracao = 'C';
                $demanda->save();

                return true;

            } catch (\Throwable $th) {
                DB::connection($demanda->sistema->conexao)->rollBack();
                throw new \Exception($th->getMessage(), 1);
                return false;
            }

        } else {
            return false;
        }
    }
}
