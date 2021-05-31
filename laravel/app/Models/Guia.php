<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Guia extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'checklist_item_id'
        , 'descricao'
    ];

    public static function VALIDATION_RULES($ignore_id=null)
    {
        return [
            'descricao' => ['required','string'],
            'checklist_item_id' => ['required','integer','exists:checklist_items,id', (!is_null($ignore_id) ? 'unique:guias,checklist_item_id,'.$ignore_id : 'unique:guias,checklist_item_id')],
        ];
    }

    public const VALIDATION_MESSAGES = [
        'descricao.required' => 'Descrição é obrigatória',
        'descricao.string' => 'Descrição deve ser texto',
        'checklist_item_id.required' => 'Obrigatório vincular a um item',
        'checklist_item_id.integer' => 'Item informado é inválido',
        'checklist_item_id.exists' => 'Item infomado não existe',
        'checklist_item_id.unique' => 'Já existe guia para este item',
    ];

    public function checklistItem()
    {
        return $this->belongsTo(ChecklistItem::class, 'checklist_item_id');
    }

    public function itens()
    {
        return $this->hasMany(GuiaItem::class);
    }

    public function imagens()
    {
        return $this->morphMany(Imagem::class, 'imageable');
    }

    public static function boot() {

        parent::boot();

        static::deleting(function($model) {
            $model->imagens()->delete();
            $model->itens()->delete();
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
