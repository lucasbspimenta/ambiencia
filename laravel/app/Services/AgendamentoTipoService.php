<?php

namespace App\Services;

use App\Models\AgendamentoTipo;
use Exception;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AgendamentoTipoService
{
    public function salvar(array $data):AgendamentoTipo
    {
        $validator = Validator::make($data,AgendamentoTipo::VALIDATION_RULES, AgendamentoTipo::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->erros()->first());
        }

        DB::beginTransaction();
        try {
            $agendamentoTipo = AgendamentoTipo::updateOrCreate($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Não foi possivel salvar o registro');
        }

        return $agendamentoTipo;
    }
}
