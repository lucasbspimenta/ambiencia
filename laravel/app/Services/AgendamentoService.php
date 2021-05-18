<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\AgendamentoTipo;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;


class AgendamentoService
{
    public function criar(array $data):Agendamento
    {
        $validator = Validator::make($data,Agendamento::VALIDATION_RULES, Agendamento::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return Agendamento::create($data);
    }

    public function atualizar(array $data, $id)
    {
        $validator = Validator::make($data,Agendamento::VALIDATION_RULES, Agendamento::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $agendamento = Agendamento::findOrFail($id);

            $agendamento->title = $data['descricao'];
            $agendamento->inicio = $data['inicio'];
            $agendamento->final = $data['final'];
            $agendamento->unidade_id = $data['unidade_id'];
            $agendamento->agendamento_tipos_id = $data['agendamento_tipos_id'];

            $agendamento->update();

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Não foi possivel atualizar o agendamento');
        }

        DB::commit();

        return $agendamento;

    }

    public function listaAgendamentosPorTipo(AgendamentoTipo $tipo)
    {

    }
}
