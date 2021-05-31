<?php

namespace App\Services;

use App\Models\AgendamentoTipo;
use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AgendamentoTipoService
{
    public function todos()
    {
        return AgendamentoTipo::withCount('agendamentos')->get();;
    }

    public function todosAtivos()
    {
        return AgendamentoTipo::withCount('agendamentos')->where('situacao',1)->get();;
    }

    public function criar(array $data):AgendamentoTipo
    {
        $validator = Validator::make($data,AgendamentoTipo::VALIDATION_RULES, AgendamentoTipo::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return AgendamentoTipo::create($data);
    }

    public function atualizar(array $data, $id)
    {
        $regras = AgendamentoTipo::VALIDATION_RULES;
        $regras['nome'] = ['required','unique:agendamento_tipos,nome,' . $id];

        $validator = Validator::make($data,$regras, AgendamentoTipo::VALIDATION_MESSAGES);
        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $agendamento = AgendamentoTipo::findOrFail($id);

            $data = $validator->validated();
            $agendamento->update($data);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Não foi possivel atualizar o tipo de agendamento: ' . $e->getMessage());
        }

        DB::commit();

        return $agendamento;

    }

    public function findById($id)
    {
        return AgendamentoTipo::findOrFail($id);
    }

    public function existsById($id)
    {
        return AgendamentoTipo::where('id', $id )->exists($id);
    }

    public function excluir($id)
    {
        return AgendamentoTipo::findOrFail($id)->delete();
    }
}
