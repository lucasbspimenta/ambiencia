<?php

namespace App\Models;

use App\Services\DemandaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Demanda extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = [
        'sistema_id'
        , 'sistema_item_id'
        , 'descricao'
        , 'unidade_id'
        , 'migracao',
    ];

    public const VALIDATION_RULES = [
        'sistema_id' => ['required'],
        'sistema_item_id' => ['required'],
        'descricao' => ['required', 'string'],
        'unidade_id' => ['required', 'integer', 'exists:unidades,id'],

    ];

    public const VALIDATION_MESSAGES = [
        'sistema_id.required' => 'Destino é obrigatório',
        'sistema_item_id.required' => 'Item é obrigatório',
        'descricao.required' => 'Descrição é obrigatória',
        'descricao.string' => 'Descrição deve ser texto',
        'unidade_id.required' => 'Unidade é obrigatória',
        'unidade_id.integer' => 'Unidade inválida',
    ];

    public function respostas()
    {
        return $this->belongsToMany(ChecklistResposta::class, 'demanda_checklist_resposta', 'demanda_id', 'checklist_resposta_id');
    }

    public function respostasDoChecklist($checklist_id)
    {
        return $this->belongsToMany(ChecklistResposta::class, 'demanda_checklist_resposta', 'demanda_id', 'checklist_resposta_id')->where('checklist_id', $checklist_id)->get();
    }

    public function itens()
    {
        return $this->hasManyDeep(
            'App\Models\ChecklistItem',
            ['demanda_checklist_resposta', 'App\Models\ChecklistResposta'],
            [
                'demanda_id',
                'id',
                'id',
            ],
            [
                'id',
                'checklist_resposta_id',
                'checklist_item_id',
            ]
        );
    }

    public function getDadosCompletosAttribute()
    {
        return $this->sistema->nome . ' | ' . $this->sistema_item->nome;
    }

    public function sistema()
    {
        return $this->belongsTo(DemandaSistema::class, 'sistema_id', 'id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id', 'id');
    }

    public function getSistemaItemAttribute()
    {
        return $this->sistema->getItemById($this->sistema_item_id);
    }

    public function getResponsavelAttribute()
    {
        return User::find($this->updated_by);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->respostas()->detach();
        });

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::created(function ($model) {
            if (env('MIGRAR_DEMANDAS') && env('MIGRAR_DEMANDAS') == 1 && $model->migracao == 'P') {
                $demandaService = new DemandaService();
                $demandaService->processa(self::find($model->id));
            }
        });

    }
}
