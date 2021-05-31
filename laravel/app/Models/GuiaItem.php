<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class GuiaItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'guia_id'
        , 'pergunta'
        , 'resposta'
        , 'situacao'
    ];

    public static function VALIDATION_RULES($ignore_id=null)
    {
        return [
            'pergunta' => ['required','string'],
            'guia_id' => ['required','integer','exists:guias,id'],
        ];
    }

    public const VALIDATION_MESSAGES = [
        'pergunta.required' => 'Pergunta/Orientação é obrigatória',
        'pergunta.string' => 'Pergunta/Orientação deve ser texto',
        'guia_id.required' => 'Obrigatório vincular a um guia',
        'guia_id.integer' => 'Guia informado é inválido',
        'guia_id.exists' => 'Guia infomado não existe',
    ];

    public function guia()
    {
        return $this->belongsTo(Guia::class);
    }

    public static function boot() {

        parent::boot();

        static::deleting(function($model) {
            $model->deleted_by = Auth::id();
        });

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

    }
}
