<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Agendamento extends Model
{
    public const VALIDATION_RULES = [
        'unidade_id' => ['required','integer'],
        'inicio' => ['required','date'],
        'final' => ['date'],
        'agendamento_tipos_id' => ['required','integer'],
        'descricao' => ['string']
    ];

    public const VALIDATION_MESSAGES = [
        'agenda.unidade_id.required' => 'Unidade é obrigatória',
        'agenda.unidade_id.integer' => 'Unidade inválida',
        'agenda.agendamento_tipos_id.required' => 'Tipo de agendamento é obrigatório',
        'agenda.agendamento_tipos_id.integer' => 'Tipo de agendamento inválido',
        'agenda.inicio.required' => 'Data de início é obrigatória',
        'agenda.inicio.date' => 'Data de início deve ser do tipo data',
        'agenda.final.date' => 'Data de fim deve ser do tipo data',
        'descricao.string' => 'Descrição deve ser texto',
    ];

    protected $fillable = [
        'descricao'
        , 'inicio'
        , 'final'
        , 'unidade_id'
        , 'agendamento_tipos_id'
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(AgendamentoTipo::class, 'agendamento_tipos_id');
    }
}
