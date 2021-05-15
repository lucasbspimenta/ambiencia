<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class AgendamentoTipo extends Model
{

    public const VALIDATION_RULES = [
        'nome' => ['required','unique:agendamento_tipos,nome'],
        'situacao' => ['required','boolean'],
        'cor' => ['required','regex:/#[a-zA-Z0-9]{6}/i'],
        'descricao' => ['string'],
    ];

    public const VALIDATION_MESSAGES = [
        'nome.required' => 'Nome é obrigatório',
        'nome.unique' => 'Já existe esse nome cadastrado',
        'situacao.required' => 'Situação é obrigatório',
        'situacao.boolean' => 'Situação inválida',
        'cor.required' => 'Cor é obrigatória',
        'cor.regex' => 'Cor inválida',
        'descricao.string' => 'Descrição deve ser texto',
    ];


    protected $fillable = ['nome','descricao', 'situacao', 'cor'];

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class, 'agendamento_tipos_id', 'id');
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
