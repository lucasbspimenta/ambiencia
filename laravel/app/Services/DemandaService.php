<?php

namespace App\Services;

use App\Models\Demanda;
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
        echo $demanda->sistema->service_class_name . PHP_EOL;
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

        if (!($usuario->is_matriz)) {
            $sql .= " AND (responsavel = '" . $usuario->matricula . "' OR supervisor = '" . $usuario->matricula . "' OR coordenador = '" . $usuario->matricula . "')";
        }

        if ($finalizados) {
            $sql .= " AND UPPER(RTRIM(demanda_situacao)) = 'FINALIZADO'";
        }

        $sql .= " ORDER BY demanda_prazo ASC, demanda_atualizacao ASC";

        $dados = DB::select($sql);

        return collect($dados);
    }
}
