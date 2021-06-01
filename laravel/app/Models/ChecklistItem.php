<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ChecklistItem extends Model
{
    use SoftDeletes;

    public static function VALIDATION_RULES($ignore_id=null)
    {
        return [
            'nome' => ['required'],
            'situacao' => ['required','boolean'],
            'cor' => ['required_without:item_pai_id','regex:/#[a-zA-Z0-9]{6}/i'],
            'descricao' => ['string'],
            'foto' => ['required','in:S,N'],
            'item_pai_id' => ['nullable','integer','exists:checklist_items,id'],
            'ordem' => ['integer'],
        ];
    }

    public const VALIDATION_MESSAGES = [
        'nome.required' => 'Nome é obrigatório',
        'nome.unique' => 'Já existe esse nome cadastrado',
        'situacao.required' => 'Situação é obrigatório',
        'situacao.boolean' => 'Situação inválida',
        'cor.required_without' => 'Cor é obrigatória',
        'cor.regex' => 'Cor inválida',
        'descricao.string' => 'Descrição deve ser texto',
        'foto.required' => 'Informar se a foto é obrigatória',
        'foto.in' => 'Valor para foto obrigatória é inválido',
        'item_pai_id.integer' => 'Item pai é inválido',
        'item_pai_id.exists' => 'Item pai infomado não existe',
        'ordem.integer' => 'Ordem deve ser número',
    ];

    protected $fillable = ['nome','descricao', 'situacao', 'cor','foto', 'item_pai_id'];

    public function getIsMacroitemAttribute()
    {
        return !(bool)$this->item_pai_id;
    }

    public function getCorAttribute()
    {
        return $this->attributes['cor'] ?? $this->macroitem->cor;
    }

    public function subitens()
    {
        return $this->hasMany(ChecklistItem::class, 'item_pai_id', 'id');
    }

    public function macroitem()
    {
        return $this->hasOne(ChecklistItem::class, 'id', 'item_pai_id');
    }

    public function isMacroitem()
    {
        return is_null($this->item_pai_id);
    }

    public function guia()
    {
        return $this->hasOne(Guia::class, 'checklist_item_id','id');
    }

    public function respostaNoChecklist($checklist_id)
    {
        return $this->hasOne(ChecklistResposta::class, 'checklist_item_id', 'id')->where('checklist_id', $checklist_id);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($checklist) {
            $checklist->subitens()->delete();
            $checklist->deleted_by = Auth::id();
            //$checklist->respostas()->delete();
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


