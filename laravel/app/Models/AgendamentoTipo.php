<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AgendamentoTipo extends Model
{
    use SoftDeletes;

    public const VALIDATION_RULES = [
        'nome' => ['required'],
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

    public function getNomeFormatadoAttribute()
    {
        return '<span style="width: 13px; height: 11px; margin-right:5px; background-color: '. $this->cor .'" class="d-inline-block align-text-middle"></span>' . $this->nome;
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

        static::deleting(function($model) {
            $model->deleted_by = Auth::id();
        });

    }
}
