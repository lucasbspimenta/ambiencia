<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class Agendamento extends Model
{
    public const VALIDATION_RULES = [
        'unidade_id' => ['required','integer','exists:unidades,id'],
        'inicio' => ['required','date_format:d/m/Y'],
        'final' => ['after_or_equal:inicio','date_format:d/m/Y','nullable'],
        'agendamento_tipos_id' => ['required','integer','exists:agendamento_tipos,id'],
        'descricao' => ['string','nullable']
    ];

    public const VALIDATION_MESSAGES = [
        'unidade_id.required' => 'Unidade é obrigatória',
        'unidade_id.integer' => 'Unidade inválida',
        'agendamento_tipos_id.required' => 'Tipo de agendamento é obrigatório',
        'agendamento_tipos_id.integer' => 'Tipo de agendamento inválido',
        'agendamento_tipos_id.exists' => 'Tipo de agendamento informado não existe',
        'inicio.required' => 'Data de início é obrigatória',
        'inicio.date' => 'Data de início não corresponde a um formato válido',
        'inicio.date_format' => 'Data de início não corresponde a um formato válido',
        'final.date' => 'Data de fim não corresponde a um formato válido',
        'final.after_or_equal' => 'Data de fim deve ser maior ou igual que a data inicial',
        'final.date_format' => 'Data de início não corresponde a um formato válido',
        'descricao.string' => 'Descrição deve ser texto',
    ];

    protected $fillable = [
        'descricao'
        , 'inicio'
        , 'final'
        , 'unidade_id'
        , 'agendamento_tipos_id'
    ];

    public function setInicioAttribute($value)
    {
        $this->attributes['inicio'] = (Carbon::canBeCreatedFromFormat($value, 'd/m/Y')) ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : $value;
    }

    public function setFinalAttribute($value)
    {
        $this->attributes['final'] = (Carbon::canBeCreatedFromFormat($value, 'd/m/Y')) ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : $value;
    }

    public function getInicioAttribute($value)
    {
        return (Carbon::canBeCreatedFromFormat($value, 'Y-m-d')) ? Carbon::parse($value)->format('d/m/Y') : $value;
    }

    public function getFinalAttribute($value)
    {
        return (Carbon::canBeCreatedFromFormat($value, 'Y-m-d')) ? Carbon::parse($value)->format('d/m/Y') : $value;
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(AgendamentoTipo::class, 'agendamento_tipos_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

    }
}
